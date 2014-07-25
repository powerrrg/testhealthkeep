<?php

onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();
$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}
if($resProfile[0]["type_profile"]==1){
	$totalNumSteps=6;
}else if($resProfile[0]["type_profile"]==2){
	$totalNumSteps=5;
}else{
	$totalNumSteps=0;
}

$step=(int)$_GET["l2"];

if($step<1 || $step>$totalNumSteps){
	go404();
}

$active="myProfile";

if($resProfile[0]["type_profile"]==1){
	require_once(ENGINE_PATH."html/step/user/$step.php");
}else if($resProfile[0]["type_profile"]==2){
	require_once(ENGINE_PATH."html/step/doctor/$step.php");
}else{
	go404();
}