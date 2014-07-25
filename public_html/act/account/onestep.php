<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(!isset($_POST["username"])|| !isset($_POST["password"]) || !isset($_POST["gender"]) || !isset($_POST["condition"]) || !isset($_POST["meds"]) || !isset($_POST["symptoms"]) || !isset($_POST["docs"]) || !isset($_POST["country"])){
	go404();
}

$username=trim($_POST["username"]);
$password=$_POST["password"];

$gender=$_POST["gender"];

if($gender!="m" && $gender!="f"){
	go404();
}

$theimage=1;

$dob="0000-00-00";

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

foreach($_POST as $key=>$value){
	$pieces=explode("goal_", $key);
	if(isset($pieces[1])) {	
		$resGoal=$topicClass->findGoal($pieces[1]);
		if(isset($resGoal["result"])){
			$topicClass->follow($resGoal[0]["id_topic"]);
		}
	}
}

$country=$_POST["country"];

require_once(ENGINE_PATH.'class/location.class.php');
$locationClass=new Location();

$resCountry=$locationClass->getCountryByIso($country);

if(!$resCountry["result"]){
	$country=null;
}

$zip=null;

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}

$userClass->updatePassword(USER_ID,$password);

if($username!=$resProfile[0]["username_profile"]){
	$res=$profileClass->getByUsername($username);
	if($res["result"]){
		go404();
	}
	$res=$profileClass->updateUsername($username);
}


if($theimage<=12){
	if($gender=="f"){
		$curGender="woman";
	}else{
		$curGender="man";
	}
	$res=$profileClass->copyAvatar($curGender.$theimage.".jpg");
}else{
	$res=$profileClass->changeAvatar("avatarFile");
}

$res=$profileClass->updateDetails($dob,$country,$zip,$resProfile[0]["job_profile"],$gender);

$res=$profileClass->saveStep('99');

$topic=$_POST["condition"];

if($topic!=""){
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$today=date('Y-m-d');

	foreach($pieces as $key=>$value){
		$res=(int)$value;	
		if($res>0){
		
			$resTopic=$topicClass->getById($res);
			
			if($resTopic["result"]){
			
				if($resTopic[0]["type_topic"]=="d"){
					$res=$timelineClass->add("dis",$res,1,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="m"){
					$res=$timelineClass->add("med",$res,1,$today,"0000-00-00",1,0,0);
				}else if($resTopic[0]["type_topic"]=="p"){
					$res=$timelineClass->add("pro",$res,0,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="s"){
					$res=$timelineClass->addSymptoms(array($res),1,$today);
				}
				
			}
			
		}
	}
	
}

$topic=$_POST["meds"];

if($topic!=""){
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$today=date('Y-m-d');

	foreach($pieces as $key=>$value){
		$res=(int)$value;	
		if($res>0){
		
			$resTopic=$topicClass->getById($res);
			
			if($resTopic["result"]){
			
				if($resTopic[0]["type_topic"]=="d"){
					$res=$timelineClass->add("dis",$res,1,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="m"){
					$res=$timelineClass->add("med",$res,1,$today,"0000-00-00",1,0,0);
				}else if($resTopic[0]["type_topic"]=="p"){
					$res=$timelineClass->add("pro",$res,0,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="s"){
					$res=$timelineClass->addSymptoms(array($res),1,$today);
				}
				
			}
			
		}
	}
	
}

$topic=$_POST["symptoms"];

if($topic!=""){
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/topic.class.php');
	$topicClass=new Topic();
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	$today=date('Y-m-d');

	foreach($pieces as $key=>$value){
		$res=(int)$value;	
		if($res>0){
		
			$resTopic=$topicClass->getById($res);
			
			if($resTopic["result"]){
			
				if($resTopic[0]["type_topic"]=="d"){
					$res=$timelineClass->add("dis",$res,1,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="m"){
					$res=$timelineClass->add("med",$res,1,$today,"0000-00-00",1,0,0);
				}else if($resTopic[0]["type_topic"]=="p"){
					$res=$timelineClass->add("pro",$res,0,$today,"0000-00-00");
				}else if($resTopic[0]["type_topic"]=="s"){
					$res=$timelineClass->addSymptoms(array($res),1,$today);
				}
				
			}
			
		}
	}
	
}

$topic=$_POST["docs"];

if($topic!=""){
	
	$pieces=explode(",", $topic);
	
	require_once(ENGINE_PATH.'class/timeline.class.php');
	$timelineClass=new Timeline();
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$today=date('Y-m-d');

	foreach($pieces as $key=>$value){
		$res=(int)$value;	

		if($res>0){
		
			$resTopic=$profileClass->getById($res);

			if($resTopic["result"] && $resTopic[0]["type_profile"]=="2"){

				$timelineClass->addDoctorVisit($res,$today);
				
			}
			
		}
	}
	
}

if($_POST["condition"]=="" && $_POST["meds"]=="" && $_POST["symptoms"]=="" && $_POST["docs"]==""){
	header("Location:".WEB_URL.$username);	
}else{
	header("Location:".WEB_URL."feed");	
}
