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
            
            
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">

                  <form action="parse/bbps_recharge/recharge.php" id="recharge_form" method="POST">

                    <div class="form-group row">
                      <label>Select Operator Type</label>
                          <div class="col-sm-4">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input type="radio" onclick="operator_type('Prepaid');" name="type" value="prepaid"  class="form-check-input"> Prepaid </label>
                              </div>
                          </div>
                          <div class="col-sm-5">
                              <div class="form-check">
                              <label class="form-check-label">
                                  <input class="form-check-input" type="radio" onclick="operator_type('Postpaid');" name="type" value="postpaid"> Postpaid </label>
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobile_number" class="form-control form-control-sm" placeholder="Mobile Number" aria-label="mobile_number">
                      </div>
                      <div class="form-group">
                        <label>Operator</label>
                          <select name="operator" class="operator form-control">
                          <option selected disabled>Choose Operator Type First</option>
                          </select>
                      </div>

                      <div class="form-group">
                        <label>Circle</label>
                          <select name="circle" class="form-control">
                          <option selected disabled>Choose Operator</option>
                          <?php
                              $query = run_query("SELECT * FROM kwik_circle");
      
                              while($service = mysqli_fetch_assoc($query)){
                                echo "<option value='".$service['circle_code']."'>".$service['circle_name']."</option>";
                              }
                          ?>
                          </select>
                      </div>

                      <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control form-control-sm" placeholder="Recharge Amount" aria-label="Amount">
                      </div>

                      <button type="submit" class="btn btn-gradient-primary mr-2">Proceed to Recharge</button>

                    </form>

                  </div>
                  
                </div>
              </div>



              <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Select a Plan</h4>
                    <div class="w3-col s12 m12 l8 w3-section">
                      <div class="w3-card  w3-round">
                          <div id="plans_insert" style="max-height:400px;min-height:400px;overflow-y:scroll;">
                          <p class="w3-padding">Plans Coming Soon</p>
                        </div>
                      </div>
                    </div>
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