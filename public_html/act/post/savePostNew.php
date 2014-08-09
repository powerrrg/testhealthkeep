<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["txtPost"])){
    go404();
}

$text=trim($_POST["txtPost"]);

if(strlen($text)<5){
    go404();
}

if(isset($_POST["forceTopic"])){
    $forceTopic=(int)$_POST["forceTopic"];
}else{
    $forceTopic=0;
}

if(isset($_POST["asMessage"])){
    $asMessage=(int)$_POST["asMessage"];
}else{
    $asMessage=0;
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$postClass->addNewV2Post($text, "avatarFile", $forceTopic,$asMessage);

$profileClass->updateBadge("sharing", USER_ID);

header("Location:".WEB_URL."feed");