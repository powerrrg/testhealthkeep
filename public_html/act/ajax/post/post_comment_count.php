<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["id"])){
	go404();
}

$id=(int)$_POST["id"];

if($id==0){
	go404();
}


require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$res=$postClass->getCommentById($id);

if($res["result"]){
	echo $res[0]["thumb_up_pc"];
}else{
	echo "error";
}
