<?php
require_once('../../../../engine/starter/config.php');

if((isset($_POST["p"]) && isset($_POST["name"]) && isset($_POST["state"]) && isset($_POST["city"]) && isset($_POST["taxo"])) || (isset($_POST["p"]) && isset($_POST["t"]))){

	require_once(ENGINE_PATH."html/inc/common/usStates.php");

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<1){
		go404();
	}
	
	if(isset($_POST["t"])){
		$pieces=explode('*_*',$_POST["t"]);
		if(!isset($pieces[3])){
			go404();
		}
		$sname=$pieces[0];
		$sstate=$pieces[1];
		$scity=$pieces[2];
		$staxo=$pieces[3];
	}else{
	
		$sname=$_POST["name"];
		$sstate=$_POST["state"];
		$scity=$_POST["city"];
		$staxo=$_POST["taxo"];
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resDoc=$profileClass->getAllDoctorsSearch($sname,$sstate,$scity,$staxo,$pageNum);
	
	if($resDoc["result"]){
		require_once(ENGINE_PATH."html/doctors/list.php");
	}
}else{
	go404();
}