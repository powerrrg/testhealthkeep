<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$res=$postClass->getNextNewsPostToLookLanguage();

if($res["result"]){
	$resIs=$postClass->isEnglish($res[0]["title_post"]);
	
	if(!$resIs){
		$postClass->forceDeletePost($res[0]["id_post"]);
	}
}