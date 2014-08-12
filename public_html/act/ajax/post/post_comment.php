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

$res=$postClass->addComment($id,$text);

if ($res) {
    $resCom = $postClass->getLastCommentFromUser(USER_ID);

    if ($resCom["result"]) {
        $displayName = $configClass->name($resCom);

        require_once(ENGINE_PATH."html/list/comment.php");
    } else {
        echo "error";
    }
} else {
    echo "error";
}