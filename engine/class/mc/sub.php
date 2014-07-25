<?php
require_once(ENGINE_PATH."class/mc/mcAPI.php");
require_once(ENGINE_PATH."class/mc/MCAPI.class.php");

function storeAddress($email,$uname,$rname){
	
	// Validation
	if(!$_GET['email']){ return "No email address provided"; } 

	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_GET['email'])) {
		return "Email address is invalid"; 
	}

	require_once('MCAPI.class.php');
	// grab an API Key from http://admin.mailchimp.com/account/api/
	$api = new MCAPI(mcAPI);
	
	// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
	// Click the "settings" link for the list - the Unique Id is at the bottom of that page. 
	$list_id = mcNewsLetter;

	if($api->listSubscribe($list_id, $email, array("UNAME"=>"$uname","RNAME"=>"$rname"),'html',false) === true) {
		// It worked!	
		return array("result"=>'ok',"text"=>'Success! Check your email to confirm sign up.');
	}else{
		// An error ocurred, return error message	
		return array("result"=>'error',"text"=>$api->errorMessage);
	}
	
}

storeAddress($email,$uname,$rname);