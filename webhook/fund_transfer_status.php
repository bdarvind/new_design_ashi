<?php
    include '../includes/config.php';
    include '../includes/functions.php';

    $data = $_POST;
    
    
    $user_type = $data['udf1'];
    $user_id = $data['udf2'];
    

    log_data(json_encode($data),'FUNDTRANSFER');
    
    
    $reference_number = $data['reference_number'];
    //check if ref no. exist
    
    $check_ref = mysqli_num_rows(run_query("SELECT * FROM money_transfer WHERE reference_number='$reference_number'"));
    
    if($check_ref > 0){
        $txn_details = select('money_transfer','reference_number',$data['reference_number']);
        $txn_amount = $txn_details['amount'];
        
        if($txn_details['status'] == "PENDING"){
            
            // //update status and bank ref number
            
            if($data['status'] == "COMPLETED"){
                $status = $data['status'];
                $bank_ref_num = $data['bank_ref_num'];
                $update_txn = mysqli_query($conn,"UPDATE money_transfer SET status='COMPLETED', bank_ref_num='$bank_ref_num' WHERE reference_number='$reference_number'");
                log_data("COMPLETED",'FUNDTRANSFER');
            }else if($data['status'] == "FAILED"){
                $status = $data['status'];
                $reason = $data['reason'];
                $update_txn = run_query("UPDATE money_transfer SET status='$status', reason='$reason' WHERE reference_number='$reference_number'");
                log_data("FAILED",'FUNDTRANSFER');
                
                //update user balance
                $update_user = run_query("UPDATE $user_type SET account_balance=account_balance+'$txn_amount' WHERE id='$user_id' LIMIT 1");
                
                $refund_txn_no = "REFUND".$reference_number;
                if($user_type == "csp"){
                    $csp_wallet_data = array(
                        'csp_id_fk' => $user_id,
                        'type' => 'CREDIT',
                        'txn_id' => $refund_txn_no,
                        'amount' => $txn_amount,
                        'status' => 'success',
                        'purpose' => 'refund',
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                }else{
                    $nb_wallet_data = array(
                        'nb_id_fk' => $user_id,
                        'type' => 'CREDIT',
                        'txn_id' => $refund_txn_no,
                        'amount' => $txn_amount,
                        'status' => 'success',
                        'purpose' => 'refund',
                        'created_on' => format_time(get_time())
                    );
                    $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));
                }
                
            }
            
        }else{
            log_data("ALREADY DONE - ".$reference_number,'FUNDTRANSFER');
        }
        
    }else{
        log_data("NO TXN ID FOUND - ".$reference_number,'FUNDTRANSFER');
    }
    
?>