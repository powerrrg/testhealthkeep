<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["missing"]) && isset($_POST["what"])){
	$text=$_POST["missing"];
	$what=$_POST["what"];

	$resUser=$userClass->getById(USER_ID);
	
	if(!$resUser["result"]){
		echo 'There was an error. Please contact us <a href="mailto:info@healthkeep.com">info@healthkeep.com</a>';
		exit;
	}
	
	require_once(ENGINE_PATH."starter/mail.php");
		
		$mail->Subject    = "User can't find $what - HealthKeep";
		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$body="Hello Lyle:<br /><br />The user <a href=\"".WEB_URL.USER_NAME."\">".USER_NAME."</a> can't find the $what: $text<br /><br />Please have a look and contact the user (<a href=\"mailto:".$resUser[0]["email_user"]."\">".$resUser[0]["email_user"]."</a>) when you have a reply.<br /><br />HealthKeep";
		$mail->MsgHTML($body);
		
		$mail->AddAddress('info@healthkeep.com', 'HealthKeep');
		
		if(!$mail->Send()) {
		  echo 'There was an error and we could not save your request. Please contact us <a href="mailto:info@healthkeep.com">info@healthkeep.com</a>';
		} else {
		  echo 'Your request was saved. We will contact you once that '.$what.' is added to our database. Thank you.';
		}
	
}else{
	go404();
}