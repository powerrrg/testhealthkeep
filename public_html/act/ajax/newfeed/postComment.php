<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["id"]) || !isset($_POST["text"])){
    go404();
}

$id=(int)$_POST["id"];

if($id==0){
    go404();
}

$text=trim($_POST["text"]);

if(strlen($text)<2){
    go404();
}

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$postClass->addComment($id,$text);

if ($res) {
    require_once(ENGINE_PATH.'class/notification.class.php');
    $notification = new Notification();
    $ownerPost = $postClass->getOwnerPost($res[0]['id_post_pc']);
    $notification->pushNotification($ownerPost, 3, true, true, true, array('id' => $id));

    $resCom=$postClass->getLastCommentFromUser(USER_ID);

    if ($resCom["result"]) {
        $profileClass->updateBadge("helpful",USER_ID);

        $displayName=$configClass->name($resCom);

        require_once(ENGINE_PATH."render/feed/comment.php");
    } else {
        echo "error";
    }
} else {
    echo "error";
}