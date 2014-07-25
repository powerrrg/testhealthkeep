<?php
require_once('../../../engine/starter/config.php');

if(isset($_POST["q"]) && $_GET["miles"]){

	$miles=$_GET["miles"];
	$input = $_POST["q"];
	
	
	if(strlen($input)>0){
		require_once(ENGINE_PATH."class/doctor.class.php");
		$doctorClass=new Doctor();
		
		$res = $doctorClass->getAutoCompleteCcode($input,$miles);
		if($res["result"]){
			$results=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					$results[]=array("name"=>$value["zip"],"city"=>$value["city"]);
					
				}
			}
			
			header('Content-Type: application/json');
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