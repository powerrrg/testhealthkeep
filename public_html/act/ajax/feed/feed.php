<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["p"]) && isset($_POST["t"]) && isset($_POST["x"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	$thisOrder="recent";
	if($_POST["t"]=="rank"){
		$thisOrder="rank";
	}
	$thisFilter="all";
	if($_POST["x"]=="exp" || $_POST["x"]=="news"){
		$thisFilter=$_POST["x"];
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/config.class.php');
	$configClass=new Config();
	
	require_once(ENGINE_PATH."class/post.class.php");
	$postClass=new Post();
	
	$resPosts = $postClass->getFeedPosts($pageNum,$thisOrder,$thisFilter);
	
	$backPath="feed/";
	
	$jsTopFormIsSet=false;
	
	$noHolderDiv=1;

	require_once(ENGINE_PATH."html/list/posts.php");

}else{
	go404();
}