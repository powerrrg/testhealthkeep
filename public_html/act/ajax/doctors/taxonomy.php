<?php
require_once('../../../../engine/starter/config.php');


if(isset($_POST["p"]) && isset($_POST["t"])){

	require_once(ENGINE_PATH."html/inc/common/usStates.php");

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/doctor.class.php');
	$doctorClass=new Doctor();
		
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$res=$doctorClass->getTaxonomyByCode($_POST["t"]);
		
	if(!$res["result"]){
		go404();
	}

	
	$resDoc=$profileClass->getAllDoctorsWithTaxonomy($_POST["t"],$pageNum);
	
	if($resDoc["result"]){
		require_once(ENGINE_PATH."html/doctors/list.php");
	}
}else{
	go404();
}