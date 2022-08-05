<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_POST['email']) && isset($_POST['mobile_number'])) {
    $member_name = escape($_POST['member_name']);
    $member_father_name = escape($_POST['member_father_name']);
    $mobile_number = escape($_POST['mobile_number']);
    $email = escape($_POST['email']);
    $aadhaar_number = escape($_POST['aadhaar_number']);
    $pan_card = escape($_POST['pan_card']);
    $address = escape($_POST['address']);
    $date_of_birth = escape($_POST['date_of_birth']);
    $pin_code = escape($_POST['pin_code']);

    $amount = 110;
  if ($csp_data['account_balance'] < $amount) {
      echo "insufficient_balance";
  }elseif($csp_data['account_balance'] <= $csp_data['account_limit']){
        echo "insufficient_balance";
  }elseif($amount > $csp_data['account_balance']-$csp_data['account_limit']){
        echo "insufficient_balance";
  }elseif ($csp_data['status'] == 'disabled') {
    api_response(200,"csp_disabled","","");
  }else{
    if (check_email($email,'net_banking_users') == "yes") {
      api_response(200,"email_exist","","");
    }elseif (check_pan($pan_card,'net_banking_users') == "yes") {
      api_response(200,"pan_exist","","");
    }elseif (check_aadhaar($aadhaar_number,'net_banking_users') == "yes") {
      api_response(200,"aadhaar_exist","","");
    }elseif (check_mobile_number($mobile_number,'net_banking_users') == "yes") {
      api_response(200,"mobile_exist","","");
    }else{
      $data = array(
        'csp_id_fk' => $csp_id,
        'member_name' => $member_name,
        'member_father_name' => $member_father_name,
        'mobile_number' => $mobile_number,
        'email' => $email,
        'aadhaar_number' => $aadhaar_number,
        'pan_card' => $pan_card,
        'address' => $address,
        'date_of_birth' => $date_of_birth,
        'pin_code' => $pin_code,
        'account_status' => 'active',
        'account_balance' => 0,
        'created_on' => format_time(get_time())
      );
      $insert = run_query(insert('net_banking_users',$data));
      if($insert){
          $update_admin = run_query("UPDATE admin SET wallet=wallet-10");
        $deduct_member_fees = run_query("UPDATE `csp` SET account_balance=account_balance-10 WHERE id='$csp_id'");
        
        if($deduct_member_fees){
            
            $nb_id_fk = select('net_banking_users','mobile_number',$mobile_number)['id'];
            
            $csp_wallet_data = array(
                'csp_id_fk' => $csp_id,
                'nb_id_fk' => $nb_id_fk,
                'type' => 'DEBIT',
                'txn_id' => RAND(10000000,99999999),
                'amount' => 10,
                'status' => 'success',
                'purpose' => 'add_member_fee',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
            
        $message = "Dear Customer, Welcome to ".$site_name.", Your CRN No. is ".$crn_code."".$mobile_number;
          send_sms($mobile_number,$message,4,'GOCASH');
          api_response(200,"success","",array('member_id'=> $crn_code.$mobile_number));
        }else{
          api_response(200,"error","","");
        }
      }else{
        api_response(200,"error","","");
      }
    }
  }
}

?>
