<?php
include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];

$table = 'csp_wallet_txn';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
  array( 'db' => 'id', 'dt' => 0 ),
  array( 'db' => 'txn_id', 'dt' => 1 ),
  array(
      'db'        => 'nb_id_fk',
      'dt'        => 2,
      'formatter' => function( $d, $row ) {
          if($d == NULL){
              return "";
          }else{
              $select = select('net_banking_users','id',$d);
              return $select['member_name'];
          }
      }
  ),
  //array( 'db' => 'type',  'dt' => 3 ),
  array( 'db' => 'purpose',   'dt' => 2 ),
  array(
    'db' => 'amount',
    'dt' => 3,
    'formatter' => function( $d, $row ) {
        return '₹'.number_format($d,3);
    }
  ),
  array(
      'db'        => 'fees',
      'dt'        => 4,
      'formatter' => function( $d, $row ) {
          return '₹'.number_format($d,4);
      }
  ),
  array(
      'db'        => 'gst',
      'dt'        => 5,
      'formatter' => function( $d, $row ) {
          return '₹'.number_format($d,5);
      }
  ),
  array( 'db' => 'created_on',     'dt' => 6 ),
  /*array(
      'db'        => 'bene_account_no',
      'dt'        => 9,
      'formatter' => function( $d, $row ) {
          
          return $d;
      }
  ),
  array(
      'db'        => 'bene_ifsc_code',
      'dt'        => 10,
      'formatter' => function( $d, $row ) {
          
          return $d;
      }
  ),
  array(
      'db'        => 'bene_name',
      'dt'        => 11,
      'formatter' => function( $d, $row ) {
          
          return $d;
      }
  ),*/
  array(
      'db'        => 'status',
      'dt'        => 7,
      'formatter' => function( $d, $row ) {
          
          return $d;
      }
  ),
  array(
      'db'        => 'txn_id',
      'dt'        => 8,
      'formatter' => function( $d, $row ) {
          
          return '<a rel="modal:open" class="w3-btn w3-small w3-blue w3-round" href=view-invoice.php?p=no&id='.$d.'>View Receipt</a>';
      }
  ),
  //array( 'db' => 'note', 'dt' => 9 ),
);

// SQL server connection information
$sql_details = array(
  'user' => $username,
  'pass' => $password,
  'db'   => $db_name,
  'host' => $host
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* If you just want to use the basic configuration for DataTables with PHP
* server-side, there is no need to edit below this line.
*/

require( '../../lib/ssp.dt.php' );
$where = "csp_id_fk = '$csp_id'";
echo json_encode( SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where ));

?>
