<?php
require_once('../../../../engine/starter/config.php');

if(USER_TYPE==9){
	
	if(isset($_GET["id"])){
		
		$id=(int)$_GET["id"];
		if($id>0){
		
			require_once(ENGINE_PATH.'class/topic.class.php');
			$topicClass=new Topic();	
			
			$resSyn=$topicClass->getSynById($id);
			
			if($resSyn["result"]){
			
				$res=$topicClass->getById($resSyn[0]["id_topic_ts"]);
				$topicClass->deleteSynonym($resSyn[0]["id_ts"]);
				header("Location:".WEB_URL."ges/topic/".$res[0]["id_topic"]);
			}else{
				go404();
			}
			
		}else{
			echo "Name needs to have more than 2 characters";
		}
		
	}else{
		go404();	
	}
	
}else{
	go404();
}