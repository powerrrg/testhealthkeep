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

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$postClass->deleteComment($id);

$profileClass->updateBadge("helpful",USER_ID);

if($res){
	echo "ok";
}else{
	echo "error";
}