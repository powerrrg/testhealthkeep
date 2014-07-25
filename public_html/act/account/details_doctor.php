<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["name"]) || !isset($_POST["address"]) || !isset($_POST["address2"])
	 || !isset($_POST["zip"]) || !isset($_POST["phone"]) || !isset($_POST["fax"]) || !isset($_POST["taxonomy"])){
	go404();
}

$name=$_POST["name"];

if(strlen($name)<6){
	go404();
}
$address=$_POST["address"];
$address2=$_POST["address2"];

require_once(ENGINE_PATH.'class/location.class.php');
$locationClass=new Location();

$zip=(string)$_POST["zip"];

$resZip=$locationClass->getZipByZip($zip);

if(!$resZip["result"]){
	$zip=null;
}

$phone=$_POST["phone"];
$fax=$_POST["fax"];
$taxonomy=$_POST["taxonomy"];

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"] || $resProfile[0]["type_profile"]=!2){
	go404();
}

require_once(ENGINE_PATH.'class/doctor.class.php');
$doctorClass=new Doctor();

$res=$profileClass->updateDocsDetails($name,$zip);

$res=$doctorClass->updateDetails($resProfile[0]["npi_profile"],$address,$address2,$phone,$fax,$taxonomy);

header("Location:".WEB_URL.USER_NAME);