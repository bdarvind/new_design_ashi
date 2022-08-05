<?php
ignore_user_abort(true);

include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_POST['nb_id']) && isset($_POST['amount'])) {
    $nb_id = $_POST['nb_id'];
    $amount = $_POST['amount'];

    $nb_user_data = select('net_banking_users', 'id', $nb_id);

    if ($csp_data['account_balance'] < $amount) {
      echo "insufficient_balance";
    }elseif($csp_data['account_balance'] <= $csp_data['account_limit']){
        echo "insufficient_balance_ten";
    }elseif($amount > $csp_data['account_balance']-$csp_data['account_limit']){
        echo "insufficient_balance_ten";
    }elseif($amount < 100){
        echo "min_hundred_error";
    }elseif ($nb_user_data['account_status'] !== "active" || $nb_user_data['bank_name'] == "") {
      echo "invalid_account";
    }else{

      $update_csp = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
      $update_admin = run_query("UPDATE admin SET wallet=wallet-'$amount'");
      if($update_csp){
        $update_nb = run_query("UPDATE net_banking_users SET account_balance=account_balance+'$amount' WHERE id='$nb_id'");
        if ($update_nb) {
            $txn_id = RAND(10000000,99999999);
            $csp_wallet_data = array(
                'csp_id_fk' => $csp_id,
                'nb_id_fk' => $nb_id,
                'type' => 'DEBIT',
                'txn_id' => $txn_id,
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'cash_deposit',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
            
            $nb_wallet_data = array(
                'nb_id_fk' => $nb_id,
                'csp_id_fk' => $csp_id,
                'type' => 'CREDIT',
                'txn_id' => $txn_id,
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'cash_deposit',
                'created_on' => format_time(get_time())
            );
            $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));
            
          $date = format_time(get_time(),"M,d,Y");
          $message = "Cash Deposit of Rs.".$amount." successfull at CSP Point (".$csp_data['name'].") on ".$date.". Do not Share you CVV, Card Details with anyone for security reasons. Dwonload our Mobile App for E-Banking - https://bit.ly/3jBUrq5";
          send_sms($nb_user_data['mobile_number'],$message,1,'GOCASH');
          echo $txn_id;
        }
      }else{
        echo "error";
      }
    }
}

?>
