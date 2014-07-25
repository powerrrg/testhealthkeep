<?php
require_once('local.php');

if($config["branch"]=="dev"){
	ini_set('display_errors','1');
	error_reporting(E_ALL | E_STRICT);
	define("LOGS", "ON");
	define("DEBUG", "ON");
}else{
	ini_set('display_errors','0');
	error_reporting(0);
	define("LOGS", "ON");
	define("DEBUG", "OFF");
}

require_once(ENGINE_PATH.'class/config.class.php');

function go404($nameFile="",$function="",$query="",$message="")
{
    if(isset($nameFile) && $nameFile != "")
    {
    	require_once(ENGINE_PATH."class/error.class.php");
        $myError = new Error();
        $myError->writeFileGo404($nameFile,$function,$query,$message);
    }
	header("HTTP/1.0 404 Not Found");
    echo "<h1>Page not found</h1>";
    die();
}

if(!isset($iamcron)){
	session_start();
}

/*
RESOLVE O PROBLEMA DO MAGIC_QUOTES nas " e ' meter \
*/
if(get_magic_quotes_gpc() == true)
{
        //foreach(array("_POST","_GET","_COOKIE","_SESSION","_REQUEST") as $var)
        foreach(array("_POST","_GET","_COOKIE","_REQUEST") as $var)
        {		
        	if(isset($GLOBALS[$var])){
                $GLOBALS[$var] = array_map("stripslashes",$GLOBALS[$var]);
            }
        }
}

$onload="";
$jsfunctions="";

$timelineType=array("med"=>"Medication","sym"=>"Symptom","dis"=>"Condition","pro"=>"Procedure","res"=>"Test Results","doc"=>"Doctor Visit","fel"=>"Health Status");
if (!defined("MOBILE_REQUEST")) {
    require_once(ENGINE_PATH."class/user.class.php");
    $userClass=new User();

    $resUser=$userClass->isLoggedUser();
}


function onlyLogged(){
	if(USER_ID==0){
		$goTo=$_SERVER["REQUEST_URI"];
	    if($goTo=="" || $goTo=="/"){
		    $goTo="";
	    }else{
		    $goTo="?go=".ltrim($goTo,"/");
	    }
	    header("Location:".WEB_URL."login.php".$goTo);
	    exit;
	}
}
