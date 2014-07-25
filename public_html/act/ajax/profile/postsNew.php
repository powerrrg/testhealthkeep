<?php
require_once('../../../../engine/starter/config.php');

if(isset($_POST["p"]) && isset($_POST["t"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/config.class.php');
	$configClass=new Config();
	
	require_once(ENGINE_PATH."class/post.class.php");
	$postClass=new Post();
	
	$id=(int)$_POST["t"];
	
	$resProfile=$profileClass->getById($id);
	
	if(!$resProfile["result"]){
		go404();
	}
	
	$resPosts=$postClass->getPostsFromAndAboutUser($resProfile[0]["id_profile"],$pageNum);
	
	$backPath=$resProfile[0]["username_profile"];
	
	$jsTopFormIsSet=false;
	
	$noHolderDiv=1;

	$iMHealthTL=1;

	require_once(ENGINE_PATH."render/feed/posts.php");

}else{
	go404();
}