<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["q"])){

	$input = $_GET["q"];
	
	
	if(strlen($input)>0){
		require_once(ENGINE_PATH."class/doctor.class.php");
		$doctorClass=new Doctor();
		
		$res = $doctorClass->getAutoCompleteTaxonomy($input);
		if($res["result"]){
			$results=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					$results[]=array("id"=>$value["code_taxonomy"],"name"=>$value["name_taxonomy"]." - ".$value["group_taxonomy"]);
				}
			}
			echo json_encode($results);
		}else{
			//echo "empty";
		}
	}else{
		go404();
	}

}else{
	go404();
}