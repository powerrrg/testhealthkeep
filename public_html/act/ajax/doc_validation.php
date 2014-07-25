<?php
require_once('../../../engine/starter/config.php');

if(isset($_POST["npi"])){

	$npi=trim($_POST["npi"]);
	
	if(strlen($npi)>=10){
		
		require_once(ENGINE_PATH.'class/doctor.class.php');
		$doctorClass=new Doctor();
		
		$res = $doctorClass->doctorValidate($npi);
		if($res["result"]){
			$show="<b>".$res[0]["first_name_doctor"];
			if($res[0]["middle_name_doctor"]!=""){
				$show.=" ".$res[0]["middle_name_doctor"];
			}
			$show.=" ".$res[0]["last_name_doctor"]."</b> from ".$res[0]["city_doctor"]." - ".$res[0]["state_doctor"];
			echo $show;
		}else{
			echo "nop";
		}
	}else{
		go404();
	}

}else{
	go404();
}