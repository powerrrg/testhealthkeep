<?php
require_once('../engine/starter/config.php');

if(isset($_GET["q"])){
	header("Location:".WEB_URL.'q/'.urlencode($_GET["q"]));
}else if(isset($_GET["id"])){
	
	$id=(int)$_GET["id"];
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/search.class.php');
	$searchClass=new Search();
	
	$res = $searchClass->getById($id);
	
	if(!$res["result"]){
		go404();
	}
	
	if($res[0]["type_s"]=="user"){
		$link=$res[0]["user_link_s"];
	}else{
		$link=$res[0]["link_s"];
	}
	
	header('HTTP/1.1 301 Moved Permanently');
	header("Location:".$link);
}else{
	go404();
}