<?php
require_once('../../../engine/starter/config.php');

if(isset($_POST["email"])){

	$email = $_POST["email"];
	
	if(filter_var( $email, FILTER_VALIDATE_EMAIL )){

		$res = $userClass->getByEmail($email);
		if($res["result"]){
			echo "exists";
		}else{
			echo "ok";
		}
	}else{
		go404();
	}

}else{
	go404();
}