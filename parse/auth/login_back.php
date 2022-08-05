<?php

  session_start();
  include '../../vendor/autoload.php';
  include '../../include/config.php';
  include '../../include/functions.php';

  if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $mobile_number = $_POST['mobile_number'];
    $password = $_POST['password'];

    if($type == "initiate"){
      $mobile_number = $_POST['mobile_number'];
      $password = $_POST['password'];

      if($server_status == "up"){
        //check
        $csp = run_query("SELECT * FROM csp WHERE mobile_number ='$mobile_number' ");
        $check_status = mysqli_fetch_assoc($csp);
        if($check_status['status'] == "active"){
          if(mysqli_num_rows($csp) > 0){
            //check password
            $csp_user = $check_status;
            if($csp_user['password'] == md5($password)){
              $rand = RAND(1000,9999);
              $hash = $rand."|".$csp_user['mobile_number'];
              setcookie('csp_csrf_token',encryption('encrypt', $hash),time()+300, "/");

              $message = "Do not share your login OTP with any one. <b>".$rand."</b> OTP to accessing your Account Please unauthorized access to customer care Powered by Ashidigital pay.in";
              
              send_mail($message,array($check_status['email']), 'Ashi digital | OTP Verification');

              $number = $mobile_number;
              $message = 'Do not share your login OTP with any one. '.$rand.' OTP to accessing your Account Please unauthorized access to customer care Powered by Ashi digital pay';
              send_login_sms($number,$message,$route="",$senderid="");
              
            //   $sms = send_sms($csp_user['mobile_number'],$message,1,'GOCASH', "cbis");
                $sms_status = true; //json_decode($sms,true);

              if($sms_status == true){
                $return['status'] = "success";
                $return['status_msg'] = "OTP has been sent to your Email Address";
              }else{
                $return['status'] = "error";
                $return['status_msg'] = "Error Occured while login, Please Try again after sometime";
              }
            }else{
              $return['status'] = "error";
              $return['status_msg'] = "Incorrect Password, Please Try Again.";
            }
          }else{
            $return['status'] = "error";
            $return['status_msg'] = "Mobile Number Does not Exist";
          }
        }else{
          $return['status'] = "error";
          $return['status_msg'] = "Inactive Account, Contact your Relationship Manager or Chat with Us - ".$phone;
        }
      }else{
        $return['status'] = "error";
        $return['status_msg'] = "Bank Server Down, Please Try again after sometime";
      }
    }else if($type == "verify"){
      $csp_csrf_token = $_GET['csp_csrf_token'];
      $csp_cookie = explode('|',encryption('decrypt',$_COOKIE["csp_csrf_token"]));

      $csp_csrf_token_cookie = $csp_cookie[0];
      $mobile_number = $csp_cookie[1];

      if($csp_csrf_token == $csp_csrf_token_cookie){
        $csp = mysqli_fetch_assoc(run_query("SELECT * FROM csp WHERE mobile_number ='$mobile_number' "));
        $_SESSION['csp'] = $csp['id'];
        setcookie('csp_csrf_token',"",time()-3600, "/");

        $return['status'] = "success";
        $return['status_msg'] = "Successfully logged in!";
      }else{
        $return['status'] = "error";
        $return['status_msg'] = "OTP Didn't Matched, Try Again";
      }

    }else{
      $return['status'] = "error";
      $return['status_msg'] = "Invalid Request, Please Try Again";
    }

  }

echo json_encode($return);

?>
