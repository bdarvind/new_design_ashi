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
                        <a style="border:1px solid;padding:20px;margin-right:5px;" class="nav-item nav-link" href="adhaar-pay.php">CASH WITHDRAWAL</a>
                        <a style="padding:20px;margin-left:5px;"class="nav-item nav-link active" href="#">STATUS</a>
                    </nav> <br/>


                  <form action="parse/bbps_recharge/recharge.php" id="recharge_form" method="POST">

                      <div class="form-group">
                        <label>Reference Number</label>
                        <input type="password" name="account_number" class="form-control form-control-sm" placeholder="Enter Reference Number" aria-label="account" required>
                      </div>

                      <button type="submit" class="btn btn-gradient-primary mr-2">Search Status</button>

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