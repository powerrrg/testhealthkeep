<?php
require_once('../engine/starter/config.php');

if(isset($_GET["f"]) && isset($_GET["o"]) && isset($_GET["i"]) && isset($_GET["b"])){
	$_SESSION["prank"]=array();
	$what=$_GET["f"];
	$id=(int)$_GET["i"];
	if($id==0){
		go404();
	}
	$_SESSION["prank"][$what][$id]=$_GET["o"];
	$url=WEB_URL.urldecode($_GET["b"]);
	header("Location: $url");
	exit;
}else{
	go404();
}