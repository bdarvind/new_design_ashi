<?php
include 'include/config.php';
if(!isset($_GET['token']) || $_GET['token'] == ""){
    header('Location:dashboard.php');
}
$title = "Welcome to CSP Portal | ".$site_name;
include 'include/functions.php';

$token = explode("|",encryption('decrypt',$_GET['token']));
$user_id = $token[0];
$from_date = format_time(strtotime($token[1]),"M,d,Y");
$to_date = format_time(strtotime($token[2]),"M,d,Y");





$nb_user = select('net_banking_users','id',$user_id);
$nb_proof = select('nb_user_proof','nb_id_fk',$user_id);

if($nb_user['bank_name'] == ""){
    echo "<center><h1>Unable to Print the Certificate</h1></center>";
    die();
}

$nb_id = $user_id;
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
    <title><?php echo $nb_user['member_name']; ?> - Statement</title>
    <style type="text/css" media="print">
      @page {
          size: auto;   /* auto is the initial value */
          margin: 0;  /* this affects the margin in the printer settings */
      }
    </style>
  </head>
  <body>

    <div class="w3-content w3-container ">
      <div class="w3-bar w3-margin-bottom">
        <a href="index.php" id="back_btn" class="w3-btn w3-bar-item w3-left w3-amber w3-round">Back</a>
        <a onclick="printpage(<?php echo $user_id; ?>)" id="print_btn" class="w3-btn w3-bar-item w3-right w3-green w3-round">Print Certificate</a>
      </div>
        <div class="w3-topbar w3-rightbar w3-bottombar w3-leftbar w3-padding">
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
                        <img src="<?php echo $site_logo; ?>" class="w3-image" style="width:80px;">
                        <h6><b><?php echo $site_name; ?></b></h6>
                        <p><b>Contact No - </b> <?php echo $phone; ?></p>
                        <p><b>Address - </b> <?php echo $address; ?></p>
                    </div>

                </div>
                <hr>
                <table class="w3-table">
                    <tr>
                        <td><b>Account Number</b></td>
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
                        <td><b>CRN No.</b></td>
                        <td><?php echo $crn_code.$nb_user['mobile_number']; ?></td>
                    </tr>
                    <tr>
                        <td><b>Branch Code.</b></td>
                        <td><?php echo "CSP100".$nb_user['csp_id_fk']; ?></td>
                    </tr>

                </table>
        </div>

        <table class="w3-table w3-striped" style="border:1px black solid;">
        <thead class="w3-red">
          <tr>
            <th>S.no.</th>
            <th>DATE CREATED</th>
            <th>TYPE</th>
            <th>TXN ID</th>
            <th>Remarks</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>

        <?php
        $nb_id = $user_id;
        $i = 1;
        $nb_txn = mysqli_query($conn, "SELECT * FROM nb_user_txn WHERE created_on BETWEEN '$from_date' AND '$to_date' AND nb_id_fk='$user_id'");
          while ($txn = mysqli_fetch_assoc($nb_txn)) {
            echo "
            <tr>
              <td>".$i++."</td>
              <td>".$txn['created_on']."</td>
              <td>".$txn['type']."</td>
              <td>".$txn['txn_id']."</td>
              <td>".$txn['note']."</td>
              <td>".number_format($txn['amount'],2)."</td>
            </tr>
            ";
          }
         ?>
       </tbody>
       </table>

    </div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  function printpage(user_id) {
      $.ajax({
        url:'parse/update_user.php',
        type:"POST",
        dataType:'text',
        data:{
          'user_id':user_id,
          'type':'statement'
        },
        success: function(data){
          if(data == "success"){
            var printButton = document.getElementById("print_btn");
            var backButton = document.getElementById("back_btn");

            printButton.style.visibility = 'hidden';
            backButton.style.visibility = 'hidden';

            window.print();

            printButton.style.visibility = 'visible';
            backButton.style.visibility = 'visible';
          }else{
            alert('Error While Printing Statement');
          }
        }
      });

  }
</script>

  </body>
</html>
