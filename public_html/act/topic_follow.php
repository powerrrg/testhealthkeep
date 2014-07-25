<?php
require_once('../../engine/starter/config.php');

onlyLogged();

if(!isset($_GET["id"])){
	go404();
}

$id=(int)$_GET["id"];

if($id==0){
	go404();
}

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();
$resTopic=$topicClass->getById($id);

if(!$resTopic["result"]){
	go404();
}

$resfollow=$topicClass->isFollowing($resTopic[0]["id_topic"]);

if($resfollow["result"]){
	$topicClass->unfollow($id);
}else{
	$topicClass->follow($id);
}

$folder=$topicClass->pathSingular($resTopic[0]["type_topic"]);

header("Location:".WEB_URL.$folder."/".$resTopic[0]["url_topic"]);