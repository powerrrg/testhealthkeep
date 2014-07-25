<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["q"])){

	$input = $_GET["q"];
	
	
	if(strlen($input)>0){
		require_once(ENGINE_PATH."class/location.class.php");
		$locationClass=new Location();
		
		$res = $locationClass->getAutoCompleteZip($input);
		if($res["result"]){
			$results=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					$results[]=array("id"=>$value["zip"],"name"=>$value["zip"].", ".$value["city"].", ".$value["state"]);
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