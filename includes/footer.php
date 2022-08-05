          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
              <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © ashidigitalpay.in 2022</span>
              <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Developed By: <a href="https://ashidigitalpay.in/" target="_blank">ashidigitalpay.in</a></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="js/chart.js"></script>

    <script src="assets/js/file-upload.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- End custom js for this page -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://malsup.github.io/min/jquery.form.min.js" charset="utf-8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<!-- Datatable -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.22/b-1.6.5/b-html5-1.6.5/r-2.2.6/datatables.min.js"></script>
<!-- Datatable -->
<script src="https://gocash-net-in.s3.ap-south-1.amazonaws.com/argnidhi/eko_aeps.js" charset="utf-8"></script>
<script src="includes/js/functions.js?v=<?php echo rand(100000,999999); ?>" charset="utf-8"></script>
<script src="includes/js/jquery_cookie.js?v=<?php echo rand(100000,999999); ?>" charset="utf-8"></script>
<script src="includes/js/main.js?v=<?php echo rand(100000,999999); ?>" charset="utf-8"></script>

  <script>
    $(document).ready(function(){

       $('#aeps_pay').click(function(){
          var language = $('#language').val();
          console.log(language);
          var aeps = new EkoAEPSGateway();

            // Configure your developer API details...
            aeps.config({
                "partner_name": "Ashi Digital Pay",
                "initiator_logo_url": "https://i.imgur.com/yuhGRMy.png",
                "initiator_id": "<?php echo $eko_initiator_id; ?>",
                "developer_key": "<?php echo $eko_developer_key; ?>",
                "secret_key": "<?php echo $secret_key; ?>",
                "secret_key_timestamp": "<?php echo $secret_key_timestamp; ?>",
                "user_code": "<?php echo $aeps_user['user_code'] ?>",
                "language": language,
                "environment": "production"
            });

            // Configure your callback URL for transaction-confirmation and for getting final result...
            aeps.setCallbackURL('<?php echo $eko_callback_url; ?>');

            aeps.open();

       });


    });

  </script>


  <script>

    var el = document.getElementsByClassName('mynewClass')[0].children[0].children[1];
    var text = el.innerHTML;
    el.innerHTML = text.replace('_','-->');

  </script>

  <script>

    function clean($string) {
      $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

      return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

  </script>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>
<script src="https://malsup.github.io/jquery.form.js"></script> 
<script>
  

 $(document).ready(function() {
  var table = $('#tabledata').DataTable({
    //"dom": 'Blfrtip',
    "lengthMenu": [
      [50, 100, 1000, -1],
      [50, 100, 1000, "All"]
    ],
    "order": [[ 0, "desc" ]],
    "initComplete": function() {
      $("#reminders").show();
    },
    //"buttons": ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
    "buttons": ['excel', 'pdf', 'print']
  });
  table.buttons().container().appendTo('#tabledata_wrapper .col-md-6:eq(0)');
});


$(document).ready(function(){
  $(".settelementrequest").click(function(){
    var amountt = $("input[name='amount']").val();
    
if(amountt!=="")
{
  $.ajax({
    type:"post",
    //url:"parse/settlement_back2.php",
    //url:"parse/settlement_back_bank-open.php",
    url:"parse/cashfree_batch_transfer.php",
    data:$('#settlement_form').serialize(),
    beforeSend: function() {
     $('.settelementrequest').attr("disabled", true);
    },
    success:function(res)
    { 
    var returnedData = JSON.parse(res);
    //console.log(returnedData);
    if(returnedData.status=="success")
    {
      sweetalert("Success!", "success", 'dashboard.php', ''+returnedData.msg);
    }else{
      sweetalert("Error Occured!", "Warning", '', ''+returnedData.msg);
    }  
      
    },
    complete: function (res) {
      setTimeout(function () { 
        $('.settelementrequest').attr("disabled", false); // enable the button after 10 seconds
     }, 10000);
       
     }
  })
}else{
  sweetalert("Warning!", "warning", '', 'Enter Amount!');
}

});
})

</script>


<script>
				function change_lang(value){
					window.location.replace("?lang="+value);
				}
			</script>

  </body>
</html>