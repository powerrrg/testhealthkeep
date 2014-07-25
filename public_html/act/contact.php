<?php
require_once('../../engine/starter/config.php');


if(isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["message"]) && isset($_POST["hpot"]) && isset($_POST["token"]) && isset($_SESSION["token"])){
	if($_SESSION["token"]!=$_POST["token"] || $_POST["hpot"]!=""){
		go404();
	}else{
		
		$email=$_POST["email"];
		$name=$_POST["name"];
		$message=$_POST["message"];
		$_SESSION["token"]="";
		
		if($email=="" || $name=="" || $message==""){
			go404();
		}
		
		if(!filter_var( $email, FILTER_VALIDATE_EMAIL )){
			go404();
		}
		
		require_once(ENGINE_PATH."starter/mail.php");
		
		$mail->AddReplyTo($email, $name);
		
		$mail->Subject    = "Contact from HealthKeep";
		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$body="Contact from: $name - $email<br /><br />Message:<br />";
		$body.=nl2br($message);
		$mail->MsgHTML($body);
		
		$mail->AddAddress('info@healthkeep.com', 'HealthKeep');
		
		if(!$mail->Send()) {
		  header("Location:".WEB_URL."contact#error");
		} else {
		  header("Location:".WEB_URL."contact#ok");
		}
    
				
		

	}
}else{
	go404();
}