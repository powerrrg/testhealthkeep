<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["iFeel"])){
	$iFeel=(int)$_POST["iFeel"];
	if($iFeel>0 && $iFeel<11){
		
		require_once(ENGINE_PATH.'class/timeline.class.php');
		$timelineClass=new Timeline();
		$resIfeel=$timelineClass->changeIfeel($iFeel);
	}else{
		go404();
	}

}else{
	go404();
}
