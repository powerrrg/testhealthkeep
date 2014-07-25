<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/external.class.php');
$externalClass=new External();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();

require_once(ENGINE_PATH."class/profile.class.php");
$profileClass=new profile();

$pubDate=date("Y-m-d H:i:s",time());

$resSourceUrl=parse_url($_POST["url"]);

$resSource=$profileClass->lookForNewsSource($resSourceUrl["host"]);
if(!$resSource["result"]){
	$resAdd=$userClass->addNewProAuto($resSourceUrl["host"],$resSourceUrl["host"]."@healthkeep.org",$resSourceUrl["host"]."st4ff","1111111111","news");
	$resSource=$profileClass->lookForNewsSource($resSourceUrl["host"]);
	if(!$resSource["result"]){
		exit;
	}	
}

if(isset($_POST["extImage"]) && $_POST["extImage"]!=""){
	$tempTB=$_POST["extImage"];
	$externalClass->processNewsPostAuto($_POST["title"],$_POST["url"],$_POST["description"],$pubDate,$resSource[0]["id_profile"],$tempTB,1);
	$resPost=$postClass->getLastPostFromUser($resSource[0]["id_profile"]);
}else{
	$externalClass->processNewsPost($_POST["title"],$_POST["url"],$_POST["description"],$pubDate,$resSource[0]["id_profile"],1);
	$resPost=$postClass->getLastPostFromUser($resSource[0]["id_profile"]);
	$img="uploadimage";
	if($resPost["result"] && isset($_FILES[$img])){
    	$imgPath=PUBLIC_HTML_PATH."img/post/";
	    $image=$configClass->uploadImage($img, $imgPath);
	    if($image["image"]!=""){
			$image=$image["image"];
	    }else{
		    $image="";
	    }
	    if($image!=""){
		    $postClass->saveImage($image,$resPost[0]["id_post"]);
	    }
	    
	}
}

if($resPost["result"]){
	$postClass->togglePostPin($resPost[0]["id_post"]);
}

header("Location:".WEB_URL."ges/posts");