<?php
require_once('../../../../engine/starter/config.php');

if(USER_TYPE==9){
	
	if(isset($_POST["syn"]) && isset($_GET["id"])){
		
		$name=trim($_POST["syn"]);
		$id=(int)$_GET["id"];
		if(strlen($name)>=2 || $id==0){
		
			require_once(ENGINE_PATH.'class/topic.class.php');
			$topicClass=new Topic();
			
			$resTop=$topicClass->getById($id);			
			
			if($resTop["result"]){

				$topicClass->addSynonym($name, $id);
			
				header("Location:".WEB_URL."ges/topic/".$resTop[0]["id_topic"]);
			
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