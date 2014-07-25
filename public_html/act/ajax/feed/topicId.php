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
	
	$type=(int)$_POST["t"];
	if($type<=0){
		go404();
	}
	
	$path=$topicClass->pathSingular($type);
	
	if(!$type){
		go404();
	}
	
	$resTopic=$topicClass->getById($type);
	
	if(!$resTopic["result"]){
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
	
	$resPosts = $postClass->getTopicIdPosts($type,$pageNum,$thisOrder,$thisFilter);
	
	$backPath="feed/$path/".$resTopic[0]["url_topic"];
	
	$jsTopFormIsSet=false;
	
	$noHolderDiv=1;

	require_once(ENGINE_PATH."html/list/posts.php");

}else{
	go404();
}