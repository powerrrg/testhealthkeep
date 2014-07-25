<?php

foreach($res as $key=>$value){
	if(is_int($key)){
		if ($key % 2 == 0) {
			$classIs="evenMeet";
			echo "<div style=\"clear:both;\"></div>";
		}else{
			$classIs="oddMeet";
		}
		if($value["image_profile"]==""){
			$img=WEB_URL."inc/img/empty-avatar.png";
		}else{
			$img=WEB_URL."img/profile/tb/".$value["image_profile"];
		}
		
	?>
		<div class="<?php echo $classIs; ?> eachMeet clearfix">
			<div class="clearfix">
			<a href="<?php echo WEB_URL.$value["username_profile"]; ?>">
			<img src="<?php echo $img; ?>" alt="<?php echo $value["username_profile"]; ?>" style="width:50px;height:50px;" /></a>
			<?php
			$resIfollow=$profileClass->doIFollow($value["id_profile"]);
			if($resIfollow["result"]){
			?>
			<button id="followBtn" class="btn meetBtn btn-red" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?no&id=".$value["id_profile"]; ?>'">Unfollow</button>						
			<?php
			}else{
			?>
			<button id="followBtn" class="btn meetBtn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$value["id_profile"]; ?>'">Follow</button>

			<?php	
			}
			?>
			</div>
			<div class="eachMeetBadges">
				<?php
				$sharingBadge=$value["sharing_profile"];
				$supportiveBadge=$value["supportive_profile"];
				$helpfulBadge=$value["helpful_profile"];
				$karmaBadge=$value["karma_profile"];
				if($sharingBadge>999){
					$sharingBadge=floor($sharingBadge/1000)."k";
				}
				if($supportiveBadge>999){
					$supportiveBadge=floor($supportiveBadge/1000)."k";
				}
				if($helpfulBadge>999){
					$helpfulBadge=floor($helpfulBadge/1000)."k";
				}
				if($karmaBadge>999){
					$karmaBadge=floor($karmaBadge/1000)."k";
				}
				?>
				<span class="iBagde iBadgeSharing">
					<?php echo $sharingBadge; ?>
				</span>
				<span class="iBagde iBadgeSupportive">
					<?php echo $supportiveBadge; ?>
				</span>
				<span class="iBagde iBadgeHelpful">
					<?php echo $helpfulBadge; ?>
				</span>
				<span class="iBagde iBadgeKarma">
					<?php echo $karmaBadge; ?>
				</span>
			
			</div>
			<div class="eachMeetText">
			<h6><a href="<?php echo WEB_URL.$value["username_profile"]; ?>"><?php echo $value["username_profile"]; ?></a></h6>
			<?php
			if(isset($gender)){
				unset($gender);
			}
			if(isset($myage)){
				unset($myage);
			}
			if($value["gender_profile"]=="m" || $value["gender_profile"]=="f"){
				if($value["gender_profile"]=="m"){
					$gender="Male";
				}else{
					$gender="Female";
				}
			}
			if($value["dob_profile"]!="0000-00-00"){
				
				require_once(ENGINE_PATH."common/date.php");
				$myage= age($value["dob_profile"])." Years Old"; 
			}
			if(isset($gender) || isset($myage)){
			?>
			<p class="eachMeetDetail"><?php 
			if(isset($gender)){
				echo $gender;
			}
			if(isset($myage)){
				if(isset($gender)){
					echo ', ';
				}
				echo $myage; 
			}
			
			?></p>
			<?php
			}
			$locationText="";
			if($value["country_profile"]!="US"){
				$resCountry=$locationClass->getCountryByIso($value["country_profile"]);
				if($resCountry["result"]){
					 $locationText=$resCountry[0]["short_name"];
				}
			}else{
				$resZip=$locationClass->getZipByZip($value["zip_profile"]);
				if($resZip["result"]){
					require_once(ENGINE_PATH."html/inc/common/usStates.php");
					if(isset($usStates[$resZip[0]["state"]])){
						$locationText=$usStates[$resZip[0]["state"]];
					}
				}
			}
			if($locationText!=""){
				echo '<p class="eachMeetLocation">'.$locationText.'</p>';
			}
			?>
			</div>
			
		</div>
	<?php
	}
}
?>