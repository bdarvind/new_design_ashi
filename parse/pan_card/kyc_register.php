<?php

include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);


if(isset($_POST['name']) && isset($_POST['shop_name']) && isset($_POST['address']) && isset($_POST['pin_code']) && isset($_POST['state_code']) && isset($_POST['email']) && isset($_POST['pan']) && isset($_POST['dob']) && isset($_POST['aadhaar']) && isset($_POST['otp'])){
    extract($_POST);
    
    $new_dob = date("Y-m-d", strtotime($dob)); 
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://api.rechapi.com/kyc/kyc.php?format=json&token='.$rech_api_key.'&kycMobile='.$csp_data['mobile_number'].'&customerName='.urlencode($name).'&shopName='.urlencode($shop_name).'&address='.urlencode($address).'&pincode='.urlencode($pin_code).'&stateCode='.urlencode($state_code).'&email='.urlencode($email).'&pan='.urlencode($pan).'&dob='.urlencode($new_dob).'&aadhaar='.urlencode($aadhaar).'&otp='.urlencode($otp),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    
    $response = json_decode(curl_exec($curl),true);
    
    curl_close($curl);
    
    if(is_array($response['data']['resText'])){
        $res_text = $response['data']['resText'][0];
    }else{
        $res_text = $response['data']['resText'];
    }
    
    if($response['data']['status'] == "SUCCESS"){
        
        // $amount = 25;
        // $update_csp = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
        // $txn_id = RAND(10000000,99999999);
        //   $csp_wallet_data = array(
        //     'csp_id_fk' => $csp_id,
        //     'type' => 'DEBIT',
        //     'txn_id' => $txn_id,
        //     'amount' => $amount,
        //     'status' => 'success',
        //     'purpose' => 'pan_card',
        //     'note' => 'Pan Card KYC',
        //     'created_on' => format_time(get_time())
        // );
        // $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
        
        $return['status'] = "success";
        $return['status_msg'] = "KYC is registered successfully";
    }else{
        $return['status'] = "error";
        $return['status_msg'] = $res_text;
    }
    
    
    
}else{
    $return['status'] = "error";
    $return['status_msg'] = "Invalid Request";
}

echo json_encode($return);
