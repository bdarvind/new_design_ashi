<?php

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

/*
Below is an integration flow on how to use Cashfree's payouts.
Please go through the payout docs here: https://dev.cashfree.com/payouts
The following script contains the following functionalities :
    1.getToken() -> to get auth token to be used in all following calls.
    2.getBeneficiary() -> to get beneficiary details/check if a beneficiary exists
    3.createBeneficiaryEntity() -> to create beneficiaries
    4.requestTransfer() -> to create a payout transfer
    5.getTransferStatus() -> to get payout transfer status.
All the data used by the script can be found in the below assosciative arrays. This includes the clientId, clientSecret, Beneficiary object, Transaction Object.
You can change keep changing the values in the config file and running the script.
Please enter your clientId and clientSecret, along with the appropriate enviornment, beneficiary details and request details
*/

#default parameters
$clientId = 'CF243472CBJPB0TG8KNNHT2F0J80';
$clientSecret = '9926c6aa8a3363f59f05ca09cf0bcf338494e8d6';
$env = 'prod';

#config objs
$baseUrls = array(
    'prod' => 'https://payout-api.cashfree.com',
    'test' => 'https://payout-gamma.cashfree.com',
);
$urls = array(
    'auth' => '/payout/v1/authorize',
    'getBene' => '/payout/v1/getBeneficiary/',
    'addBene' => '/payout/v1/addBeneficiary',
    'requestTransfer' => '/payout/v1/requestTransfer',
    'getTransferStatus' => '/payout/v1/getTransferStatus?transferId='
);
$beneficiary = array(
    'beneId' => $user['beneficiary_id'],
    'name' => $user['payout_name'],
    'email' => $user['email'],
    'phone' => $user['mobile_number'],
    'bankAccount' => $user['payout_account_no'],
    'ifsc' => $user['payout_ifsc'],
    'address1' => 'Fatehpur',
    'city' => 'Fatehpur',
    'state' => 'Uttar Pradesh',
    'pincode' => '212601',
);
$transfer = array(
    'beneId' => $user['beneficiary_id'],
    'amount' => $amount,
    'transferId' => $txn_id,
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

function post_helper($action, $data, $token){
    global $baseurl, $urls;
    $finalUrl = $baseurl.$urls[$action];
    $headers = create_header($token);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $finalUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
    if(!is_null($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    
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

#get beneficiary details
function getBeneficiary($token){
    try{
        global $baseurl, $urls, $beneficiary;
        $beneId = $beneficiary['beneId'];
        $finalUrl = $baseurl.$urls['getBene'].$beneId;
        $response = get_helper($finalUrl, $token);
        return true;
    }
    catch(Exception $ex){
        $msg = $ex->getMessage();
        if(strstr($msg, 'Beneficiary does not exist')) return false;
        error_log('error in getting beneficiary details');
        error_log($msg);
        die();
    }    
}

#add beneficiary
function addBeneficiary($token){
    try{
        global $beneficiary;
        $response = post_helper('addBene', $beneficiary, $token);
        error_log('beneficiary created');
    }
    catch(Exception $ex){
        $msg = $ex->getMessage();
        error_log('error in creating beneficiary');
        error_log($msg);
        die();
    }    
}

#request transfer
function requestTransfer($token){
    try{
        global $transfer;
        $response = post_helper('requestTransfer', $transfer, $token);
        error_log('transfer requested successfully');
    }
    catch(Exception $ex){
        $msg = $ex->getMessage();
        error_log('error in requesting transfer');
        error_log($msg);
        die();
    }
}

#get transfer status
function getTransferStatus($token){
    try{
        global $baseurl, $urls, $transfer;
        $transferId = $transfer['transferId'];
        $finalUrl = $baseurl.$urls['getTransferStatus'].$transferId;
        $response = get_helper($finalUrl, $token);
        error_log(json_encode($response));
    }
    catch(Exception $ex){
        $msg = $ex->getMessage();
        error_log('error in getting transfer status');
        error_log($msg);
        die();
    }
}

#main execution
$token = getToken();
if(!getBeneficiary($token)) addBeneficiary($token);
requestTransfer($token);
getTransferStatus($token);
    }
}
?> 