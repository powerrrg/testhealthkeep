<?php
require_once('../../../../engine/starter/config.php');

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

if(isset($_POST["query"])){
	require_once(ENGINE_PATH.'class/search.class.php');
	$searchClass=new Search();
	
	$q=urldecode($_POST["query"]);
	$res=$searchClass->search($q);
	
	$array=array();
	$array[]=array("id"=>0,"name"=>"Search $q");
	if($res["result"]){
		foreach($res as $key=>$value){
			if(is_int($key)){
				if($value["type_s"]=="topic"){
					$name=$value["title_s"];
				}else if($value["type_s"]=="user"){
					$name=substr($value["user_name_s"],0,25);
				}else if($value["type_s"]=="post"){
					if($value["title_s"]!=""){
						$name=substr($value["title_s"],0,25);
					}else{
						$name=substr(strip_tags($value["snippet_s"]),0,25);
					}
				}else{
					$name=substr(strip_tags($value["snippet_s"]),0,25);
				}
				$array[]=array("id"=>$value["id_s"],"name"=>$name);
			}
		}
	}
	echo json_encode($array);
}else{
	go404();
}
?>