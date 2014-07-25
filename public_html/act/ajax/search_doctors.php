<?php
require_once('../../../engine/starter/config.php');

if(isset($_POST["p"]) && isset($_POST["t"]) && isset($_POST["x"]) && isset($_POST["y"])){

	$pageNum = (int)$_POST["p"];
	
	if($pageNum<2){
		go404();
	}
	
	$q=$_POST["t"];
	
	$z=$_POST["x"];
	
	$y=urldecode($_POST["y"]);
	
	$j=$_POST["j"];
	$g=$_POST["g"];
	
	require_once(ENGINE_PATH.'class/doctor.class.php');
	$doctorClass=new Doctor();
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	require_once(ENGINE_PATH.'class/config.class.php');
	$configClass=new Config();
	
	$resDocs=$doctorClass->search($pageNum,$q,$z,$y,$j,$g);
	
	
	require_once(ENGINE_PATH."render/others/doctors_list_html.php");

}else{
	go404();
}