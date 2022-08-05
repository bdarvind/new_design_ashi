<?php

function log_data($message, $type = 'info'){
    $prefix = 'webhookLog_';
    $file = $type . '.log';
    date_default_timezone_set('Asia/kolkata');
    $date = new DateTime();
    error_log($date->format('Y-m-d H:i:s') . ' ' . $message . "\n\n", 3, $prefix . $file);
}

function escape($input){
  global $conn;
  $escape = mysqli_real_escape_string($conn, trim($input));
  return $escape;
}

    function send_mail($body,$recepients, $subject = 'Notification from Ashi Digital'){

        $transport = (new Swift_SmtpTransport('smtp.elasticemail.com', 587, 'tls'))
          ->setUsername('AC79A3E2DFA618701A6ACF2A8D4F7A1AA2536E7E238515EC0B429EEAA69FAC32FBF1E39DBA670FF894E1FD3F391B9EB6')
          ->setPassword('AC79A3E2DFA618701A6ACF2A8D4F7A1AA2536E7E238515EC0B429EEAA69FAC32FBF1E39DBA670FF894E1FD3F391B9EB6');



        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = new Swift_Message($subject);
        $message->setFrom(['info@ashidigitalpay.in' => 'Ashi Digital']);
        $message->setTo($recepients);

        $message->setBody($body, 'text/html');
        // Send the message
        $result = $mailer->send($message);



        return $result;

    }


    function edit_image($image_url,$w,$h,$bgcolor){
        $image_url = explode(".com",$image_url)[1];
        $edited_image = "https://ik.imagekit.io/ugrzmu7ged5/".$image_url."?tr=w-".$w.",h-".$h.",cm-pad_resize,bg-".$bgcolor;
        
        return $edited_image;
    }
    
    function is_ajax() {
      return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    function send_sms($number,$message,$route="",$senderid="", $pipe = "hspsms"){
        $message = urlencode($message);
        
        if($pipe == "hspsms"){
            $url = 'http://sms.hspsms.com/sendSMS?username=srawan%20kumar&message='.$message.'&sendername=ARGNDI&smstype=TRANS&numbers='.$number.'&apikey=67ee06c1-d61b-4e12-a466-58f91cb74d11';
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $response = json_decode($result,true);
            curl_close($ch);
            
            if($response[0]['responseCode'] == "Message SuccessFully Submitted"){ //status
              $data['status'] = 200;
              $data['status_message'] = 'sms_success';
              $data['data']['number'] = $number;
              $data['data']['txn_id'] = $response[1]['msgid']; //transactionId
              $data['data']['message_body'] = $message;
            }else{
              $data['status'] = 404;
              $data['status_message'] = 'error_occured';
              $data['data'] = $response;
            }
            
            
        }else{
            $url = 'https://alerts.cbis.in/SMSApi/send?userid=ashitr&password=Madhav@123&sendMethod=quick&mobile='.$number.'&msg='.$message.'&senderid=ASHIWB&msgType=text&dltEntityId=1201159128391380319&dltTemplateId=1207163739602595922&duplicatecheck=true&output=json';    
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $response = json_decode($result,true);
            curl_close($ch);
            
            if($response['status'] == "Message SuccessFully Submitted"){ //status
              $data['status'] = 200;
              $data['status_message'] = 'sms_success';
              $data['data']['number'] = $number;
              $data['data']['txn_id'] = $response['transactionId']; //transactionId
              $data['data']['message_body'] = $message;
            }else{
              $data['status'] = 404;
              $data['status_message'] = 'error_occured';
              $data['data'] = $response;
            }
            
        }
        
        
        return json_encode($data);

     }

      function send_reg_sms($number,$message,$route="",$senderid=""){
        $message = urlencode($message);
        $url = 'http://sms.hspsms.com/sendSMS?username=srawan%20kumar&message='.$message.'&sendername=ARGNDI&smstype=TRANS&numbers='.$number.'&apikey=67ee06c1-d61b-4e12-a466-58f91cb74d11';
        //$url = 'http://sms.hspsms.com/sendSMS?username=srawan%20kumar&message='.$message.'&sendername=ARGNDI&smstype=TRANS&numbers='.$number.'&apikey=b6cb39cf-62b1-49af-9671-2b673ab39923';
      //   $url = 'http://sms.hspsms.com/sendSMS?username=srawankumar2012&message='.$message.'&sendername=ARGSMS&smstype=SIM&numbers='.$number.'&apikey=3926066b-0fbb-491d-849d-9b6875b85b79';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $response = json_decode($result,true);
        curl_close($ch);
  
        if($response[0]['responseCode'] == "Message SuccessFully Submitted"){
            $data['status'] = 200;
            $data['status_message'] = 'success';
            $data['data']['number'] = $number;
            $data['data']['txn_id'] = $response[1]['msgid'];
            $data['data']['message_body'] = $message;
        }else{
            $data['status'] = 404;
            $data['status_message'] = 'error_occured';
            $data['data'] = $response;
        }
        return json_encode($data);
  
        }

        function send_login_sms($number,$message,$route="",$senderid=""){
        $message = urlencode($message);
        $url = 'https://alerts.cbis.in/SMSApi/send?userid=ashitr&password=Madhav@123&sendMethod=quick&mobile='.$number.'&msg='.$message.'&senderid=AWSPAY&msgType=text&dltEntityId=&dltTemplateId=&duplicatecheck=true&output=json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $response = json_decode($result,true);
        curl_close($ch);
  
        if($response['statusCode'] == "200" && $response['statusCode'] == "success"){
            $data['status'] = 200;
            $data['status_message'] = 'success';
            $data['data']['number'] = $number;
            $data['data']['txn_id'] = $response[1]['msgid'];
            $data['data']['message_body'] = $message;
        }else{
            $data['status'] = 404;
            $data['status_message'] = 'error_occured';
            $data['data'] = $response;
        }
        return json_encode($data);
  
        }

        function send_forgot_sms($number,$message,$route="",$senderid=""){
        $message = urlencode($message);
        //$url = 'https://alerts.cbis.in/SMSApi/send?userid=ashitr&password=Madhav@123&sendMethod=quick&mobile='.$number.'&msg='.$message.'&senderid=AWSPAY&msgType=text&dltEntityId=&dltTemplateId=&duplicatecheck=true&output=json';
        $url = 'https://alerts.cbis.in/SMSApi/send?userid=ashitr&password=Madhav@123&sendMethod=quick&mobile='.$number.'&msg='.$message.'&senderid=AWSPAY&msgType=text&dltEntityId=&dltTemplateId=&duplicatecheck=true&output=json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $response = json_decode($result,true);
        curl_close($ch);
  
        if($response['statusCode'] == "200" && $response['statusCode'] == "success"){
            $data['status'] = 200;
            $data['status_message'] = 'sms_success';
            $data['data']['number'] = $number;
            $data['data']['txn_id'] = $response[1]['msgid'];
            $data['data']['message_body'] = $message;
        }else{
            $data['status'] = 404;
            $data['status_message'] = 'error_occured';
            $data['data'] = $response;
        }
        return json_encode($data);
  
        }


        function newSend_massege($number,$message,$route="",$senderid="",$dlttemplateid){//08012022
        $message = urlencode($message);
        //$url = 'http://sms.hspsms.com/sendSMS?username=srawan%20kumar&message='.$message.'&sendername=ARGNDI&smstype=TRANS&numbers='.$number.'&apikey=67ee06c1-d61b-4e12-a466-58f91cb74d11';
        $url = 'https://alerts.cbis.in/SMSApi/send?userid=ashitr&password=Madhav@123&sendMethod=quick&mobile='.$number.'&msg='.$message.'&senderid=AWSPAY&msgType=text&dltEntityId=&dltTemplateId='.$dlttemplateid.'&duplicatecheck=true&output=json';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $response = json_decode($result,true);
        curl_close($ch);
  
        if($response['status'] == "success" && $response['statusCode'] == "200"){
            $data['status'] = 200;
            $data['status_message'] = 'success';
            $data['data']['number'] = $number;
            //$data['data']['txn_id'] = $response[1]['msgid'];
            $data['data']['message_body'] = $message;
        }else{
            $data['status'] = 404;
            $data['status_message'] = 'error_occured';
            $data['data'] = $response;
        }
        return json_encode($data);
  
        }

function encryption($action, $string) {
	    $output = false;

	    $encrypt_method = "AES-256-CBC";
	    $secret_key = '@@#$%^&()_+Si';
	    $secret_iv = 'rahulgangotri12345';

	    // hash
	    $key = hash('sha256', $secret_key);

	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);

	    if( $action == 'encrypt' ) {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    }
	    else if( $action == 'decrypt' ){
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }

	    return $output;
	}

  function amazon_image_upload($image){
    $time = get_time();
        $file_name = $image['name'];
        $temp_file_location = $image['tmp_name'];


        $s3 = new Aws\S3\S3Client([
            'region'  => 'ap-south-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => "AKIAJAL4L3OY5P3WT4YQ", // access key id
                'secret' => "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",
            ]
        ]);
        $result = $s3->putObject([
            'Bucket' => 'gocash-net-in',
            'Key'    => "images/".$time."_".$file_name,
            'SourceFile' => $temp_file_location ,
            'ACL'    => 'public-read'
        ]);
        $img_url = $result['ObjectURL'];
        return $img_url;
  }

  function check_mobile_number($mobile_number,$where = "csp"){
    global $conn;
    $check = mysqli_query($conn, "SELECT * FROM $where WHERE `mobile_number` = '$mobile_number'");
    if(mysqli_num_rows($check) > 0) {
      $exist = "yes";
    }else{
    $exist = 'no';
    }
    return $exist;
    }

    function check_email($email,$where = "csp"){
    global $conn;
    $check = mysqli_query($conn, "SELECT * FROM $where WHERE `email` = '$email'");
    if(mysqli_num_rows($check) > 0) {
    $exist = "yes";
    }else{
    $exist = 'no';
    }
    return $exist;
    }

    function check_pan($email,$where){
    global $conn;
    $check = mysqli_query($conn, "SELECT * FROM $where WHERE `email` = '$email'");
    if(mysqli_num_rows($check) > 0) {
    $exist = "yes";
    }else{
    $exist = 'no';
    }
    return $exist;
    }

    function check_aadhaar($email,$where){
    global $conn;
    $check = mysqli_query($conn, "SELECT * FROM $where WHERE `email` = '$email'");
    if(mysqli_num_rows($check) > 0) {
    $exist = "yes";
    }else{
    $exist = 'no';
    }
    return $exist;
    }



function get_time(){
date_default_timezone_set('Asia/kolkata');
$time = time();
return $time;
}

function format_time($time, $format = "M,d,Y g:i A"){
date_default_timezone_set('Asia/kolkata');
return date($format, $time);
}


function generate_string($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

function get_date_diff($session_time){
		// check if time is not provided
		if (empty($session_time)) {
			return false;
			exit();
		}
	    $time_difference = get_time() - $session_time;
	    $day = round($time_difference / 86400);

      return $day;
	 }


  function run_query($query){
    global $conn;
    $run = mysqli_query($conn, $query);

    return $run;
  }

  function insert($tableName,$array = array()){
    global $conn;

    $columns = implode(", ",array_keys($array));
    $values  = implode("', '", array_values($array));
    $sql = "INSERT INTO `{$tableName}`($columns) VALUES ('$values')";
    return $sql;
  }

  function select($tblName,$clmName,$chkValue){
    global $conn;

    $sql = mysqli_query($conn,"SELECT * FROM $tblName WHERE $clmName = '$chkValue' ");
		if(mysqli_num_rows($sql)>0){
    	$row = mysqli_fetch_assoc($sql);
    	return $row;
		}else{
			return false;
		}
	}

	function row_count($tblName,$clmName,$chkValue){
    global $conn;

    $sql = mysqli_query($conn,"SELECT * FROM $tblName WHERE $clmName = '$chkValue' ");
		echo mysqli_num_rows($sql);
	}

  function log_data_txt($logMsg="logger", $filename="logger", $logData=""){
      date_default_timezone_set('Asia/kolkata');
      $log  = date("j.n.Y h:i:s")." || $logMsg : ".print_r($logData,1).PHP_EOL .
      "-------------------------".PHP_EOL;
      file_put_contents($filename.date("j.n.Y").'.txt', $log, FILE_APPEND);
  }

	function api_response($status_code,$status,$status_message,$data){
		header("Content-Type:application/json");

		$response['status_code'] = $status_code;
		$response['status']=$status;
		$response['status_message']=$status_message;
		$response['data']=$data;

		$json_response = json_encode($response);
		echo $json_response;
	}

	function pin_code($pin_code){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.postalpincode.in/pincode/".$pin_code,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$url = json_decode(curl_exec($curl),true)[0];

		if($url['Status'] == "Success"){
			$data['city'] = $url['PostOffice'][0]['Division'];
			$data['district'] = $url['PostOffice'][0]['District'];
			$data['division'] = $url['PostOffice'][0]['Division'];
			$data['state'] = $url['PostOffice'][0]['State'];

		}else{
			$data['city'] == "error_occured";
			$data['district'] = "error_occured";
			$data['division'] =	"error_occured";
			$data['state'] = "error_occured";
		}
		// return $url;
		return $data;
	}

function mask_number($cc, $maskFrom = 0, $maskTo = 4, $maskChar = 'X', $maskSpacer = '-'){
    // Clean out
    $cc       = str_replace(array('-', ' '), '', $cc);
    $ccLength = strlen($cc);

    // Mask CC number
    if (empty($maskFrom) && $maskTo == $ccLength) {
        $cc = str_repeat($maskChar, $ccLength);
    } else {
        $cc = substr($cc, 0, $maskFrom) . str_repeat($maskChar, $ccLength - $maskFrom - $maskTo) . substr($cc, -1 * $maskTo);
    }

    // Format
    if ($ccLength > 4) {
        $newCreditCard = substr($cc, -4);
        for ($i = $ccLength - 5; $i >= 0; $i--) {
            // If on the fourth character add the mask char
            if ((($i + 1) - $ccLength) % 4 == 0) {
                $newCreditCard = $maskSpacer . $newCreditCard;
            }

            // Add the current character to the new credit card
            $newCreditCard = $cc[$i] . $newCreditCard;
        }
    } else {
        $newCreditCard = $cc;
    }

    return $newCreditCard;
}

function count_days_from_date($start_date, $end_date){
    // calulating the difference in timestamps
    $diff = strtotime($start_date) - strtotime($end_date);

    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return ceil(abs($diff / 86400));
}

?>
