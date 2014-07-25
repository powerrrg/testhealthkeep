<?php
require_once('../../../../engine/starter/config.php');

if(USER_TYPE==9){
	
	if(isset($_GET["id"])){
		
		$id=(int)$_GET["id"];
		if($id>0){
		
			require_once(ENGINE_PATH.'class/topic.class.php');
			$topicClass=new Topic();			
			
			$res=$topicClass->getById($id);
			
			if($res["result"]){
				$topicClass->delete($id);
				header("Location:".WEB_URL."ges/topics/");
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