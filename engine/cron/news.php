<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/external.class.php');
$externalClass=new External();
require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();
$res = $externalClass->getLinkToUpdate();

if($res["result"]){
	
	$link=$res[0]["link_es"];
	$type=$res[0]["type_es"];
	$id_profile=$res[0]["id_profile_es"];
	
	
	require_once(ENGINE_PATH."class/post.class.php");
	$postClass=new Post();
	
	$res=get_web_page($link);
	if($res["http_code"]=="200"){
		$originContent=$res["content"];
		$xml=new SimpleXMLElement($originContent);
		include(ENGINE_PATH."cron/news_types/embedly_magic.php");
		
		require_once(ENGINE_PATH.'cron/news_types/'.$type.'.php');

		
	}
	
	
}