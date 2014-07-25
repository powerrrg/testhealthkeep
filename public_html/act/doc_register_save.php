<?php
require_once('../../engine/starter/config.php');

if(!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["password"]) || !isset($_POST["phone"]) || !isset($_POST["npi_reg"])){
	go404();
}

$name=trim($_POST["name"]);
$email=trim($_POST["email"]);
$password=$_POST["password"];
$phone=trim($_POST["phone"]);
$npi=(int)$_POST["npi_reg"];

if($npi==0){
	go404();
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();
$resDoc=$profileClass->getByNPI($npi);

if(!$resDoc["result"]){
	echo "Something really strange happened. Please try again or contact us!";
	exit;
}
		
$res=$userClass->addNewDoc($name,$email,$password,$phone,$npi);

if(!$res["result"]){
	if(isset($res["error"])){
		echo $res["error"];
	}else{
		echo "Something really strange happened. Please try again or contact us!";
	}
}else{
	header("Location:".WEB_URL.$resDoc[0]["username_profile"]);
	exit;
}
		

