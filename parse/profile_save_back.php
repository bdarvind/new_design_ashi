<?php

    session_start();
    if(!isset($_SESSION['csp'])){
      header('Location:../index.php');
    }
  include '../include/config.php';
  include '../include/functions.php';

if (isset($_POST['address']) && isset($_POST['office_address']) && isset($_POST['pin_code']) && isset($_POST['payout_ifsc']) && isset($_POST['payout_name']) && isset($_POST['payout_account_no'])) {
   
  if($_POST['address'] != "" && $_POST['office_address'] != "" && $_POST['pin_code'] != "" && $_POST['payout_ifsc'] != "" && $_POST['payout_name'] != "" && $_POST['payout_account_no'] != ""){
      
    $csp_id = $_SESSION['csp'];
    $address = escape($_POST['address']);
    $office_address = escape($_POST['office_address']);
    $pin_code = escape($_POST['pin_code']);
    $payout_ifsc = escape($_POST['payout_ifsc']);
    $payout_name = escape($_POST['payout_name']);
    $payout_account_no = escape($_POST['payout_account_no']);
    
   
    $update = run_query("UPDATE csp SET address='$address',office_address='$office_address',pin_code='$pin_code',payout_ifsc='$payout_ifsc',payout_name='$payout_name',payout_account_no='$payout_account_no' WHERE id='$csp_id' ");
    if($update){
        echo "success";
    }else{
        echo "error";
    }
    
  }else{
      echo "invalid_data";
  }
  
  
}elseif(isset($_POST['shop_area_name'])){
    
    $csp_id = $_SESSION['csp'];
    $shop_area_name = escape($_POST['shop_area_name']);
    
    if($shop_area_name != ""){
        
        $update = run_query("UPDATE csp SET shop_area_name='$shop_area_name' WHERE id='$csp_id' ");
        if($update){
            echo "success";
        }else{
            echo "error";
        }
        
    }else{
        echo "error";
    }
    
}else if(isset($_POST['pan_card']) && isset($_POST['adhaar_card'])){
    $csp_id = $_SESSION['csp'];
    $pan_card = escape($_POST['pan_card']);
    $adhaar_card = escape($_POST['adhaar_card']);
    
    if($pan_card != "" && $adhaar_card != ""){
        
        $update = run_query("UPDATE csp SET pan_card='$pan_card' , adhaar_card='$adhaar_card' WHERE id='$csp_id' ");
        if($update){
            echo "success";
        }else{
            echo "error";
        }
        
    }else{
        echo "error";
    }
    
}else{
    echo "error";
}

?>