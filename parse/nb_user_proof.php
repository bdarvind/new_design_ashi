<?php
include '../include/config.php';
include '../include/functions.php';
include '../vendor/autoload.php';


session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);
$csp_bank = $csp_data['bank'];


if (isset($_POST['nb_id_fk'])) {

  $nb_id_fk = $_POST['nb_id_fk'];
  
  $check_nb_id = run_query("SELECT * FROM nb_user_proof WHERE nb_id_fk='$nb_id_fk'");
  if(mysqli_num_rows($check_nb_id) > 0){
      echo "account_exist";
  }else{
  
  $nominee_title = $_POST['nominee_title'];
  $nominee_name = $_POST['nominee_name'];
  $nominee_mobile = $_POST['nominee_mobile'];
  $nominee_relation = $_POST['nominee_relation'];
  $nominee_address = $_POST['nominee_address'];
  $nominee_birth_date = $_POST['nominee_birth_date'];
  $nominee_pin_code = $_POST['nominee_pin_code'];
  $introducer_name = $_POST['introducer_name'];
  $introducer_mobile = $_POST['introducer_mobile'];
  $introducer_address = $_POST['introducer_address'];
  $nb_user_aadhaar = amazon_image_upload($_FILES['nb_user_aadhaar']);
  $nb_user_pan = amazon_image_upload($_FILES['nb_user_pan']);
  $nb_user_photo = amazon_image_upload($_FILES['nb_user_photo']);
  $nb_user_sign = amazon_image_upload($_FILES['nb_user_sign']);

  $insert_data = array(
    'nb_id_fk' => $nb_id_fk,
    'nominee_title' => $nominee_title,
    'nominee_name' => $nominee_name,
    'nominee_mobile' => $nominee_mobile,
    'nominee_relation' => $nominee_relation,
    'nominee_address' => $nominee_address,
    'nominee_pin_code' => $nominee_pin_code,
    'nominee_birth_date' => $nominee_birth_date,
    'introducer_name' => $introducer_name,
    'introducer_mobile' => $introducer_mobile,
    'introducer_address' => $introducer_address,
    'nb_user_aadhaar' => $nb_user_aadhaar,
    'nb_user_pan' => $nb_user_pan,
    'nb_user_photo' => $nb_user_photo,
    'nb_user_sign' => $nb_user_sign
  );

  $insert = run_query(insert('nb_user_proof',$insert_data));
  if($insert){

    $nb_user = select('net_banking_users','id',$nb_id_fk);
    $data = array(
        'reference_number' => "000".$nb_user['mobile_number'],
        'udf1' => $nb_user['email'],
        'udf2' => $nb_user['member_name']
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://partners.hypto.in/api/virtual_accounts",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($data),
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$hypto_api_key,
        "Content-Type: application/json"
      ),
    ));

    $response = json_decode(curl_exec($curl),true);
    if($response['success'] == true){
        $va = array(
            'va_id' => $response['data']['virtual_account']['id'],
            'reference_number' => $response['data']['virtual_account']['reference_number'],
            'icici_bank' => serialize($response['data']['virtual_account']['details'][3]),
            'yes_bank' => serialize($response['data']['virtual_account']['details'][0]),
            'rbl_bank' => serialize($response['data']['virtual_account']['details'][4]),
            'kotak_bank' => serialize($response['data']['virtual_account']['details'][1]),
            'dbs_bank' => serialize($response['data']['virtual_account']['details'][2]),
            'user_type' => 'net_banking_users',
            'user_id' => $nb_id_fk,
            'created_on' => get_time(),

        );
        $insert_va = run_query(insert('virtual_account',$va));
        if($insert_va){
            $account_number = unserialize($va[$csp_bank])['account_number'];
            $message = "Dear Customer, We wish to inform you that your account ".mask_number($account_number)." is activated. you can now start availing banking benifits with us.";
             send_sms($nb_user['mobile_number'],$message,4,'GOCASH');
            
            $update_user = run_query("UPDATE net_banking_users SET bank_name='$csp_bank' WHERE id='$nb_id_fk'");
            if($update_user){
                
            $nb_user_data = select('net_banking_users', 'id', $nb_id_fk);
                
            $amount = 500;
            $commission = 15;
            $total_amount_csp = $amount + $commission;
                
            $update_csp = run_query("UPDATE csp SET account_balance=account_balance-'$total_amount_csp' WHERE id='$csp_id'");
            $update_admin = run_query("UPDATE admin SET wallet=wallet-'$amount'");
              if($update_csp){
                $update_nb = run_query("UPDATE net_banking_users SET account_balance=account_balance+'$amount' WHERE id='$nb_id_fk'");
                if ($update_nb) {
                    $txn_id = RAND(10000000,99999999);
                    $csp_wallet_data = array(
                        'csp_id_fk' => $csp_id,
                        'nb_id_fk' => $nb_id_fk,
                        'type' => 'DEBIT',
                        'txn_id' => $txn_id,
                        'amount' => $amount,
                        'status' => 'success',
                        'purpose' => 'cash_deposit',
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                    
                    $txn_id_commission = RAND(10000000,99999999);
                    $csp_wallet_data_commission = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'CREDIT',
                        'txn_id' => $txn_id_commission,
                        'amount' => $commission,
                        'status' => 'success',
                        'purpose' => 'commission',
                        'note' => "Commission Credit for Account Opening of Customer ".$nb_user['member_name'],
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn_commission = run_query(insert('csp_wallet_txn',$csp_wallet_data_commission));
                    
                    $nb_wallet_data = array(
                        'nb_id_fk' => $nb_id_fk,
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
                  $message = "Cash Deposit of Rs.".$amount." successfull at CSP Point (".$csp_data['name'].") on ".$date.". Do not Share you CVV, Card Details with anyone. Download Our APP for Digital Banking- https://bit.ly/3sNzz39";
                  send_sms($nb_user_data['mobile_number'],$message,1,'GOCASH');
                  
                }
            }    
                
            
                
                
                
              echo "success";
            }else{
              echo "error_occured";
            }
            // echo "success";
        }else{
          echo "error_occured";
        }
    }else{
      echo "error_occured";
    }
  }

    }
}

 ?>
