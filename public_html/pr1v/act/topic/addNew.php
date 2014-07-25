<?php
require_once('../../../../engine/starter/config.php');

if(USER_TYPE==9){
	
	if(isset($_POST["newName"]) && isset($_POST["type"])){
		
		$name=trim($_POST["newName"]);
		if(strlen($name)>2){
		
			require_once(ENGINE_PATH.'class/topic.class.php');
			$topicClass=new Topic();			
			
			if(!$topicClass->pathSingular($_POST["type"])){
				go404();
			}
			

			$topicClass->addNew($name, $_POST["type"]);
			
			$res=$topicClass->getLastByTopic($_POST["type"]);
			
			header("Location:".WEB_URL."ges/topic/".$res[0]["id_topic"]);
			
		}else{
			echo "Name needs to have more than 2 characters";
		}
		
	}else{
		go404();	
	}
	
}else{
	go404();
}