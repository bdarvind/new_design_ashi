<?php
header('Content-type:application/json');
include '../include/config.php';
include '../include/functions.php';

  if (isset($_POST['mobile_number'])) {
    if (check_mobile_number($_POST['mobile_number'],"csp") == "yes") {
      $mobile_number = $_POST['mobile_number'];
      $csp = run_query("SELECT * FROM csp WHERE mobile_number ='$mobile_number' ");
      $check_status = mysqli_fetch_assoc($csp);
      if($check_status['status'] == "active"){
        $mobile = $_POST['mobile_number'];
        $rand = rand(10000000,99999999);
        $md5 = md5($rand);
        $update = run_query("UPDATE csp SET password='$md5' WHERE mobile_number='$mobile' LIMIT 1");
        if($update){
            
          //$message = "Do not share your login OTP with any one. ".$rand." OTP to accessing your Account Please unauthorized access to customer care Powered by Ashidigital pay.in";
          
          //$send_sms = json_decode(send_sms($mobile,$message),true);

          $message = 'Dear User Do not share your Password with anyone.Use '.$rand.'Das forgot password OTP to reset your Ashi Digital Pay account.';
          $number = $_POST['mobile_number'];
          $send_sms = json_decode(send_forgot_sms($number,$message,$route="",$senderid=""),true);
          //print_r($send_sms);
          if($send_sms['data']['status'] == "success"){
            $return['status'] = "success";
            $return['msg'] = "Password, reset succesfully, password reset instructions has been sent to your mobile.";
          }else{
            $return['status'] = "error";
            $return['msg'] = "Unable to Send SMS to your Mobile Number, try again later.";
          }

        }else{
          $return['status'] = "error";
          $return['msg'] = "Unable to Reset your password, try again later.";
        }
      }else{
        $return['status'] = "error";
        $return['msg'] = "Inactive Account, Contact your Relationship Manager or Chat with Us - ".$phone;
      }

    }else{
      $return['status'] = "error";
      $return['msg'] = "Mobile Number does not exist, try again.";
    }
  }else{
    $return['status'] = "error";
    $return['msg'] = "Invalid Request";
  }

echo json_encode($return);

?>
