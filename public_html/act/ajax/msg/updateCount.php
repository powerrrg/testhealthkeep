<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$profileClass->getById(USER_ID);

if($res["result"]){
	echo $res[0]["msgs_profile"];
}else{
	echo "0";
}