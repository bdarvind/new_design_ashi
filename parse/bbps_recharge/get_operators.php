<?php
header('content-type:text/html');
include '../../includes/config.php';
include '../../includes/functions.php';

if(isset($_GET['service_type'])){
    
    $service_type = $_GET['service_type'];
    
    $query = run_query("SELECT * FROM  kwik_operator WHERE service_type = '$service_type' ");
    foreach($query as $key => $value){
        echo "<option value='".$value['operator_id']."'>".$value['operator_name']."</option>";
    }
   
    /*$curl = curl_init();

    curl_setopt_array($curl, array(
    //CURLOPT_URL => 'https://argnidhi.com/csp/netbanking_api/bbps/get_operators.php?service_type='.$service_type,
      CURLOPT_URL => 'https://ashidigitalpay.in/csp/netbanking_api/bbps/get_operators.php?service_type='.$service_type,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    
    $response = json_decode(curl_exec($curl),true);
    
    curl_close($curl);
    
    if($response['status'] == "success"){
        foreach($response['data'] as $key => $value){
            echo "<option value='".$value['operator_id']."'>".$value['operator_name']."</option>";
        }
    }else{
        $return['status'] = "error";
        $return['msg'] = "Invalid Service Type";
        $return['data'] = NULL;
    }*/
    
    
}else{
    $return['status'] = "error";
    $return['msg'] = "Invalid Service Type";
    $return['data'] = NULL;
}

//echo json_encode($return);