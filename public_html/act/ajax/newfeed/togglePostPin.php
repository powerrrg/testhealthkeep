<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(USER_TYPE!=9){
	go404();
}

if(!isset($_POST["id"])){
	go404();
}

$id=(int)$_POST["id"];

if($id==0){
	go404();
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$res=$postClass->togglePostPin($id);

if($res){
	echo "ok";
}else{
	echo "error";
}