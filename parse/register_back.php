<?php

  include '../include/config.php';
  include '../include/functions.php';

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile_number']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $password = $_POST['password'];

    if (check_email($email) == "yes") {
      echo "email_exist";
    }elseif (check_mobile_number($mobile_number) == "yes") {
      echo "mobile_exist";
    }else{
      $data = array(
        'name' => $name,
        'email' => $email,
        'mobile_number' => $mobile_number,
        'password' => md5($password),
        'created_on' => format_time(get_time())
      );
      $insert = run_query(insert('csp',$data));
      if($insert){
        $number = $mobile_number;
        $message = 'Dear '.$name.' your user id is '.$mobile_number.' and password '.$password.'';
        send_reg_sms($number,$message,$route="",$senderid="");
        echo "success";
      }else{
        echo "error";
      }
    }
}

?>
