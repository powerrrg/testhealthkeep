<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["txtPost"]) || !isset($_POST["topic"])){
	go404();
}

$text=trim($_POST["txtPost"]);

if(strlen($text)<5){
	go404();
}

$topics=explode(",", $_POST["topic"]);
if(!isset($topics[0])){
	go404();
}

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();
foreach($topics as $key=>$value){
	$resTopic=$topicClass->getById($value);
	
	if(!$resTopic["result"]){
		go404();
	}
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$postClass->addNewTopicsPost($topics,$text,"avatarFile");


header("Location:".WEB_URL."feed/new");