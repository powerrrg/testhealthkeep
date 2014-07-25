<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

if(!isset($_POST["email"]) || !isset($_POST["uname"]) || !isset($_POST["id"]) || !isset($_POST["name"]) || !isset($_POST["type"])){
	go404();	
}

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$uname=$_POST["uname"];
$name=$_POST["name"];
$email=$_POST["email"];
$id=(int)$_POST["id"];
$type=(int)$_POST["type"];

if($id==0){
	echo "erro";
	exit;
}

$resProfile=$profileClass->getById($id);

if(!$resProfile["result"]){
	echo "erro";
	exit;
}
$myarr=array("UID"=>$resProfile[0]["id_profile"],"UNAME"=>$resProfile[0]["username_profile"],"RNAME"=>$configClass->name($resProfile),"UTYPE"=>$resProfile[0]["type_profile"],);

$resPosts = $postClass->getMCPosts($id);
if($resPosts["result"]){
	foreach($resPosts as $rkey=>$rvalue){
		
		if(is_int($rkey)){

			if($rvalue["title_post"]!=""){
				$title=$rvalue["title_post"];
			}else{
				$title=substr(strip_tags($rvalue["text_post"]), 0,150)."...";
			}
			
			if($rvalue["image_post"]!=""){
				$image=WEB_URL."img/post/tb/".$rvalue["image_post"];
			}else{
				$image="";
			}
			
			$oint=$rkey+1;
			$myarr["STORY".$oint]=$title;
			$myarr["STORY".$oint."URL"]=WEB_URL."post/".$rvalue["id_post"];
			$myarr["STORY".$oint."TB"]=$image;
		}
	}
}else{
	echo "errro grande";
	exit;

}

include(ENGINE_PATH."class/mc/mcAPI.php");
include(ENGINE_PATH."class/mc/MCAPI.class.php");

$api = new MCAPI(mcAPI);

// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
// Click the "settings" link for the list - the Unique Id is at the bottom of that page. 
$list_id = mcNewsLetter;



if($api->listUpdateMember($list_id, $email, $myarr) === true) {
			// It worked!
			echo $email." - ok!";
		}else{
			// An error ocurred, return error message	
			echo $api->errorMessage;
		}


		
		