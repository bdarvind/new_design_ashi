$(document).ready(function(){
$('#datatable-pdf').DataTable({
  responsive: true,
  dom: 'Bfrtip',
  buttons: [
      'pdfHtml5'
  ]
});

    $('#datatable_csp_report').DataTable( {
        responsive: true,
        "order": [[0,"DESC"]],
        "processing": true,
        "serverSide": true,
        "ajax": "parse/datatable/csp_report.php",
        // "deferLoading": 57
    } );
    
    $('#recharge_report').DataTable( {
        responsive: true,
        "order": [[0,"DESC"]],
        "processing": true,
        "serverSide": true,
        "ajax": "parse/datatable/recharge_report.php",
        // "deferLoading": 57
    });

$('#register_form').submit(function() {
    $(this).ajaxSubmit({
        beforeSubmit: function(data){
          $('#register_btn').val('Loading...');
        },
        success: function(data){
          if (data == "success") {
            sweetalert("Successfully Registered!", "success", 'login.php', 'Registered Successfully!');
            // $('.pan_card_docs').addClass('w3-hide')
          }else if (data == "email_exist") {
            sweetalert("Error Occured!", "warning", '', 'Email Address Already Exist!');
          }else if (data == "mobile_exist") {
            sweetalert("Error Occured!", "warning", '', 'Mobile Number Already Exist!');
          }else{
            sweetalert("Error Occured", "error", '', 'Error Occured while registering you, Try Again.');
          }
        },
        complete: function(){
          $('#register_btn').val('Register');
        }
    });
    return false;
});

$('#add_member_form').submit(function() {
    $(this).ajaxSubmit({
        beforeSubmit: function(data){
          $('#add_member_btn').val('Loading...');
        },
        success: function(data){
          if (data.status == "success") {
            sweetalert("Successfully Registered!", "success", 'index.php', 'Registered Successfully, CRN No. of this applicant is '+data.data.member_id);
          }else if (data.status == "email_exist") {
            sweetalert("Error Occured!", "warning", '', 'Email Address Already Exist!');
          }else if (data.status == "mobile_exist") {
            sweetalert("Error Occured!", "warning", '', 'Mobile Number Already Exist!');
          }else if (data.status == "insufficient_balance") {
            sweetalert("Error Occured!", "warning", '', 'Insufficient Balance to Add member');
          }else if (data.status == "csp_disabled") {
            sweetalert("Disabled CSP!", "warning", '', 'Cannot add Member.');
          }else if (data.status == "aadhaar_exist") {
            sweetalert("Aadhaar already Exist!", "warning", '', 'Aadhaar Already Exist');
          }else if (data.status == "pan_exist") {
            sweetalert("Pan Card already Exist!", "warning", '', 'Pan Card Already Exist');
          }else{
            sweetalert("Error Occured", "error", '', 'Error Occured while adding Member, Try Again.');
          }
        },
        complete: function(){
          $('#add_member_btn').val('Add Member');
        }
    });
    return false;
});

  $('#open_saving_account_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#search_crn_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "no_user_exist") {
          sweetalert("Error Occured!", "warning", '', 'No User Exist Mathcing your search query!');
          $('.crn-details').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else if (data == "wrong_crn_no") {
          sweetalert("Error Occured!", "warning", '', 'Wrong CRN No, Enter Correct one.');
          $('.crn-details').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else{
          $('.crn-details').html(data);
        }
      },
      complete:function(){
        $('#search_crn_btn').val('Search');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#open_account_proof_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
        $('#nb_proof_btn').val('Loading...');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "success") {
          sweetalert("Success!", "success", 'dashboard.php', 'Account opened SuccessFully!');
        }else if(data == "account_exist"){
            sweetalert("Account Already Exist!", "error", 'dashboard.php', 'Account Already Exist, Please Check back Again');
        }else{
          sweetalert("Error Occured!", "warning", '', 'Error Occured, Please Again');
        }
      },
      complete:function(){
        $('#nb_proof_btn').val('Submit and Open Account');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });


  $('#cash_deposit_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#search_crn_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "no_user_exist") {
          sweetalert("Error Occured!", "warning", '', 'No User Exist Mathcing your search query!');
          $('.crn-details').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else if (data == "wrong_crn_no") {
          sweetalert("Error Occured!", "warning", '', 'Wrong CRN No, Enter Correct one.');
          $('.crn-details').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else{
          $('.crn-details').html(data);
        }
      },
      complete:function(){
        $('#search_crn_btn').val('Search');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });


  $('#cd_amount_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#cash_deposit_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "insufficient_balance") {
          sweetalert("Insufficient Balance!", "warning", '', 'Unable to add funds due to insufficient balance!');


        }else if (data == "insufficient_balance_ten") {
          sweetalert("Insufficient Balance!", "warning", 'dashboard.php', 'Unable to Complete your Transaction, please maintain Minimum balance.');


        }else if (data == "invalid_account") {
          sweetalert("Error Occured!", "warning", '', 'Account is Inactive/Invalid');

        }else if (data == "error") {
          sweetalert("Error Occured!", "warning", '', 'Error While processing your request, Try Again.');

        }else if(data == "min_hundred_error"){
            sweetalert("Error Occured!", "warning", '', 'Minimum Cash deposit amount should be above ₹100, Try Again.');
        }else{
          sweetalert("Cash Deposit SuccessFull!", "success", 'dashboard.php', 'Cash has been SuccessFully Deposited. Your TXN Id is '+data);
        }
      },
      complete:function(){
        $('#cash_deposit_btn').val('Add Funds');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });


  // Balance Enquiry
  $('#balance_enquiry_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#bq_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "no_user_exist") {
          sweetalert("Error Occured!", "warning", '', 'No User Exist Mathcing your search query!');
          $('.balance_enquiry_result').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else if (data == "wrong_crn_no") {
          sweetalert("Error Occured!", "warning", '', 'Wrong CRN No, Enter Correct one.');
          $('.balance_enquiry_result').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else{
          $('.balance_enquiry_result').html(data);
        }
      },
      complete:function(){
        $('#bq_btn').val('Search');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  // Cash Widthdrawl
  $('#cash_withdrawl_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#search_crn_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "no_user_exist") {
          sweetalert("Error Occured!", "warning", '', 'No User Exist Mathcing your search query!');
          $('.crn-details').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else if (data == "wrong_crn_no") {
          sweetalert("Error Occured!", "warning", '', 'Wrong CRN No, Enter Correct one.');
          $('.crn-details').html('<p class="w3-center"><b>Search to see the Details</b></p>');
        }else if (data == "insufficient_balance_ten") {
          sweetalert("Insufficient Balance!", "warning", '', 'Unable to add funds due to insufficient balance, You must maintain Min Balance');
        }else{
          $('.crn-details').html(data);
        }
      },
      complete:function(){
        $('#search_crn_btn').val('Search');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#cw_amount_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#cash_withdrawl_btn').val('Loading..');
      },
      success:function(data){
        if (data == "otp_success") {
          swal("OTP has been sent to you Mobile no.","Enter OTP Below:", {
            content: "input",
            button: {
              text: "Verify!",
              closeModal: false,
            },
          })
          .then((value) => {
            if (!value) {
              sweetalert("Error Occured", "error", 'cash-withdrawl.php', 'Error Occured, Try again');
            }else{
              var otp = value;
              var token = $.cookie('token');

              if(otp == token){
                $.ajax({
                  dataType:'text',
                  type:'GET',
                  url:'parse/cash_withdrawl_back.php?type=verify',
                  success: function(data){
                    if (data == "insufficient_balance") {
                      sweetalert("Insufficient Balance!", "warning", '', 'Unable to add funds due to insufficient balance!');
                    }else if (data == "invalid_account") {
                      sweetalert("Error Occured!", "warning", '', 'Account is Inactive/Invalid');
                    }else if (data == "error") {
                      sweetalert("Error Occured!", "warning", '', 'Error While processing your request, Try Again.');
                    }else{
                      sweetalert("Cash Widthdrawl Successfull!", "success", 'dashboard.php', 'Cash Withdrawl Successfull. You TXN ID is '+data);
                    }
                  }

                });
              }else{
                sweetalert("OTP Wong", "warning", "cash-withdrawl.php");
              }

            }
          });
        }else if(data == "min_hundred_error"){
            sweetalert("Error Occured!", "warning", '', 'Minimum Cash Withdrawl amount should be above ₹500, Try Again.');
        }else if(data == "customer_mantain"){
            sweetalert("Error Occured!", "warning", '', 'Insufficient Balance, Please maintain Rs.500 In Customer Account.');
        }else if(data == "customer_mantain_limit"){
            sweetalert("Error Occured!", "warning", '', 'Account Limit Reached for the Day');
        }else{
          sweetalert("Error Occured", "error", 'cash-withdrawl.php', 'Error Occured, Try again');
        }
      }
    });

    return false;
  });




  // Mini Statement
  $('#mini_statement_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#ms_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "no_user_exist") {
          sweetalert("Error Occured!", "warning", '', 'No User Exist Mathcing your search query!');

        }else if (data == "wrong_crn_no") {
          sweetalert("Error Occured!", "warning", '', 'Wrong CRN No, Enter Correct one.');

        }else if (data == "no_account_exist") {
          sweetalert("Error Occured!", "warning", '', 'No Account Exist for this User!');

        }else{
          sweetalert("SuccessFully Fetched!", "success", 'view_mini_statement.php?token='+data, 'Click Okay to view the mini statement for this account!');
        }
      },
      complete:function(){
        $('#ms_btn').val('Search');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  //Statement
  $('#statement_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#s_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "no_user_exist") {
          sweetalert("Error Occured!", "warning", '', 'No User Exist Mathcing your search query!');

        }else if (data == "wrong_crn_no") {
          sweetalert("Error Occured!", "warning", '', 'Wrong CRN No, Enter Correct one.');

        }else if (data == "insufficient_balance") {
          sweetalert("Error Occured!", "warning", '', 'Insufficient Balance, balance must be Above Rs.118 To View statement');

        }else if (data == "invalid_date") {
          sweetalert("Error Occured!", "warning", '', 'Invalid date range, Should be between 90 days');

        }else if (data == "no_record_found") {
          sweetalert("Error Occured!", "warning", '', 'No transaction Found.');

        }else if (data == "no_account_exist") {
          sweetalert("Error Occured!", "warning", '', 'No Account Exist for this User!');

        }else{
          sweetalert("SuccessFully Fetched!", "success", 'view_statement.php?token='+data, 'Click Okay to view the mini statement for this account!');
        }
      },
      complete:function(){
        $('#s_btn').val('Search');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });


  // Profile Details
  $('#payout_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
          $('#payout_form_btn').prop('disabled', true);
         $('#payout_form_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "success") {
         sweetalert("SuccessFully Saved", "success", 'dashboard.php', 'Details Saved Successfully');

        }else if(data == "invalid_data"){
            sweetalert("Fill In all the Required Fields!", "error", '', 'Error Occured, Fill All the Details');

        }else{
          sweetalert("Error Occured!", "error", 'dashboard.php', 'Error Occured, Try Again After Sometime');
        }
      },
      complete:function(){
          $('#payout_form_btn').prop('disabled', false);
        $('#payout_form_btn').val('Save Details');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  // Settlement Form
  $('#settlement_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
          $('#settlement_form_btn').prop('disabled', true);
         $('#settlement_form_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        console.log(data);
        if (data.status == "success") {
         sweetalert("Transaction Successfull", "success", 'dashboard.php', data.msg);
       }else if(data.status == "server_error"){
            sweetalert("Error Occured", "error", 'dashboard.php', data.msg);
        }else{
          sweetalert("Error Occured!", "error", 'dashboard.php', data.msg);
        }
      },
      complete:function(){
          $('#settlement_form_btn').prop('disabled', false);
        $('#settlement_form_btn').val('Withdraw');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  //Bank verify
  $('#validate').click(function(){
    var account_number = $('#account_number').val();
    var ifsc_code = $('#ifsc_code').val();

    if(account_number == "" || ifsc_code == ""){
      sweetalert("Attention", "error", '', "Fill Account Number and IFSC Code to validate the Account");
    }else{
      $.ajax({
        dataType:'json',
        type:'POST',
        data:{
          'account_number':account_number,
          'ifsc_code':ifsc_code
        },
        url:'parse/verify_bank_account.php',
        beforeSend:function(data){
          $('#validate').text('Loading..');
          $('#beneficiary_name').html('<img src="https://i.imgur.com/ut6U1xK.gif" class="w3-image" style="width:50px">');
        },
        success:function(data){

          if (data.status == "success") {
            $('#beneficiary_name').html(data.data.verify_account_holder);
            sweetalert("Success", "success", '', 'Bank Verified Successfully');
            $('#beneficiary_name').html(data.data.verify_account_holder);
            $('#bene_name').val(data.data.verify_account_holder);


            $('#amount').prop('disabled',false);
            $('#dmt_btn').prop('disabled',false);

          }else if(data.status == "server_error"){
            sweetalert("Server Error", "warning", 'dashboard.php', 'Bank Server Error, Please try after Sometime');
          }else if(data.status == "insufficient_balance"){
              sweetalert("Error Occured", "error", 'dashboard.php', 'Insufficient Balance, Add Money to OD Balance to Proceed.');
          }else{
            sweetalert("Error Occured", "error", '', 'Error Occured, Try Again After Some Time');
          }
        },
        complete:function(){
          $('#validate').text('Validate');
        }
      });
    }

  });


  // DMT Form
  $('#dmt_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
         $('#dmt_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data.status == "success") {
         sweetalert("Transaction Successfull", "success", 'dashboard.php', data.msg);
       }else if(data.status == "server_error"){
            sweetalert("Error Occured", "error", 'dashboard.php', data.msg);
        }else{
          sweetalert("Error Occured!", "error", 'dashboard.php', data.msg);
        }
      },
      complete:function(){
        $('#dmt_btn').val('Transfer Now');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  // change Password Form
  $('#change_password_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
         $('#cp_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data.status == "success") {
         sweetalert("Password Changed Successfully", "success", 'dashboard.php', data.msg);
        }else{
          sweetalert("Error Occured!", "error", 'settings.php', data.msg);
        }
      },
      complete:function(){
        $('#cp_btn').val('Update Password');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  // Forgot Password Form
  $('#forgot_password_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
         $('#fp_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data.status == "success") {
         sweetalert("Executed Successfully", "success", 'index.php', data.msg);
        }else{
          sweetalert("Error Occured!", "error", '', data.msg);
        }
      },
      complete:function(){
        $('#fp_btn').val('Reset Password');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });


  // Profile Details
  $('#shop_area_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#shop_area_form_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "success") {
         sweetalert("SuccessFully Saved", "success", 'dashboard.php', 'Details Saved Successfully');

        }else{
          sweetalert("Error Occured!", "error", 'dashboard.php', 'Error Occured, Try Again After Sometime');
        }
      },
      complete:function(){
        $('#shop_area_form_btn').val('Save Details');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#pan_adhaar_form').submit(function(){
    $(this).ajaxSubmit({
      beforeSubmit: function(data){
         $('#pan_adhaar_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data == "success") {
         sweetalert("SuccessFully Saved", "success", 'dashboard.php', 'Details Saved Successfully');

        }else{
          sweetalert("Error Occured!", "error", 'dashboard.php', 'Error Occured, Try Again After Sometime');
        }
      },
      complete:function(){
        $('#pan_adhaar_btn').val('Save Details');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#create_upi_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
         $('#create_upi_btn').val('Loading..');
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if (data.status == "success") {
         sweetalert("Successfully Created", "success", 'dashboard.php', data.status_message);

        }else{
          sweetalert("Error Occured!", "error", 'dashboard.php', data.status_message);
        }
      },
      complete:function(){
        $('#create_upi_btn').val('CREATE UPI');
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#pan_kyc_apply_btn').click(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      type:'POST',
      url:'parse/pan_card/kyc_send_otp.php',
      beforeSubmit: function(data){
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if(data.data.status == "SUCCESS"){
            sweetalert("OTP Sent Successfully", "success", '', '');
            document.getElementById('kyc_register_modal').style.display='block';
        }else{
            sweetalert("Error Occured", "error", '', 'Error While Sending OTP');
        }
      },
      complete:function(){
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#kyc_register_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if(data.status == "success"){
            sweetalert("Success", "success", 'pan-card.php', data.status_msg);
            document.getElementById('kyc_register_modal').style.display='none';
        }else{
            sweetalert("Error Occured", "error", 'pan-card.php', data.status_msg);
            document.getElementById('kyc_register_modal').style.display='none';
        }
      },
      complete:function(){
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#agent_apply_btn').click(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      type:'POST',
      url:'parse/pan_card/agent_register.php',
      beforeSubmit: function(data){
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if(data.data.status == "SUCCESS"){
            sweetalert("Successfull", "success", 'pan-card.php', 'Agent Registered Successfully');
        }else{
            sweetalert("Error Occured", "error", 'pan-card.php', '');
        }
      },
      complete:function(){
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#buy_coupon_btn').click(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      type:'POST',
      url:'parse/pan_card/buy_coupon.php',
      beforeSubmit: function(data){
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if(data.status == "pending"){
            sweetalert("Successfull", "success", 'dashboard.php', data.status_msg);
        }else if(data.status == "insufficient_balance"){
            sweetalert("Error Occured", "warning", 'dashboard.php', data.status_msg);
        }else{
            sweetalert("Error Occured", "error", 'dashboard.php', data.status_msg);
        }
      },
      complete:function(){
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  $('#activate_aeps_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
        $('#full_page_loading').removeClass('w3-hide');
      },
      success:function(data){
        if(data.status == "success"){
            sweetalert("Successfull", "success", 'dashboard.php', data.status_msg);
        }else{
            sweetalert("Error Occured", "error", '', data.status_msg);
        }
      },
      complete:function(){
        $('#full_page_loading').addClass('w3-hide');
      }
    });

    return false;
  });

  // Login
  $('#login_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      url:'parse/auth/login_back.php?type=initiate',
      beforeSubmit: function(data){
         $('#login_form_btn').val('Loading..');
      },
      success:function(data){
        if (data.status == "success") {
          swal(data.status_msg,"Enter OTP Below:", {
            content: "input",
            button: {
              text: "Verify!",
              closeModal: false,
            },
          })
          .then((value) => {
            if (!value) {
              //sweetalert("Error Occured", "error", 'index.php', 'Error Occured, Try again');
              sweetalert("Error Occured", "error", 'login.php', 'Error Occured, Try again');
            }else{
              var otp = value;
                $.ajax({
                  dataType:'json',
                  type:'GET',
                  url:'parse/auth/login_back.php?type=verify&csp_csrf_token='+otp,
                  success: function(data){
                    if (data.status == "success") {
                      sweetalert("Successfull", "success", 'services.php', data.status_msg);
                    }else{
                      //sweetalert("Error Occured", "error", 'index.php', data.status_msg);
                      sweetalert("Error Occured", "error", 'login.php', data.status_msg);
                    }
                  }
                });
              }
          });
        }else{
          sweetalert("Error Occured", "error", '', data.status_msg);
        }
      }
    });

    return false;
  });
  
    //   recharge

    $('#recharge_form').submit(function(){
    $(this).ajaxSubmit({
      dataType:'json',
      beforeSubmit: function(data){
        $('#recharge_form_btn').text('Loading...');
        sweetalert("Loading...", "info", '', "Processing your Recharge, Do not Hit back or reload the page");
      },
      success:function(data){
        if(data.status == "success"){
            sweetalert("Successfull", "success", 'dashboard.php', data.status_message);
        }else if(data.status == "pending"){
            sweetalert("Pending", "warning", 'dashboard.php', data.status_message);
        }else{
            sweetalert("Error Occured", "error", '', data.status_message);
        }
      },
      complete:function(){
        $('#recharge_form_btn').text('Proceed to Recharge');
      }
    });

    return false;
  });




});
