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


  <style>

body{
  padding:100px 0;
  background-color:#efefef
}
a, a:hover{
  color:#333
}

  </style>

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

            <div class="container">
            <div class="row justify-content-center">
                <div class="col-10">

                <form id="change_password_form" action="../parse/change_password.php" method="post">
                <div class="form-group">
                    <label>Current Password</label>
                    <div class="input-group mb-3" id="show_hide_password">
                    <input class="form-control" type="password" name="current_password" placeholder="Enter Current Password">

                    <div class="input-group-append">
                        <span class="input-group-text">
                        <a href=""><i class="mdi mdi-eye-off" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <div class="input-group mb-3" id="show_hide_password">
                    <input class="form-control" type="password" name="new_password" placeholder="Enter New Password">

                    <div class="input-group-append">
                        <span class="input-group-text">
                        <a href=""><i class="mdi mdi-eye-off" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="input-group mb-3" id="show_hide_password">
                    <input class="form-control" type="password" name="confirm_password" placeholder="Enter New Password">

                    <div class="input-group-append">
                        <span class="input-group-text">
                        <a href=""><i class="mdi mdi-eye-off" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>

                <button type="submit" name="submit" id="cp_btn" class="btn btn-success">Save Details</button>

                </form>
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


<script id="rendered-js" >
$(document).ready(function () {
  $("#show_hide_password a").on('click', function (event) {
    event.preventDefault();
    if ($('#show_hide_password input').attr("type") == "text") {
      $('#show_hide_password input').attr('type', 'password');
      $('#show_hide_password i').addClass("mdi-eye-off");
      $('#show_hide_password i').removeClass("mdi-eye");
    } else if ($('#show_hide_password input').attr("type") == "password") {
      $('#show_hide_password input').attr('type', 'text');
      $('#show_hide_password i').removeClass("mdi-eye-off");
      $('#show_hide_password i').addClass("mdi-eye");
    }
  });
});
//# sourceURL=pen.js
    </script>