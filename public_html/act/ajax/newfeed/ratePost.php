<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["id"]) || !isset($_POST["rate"])){
	go404();
}

$id=(int)$_POST["id"];

if($id==0){
	go404();
}

$rate=(int)$_POST["rate"];

if($rate==0 || $rate>5){
	go404();
}

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$res=$postClass->ratePost($id,$rate);

if($res){
	$res=$postClass->getById($id);
	
	if($res["result"]){
		echo $res[0]["rating_count_post"];
	}else{
		echo "error";
	}
	
}else{
	echo "error";
}