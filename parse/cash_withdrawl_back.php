<?php
ignore_user_abort(true);

include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_GET['type'])) {


    if($_GET['type'] == "initiate"){
      $nb_id = $_POST['nb_id'];
      $amount = $_POST['amount'];
    
        if($amount < 500){
            echo "min_hundred_error";
            exit();
        }else{

          $nb_user_data = select('net_banking_users', 'id', $nb_id);
          $limit = $nb_user_data['upi_limit_crossed'] + $amount;
          
            if($amount > $nb_user_data['account_balance'] - $netbanking_limit){
                echo "customer_mantain";
                exit();
            }else if($nb_user_data['upi_limit_crossed'] == $nb_user_data['upi_limit_perday']){
                echo "customer_mantain_limit";
                exit();
            }else if($limit > $nb_user_data['upi_limit_perday']){
                echo "customer_mantain_limit";
                exit();
            }else{
              $rand = RAND(1000,9999);
        
              $key = $nb_id."|".$amount;
              $set_hash = encryption('encrypt',$key);
              setcookie("csrf-token", $set_hash, time()+300, "/");
              setcookie('token',$rand,time()+300, "/");
        
              $message = $rand." is your OTP for WITHDRAWL at CSP (".$csp_data['name'].") . Do not share OTP with anyone.";
              $sms = send_sms($nb_user_data['mobile_number'],$message,1,'GOCASH');
                $sms_status = json_decode($sms,true);
        
              if($sms_status['status_message'] == "sms_success"){
                echo "otp_success";
                exit();
              }else{
                echo $sms_status['status_message'];
                exit();
              }
            }
        }

    }else if ($_GET['type'] == "verify") {
    $hash = explode('|',encryption('decrypt',$_COOKIE['csrf-token']));
      $nb_id = $hash[0];
      $amount = $hash[1];
      $nb_user_data = select('net_banking_users', 'id', $nb_id);
      
      if ($nb_user_data['account_balance'] < $amount) {
        echo "insufficient_balance";
      }else if ($nb_user_data['account_status'] !== "active" || $nb_user_data['bank_name'] == "") {
        echo "invalid_account";
      }else{

        $update_csp = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
        $update_admin = run_query("UPDATE admin SET wallet=wallet+'$amount'");
        if($update_csp){
          $update_nb = run_query("UPDATE net_banking_users SET account_balance=account_balance - '$amount', upi_limit_crossed = upi_limit_crossed+'$amount' WHERE id='$nb_id'");
          if ($update_nb) {
              $txn_id = RAND(10000000,99999999);
              $csp_wallet_data = array(
                'csp_id_fk' => $csp_id,
                'nb_id_fk' => $nb_id,
                'type' => 'CREDIT',
                'txn_id' => $txn_id,
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'cash_withdrawl',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
            
            $nb_wallet_data = array(
                'nb_id_fk' => $nb_id,
                'csp_id_fk' => $csp_id,
                'type' => 'DEBIT',
                'txn_id' => $txn_id,
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'cash_withdrawl',
                'created_on' => format_time(get_time())
            );
            $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));
              
            $date = format_time(get_time(),"M,d,Y");
            $message = "Cash withdrawl of Rs.".$amount." successfull at CSP Point (".$csp_data['name'].") on ".$date.". Dwonload our Mobile App for E-Banking - https://bit.ly/3jBUrq5";
            send_sms($nb_user_data['mobile_number'],$message,1,'GOCASH');

            echo $txn_id;
            setcookie('csrf-token',"", time() - 3600, "/");
            setcookie('token',"", time() - 3600, "/");
          }
        }else{
          echo "error";
        }
      }
    }
}

?>
