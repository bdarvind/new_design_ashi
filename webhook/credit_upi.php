<?php
    include '../include/config.php';
    include '../include/functions.php';

    $data = $_POST;

   

    log_data(json_encode($data),'UPI_TXN');
    
    $hypto_upi_id = $data['hypto_upi_id'];

    $check_va = mysqli_num_rows(run_query("SELECT * FROM upi WHERE hypto_upi_id='$hypto_upi_id'"));
    if($check_va < 1){
        log_data('No UPI ID Found','UPI_TXN');
    }else{
        
        
        //insert_txn
        $upi_txn_data = array(
            "txn_id" => $data["txn_id"],
            "order_id" => $data["order_id"],
            "payee_vpa" => $data["payee_vpa"],
            "source" => $data["source"],
            "payer_name" => $data["payer_name"],
            "payer_mobile" => $data["payer_mobile"],
            "payer_note" => $data["payer_note"],
            "amount" => $data["amount"],
            "charges_gst" => $data["charges_gst"],
            "settled_amount" => $data["settled_amount"],
            "txn_time" => $data["txn_time"],
            "created_at" => $data["created_at"],
            "payment_type" => $data["payment_type"],
            "bank_ref_num" => $data["bank_ref_num"],
            "hypto_upi_id" => $data["hypto_upi_id"],
        );
        $insert = run_query(insert('upi_txn',$upi_txn_data));
        if($insert){
            //select user
            $upi_user = select('upi','hypto_upi_id',$data["hypto_upi_id"]);
            
            //update_user
            $user_type = $upi_user['user_type'];
            $user_id_fk = $upi_user['user_id_fk'];
            $amount = $data["amount"];
            $update = run_query("UPDATE $user_type SET account_balance=account_balance-'$amount' WHERE id ='$user_id_fk' LIMIT 1");
            
            if($update){
                if($user_type == "csp"){
                    $update_admin = run_query("UPDATE admin SET wallet=wallet-'$amount'");
                    $csp_wallet_data = array(
                        'csp_id_fk' => $user_id_fk,
                        'type' => 'CREDIT',
                        'txn_id' => $data["bank_ref_num"],
                        'amount' => $amount,
                        'status' => 'success',
                        'purpose' => 'upi_credit',
                        'note' => 'Via UPI '.$data["source"],
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                }else{
                    $nb_wallet_data = array(
                        'nb_id_fk' => $user_id_fk,
                        'type' => 'CREDIT',
                        'txn_id' => $data["bank_ref_num"],
                        'amount' => $amount,
                        'status' => 'success',
                        'purpose' => 'upi_credit',
                        'note' => 'Via UPI '.$data["source"],
                        'created_on' => format_time(get_time())
                    );
                    $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));
                }
                
                $select_user = select($user_type,'id',$user_id_fk);
                
                if($user_type == "csp"){
                    $bank_key = $select_user['bank'];
                }else{
                    $bank_key = $select_user['bank_name'];
                }
                
                $va_details = mysqli_fetch_assoc(run_query("SELECT $bank_key as bank FROM virtual_account WHERE user_type='$user_type' AND user_id='$user_id_fk'"));
                $bene_account_no = unserialize($va_details['bank'])['account_number'];
                
                
                $message = "Your a/c ".mask_number($bene_account_no)." has been credited with Rs.".$amount." on ".format_time(get_time(),"d/M/Y")." via ".$data['payment_type']." with ref. no. (".$data['bank_ref_num']."). For Dispute - +919311252103";
    
                send_sms($select_user['mobile_number'],$message,1,"GOCASH");
            }
            
        }
           
    }
    
?>