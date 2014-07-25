<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["q"])){

	$input = $_GET["q"];
	
	if(strlen($input)>0){
		require_once(ENGINE_PATH."class/profile.class.php");
		$profileClass=new Profile();
		
		$res = $profileClass->getAutoCompleteDoctor($input);
		if($res["result"]){
			$results=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					if($value["state"]!=""){
						$state=$value["state"];
					}else{
						$state=$value["state_doctor"];
					}
					if($state!=""){
						$state=" - ".$state;
					}
					$results[]=array("id"=>$value["id_profile"],"name"=>$value["name_profile"].$state);
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