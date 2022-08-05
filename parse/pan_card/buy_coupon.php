<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

$amount = 214;

if ($csp_data['account_balance'] < $amount) {
    
    $return['status'] = "insufficient_balance";
    $return['status_msg'] = "Insufficient Balance, Please Add funds to your OD account to purchase pan coupons";

    
}elseif($csp_data['account_balance'] <= $csp_data['account_limit']){
    
    $return['status'] = "insufficient_balance";
    $return['status_msg'] = "Insufficient Balance, Please Add funds to your OD account to purchase pan coupons";

    
}elseif($amount > $csp_data['account_balance']-$csp_data['account_limit']){
    
    $return['status'] = "insufficient_balance";
    $return['status_msg'] = "Insufficient Balance, Please Add funds to your OD account to purchase pan coupons";

    
}else{

    $txn_id = rand(10000000,99999999);
    
    $curl = curl_init();
    
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://api.rechapi.com/recharge.php?format=json&token='.$rech_api_key.'&mobile='.$csp_data['mobile_number'].'&opid=170&urid='.$txn_id.'&opvalue1=2',
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
    
    if($response['data']['status'] == "FAILED"){
        $return['status'] = "failed";
        $return['status_msg'] = "Error Occured while Processing your request, Try Again";
        
    }else{
        $return['status'] = "pending";
        $return['status_msg'] = "Your Request is processing, please check your PSA Portal in 30 min. to know status of coupons";
        
            
            $update_csp = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
            $txn_id = RAND(10000000,99999999);
              $csp_wallet_data = array(
                'csp_id_fk' => $csp_id,
                'type' => 'DEBIT',
                'txn_id' => $response['data']['orderId'],
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'pan_card',
                'note' => '2 Pan Coupon',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
        
    }
}

echo json_encode($return);
