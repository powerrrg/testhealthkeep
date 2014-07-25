<?php
require_once('../../engine/starter/config.php');

require_once(ENGINE_PATH."html/doctors/reg_validation.php");

if($type!="fac"){
	go404();
}
		
$res=$userClass->addNewPro($name,$email,$password,$phone,$type);

if(!$res["result"]){
	if(isset($res["error"])){
		echo $res["error"];
	}else{
		echo "Something really strange happened. Please try again or contact us!";
	}
}else{
	//header("Location:".WEB_URL."feed");
	//mxprohack is a way to track the registration so it doesnt go and check everytime on the feed for session mxsignup
	header("Location:".WEB_URL."mxprohack.php");
	exit;
}
		

