<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_POST['search_option'])) {
  $search_option = $_POST['search_option'];
  $search_from_id = $_POST['search_from_id'];

  if ($search_option == "aadhaar_no") {
    $opt = 'aadhaar_number';
  }elseif ($search_option == "pan_card") {
    $opt = 'pan_card';
  }elseif ($search_option == "crn_no") {
    $opt = 'mobile_number';
    if(strpos($search_from_id, $crn_code) !== false){
      $search_from_id = explode($crn_code,$search_from_id)[1];
    }else{
      echo "wrong_crn_no";
      exit();
    }

  }
  $check_user_exist = mysqli_query($conn, "SELECT * FROM net_banking_users WHERE $opt = '$search_from_id'"); //AND csp_id_fk='$csp_id'
  if(mysqli_num_rows($check_user_exist) > 0){
    $get_user = select('net_banking_users',$opt,$search_from_id);

    if ($get_user['bank_name'] == ""){
      echo "no_account_exist";
    }else{
      echo encryption('encrypt',$get_user['id']);
    }
    
  }else{
    echo "no_user_exist";
  }

}

 ?>
