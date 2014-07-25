<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["id"])){
	$id=$_POST["id"];
	
	if($id==''){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();	
	$resTopic=$topicClass->getById($id);

			
	if($resTopic["result"] && $resTopic[0]["type_topic"]=='g'){
	
		$res=$topicClass->follow($resTopic[0]["id_topic"]);
		
		if($res){
		echo '<div id="goal_'.$resTopic[0]["id_topic"].'" class="iMGoal"><b>delete</b><a href="'.WEB_URL.$topicClass->pathSingular('g')."/".$resTopic[0]["url_topic"].'" class="goalNameClass">'.$resTopic[0]["name_topic"].'</a>';
		echo '</div>';

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