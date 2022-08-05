<?php

#default values
    $clientId = "CF195089CBKGPVHF0UP2UVDB41U0";
    $clientSecret = "e736a78474469584e43cb050e41672a5fbe292ec";
    $env = "test";

    $baseUrls = array(
        'prod' => 'https://payout-api.cashfree.com',
        'test' => 'https://payout-gamma.cashfree.com',
    );

    $urls = array(
        'auth' => '/payout/v1/authorize',
        'requestBatchTransfer' => '/payout/v1/requestBatchTransfer',
        'getBatchTransferStatus' => '/payout/v1/getBatchTransferStatus?batchTransferId=',
    );

    $url = 'https://payout-gamma.cashfree.com/payout/v1/requestBatchTransfer';
    $post = array(
        "appId" => $clientId,
        "secretKey" => $clientSecret,
        'batchTransferId' => 'MKR12456644',
        'batchFormat' => 'BANK_ACCOUNT',
        'deleteBene' => 1,
        'batch' => [ 
            ["transferId" => 'MKR12456644', 
            "amount" => '50', 
            "phone" => '9616806946', 
            "bankAccount" => '956545454545', 
            "ifsc" => 'PYTM0123456', 
            "email" => 'senaukri@gmail.com', 
            "name" => 'Arvindn Singh'] ],
        "returnUrl" => "https://www.stllogistics.com/csp/parse/cashfree_batch_transfer.php",
        "notifyUrl" => "https://www.stllogistics.com/csp/parse/cashfree_batch_transfer.php"
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $server_output = curl_exec($ch);

    curl_close ($ch);

    $response = json_decode($server_output, true);

    if($response['status'] == "OK"){
        $return['status'] = "success";
        $return['msg'] = "Please wait while we redirect you to payment gateway";
        $return['data'] = $response['cftoken'];
        $return['txn_id'] = 'MKR12456644';
        $return['deduct'] = '50';
        $return['gst'] = '0';
    }else{
        $return['status'] = "error";
        $return['msg'] = "Something went wrong, please try again later";
    }

    echo json_encode($return);


?>