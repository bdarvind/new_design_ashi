<?php
  // error_reporting(E_ALL);
  session_start();

  include '../includes/config.php';
  include '../includes/functions.php';
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

      $txn_id = "STLMT".rand(10000000,99999999);

      
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

#default parameters
$clientId = 'CF243472CBJPB0TG8KNNHT2F0J80';
$clientSecret = '9926c6aa8a3363f59f05ca09cf0bcf338494e8d6';
$env = 'test';


#config objs
$baseUrls = array(
  'prod' => 'https://payout-api.cashfree.com',
  'test' => 'https://payout-gamma.cashfree.com',
);

$urls = array(
  'auth' => '/payout/v1/authorize',
  'requestBatchTransfer' => '/payout/v1/requestBatchTransfer',
  'getBatchTransferStatus' => '/payout/v1/getBatchTransferStatus?batchTransferId=',
);
	
$batchTransfer = array(

  'batchTransferId' => $txn_id,
  'batchFormat' => 'BANK_ACCOUNT',
  'deleteBene' => 1,
  'batch' => [ 
      ["transferId" => $txn_id, 
      "amount" => $amount, 
      "phone" => $user['mobile_number'], 
      "bankAccount" => $user['payout_account_no'], 
      "ifsc" => $user['payout_ifsc'], 
      "email" => $user['email'], 
      "name" => $user['payout_name']] ]


	// "bene_account_number" => $user['payout_account_no'],
  //    "ifsc_code"=> $user['payout_ifsc'],
  //    "recepient_name"=> $user['payout_name'],
  //    "email_id"=> $user['email'],
  //    "mobile_number"=> $user['mobile_number'],
  //    //"otp"=> $_POST['otp'],
	//  "otp"=> "123456",
  //    "debit_account_number"=> "36361626439",
  //    "transaction_types_id"=> 4,
  //    "amount"=> $amount,
  //    "merchant_ref_id"=>$txn_id
);

$header = array(
  'X-Client-Id: '.$clientId,
  'X-Client-Secret: '.$clientSecret, 
  'Content-Type: application/json',
);

$baseurl = $baseUrls[$env];

function create_header($token){
    global $header;
    $headers = $header;
    if(!is_null($token)){
        array_push($headers, 'Authorization: Bearer '.$token);
    }
    return $headers;
}

function post_helper($action, $batchTransfer, $token){
    global $baseurl, $urls;
    $finalUrl = $baseurl.$urls[$action];
    $headers = create_header($token);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $finalUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
    if(!is_null($batchTransfer)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($batchTransfer)); 
    
    $r = curl_exec($ch);
    
    if(curl_errno($ch)){
        print('error in posting');
        print(curl_error($ch));
        die();
    }
    curl_close($ch);
    $rObj = json_decode($r, true);    
    if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: '.$rObj['message']);
    return $rObj;
}

function get_helper($finalUrl, $token){
    $headers = create_header($token);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $finalUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
    
    $r = curl_exec($ch);
    
    if(curl_errno($ch)){
        print('error in posting');
        print(curl_error($ch));
        die();
    }
    curl_close($ch);

    $rObj = json_decode($r, true);    
    if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: '.$rObj['message']);
    return $rObj;
}

#get auth token
function getToken(){
    try{
       $response = post_helper('auth', null, null);
       return $response['data']['token'];
    }
    catch(Exception $ex){
        error_log('error in getting token');
        error_log($ex->getMessage());
        die();
    }

}

#request batch transfer
function requestBatchTransfer($token){
    try{
        global $batchTransfer;
        $response = post_helper('requestBatchTransfer', $batchTransfer ,$token);
        error_log('batch transfer successfully requested');
    }
    catch(Exception $ex){
        error_log('error in requesting batch transfer');
        error_log($ex->getMessage());
        die();
    }
}

#get batch transfer status
function getBatchTransferStatus($token){
  try{
      global $batchTransfer, $baseurl, $urls;
      $finalUrl = $baseurl.$urls['getBatchTransferStatus'].$batchTransfer['batchTransferId'];
      $response = get_helper($finalUrl, $token);
      error_log(json_encode($response));
  }
  catch(Exception $ex){
      error_log('error in getting batch transfer status');
      error_log($ex->getMessage());
      die();
  }



	 //print_r($data);die;
//transaction_status_id 
// 15/103/4 Success
//16 Pending
//17 Failure
//18 Suspect
//19 Canceled
//20 Processing
//21 Pending for Processing
// 37/58/63/37  The transaction failed because IFSC code is not supported for transactions or Invalid. This means either the beneficiary branch is new or not supported by ICICI for transactions
	 $curl = curl_init();
	 //$bankopen_key	 
	  curl_setopt_array($curl, [
	  //CURLOPT_URL => "https://sandbox.bankopen.co/v1/payouts",
	  CURLOPT_URL => "https://payout-api.cashfree.com/payout/v1/requestTransfer",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => $batchTransfer,
	  CURLOPT_HTTPHEADER => [
    'X-Client-Id: '.$clientId,
    'X-Client-Secret: '.$clientSecret,
		// "Accept: application/json",
		// "Authorization: Bearer ".$bankopen_key_live,
		"Content-Type: application/json"
	  ],
	]);

	$response = curl_exec($curl);
	curl_close($curl);
	//print_r($response);die;
	$newresponse = json_decode($response,true);	  
	
	  log_data(json_encode($newresponse),'CHECK_DOUBLE_TXN');

      
	  //if($newresponse['status']==200 && $newresponse['data']['bank_error_message']==null)
	if($newresponse['status']==200)
	  {
	  
	    log_data("Come under Success",'CHECK_DOUBLE_TXN');
          
          
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
      }



    }
  }

}else{
    log_data("ALERT - Direct Request, User - ".json_encode($user),'CHECK_DOUBLE_TXN');
  $return['status'] = "error";
  $return['msg'] = "Error Occured, Try After sometime";
}



#main execution
$token = getToken();
requestBatchTransfer($token);
getBatchTransferStatus($token);

echo json_encode($return);

?>
