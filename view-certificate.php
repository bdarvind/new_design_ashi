<?php
include 'includes/config.php';
if(!isset($_GET['id']) || $_GET['id'] == ""){
  header('Location:dashboard.php');
}
$title = "Welcome to CSP Portal | ".$site_name;
include 'includes/functions.php';

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

<html>

    <head>
        <title>Certificate</title>
        <style>
            .outer-border{
                width:800px; 
                height:650px; 
                padding:20px; 
                text-align:center; 
                border: 10px solid #673AB7;    
                margin-left: 21%;
            }

            .inner-dotted-border{
                width:750px; 
                height:600px; 
                padding:20px; 
                text-align:center; 
                border: 5px solid #673AB7;
                border-style: dotted;
            }

            .certification{
                font-size:50px; 
                font-weight:bold;    
                color: #663ab7;
            }

            .certify{
                font-size:25px;
            }

            .name{
                font-size:30px;    
                color: green;
            }

            .fs-30{
                font-size:30px;
            }

            .fs-20{
                font-size:20px;
            }
        </style>
    </head>

    <body>
        <div class="outer-border">
            <div class="inner-dotted-border">
                <span class="certification">Certificate of CSP</span>
                <br><br>
                <span class="certify"><i>This is to certify that</i></span>
                <br><br>
                <span class="name"><b>Daniel Vitorrie</b></span><br/><br/>
                <span class="certify"><i>has successfully completed the certification</i></span> <br/><br/>
                <span class="fs-30">Java Developer</span> <br/><br/>
                <span class="fs-20">with score of <b>A+</b></span> <br/><br/><br/><br/>
                <span class="certify"><i>dated</i></span><br>
                
                <span class="fs-30">23 March,2019</span>

            </div>
        </div>
    </body>
</html>