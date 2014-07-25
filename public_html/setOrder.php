<?php
require_once('../engine/starter/config.php');

if(isset($_GET["f"]) && isset($_GET["o"]) && isset($_GET["b"])){
	$_SESSION["prank"]=array();
	$what=$_GET["f"];
	if(isset($_GET["topic"])){
		$idTopic=(int)$_GET["topic"];
		if($idTopic>0){
			$_SESSION["prank"][$idTopic]=$_GET["o"];	
		}
	}else{
		$_SESSION["prank"][$what]=$_GET["o"];
	}
	
	$url=WEB_URL.urldecode($_GET["b"]);
	header("Location: $url");
	exit;
}else{
	go404();
}