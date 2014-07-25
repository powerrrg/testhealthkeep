<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["month"]) || !isset($_POST["day"]) || !isset($_POST["year"])
	 || !isset($_POST["country"]) || !isset($_POST["zip"]) || !isset($_POST["job"]) || !isset($_POST["gender"]) || !isset($_POST["miniBio"])){
	go404();
}

$month=(int)$_POST["month"];
$day=(int)$_POST["day"];
$year=(int)$_POST["year"];

$currentYear=date('Y');
$dob="0000-00-00";

if($month>0 && $month<13 && $day>0 && $day<32 && $year>0 && $year<=$currentYear){
	if(checkdate($month, $day, $year)){
		$dob=$year."-".$month."-".$day;
	}
}else if($month>0 && $month<13 && $year>0 && $year<=$currentYear){
	$dob=$year."-".$month."-00";
}else if($year>0 && $year<=$currentYear){
	$dob=$year."-00-00";
}

$country=$_POST["country"];

require_once(ENGINE_PATH.'class/location.class.php');
$locationClass=new Location();

$resCountry=$locationClass->getCountryByIso($country);

if(!$resCountry["result"]){
	$country=null;
}

$zip=(string)$_POST["zip"];

$resZip=$locationClass->getZipByZip($zip);

if(!$resZip["result"]){
	$zip=null;
}

$job=$_POST["job"];

if(strlen($job)>100){
	$job="";
}

$gender=$_POST["gender"];

if($gender!="m" && $gender!="f"){
	go404();
}

$bio=$_POST["miniBio"];

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$profileClass->updateDetails($dob,$country,$zip,$job,$gender,$bio);

header("Location:".WEB_URL.USER_NAME);