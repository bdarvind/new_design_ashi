<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_POST['search_option'])) {
  $search_option = $_POST['search_option'];
  $search_from_id = $_POST['search_from_id'];
  $from_date = $_POST['from_date'];
  $to_date = $_POST['to_date'];

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

  if (count_days_from_date($from_date, $to_date) > 90) {
    echo "invalid_date";
    exit();
  }else{

    $check_user_exist = mysqli_query($conn, "SELECT * FROM net_banking_users WHERE $opt = '$search_from_id'"); //AND csp_id_fk='$csp_id'
    if(mysqli_num_rows($check_user_exist) > 0){
      $get_user = select('net_banking_users',$opt,$search_from_id);
      if($get_user['account_balance'] < 50){
        echo "insufficient_balance";
        exit();
      }else{
        $user_id = $get_user['id'];
        $from_parse_date = format_time(strtotime($from_date),"M,d,Y");
        $to_parse_date = format_time(strtotime($to_date),"M,d,Y");

        $nb_txn = mysqli_query($conn, "SELECT * FROM nb_user_txn WHERE created_on BETWEEN '$from_parse_date' AND '$to_parse_date' AND nb_id_fk='$user_id'");
        if(mysqli_num_rows($nb_txn) < 1){
          echo "no_record_found";
          exit();
        }else{
          if ($get_user['bank_name'] == ""){
            echo "no_account_exist";
          }else{
            $hash = $get_user['id']."|".$from_date."|".$to_date;
            echo encryption('encrypt',$hash);
          }
        }
      }
    }else{
      echo "no_user_exist";
    }

  }


}

 ?>
