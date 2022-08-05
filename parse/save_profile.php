<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);


    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile_number']) && isset($_POST['shop_area_name']) && isset($_POST['address']) && isset($_POST['office_address']) && isset($_POST['pin_code'])) {
        $name = mysqli_real_escape_string($conn,trim($_POST['name']));
        $email = mysqli_real_escape_string($conn,trim($_POST['email']));
        $mobile_number = mysqli_real_escape_string($conn,trim($_POST['mobile_number']));
        $shop_area_name = mysqli_real_escape_string($conn,trim($_POST['shop_area_name']));
        $address = mysqli_real_escape_string($conn,trim($_POST['address']));
        $office_address = mysqli_real_escape_string($conn,trim($_POST['office_address']));
        $pin_code = mysqli_real_escape_string($conn,trim($_POST['pin_code']));
    
        
        $update = mysqli_query($conn, "UPDATE csp SET name ='$name',email ='$email',mobile_number ='$mobile_number',shop_area_name ='$shop_area_name',address ='$address',office_address ='$office_address',pin_code ='$pin_code' WHERE id ='$csp_id' LIMIT 1");
        
        if($update){
            $_SESSION['profile_update'] = "<div class='w3-panel w3-border w3-margin w3-border-green w3-round w3-pale-green w3-padding'>Profile Updated Successfully.</div>";
        }else{
            $_SESSION['profile_update'] = "<div class='w3-panel w3-border w3-margin w3-border-red w3-round w3-pale-red w3-padding'>Unable to Update, Please try again.</div>";
        }
            
    }else{
        $_SESSION['profile_update'] = "<div class='w3-panel w3-border w3-margin w3-border-red w3-round w3-pale-red w3-padding'>Unable to Update, Please try again.</div>";
    }
    
    header('Location:/csp/new_design/profile.php');

?>
