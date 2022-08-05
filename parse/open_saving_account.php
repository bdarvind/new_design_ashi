<?php
include '../include/config.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_POST['search_option'])) {
  $search_option = $_POST['search_option'];
  $search_from_id = $_POST['search_from_id'];

  if ($search_option == "aadhaar_no") {
    $opt = 'aadhaar_number';
  }elseif ($search_option == "pan_card") {
    $opt = 'pan_card';
  }elseif ($search_option == "crn_no") {
    $opt = 'mobile_number';
    if(strpos($search_from_id, $crn_code) !== false){
      $search_from_id = explode($crn_code,$search_from_id)[1];
    }else{
      echo "wrong_crn_no";
      exit();
    }

  }
  $check_user_exist = mysqli_query($conn, "SELECT * FROM net_banking_users WHERE $opt = '$search_from_id' AND csp_id_fk='$csp_id'");
  if(mysqli_num_rows($check_user_exist) > 0){
    $get_user = select('net_banking_users',$opt,$search_from_id);
  ?>

<?php if ($get_user['bank_name'] == ""): ?>
  <div class="w3-panel w3-round w3-amber w3-margin w3-padding">
    <p class="w3-large">Want to Open a saving Bank Account for <?php echo $get_user['member_name']; ?>?</p>
    <a onclick="document.getElementById('nominee_form').style.display='block'; open_saving_btn(<?php echo $get_user['id']; ?>)" id="open_saving_btn" class="w3-btn w3-green w3-round">Open Saving Account</a>
  </div>
<?php else: ?>
  <div class="w3-panel w3-round w3-red w3-margin w3-padding">
    <p class="w3-large"><?php echo $get_user['member_name']; ?> is already a Saving Account Holder</p>
  </div>
    <div class="w3-section">
        <a target='_BLANK' class="w3-btn w3-theme w3-round w3-margin" href="view_certificate.php?id=<?php echo $get_user['id']; ?>">Download Certificate</a>
    </div>
<?php endif; ?>


    <div class="w3-row-padding">

        <?php if ($get_user['bank_name'] != ""): ?>
            <?php
              $nb_id = $get_user['id'];
              $nb_bank = $get_user['bank_name'];
              $va = mysqli_query($conn, "SELECT $nb_bank FROM virtual_account WHERE user_type='net_banking_users' AND user_id='$nb_id'");
              $va_row = unserialize(mysqli_fetch_assoc($va)[$nb_bank]);

             ?>
            <div class="w3-col s12 l6 m12 w3-section">
                <label>Account number</label>
                <input disabled class="w3-input" value="<?php echo $va_row['account_number']; ?>">
            </div>
            <div class="w3-col s12 l6 m12 w3-section">
                <label>IFSC Code</label>
                <input disabled class="w3-input" value="<?php echo $va_row['account_ifsc']; ?>">
            </div>
      <?php endif; ?>

      <div class="w3-col s12 l6 m12 w3-section">
        <label>Member Name</label>
        <input type="text" disabled class="w3-input" value="<?php echo $get_user['member_name']; ?>">
      </div>
      <div class="w3-col s12 l6 m12 w3-section">
        <label>Email Address</label>
        <input type="text" disabled class="w3-input" value="<?php echo $get_user['email']; ?>">
      </div>
      <div class="w3-col s12 l6 m12 w3-section">
        <label>Mobile number</label>
        <input type="text" disabled class="w3-input" value="<?php echo $get_user['mobile_number']; ?>">
      </div>
      <div class="w3-col s12 l6 m12 w3-section">
        <label>Aadhaar Number</label>
        <input type="text" disabled class="w3-input" value="<?php echo $get_user['aadhaar_number']; ?>">
      </div>
      <div class="w3-col s12 l6 m12 w3-section">
        <label>Pan Card</label>
        <input type="text" disabled class="w3-input" value="<?php echo $get_user['pan_card']; ?>">
      </div>
      <div class="w3-col s12 l6 m12 w3-section">
        <label>Address</label>
        <textarea disabled class="w3-input"><?php echo $get_user['address']; ?></textarea>
      </div>

    </div>

  <?php
  }else{
    echo "no_user_exist";
  }



}

 ?>
