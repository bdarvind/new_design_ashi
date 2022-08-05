<?php
include 'includes/config.php';
session_start();

if(!isset($_SESSION['csp'])){
  header('Location:index.php');
}
$title = "Welcome to CSP Portal | ".$site_name;
include 'includes/functions.php';

$user = select('csp','id',$_SESSION['csp']);
?>
<?php include "security.php"; ?>


  <?php include('includes/header.php'); ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        
        <?php include ('includes/sidebar.php'); ?>

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">


            <div class="col-md-12 grid-margin stretch-card">
                <div style="padding:20px;background:#1953AD;color:white;font-family:calibri;font-weight: bold;" class="card">

                  <h3>Adhaar Pay</h3>
                  </div>
                  </div>
            
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                  <nav class="nav nav-pills nav-fill">
                        <a style="padding:20px;margin-right:5px;" class="nav-item nav-link active" href="#">CASH WITHDRAWAL</a>
                        <a style="border:1px solid;padding:20px;margin-left:5px;" class="nav-item nav-link" href="adhaar-status.php">STATUS</a>
                    </nav> <br/>


                  <form action="parse/bbps_recharge/recharge.php" id="recharge_form" method="POST">

                      <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="password" name="account_number" class="form-control form-control-sm" placeholder="Mobile Number" aria-label="account" required>
                      </div>

                      <div class="form-group">
                        <label>Customer Adhaar Number</label>
                        <input type="text" name="account_number" class="form-control form-control-sm" placeholder="Customer Adhaar Number" aria-label="account" required>
                      </div>

                      <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" name="ifsc_code" class="form-control form-control-sm" placeholder="Select Bank Name" aria-label="ifsc_code" required>
                      </div>
                      

                      <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control form-control-sm" placeholder="Amount" aria-label="Amount" reuired>
                    
                        <div class="form-group row">

                          <div class="col-sm-2">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input type="radio" name="type" value="prepaid"  class="form-check-input"> 100 </label>
                              </div>
                          </div>
                          <div class="col-sm-2">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="type" value="postpaid"> 500 </label>
                              </div>
                          </div>
                          <div class="col-sm-2">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input type="radio" name="type" value="prepaid"  class="form-check-input"> 1000 </label>
                              </div>
                          </div>
                          <div class="col-sm-2">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="type" value="postpaid"> 2000 </label>
                              </div>
                          </div>
                          <div class="col-sm-2">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input type="radio" name="type" value="prepaid"  class="form-check-input"> 5000 </label>
                              </div>
                          </div>
                          <div class="col-sm-2">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="type" value="postpaid"> 10000 </label>
                              </div>
                          </div>
                        </div>

                    </div>

                    <input style="font-family:calibri;font-size:12px;" type="checkbox"> I, the undersigned, the holder of the AADHAAR Number hereby give my consent to ASHI DIGITAL PAY to obtain my Aaadhaar Number and biometrics for authentication with Unique Identification Authority of India (UIDAI). ASHI DIGITAL PAY has informed me that my identity information will be used only for AEPS (Aadhaar Enabled Payment System) Cash Withdrawal purpose and that my biometrics will not be stored/shared by ASHI DIGITAL PAY and will be submitted to Central Identity Data Repository (CIDR) only for purpose of authenticating my identity. <br/><br/>

                      <button type="submit" class="btn btn-gradient-primary mr-2">Proceed to Recharge</button>

                    </form>

                  </div>
                  
                </div>
              </div>

            </div>
            
        </div>
    </div>
</div>
          <!-- content-wrapper ends -->
<?php include('includes/footer.php'); ?>

<script>
$('#myModal').modal(options)
</script>