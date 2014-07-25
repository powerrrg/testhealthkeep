<?php
require_once('../../../engine/starter/config.php');

if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["gender"]) && isset($_POST["experience"]) && isset($_POST["username"])){

	$email = $_POST["email"];
	$username=trim($_POST["username"]);
	$password=$_POST["password"];
	if(strlen($password)<5){
		go404();
	}
	
	$gender=$_POST["gender"];
	if($gender!="m" && $gender!="f"){
		go404();
	}
	
	$experience=$_POST["experience"];
	if(strlen($experience)<5){
		go404();
	}
	
	if(filter_var( $email, FILTER_VALIDATE_EMAIL )){

		$res = $userClass->getByEmail($email);
		if($res["result"]){
			$res=$userClass->doLogin($email,$password);
		
			if($res["result"]){
				//renew mixpanel info
				$_SESSION["mx_name_tag"]=0;
				echo "loggedin";
			}else{
				if(isset($res["error"])){
				
					if($res["error"]=="email"){
						echo "error";
						exit;
					}else if($res["error"]=="password"){
						echo "invalidpassword";
						exit;
					}else{
						echo "error";
						exit;
					}
					
				}else{
					echo "error";
					exit;
				}
			}
		}else{
			$res=$userClass->addNew($username,$email,$password,$gender);
		
			if(!$res["result"]){
				if(isset($res["error"])){
					if(isset($res["emailDup"])){
						echo "error";
						exit;
					}else{
						echo "error";
						exit;
					}
				}else{
					echo "error";
					exit;
				}
			}else{
				echo "registered";	
			}
		}
		
	}else{
		go404();
	}

}else{
	go404();
}