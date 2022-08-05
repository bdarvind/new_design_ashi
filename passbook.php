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

$list_balance = mysqli_query($conn,"SELECT *
, SUM(COALESCE(CASE WHEN type = 'debit' THEN amount END,0)) total_debits
, SUM(COALESCE(CASE WHEN type = 'credit' THEN amount END,0)) total_credits
, SUM(COALESCE(CASE WHEN type = 'debit' THEN amount END,0)) 
- SUM(COALESCE(CASE WHEN type = 'credit' THEN amount END,0)) balance 
FROM csp_wallet_txn 
WHERE (csp_id_fk ='".$_SESSION['csp']."')
GROUP  
BY id DESC
HAVING balance <> 0" );

?>


  <?php include('includes/header.php'); ?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        
        <?php include ('includes/sidebar.php'); ?>

        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="card">
              <div class="card-body">
            <h4 class="card-title">Wallet Passbook</h4>
              <form action="" method="post">
                <div class="row mt-3">
                    
                    <div class="col-md-6 offset-md-3">
                        <div class="row">
                            
                          <div class="col-md-5">
                        <div class="form-group">
                            <input type="date" class="form-control" name="fromdate" style="height: 50px;" min="2022-02-01" required="">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="date" class="form-control" name="todate" style="height: 50px;" required="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" type="submit" name="search" style="height: 50px;">Search</button>
                    </div>  
                        </div>
                    </div>
                    
                    
                </div>
              </form>
              <!--<table class="display" id="datatable_csp_report" style="width:100%;">
                <thead>
                  <tr>
                    <th>REF.No.</th>
                    <th>Txn ID</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Service Charge</th>
                    <th>GST</th>
                    <th>Created On</th>
		    <th>Status</th>
                    <th>Receipt</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>-->
                    <table id="tabledata" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                    <th>REF.No.</th>
                    <th>Created On</th>
                    <th>Type</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <!-- <th>GST</th> -->
		                <th>Amount</th>
                    <th>Purpose</th>
                    <th>Receipt</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($list_balance as $transaction)
                        {
                            echo '<tr>
                      <td>'.$transaction['id'].'</td>
                      <td>'.$transaction['created_on'].'</td>
                      <td>'.$transaction['type'].'</td>
                      <td>'.$transaction['total_debits'].'</td>
                      <td>'.$transaction['total_credits'].'</td>
                      <td>'.$transaction['amount'].'</td>
                      <td style="text-transform:capitalize;">'.$transaction['purpose'].'</td>

                      

                      <td><a rel="modal:open" class="btn btn-outline-primary" href="../view-invoice.php?p=no&amp;id='.$transaction['txn_id'].'">View</a></td>
                    </tr>';
                        }
                        ?>
                        
                     
                    </tbody>
                </table>
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