<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'class/message.class.php');
$messageClass=new Message();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

$res=$messageClass->getNext10UnReadWarn();

if($res){

	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resProfile=$profileClass->getByIdComplete($res);
	
	if($resProfile["result"]){
	
		$resAllow=$messageClass->doesntAllowEmail($resProfile[0]["id_profile"],"newpost");
	    
	    if($resAllow["result"]){
	    	//marked at notifications that doesn't want this email
		    exit;
	    }
		
		require_once(ENGINE_PATH."starter/mail.php");
		
		$name=$configClass->name($resProfile);
		$tomail=$resProfile[0]["email_user"];
		

		
		$mail->Subject = "$name, you have new messages waiting on HealthKeep!";
		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$body="Hi $name,<br /><br />You have new messages waiting!<br /><br />Other users may have answered a question you posted, shared a new experience about a health topic you're interested in, or offered words of support.<br /><br /><a href=\"".WEB_URL."msg\">Check out your new messages now</a>";
		$body.=$configClass->endEmailText();
		$mail->MsgHTML($body);
		
		$mail->AddAddress($tomail, $name);
		
		if(!$mail->Send()) {
			//echo 'Message was not sent.';
            echo 'Mailer error: ' . $mail->ErrorInfo;
		}else{
			//echo 'Message has been sent.';
		}
		
	}
	
	
}else{
	//echo "do nothing";	
}

