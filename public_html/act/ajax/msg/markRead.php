<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["id"])){
	go404();
}

$id=ltrim($_POST["id"],"message_");

$id=(int)$id;

if($id==0){
	go404();
}

require_once(ENGINE_PATH.'class/message.class.php');
$messageClass=new Message();

$res=$messageClass->markRead($id);

if($res){
	echo "ok";
}else{
	echo "error";
}