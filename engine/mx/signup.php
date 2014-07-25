<?php
if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==1){

	if(!isset($resProfile)){
		if(!isset($profileClass)){
			require_once(ENGINE_PATH.'class/profile.class.php');
			$profileClass=new Profile();
		}
		$resProfile=$profileClass->getById(USER_ID);
	}
	$jsfunctions.="mixpanel.track('New Registration');";
	$jsfunctions.="mixpanel.register({'Username':'".$resProfile[0]["username_profile"]."','Account Type':'".$resProfile[0]["type_profile"]."','Creation Date':'".$resProfile[0]["created_profile"]."'});";
	$jsfunctions.="mixpanel.people.set({'Username':'".$resProfile[0]["username_profile"]."','Account Type':'".$resProfile[0]["type_profile"]."','Creation Date':'".$resProfile[0]["created_profile"]."'});";
	$jsfunctions.="mixpanel.name_tag('".$resProfile[0]["username_profile"]."');";
	$jsfunctions.="mixpanel.alias('".$resProfile[0]["id_profile"]."');";
	
	$_SESSION["mx_signup"]=2;
	$_SESSION["mx_name_tag"]=1;
	$justSignedUp=1;	
}
?>