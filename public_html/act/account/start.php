<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["username"])|| !isset($_POST["password"]) || !isset($_POST["gender"]) || !isset($_POST["experience"])){
    go404();
}

$username=trim($_POST["username"]);
$password=$_POST["password"];

$gender=$_POST["gender"];

if($gender!="m" && $gender!="f"){
    go404();
}

$theimage=rand(1,12);


$experience=$_POST["experience"];
if(strlen($experience)<5){
    go404();
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
    go404();
}

$userClass->updatePassword(USER_ID,$password);

if($username!=$resProfile[0]["username_profile"]){
    $res=$profileClass->getByUsername($username);
    if($res["result"]){
        go404();
    }
    $res=$profileClass->updateUsername($username);
}

$profileClass->updateGender($gender);

if($theimage<=12){
    if($gender=="f"){
        $curGender="woman";
    }else{
        $curGender="man";
    }
    $res=$profileClass->copyAvatar($curGender.$theimage.".jpg");
}else{
    $res=$profileClass->changeAvatar("avatarFile");
}

require_once(ENGINE_PATH.'class/message.class.php');
$messages = new Message();
$messages->doesntAllowEmailStart(USER_ID);

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();
$res=$postClass->addNewNoTopic($experience);

$res=$profileClass->saveStep('99');

$profileClass->updateBadge("sharing",USER_ID);

$_SESSION["welcome"]=array("first"=>true);

header("Location:".WEB_URL."feed");	