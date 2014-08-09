<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_GET["id"]) || !isset($_GET["vote"])){
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

$resCom=$postClass->getCommentById($id);

if(!$resCom["result"]){
    echo "error";
    exit;
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$postClass->addCommentVote($id,$vote);

require_once(ENGINE_PATH.'class/notification.class.php');
$notification = new Notification();
$ownerComment = $postClass->getCommentOwner($id);
if (isset($ownerComment[0]["comment_owner_id"])) {
    $notification->pushNotification($ownerComment[0]["comment_owner_id"], 4, false, false, false, array('id' => $id));
}

if($res){
    $profileClass->updateBadge("supportive",USER_ID);
    $profileClass->updateBadge("karma",$resCom[0]["id_profile_pc"]);
    echo "ok";
}else{
    echo "error";
}