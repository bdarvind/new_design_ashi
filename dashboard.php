<?php
include 'includes/config.php';
session_start();

if(!isset($_SESSION['csp'])){
  header('Location:login.php');
}
$title = "Welcome to CSP Portal | ".$site_name;
include 'includes/functions.php';

$user = select('csp','id',$_SESSION['csp']);
$settings = select('settings','id',1);

if(isset($_POST['search']))
{
    $fromdate = $_POST['fromdate'];
    $todate  = $_POST['todate'];
    /*$fromdate = date('M,d,Y',strtotime($fromdate));
    $todate = date('M,d,Y',strtotime($todate));*/
    
    $fromdate = date('Y-m-d',strtotime($fromdate));
    $todate = date('Y-m-d',strtotime($todate . "+1 days"));
    $sqltran = mysqli_query($conn,"select * from csp_wallet_txn where (csp_id_fk ='".$_SESSION['csp']."') AND (creation_time >= '".$fromdate." 00:00:00' 
AND creation_time <= '".$todate." 00:00:00')");
}else{
    $sqltran = mysqli_query($conn,"select * from csp_wallet_txn where csp_id_fk ='".$_SESSION['csp']."' ORDER BY id DESC LIMIT 30");
}

//echo "no of rows ".mysqli_num_rows($sql);
include('security2.php');

include('includes/header.php');
?>

  
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        
        <?php include ('includes/sidebar.php'); ?>

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">


              <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white" style="height:150px;">
                  <div class="card-body">
                    <h3 class="mb-2"><?php echo number_format($user['account_balance'],2); ?></h3>
                    <h5 class="font-weight-normal mb-1"><?php echo constant('dashboard_Balance'); ?>
                    </h5>
                  </div>
                  <button onclick="document.getElementById('withdraw_modal').style.display='block'" style="padding:5px;" type="submit" class=" btn btn-gradient-primary mr-1">Withdraw</button>
                </div>
              </div>

              

              <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white" style="height:150px;">
                  <div class="card-body">
                  
                    <h3 class="mb-2">3490</h3>

                    <h5 class="font-weight-normal mb-1"><?php echo constant('dashboard_Day_Book'); ?></h5> <!--<i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>-->
                    

                  </div>
                </div>
              </div>

              

              <div class="col-md-3 stretch-card grid-margin">
              
                <div class="card bg-gradient-warning card-img-holder text-white" style="height:150px;">
                  <div class="card-body">

                  
                  <h3 class="mb-2"><?php echo number_format($user['new_wallet_balance'],2); ?></h3>

                    <h5 class="font-weight-normal mb-1"><?php echo constant('dashboard_Wallet_Passbook'); ?></h5> <!-- <i class="mdi mdi-diamond mdi-24px float-right"></i> -->
                    
                  </div>

                  <button onclick="document.getElementById('new_withdraw_modal').style.display='block'" style="padding:5px;" type="submit" class=" btn btn-gradient-danger mr-1">Transfer to Wallet</button>

                </div>
              </div>


              <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white" style="height:150px;">
                  <div class="card-body">

                  <h3 class="mb-2">1500</h3>

                    <h5 class="font-weight-normal mb-1"><?php echo constant('dashboard_Fund_Request'); ?></h5> <!-- <i class="mdi mdi-diamond mdi-24px float-right"></i>-->
                
                    
                  </div>
                </div>
              </div>


                  <div style="background:#37BB24;color:white;margin-left:20px;margin-right:20px;border-radius:5px;" class="card-body">

                  <marquee class="" behavior="scroll" direction="left" scrollamount="5"><img style="width:20px;" src="images/info.png"> Hi! <?php echo $user['name']; ?>, <?php echo $settings['marquee_text']; ?></marquee>
                  </div>
                  <br/>

            </div>
            <br/>

            <div class="row">
              <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">


                  <div class="row">
                    <a href="recharge.php">
                      <div onclick="location.href='recharge.php';" style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                        <img src="images/icons/recharge.png" style="width:100px; height:100px;pointer-events:none;"></a>
                        <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Recharge'); ?></p>
                      </div>
                    </a>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/pancard.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_PAN_Card'); ?></p>
                    </div>
                    <div onclick="location.href='money-transfer.php';" style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/money_transfer.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;clear:both;display:inline-clock;overflow:hidden;white-space:nowrap;"><?php echo constant('dashboard_Image_Money_Transfer'); ?></p>
                    </div>

                    <div onclick="location.href='adhaar-pay.php';" style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/adhar_pay.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Adhaar_Pay'); ?></p>
                    </div>
                    
                  </div> <br/>


                  <div class="row">
                      <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                        <img src="images/icons/loan.png" style="width:100px; height:100px;pointer-events:none;"></a>
                        <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Loan'); ?></p>
                      </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/irctc.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Train_Tickets'); ?></p>
                    </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/insurance.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Insurance'); ?></p>
                    </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/gst.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_GST'); ?></p>
                    </div>
                  </div> <br/>


                  <div class="row">
                      <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                        <img src="images/icons/deposit.png" style="width:100px; height:100px;pointer-events:none;"></a>
                        <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Cash_Deposit'); ?></p>
                      </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/bbps.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_BBPS'); ?></p>
                    </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/cms.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;clear:both;display:inline-clock;overflow:hidden;white-space:nowrap;"><?php echo constant('dashboard_Image_CMS'); ?></p>
                    </div>

                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/matm.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_M-ATM'); ?></p>
                    </div>
                  </div> <br/>


                  <div class="row">
                      <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                        <img src="images/icons/aeps.png" style="width:100px; height:100px;pointer-events:none;"></a>
                        <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_AEPS'); ?></p>
                      </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/icon14.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_CSP'); ?></p>
                    </div>

                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/recharge.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_Others'); ?></p>
                    </div>
                    <div style="border:solid 1px #CECFCF;border-radius:5px;padding:5px;text-align:center;margin:5px;" class="col">
                      <img src="images/icons/icon14.png" style="width:100px; height:100px;pointer-events:none;">
                      <p style="text-align:center;width:100%;background:#033639;border-radius:5px;padding:5px;color:white;margin-top:10px;"><?php echo constant('dashboard_Image_More'); ?></p>
                    </div>
                  </div> <br/>


                  </div>
                </div>
              </div>
              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Traffic Sources</h4>
                    <canvas id="traffic-chart"></canvas>
                    <canvas id="visit-sale-chart" class="mt-4 chartjs-render-monitor" style="display: block; height: 111px; width: 223px;" width="446" height="222">
                  </canvas>  
                </div>
              </div>
            </div>


            <div class="row">
              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="chartjs-size-monitor">
                      <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                      </div>
                      <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                      </div>
                    </div>
                    <h4 class="card-title">Line chart</h4>
                    <canvas id="lineChart" style="height: 345px; display: block; width: 691px;" width="691" height="345" class="chartjs-render-monitor"></canvas>
                  </div>
                </div>
              </div>

              

              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="chartjs-size-monitor">
                      <div class="chartjs-size-monitor-expand">
                        <div class=""></div>
                      </div>
                      <div class="chartjs-size-monitor-shrink">
                        <div class=""></div>
                      </div>
                    </div>
                    <h4 class="card-title">Bar chart</h4>
                    <canvas id="barChart" style="height: 345px; display: block; width: 691px;" width="691" height="345" class="chartjs-render-monitor"></canvas>
                  </div>
                </div>
              </div>
            </div>


            
            
          </div>
          <!-- content-wrapper ends -->
<?php include('includes/footer.php'); ?>

<div id="new_withdraw_modal" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-top">
    <header class="w3-container w3-theme">
      <h3>Transfer Amount</h3>
      <span onclick="document.getElementById('new_withdraw_modal').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">×</span>
    </header>
    <div class="w3-container">
      <table class="w3-table w3-bordered w3-margin-top w3-border">
        <tbody><tr>
          <td><b>OD A/C Balance</b></td>
          <td>₹<?php echo number_format($user['account_balance'],2); ?></td>
        </tr>
        <tr class="w3-text-red">
          <td><b>Balance Maintain</b></td>
          <td>- ₹10</td>
        </tr>
        <tr class="w3-text-green">
          <td><b>New Wallet balance</b></td>
          <td>
            ₹<?php echo number_format($user['new_wallet_balance'],2); ?></td>
        </tr>
      </tbody>
    </table>
              <form id="transfer_form" action="" class="w3-padding-16" method="POST">
          <p class="w3-center w3-large">Transfer Form Old Wallet to New Wallet</p><hr>
          <input type="hidden" name="csrf_token" value="ak04RjlISE04SUhtWFZjTDhxYVdtUT09">
          <label><b>Amount</b></label>
          <input type="number" name="amount" class="w3-border w3-round w3-input" max="<?php echo number_format($user['new_wallet_balance'],2); ?>" placeholder="Enter Amount to Transfer in new Wallet" required="">
          <span class="w3-small w3-text-red"><b>Note - </b> Withdraw Amount should be greater than ₹100</span><br><br>
          <input type="submit" id="settlement_form_btn" name="submitnew" class="w3-btn w3-green w3-round w3-block" value="Withdraw">

        </form>
          </div>
  </div>
</div>
<style>
#new_withdraw_modal{display:none;}
.dt-buttons{clear: both!important;}
.dt-buttons .btn-secondary {
    color: #fff;
    background-color: #2196f3;
    border-color: #2196f3;
}
.dt-buttons .btn-secondary:hover,.dt-buttons .btn-secondary:focus,.dt-buttons .btn-secondary:active {
    color: #fff;
    background-color: #53adf5;
    border-color: #53adf5;
}
.jquery-modal .modal{position: absolute; left:50%; transform: translateX(-50%); height: auto;}
.modal a.close-modal {
    position: absolute;
    top: -1.5px;
    right: -2.5px;}
.w3-bar .w3-large{padding-top: 10px;}
</style>
