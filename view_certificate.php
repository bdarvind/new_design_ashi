<?php
include 'include/config.php';
if(!isset($_GET['id']) || $_GET['id'] == ""){
    header('Location:dashboard.php');
}
$title = "Welcome to CSP Portal | ".$site_name;
include 'include/functions.php';

$nb_user = select('net_banking_users','id',$_GET['id']);
$nb_proof = select('nb_user_proof','nb_id_fk',$_GET['id']);

if($nb_user['bank_name'] == ""){
    echo "<center><h1>Unable to Print the Certificate</h1></center>";
    die();
}

$nb_id = $_GET['id'];
$nb_bank = $nb_user['bank_name'];
$va = mysqli_query($conn, "SELECT $nb_bank FROM virtual_account WHERE user_type='net_banking_users' AND user_id='$nb_id'");
$va_row = unserialize(mysqli_fetch_assoc($va)[$nb_bank]);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title><?php echo $nb_user['member_name']; ?> - Download Certificate</title>
    <style type="text/css" media="print">
      @page {
          size: auto;   /* auto is the initial value */
          margin: 0;  /* this affects the margin in the printer settings */
      }
    </style>
  </head>
  <body>

    <div class="w3-content w3-container w3-padding-16">

        <div class="w3-topbar w3-rightbar w3-bottombar w3-leftbar w3-padding w3-padding-16">
                <?php

                if($nb_user['bank_name'] == "icici_bank"){
                    $logo = "https://www.searchpng.com/wp-content/uploads/2019/01/ICICI-Bank-PNG-Logo.png";
                }else if($nb_user['bank_name'] == "yes_bank"){
                    $logo = "https://m-yescart.yesbank.in/assets/images/logo1.png";
                }else if($nb_user['bank_name'] == "rbl_bank"){
                    $logo = "https://drws17a9qx558.cloudfront.net/website/images/logo.png";
                }else if($nb_user['bank_name'] == "kotak_bank"){
                    $logo = "https://www.kotak.com/content/dam/Kotak/xkotak-logo.png.pagespeed.ic.AmAEgGV001.png";
                }else if($nb_user['bank_name'] == "dbs_bank"){
                    $logo = "https://www.dbs.com/in/iwov-resources/flp/splitter/images/dbs_logo.svg";
                }

                ?>
                <div class="w3-bar">
                    <div class="w3-bar-item w3-left">
                        <img src="<?php echo $site_logo; ?>" class="w3-image" style="width:70px;">
                        <img src="<?php echo $logo; ?>" class="w3-image" style="width:90px;">
                        <h6><b><?php echo $site_name; ?></b></h6>
                        <p><b>Contact No - </b> <?php echo $phone; ?></p>
                        <p><b>Address - </b> <?php echo $address; ?></p>
                    </div>
                    <div class="w3-bar-item w3-right">
                        <img src="https://dummyimage.com/132x170/cccccc/fff.png&text=Passport+Image" class="w3-image" >
                    </div>
                </div>
                <hr>
                <table class="w3-table">
                    <tr>
                        <td><b>V Account Number</b></td>
                        <td><?php echo $va_row['account_number']; ?></td>
                    </tr>
                    <tr>
                        <td><b>IFSC Code</b></td>
                        <td><?php echo  $va_row['account_ifsc']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Customer Name</b></td>
                        <td><?php echo  $nb_user['member_name']; ?></td>
                    </tr>
                    <tr>
                        <td><b>S/O</b></td>
                        <td><?php echo  $nb_user['member_father_name']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Address</b></td>
                        <td><?php echo  $nb_user['address']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Date Of Issue</b></td>
                        <td><?php echo $nb_user['created_on']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Date Of Birth</b></td>
                        <td><?php echo  $nb_user['date_of_birth']; ?></td>
                    </tr>
                    <tr>
                        <td><b>CRN No.</b></td>
                        <td><?php echo $crn_code.$nb_user['mobile_number']; ?></td>
                    </tr>
                    <tr>
                        <td><b>CC Code</b></td>
                        <td><?php echo "CSP100".$nb_user['csp_id_fk']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Branch Name</b></td>
                        <td>Mumbai</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><b><a onclick="printpage()" id="print_btn" class="w3-btn w3-green w3-round w3-passing-16">Print Certificate</a></b></td>
                    </tr>
                </table>
        </div>

    </div>


    <script type="text/javascript">
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("print_btn");
        //Set the print button visibility to 'hidden'
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        //Set the print button to 'visible' again
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
    }
</script>
  </body>
</html>
