<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/message.class.php');
$messageClass=new Message();

$res=$postClass->getPostNextEmail();

if($res["result"]){
	$messageClass->newPost($res);
}