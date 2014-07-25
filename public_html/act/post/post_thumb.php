<?php
require_once('../../../engine/starter/config.php');

onlyLogged();


if(!isset($_GET["id"]) || !isset($_GET["vote"]) || !isset($_GET["back"])){
	go404();
}

$id=(int)$_GET["id"];

if($id==0){
	go404();
}

if($_GET["vote"]=="up"){
	$vote=1;
}else if($_GET["vote"]=="down"){
	//$vote=-1;
	go404();
}else{
	go404();
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$postClass->addVote($id,$vote);

$profileClass->updateBadge("supportive",USER_ID);

header("Location:".WEB_URL.$_GET["back"]);