<?php
require_once('../../../../engine/starter/config.php');

onlyLogged();

if(isset($_POST["id"])){
	$id=(int)$_POST["id"];
	
	if($id==0){
		go404();
	}
	
	require_once(ENGINE_PATH.'class/profile.class.php');
	$profileClass=new Profile();
	
	$resProfile=$profileClass->getById($id);
			
	if($resProfile["result"] && $resProfile[0]["type_profile"]==2){
	
		
		$res=$profileClass->follow($id);
		
		if($res){
			$valueDoc=$resProfile[0];
			?>
			<li id="doctor_<?php echo $valueDoc["id_profile"]; ?>" class="clearfix">
			<?php
			if($valueDoc["image_profile"]!=""){
				$docImagePath=WEB_URL."img/profile/tb/".$valueDoc["image_profile"];
				$docImageAlt=$valueDoc["name_profile"];
			}else{
				$docImagePath=WEB_URL."inc/img/v2/profile/doctor_no_avatar.png";
				$docImageAlt="Doctor with no avatar image";
			}
			?>
			<b>delete</b>
			<a href="<?php echo WEB_URL.$valueDoc["username_profile"]; ?>"><img src="<?php echo $docImagePath; ?>" alt="<?php echo $docImageAlt; ?>" /></a>
			<a href="<?php echo WEB_URL.$valueDoc["username_profile"]; ?>" class="colorRed doctorNameClass"><?php echo $valueDoc["name_profile"]; ?></a>
			</li>
			<?php
		}else{
			//echo "error 1";
			echo "repeat";
		}
		
	}else{
		echo "error";
	}
	
}else{
	go404();
}