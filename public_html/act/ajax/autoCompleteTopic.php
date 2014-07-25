<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["q"]) && isset($_GET["type"])){

	$input = $_GET["q"];
	
	$type=$_GET["type"];
	
	if(strlen($input)>0 && strlen($type)==1){
		require_once(ENGINE_PATH."class/topic.class.php");
		$topicClass=new Topic();
		
		if(!$topicClass->pathSingular($type)){
			go404();
		}
		
		$res = $topicClass->getAutoComplete($input,$type);
		if($res["result"]){
			$results=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					if($value["name_ts"]!="" && !preg_match('/'.$input.'/i', $value["name_topic"]) && preg_match('/'.$input.'/i', $value["name_ts"])){
						$results[]=array("id"=>$value["id_topic"],"name"=>ucwords(strtolower($value["name_topic"]))." (".$value["name_ts"].")");	
					}else{
						$results[]=array("id"=>$value["id_topic"],"name"=>ucwords(strtolower($value["name_topic"])));
					}
					
				}
			}
			if(isset($_GET["cantFind"])){
				$results[]=array("id"=>0,"name"=>"Can't find a ".$topicClass->nameSingular($type)."&#63;");
			}
			echo json_encode($results);
		}else{
			//echo "empty";
			if(isset($_GET["cantFind"])){
				$results=array();
				$results[]=array("id"=>0,"name"=>"Can't find a ".$topicClass->nameSingular($type)."&#63;");
				echo json_encode($results);
			}
			
		}
	}else{
		go404();
	}

}else{
	go404();
}