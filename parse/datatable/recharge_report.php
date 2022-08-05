<?php
include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];

$table = 'kwik_recharge';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'number', 'dt' => 1 ),
    array( 'db' => 'amount', 'dt' => 2 ),
    array( 'db' => 'status', 'dt' => 3 ),
    array( 'db' => 'operator', 'dt' => 4,
        'formatter' => function( $d, $row ) {
            $operator = select('kwik_operator','operator_id',$d);
            return $operator['operator_name'];
        }
    ),
    array( 'db' => 'circle', 'dt' => 5,
        'formatter' => function( $d, $row ) {
            $circle = select('kwik_circle','circle_code',$d);
            return $circle['circle_name'];
          }
    ),
    array( 'db' => 'txn_id', 'dt' => 6 ),
    array( 'db' => 'operator_type', 'dt' => 7 ),
    array( 'db' => 'created_on', 'dt' => 8 ),
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
$where = "user_id = '$csp_id' AND user_type='csp' AND (operator_type ='prepaid' OR operator_type='postpaid') ";
echo json_encode(
  SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $where )
);

?>
