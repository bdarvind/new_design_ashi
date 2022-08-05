
<?php

include 'includes/config.php';
session_start();
if(isset($_SESSION['csp']) && $_SESSION['csp'] != ""){
  header('Location:dashboard.php');
}

if (isset($_POST['login'])) {
$mobile_number = $_POST['mobile_number'];
$password = md5($_POST['password']);
$query = mysqli_query($conn, "SELECT * FROM csp WHERE mobile_number='$mobile_number' AND password='$password' AND status='active'");
if (mysqli_num_rows($query) > 0) {
  $row = mysqli_fetch_assoc($query);
  $_SESSION['csp'] = $row['id'];
  header('Location:dashboard.php');
}else{
    $_SESSION['error'] = "Incorrect Password/Inactive Account";
}
}

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ASHI DIGITAL PAY</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="images/logo_black.png" style="display:block;margin-left:auto;margin-right:auto;">
                </div>
                <h6 class="font-weight-light">Sign in to continue.</h6>

                <?php if(isset($_SESSION['error'])): ?>
                  <div class="w3-panel w3-red w3-round w3-padding w3-margin">
                      <?php echo $_SESSION['error'];
                          unset($_SESSION['error']);
                      ?>
                  </div>
                <?php endif; ?>

                <form class="pt-3" action="" method="post">
                  <div class="form-group">
                    <input type="text" name="mobile_number" class="form-control form-control-lg" placeholder="Mobile Number" required>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                  </div>
                  <div class="mt-3">
                    <input type="submit" name="login" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" value="Login">
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>
                    <a href="#" class="auth-link text-black">Forgot password?</a>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="register.html" class="text-primary">Create</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
<?php include ('includes/footer.php'); ?>