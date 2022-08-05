<?php


if(isset($_GET['lang'])){
	$_SESSION['lang'] = $_GET['lang'];
}
elseif (!$_SESSION['lang']){
	$_SESSION['lang'] = 'en';
}
include ('languages/'.$_SESSION['lang'].'.php');
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags -->

    <!-- This Template Designed and Developed by Arvind Kumar -->
    <!-- AWS NextStep -->
    <!-- New Design for Ashi Digital Pay -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php if (isset($title)): ?>
      <?php echo $title; ?>
    <?php else: ?>
      Welcome to CSP Portal | <?php echo $site_name; ?>
    <?php endif; ?></title>

    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="include/css/style.css?v=4">
    <link rel="stylesheet" href="include/css/theme.css?v=4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dataTables.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />

    

    <style>
#new_withdraw_modal{display:none;background:white;}
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
.jquery-modal .modal{position: absolute; left:50%; transform: translateX(-50%); height: auto; background:white;}
.modal a.close-modal {
  background:white;
    position: absolute;
    top: -1.5px;
    right: -2.5px;}
.w3-bar .w3-large{padding-top: 10px;}

.mynewClass{
  content: '->';
  position: absolute;
  left: 10.5em;
  background: #fff;
}

</style>


  </head>

  <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
//s1.src='https://embed.tawk.to/5f8aa46ff91e4b431ec53a5a/default';
s1.src='https://embed.tawk.to/61140eb5649e0a0a5cd0ae80/1fcr5itul';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


  <body>
    <div class="container-scroller">
      
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="index.html"><img src="images/logo.png" alt="logo" /></a>
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>

          

          <div class="search-field d-none d-md-block">
            <div class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </div>
              </div>
            </div>
          </div>



          <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
              <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                  <i class="input-group-text border-0 mdi mdi-magnify"></i>
                </div>
               <!--- <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects"> -->
              </div>
            </form>
          </div>



          <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="images/language/flags/hi.png" style="width:30px" class="mdi mdi-english-outline"></i>
                
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Languages</h6>
                <div class="dropdown-divider"></div>
                
                <a onclick='change_lang("hi")' class="dropdown-item preview-item">

                <div class="preview-thumbnail">
                    <img src="images/language/flags/hi.png" alt="image" class="profile-pic">
                  </div>

                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal" >हिन्दी</h6>
                  </div>
                </a>


                <a onclick='change_lang("en")' class="dropdown-item preview-item">

                <div class="preview-thumbnail">
                    <img src="images/language/flags/en.png" alt="image" class="profile-pic">
                  </div>

                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal" >English</h6>
                  </div>
                </a>



                <a onclick='change_lang("mr")' class="dropdown-item preview-item">

                <div class="preview-thumbnail">
                    <img src="images/language/flags/mr.png" alt="image" class="profile-pic">
                  </div>

                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal" >मराठी</h6>
                  </div>
                </a>


                <a onclick='change_lang("gu")' class="dropdown-item preview-item">

                <div class="preview-thumbnail">
                    <img src="images/language/flags/gu.jpg" alt="image" class="profile-pic">
                  </div>

                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal" >ગુજરાતી</h6>
                  </div>
                </a>


                </div>
            </li>


            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count-symbol bg-danger"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <h6 class="p-3 mb-0">Notifications</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-success">
                      <i class="mdi mdi-calendar"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">New Transaction</h6>
                    <p class="text-gray ellipsis mb-0"> <?php echo number_format($user['account_balance'],2); ?> </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-warning">
                      <i class="mdi mdi-settings"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                    <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-info">
                      <i class="mdi mdi-link-variant"></i>
                    </div>
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                    <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <h6 class="p-3 mb-0 text-center">See all notifications</h6>
              </div>
            </li>


            <li class="nav-item dropdown">
              <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-email-outline"></i>
                <span class="count-symbol bg-warning"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                <h6 class="p-3 mb-0">Messages</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face4.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a message</h6>
                    <p class="text-gray mb-0"> 1 Minutes ago </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face2.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a message</h6>
                    <p class="text-gray mb-0"> 15 Minutes ago </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face3.jpg" alt="image" class="profile-pic">
                  </div>
                  <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                    <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture updated</h6>
                    <p class="text-gray mb-0"> 18 Minutes ago </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <h6 class="p-3 mb-0 text-center">4 new messages</h6>
              </div>
            </li>
            


            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="assets/images/faces/face1.jpg" alt="image">
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="#">
                  <i class="mdi mdi-cached me-2 text-success"></i> <?php echo constant('sidebar_Edit_Profile'); ?> </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                  <i class="mdi mdi-cached me-2 text-success"></i> <?php echo constant('sidebar_Certificate'); ?> </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../essential_certificate.php?id=<?php echo $user['id']; ?>">
                  <i class="mdi mdi-cached me-2 text-success"></i> <?php echo constant('sidebar_ID_Card'); ?> </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">
                  <i class="mdi mdi-cached me-2 text-success"></i> <?php echo constant('sidebar_Invoice'); ?> </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">
                  <i class="mdi mdi-logout me-2 text-primary"></i> <?php echo constant('sidebar_Logout'); ?> </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>