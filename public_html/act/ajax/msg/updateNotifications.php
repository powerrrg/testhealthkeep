<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["nots"])){
	go404();
}
$not=trim($_POST["nots"]);

require_once(ENGINE_PATH.'class/message.class.php');
$messageClass=new Message();

$res=$messageClass->removeAllBlockEmail();

if($not!=""){
	$pieces=explode("_", $not);
	foreach($pieces as $key=>$value){
		if($value!=""){
			$res=$messageClass->blockEmailNot($value);
		}
	}
}

if($res){
	echo "ok";
}else{
	echo "error";
}