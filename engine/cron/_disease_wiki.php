<?php
$iamcron=1;
require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/disease.class.php');
$diseaseClass=new Disease();
$res=$diseaseClass->getWikiNotTried();

if($res["result"]){

$id=$res[0]["id_disease"];
$definition="";
$source="";

$def=wikidefinition($res[0]["name_disease"]);
if($def){
	$definition=$def[1];
	$source=$def[2];	
}

$diseaseClass->updateDefinition($id,$definition,$source);

}