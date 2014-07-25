<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();
$res=$topicClass->getWikiNotTried("m");

if($res["result"]){

$id=$res[0]["id_topic"];
$definition="";
$source="";

$def=wikidefinition($res[0]["name_topic"]);
if($def){
	$definition=$def[1];
	$source=$def[2];	
}

$topicClass->updateDefinition($id,$definition,$source);

}