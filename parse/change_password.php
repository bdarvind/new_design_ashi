<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);


  if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (md5($current_password) == $csp_data['password']) {
      if ($new_password == $confirm_password) {
        $update_password = md5($new_password);
        $update = mysqli_query($conn, "UPDATE csp SET password ='$update_password' WHERE id ='$csp_id' LIMIT 1");
        if($update){
          $return['status'] = "success";
          $return['msg'] = "Password Changed Successfully";
        }else{
          $return['status'] = "error";
          $return['msg'] = "Error While Changing your password, try again.";
        }

      }else{
        $return['status'] = "error";
        $return['msg'] = "New and Confirm Password didn't Matched, Try Again";
      }

    }else{
      $return['status'] = "error";
      $return['msg'] = "Incorrect Current Password";
    }

  }else{
    $return['status'] = "error";
    $return['msg'] = "Invalid Request, Try Again";
  }

  echo json_encode($return);

?>
