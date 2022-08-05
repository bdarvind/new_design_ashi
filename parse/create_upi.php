<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

	if(isset($_POST['upi_id'])){
	    $user_id_fk = $csp_id;
	    $upi_id = $upi_prefix.$_POST['upi_id']."@yesbank";
	    $name = $csp_data['name'];
	    $pan = $csp_data['pan_card'];
	    $address = $csp_data['address'];
	    
	    $check_user = mysqli_num_rows(run_query("SELECT * FROM upi WHERE user_type='csp' AND user_id_fk='$user_id_fk'"));
	    
	    if($check_user < 1){
	        
	        //check if already
    	    $check_upi = mysqli_num_rows(run_query("SELECT * FROM upi WHERE upi_id='$upi_id'"));
    	    if($check_upi < 1){
    	        
    	        $payload['upi_id'] = $upi_id;
    	        $payload['name'] = $name;
    	        $payload['pan'] = $pan;
    	        $payload['address'] = $address;
    	        
    	        $payload['category'] = "Others";
    	        
    	        $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://partners.hypto.in/api/upis',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => json_encode($payload),
                  CURLOPT_HTTPHEADER => array(
                    'Authorization: '.$hypto_api_key,
                    'Content-Type: application/json'
                  ),
                ));
                
                $response = curl_exec($curl);
                curl_close($curl);
                
                $response = json_decode($response,true);
                
                if($response['success'] == true){
                    
                    $insert_data = array(
                        'user_type' => 'csp',
                        'user_id_fk' => $user_id_fk,
                        'upi_id' => $upi_id,
                        'hypto_upi_id' => $response['data']['upi']['id'],
                        'status' => "ACTIVATED",
                        'created_on' => format_time(get_time())
                    );
                    $insert_upi = run_query(insert('upi',$insert_data));
                    if($insert_upi){
                        api_response(200,'success',"UPI ID created Successfully",NULL);
                    }else{
                        api_response(400,'error_occured',"Error Occured while creating UPI",NULL);
                    }
                    
                }else{
                    api_response(400,'error_occured',"Unable to create UPI ID, try again with different upi",NULL);
                }
    	        
    	    }else{
    	        api_response(400,'upi_already_exist',"UPI ID Already Exist",NULL);
    	    }
	        
	    }else{
    	    api_response(400,'already_upi_user',"User Already Have a UPI ID",NULL);
	    }
	    
	}else{
    	api_response(400,'invalid_request',"Required parameters not passed",NULL);
	}

?>