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
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    body{
        font-family: 'calibri';
        margin:auto;
    }
    h1{
        text-align: center;
        margin:auto;
        padding:10px;
        background-image: linear-gradient(to right, rgba(55, 187, 36), rgba(43, 149, 28));
        color:white;
    }
    .center_image{
        text-align: center;
    }
    table {
        font-family: 'calibri';
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
    .center_footer{
        text-align: center;
        margin:auto;
        padding:10px;
        background-image: linear-gradient(to right, rgba(55, 187, 36), rgba(43, 149, 28));
        color:white;
    }
    #print_btn{
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 5px 7px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
    }
</style>

<body>
<div>
    <h1>ASHI DIGITAL PAY</h1><br/>
    <div class="center_image">
              <img src="<?php echo $imagestatus;?>" style="width:120px;" class="w3-image"> <br>
              <p class="w3-opacity-min"><b><?php echo $paymentstatus;?><!-- Payment Successfull --></b></p>
          </div><br/>

    <table>
        <?php if($txn['csp_id_fk'] !== NULL): 
            $nb_user = select('csp','id',$txn['csp_id_fk']);    
        ?>
        <tr>
            <td>CSP NAME</td>
            <td><?php echo $nb_user['name']; ?></td>
        </tr>
        <?php endif; ?>

        <?php if($txn['nb_id_fk'] !== NULL): 
            $nb_user = select('net_banking_users','id',$txn['nb_id_fk']);    
        ?>
        <tr>
            <td>Customer Name</td>
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
            <td><b><a href="view_invoice_new.php?id=<?php echo $_GET['id']; ?>" target="_blank" id="print_btn" class="w3-btn w3-green w3-round w3-passing-16">Download Receipt</a></b></td>
        </tr>
        <?php else: ?>
        <tr>
            <td></td>
            <td><b><a onclick="printpage()" id="print_btn" class="w3-btn w3-green w3-round w3-passing-16">Print Receipt</a></b></td>
        </tr>

        <?php endif; ?>
    </table>
</div>

<hr>
          <div class="center_footer">
              <p>For Any Problem, Contact us on <a class="w3-text-blue" href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a><br><br>
              </p>
          </div>
          <div class="center_footer">
              This receipt is automatically generated, Signature not required.
              <hr>
              <p class="center_footer"><b><?php echo $address; ?></b></p>
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