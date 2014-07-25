<?php
require_once(ENGINE_PATH."class/phpMailer/class.phpmailer.php");
$mail = new PHPMailer();

if(isset($fromEmail) && $fromEmail=="lyle@healthkeep.com"){
	$fromEmailPassword="dW!{W{la8FnS";
	$fromEmailName="Lyle Dennis - HealthKeep";
}else if(isset($fromEmail) && $fromEmail=="team@healthkeep.com"){
	$fromEmailPassword="3g9)h3W}rE~W";
	$fromEmailName="HealthKeep Team";
}else{
	$fromEmail="info@healthkeep.com";
	$fromEmailPassword="d.;[W?=m]p%&";
	$fromEmailName="HealthKeep";
}

$mail->IsSMTP(); // telling the class to use SMTP
$mail->CharSet="UTF-8";
$mail->Host       = "host.healthkeep.me"; // SMTP server
//$mail->SMTPDebug  = 2;                   // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
$mail->SMTPSecure = 'ssl';
$mail->Username   = $fromEmail;            // SMTP account username
$mail->Password   = $fromEmailPassword;    // SMTP account password

$mail->SetFrom($fromEmail, $fromEmailName);
?>