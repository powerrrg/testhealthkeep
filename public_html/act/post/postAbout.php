<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_GET["id"]) || !isset($_POST["txtPost"])){
	go404();
}

$id=(int)$_GET["id"];

if($id==0){
	go404();
}

$text=trim($_POST["txtPost"]);

if(strlen($text)<5){
	go404();
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();
$resUser=$profileClass->getById($id);

if(!$resUser["result"]){
	go404();
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$postClass->addNewAbout($id,$text);


header("Location:".WEB_URL.$resUser[0]["username_profile"]."#iHoldPosts");