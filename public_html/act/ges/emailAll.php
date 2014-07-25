<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

if(!isset($_POST["from"]) || !isset($_POST["subject"]) || !isset($_POST["message"]) || !isset($_POST["name"]) || !isset($_POST["email"])){
	go404();	
}

$fromEmail=$_POST["from"];
$name=$_POST["name"];
$email=$_POST["email"];
$subject=$_POST["subject"];
$message=$_POST["message"];

if(filter_var( $email, FILTER_VALIDATE_EMAIL )){

	require_once(ENGINE_PATH."starter/mail.php");
	
	$mail->Subject = $subject;
	
	//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$body=$message;
	$mail->MsgHTML($body);
	
	$mail->AddAddress($email, $name);
	
	if(!$mail->Send()) {
	  echo "error, not sent";
	} else {
	  echo "sent";
	}
}else{
	echo "invalid email";
}
		
		