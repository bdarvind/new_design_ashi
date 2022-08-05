<?php
  // error_reporting(E_ALL);
  session_start();

  include '../include/config.php';
  include '../include/functions.php';
  //echo format_time(get_time(),"M,d,Y");die;
$user = select('csp','id',$_SESSION['csp']);

if (!empty($_POST['amount']) && is_numeric($_POST['amount'])) {
    $csp_id = $user['id'];
    $amount = $_POST['amount'];
    $user_balance = $user['account_balance'];
    $withdrawable_balance = $user_balance-$user['account_limit'];

    if ($amount < 100) {
      $return['status'] = "min_threshold";
      $return['msg'] = "Minimum Withdraw Amount Should be greater than ₹100". $withdrawable_balance;      
    }elseif ($amount > $withdrawable_balance) {
      $return['status'] = "invalid_amount";
      $return['msg'] = "You Can only Withdraw upto ₹".$withdrawable_balance;
    }else{
      //coding here
      $txn_id = "STLMT".rand(10000000,99999999);

      //commission check
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
    //commission check end


//code for api
    $data = json_encode(array(
  "bene_account_number" => $user['payout_account_no'],
     "ifsc_code"=> $user['payout_ifsc'],
     "recepient_name"=> $user['payout_name'],
     "email_id"=> $user['email'],
     "mobile_number"=> $user['mobile_number'],
     //"otp"=> $_POST['otp'],
   "otp"=> "123456",
     "debit_account_number"=> "36361626439",
     "transaction_types_id"=> 4,
     "amount"=> $amount,
     "merchant_ref_id"=>$txn_id
   ));

   /*$data = json_encode(array(
  "bene_account_number" => '8645675515',
     "ifsc_code"=> 'KKBK0004601',
     "recepient_name"=> 'Pankaj Kumar',
     "email_id"=> $user['email'],
     "mobile_number"=> $user['mobile_number'],
     //"otp"=> $_POST['otp'],
   "otp"=> "123456",
     "debit_account_number"=> "36361626439",
     "transaction_types_id"=> 4,
     "amount"=> $amount,
     "merchant_ref_id"=>$txn_id
   ));*/
    //print_r($data);die;

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

  $response = curl_exec($curl);
  curl_close($curl);
  $newresponse = json_decode($response,true);  
  /*print_r("1 ".$newresponse);
   print_r("2 ".$response);  
   1 Array2 
{"data":{"amount":"100",
"open_transaction_ref_id":46110073,
"transaction_status_id":1,
"transaction_types_id":4,
"purpose":null,
"recepient_name":"Pankaj Kumar",
"email_id":"pkprabhakardeveloper@gmail.com",
"mobile_number":"9990892677",
"merchant_ref_id":"STLMT77491839",
"debit_account_number":"36361626439",
"bank_error_message":null,
"bank_transaction_ref_id":null,
"created_at":"16-03-2022 00:14:20"},
"status":200}
null
*/
  log_data(json_encode($newresponse),'CHECK_DOUBLE_TXN');
  if(($newresponse['status']==200) && ($newresponse['transaction_status_id']==15 || $newresponse['transaction_status_id']==103 || $newresponse['transaction_status_id']==4))
  {
      $return['status'] = "payment_success";
      $return['msg'] = "Your settlement has been completed";

  }


// end api code
    }




  }else{
      $return['status'] = "data_notset";
      $return['msg'] = "Enter Valid Amount";
  }
  echo json_encode($return);
?>
