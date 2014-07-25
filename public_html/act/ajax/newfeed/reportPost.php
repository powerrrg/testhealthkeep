<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["id"])){
	go404();
}

$id=(int)$_POST["id"];

if($id==0){
	go404();
}

require_once(ENGINE_PATH."starter/mail.php");

$mail->Subject    = "Report from HealthKeep";
		
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$body="The user ".WEB_URL.USER_NAME." reported the post ".WEB_URL."ges/post/".$id;
$mail->MsgHTML($body);

$mail->AddAddress('lyle@healthkeep.com', 'HealthKeep');

if($mail->Send()) {
	echo "ok";
}else{
	echo "error";
}