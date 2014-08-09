<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();


if(!isset($_GET["id"])){
    go404();
}

$id=(int)$_GET["id"];

if($id==0){
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
$profileClass=new Profile();

$res=$postClass->removeVote($id);

if ($res) {
    $profileClass->updateBadge("supportive",USER_ID);
    $profileClass->updateBadge("karma",$resPost[0]["id_profile_post"]);

    require_once(ENGINE_PATH.'class/notification.class.php');
    $notification = new Notification();
    $ownerPost = $postClass->getPostOwner($id);
    if (isset($ownerPost[0]['post_owner_id'])) {
        $notification->pushNotification($ownerPost[0]['post_owner_id'], 5, false, false, false, array('id' => $id));
    }

    echo "ok";
} else {
    echo "error";
}