<?php
require_once('../../engine/starter/config.php');

onlyLogged();


if(isset($_POST["id_disease"]) && isset($_POST["year"])){
	
	$id_disease=(int)$_POST["id_disease"];
	$year=(int)$_POST["year"];
	
	if($year==0 || $id_disease==0){
		go404();
	}else{
		
		require_once(ENGINE_PATH."class/disease.class.php");
		$diseaseClass=new Disease();

		$res = $diseaseClass->addUdisease($id_disease,$year);
		
		header("Location:".WEB_URL."list/disease.php");
		
	}
	
}else{
	go404();
}