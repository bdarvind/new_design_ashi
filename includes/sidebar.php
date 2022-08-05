<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            
          <li class="nav-item">
              <a class="nav-link" href="dashboard.php">
                <span class="menu-title"><?php echo constant('sidebar_dashboard'); ?></span>
                <i class="mdi mdi-view-dashboard menu-icon"></i>
              </a>
            </li>


            <li class="nav-item">
              <a class="nav-link" href="reports.php">
                <span class="menu-title"><?php echo constant('sidebar_report'); ?></span>
                <i class="mdi mdi-chart-bar menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="recharge_history.php">
                <span class="menu-title"><?php echo constant('sidebar_Recharge_History'); ?></span>
                <i class="mdi mdi-cellphone-android menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="bbps_history.php">
                <span class="menu-title"><?php echo constant('sidebar_BBPS_History'); ?></span>
                <i class="mdi mdi-spotify menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="aeps_history.php">
                <span class="menu-title"><?php echo constant('sidebar_AEPS_History'); ?></span>
                <i class="mdi mdi-crop-free menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="dmt_history.php">
                <span class="menu-title"><?php echo constant('sidebar_DMT_History'); ?></span>
                <i class="mdi mdi-arrow-right-bold menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="pages/forms/basic_elements.html">
                <span class="menu-title"><?php echo constant('sidebar_M-ATM_History'); ?></span>
                <i class="mdi mdi-undo-variant menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="pages/forms/basic_elements.html">
                <span class="menu-title"><?php echo constant('sidebar_Adhaar_Pay_History'); ?></span>
                <i class="mdi mdi-arrow-right-bold menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="pages/forms/basic_elements.html">
                <span class="menu-title"><?php echo constant('sidebar_Cash_Withdrawal_History'); ?></span>
                <i class="mdi mdi-arrow-right-bold menu-icon"></i>
              </a>
            </li>


            <li class="nav-item">
              <a class="nav-link" href="pages/forms/basic_elements.html">
                <span class="menu-title"><?php echo constant('sidebar_Wallet_Recharge_Request'); ?></span>
                <i class="mdi mdi-wallet menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#general-pages" aria-expanded="false" aria-controls="general-pages">
                <span class="menu-title"><?php echo constant('sidebar_Passbook'); ?></span>
                <i class="mdi mdi-arrow-right menu-icon"></i>
              </a>

              <div class="collapse" id="general-pages">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="passbook.php"> <?php echo constant('sidebar_Ledger_Account'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="cash_deposits.php"> <?php echo constant('sidebar_Cash_Deposits'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="cash_withdrawals.php"> <?php echo constant('sidebar_Cash_Withdrawals'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="commissions.php"> <?php echo constant('sidebar_Commissions'); ?> </a></li>
                </ul>
              </div>

            </li>

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#general-pages" aria-expanded="false" aria-controls="general-pages">
                <span class="menu-title"><?php echo constant('sidebar_User_Profile'); ?></span>
                <i class="mdi mdi-arrow-right menu-icon"></i>
              </a>
              <div class="collapse" id="general-pages">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="profile.php"> <?php echo constant('sidebar_Edit_Profile'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="change_password.php"> <?php echo constant('sidebar_Change_Password'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> <?php echo constant('sidebar_Certificate'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="../essential_certificate.php?id=<?php echo $user['id']; ?>"> <?php echo constant('sidebar_ID_Card'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> <?php echo constant('sidebar_Invoice'); ?> </a></li>
                  <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> <?php echo constant('sidebar_Logout'); ?> </a></li>
                </ul>
              </div>
            </li>

            <!-- <li class="nav-item">
              <a class="nav-link" href="pages/forms/basic_elements.html">
                <span class="menu-title">Quota</span>
                <i class="mdi mdi-leaf menu-icon"></i>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="pages/forms/basic_elements.html">
                <span class="menu-title">Alerts</span>
                <i class="mdi mdi-bell-outline menu-icon"></i>
              </a>
            </li> -->
          
          </ul>
        </nav>