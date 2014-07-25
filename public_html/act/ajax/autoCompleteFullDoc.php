<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["q"])){

	$input = $_GET["q"];
	
	if(strlen($input)>0){
		require_once(ENGINE_PATH."class/profile.class.php");
		$profileClass=new Profile();
		
		require_once(ENGINE_PATH."html/inc/common/usStates.php");
		
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
					
					if(isset($usStates[$state])){
						$state=$usStates[$state];
					}

					if($value["image_profile"]!=""){
						$docImagePath=WEB_URL."img/profile/tb/".$value["image_profile"];
					}else{
						$docImagePath=WEB_URL."inc/img/v2/profile/doctor_no_avatar.png";
					}
					$results[]=array("id"=>$value["id_profile"],"name"=>$value["name_profile"],"state"=>$state,"image"=>$docImagePath);
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