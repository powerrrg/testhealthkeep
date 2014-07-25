<?php

if(!isset($_POST["month"]) || !isset($_POST["day"]) || !isset($_POST["year"])
	 || !isset($_POST["country"]) || !isset($_POST["zip"])
	  || !isset($_POST["weight"])  || !isset($_POST["feets"])  || !isset($_POST["inches"])
	   || !isset($_POST["job"])){
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

$weight=$_POST["weight"];

if(!is_numeric($weight) || $weight<7 || $weight>1500){
	$weight=0;
}

$feets=(int)$_POST["feets"];

if($feets<1 || $feets>10){
	$feets=0;
}

$inches=(int)$_POST["inches"];

if(!is_numeric($inches) || $inches<1 || $inches>100){
	$inches=0;
}

$job=$_POST["job"];

if(strlen($job)>100){
	$job="";
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$profileClass->step1($dob,$country,$zip,$weight,$feets,$inches,$job);
