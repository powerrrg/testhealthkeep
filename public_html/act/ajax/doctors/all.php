<?php
require_once('../../../../engine/starter/config.php');


if(isset($_POST["p"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resDoc=$profileClass->getAllDoctors($pageNum);	
	
	if($resDoc["result"]){
		require_once(ENGINE_PATH."html/doctors/list.php");
	}
}else{
	go404();
}