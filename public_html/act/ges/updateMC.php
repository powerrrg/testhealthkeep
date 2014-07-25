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

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

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
		
			$descr="";
			if($rvalue["title_post"]!=""){
				$title=$rvalue["title_post"];
				$descr=substr(strip_tags($rvalue["text_post"]), 0,300)."...";
			}else{
				$title=substr(strip_tags($rvalue["text_post"]), 0,150)."...";
				$descr=substr(strip_tags($rvalue["text_post"]), 151,450)."...";
			}
			
			if($rvalue["image_post"]!=""){
				$image=WEB_URL."img/post/tb/".$rvalue["image_post"];
			}else{
				$image="";
			}
			
			$oint=$rkey+1;
			$myarr["STORY".$oint]=$title;
			$myarr["STORY".$oint."URL"]="post/".$rvalue["id_post"];
			$myarr["STORY".$oint."TB"]=$image;
			$myarr["STORY".$oint."DESC"]=$descr;
			$topics="";
			/*$resPostTopics=$postClass->getPostTopics($rvalue["id_post"]);
			if($resPostTopics["result"]){
				$topics.="<div>";
				foreach($resPostTopics as $paKey=>$paValue){
					if(is_int($paKey)){
						if($paKey<3){
						$topics.='<a href="'.WEB_URL.$topicClass->pathSingular($paValue["type_topic"])."/".$paValue["url_topic"].'" style="color:#666 !important;font-size: 18px !important;line-height: 24px !important;margin-right: 20px;">';
						$topics.=$paValue["name_topic"].'</a>';
						}
					}
				}
				$topics.="</div>";
			}*/
			
			/*$myarrNew["NEWSTORY".$oint]='
			<div style="margin:30px 0 !important;padding-bottom: 10px !important; border-bottom: 1px solid #DDD;">
				<h1 style="font-size: 30px !important;line-height: 35px !important;"><a href="'.WEB_URL."post/".$rvalue["id_post"].'" style="color:#5F91CC !important;font-weight: bold !important;text-decoration: none !important;">'.$title.'</a></h1>
				'.$topics.'
				<p style="font-size: 18px !important;line-height: 24px !important;color:#666 !important;">'.$descr.'</p>
			</div>
			';*/
			
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


		
		