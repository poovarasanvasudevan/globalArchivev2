<?php
include_once '../common/DatabaseConnection.php';
include "../common/Config.php";

function wwwcopy($dynamic) {

	ob_start();

	require $dynamic;

	$temp= ob_get_contents();
	 
	
	ob_end_clean();

	//$fh = fopen($static,"w");
	//fwrite($fh,$temp);
	//fclose($fh);
	
	return $temp;
	 
}

function sendMail($to,$body) {
	
	
	
	
	$mail = new PHPMailer;
	
	//$mail->SMTPDebug = 3;                               // Enable verbose debug output
	
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = MAIL_HOST;  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = MAIL_USERNAME;                 // SMTP username
	$mail->Password = MAIL_PWD;                           // SMTP password
	$mail->SMTPSecure = MAIL_LAYER;                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = MAIL_PORT;                                    // TCP port to connect to
	
	$mail->From = MAIL_USERNAME;
	$mail->addAddress($to);               // Name is optional
	$mail->isHTML(true);                                  // Set email format to HTML
	
	$mail->Subject = MAIL_SUBJECT;
	$mail->Body    = $body;
	$mail->AltBody = 'Your Task Today';
	
	if(!$mail->send()) {
		return 'Message could not be sent.'.'Mailer Error: ' . $mail->ErrorInfo;;
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		return 'Message has been sent';
	}
	
}

?>