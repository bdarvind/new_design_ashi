<?php

    $server_status = "up";
    $netbanking_limit = 500;
    //site Details
    $company_short_name = "Ashi Digital";
    $site_name = "ASHI DIGITAL PAY";
    $phone = "9756376759";
    $address = "Firozabad";
    $site_logo = "https://i.imgur.com/yuhGRMy.png";

    $upi_prefix = "ecegs";

    $crn_code = "ASHI";

    $powered_by = "https://www.searchpng.com/wp-content/uploads/2019/01/ICICI-Bank-PNG-Logo.png";

    

    $hypto_api_key = "055b5c99-d550-45fc-8fa0-2daaa3247720";
    $kwik_api_key = "73fc96-7e56cc-65e81a-e445fb-c69f17";
    
    
    
    //new
    // $bankopen_key = "efa9d0b0-1176-11ed-8dbf-4b7b499c2584:e0f7abcaa2061fa8bc1ef347ef07a948992e7060";
	// $bankopen_key_live = 'efa9d0b0-1176-11ed-8dbf-4b7b499c2584:e0f7abcaa2061fa8bc1ef347ef07a948992e7060';

    // $bankopen_key = "CF243472CBJPB0TG8KNNHT2F0J80:9926c6aa8a3363f59f05ca09cf0bcf338494e8d6";
	$bankopen_key_live = 'CF195089CBKGPVHF0UP2UVDB41U0:e736a78474469584e43cb050e41672a5fbe292ec';
	
	$rgfintech_recharge_key = '94107b262894582fbdff9524e0541dba';
    $rgfintech_userid = 56;
    $rgfintech_cutomernum = 9897582050;
    $rgfintech_cust_pin = 4002;
	
	
	//rownpay
	$roundpay_recharge_key 	 = '0e3895a59323c99bdb94c3518d613bba';
	$roundpay_user_id		 = '10222';
	$roundpay_customer_num 	 = '8287269692';
	$roundpay_customer_pin	 = '283203';
	
    $eko_aeps_key = "7692aac1-93a2-48f4-b77c-f3fc6cb2a5ff";
    $eko_developer_key = "dd7cd97e9aae7adb0282dee757e3a4e3";
    $eko_initiator_id = 9837078320;
    
    //old
    /*$eko_aeps_key = "02e4b76d-cf4c-4c98-9105-919f4f6980f5";
    $eko_developer_key = "27a4b139237c49a2e99daad72bf1f781";
    $eko_initiator_id = 9311252103;
    */
    $eko_callback_url = "https://ashidigitalpay.in/csp/webhook/aeps.php";


    $host = "localhost";
    $username = "root";
    $password = "";
    $db_name = "ashidigital";
    

    $conn = mysqli_connect($host,$username,$password,$db_name);
    mysqli_set_charset($conn, 'utf8');
    if(!$conn){
        echo "Error Connecting".mysqli_connect_error();
    }

    /*$server_status = "up";


    $netbanking_limit = 500;

    //site Details
    $company_short_name = "Ashi Digital";
    $site_name = "ASHI DIGITAL PAY";
    $phone = "9756376759";
    $address = "Firozabad";
    $site_logo = "https://i.imgur.com/yuhGRMy.png";

    $upi_prefix = "ecegs";

    $crn_code = "ASHI";

    $powered_by = "https://www.searchpng.com/wp-content/uploads/2019/01/ICICI-Bank-PNG-Logo.png";

    

    $hypto_api_key = "055b5c99-d550-45fc-8fa0-2daaa3247720";
    $rech_api_key = "GpAlpfgYCDXpfWqs5cSM";

    //$kwik_api_key = "54ab08-dcf53c-a45270-76a181-23b447";
    $kwik_api_key = "73fc96-7e56cc-65e81a-e445fb-c69f17";
    //new api
    $rgfintech_recharge_key = '94107b262894582fbdff9524e0541dba';
    $rgfintech_userid = 56;
    $rgfintech_cutomernum = 9897582050;
    $rgfintech_cust_pin = 4002;
    //new api details..end
    $eko_aeps_key = "02e4b76d-cf4c-4c98-9105-919f4f6980f5";
    $eko_developer_key = "27a4b139237c49a2e99daad72bf1f781";
    $eko_initiator_id = 9311252103;
    $eko_callback_url = "https://ashidigitalpay.in/csp/webhook/aeps.php";
    */
    

?>
