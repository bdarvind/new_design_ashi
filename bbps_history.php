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

if(isset($_POST['search']))
{
    $fromdate = $_POST['fromdate'];
    $todate  = $_POST['todate'];
    /*$fromdate = date('M,d,Y',strtotime($fromdate));
    $todate = date('M,d,Y',strtotime($todate));*/
    
    $fromdate = date('Y-m-d',strtotime($fromdate));
    $todate = date('Y-m-d',strtotime($todate . "+1 days"));
    $sqltran = mysqli_query($conn,"select * from kwik_recharge where (user_id ='".$_SESSION['csp']."') AND (creation_time >= '".$fromdate." 00:00:00' 
AND creation_time <= '".$todate." 00:00:00') ORDER BY id DESC");
}else{
    $sqltran = mysqli_query($conn,"select * from kwik_recharge where user_id ='".$_SESSION['csp']."' ORDER BY id DESC");
}

//echo "no of rows ".mysqli_num_rows($sql);
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

              <h4 class="card-title">Reports</h4>

              <form action="" method="post">
                <div class="row mt-6">
                    
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
                <div class="row">
                  <div class="col-12">
                    <div id="order-listing_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length" id="order-listing_length">
                                    <label style="font-family:calibri;" >Show  <select name="order-listing_length" aria-controls="order-listing" class="custom-select custom-select-sm form-control">
                                        <option value="5">50</option>
                                        <option value="10">100</option>
                                        <option value="15">200</option>
                                        <option value="-1">All</option>
                                    </select> entries</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div id="order-listing_filter" class="dataTables_filter"><label>
                                    <input type="search" class="form-control" placeholder="Search" aria-controls="order-listing">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="font-family:calibri;" class="row">
                        <div class="col-sm-12">
                            <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Order #: activate to sort column descending" style="width: 54.125px;">REF.No.</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased On: activate to sort column ascending" style="width: 99.1719px;">Created On</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Customer: activate to sort column ascending" style="width: 68.2969px;">Mobile Number</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Ship to: activate to sort column ascending" style="width: 50.5625px;">Amount</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Base Price: activate to sort column ascending" style="width: 75.8125px;">Fees</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Purchased Price: activate to sort column ascending" style="width: 113.344px;">Operator Type</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 59.75px;">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 55.9375px;">Txn ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="order-listing" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending" style="width: 55.9375px;">Receipt</th></tr>
                                </thead>
                          
                      <?php
                        foreach($sqltran as $transaction)
                        {
                            echo '

                        <tr>
                          <td>'.$transaction['id'].'</td>
                          <td>'.$transaction['created_on'].'</td>
                          <td>'.$transaction['number'].'</td>
                          <td>'.$transaction['amount'].'</td>
                          <td>'.$transaction['fees'].'</td>
                          <td>'.$transaction['operator_type'].'</td>
                          <td>'.$transaction['status'].'</td>
                          <td>'.$transaction['txn_id'].'</td>
                          <td>
                            <a rel="model:open" href="../view-invoice.php?p=no&amp;id='.$transaction['txn_id'].'" <button class="btn btn-outline-primary" data-toggle="modal" data-target="#myModal" >View</button></a>
                          </td>
                        </tr>';
                        }
                        ?>
                      
                    </tbody>
                    </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="order-listing_info" role="status" aria-live="polite">Showing 1 to 10 of 10 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="order-listing_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="order-listing_previous"><a href="#" aria-controls="order-listing" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="order-listing" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="order-listing_next"><a href="#" aria-controls="order-listing" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
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