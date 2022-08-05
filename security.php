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