<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if($_FILES['pan_card_doc']['size'] < 1048576 && $_FILES['aadhar_front_doc']['size'] < 1048576 && $_FILES['aadhar_back_doc']['size'] < 1048576){

    

    if(isset($_POST['first_name']) && isset($_POST['mobile']) && isset($_POST['email']) && isset($_POST['pan_number']) && isset($_POST['shop_name']) && isset($_POST['line']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['district']) && isset($_POST['area']) && isset($_POST['dob']) && isset($_POST['pincode'])){
    
    $pan_upload = 'uploads/'.$_FILES['pan_card_doc']['name'];
    move_uploaded_file($_FILES['pan_card_doc']['tmp_name'],$pan_upload);
    $pan_card_doc = realpath($pan_upload);
    
    $adhaar_front_upload = 'uploads/'.$_FILES['aadhar_front_doc']['name'];
    move_uploaded_file($_FILES['aadhar_front_doc']['tmp_name'],$adhaar_front_upload);
    $adhaar_card_front = realpath($adhaar_front_upload);
    
    $adhaar_back_upload = 'uploads/'.$_FILES['aadhar_back_doc']['name'];
    move_uploaded_file($_FILES['aadhar_back_doc']['tmp_name'],$adhaar_back_upload);
    $adhaar_card_back = realpath($adhaar_back_upload);
    
    // log_data(json_encode($_POST),'AEPS');
    
    $initiator_id = $eko_initiator_id;
    

    $encodedKey = base64_encode($eko_aeps_key);
    
    $secret_key_timestamp = "".round(microtime(true) * 1000);
    $signature = hash_hmac('SHA256', $secret_key_timestamp, $encodedKey, true);
    $secret_key = base64_encode($signature);
    
    
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $pan_number = $_POST['pan_number'];
    $shop_name = $_POST['shop_name'];
    $line = $_POST['line'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $area = $_POST['area'];
    $dob = date("Y-m-d", strtotime($_POST['dob'])); 
    $pincode = $_POST['pincode'];
    
    $residence_address = urlencode(json_encode(array(
        'line' => $line,
        'city' => $city,
        'state' => $state,
        'pincode' => $pincode,
        'district' => $district,
        'area' => $area
    )));
    
    
    
    $request = 'initiator_id='.$initiator_id.'&pan_number='.$pan_number.'&mobile='.$mobile.'&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&residence_address='.$residence_address.'&dob='.$dob.'&shop_name='.$shop_name;
    log_data($request,'AEPS');
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.eko.in:25002/ekoicici/v1/user/onboard',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'PUT',
      CURLOPT_POSTFIELDS => 'initiator_id='.$initiator_id.'&pan_number='.$pan_number.'&mobile='.$mobile.'&first_name='.$first_name.'&last_name='.$last_name.'&email='.$email.'&residence_address='.$residence_address.'&dob='.$dob.'&shop_name='.$shop_name,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'developer_key: '.$eko_developer_key,
        'secret-key: '.$secret_key,
        'secret-key-timestamp: '.$secret_key_timestamp
      ),
    ));
    
    $response = json_decode(curl_exec($curl),true);
    //print_r($response);
    // log_data($response,'AEPS');
    
    curl_close($curl);
    
    if($response['response_type_id'] == 1290){
        
        $insert_aeps_data = array(
            'user_id_fk' => $csp_id,
            'user_code' => $response['data']['user_code'],
            'created_on' => format_time(get_time()),
        );
        $insert = run_query(insert('aeps_users',$insert_aeps_data));
        
        if($insert){
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.eko.in:25002/ekoicici/v1/user/service/activate',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'PUT',
              CURLOPT_POSTFIELDS => array(
                  
                  'form-data' => 'service_code=43&initiator_id='.$initiator_id.'&user_code='.$response['data']['user_code'].'&devicenumber=12345678&modelname=Mantra&office_address='.$residence_address.'&address_as_per_proof='.$residence_address.'',
                  'pan_card'=> new CURLFILE($pan_card_doc),
                  'aadhar_front'=> new CURLFILE($adhaar_card_front),
                  'aadhar_back'=> new CURLFILE($adhaar_card_back)
                ),
                  
              CURLOPT_HTTPHEADER => array(
                'Content-Type: multipart/form-data',
                'developer_key: '.$eko_developer_key,
                'secret-key: '.$secret_key,
                'secret-key-timestamp: '.$secret_key_timestamp
              ),
            ));
            
            
            $activate_res = json_decode(curl_exec($curl),true);
            // log_data(json_encode($activate_res),'AEPS');
            curl_close($curl);
            
            if($activate_res['data']['service_status_desc'] == "Activated"){
                //update User
                $user_code = $activate_res['data']['user_code'];
                $update_aeps_user = run_query("UPDATE aeps_users SET aeps_status ='active' WHERE user_code='$user_code' LIMIT 1");
                if($update_aeps_user){
                    
                    $update = run_query("UPDATE csp SET account_balance=account_balance-25 WHERE id='$csp_id'");
            
                    $csp_wallet_data = array(
                        'csp_id_fk' => $csp_id,
                        'type' => 'DEBIT',
                        'txn_id' => RAND(10000000,99999999),
                        'amount' => 25,
                        'status' => 'success',
                        'purpose' => 'charge',
                        'note' => 'AEPS Onboarding Charge',
                        'created_on' => format_time(get_time())
                    );
                    $insert_csp_txn = run_query(insert('csp_wallet_txn',$csp_wallet_data));
                    
                    $return['status'] = "success";
                    $return['status_msg'] = $activate_res['message'];
                }
            }
            
            
            
        }
        
    }else{
        $return['status'] = "error";
        $return['status_msg'] = $response['message'];
    }
    
    

}else{
    $return['status'] = "error";
    $return['status_msg'] = "Invalid Request";
}
    
    
}else{
    $return['status'] = "error";
    $return['status_msg'] = "Images Must be under 1 MB";
}

echo json_encode($return);

?>