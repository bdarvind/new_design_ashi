<?php

  include '../include/config.php';
  include '../include/functions.php';

if (isset($_POST['user_id']) && isset($_POST['type'])) {
    $user_id = $_POST['user_id'];
    $type = $_POST['type'];

    if($type == "statement"){
      $update = mysqli_query($conn, "UPDATE net_banking_users SET account_balance=account_balance-50 WHERE id='$user_id' LIMIT 1");
      if($update){
        $txn_id = RAND(10000000,99999999);

        $nb_wallet_data = array(
            'nb_id_fk' => $user_id,
            'type' => 'DEBIT',
            'txn_id' => $txn_id,
            'amount' => 50,
            'status' => 'success',
            'purpose' => 'statement',
            'created_on' => format_time(get_time())
        );
        $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));

        echo "success";
      }else{
        echo "error";
      }
    }
}

?>
