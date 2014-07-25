<?php
foreach($resDocs as $key=>$value){
	if(is_int($key)){
	if($value["image_profile"]==""){
		$imgurl=WEB_URL. "inc/img/empty-avatar.png";
	}else{
		$imgurl=WEB_URL. "img/profile/tb/".$value["image_profile"];
	}
	?>
	<div class="iHoldDoctor clearfix <?php if (($key+1) % 2 == 0){ echo 'inDoctorListOdd'; }else{ echo 'inDoctorListEven'; } ?>">
		<a href="<?php echo WEB_URL.$value["username_profile"]; ?>"><img src="<?php echo $imgurl; ?>" alt="<?php echo $configClass->name($value, false); ?>" style="width:50px;height:50px;" /></a>
		<button class="btn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$value["id_profile"]; ?>'">follow</button>
		<?php
		if($value["phone_profile"]!="" || $value["telephone_doctor"]!=""){
		if($value["phone_profile"]!=""){
			$phoneDoc=$value["phone_profile"];
		}else{
			$phoneDoc=$value["telephone_doctor"];
		}
		
		?>
		<span class="btn btn-blue"><a href="tel:<?php echo $value["telephone_doctor"]; ?>" style="color:#5F91CC;"><img src="<?php echo WEB_URL; ?>inc/img/phone.png" alt="Telephone" /><?php echo $phoneDoc; ?></a></span>
		<?php
		}
		?>
		<br />
		<h4><a href="<?php echo WEB_URL.$value["username_profile"]; ?>"><?php echo $configClass->name($value, false); ?></a></h4>
		<h5><?php echo $value["name_taxonomy"]; ?></h5>
		<h6 style="color:#D4D4D4;font-weight:normal;"><?php echo $value["city_doctor"]." - ".$value["state_doctor"]; ?>
		<?php
		if($value["zip_doctor"]>0){ echo " - ".$value["zip_doctor"]; }
		?>
		</h6>
		
	</div>
	<?php	
	}
}
