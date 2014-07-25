<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_GET["q"])){

	$input = $_GET["q"];
	
	
	if(strlen($input)>0){
		require_once(ENGINE_PATH."class/topic.class.php");
		$topicClass=new Topic();
		
		$res = $topicClass->getAutoCompleteAll($input);
		if($res["result"]){
			$results=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					if($value["name_ts"]!="" && !preg_match('/'.$input.'/i', $value["name_topic"]) && preg_match('/'.$input.'/i', $value["name_ts"])){
					$results[]=array("id"=>$value["id_topic"],"name"=>$value["name_topic"]." (".$value["name_ts"].") - <i>".$topicClass->nameSingular($value["type_topic"])."</i>");
					}else{
					$results[]=array("id"=>$value["id_topic"],"name"=>$value["name_topic"]." - <i>".$topicClass->nameSingular($value["type_topic"])."</i>");
					}
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