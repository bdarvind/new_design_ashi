<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if(isset($_POST['account_number']) && isset($_POST['ifsc_code']) && $csp_id !== ""){
  $txn_id = 'BVERIFY'.rand(100000,999999);
  $payload = array(
    'number' => $_POST['account_number'],
    'ifsc' => $_POST['ifsc_code'],
    'reference_number' => $txn_id
  );
  
    $log_data = array(
        'csp_id' => $csp_id,
        'number' => $_POST['account_number'],
        'ifsc' => $_POST['ifsc_code'],
        'reference_number' => $txn_id
    );
  
  log_data(json_encode($log_data),'VERIFY_BANK');

    if($csp_data['account_balance'] < 5){
        $return['status'] = "insufficient_balance";
    }elseif(5 > $csp_data['account_balance']-$csp_data['account_limit']){
        $return['status'] = "insufficient_balance";
    }else{

      $curl = curl_init();
    
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://partners.hypto.in/api/verify/bank_account",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => array(
          "Authorization: ".$hypto_api_key,
          "Content-Type: application/json"
        ),
      ));
    
      $response = curl_exec($curl);
    
      curl_close($curl);
      $data = json_decode($response,true);
    
      if ($data['success'] == true) {
    
        if($data['data']['status'] == "COMPLETED"){
            
            $csp_wallet_data = array(
            'csp_id_fk' => $csp_id,
            'type' => 'DEBIT',
            'txn_id' => $txn_id,
            'amount' => 1,
            'fees' => 2,
            'gst' => 0.54,
            'status' => 'success',
            'purpose' => 'bank_verify',
            'bene_account_no' => $_POST['account_number'],
            'bene_ifsc_code' => $_POST['ifsc_code'],
            'bene_name' => $data['data']['verify_account_holder'],
            'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
        
            $fees = 3.54;
            $update_csp = $update_csp = run_query("UPDATE csp SET account_balance=account_balance-'$fees' WHERE id='$csp_id'");
            $update_admin = run_query("UPDATE admin SET wallet=wallet-'$fees'");
        
            if($update_csp){
              $return['status'] = "success";
              $return['data'] = $data['data'];
            }
        }else{
            $return['status'] = "error";
        }
    
      }else{
        if ($data['reason'] == "Balance Insufficient") {
          $return['status'] = "server_error";
        }else{
          $return['status'] = "error";
        }
      }
  
    }
  
}else{
  $return['status'] = "error";
}

echo json_encode($return);
