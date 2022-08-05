<?php
  // error_reporting(E_ALL);
  session_start();

  include '../include/config.php';
  include '../include/functions.php';
  //echo format_time(get_time(),"M,d,Y");die;
$user = select('csp','id',$_SESSION['csp']);
if (isset($_POST['amount']) && isset($_POST['csrf_token'])) {
    $csp_id = $user['id'];
    $amount = $_POST['amount'];
    //$withdrawable_balance = encryption('decrypt',$_POST['csrf_token']); //commented on 16112021
  //start
    $user_balance = $user['account_balance'];
    $withdrawable_balance = $user_balance-$user['account_limit'];
    //end
    if ($amount > $withdrawable_balance) {
      $return['status'] = "invalid_amount";
      $return['msg'] = "You Can only Withdraw upto ₹".$withdrawable_balance;
    }elseif ($amount < 100) {
      $return['status'] = "min_threshold";
      $return['msg'] = "Minimum Withdraw Amount Should be greater than ₹100";
    }else{

      //$txn_id = "STLMT".rand(10000000,99999999);

      
	  //new api
    $payoutsql = mysqli_query($conn,"select * from csp_commission where csp_id='".$csp_id."' and commission_type='payout'");
    if(mysqli_num_rows($payoutsql)==0)
    {
	  if($amount >= 100 && $amount <= 100000){
              $deduct = 5;
              //$gst = $deduct/100*18;
              $gst = 0;
          }elseif($amount >= 25001 && $amount <= 200000){
              $deduct = 8;
              //$gst = $deduct/100*18;
              $gst = 0;
          }else{
			  $deduct = 10;
              //$gst = $deduct/100*18;
              $gst = 0;
		  }
    }else{
      foreach($payoutsql as $payoutrow)
      {
        if($amount >= $payoutrow['slab_from'] && $amount <= $payoutrow['slab_to']){
          $deduct = $payoutrow['commission'];
          //$gst = $deduct/100*18;
          $gst = 0;
        }
      }
    }

    $data = array(
     "beneficiary_name"=> $user['payout_name'],
     "account_number"=> $user['payout_account_no'],
     "ifsc_code"=>$user['payout_ifsc'],
     "udf1"=>"",
     "udf2"=>"",
     "udf3"=>"",
     "amount"=> $amount,
     "payment_type" => "IMPS",
     "note" => $amount." Creadit in ".$user['payout_account_no'],
   );

  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://argbanking.com/v1/api/transfer/initiate',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $data,
  CURLOPT_HTTPHEADER => array(
    'x-api-key: cfa8dc8ca6b0a2b94fd25461c7bf5c'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$responsedata = json_decode($response,true);
//print_r($responsedata);
//echo $response;
	if(($responsedata['status_code']==200 && $responsedata['status']=="success") && ($responsedata['data']['status']=="PENDING" || $responsedata['data']['status']=="SUCCESS" ))
	  {
	  
	    log_data("Come under Success",'CHECK_DOUBLE_TXN');
          
          //$txn_id = $responsedata['data']['txn_id_fk'];
          $txn_id = $responsedata['data']['txn_id'];

            $deduct_balance = $amount+$deduct+$gst;
            //Update CSP Wallet TXN
            $csp_wallet_data = array(
                'csp_id_fk' => $csp_id,
                'type' => 'DEBIT',
                'txn_id' => $txn_id,
                'amount' => $amount,
                'fees' => $deduct,
                'gst' => $gst,
                'status' => 'success',
                'purpose' => 'settlement',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
            if ($insert_csp_txn) {
                
                $insert_money_transfer_data = array(
                    "reason" => "Settlement",
                    "account_number" => $user['payout_account_no'],
                    "account_ifsc" => $user['payout_ifsc'],
                    "upi_id" => "",
                    "beneficiary_name" => $user['payout_name'],
                    "note" => "Domestic Money Transfer by ".$user['name'],
                    "udf1" => "csp",
                    "udf2" => $csp_id,
                    "udf3" => $user['name'],
                    "account_holder" => "",
                    "amount" => $amount,
                    "charges_gst" => 0,
                    "settled_amount" => $amount,
                    "txn_time" => format_time(get_time()),
                    "created_at" => format_time(get_time()),
                    "payment_type" => "IMPS",
                    "bank_ref_num" => "",
                    "reference_number" => $txn_id,
                    "status" => "PENDING",
                    "txn_type" => "Bank Transfer"
                );
                $insert_money_transfer = run_query(insert('money_transfer',$insert_money_transfer_data));
                
              //update User Wallet
              $csp_update = run_query("UPDATE csp SET account_balance=account_balance-'$deduct_balance' WHERE id='$csp_id'");
              $update_admin = run_query("UPDATE admin SET wallet=wallet-'$deduct_balance'");
              if($csp_update){
                  
                $date = format_time(get_time(),"M,d,Y");
                $bank = $user['bank'];
                $user_id = $user['id'];
                $get_bank = mysqli_fetch_assoc(run_query("SELECT $bank FROM virtual_account WHERE user_type ='csp' AND user_id='$user_id' "));
                $bank_details = unserialize($get_bank[$bank]);
                $account_number = $bank_details['account_number'];
                
                $message = "Rs. ".$amount." debited from your account ".mask_number($account_number)." on ".$date." has been settled to your Account with Ref.No. ".$txn_id;
                send_sms($nb_user['mobile_number'],$message,4,'GOCASH');
                  
                $return['status'] = "success";
                $return['msg'] = "Transfer Successfull, Transaction ID - ".$txn_id;
              }
            }
          }else{
            $return['status'] = "server_error";
            $return['msg'] = "Bank Server Down, Try Again after sometime";
          }
      /*}else{
          
          log_data("Come under Failed",'CHECK_DOUBLE_TXN');
          
		  $amount = 0;
		  $deduct=0;
		  $gst = 0;
		
		
    		$deduct_balance = $amount+$deduct+$gst;
            //Update CSP Wallet TXN
            $csp_wallet_data = array(
                'csp_id_fk' => $csp_id,
                'type' => 'DEBIT',
                'txn_id' => $txn_id,
                'amount' => $amount,
                'fees' => $deduct,
                'gst' => $gst,
                'status' => 'Failed',
                'purpose' => 'settlement',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
            if ($insert_csp_txn) {
                
                $insert_money_transfer_data = array(
                    "reason" => "Settlement",
                    "account_number" => $user['payout_account_no'],
                    "account_ifsc" => $user['payout_ifsc'],
                    "upi_id" => "",
                    "beneficiary_name" => $user['payout_name'],
                    "note" => "Domestic Money Transfer by ".$user['name'],
                    "udf1" => "csp",
                    "udf2" => $csp_id,
                    "udf3" => $user['name'],
                    "account_holder" => "",
                    "amount" => $amount,
                    "charges_gst" => 0,
                    "settled_amount" => $amount,
                    "txn_time" => format_time(get_time()),
                    "created_at" => format_time(get_time()),
                    "payment_type" => "IMPS",
                    "bank_ref_num" => "",
                    "reference_number" => $txn_id,
                    "status" => "PENDING",
                    "txn_type" => "Bank Transfer"
                );
                $insert_money_transfer = run_query(insert('money_transfer',$insert_money_transfer_data));
                
                //update User Wallet
                $csp_update = run_query("UPDATE csp SET account_balance=account_balance-'$deduct_balance' WHERE id='$csp_id'");
                $update_admin = run_query("UPDATE admin SET wallet=wallet-'$deduct_balance'");
                if($csp_update){
                  
                    $date = format_time(get_time(),"M,d,Y");
                    $bank = $user['bank'];
                    $user_id = $user['id'];
                    $get_bank = mysqli_fetch_assoc(run_query("SELECT $bank FROM virtual_account WHERE user_type ='csp' AND user_id='$user_id' "));
                    $bank_details = unserialize($get_bank[$bank]);
                    $account_number = $bank_details['account_number'];
                    
                    $message = "Rs. ".$amount." debited from your account ".mask_number($account_number)." on ".$date." has been settled to your Account with Ref.No. ".$txn_id;
                    send_sms($nb_user['mobile_number'],$message,4,'GOCASH');
                      
                    $return['status'] = "success";
                    $return['msg'] = "Transfer Successfull, Transaction ID - ".$txn_id;
                }
            }
      
	  
        $return['status'] = "server_error";
        $return['msg'] = "Bank Server Down, Try Again after sometime";
      }*/
      //end



    }

}else{
    log_data("ALERT - Direct Request, User - ".json_encode($user),'CHECK_DOUBLE_TXN');
  $return['status'] = "error";
  $return['msg'] = "Error Occured, Try After sometime";
}

echo json_encode($return);

?>
