<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(!isset($_GET["id"]) || !isset($_POST["text"]) || !isset($_GET["back"])){
	go404();
}

$id=(int)$_GET["id"];

if($id==0){
	go404();
}

$text=trim($_POST["text"]);

if(strlen($text)<2){
	go404();
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$postClass->addComment($id,$text);

header("Location:".WEB_URL.$_GET["back"]);