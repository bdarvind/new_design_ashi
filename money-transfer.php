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
                <div style="padding:20px;" class="card">

                  <h3>Money Transfer</h3>
                  </div>
                  </div>
            
            
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                  <form action="parse/bbps_recharge/recharge.php" id="recharge_form" method="POST">

                      <div class="form-group">
                        <label>Beneficiary Account Number</label>
                        <input type="password" name="account_number" class="form-control form-control-sm" placeholder="Account Number" aria-label="account" required>
                      </div>

                      <div class="form-group">
                        <label>Confirm Account Number</label>
                        <input type="text" name="account_number" class="form-control form-control-sm" placeholder="Confirm Account Number" aria-label="account" required>
                      </div>

                      <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control form-control-sm" placeholder="Bank IFSC Code" aria-label="ifsc_code" required>
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