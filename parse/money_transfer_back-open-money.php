<?php
ignore_user_abort(true);

  // error_reporting(E_ALL);
  session_start();

  include '../include/config.php';
  include '../include/functions.php';
$user = select('csp','id',$_SESSION['csp']);

if (isset($_POST['amount']) && isset($_POST['account_number']) && isset($_POST['ifsc_code']) && isset($_POST['beneficiary_name'])) {
    $csp_id = $user['id'];
    $amount = $_POST['amount'];
    $account_number = $_POST['account_number'];
    $ifsc_code = $_POST['ifsc_code'];
    $beneficiary_name = $_POST['beneficiary_name'];

    if($user['account_balance'] <= $user['account_limit']){
      $withdrawable_balance = 0;
    }else{
      $withdrawable_balance = $user['account_balance']-$user['account_limit'];
    }

    if ($withdrawable_balance < 1) {
      $return['status'] = "invalid_amount";
      $return['msg'] = "Insufficient Balance, You have to maintain ₹10,000 in your OD Account, hence you cannot initiate this request at the moment";

    }else if ($amount > $withdrawable_balance) {
      $return['status'] = "invalid_amount";
      $return['msg'] = "You Can only Withdraw upto ₹".$withdrawable_balance;
    }else{
		
		

      $txn_id = "DMT".rand(10000000,99999999);
	  $dmtsql = mysqli_query($conn,"select * from csp_commission where csp_id='".$csp_id."' and commission_type='dmt'");
          if(mysqli_num_rows($dmtsql)==0)
          {
			if($amount >= 1 && $amount <= 1000){
              $deduct = 7;              
          }else if($amount >= 1001 && $amount <= 2000){
              $deduct = 13;              
          }else if($amount >= 2001 && $amount <= 3000){
              $deduct = 15;              
          }else if($amount >= 3001 && $amount <= 4000){
              $deduct = 19;              
          }else if($amount >= 4001 && $amount <= 5000){
              $deduct = 21;			  
		  }else{
              $deduct = 23;
          }

        }else{// found commission

          foreach($dmtsql as $dmtrow)
          {
            if($amount >= $dmtrow['slab_from'] && $amount <= $dmtrow['slab_to']){
              $deduct = $dmtrow['commission'];
              //$gst = $deduct/100*18;
            }else{
				
              if($amount >= 1 && $amount <= 1000){
                $deduct = 7;              
            }else if($amount >= 1001 && $amount <= 2000){
                $deduct = 13;              
            }else if($amount >= 2001 && $amount <= 3000){
                $deduct = 15;              
            }else if($amount >= 3001 && $amount <= 4000){
                $deduct = 19;              
            }else if($amount >= 4001 && $amount <= 5000){
                $deduct = 21;			  
        }else{
                $deduct = 23;
            }
			}
          }
		  
		}
	  
		  
	$data = json_encode(array(
	"bene_account_number" => $account_number,
     "ifsc_code"=> $ifsc_code,
     "recepient_name"=> $beneficiary_name,
     "email_id"=> "ashidigitalpayment@gmail.com",
     "mobile_number"=> "9837078320",
     //"otp"=> $_POST['otp'],
	 "otp"=> "123456",
     "debit_account_number"=> "36361626439",
     "transaction_types_id"=> 4,
     "amount"=> $amount,
     "merchant_ref_id"=>$txn_id
	 ));
	
	 $curl = curl_init(); 
	  curl_setopt_array($curl, [
	  CURLOPT_URL => "https://v2-api.bankopen.co/v1/payouts",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => $data,
	  CURLOPT_HTTPHEADER => [
		"Accept: application/json",
		"Authorization: Bearer ".$bankopen_key_live,
		"Content-Type: application/json"
	  ],
	]);
      /*$data = json_encode(array(
        "amount" => $amount,
        "payment_type" => "IMPS",
        "ifsc" => $ifsc_code,
        "number" => $account_number,
        "note" => "Domestic Money Transfer by ".$user['name'],
        "udf1" => "csp",
        "udf2" => $csp_id,
        "udf3" => $user['mobile_number'],
        "beneficiary_name" => $beneficiary_name,
        "reference_number" => $txn_id
      ));

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://partners.hypto.in/api/transfers/initiate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_HTTPHEADER => array(
          "Authorization: ".$hypto_api_key,
          "Content-Type: application/json"
        ),
      ));*/

      $response = curl_exec($curl);
	  
      curl_close($curl);
      $response = json_decode($response,true);
      //print_r($response);
      $return['status'] = $response;
	//print_r($response);
      //if($response['success'] == true){
        if($response['status']==200 && $response['data']['bank_error_message']==null){
         $gst = 0;
//$deduct_balance = $amount+$deduct+$gst;
		$deduct_balance = $amount+$deduct;
        //Update CSP Wallet TXN
        $csp_wallet_data = array(
            'csp_id_fk' => $csp_id,
            'type' => 'DEBIT',
            'txn_id' => $txn_id,
            'amount' => $amount,
            'fees' => $deduct,
            'gst' => $gst,
            'status' => 'success',
            'purpose' => 'money_transfer',
            'bene_account_no' => $account_number,
            'bene_ifsc_code' => $ifsc_code,
            'bene_name' => $beneficiary_name,
            'created_on' => format_time(get_time())
        );
        $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
        if ($insert_csp_txn) {
    
    //start distributor
    $dist_commission = 1;
    $select_dist = run_query("SELECT * FROM csp WHERE id='$csp_id'");
    $rowuser = mysqli_fetch_array($select_dist);
    //$distributor_commission = $amount*1/100+$amount;
    $rech_report_data_disb = array(
        'csp_id' => $csp_id,
        'distributor_id' => $rowuser['distributor_id'],
        'commission' => $dist_commission,
        'commission_type' => 'recharge',
        'amount' => $amount,                    
        'addon_date' => format_time(get_time())
    );
    $insert_dist_comm = run_query(insert('distributor_commission_transaction',$rech_report_data_disb));

    $update_dist = run_query("UPDATE distributor SET account_balance=account_balance+'$dist_commission' WHERE id='".$rowuser['distributor_id']."'");
    //end distributor
            $insert_money_transfer_data = array(
                "reason" => "Money Transfer",
                "account_number" => $account_number,
                "account_ifsc" => $ifsc_code,
                "upi_id" => "",
                "beneficiary_name" => $beneficiary_name,
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
            
            $message = "Rs. ".$amount." debited from your account ".mask_number($account_number)." on ".$date." with Ref. No.".$txn_id;
            send_sms($nb_user['mobile_number'],$message,4,'GOCASH');
              
            $return['status'] = "success";
            $return['msg'] = "Transfer Successfull, Transaction ID - ".$txn_id;
          }
        }
      }else{//response
	  $amount = 0;
	  $deduct = 0;
	  $gst = 0;
		  $deduct_balance = $amount+$deduct;

      //start distributor
    $dist_commission = 1;
    $select_dist = run_query("SELECT * FROM csp WHERE id='$csp_id'");
    $rowuser = mysqli_fetch_array($select_dist);
    //$distributor_commission = $amount*1/100+$amount;
    $rech_report_data_disb = array(
        'csp_id' => $csp_id,
        'distributor_id' => $rowuser['distributor_id'],
        'commission' => $dist_commission,
        'commission_type' => 'recharge',
        'amount' => $amount,                    
        'addon_date' => format_time(get_time())
    );
    $insert_dist_comm = run_query(insert('distributor_commission_transaction',$rech_report_data_disb));

    $update_dist = run_query("UPDATE distributor SET account_balance=account_balance+'$dist_commission' WHERE id='".$rowuser['distributor_id']."'");
    //end distributor
    
        //Update CSP Wallet TXN
        $csp_wallet_data = array(
            'csp_id_fk' => $csp_id,
            'type' => 'DEBIT',
            'txn_id' => $txn_id,
            'amount' => $amount,
            'fees' => $deduct,
            'gst' => $gst,
            'status' => 'failed',
            'purpose' => 'money_transfer',
            'bene_account_no' => $account_number,
            'bene_ifsc_code' => $ifsc_code,
            'bene_name' => $beneficiary_name,
            'created_on' => format_time(get_time())
        );
        $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
        if ($insert_csp_txn) {
            
            $insert_money_transfer_data = array(
                "reason" => "Money Transfer",
                "account_number" => $account_number,
                "account_ifsc" => $ifsc_code,
                "upi_id" => "",
                "beneficiary_name" => $beneficiary_name,
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
            
            $message = "Rs. ".$amount." debited from your account ".mask_number($account_number)." on ".$date." with Ref. No.".$txn_id;
            send_sms($nb_user['mobile_number'],$message,4,'GOCASH');
              
            $return['status'] = "success";
            $return['msg'] = "Transfer Successfull, Transaction ID - ".$txn_id;
          }
        }
		
        $return['status'] = "server_error";
        $return['msg'] = "Bank Server Down, Try Again after sometime";
        $return['hypto'] = $response;
      }
        }//resoponse
          
        



    

}else{
  $return['status'] = "error";
  $return['msg'] = "Error Occured, Try After sometime";
}

echo json_encode($return);

?>
