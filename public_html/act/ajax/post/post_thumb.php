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

$resPost=$postClass->getById($id);

if(!$resPost["result"]){
    echo "error";
    exit;
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass = new Profile();

$res = $postClass->addVote($id, $vote);

if($res){
    $profileClass->updateBadge("supportive",USER_ID);
    $profileClass->updateBadge("karma",$resPost[0]["id_profile_post"]);

    require_once(ENGINE_PATH.'class/notification.class.php');
    $notification = new Notification();
    $ownerPost = $postClass->getPostOwner($id);
    if (isset($ownerPost[0]['post_owner_id'])) {
        $notification->pushNotification($ownerPost[0]['post_owner_id'], 5, false, false, false, array('id' => $id));
    }

    echo "ok";
}else{
    echo "error";
}