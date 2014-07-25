<?php
require_once('../../../engine/starter/config.php');


if(isset($_POST["yourName"]) && isset($_POST["yourFriend"])){

		
		$name=$_POST["yourName"];
		$friend=$_POST["yourFriend"];
		$_SESSION["token"]="";
		
		if($name=="" || $friend==""){
			go404();
		}
		
		$emails=explode(',', $friend);
		
		foreach($emails as $oneMail){
		
			if(!filter_var( $oneMail, FILTER_VALIDATE_EMAIL )){
				go404();
			}
		
		}
		
		require_once(ENGINE_PATH."class/invite.class.php");
		$inviteClass=new Invite();
		
		$fromEmail="team@healthkeep.com";
		
		require_once(ENGINE_PATH."starter/mail.php");
		
		$mail->Subject    = $name." invited you to HealthKeep";
		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$body="Hello,<br /><br />Your friend $name has invited you to join them on <a href=\"".WEB_URL."\" target=\"_blank\">HealthKeep</a>, an exciting new social health network.";
		$body.="<br /><br />Hope to see you there,<br /><i>The HealthKeep Team</i>";
		$mail->MsgHTML($body);
		
		$error="";
		foreach($emails as $oneMail){
			$mail2 = clone $mail;
			$mail2->AddAddress($oneMail);
			if(!$mail2->Send()) {
				$error.=$mail2->ErrorInfo;
			} else {
				$inviteClass->save($name,$oneMail);
			}
		}
		
		if($error==""){
			echo "ok";
		}else{
			echo $error;
		}
		

}else{
	go404();
}