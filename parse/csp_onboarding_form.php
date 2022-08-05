<?php
include '../vendor/autoload.php';
include '../include/config.php';
include '../lib/upload.php';
include '../include/functions.php';

session_start();
$csp_id = $_SESSION['csp'];
$csp_data = select('csp','id',$_SESSION['csp']);

if (isset($_FILES['form'])) {
		$allowedFile = array("docx","doc","pdf");
		// call the Upload class and upload file
		$upload = new Upload($_FILES['form'],"../docs/uploads/",10000000,$allowedFile);
		// show results
		$result = $upload->GetResult();
		
		if($result['type'] == "success"){
		    $body = "Hello<br><br> A User has submitted the CSP Onboarding Form, that is attached to this email.<br><br>User Details are - <br><br>
		    Name : ".$csp_data['name']."<br>
		    Email Address : ".$csp_data['email']."<br>
		    Phone : ".$csp_data['mobile_number']."<br>
		    ";
    		
    		$transport = (new Swift_SmtpTransport('smtp.elasticemail.com', 587, 'tls'))
              ->setUsername('AC79A3E2DFA618701A6ACF2A8D4F7A1AA2536E7E238515EC0B429EEAA69FAC32FBF1E39DBA670FF894E1FD3F391B9EB6')
              ->setPassword('AC79A3E2DFA618701A6ACF2A8D4F7A1AA2536E7E238515EC0B429EEAA69FAC32FBF1E39DBA670FF894E1FD3F391B9EB6');
    
    
    
            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);
    
            // Create a message
            $message = new Swift_Message('AshiDigitalPay | New CSP Onboard form');
            $message->setFrom(['info@ashidigitalpay.in' => 'Ashi Digital']);
            $message->setTo(['ashidigitalpayment@gmail.com']);
            $message->attach(Swift_Attachment::fromPath($result['path']));
    
            $message->setBody($body, 'text/html');
            // Send the message
            $result_mail = $mailer->send($message);
            
            if($result_mail){
                unlink($result['path']);
                $_SESSION['onboard_form_update'] = "<div class='w3-panel w3-border w3-margin w3-border-green w3-round w3-pale-green w3-padding'>Form Uploaded Successfully</div>";
            }else{
                $_SESSION['onboard_form_update'] = "<div class='w3-panel w3-border w3-margin w3-border-red w3-round w3-pale-red w3-padding'>Unable to Upload Form, Try again</div>";
            }
		}else{
		    $_SESSION['onboard_form_update'] = "<div class='w3-panel w3-border w3-margin w3-border-red w3-round w3-pale-red w3-padding'>".$result['message']."</div>";
		}
		
		 header('Location:/csp/csp-onboarding-form.php');

	}