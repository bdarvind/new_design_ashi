<?php

    include '../include/config.php';
    include '../include/functions.php';

    log_data(json_encode($_GET),'RECHARGE');
    
    $client_id = $_GET['client_id'];
    $status = $_GET['status'];
    
    //get_txn from id
    $get = run_query("SELECT * FROM kwik_recharge WHERE txn_id ='$client_id'");
    if(mysqli_num_rows($get) > 0){
     
        $recharge_detail = mysqli_fetch_assoc($get);
        
        $user_type = $recharge_detail['user_type'];
        $user_id = $recharge_detail['user_id'];
         
         
        //update recharge
        $update_recharge = run_query("UPDATE kwik_recharge SET status = '$status' WHERE txn_id ='$client_id' ");
         
        if($status == "SUCCESS"){
            
         
            if($user_type == 'csp'){
                
                if($recharge_detail['operator_type'] == "prepaid"){
                    //  Update Commission
                    $commission = $recharge_detail['amount']/100*0.5;
                    $update_commission = run_query("UPDATE csp SET account_balance=account_balance+'$commission' WHERE id='$user_id'");
                    if($update_commission){
                        $csp_wallet_data_commission = array(
                            'csp_id_fk' => $user_id,
                            'type' => 'CREDIT',
                            'txn_id' => rand(10000000,99999999),
                            'amount' => $commission,
                            'status' => 'success',
                            'purpose' => 'commission',
                            'note' => 'Commission for txn id - '.$recharge_detail['txn_id'],
                            'created_on' => format_time(get_time())
                        );
                        $insert_csp_txn_commission = run_query(insert('csp_wallet_txn',$csp_wallet_data_commission));
                    }
                }
                
            }
         
        }else if($status == "FAILED"){
            // Refund User
            $recharge_acount = $recharge_detail['amount'];
            
            $update_user = run_query("UPDATE $user_type SET account_balance =account_balance+'$recharge_amount' WHERE id ='$user_id' LIMIT 1");
            
            
            $txn_id = rand(1000000000,9999999999);
            if($user_type == "csp"){
                    $csp_wallet_data = array(
                        'csp_id_fk' => $user_id,
                        'type' => 'CREDIT',
                        'txn_id' => $txn_id,
                        'amount' => $recharge_acount,
                        'status' => 'success',
                        'purpose' => 'refund',
                        'note' => 'Refund For Recharge - '.$recharge_detail['txn_id'],
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                }else{
                    $nb_wallet_data = array(
                        'nb_id_fk' => $user_id,
                        'type' => 'CREDIT',
                        'txn_id' => $txn_id,
                        'amount' => $recharge_acount,
                        'status' => 'success',
                        'purpose' => 'refund',
                        'note' => 'Refund For Recharge - '.$recharge_detail['txn_id'],
                        'created_on' => format_time(get_time())
                    );
                    $insert_nb_txn = run_query(insert('nb_user_txn',$nb_wallet_data));
                }
            
            
        }else{
            log_data("Invalid Recharge - ". $client_id,'RECHARGE');
        }
     
     
        
    }else{
        log_data("Invalid Recharge - ". $client_id,'RECHARGE');
    }