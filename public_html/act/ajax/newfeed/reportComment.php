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

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$res=$postClass->getCommentById($id);

if(!$res["result"]){
	echo "error";
	exit;
}

require_once(ENGINE_PATH."starter/mail.php");

$mail->Subject    = "Report from HealthKeep";
		
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$body="The user ".WEB_URL.USER_NAME." reported a comment from the post ".WEB_URL."ges/post/".$res[0]["id_post_pc"]."<br /><br />The comment says:<br />";
$body.=$res[0]["text_pc"];
$mail->MsgHTML($body);

$mail->AddAddress('lyle@healthkeep.com', 'HealthKeep');

if($mail->Send()) {
	echo "ok";
}else{
	echo "error";
}