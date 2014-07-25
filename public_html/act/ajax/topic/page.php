<?php
require_once('../../../../engine/starter/config.php');


if(isset($_POST["p"]) && isset($_POST["t"]) && isset($_POST["x"]) && isset($_POST["y"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	require_once(ENGINE_PATH.'class/config.class.php');
	$configClass=new Config();
	
	require_once(ENGINE_PATH."class/post.class.php");
	$postClass=new Post();
	
	$id=(int)$_POST["t"];
	
	$resTopic=$topicClass->getByUrl($_POST["x"],$_POST["y"]);
	
	if(!$resTopic["result"]){
		go404();
	}
	
	$backPath=$_POST["x"]."/".$topicClass->pathSingular($_POST["y"]);
	
	$resPosts = $postClass->getByTopicId($id,$pageNum);
	
	$jsTopFormIsSet=false;
	
	$noHolderDiv=1;

	require_once(ENGINE_PATH."html/list/posts.php");

}else{
	go404();
}