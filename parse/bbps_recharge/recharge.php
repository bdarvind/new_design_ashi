<?php
ignore_user_abort(true);

include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if(isset($_POST['type']) && isset($_POST['mobile_number']) && isset($_POST['operator']) && isset($_POST['circle']) && isset($_POST['amount'])){
    
    $type = $_POST['type'];
    $mobile_number = $_POST['mobile_number'];
    $operator = $_POST['operator'];
    $circle = $_POST['circle'];
    $amount = $_POST['amount'];
    $txn_id = rand(1000000000,9999999999);
    
    
    if($csp_data['account_balance'] <= $amount){
        api_response(400,'error',"Insufficient Balance",NULL);
        
    }elseif($csp_data['account_balance'] <= $csp_data['account_limit']){
        
        api_response(400,'error',"Insufficient Balance, Please Maintain Minimum Balance",NULL);
        
    }elseif($amount > $csp_data['account_balance']-$csp_data['account_limit']){
        
        api_response(400,'error',"Insufficient Balance, Please Maintain Minimum Balance",NULL);
        
    }else{    


//commission
/*$rechargsql = mysqli_query($conn,"select * from csp_commission where csp_id='".$csp_id."' and commission_type='recharge'");    
 if(mysqli_num_rows($rechargsql)==0)
    {
	}else{
		foreach($rechargsql as $rechargecommission)
		{
        if($amount >= $rechargecommission['slab_from'] && $amount <= $rechargecommission['slab_to']){
          $deduct = $rechargecommission['commission'];
          $gst = $deduct/100*18;
        }
        }
	}*/		
        if($type == "prepaid"){
            
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
              //CURLOPT_URL => 'https://www.kwikapi.com/api/v2/recharge.php?api_key='.$kwik_api_key.'&number='.$mobile_number.'&amount='.$amount.'&opid='.$operator.'&order_id='.$txn_id.'&state_code='.$circle,
              //CURLOPT_URL => 'http://rgfintech.in/API/TransactionAPI?UserID='.$rgfintech_userid.'&Token='.$rgfintech_recharge_key.'&Account='.$mobile_number.'&Amount='.$amount.'&SPKey='.$operator.'&APIRequestID='.$txn_id.'&Optional1=&Optional2=&Optional3=&Optional4=&GEOCode=Longitude,Latitude&CustomerNumber='.$rgfintech_cutomernum.'&Pincode='.$rgfintech_cust_pin.'&Format=1-Json,2-XML',
			  CURLOPT_URL => 'https://roundpay.net/API/TransactionAPI?UserID='.$roundpay_user_id.'&Token='.$roundpay_recharge_key.'&Account='.$mobile_number.'&Amount='.$amount.'&SPKey='.$operator.'&APIRequestID='.$txn_id.'&Optional1=&Optional2=&Optional3=&Optional4=&GEOCode=Longitude,Latitude&CustomerNumber='.$roundpay_customer_num.'&Pincode='.$roundpay_customer_pin.'&Format=1-Json,2-XML',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = json_decode(curl_exec($curl),true);
			//print_r($response);
            //1-Pending/ 2- Success/ 3-Failed
            curl_close($curl);
            if($response['status'] == 1){//pending
                //distributor_commission_transaction
                // Recharge Report
                $rech_report_data = array(
                    'user_type' => 'csp',
                    'user_id' => $csp_id,
                    'number' => $mobile_number,
                    'amount' => $amount,
                    'fees' => $amount*1/100,
                    'status' => 'PENDING',
                    'operator' => $operator,
                    'circle' => $circle,
                    'txn_id' => $txn_id,
                    'operator_type' => 'prepaid',
                    'created_on' => format_time(get_time())
                );
                $insert_recharge_report = run_query(insert('kwik_recharge',$rech_report_data));

            //distributor
                $dist_user = run_query("SELECT * FROM csp WHERE id ='$csp_id' ");
                $distuser_row = mysqli_fetch_assoc($dist_user);
                $distributor_id = $distuser_row['distributor_id'];

                $distributor_commission = $amount*1/100+$amount;
                $rech_report_data_disb = array(
                    'csp_id' => $csp_id,
                    'distributor_id' => $distributor_id,
                    'commission' => $amount*$distributor_commission/100,
                    'commission_type' => 'recharge',
                    'amount' => $amount,                    
                    'addon_date' => format_time(get_time())
                );
                $insert_dist_comm = run_query(insert('distributor_commission_transaction',$rech_report_data_disb));
                $update_dist_amount = run_query("UPDATE distributor SET account_balance=account_balance+'$distributor_commission' WHERE id='$distributor_id'");
                //distributor


                
                //update_user
				//$totaldeduction = $amount+$deduct+$gst;
				$totaldeduction = ($amount*1/100+$amount);
				$addcommission = ($amount*1/100);
				$update = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
                $update = run_query("UPDATE csp SET account_balance=account_balance+'$addcommission' WHERE id='$csp_id'");
                if($update){
                    $csp_wallet_data = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'DEBIT',
                        'txn_id' => $txn_id,
                        'amount' => $amount,
                        'fees' => $amount*1/100,
                        'status' => 'success',
                        'purpose' => 'bbps',
                        'note' => 'Prepaid Recharge - '.$mobile_number,
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                }
                api_response(200,'pending',"Recharge Pending with Txn Id -".$txn_id." ",array('txn_id' => $txn_id));
                
                
            }else if($response['status'] == 2){//"SUCCESS" 2
                
                // Recharge Report
                $rech_report_data = array(
                    'user_type' => 'csp',
                    'user_id' => $csp_id,
                    'number' => $mobile_number,
                    'amount' => $amount,
                    'fees' => $amount*1/100,
                    'status' => 'SUCCESS',
                    'operator' => $operator,
                    'circle' => $circle,
                    'txn_id' => $txn_id,
                    'operator_type' => 'prepaid',
                    'created_on' => format_time(get_time())
                );
                $insert_recharge_report = run_query(insert('kwik_recharge',$rech_report_data));

                 //distributor
                $dist_user = run_query("SELECT * FROM csp WHERE id ='$csp_id' ");
                $distuser_row = mysqli_fetch_assoc($dist_user);
                $distributor_id = $distuser_row['distributor_id'];

                $distributor_commission = $amount*1/100;
                $rech_report_data_disb = array(
                    'csp_id' => $csp_id,
                    'distributor_id' => $distributor_id,
                    'commission' => $amount*1/100,
                    'commission_type' => 'recharge',
                    'amount' => $amount,                    
                    'addon_date' => format_time(get_time())
                );
                $insert_dist_comm = run_query(insert('distributor_commission_transaction',$rech_report_data_disb));
                $update_dist_amount = run_query("UPDATE distributor SET account_balance=account_balance+'$distributor_commission' WHERE id='$distributor_id'");
                //distributor
                
                //update_user
				//$totaldeduction = $amount+$deduct+$gst;
				//$totaldeduction = ($amount+1.50);
                $commission = $amount*1/100+$amount;
                $update = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
                if($update){
                    $csp_wallet_data = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'DEBIT',
                        'txn_id' => $txn_id,
                        'amount' => $amount,
                        'fees' => $amount*1/100,
                        'status' => 'success',
                        'purpose' => 'bbps',
                        'note' => 'Prepaid Recharge - '.$mobile_number,
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                }
                
                // Commission
                //$commission = $amount/100*1;
				//$commission = ($amount/100*1.5);
                $commission = $amount*1/100;
                $update_commission = run_query("UPDATE csp SET account_balance=account_balance+'$commission' WHERE id='$csp_id'");
                if($update_commission){
                    $csp_wallet_data_commission = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'CREDIT',
                        'txn_id' => rand(10000000,99999999),
                        'amount' => $commission,
                        'fees' => $amount*1/100,
                        'status' => 'success',
                        'purpose' => 'commission',
                        'note' => 'Commission for BBPS recharge with txn id - '.$txn_id,
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn_commission = run_query(insert('csp_wallet_txn',$csp_wallet_data_commission));
                }
                
                api_response(200,'success',"Recharge Successfull with Txn Id -".$txn_id." ",array('txn_id' => $txn_id));
                
            }else{
                api_response(400,'error',"Recharge Failed",array('msg' => $response['message']));
            }
            
        }else if($type == "postpaid"){
            
            
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
              //CURLOPT_URL => 'https://www.kwikapi.com/api/v2/bills/recharge.php?api_key='.$kwik_api_key.'&number='.$mobile_number.'&amount='.$amount.'&opid='.$operator.'&order_id='.$txn_id.'&state_code='.$circle.'refrence_id='.rand(1000000000,9999999999),
              //CURLOPT_URL => 'http://rgfintech.in/API/TransactionAPI?UserID=56&Token='.$rgfintech_recharge_key.'&Account='.$mobile_number.'&Amount='.$amount.'&SPKey='.$operator.'&APIRequestID='.$txn_id.'&Optional1=&Optional2=&Optional3=&Optional4=&GEOCode=Longitude,Latitude&CustomerNumber=9897582050&Pincode=4002&Format=1-Json,2-XML',
              CURLOPT_URL   => 'http://rgfintech.in/API/FetchBill?UserID='.$rgfintech_userid.'&Token='.$rgfintech_recharge_key.'&Account='.$mobile_number.'&Amount='.$amount.'&SPKey='.$operator.'&APIRequestID='.$txn_id.'&Optional1=&Optional2=&Optional3=&Optional4=&GEOCode=23.8530,87.9727&CustomerNumber='.$rgfintech_cutomernum.'&Pincode='.$rgfintech_cust_pin.'&Format=1',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = json_decode(curl_exec($curl),true);
            
            curl_close($curl);
            // 	1-Pending/ 2- Success/ 3-Failed 
            if($response['status'] == 1){//"PENDING"
                
                // Recharge Report
                $rech_report_data = array(
                    'user_type' => 'csp',
                    'user_id' => $csp_id,
                    'number' => $mobile_number,
                    'amount' => $amount,
                    'status' => 'PENDING',
                    'operator' => $operator,
                    'circle' => $circle,
                    'txn_id' => $txn_id,
                    'operator_type' => 'postpaid',
                    'created_on' => format_time(get_time())
                );
                $insert_recharge_report = run_query(insert('kwik_recharge',$rech_report_data));
                
                //update_user
                $update = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
                if($update){
                    $csp_wallet_data = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'DEBIT',
                        'txn_id' => $txn_id,
                        'amount' => $amount,
                        'status' => 'success',
                        'purpose' => 'bbps',
                        'note' => 'Postpaid Recharge - '.$mobile_number,
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                }
                api_response(200,'pending',"Recharge Pending with Txn Id -".$txn_id." ",array('txn_id' => $txn_id));
                
                
            }else if($response['status'] == 2){//"SUCCESS"
                
                // Recharge Report
                $rech_report_data = array(
                    'user_type' => 'csp',
                    'user_id' => $csp_id,
                    'number' => $mobile_number,
                    'amount' => $amount,
                    'status' => 'SUCCESS',
                    'operator' => $operator,
                    'circle' => $circle,
                    'txn_id' => $txn_id,
                    'operator_type' => 'postpaid',
                    'created_on' => format_time(get_time())
                );
                $insert_recharge_report = run_query(insert('kwik_recharge',$rech_report_data));
                
                //update_user
                $update = run_query("UPDATE csp SET account_balance=account_balance-'$amount' WHERE id='$csp_id'");
                if($update){
                    $csp_wallet_data = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'DEBIT',
                        'txn_id' => $txn_id,
                        'amount' => $amount,
                        'status' => 'success',
                        'purpose' => 'bbps',
                        'note' => 'Postpaid Recharge - '.$mobile_number,
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                    
                    api_response(200,'success',"Recharge Successfull with Txn Id -".$txn_id." ",array('txn_id' => $txn_id));
                }
                
                
                
            }else{
                api_response(400,'error',"Recharge Failed",array('msg' => $response['message']));
            }
            
            
            
            
            
            // api_response(400,'error',"Under Production",NULL);
            
        }else{
            api_response(400,'error',"Invalid Request, Try Again Later",NULL);
        }
    
    }
}else{
    api_response(400,'error',"Invalid Request, Try Again Later",NULL);
}
    
    
?>