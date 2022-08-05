<?php

    include 'includes/config.php';
    session_start();
    if(!isset($_SESSION['csp'])){
      echo "ERROR OCCURED";
      exit();
    }
    $title = "Welcome to CSP Portal | ".$site_name;
    include 'includes/functions.php';

    $payment_id = $_GET['id'];
    
    $txn = select('csp_wallet_txn','txn_id',$payment_id);
    
    $coountno = $txn['bene_account_no'];
    $ifsccode = $txn['bene_ifsc_code'];
    if($txn['status']=="Failed")
    {
      $paymentstatus = 'Payment Failed';
	  $imagestatus = 'https://i.imgur.com/ENLECcF.png';
    }elseif($txn['status']=="success")
    {
      $paymentstatus = 'Payment Successfull';
	  $imagestatus = 'https://i.imgur.com/5jJDBXL.png';
    }else{
      $paymentstatus = 'Payment Process';
	  $imagestatus = 'https://i.imgur.com/PdnEs3I.png';
    }
    $bank_ref_no = "";
    if($txn['purpose'] == "va_credit"){
        $bank_ref_no = $txn['txn_id'];
    }else if($txn['purpose'] == "bank_verify"){
        $bank_ref_no = $txn['txn_id'];
    }else if($txn['purpose'] == "money_transfer"){
        $bank_ref_no = select('money_transfer','reference_number',$txn['txn_id'])['bank_ref_num'];
    }else if($txn['purpose'] == "settlement"){
        $bank_ref_no = select('money_transfer','reference_number',$txn['txn_id'])['bank_ref_num'];
    }else{
        $bank_ref_no = $txn['txn_id'];
    }
    
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php if (isset($title)): ?>
      <?php echo $title; ?>
    <?php else: ?>
      Welcome to CSP Portal | <?php echo $site_name; ?>
    <?php endif; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="https://gocash.net.in/include/assets/images/icon/logo.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="include/css/style.css?v=1">
    <link rel="stylesheet" href="include/css/theme.css?v=1">
  </head>
    <style type="text/css" media="print">
      @page {
          size: auto;   /* auto is the initial value */
          margin: 0;  /* this affects the margin in the printer settings */
      }
    </style>
  <body>
      
      <div class="w3-content w3-border">
          <div class="w3-bar w3-theme">
              <p class="w3-center w3-large"><b><?php echo $site_name; ?></b></p>
          </div>
          
          <div class="w3-section w3-center">
              <img src="<?php echo $imagestatus;?>" style="width:120px;" class="w3-image"> <br>
              <p class="w3-opacity-min"><b><?php echo $paymentstatus;?><!-- Payment Successfull --></b></p>
          </div><hr>
          <div class="w3-section">
              <table class="w3-table">
                    <?php if($txn['csp_id_fk'] !== NULL): 
                        $nb_user = select('csp','id',$txn['csp_id_fk']);    
                    ?>
                        <tr>
                          <td><b>CSP Name</b></td>
                          <td><?php echo $nb_user['name']; ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if($txn['nb_id_fk'] !== NULL): 
                        $nb_user = select('net_banking_users','id',$txn['nb_id_fk']);    
                    ?>
                        <tr>
                          <td><b>Customer Name</b></td>
                          <td><?php echo $nb_user['member_name']; ?></td>
                        </tr>
                    <?php endif; ?>
                    
                    <tr>
                      <td><b>Transaction ID</b></td>
                      <td><?php echo $txn['txn_id']; ?></td>
                    </tr>
                    <tr>
                      <td><b>Bank Ref. No.</b></td>
                      <td><?php echo $bank_ref_no; ?></td>
                    </tr>
                    <tr>
                      <td><b>Account</b></td>
                      <td><?php echo $coountno; ?></td>
                    </tr>
                    <tr>
                      <td><b>IFSC</b></td>
                      <td><?php echo $ifsccode; ?></td>
                    </tr>
                    <tr>
                      <td><b>Amount</b></td>
                      <td><?php echo $txn['amount']; ?></td>
                    </tr>
                    <tr>
                      <td><b>Service Charge</b></td>
                      <td><?php echo $txn['fees']; ?></td>
                    </tr>
                    <tr>
                      <td><b>GST</b></td>
                      <td><?php echo $txn['gst']; ?></td>
                    </tr>
                    <tr>
                      <td><b>Purpose</b></td>
                      <td><?php echo $txn['purpose']; ?></td>
                    </tr>
                    <tr>
                      <td><b>Note</b></td>
                      <td><?php echo $txn['note']; ?></td>
                    </tr>
                    <tr>
                      <td><b>Date</b></td>
                      <td><?php echo $txn['created_on']; ?></td>
                    </tr>
                    <?php if($_GET['p'] == "no"): ?>
                    <tr>
                        <td></td>
                        <td><b><a href="view-invoice.php?id=<?php echo $_GET['id']; ?>" target="_blank" id="print_btn" class="w3-btn w3-green w3-round w3-passing-16">Download Receipt</a></b></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td></td>
                        <td><b><a onclick="printpage()" id="print_btn" class="w3-btn w3-green w3-round w3-passing-16">Print Receipt</a></b></td>
                    </tr>
                    <?php endif; ?>
              </table>
          </div><hr>
          <div class="w3-section w3-center">
              <p>For Any Problem, Contact us on <a class="w3-text-blue" href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a><br><br>
              </p>
          </div>
          <div class="w3-section w3-center">
              This receipt is automatically generated, Signature not required.
              <hr>
              <p class="w3-small w3-opacity-min"><b><?php echo $address; ?></b></p>
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