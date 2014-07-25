<?php
onlyLogged();

$id=(int)$_GET["l3"];

if($id==0){
	go404();
}

require_once(ENGINE_PATH.'class/timeline.class.php');
$timelineClass=new Timeline();

$res=$timelineClass->getById($id);

if(!$res["result"]){
	go404();
}

if($res[0]["id_profile_tm"]!=USER_ID || $res[0]["file_tm"]==""){
	go404();
}

$path=ENGINE_PATH."uploads/".$res[0]["file_tm"];

$savename = (basename($path));

header("Pragmaes: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-type: application/force-download");
header("Content-Transfer-Encoding: Binary");
header("Content-length: " . filesize($path));
header("Content-disposition: attachment; filename=\"$savename\"");

readfile("$path");