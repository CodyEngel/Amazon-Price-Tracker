<?php
require_once("../includes/config.php");
$mail = new PHPMailer;

smtpmailer("cengel815@gmail.com", "unmarkettest@gmail.com", "Unmarket", "TEST EMAIL", "Hey Cody, this is a test ya dumb ass.\nHere is a line break I think.");


function smtpmailer($to, $from, $from_name, $subject, $body) { 
	GLOBAL $gmail_username;
	GLOBAL $gmail_password;

	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = $gmail_username;  
	$mail->Password = $gmail_password;           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo;
		echo $error; 
		return false;
	} else {
		$error = 'Message sent!';
		echo $error;
		return true;
	}
}