<?php
require_once('../../../../engine/starter/config.php');


if(isset($_POST["p"]) && isset($_POST["t"])){

	require_once(ENGINE_PATH."html/inc/common/usStates.php");

	$pageNum = (int)$_POST["p"];
	
	$pieces=explode('_',$_POST["t"]);
	
	if($pageNum<2){
		go404();
	}
	
	
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	if(isset($pieces[1])){
		$cityState=strtoupper($pieces[0]);
		if(!isset($usStates[$cityState])){
			go404();
		}
		$onlyCity=str_replace("-", " ", strtolower($pieces[1]));
		$resDoc=$profileClass->getAllDoctorsFromCity($cityState,$onlyCity,$pageNum);
	}else{
		$onlyState=strtoupper($_POST["t"]);
		if(!isset($usStates[$onlyState])){
			go404();
		}
		$resDoc=$profileClass->getAllDoctorsFromState($onlyState,$pageNum);
	}
	
	if($resDoc["result"]){
		require_once(ENGINE_PATH."html/doctors/list.php");
	}
}else{
	go404();
}