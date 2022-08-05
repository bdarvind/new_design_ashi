<?php
    
    // if (isset($_SERVER['HTTP_ORIGIN'])) 
    // {
    //     header("Access-Control-Allow-Origin: https://stagegateway.eko.in");
    // }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Content-Type:text/html');
        // if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: POST,OPTIONS");         

        // if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: Content-Type");
            header("Access-Control-Allow-Origin: https://gateway.eko.in");
        
        // header("Access-Control-Allow-Methods: POST,OPTIONS");     
        // header("Access-Control-Allow-Origin: https://stagegateway.eko.in");

       
    }else{
        header('Content-Type:application/json');
        header("Access-Control-Allow-Origin: https://gateway.eko.in");
    }
    
    include '../include/config.php';
    include '../include/functions.php';
    
    
    $json = file_get_contents('php://input');
    
    log_data($json,"AEPS");
    $data = json_decode($json,true);
    
    // Generate Secret Key
    $encodedKey = base64_encode($eko_aeps_key);
    
    $secret_key_timestamp = "".round(microtime(true) * 1000);
    $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
    $secret_key = base64_encode($signature);
    // End Secret Key
    
    
    if($data['action'] == "debit-hook"){
        if($data['detail']['data']['type'] == "4"){
            //Mini Statement
            $return['action'] = "go";
            $return['allow'] = true;
            $return['secret_key_timestamp'] = $secret_key_timestamp;
            
            $request_data = $secret_key_timestamp.$data['detail']['data']['customer_id'].$data['detail']['data']['user_code'];
            
            $req_signature = hash_hmac('SHA256', $request_data, $encodedKey, true);
            $request_hash = base64_encode($req_signature);
            
            $return['request_hash'] = $request_hash;
            $return['secret_key'] = $secret_key;
            
        }else if($data['detail']['data']['type'] == "3"){
            //Balance Inquiry
            
            $return['action'] = "go";
            $return['allow'] = true;
            $return['secret_key_timestamp'] = $secret_key_timestamp;
            
            $request_data = $secret_key_timestamp.$data['detail']['data']['customer_id'].$data['detail']['data']['user_code'];
            
            $req_signature = hash_hmac('SHA256', $request_data, $encodedKey, true);
            $request_hash = base64_encode($req_signature);
            
            $return['request_hash'] = $request_hash;
            $return['secret_key'] = $secret_key;
            
            
        }else if($data['detail']['data']['type'] == "2"){
            // Cash Withdrawal
            
            $return['action'] = "go";
            $return['allow'] = true;
            $return['secret_key_timestamp'] = $secret_key_timestamp;
            
            $request_data = $secret_key_timestamp.$data['detail']['data']['customer_id'].$data['detail']['data']['amount'].$data['detail']['data']['user_code'];
            
            $req_signature = hash_hmac('SHA256', $request_data, $encodedKey, true);
            $request_hash = base64_encode($req_signature);
            
            $return['request_hash'] = $request_hash;
            $return['secret_key'] = $secret_key;
            
            
        }else{
            log_data("Invalid Type","AEPS"); 
            $return['action'] = "go";
            $return['allow'] = false;
            $return['message'] = "Invalid Transaction, Please Try Again Later";
        }
        echo json_encode($return);
    }else if($data['action'] == "eko-response"){
        if(isset($data['detail']['response']['data']['tx_status']) && $data['detail']['response']['data']['tx_status'] == "0"){
            $user_code = $data['detail']['response']['data']['user_code'];
            $select_user_code = run_query("SELECT * FROM aeps_users WHERE user_code ='$user_code' ");
            if(mysqli_num_rows($select_user_code) > 0){
                $row_data = mysqli_fetch_assoc($select_user_code);
                $user_id_fk = $row_data['user_id_fk'];
                
                $amount = $data['detail']['response']['data']['amount'];
				$aepscomm = mysqli_query($conn,"select * from csp_commission where csp_id='".$user_id_fk."' and commission_type='aeps'");
				if(mysqli_num_rows($aepscomm)==0)
				{
if($amount >= 0 && $amount <= 200){
$commission = 0;
}elseif($amount >= 201 && $amount <= 500)
{
$commission = 1.5;
}elseif($amount >= 501 && $amount <= 1000)
{
$commission = 2;
}elseif($amount >= 1001 && $amount <= 1500)
{
$commission = 2.5;
}elseif($amount >= 1501 && $amount <= 2000)
{
$commission = 4;
}elseif($amount >= 2001 && $amount <= 2500)
{
$commission = 5;
}elseif($amount >= 2501 && $amount <= 3000)
{
$commission = 8;
}else{
$commission = 8;
}
                /*if($amount >= 501 && $amount <= 3000){
                    $commission = $amount/100*0.2;
                    
                }else if($amount >= 3001 && $amount <= 4999){
                    $commission = 5;
                    
                }else if($amount >= 5000 && $amount <= 10000){
                    $commission = 8;
                    
                }else{
                    $commission = 0;
                }*/
				}else{
					foreach($aepscomm as $aepscommission)
					  {
						if($amount >= $aepscommission['slab_from'] && $amount <= $aepscommission['slab_to']){
						  //$commission = ($amount/100*$aepscommission['commission']);	
						$commission = $aepscommission['commission'];					  
						}else{
							if($amount >= 0 && $amount <= 200){
$commission = 0;
}elseif($amount >= 201 && $amount <= 500)
{
$commission = 1.5;
}elseif($amount >= 501 && $amount <= 1000)
{
$commission = 2;
}elseif($amount >= 1001 && $amount <= 1500)
{
$commission = 2.5;
}elseif($amount >= 1501 && $amount <= 2000)
{
$commission = 4;
}elseif($amount >= 2001 && $amount <= 2500)
{
$commission = 5;
}elseif($amount >= 2501 && $amount <= 3000)
{
$commission = 8;
}else{
$commission = 8;
}
						}
					  }
					
				}
				
                
                $total = $amount+$commission;
                
                $update = run_query("UPDATE csp SET account_balance=account_balance+'$total' WHERE id='$user_id_fk'");
                $update_admin = run_query("UPDATE admin SET wallet=wallet+'$total'");
    
                $csp_wallet_data = array(
                    'csp_id_fk' => $user_id_fk,
                    'type' => 'CREDIT',
                    'txn_id' => $data['detail']['response']['data']['bank_ref_num'],
                    'amount' => $amount,
                    'status' => 'success',
                    'purpose' => 'aadhaar_withdrawl',
                    'note' => 'AEPS Withdrawl for customer '.$data['detail']['response']['data']['sender_name'].' with bank ('.$data['detail']['response']['data']['bank'].') and Adhaar - '.$data['detail']['response']['data']['aadhar'],
                    'created_on' => format_time(get_time())
                );
                $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));  
                
                
                
                if($commission > 0){
                    $csp_wallet_data_commission = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'CREDIT',
                        'txn_id' => rand(10000000,99999999),
                        'amount' => $commission,
                        'status' => 'success',
                        'purpose' => 'commission',
                        'note' => 'Commission for AEPS, TXN ID - '.$data['detail']['response']['data']['bank_ref_num'],
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn_commission = run_query(insert('csp_wallet_txn',$csp_wallet_data_commission));
                }
                
            }
        }
    }


    
?>