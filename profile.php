<?php
include '../include/config.php';
session_start();

if(!isset($_SESSION['csp'])){
  header('Location:index.php');
}
$title = "Welcome to CSP Portal | ".$site_name;
include '../include/functions.php';

$user = select('csp','id',$_SESSION['csp']);

$settings = select('settings','id',1);


?>


  <?php include('includes/header.php'); ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        
        <?php include ('includes/sidebar.php'); ?>

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="card">
              <div class="card-body">
            <h3 class="card-title">Profile</h3> <hr/> <br/>

            
            <?php
                        
                        if(isset($_SESSION['profile_update']) && !empty($_SESSION['profile_update'])){
                            echo $_SESSION['profile_update'];
                            unset($_SESSION['profile_update']);
                        }
                    
                    ?>


            <form style="font-family:calibri;" action="../parse/save_profile.php" method="POST">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Name</label>
                            <input type="text" class="form-control" placeholder="Enter your Name..." value="<?php echo ucwords($user['name']); ?>" required>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Email</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['email']); ?>" required>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Mobile</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['mobile_number']); ?>" required>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Address</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['address']); ?>" required>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Office Address</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['office_address']); ?>" required>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">PIN Code</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['pin_code']); ?>" required>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Shop Area Name</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['shop_area_name']); ?>" required>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">PAN Card</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['pan_card']); ?>" disable readonly>
                        </div>
                    </div>

                </div>


                    <div class="col">
                        <div class="form-group">
                            <label for="formGroupExampleInput">Adhaar Card</label>
                            <input type="text" class="form-control" value="<?php echo ucwords($user['adhaar_card']); ?>" disable readonly>
                        </div>
                    </div>

                    
                <button type="submit" class="btn btn-success">Save Details</button>

            </form>

          </div>



          <div class="row">
              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    
                    <h4 class="card-title">CSP Details</h4><hr/>

                    <table class="table">
                      <tr>
                        <td><b>CSP Code</b></td>
                        <td><?php echo "CSP100".$user['id']; ?></td>
                      </tr>
                      <tr>
                          <td><b>Office Address</b></td>
                          <td><?php echo $user['office_address']; ?></td>
                      </tr>
                      
                      
                      <?php
                            $user_id_fk = $user['id'];
                            $get_aeps = run_query("SELECT * FROM aeps_users WHERE user_id_fk='$user_id_fk'");
                            if(mysqli_num_rows($get_aeps) > 0):
                        
                        ?>
                          <tr>
                            <td><b>Essential Service Certificate</b></td>
                            <td><a class="w3-btn w3-small w3-black w3-round" target="_blank" href="../essential_certificate.php?id=<?php echo $user['id']; ?>">Download</a></td>
                          </tr>
                      <?php endif; ?>
                    </table>
                  </div>
                </div>
              </div>



              <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Settlement Account</h4><hr/>
                    <table class="table">
                      <tr>
                        <td><b>Account Holder Name</b></td>
                        <td><?php echo $user['payout_name']; ?></td>
                      </tr>
                      <tr>
                        <td><b>Account No.</b></td>
                        <td><?php echo $user['payout_account_no']; ?></td>
                      </tr>
                      <tr>
                        <td><b>IFSC Code</b></td>
                        <td><?php echo $user['payout_ifsc']; ?></td>
                      </tr>
                    </table>
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