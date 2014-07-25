<?php
require_once('../../engine/starter/config.php');

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

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();
$resTopic=$topicClass->getById($id);

if(!$resTopic["result"]){
	go404();
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$postClass->addNew($id,$text);


$folder=$topicClass->pathSingular($resTopic[0]["type_topic"]);

header("Location:".WEB_URL.$folder."/".$resTopic[0]["url_topic"]);