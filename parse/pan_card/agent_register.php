<?php

include '../../include/config.php';
include '../../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://api.rechapi.com/pan/registerAgent.php?format=json&token='.$rech_api_key.'&mobile='.$csp_data['mobile_number'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
