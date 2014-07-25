<?php
require_once('../../../engine/starter/config.php');

if(isset($_POST["username"])){

	$username = preg_replace("/[^a-zA-Z0-9\_\-]/", "", $_POST["username"]);
	
	if(strlen($username)>4){
		require_once(ENGINE_PATH."class/profile.class.php");
		$profileClass=new Profile();
		if(isset($_POST["notme"])){
			$res = $profileClass->getByUsername($username,true);
		}else{
			$res = $profileClass->getByUsername($username);
		}
		if($res["result"]){
			echo "exists";
		}else{
			echo "ok";
		}
	}else{
		go404();
	}

}else{
	go404();
}