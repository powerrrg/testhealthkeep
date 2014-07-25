<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["id"])){
	$id=(int)$_POST["id"];
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	$resTopic=$topicClass->getById($id);
			
	if($resTopic["result"]){
	
		$res=$topicClass->follow($id);
		
		if($res){
			echo "<b>delete</b><a id=\"topic_".$resTopic[0]["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"])."/".$resTopic[0]["url_topic"]."\">".$resTopic[0]["name_topic"]."</a>";
		}else{
			//echo "error 1";
			echo "repeat";
		}
		
	}else{
		echo "error";
	}
	
}else{
	go404();
}