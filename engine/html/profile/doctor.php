<?php

$designV1=1;

require_once(ENGINE_PATH.'class/doctor.class.php');
$doctorClass=new Doctor();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/location.class.php');
$locationClass=new Location();

require_once(ENGINE_PATH."html/inc/common/usStates.php");

$resDoctor=$doctorClass->getByNPI($resProfile[0]["npi_profile"]);

if(!$resDoctor["result"]){
	go404();
}

if($resProfile[0]["name_profile"]!=""){
	$name=$resProfile[0]["name_profile"];
	}else{
	//this is not needed any more
	$name="";
	if($resDoctor[0]["name_prefix_doctor"]!=""){
		$name.=ucfirst(strtolower($resDoctor[0]["name_prefix_doctor"]))." ";
	}
	$name.=ucfirst(strtolower($resDoctor[0]["first_name_doctor"]));
	if($resDoctor[0]["middle_name_doctor"]!=""){
		$name.=" ".ucfirst(strtolower($resDoctor[0]["middle_name_doctor"]));
	}
	$name.=" ".ucfirst(strtolower($resDoctor[0]["last_name_doctor"]));
	if($resDoctor[0]["name_suffix_doctor"]!=""){
		$name.=" ".$resDoctor[0]["name_suffix_doctor"];
	}
	$name.=", ".$resDoctor[0]["credential_doctor"];
}

if($resProfile[0]["zip_profile"]!=''){
	require_once(ENGINE_PATH.'class/location.class.php');
	$locationClass=new Location();
	$resZip=$locationClass->getZipByZip($resProfile[0]["zip_profile"]);
	
}
$descrLocation="";
if(isset($resZip) && $resZip["result"]){
	$descrLocation.=", ".$usStates[$resZip[0]["state"]].", ".$resZip[0]["city"];
}else{
	if(isset($usStates[$resDoctor[0]["state_doctor"]])){
		$descrLocation.=", ".$usStates[$resDoctor[0]["state_doctor"]];
	}
	if($resDoctor[0]["city_doctor"]!=""){
		$descrLocation.=", ";
		$descrLocation.=ucwords(strtolower($resDoctor[0]["city_doctor"]));
	}
}

$pageTitle=$name.", ".$resDoctor[0]["name_taxonomy"].$descrLocation." - HealthKeep";
$pageDescr="Profile page for ".str_replace("\"", "'", $name).". Keep in contact with your doctors and find information from other patients and communities.";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div id="doctorProfile" class="iBoard">
			<?php
			require_once(ENGINE_PATH."html/profile/emailWarning.php");
			?>
			<div class="iHeading clearfix">
				<h1 class="profileHeadingName"><img src="<?php echo WEB_URL; ?>inc/img/v1/profile/caduceus.png" alt="caduceus" width="35" height="32" /><?php echo $name; ?></h1>
				<?php
				if(USER_ID!=0){
					if(USER_ID!=$resProfile[0]["id_profile"]){	
					$resIfollow=$profileClass->doIFollow($resProfile[0]["id_profile"]);
					if($resIfollow["result"]){
					?>
					<div class="profileHeadingBtns">
						<button class="btn btn-blue" id="followBtn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?no&id=".$resProfile[0]["id_profile"]; ?>'">Following</button>
					</div>
					<?php	
					$onload.="$('#followBtn').hover(function(){
						$(this).text('unfollow');	
					},function(){
						$(this).text('following');
					});";
					
					}else{
					?>
					<div class="profileHeadingBtns">
						<button class="btn btn-red" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'">Follow</button>
					</div>
					<?php
					}
					?>
					
					<?php
					}
				}else{
					?>
					<div class="profileHeadingBtns btn-group">
						<a class="btn btn-blue dropdown-toggle" data-toggle="dropdown">Follow</a>
						<ul class="dropdown-menu pull-right">
							<li><a href="<?php echo WEB_URL; ?>login.php?go=<?php echo $resProfile[0]["username_profile"]; ?>">Login</a></li>
							<li><a href="<?php echo WEB_URL; ?>">Register</a></li>
						</ul>
					</div>
					<?php
				}
				?>
				
			</div>
			<div class="iBoard2 clearfix">
				<div id="profileMain1">
					<div id="profileImage">
						<?php
						if($resProfile[0]["image_profile"]==""){
							$imagePath=WEB_URL."inc/img/empty-avatar.png";
							$imageAlt="No Image Avatar";
						}else{
							$imagePath=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
							$imageAlt=$resProfile[0]["username_profile"];
						}
						if($resProfile[0]["id_profile"]==USER_ID){
						?>
						<img src="<?php echo $imagePath; ?>" id="profileImageTag" alt="<?php echo $imageAlt; ?>" />
						<form action="<?php echo WEB_URL; ?>act/profile/uploadAvatar.php" enctype="multipart/form-data" id="avatarImg" method="post">
							<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload">
							<?php
							if($resProfile[0]["image_profile"]!=""){
							?>
							<a style="display:inline-block;margin-left:15px;" onclick="confirmDelete();" title="delete" class="btn btn-red">x</a>
							<?php
							$jsfunctions.="function confirmDelete(){
								if(confirm('Are you sure you want to delete your profile image?')){
									location.href='".WEB_URL."act/profile/delAvatar.php';
								}
							}";
							}
							?>
							  <span class="btn btn-file btn-blue" style="display:inline-block"><span class="fileupload-new">Change</span>
							  <input type="file" name="avatarFile" id="avatarFile" /></span>
							  
							</div>
							
						</form>
						<?php
						$needFupload=1;
						$onload.="$('#avatarFile').bind('change', function() {
							$('.fileupload-new').hide();
							$('#subImg').hide();
							if(this.files[0]!=undefined && this.files[0].size>2097152){
								alert('The Image cannot have more than 2 MB in size');
								$('.fileupload').fileupload('clear');
								$('.fileupload-new').show();
						  	}else if(this.files[0]!=undefined){
						  		var val = $(this).val();
						  		var val = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
						  		if(val!='gif' && val!='jpg' && val!='jpeg' && val!='png'){
							  		alert('That is not a valid image file!');
						  			$('.fileupload').fileupload('clear');	
						  			$('.fileupload-new').show();		            
						  		}else{
						  			$('#avatarImg').submit();
						  		}
						  	}
						});";
						}else{
						?>
							<img src="<?php echo $imagePath; ?>" id="profileImageTag" alt="<?php echo $imageAlt; ?>" />
						<?php
						}
						if($resDoctor[0]["claimed_doctor"]==1){
						?>
						<img src="<?php echo WEB_URL; ?>inc/img/v1/profile/claimed.png" id="claimedBadge" alt="Claimed profile" />
						<?php
						}
						?>
					</div>
					<?php
					$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
					$totalFers=0;
					if($res["result"]){
						$totalFers=$res[0]["total"];
					}
					?>
					<div class="profileFollowerBox">
						<div class="padding5_10">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/profile/followers.png" alt="Followers" />
						<?php
						if($totalFers>0){
							echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/followers/\" class=\"colorGray\">";	
						}
						?>
						<span class="bigBlue marginX5"><?php echo $totalFers; ?></span><span class="followersGray">followers</span>
						<?php
						if($totalFers>0){
							echo "</a>";	
						}
						?>
						</div>
					</div>
				</div>
				<div id="profileMain2">
					<p class="profileDetails allcaps">
					<?php echo $resDoctor[0]["name_taxonomy"]; ?>
					</p>
					<p class="profileDetails">
					<?php
					$mapByAddress=$resDoctor[0]["address_1_doctor"];
					echo ucwords(strtolower($resDoctor[0]["address_1_doctor"]));
					if($resDoctor[0]["address_2_doctor"]!=""){
					echo " ".ucwords(strtolower($resDoctor[0]["address_2_doctor"]));
					$mapByAddress.=" ".$resDoctor[0]["address_2_doctor"];
					}
					?>
					</p>
					<p class="profileDetails">
					<?php
					
					if(isset($resZip) && $resZip["result"]){
						echo $resZip[0]["city"].", ";
						echo $usStates[$resZip[0]["state"]]." ";
						$mapByAddress.=" ".$resZip[0]["city"]." ".$usStates[$resZip[0]["state"]];
						echo $resZip[0]["zip"];
						$mapByAddress.=" ".$resZip[0]["zip"]." USA";
					}
					
					if(!isset($resZip) || !$resZip["result"]){
						echo $resDoctor[0]["city_doctor"].", ".$resDoctor[0]["state_doctor"]." ";
					
						if($resDoctor[0]["city_doctor"]!="APO" && $resDoctor[0]["city_doctor"]!="FPO" 
						&& $resDoctor[0]["state_doctor"]!="AA" && $resDoctor[0]["state_doctor"]!="AE" && $resDoctor[0]["state_doctor"]!="AP"){
						$mapByAddress.=" ".$resDoctor[0]["city_doctor"]." ".$resDoctor[0]["state_doctor"];
						}
						echo substr($resDoctor[0]["postal_code_doctor"], 0,5);
						$mapByAddress.=" ".$resDoctor[0]["postal_code_doctor"]." USA";
					}
					
					?>
					</p>
					<div class="marginTop15">
					<?php
					if($resDoctor[0]["telephone_doctor"]!=""){
					$phoneNumber=$configClass->formatPhoneNumber($resDoctor[0]["telephone_doctor"]);
					echo "<a class=\"btn btn-red phoneButton\" href=\"tel:".$resDoctor[0]["telephone_doctor"]."\">";
					echo '<img src="'.WEB_URL.'inc/img/v1/profile/phone.png" alt="Phone" />';
					echo $phoneNumber."</a>";
					}
					if($resDoctor[0]["fax_doctor"]!=""){
					echo "<span class=\"faxGray\">Fax: ".$configClass->formatPhoneNumber($resDoctor[0]["fax_doctor"])."</span>";
					}
					?>
					</div>
				</div>
				<div id="profileMain3">
					<?php
					$mapByAddressUrl=urlencode($mapByAddress);
					$googleMapUrl="https://maps.google.com/maps?q=".$mapByAddressUrl."&hl=en-US";
					
					$mapImg='https://maps.googleapis.com/maps/api/staticmap?center='.$mapByAddressUrl.'&markers='.$mapByAddressUrl.'&zoom=14&size=245x245&maptype=roadmap&sensor=false&key=AIzaSyB-zuAVEeBKpfBT45yZl7mEktPV-rq6-Mc';
					echo '<a href="'.$googleMapUrl.'" target="_blank" class="smallMapImage">';
					echo '<img src="'.$mapImg.'" alt="map image of '.$mapByAddress.'" /></a>';
					
					$mapImg='https://maps.googleapis.com/maps/api/staticmap?center='.$mapByAddressUrl.'&markers='.$mapByAddressUrl.'&zoom=14&size=700x700&maptype=roadmap&sensor=false&key=AIzaSyB-zuAVEeBKpfBT45yZl7mEktPV-rq6-Mc';
					echo '<a href="'.$googleMapUrl.'" target="_blank" class="bigMapImage">';
					echo '<img src="'.$mapImg.'" alt="map image of '.$mapByAddress.'" /></a>';
					?>
				</div>
			</div>
			<?php
			if(USER_ID==0 && $resDoctor[0]["claimed_doctor"]==0){
			?>
			<div class="iBoxHeadingColoured">
				<div class="iBoxHeadingColouredHeading iBoxHeading_HKblue clearfix">
					<h3>Claim this profile</h3>
				</div>
				<div class="claimText">
					<span class="colorLighterBlue"><?php echo $name; ?></span> has not claimed <?php if($resDoctor[0]["gender_doctor"]=='f'){ echo "her"; }else{ echo "his"; } ?> profile.<br />	
					If you are <span class="colorLighterBlue"><?php echo $name; ?></span> register and claim this profile.
				</div>
				<div class="center paddingBottom20">
					
					<a href="<?php echo WEB_URL; ?>" class="btn btn-blue marginTop30">Register</a>
					<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/blueArrow.png" class="marginLeft20" alt="blue arrow" />
				</div>
			</div>
			<?php
			}else if($resDoctor[0]["claimed_doctor"]==1){
			?>
			<div class="iBoxHeadingColoured">
				<div class="iBoxHeadingColouredHeading iBoxHeading_blue clearfix">
					<h3><?php echo $name; ?> is Following</h3>
				</div>
				<div class="clearfix">
					<?php
					$countFollowingUsers=$profileClass->countFollowing($resProfile[0]["id_profile"]);
					$totalUsers=0;
					if($countFollowingUsers["result"]){
						$totalUsers=$countFollowingUsers[0]["total"];
					}
					?>
					<div class="profileIsFollowing">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/topic/chat.png" alt="chat" />
						<?php
						if($totalUsers>0){
							echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/following/\" class=\"colorBlue\">";	
						}
						?>
						<span class="colorBlue"><?php echo $totalUsers; ?></span>Users
						<?php
						if($totalUsers>0){
							echo "</a>";	
						}
						?>
					</div>
					<?php
					$countFollowing=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"d");
					$totalTopic=0;
					if($countFollowing["result"]){
						$totalTopic=$countFollowing[0]["total"];
					}
					?>
					<div class="profileIsFollowing">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/topic/heart.png" alt="heart" /><span class="colorConditions"><?php echo $totalTopic; ?></span>Conditions
					</div>
					<?php
					$countFollowing=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"m");
					$totalTopic=0;
					if($countFollowing["result"]){
						$totalTopic=$countFollowing[0]["total"];
					}
					?>
					<div class="profileIsFollowing">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/topic/pill.png" alt="pill" /><span class="colorMedications"><?php echo $totalTopic; ?></span>Medications
					</div>
					<?php
					$countFollowing=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"p");
					$totalTopic=0;
					if($countFollowing["result"]){
						$totalTopic=$countFollowing[0]["total"];
					}
					?>
					<div class="profileIsFollowing">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/topic/inbed.png" alt="In bed" /><span class="colorProcedures"><?php echo $totalTopic; ?></span>Procedures
					</div>
					<?php
					$countFollowing=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"s");
					$totalTopic=0;
					if($countFollowing["result"]){
						$totalTopic=$countFollowing[0]["total"];
					}
					?>
					<div class="profileIsFollowing">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/topic/temperature.png" alt="Temperature" /><span class="colorSymptoms"><?php echo $totalTopic; ?></span>Symptoms
					</div>
				</div>
			</div>
			<div class="iBoxHeadingColoured">
				<div class="iBoxHeadingColouredHeading iBoxHeading_HKblue clearfix">
					<h3>Specialities / Areas of practice & Hospital affiliations</h3>
				</div>
				<div class="profileSpecialities">
				<?php echo $resDoctor[0]["name_taxonomy"]; ?>
				</div>
			</div>
			<?php
			}
			if(USER_ID!=0){
			if(!isset($needAutoGrow)){
				$needAutoGrow=1;
				$onload.="$('textarea').autogrow();";
			}
			?>
			<form class="iPost" id="postAbout" method="post" action="<?php echo WEB_URL; ?>act/post/postAbout.php?id=<?php echo $resProfile[0]["id_profile"]; ?>">
				<textarea placeholder="Share your health experience with <?php echo $name; ?>" class="textArea100" name="txtPost" id="txtPost"></textarea>
				<div class="iPostBtns">
					<input type="submit" disabled class="btn btn-red submitBtn iPostSubmitBtn" value="share" />
				</div>
			</form>
			<?php
				$onload.="$('#txtPost').keyup(function(){
					if($('#txtPost').val().length>5){
						$('.iPostSubmitBtn').prop('disabled', false);
					}else{
						$('.iPostSubmitBtn').prop('disabled', true);
					}
				});";
				if(!$jsTopFormIsSet){
					$onload.="$('input[placeholder],textarea[placeholder]').placeholder();";
					$jsTopFormIsSet=1;
				}
				$onload.="
				$('#postAbout').submit(function(){
					if($('#txtPost').val().length<5){
						alert('You need to type a message to be able to post!');
						$('#txtPost').focus();
						return false;
					}else{
						return true;
					}
				});
				";
			}
			$resPosts=$postClass->getPostsFromAndAboutUser($resProfile[0]["id_profile"]);
			if($resPosts["result"]){
			$backPath=$resProfile[0]["username_profile"];
			?>
			<div id="profilePostHolder">
			<?php require_once(ENGINE_PATH."html/list/posts.php"); ?>
			</div>
			<?php
			$ajaxUrl=WEB_URL."act/ajax/profile/posts.php";
			$onload.="endlessScroll('$ajaxUrl',$('#profilePostHolder'),".$resProfile[0]['id_profile'].");";
			require_once(ENGINE_PATH."html/inc/endless.php");
			}
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');