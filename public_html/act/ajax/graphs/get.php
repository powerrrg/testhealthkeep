<?php
require_once('../../../../engine/starter/config.php');

if(isset($_GET["id"]) && isset($_GET["o1"]) && isset($_GET["time"])){

	require_once(ENGINE_PATH."html/inc/common/typeArray.php");
	
	$meaArray=typeArray('measurement');
	
	$id=(int)$_GET["id"];
	$o1=$_GET["o1"];
	$time=(int)$_GET["time"];
	
	$pieces=explode("_", $o1);
	
	$values=array();
	if(isset($pieces[1])){
		$cols=array("Date");
		foreach($pieces as $value){
			if(!isset($meaArray["type"][$value])){
				go404();	
			}
			$cols[]=$meaArray["type"][$value]["name"];
			$values[$value]=array(0);
		}
	}else{
		$cols=array("Date",$meaArray["type"][$o1]["name"]);
		if(!isset($meaArray["type"][$o1])){
			go404();	
		}
		$values[$o1]=array();
	}
	
	
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resProfile=$profileClass->getById($id);
	
	if(!$resProfile["result"]){
		go404();
	}

	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	$res=$timelineClass->getMeasurements($o1,$id,$time);
	$dataArray=array();
	$dataArray[]=$cols;
	
	if($res["result"]){

		foreach($res as $key=>$value){
			if(is_int($key)){
				$newarray=array($value["date_tm"]);
				foreach($values as $valKey=>$valValue){
					if($valKey==$value["measurement_tm"]){
						$newarray[]=(float)$value["frequency_tm"];
						$values[$valKey][]=(float)$value["frequency_tm"];
					}else{
						$newarray[]=end($values[$valKey]);
					}
				}
				$dataArray[]=$newarray;
			}
		}
	}
	
	
	echo json_encode($dataArray);
	
}else{
	go404();
}