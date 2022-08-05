<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php if (isset($title)): ?>
      <?php echo $title; ?>
    <?php else: ?>
      Welcome to CSP Portal | <?php echo $site_name; ?>
    <?php endif; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="https://gocash.net.in/include/assets/images/icon/logo.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="include/css/style.css?v=4">
    <link rel="stylesheet" href="include/css/theme.css?v=4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.22/b-1.6.5/b-html5-1.6.5/r-2.2.6/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
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

    <div class="loading w3-hide" id="full_page_loading">&#8230;</div>

    <div class="w3-sidebar w3-bar-block w3-animate-right" style="display:none;z-index:5;right:0;width:320px;" id="mySidebar">
      <button class="w3-bar-item w3-button w3-center w3-red w3-hover-red" onclick="w3_close()">&times;</button>
<div class="w3-dropdown-hover">
        <button class="w3-button w3-hover-theme w3-padding-16" onclick="accordion('services');"><i class="fas fa-wallet"></i> My Profile <i class="fas fa-caret-down"></i></button>
        <div class="w3-round w3-bar-block w3-card-4 w3-hide" id="services">
            <a href="profile.php" class="w3-bar-item w3-button"><i class="fas fa-wallet"></i> Profile</a>
      <a href="settings.php" class="w3-bar-item w3-button"><i class="fas fa-cogs"></i> Password</a>
            <a href="logout.php" class="w3-bar-item w3-button"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
      <div class="w3-dropdown-hover">
        <button class="w3-button w3-hover-theme w3-padding-16" onclick="accordion('services');"><i class="fas fa-wallet"></i> Services <i class="fas fa-caret-down"></i></button>
        <div class="w3-round w3-bar-block w3-card-4 w3-hide" id="services">
            <a href="services.php" class="w3-bar-item w3-button"><i class="fas fa-globe w3-text-blue"></i> All Services</a>
           <!--  <a target="_BLANK" href="https://www.easypolicy.com/epnew/Partners/index.html?y4HB3Qxc0OepYJfDj2EDziBg3KHh8zh7468M2QnTwgWVdM2EQGMszcaTg7QbY4Y8sZFSAVhgs3nbptD4usI2X3QMWWRKotZzpAm3sXDomDBpe19OcJI6Fj7rwH4YL/KrM15VtwuEl7IokT5LhE8/PdI5mCLAZ5Gkr1SWx0s47TdxCL7glmBXOAOtcfwenan1gXYR/syQBFmUGqh+bWobXEn9vPpni9n+nqjiZmTm1QFJt7jEvRAS1MxTJoriXlhDiFBud/f3Ro1DSSbDM094od/6TENUcffga+1B6OkYsCqYgSJOgPKi9/LLf9xDx1z7e7ZYkEl4QyKMCOcXqxvHhVHaXMoHkG3lCAU2+KHMbZxqkdR1CjmTObLGJJfx52Pqf8kvwEFp5+vYkx6abzld9S6Vi8mik4Rfs1SU23YWbBVnYvFOqbctKYoq7F6TJd+razsficpVl1TX5pZLDiLh+yEMEWhovDecHB2pqkxOKVcD0m8I7SPBVZSU8LwPdu6bGSkkexCGuQvXaYFiUKzOE673YxWWkA3gretS78sd4HmV3BIMb+/0/ZMzsn6snl3sdcRZRPjZ1J2hD0lSk9jxq09t17OIpvt+GZ6Euy28CQi+L38i0JrcMNhxMZtmjffIHjOGGklLiRO5jxx09vJmncZPGvNgDY7BkCk2KuwXwZasajLwYO0IJnVFVax4G4G1u0TKwwvauL6kSsHZmr3TGj5jp//kkUEkTWBZ3ynO6Dns3elLF7un96xiFOqK25TxaWnXcHWiQ3dLcGlNOXpGfUjoLIenqGKLCuMcN0HHuR/4mzdrWm1Id+fIlNinMHVzDZbkrzKNUYxvpyvIrvyQH6wFKBJKmNNr7r5EqmV8CzUAr6RDMxy5w4P+oryoSNHQbdu5Gc/w9NscOWhsMO9pwgh9KAV1CXua0+xzPL4+dTyoUktFBQV0kAsX6VAUDGLX++j4NCGtscetncCPfG0NrA==" class="w3-bar-item w3-button"><i class="fas fa-wallet"></i> Insurance</a>
            <a href="pan-card.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Pan Card</a>
            <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-pink"></i> Mini ATM</a>
            <a href="money-transfer.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-green"></i> DMT</a>
            <a href="sprint/" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Money Transfer</a>
            <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-amber"></i> LIC Policy</a> -->
        </div>
      </div>
            <?php
            $aeps1_check = mysqli_query($conn,"select * from sprint_aeps_user where user_id='".$_SESSION['csp']."' AND aeps_status='1'");
            if(mysqli_num_rows($aeps1_check)>0)
            {
                echo '<a href="sprint/aeps-withdraw.php" class="w3-bar-item w3-button w3-hover-theme w3-padding-16"><i class="fas fa-wallet"></i> AEPS 1</a>';
            }else{
             echo '<a href="sprint/onboarding.php" class="w3-bar-item w3-button w3-hover-theme w3-padding-16"><i class="fas fa-wallet"></i> AEPS 1</a>';   
            }
            ?>
      
      <a href="aeps.php" class="w3-bar-item w3-button w3-hover-theme w3-padding-16"><i class="fas fa-wallet"></i> AEPS 2</a>

      <a href="https://ashidigitalpay.in/csp/sprint/aadhar-pay.php" class="w3-bar-item w3-button w3-hover-theme w3-padding-16"><i class="fas fa-wallet"></i> Aadhar Pay</a>

      <!--<div class="w3-dropdown-hover">-->
      <!--  <button class="w3-button w3-hover-theme w3-padding-16" onclick="accordion('accounting');"><i class="fas fa-wallet"></i> Accounting <i class="fas fa-caret-down"></i></button>-->
      <!--  <div class="w3-round w3-bar-block w3-card-4 w3-hide" id="accounting">-->
      <!--    <a href="cash-withdrawl.php" class="w3-bar-item w3-button"><i class="fas fa-wallet"></i> Cash withdrawl</a>-->
      <!--    <a href="acc-cash-deposit.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-green"></i> Cash Deposit</a>-->
      <!--    <a href="balance-enquiry.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-pink"></i> Balance Enquiry</a>-->
      <!--    <a href="mini-statement.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-amber"></i> Mini Statement</a>-->
      <!--    <a href="statement.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Statement</a>-->
      <!--    <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Print Passbook</a>-->
      <!--  </div>-->
      <!--</div>-->

      <!--<div class="w3-dropdown-hover">-->
      <!--  <button class="w3-button w3-hover-theme w3-padding-16" onclick="accordion('account');"><i class="fas fa-users"></i> Account <i class="fas fa-caret-down"></i></button>-->
      <!--  <div class="w3-round w3-bar-block w3-card-4 w3-hide" id="account">-->
      <!--    <a href="add_member.php" class="w3-bar-item w3-button"><i class="fas fa-user"></i> Add Applicant</a>-->
      <!--    <a href="open-saving-account.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-green"></i> Open Saving Account</a>-->
      <!--    <a href="account_details.php" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-indigo"></i> Account Details</a>-->
      <!--    <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-amber"></i> RD Account</a>-->
      <!--    <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> FD Account</a>-->
      <!--    <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> MIS Account</a>-->
      <!--    <a target="_BLANK" href="https://www.lendingkart.com/business-loan/check-eligibility?utm_source=GoogleAdWords&utm_medium=%20+finance%20+loan&utm_campaign=mebn&utm_term=finance%20loan&utm_source=GoogleAdWords&Campaignid=871158846&gclid=CjwKCAjw_NX7BRA1EiwA2dpg0qlJy6HsSNC35KNrjpaNL3ovRkQmzp83i57HF7_jCurbqfKN2rfgkxoCU8cQAvD_BwE" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Loan Account</a>-->
      <!--    <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Mutual Funds</a>-->
      <!--    <a href="#" class="w3-bar-item w3-button"><i class="fas fa-circle w3-text-blue"></i> Daily Account</a>-->
      <!--  </div>-->
      <!--</div>-->

    </div>

    <!-- Page Content -->
    <div class="w3-overlay w3-animate-opacity" onclick="w3_close()" style="cursor:pointer;" id="myOverlay"></div>

    <div class="w3-bar w3-theme">
      <!--<a href="index.php" class="w3-bar-item w3-left">-->
      <!--  <img src="https://gocash.net.in/include/assets/images/icon/logo.png" class="w3-image w3-padding" style="width:180px;" alt="">-->
      <!--</a>-->
      <a href="dashboard.php" class="w3-bar-item w3-left"><h4><b><?php echo $site_name; ?></b></h4></a>

      <div class="w3-hide-small w3-hide-medium">

        <div class="w3-dropdown-hover w3-margin w3-right">
          <button class="w3-button w3-hover-theme"><i class="fas fa-wallet"></i> My Profile <i class="fas fa-caret-down"></i></button>
          <div class="w3-dropdown-content w3-round w3-bar-block w3-card-4">
              <a href="profile.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-wallet"></i> Profile</a>
                <a href="settings.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-cogs"></i> Password</a>
              <a href="logout.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </div>
        </div>
        <div class="w3-dropdown-hover w3-margin w3-right">
          <button class="w3-button w3-hover-theme"><i class="fas fa-wallet"></i> Services <i class="fas fa-caret-down"></i></button>
          <div class="w3-dropdown-content w3-round w3-bar-block w3-card-4">
            <a href="services.php"  class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-globe w3-text-blue"></i> All Services</a>
            <!-- <a target="_BLANK" href="https://www.easypolicy.com/epnew/Partners/index.html?y4HB3Qxc0OepYJfDj2EDziBg3KHh8zh7468M2QnTwgWVdM2EQGMszcaTg7QbY4Y8sZFSAVhgs3nbptD4usI2X3QMWWRKotZzpAm3sXDomDBpe19OcJI6Fj7rwH4YL/KrM15VtwuEl7IokT5LhE8/PdI5mCLAZ5Gkr1SWx0s47TdxCL7glmBXOAOtcfwenan1gXYR/syQBFmUGqh+bWobXEn9vPpni9n+nqjiZmTm1QFJt7jEvRAS1MxTJoriXlhDiFBud/f3Ro1DSSbDM094od/6TENUcffga+1B6OkYsCqYgSJOgPKi9/LLf9xDx1z7e7ZYkEl4QyKMCOcXqxvHhVHaXMoHkG3lCAU2+KHMbZxqkdR1CjmTObLGJJfx52Pqf8kvwEFp5+vYkx6abzld9S6Vi8mik4Rfs1SU23YWbBVnYvFOqbctKYoq7F6TJd+razsficpVl1TX5pZLDiLh+yEMEWhovDecHB2pqkxOKVcD0m8I7SPBVZSU8LwPdu6bGSkkexCGuQvXaYFiUKzOE673YxWWkA3gretS78sd4HmV3BIMb+/0/ZMzsn6snl3sdcRZRPjZ1J2hD0lSk9jxq09t17OIpvt+GZ6Euy28CQi+L38i0JrcMNhxMZtmjffIHjOGGklLiRO5jxx09vJmncZPGvNgDY7BkCk2KuwXwZasajLwYO0IJnVFVax4G4G1u0TKwwvauL6kSsHZmr3TGj5jp//kkUEkTWBZ3ynO6Dns3elLF7un96xiFOqK25TxaWnXcHWiQ3dLcGlNOXpGfUjoLIenqGKLCuMcN0HHuR/4mzdrWm1Id+fIlNinMHVzDZbkrzKNUYxvpyvIrvyQH6wFKBJKmNNr7r5EqmV8CzUAr6RDMxy5w4P+oryoSNHQbdu5Gc/w9NscOWhsMO9pwgh9KAV1CXua0+xzPL4+dTyoUktFBQV0kAsX6VAUDGLX++j4NCGtscetncCPfG0NrA==" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-wallet"></i> Insurance</a>
            <a href="pan-card.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Pan Card</a>
            <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-pink"></i> Mini ATM</a>
            <a href="money-transfer.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-green"></i> DMT</a>
            <a href="sprint/" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Money Transfer</a>
            <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-amber"></i> LIC Policy</a> -->
          </div>
        </div>
         <?php
            if(mysqli_num_rows($aeps1_check)>0)
            {
                echo '<a href="sprint/aeps-withdraw.php" class="w3-bar-item w3-right w3-margin w3-button w3-theme w3-hover-theme"><i class="fas fa-wallet"></i> AEPS 1</a>';
            }else{
                echo '<a href="sprint/onboarding.php" class="w3-bar-item w3-right w3-margin w3-button w3-theme w3-hover-theme"><i class="fas fa-wallet"></i> AEPS 1</a>'; 
            }
            ?>

        <a href="aeps.php" class="w3-bar-item w3-right w3-margin w3-button w3-theme w3-hover-theme"><i class="fas fa-wallet"></i> AEPS 2</a>

        <a href="https://ashidigitalpay.in/csp/sprint/aadhar-pay.php" class="w3-bar-item w3-right w3-margin w3-button w3-theme w3-hover-theme"><i class="fas fa-wallet"></i> Aadhar Pay</a>

        <!--<div class="w3-dropdown-hover w3-margin w3-right">-->
        <!--  <button class="w3-button w3-hover-theme"><i class="fas fa-wallet"></i> Accounting <i class="fas fa-caret-down"></i></button>-->
        <!--  <div class="w3-dropdown-content w3-round w3-bar-block w3-card-4">-->
        <!--    <a href="cash-withdrawl.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-wallet"></i> Cash withdrawl</a>-->
        <!--    <a href="acc-cash-deposit.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-green"></i> Cash Deposit</a>-->
        <!--    <a href="balance-enquiry.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-pink"></i> Balance Enquiry</a>-->
        <!--    <a href="mini-statement.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-amber"></i> Mini Statement</a>-->
        <!--    <a href="statement.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Statement</a>-->
        <!--    <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Print Passbook</a>-->
        <!--  </div>-->
        <!--</div>-->

        <!--<div class="w3-dropdown-hover w3-margin w3-right">-->
        <!--  <button class="w3-button w3-hover-theme"><i class="fas fa-users"></i> Account <i class="fas fa-caret-down"></i></button>-->
        <!--  <div class="w3-dropdown-content w3-round w3-bar-block w3-card-4">-->
        <!--    <a href="add_member.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-user"></i> Add Applicant</a>-->
        <!--    <a href="open-saving-account.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-green"></i> Open Saving Account</a>-->
        <!--    <a href="account_details.php" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-indigo"></i> Account Details</a>-->
        <!--    <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-amber"></i> RD Account</a>-->
        <!--    <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> FD Account</a>-->
        <!--    <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> MIS Account</a>-->
        <!--    <a target="_BLANK" href="https://www.lendingkart.com/business-loan/check-eligibility?utm_source=GoogleAdWords&utm_medium=%20+finance%20+loan&utm_campaign=mebn&utm_term=finance%20loan&utm_source=GoogleAdWords&Campaignid=871158846&gclid=CjwKCAjw_NX7BRA1EiwA2dpg0qlJy6HsSNC35KNrjpaNL3ovRkQmzp83i57HF7_jCurbqfKN2rfgkxoCU8cQAvD_BwE" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Loan Account</a>-->
        <!--    <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Mutual Funds</a>-->
        <!--    <a href="#" class="w3-bar-item w3-button w3-hover-theme"><i class="fas fa-circle w3-text-blue"></i> Daily Account</a>-->
        <!--  </div>-->
        <!--</div>-->


      </div>

      <div class="w3-hide-large">
        <a onclick="w3_open()" class="w3-button w3-bar-item w3-right w3-margin w3-border w3-border-white w3-hover-white w3-round">&#9776;</a>


      </div>
    </div>
    
