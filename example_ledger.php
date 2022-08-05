<?php

include '../include/config.php';

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

<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>

<body>

<table>
  <tr>
    <th>TXN ID</th>
    <th>Date</th>
    <th>Type</th>
    <th>Debit</th>
    <th>Credit</th>
    <th>Balance</th>
  </tr>

<?php
    foreach($list_balance as $transaction)
        {
    echo '<tr>
    <td>'.$transaction['txn_id'].'</td>
    <td>'.$transaction['created_on'].'</td>
    <td>'.$transaction['type'].'</td>
    <td>'
    .$transaction['total_debits'].
    '</td>
    <td>'
    .$transaction['total_credits'].
    '</td>
    <td>'.$transaction['balance'].'</td>
</tr>';
}
?>

</table>


</body>
</html>
