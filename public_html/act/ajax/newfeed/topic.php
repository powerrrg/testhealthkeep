<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["p"]) && isset($_POST["t"]) && isset($_POST["x"]) && isset($_POST["y"])){

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
	
	$type=$_POST["t"];
	
	$path=$topicClass->pathPlural($type);
	
	if(!$type){
		go404();
	}
	
	$thisOrder="recent";
	if($_POST["x"]=="rank"){
		$thisOrder="rank";
	}
	
	$thisFilter="all";
	if($_POST["y"]=="exp" || $_POST["y"]=="news"){
		$thisFilter=$_POST["y"];
	}
	
	$resPosts = $postClass->getTopicPosts($type,$pageNum,$thisOrder,$thisFilter);
	
	$backPath="feed/$path";
	
	$jsTopFormIsSet=false;
	
	$noHolderDiv=1;

	require_once(ENGINE_PATH."render/feed/posts.php");

}else{
	go404();
}