<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["p"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	$resProfile=$profileClass->getById(USER_ID);

	$resTimeline=$timelineClass->getProfileTimeline($resProfile[0]["id_profile"],$pageNum);
		
	$jsTopFormIsSet=false;

	require_once(ENGINE_PATH."html/timeline/timelineHTML.php");
	?>
	<script>
	$('.holdTooltip').tooltip();
	</script>
	<?php

}else{
	go404();
}