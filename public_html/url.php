<?php
require_once('../engine/starter/config.php');

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

if(isset($_GET["l3"])){

	if($_GET["l1"]=="doctors"){
		
		if($_GET["l2"]=="taxonomy"){
			require_once(ENGINE_PATH."render/others/doctors_list.php");
		}else{
			go404();
		}
		
	}else if($_GET["l1"]=="feed"){
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]=="q"){
		require_once(ENGINE_PATH."render/feed/search.php");
	}else if($_GET["l1"]=="timeline"){
		if($_GET["l2"]=="add"){
			if($_GET["l3"]=="medication"){
				require_once(ENGINE_PATH."html/timeline/addMedication.php");
			}else if($_GET["l3"]=="symptom"){
				require_once(ENGINE_PATH."html/timeline/addSymptoms.php");
			}else if($_GET["l3"]=="condition"){
				require_once(ENGINE_PATH."html/timeline/addDisease.php");
			}else if($_GET["l3"]=="procedure"){
				require_once(ENGINE_PATH."html/timeline/addProcedure.php");
			}else if($_GET["l3"]=="result"){
				require_once(ENGINE_PATH."html/timeline/addResult.php");
			}else if($_GET["l3"]=="docvisit"){
				require_once(ENGINE_PATH."html/timeline/addDocVisit.php");
			}else{
				go404();
			}
		}else if($_GET["l2"]=="file"){
			require_once(ENGINE_PATH."html/timeline/viewFile.php");
		}else if($_GET["l2"]=="new"){
			require_once(ENGINE_PATH."html/timeline/newFollow.php");
		}else{
			go404();	
		}
	}else if($_GET["l1"]=="measurement"){
		if($_GET["l2"]=="add"){
			if($_GET["l3"]=="weight"){
				require_once(ENGINE_PATH."html/measurement/weight.php");
			}else if($_GET["l3"]=="diet"){
				require_once(ENGINE_PATH."html/measurement/diet.php");
			}else if($_GET["l3"]=="bp"){
				require_once(ENGINE_PATH."html/measurement/bp.php");
			}else if($_GET["l3"]=="sugar"){
				require_once(ENGINE_PATH."html/measurement/sugar.php");
			}else if($_GET["l3"]=="exercise"){
				require_once(ENGINE_PATH."html/measurement/exercise.php");
			}else{
				go404();
			}
		}else{
			go404();
		}
	}else if($_GET["l1"]=="ges"){
		if($_GET["l2"]=="emailall"){
			if($_GET["l3"]=="send"){
				require_once(ENGINE_PATH."html/ges/emailAllSend.php");
			}else{
				go404();
			}
		}else if($_GET["l2"]=="updatesearch"){
			require_once(ENGINE_PATH."html/ges/updateSearch.php");
		}else if($_GET["l2"]=="topics"){
			require_once(ENGINE_PATH."html/ges/topics.php");
		}else if($_GET["l2"]=="topic"){
			require_once(ENGINE_PATH."html/ges/topics.php");
		}else if($_GET["l2"]=="blog"){
			if($_GET["l3"]=="add"){
				require_once(ENGINE_PATH."html/ges/blog_add.php");
			}else{
				go404();
			}
		}else if($_GET["l2"]=="post"){
			require_once(ENGINE_PATH."html/ges/post.php");
		}else if($_GET["l2"]=="addpost"){
			if($_GET["l3"]=="save"){
				require_once(ENGINE_PATH."html/ges/addpost_save.php");
			}else if($_GET["l3"]=="confirm"){
				require_once(ENGINE_PATH."html/ges/addpost_confirm.php");
			}else{
				exit;
			}
		}else{
			go404();
		}
	}else if($_GET["l1"]==$topicClass->pathSingular('d') && $_GET["l3"]=="followers"){
		$topicType="d";
		require_once(ENGINE_PATH."render/feed/followers.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('m') && $_GET["l3"]=="followers"){
		$topicType="m";
		require_once(ENGINE_PATH."render/feed/followers.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('p') && $_GET["l3"]=="followers"){
		$topicType="p";
		require_once(ENGINE_PATH."render/feed/followers.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('s') && $_GET["l3"]=="followers"){
		$topicType="s";
		require_once(ENGINE_PATH."render/feed/followers.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('g') && $_GET["l3"]=="followers"){
		$topicType="g";
		require_once(ENGINE_PATH."render/feed/followers.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('d') || $_GET["l1"]==$topicClass->pathSingular('m') || $_GET["l1"]==$topicClass->pathSingular('p') || $_GET["l1"]==$topicClass->pathSingular('s') || $_GET["l1"]=="disease" || $_GET["l1"]=="surgery" ){
			 
			 	header ('HTTP/1.1 301 Moved Permanently');
			 	header("Location".WEB_URL."post/".$_GET["l3"] );
			 	
	}else if($_GET["l1"]=="blog"){
	
		onlyLogged();

		if(USER_TYPE!=9){
			go404();	
		}
		if($_GET["l2"]=="delete"){
			require_once(ENGINE_PATH.'class/blog.class.php');
			$blogClass=new Blog();
			$resPost=$blogClass->getById($_GET["l3"]);
	
			if(!$resPost["result"]){	
				go404();
			}
			$blogClass->delById($_GET["l3"]);
			
			header("Location:".WEB_URL."ges/blog");
			exit;
			
		}else{
			go404();
		}
	}else{
		go404();
	}

}else if(isset($_GET["l2"])){
	if($_GET["l1"]=="timeline"){
		if($_GET["l2"]=="add"){
			require_once(ENGINE_PATH."html/timeline/add2timeline.php");
		}else{
			go404();	
		}
	}else if($_GET["l1"]=="step"){
		require_once(ENGINE_PATH."html/step/go.php");
	}else if($_GET["l1"]=="post"){
		require_once(ENGINE_PATH."render/feed/post_detail.php");
	}else if($_GET["l1"]=="feed"){
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]=="meet"){
		if($_GET["l2"]=="active"){
			require_once(ENGINE_PATH."render/others/meet.php");
		}else if($_GET["l2"]=="recent"){
			require_once(ENGINE_PATH."render/others/meet.php");
		}else{
			go404();
		}
	}else if($_GET["l1"]=="account"){
		if($_GET["l2"]=="details"){
			require_once(ENGINE_PATH."html/account/details.php");
		}else if($_GET["l2"]=="health"){
			require_once(ENGINE_PATH."render/account/healthdetails.php");
		}else if($_GET["l2"]=="notifications"){
			require_once(ENGINE_PATH."render/account/notifications.php");
		}else{
			go404();
		}
	}else if($_GET["l1"]=="blog"){
		require_once(ENGINE_PATH.'class/blog.class.php');
		$blogClass=new Blog();
		$resPost=$blogClass->getByURL($_GET["l2"]);

		if(!$resPost["result"]){
			go404();
		}
		require_once(ENGINE_PATH."render/blog/post.php");
	}else if($_GET["l1"]=="graphs"){
		require_once(ENGINE_PATH."html/graphs/index.php");
	}else if($_GET["l1"]=="q"){
		require_once(ENGINE_PATH."render/feed/search.php");
	}else if($_GET["l1"]=="ges"){
		if($_GET["l2"]=="emailall"){
			require_once(ENGINE_PATH."html/ges/emailAll.php");
		}else if($_GET["l2"]=="updatesearch"){
			require_once(ENGINE_PATH."html/ges/updateSearch.php");
		}else if($_GET["l2"]=="mcCustom"){
			require_once(ENGINE_PATH."html/ges/mcCustom.php");
		}else if($_GET["l2"]=="mcUpdate"){
			require_once(ENGINE_PATH."html/ges/mcUpdate.php");
		}else if($_GET["l2"]=="posts"){
			require_once(ENGINE_PATH."html/ges/posts.php");
		}else if($_GET["l2"]=="stats"){
			require_once(ENGINE_PATH."html/ges/stats.php");
		}else if($_GET["l2"]=="topics"){
			require_once(ENGINE_PATH."html/ges/topics.php");
		}else if($_GET["l2"]=="badges"){
			require_once(ENGINE_PATH."html/ges/badges.php");
		}else if($_GET["l2"]=="top5"){
			require_once(ENGINE_PATH."html/ges/top5.php");
		}else if($_GET["l2"]=="top5news"){
			require_once(ENGINE_PATH."html/ges/top5news.php");
		}else if($_GET["l2"]=="addpost"){
			require_once(ENGINE_PATH."html/ges/addpost.php");
		}else if($_GET["l2"]=="addSimplepost"){
			require_once(ENGINE_PATH."html/ges/addSimplepost.php");
		}else if($_GET["l2"]=="blog"){
			require_once(ENGINE_PATH."html/ges/blog.php");
		}else if($_GET["l2"]=="blist"){
			require_once(ENGINE_PATH."html/ges/blist.php");
		}else{
			go404();
		}
	}else if($_GET["l2"]=="followers" || $_GET["l2"]=="following"){
		require_once(ENGINE_PATH."render/profile/followersfollowing.php");
	}else if($_GET["l1"]=="doctors"){
		require_once(ENGINE_PATH."render/others/doctors_list.php");
	}else if($_GET["l1"]=="doctor"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.WEB_URL.$_GET["l2"]);
	}else if($_GET["l1"]=="disease"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.WEB_URL.$topicClass->pathSingular('d')."/".$_GET["l2"]);
	}else if($_GET["l1"]==$topicClass->pathSingular('d')){
		$topicType="d";
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('m')){
		$topicType="m";
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]=="surgery"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.WEB_URL.$topicClass->pathSingular('p')."/".$_GET["l2"]);
	}else if($_GET["l1"]==$topicClass->pathSingular('p')){
		$topicType="p";
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('s')){
		$topicType="s";
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]==$topicClass->pathSingular('g')){
		$topicType="g";
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]==$topicClass->pathPlural('d')){
		$topicType="d";
		require_once(ENGINE_PATH."render/others/topic_list_letter.php");
	}else if($_GET["l1"]==$topicClass->pathPlural('m')){
		$topicType="m";
		require_once(ENGINE_PATH."render/others/topic_list_letter.php");
	}else if($_GET["l1"]==$topicClass->pathPlural('p')){
		$topicType="p";
		require_once(ENGINE_PATH."render/others/topic_list_letter.php");
	}else if($_GET["l1"]==$topicClass->pathPlural('s')){
		$topicType="s";
		require_once(ENGINE_PATH."render/others/topic_list_letter.php");
	}else if($_GET["l1"]=="track"){
		if($_GET["l2"]=="activate"){
			require_once(ENGINE_PATH."render/track/activate.php");
		}else if($_GET["l2"]=="deactivate"){
			require_once(ENGINE_PATH."render/track/deactivate.php");
		}else if($_GET["l2"]=="start"){
			require_once(ENGINE_PATH."render/track/start.php");
		}else{
			go404();
		}
	}else{
		go404();
	}
	
}else if(isset($_GET["l1"])){

	if($_GET["l1"]=="home"){
		header("Location:".WEB_URL."feed");
	}else if($_GET["l1"]=="about"){
		require_once(ENGINE_PATH."render/others/about.php");
	}else if($_GET["l1"]=="contact"){
		require_once(ENGINE_PATH."render/others/contact.php");
	}else if($_GET["l1"]=="timelime"){
		if(preg_match('/^\/timelime/', $_SERVER["REQUEST_URI"])){
		$res= preg_replace('/^\/timelime/', 'timeline', $_SERVER["REQUEST_URI"]);
		header("Location: ".WEB_URL.$res,TRUE,301); 
		}else{
			header("Location: ".WEB_URL."timeline",TRUE,301); 	
		}
		exit;
	}else if($_GET["l1"]=="timeline" || $_GET["l1"]=="timelime"){
		require_once(ENGINE_PATH."html/timeline/timeline.php");
	}else if($_GET["l1"]=="feed"){
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]=="meet"){
		require_once(ENGINE_PATH."render/others/meet.php");
	}else if($_GET["l1"]=="home2"){
		require_once(ENGINE_PATH."render/feed/feed.php");
	}else if($_GET["l1"]=="new"){
		require_once(ENGINE_PATH."html/list/new.php");
	}else if($_GET["l1"]=="track"){
		require_once(ENGINE_PATH."render/track/opt.php");
	}else if($_GET["l1"]=="doctors"){
		require_once(ENGINE_PATH."render/others/doctors_list.php");
	}else if($_GET["l1"]=="onestep"){
		require_once(ENGINE_PATH."html/profile/onestep.php");
	}else if($_GET["l1"]=="privacy"){
		require_once(ENGINE_PATH."html/profile/privacy.php");
	}else if($_GET["l1"]=="tos"){
		require_once(ENGINE_PATH."render/others/tos.php");
	}else if($_GET["l1"]=="pp"){
		require_once(ENGINE_PATH."render/others/ppolicy.php");
	}else if($_GET["l1"]=="ges"){
		require_once(ENGINE_PATH."html/ges/ges.php");
	}else if($_GET["l1"]=="msg"){
		require_once(ENGINE_PATH."render/others/msg.php");
	}else if($_GET["l1"]=="avatar"){
		require_once(ENGINE_PATH."render/profile/avatar.php");
	}else if($_GET["l1"]=="diseases"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.WEB_URL.$topicClass->pathPlural('d'));
	}else if($_GET["l1"]==$topicClass->pathPlural('d')){
		$topicType="d";
		require_once(ENGINE_PATH."render/others/topic_list.php");
	}else if($_GET["l1"]==$topicClass->pathPlural('m')){
		$topicType="m";
		require_once(ENGINE_PATH."render/others/topic_list.php");
	}else if($_GET["l1"]=="surgeries"){
		header ('HTTP/1.1 301 Moved Permanently');
		header ('Location: '.WEB_URL.$topicClass->pathPlural('p'));
	}else if($_GET["l1"]==$topicClass->pathPlural('p')){
		$topicType="p";
		require_once(ENGINE_PATH."render/others/topic_list.php");
	}else if($_GET["l1"]==$topicClass->pathPlural('s')){
		$topicType="s";
		require_once(ENGINE_PATH."render/others/topic_list.php");
	}else if($_GET["l1"]=="graphs"){
		require_once(ENGINE_PATH."html/graphs/index.php");
	}else{
		require_once(ENGINE_PATH."html/profile/profile.php");
	}

}else{
	go404();
}