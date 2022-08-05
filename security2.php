<?php if($server_status == "down"): ?>
     <div id="id01" class="w3-modal w3-show">
        <div class="w3-modal-content w3-card-4">
          <header class="w3-container w3-amber">
            <h2>Server on Maintainence</h2>
          </header>
          <div class="w3-container">
            <p>Server is on Maintainence, Please Try After Sometime.</p>
          </div>
        </div>
      </div>
<?php else: ?>
    <div id="withdraw_modal" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-top">
    <header class="w3-container w3-theme">
      <h3>Withdraw OD Balance</h3>
      <span onclick="document.getElementById('withdraw_modal').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
    </header>
    <div class="w3-container">
      <table class="w3-table w3-bordered w3-margin-top w3-border">
        <tr>
          <td><b>OD A/C Balance 2</b></td>
          <td>₹<?php echo $user['account_balance']; ?></td>
        </tr>
        <tr class="w3-text-red">
          <td><b>Balance Maintain</b></td>
          <td>- ₹<?php echo number_format($user['account_limit']); ?></td>
        </tr>
        <tr class="w3-text-green">
          <td><b>Withdrawable balance</b></td>
          <td>
            <?php
              $user_balance = $user['account_balance'];
              $withdrawable_balance = $user_balance-$user['account_limit'];

              if($withdrawable_balance <= 0){
                echo "₹0";
              }else{
                echo "₹".$withdrawable_balance;
              }
            ?>
          </td>
        </tr>
      </table>
      <?php if ($withdrawable_balance > 0): ?>
        <!-- <form id="settlement_form" action="parse/settlement_back2.php" class="w3-padding-16" method="POST"> -->
          <form id="settlement_form" action="" class="w3-padding-16" method="POST">
          <p class="w3-center w3-large">Settlement Form</p><hr>
		  <p style="text-align:center; color:red;font-size:12px;">Please contact to servoce provider for otp</p>
          <input type="hidden" name="csrf_token" value="<?php echo encryption('encrypt',$withdrawable_balance); ?>">
		  <!--<label><b>OTP</b></label>
          <input type="text" name="otp" class="w3-border w3-round w3-input" maxlenght="6" placeholder="Enter OTP" required>-->
          <label><b>Amount</b></label>
          <input type="number" name="amount" class="w3-border w3-round w3-input" max="<?php echo $withdrawable_balance ?>" placeholder="Enter Amount to Withdraw" required>
          <span class="w3-small w3-text-red"><b>Note - </b> Withdraw Amount should be greater than ₹1000</span><br><br>
          <!-- <input type="submit" id="settlement_form_btn" name="submit" class="w3-btn w3-green w3-round w3-block" value="Withdraw"> -->
          <input type="button" id="settlement_form_btn" name="submit" class="w3-btn w3-green w3-round w3-block settelementrequest" value="Withdraw">

        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

    
    <?php if($user['status'] == "disabled"): ?>
      <div id="id01" class="w3-modal w3-show">
        <div class="w3-modal-content w3-card-4">
          <header class="w3-container w3-amber">
            <h2>Account Disabled</h2>
          </header>
          <div class="w3-container">
            <p>
            Contact Us using Chat button On bottom left or <br>Call us here - <br> All India - <a class="w3-text-blue" href="tel:+91<?php echo $phone; ?>">+91<?php echo $phone; ?></a>
            </p>
            <a class="w3-btn w3-red w3-round w3-ripple w3-margin" href="logout.php">Logout</a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if($user['address'] == ""): ?>
      <div id="id01" class="w3-modal w3-show">
        <div class="w3-modal-content w3-card-4">
          <header class="w3-container w3-amber">
            <h2>Fill Up some Details</h2>
          </header>
          <div class="w3-container">
            <form id="payout_form" action="parse/profile_save_back.php"class="w3-padding-16" method="POST">
                <p class="w3-medium"><b>Personal Details</b></p>
                <label>Address</label>
                <textarea class="w3-input w3-round w3-border" name="address" placeholder="Enter Address" required></textarea><br>
                <label>Office Address</label>
                <textarea class="w3-input w3-round w3-border" name="office_address" placeholder="Enter Office Address" required></textarea><br>
                <label>Pin Code</label>
                <input type="number" name="pin_code" placeholder="Enter Pin Code" class="w3-input w3-round w3-border" required><hr>

                <p class="w3-medium"><b>Payout Details</b></p>
                <label>Bank Account Holder Name</label>
                <input type="text" name="payout_name" placeholder="Enter Bank Account Holder Name" class="w3-input w3-round w3-border" required><br>
                <label>Bank Account number</label>
                <input type="text" name="payout_account_no" placeholder="Enter Bank Account Number" class="w3-input w3-round w3-border" required><br>
                <label>IFSC Code</label>
                <input type="text" name="payout_ifsc" placeholder="Enter IFSC Code" class="w3-input w3-round w3-border" required><br>
                <input type="submit" id="payout_form_btn" name="submit" class="w3-btn w3-green w3-round w3-block" value="Save Details">
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if($user['shop_area_name'] == ""): ?>
      <div id="shop_area_modal" class="w3-modal w3-show">
        <div class="w3-modal-content w3-card-4">
          <header class="w3-container w3-amber">
            <h2>Fill Up some Details</h2>
          </header>
          <div class="w3-container">
            <form id="shop_area_form" action="parse/profile_save_back.php"class="w3-padding-16" method="POST">

                <label>Shop Area Name</label>
                <textarea class="w3-input w3-round w3-border" name="shop_area_name" placeholder="Enter Shop Area Name" required></textarea>
                <span class="w3-text-red"><b>Note:</b> Enter name of the area where your shop is located.</span>

                <input type="submit" id="shop_area_form_btn" name="submit" class="w3-btn w3-green w3-round w3-margin-top w3-block" value="Save Details">
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    <?php if($user['pan_card'] == NULL || $user['adhaar_card'] == NULL): ?>
      <div id="shop_area_modal" class="w3-modal w3-show">
        <div class="w3-modal-content w3-card-4">
          <header class="w3-container w3-amber">
            <h2>Fill Up some Details</h2>
          </header>
          <div class="w3-container">
            <form id="pan_adhaar_form" action="parse/profile_save_back.php"class="w3-padding-16" method="POST">

                <label>Pan Card</label>
                <input type="text" class="w3-input w3-round w3-border" name="pan_card" placeholder="Enter Pan Card Number" required><br>
                <label>Aadhaar Card</label>
                <input type="text" class="w3-input w3-round w3-border" name="adhaar_card" placeholder="Enter Aadhaar Number" required>
                

                <input type="submit" id="pan_adhaar_btn" name="submit" class="w3-btn w3-green w3-round w3-margin-top w3-block" value="Save Details">
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>

<?php endif; ?>