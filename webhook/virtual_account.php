<?php
    include '../include/config.php';
    include '../include/functions.php';

    $data = $_POST;

    log_data(json_encode($data),'VA');
    
    $va_id = $data['hypto_va_id'];

    $check_va = mysqli_num_rows(run_query("SELECT * FROM virtual_account WHERE va_id='$va_id'"));
    if($check_va < 1){
        log_data('No VA Found','VA');
    }else{
    
      $insert_txn = array(
          'bene_account_no' => $data['bene_account_no'],
          'bene_account_ifsc' => $data['bene_account_ifsc'],
          'rmtr_full_name' => $data['rmtr_full_name'],
          'rmtr_account_no' => $data['rmtr_account_no'],
          'rmtr_account_ifsc' => $data['rmtr_account_ifsc'],
          'rmtr_to_bene_note' => $data['rmtr_to_bene_note'],
          'amount' => $data['amount'],
          'settled_amount' => $data['settled_amount'],
          'created_at' => $data['created_at'],
          'payment_type' => $data['payment_type'],
          'bank_ref_num' => $data['bank_ref_num'],
          'hypto_va_id' => $data['hypto_va_id'],
      );
      $insert = run_query(insert('virtual_account_txn',$insert_txn));
      if($insert){
        $select_ca_user = select('virtual_account','va_id', $data['hypto_va_id']);
        $user_type = $select_ca_user['user_type'];
        $user_id = $select_ca_user['user_id'];

        if($user_type=="net_banking_users"){
          $wallet = "account_balance";
        }else{
          $wallet = "account_balance";
        }
        $amount = $data['amount'];

        $update_wallet = run_query("UPDATE $user_type SET $wallet=$wallet+$amount WHERE id='$user_id'");
        if($update_wallet){

        if($user_type == "csp"){
            $update_admin = run_query("UPDATE admin SET wallet=wallet+'$amount'");
            $csp_wallet_data = array(
                'csp_id_fk' => $user_id,
                'type' => 'CREDIT',
                'txn_id' => $data['bank_ref_num'],
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'va_credit',
                'created_on' => format_time(get_time())
            );
            $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
        }else{
            $nb_wallet_data = array(
                'nb_id_fk' => $user_id,
                'type' => 'CREDIT',
                'txn_id' => $data['bank_ref_num'],
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'va_credit',
                'created_on' => format_time(get_time())
            );
            $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));
        }

          $select_user = select($user_type,'id',$user_id);
          $message = "Your A/C no. ".mask_number($data['bene_account_no'])." is credited with Rs.".$amount." on ".format_time(get_time(),"d/M/Y")." from A/C ".mask_number($data['rmtr_account_no'])." using ".$data['payment_type']." with ref. no. (".$data['bank_ref_num']."). For Dispute - +919311252103";

          send_sms($select_user['mobile_number'],$message,1,"GOCASH");

          log_data('txn_updated','VA');
        }
      }
    }

 ?>
