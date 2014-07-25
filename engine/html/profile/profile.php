<?php
require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getByUsername(urlencode($_GET["l1"]));

if(!$resProfile["result"]){
	go404();
}


if(USER_ID==$resProfile[0]["id_profile"]){
	//top bar active
	$active="myProfile";
	$resUser=$userClass->getById($resProfile[0]["id_profile"]);
	
	if(!$resUser["result"]){
		go404();
	}
}

if($resProfile[0]["image_profile"]!=""){
	$ogImage=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
}

if($resProfile[0]["type_profile"]==2){
	//require_once(ENGINE_PATH."html/profile/doctor.php");
	require_once(ENGINE_PATH."render/profile/doctor.php");
}else if($resProfile[0]["type_profile"]==1){
	//require_once(ENGINE_PATH."html/profile/user.php");
	require_once(ENGINE_PATH."render/profile/user.php");
}else{
	//require_once(ENGINE_PATH."html/profile/others.php");
	require_once(ENGINE_PATH."render/profile/other.php");
}
