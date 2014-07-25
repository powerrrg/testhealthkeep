<?php

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

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix" itemscope itemtype="http://schema.org/Person">
		
		<?php
		if(USER_ID==0){
		$token=sha1(microtime(true).mt_rand(10000,90000));
		$_SESSION["token"]=$token;
		?>
		<div id="feedCTA">
		<div id="feedSignUp">
			<h2>Share and learn with others like you</h2>
			<form id="homeRegister" method="post" class="clearfix" action="<?php echo WEB_URL; ?>act/registerNewDesign.php">
				<input type="email" id="hpSingleInput" name="email" placeholder="Enter your email adress" />
				<input type="hidden" name="username" value="user<?php echo time(); ?>" />
				<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
				<input type="hidden" name="gender" value="m" />
				<input type="text" name="hpot" class="hpot" value="" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<div class="clearfix">
					
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-red" value="Sign Up" />
				</div>
			</form>
		</div>
		<?php
		$onload.="$('#hpSingleInput').focus();";
	
		$onload.="$('.submitBtn').prop('disabled', false);
				$('input[placeholder]').placeholder();";
	
		$jsfunctions.="
		function testEmail(){
			if(isValidEmailAddress($('#hpSingleInput').val())){
				return true;
			}else{
				alert('Invalid email!');
				return false;
			}
		}";
		$onload.="
		$('#homeRegister').submit(function(){
			return testEmail();
		});
		";
		$_SESSION["mx_signup"]=1;
		$jsfunctions.="mixpanel.track('Profile Page V2 New Design');";
		echo "</div>";
		}else if($resProfile[0]["id_profile"]==USER_ID && $resUser[0]["confirmed_email_user"]==0){
		/*
		HIDE confirmation email notice. The email still goes but it doesn't matter if users cconfirms or not.
		?>
			<div class="alert alert-error" style="margin:30px 0 30px 0;">
				<strong>Notice! You have not confirmed your email.</strong><br />
				Please check your email and follow the instructions to confirm your email address.<br />
				If you don't see the email in your 'inbox', please look for it in the 'bulk', 'junk' or 'spam' folder.
			</div>
		<?php
		*/
		}
		?>
		<div id="doctorProfileTop" class="clearfix">
			<div class="clearfix">
				<h1 class="profileHeadingName"><img src="<?php echo WEB_URL; ?>inc/img/v1/profile/caduceus.png" alt="caduceus" width="35" height="32" /><span itemprop="name"><?php echo $name; ?></span></h1>
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
								<button class="btn btn-red" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'" style="cursor:pointer;">Follow</button>
							</div>
						<?php
						}
					}
				}
				?>
				
			</div>
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
					<img src="<?php echo $imagePath; ?>" itemprop="image" id="profileImageTag" alt="<?php echo $imageAlt; ?>" />
					<form action="<?php echo WEB_URL; ?>act/profile/uploadAvatar.php" enctype="multipart/form-data" id="avatarImg" method="post">
						<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload">
						  <span class="btn btn-file btn-blue" style="display:inline-block">
						  <span class="fileupload-new">Change</span>
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
						<img src="<?php echo $imagePath; ?>" itemprop="image" id="profileImageTag" alt="<?php echo $imageAlt; ?>" />
					<?php
					}
					echo "<div style=\"padding:10px 0 0;\">";
					$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
					$totalFers=0;
					if($res["result"]){
						$totalFers=$res[0]["total"];
					}
					if($totalFers>0){
						echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/followers/\" class=\"underlineAlternative\">";	
					}
					echo "<b>Followers:</b> <span class=\"color999\">".$totalFers."</span>";
					if($totalFers>0){
						echo "</a>";	
					}
					echo "</div>";
					?>
				</div>
			</div>
			<div id="profileMain2">
				<p class="profileDetails allcaps" itemprop="jobTitle">
				<?php echo $resDoctor[0]["name_taxonomy"]; ?>
				</p>
				<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<p class="profileDetails">
				<?php
				$mapByAddress=$resDoctor[0]["address_1_doctor"];
				echo '<span itemprop="streetAddress">'.ucwords(strtolower($resDoctor[0]["address_1_doctor"]));
				if($resDoctor[0]["address_2_doctor"]!=""){
				echo " ".ucwords(strtolower($resDoctor[0]["address_2_doctor"]))."</span>";
				$mapByAddress.=" ".$resDoctor[0]["address_2_doctor"];
				}
				?>
				</p>
				<p class="profileDetails">
				<?php
				
				if(isset($resZip) && $resZip["result"]){
					echo '<span itemprop="addressLocality">'.$resZip[0]["city"]."</span>, ";
					echo '<span itemprop="addressRegion">'.$usStates[$resZip[0]["state"]]."</span> ";
					$mapByAddress.=" ".$resZip[0]["city"].' '.$usStates[$resZip[0]["state"]];
					echo '<span itemprop="postalCode">'.$resZip[0]["zip"].'</span>';
					$mapByAddress.=" ".$resZip[0]["zip"]." USA";
				}
				
				if(!isset($resZip) || !$resZip["result"]){
					echo '<span itemprop="addressLocality">'.$resDoctor[0]["city_doctor"].'</span>, <span itemprop="addressRegion">'.$resDoctor[0]["state_doctor"]."</span> ";
				
					if($resDoctor[0]["city_doctor"]!="APO" && $resDoctor[0]["city_doctor"]!="FPO" 
					&& $resDoctor[0]["state_doctor"]!="AA" && $resDoctor[0]["state_doctor"]!="AE" && $resDoctor[0]["state_doctor"]!="AP"){
					$mapByAddress.=' '.$resDoctor[0]["city_doctor"].' '.$resDoctor[0]["state_doctor"];
					}
					echo substr($resDoctor[0]["postal_code_doctor"], 0,5);
					$mapByAddress.=" ".$resDoctor[0]["postal_code_doctor"]." USA";
				}
				
				?>
				</p>
				</div>
				<div class="phoneFaxHolder">
				<?php
				if($resDoctor[0]["telephone_doctor"]!=""){
				$phoneNumber=$configClass->formatPhoneNumber($resDoctor[0]["telephone_doctor"]);
				echo "<a class=\"btn btn-red phoneButton\" href=\"tel:".$resDoctor[0]["telephone_doctor"]."\">";
				echo '<img src="'.WEB_URL.'inc/img/v1/profile/phone.png" alt="Phone" /><span itemprop="telephone">';
				echo $phoneNumber."</span></a>";
				}
				if($resDoctor[0]["fax_doctor"]!=""){
				echo '<span class="faxGray">Fax: <span itemprop="faxNumber">'.$configClass->formatPhoneNumber($resDoctor[0]["fax_doctor"])."</span></span>";
				}
				?>
				</div>
				<?php
				if($resProfile[0]["id_profile"]==USER_ID){
				?>
				<p><a href="<?php echo WEB_URL."account/details"; ?>" class="blueWithUnderline">edit account details</a></p>
				<?php
				}
				
				if($resProfile[0]["id_profile"]==USER_ID || $resProfile[0]["bio_profile"]!=""){
				?>
				<div class="docBio">
				<h2>Bio</h2>
				<?php
				}
				echo '<p id="docBioP"';
				if($resProfile[0]["bio_profile"]==""){
					echo ' style="display:none;"';
				}
				echo '>'.$resProfile[0]["bio_profile"].'</p>';
				if($resProfile[0]["id_profile"]==USER_ID){
					function br2nl($string)
					{
					    return preg_replace('#<br\s*?/?>#i', "", $string);
					}  
					?>
					<div class="docBioForm">
					<textarea class="docBioTA" maxlength="450"><?php echo br2nl($resProfile[0]["bio_profile"]); ?></textarea>
					<button class="btn btn-red" id="saveBioBtn">Save</button>
					</div>
					<a href="#" onclick="return showBioForm();" id="editBioBtn" class="colorBlue" style="padding-left:10px;">edit bio</a>
					<script>
					function nl2br (str) {   
						var breakTag = '<br ' + '/>';
						return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    				}
					</script>
					<?php
					$jsfunctions.="
					function showBioForm(){
						$('#docBioP').hide();
						$('#editBioBtn').hide();
						$('.docBioForm').show();
						return false;
					};
					";
					
					$onload.="$('#saveBioBtn').click(function(){
						$('.docBioForm').slideUp('fast');
						$('#editBioBtn').show();
						var textAreaContent=$('.docBioTA').val();
						textAreaContent=nl2br(textAreaContent);
						$('#docBioP').html(textAreaContent);
						$('#docBioP').show();
						$.ajax({
						  type: 'POST',
						  url: '".WEB_URL."act/ajax/profile/saveBio.php',
						  data: { bio: $('.docBioTA').val() }
						}).done(function( msg ) {
						  if(msg!='ok'){
							  alert('Ops! We could not save your bio. Please try again later or contact us.');	
						  }
						});

					});
					";
				}
				if($resProfile[0]["id_profile"]==USER_ID || $resProfile[0]["bio_profile"]!=""){
				echo "</div>";
				}
				?>
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
		<hgroup id="profileTop" class="clearfix">
			<div style="background:#5E93CC;padding:5px 10px;color:#fff;font-size:16px;margin:10px 0 30px 0;-webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px;">
				Is interested in
			</div>
			<div id="profileInfo">
				<?php
				
				$res = $topicClass->getAllUserFollowedFromTopic('m',$resProfile[0]["id_profile"]);
				?>
				<h2 class="clearfix"><img src="<?php echo WEB_URL; ?>inc/img/v2/profile/medications.png" alt="transparent blue pill" /> <span>Medications</span></h2>
				<div id="iProfileBoxMed" class="profileUserHealthActive <?php if(USER_ID==0){ echo "marginBottom20"; } ?> clearfix">
				<?php 
				if($res["result"]){
					$string="";
					$i=0;
					foreach($res as $key=>$value){
						if(is_int($key)){
							$i=$key;
							if($key==4){
								echo "<div class=\"profileUserHealthActiveHide\" style=\"display:none;\">";
							}
							echo "<span>";
							if($resProfile[0]["id_profile"]==USER_ID){
								echo "<b>delete</b>";
							}
							
							echo "<a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('m')."/".$value["url_topic"]."\">".ucwords(strtolower($value["name_topic"]))."</a>";
							echo "</span>";
						}
					}
					if($i>3){
						echo "</div>";
						echo "<div class=\"profileHideButton btn btn-blue";
						if($resProfile[0]["id_profile"]!=USER_ID){ echo " profileViewMoreOthers"; }
						echo " input100\">View More</div>";
					}
				}else if($resProfile[0]["id_profile"]!=USER_ID){
					echo "<span>none</span>";
				}
				$onload.="
					$('.profileHideButton').click(function(){
						$(this).parent().children('.profileUserHealthActiveHide').slideDown('fast');
						$(this).hide();
					});
				";
				if($resProfile[0]["id_profile"]==USER_ID){
					echo '<div id="addMinput" class="addProfileTopic"><input type="text" id="topicM" name="topicM" /></div>';
					$needTokenInput=1;
					$onload.="$('#topicM').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=m', { hintText: 'Type the name of the medication',placeholder:'Add new medication', noResultsText: 'No medication with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicM').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addMinput'),$('#addM'),$('#iProfileBoxMed span'),$('#iProfileBoxMed'),'topicM'); } } });";
					
					$onload.="
						$('.profileUserHealthActive').delegate('span b','mouseenter',function(){
							$(this).parent().children('a').css('color','#E86271');
							$(this).css('color','#E86271');
						});
						$('.profileUserHealthActive').delegate('span b','mouseleave',function(){
							$(this).parent().children('a').css('color','#999999');
							$(this).css('color','#999999');
						});
					";
					
					$onload.="
						$('.profileUserHealthActive').delegate('span b','click',function(){
							var topicName=$(this).parent().children('a').html();
							if(topicName!=undefined && topicName!='' && confirm('Are you sure you want to remove '+topicName+'?')){
								var topicId=$(this).parent().children('a').attr('id');
								topicId = parseInt(topicId.replace('topic_', ''));
								
								if(topicId>0){
									$(this).parent().hide();
									$.ajax({
									  type: 'POST',
									  url: '".WEB_URL."act/ajax/profile/removeTopic.php',
									  data: { id: topicId }
									}).done(function( msg ) {
										if(msg=='error'){
											alert('Sorry :( Something went wrong and we did not save that, please try again later and if it still does not work, please contact us.');
											$(this).parent().show();
										}
	  								});
								}
							
							}
						});
					";
					
					$jsfunctions.="
					function addTopicInline(item,inputdiv,buttonspan,parentdiv,realparentdiv,tokenin){
						$('#'+tokenin).tokenInput('clear');
						var count = parentdiv.length;
						if(count==1){
							realparentdiv.prepend('<span id=\"addNewSpan_'+item+'\"><img src=\"".WEB_URL."inc/img/v1/inc/ajax-loader.gif\" /></span>');
						}else{
							$('<span id=\"addNewSpan_'+item+'\"><img src=\"".WEB_URL."inc/img/v1/inc/ajax-loader.gif\" /></span>').insertBefore(parentdiv.eq(0));
						}
						
						$.ajax({
						  type: 'POST',
						  url: '".WEB_URL."act/ajax/profile/addTopic.php',
						  data: { id: item }
						}).done(function( msg ) {
							if(msg=='error'){
								alert('Sorry :( Something went wrong and we did not save that, please try again later and if it still does not work, please contact us.');
								$('#addNewSpan_'+item).hide();
							}else if(msg=='repeat'){
								alert('You already follow that! :)');
								$('#addNewSpan_'+item).hide();
							}else{
								$('#addNewSpan_'+item).html(msg);
							}
						});
						
					}
					";
				}
				?>
				</div>
				<?php
				$res = $topicClass->getAllUserFollowedFromTopic('p',$resProfile[0]["id_profile"]);
				?>
				<h2 class="clearfix"><img src="<?php echo WEB_URL; ?>inc/img/v2/profile/procedures.png" alt="transparent blue procedures" /><span>Procedures</span></h2>
				<div id="iProfileBoxPro" class="profileUserHealthActive <?php if(USER_ID==0){ echo "marginBottom20"; } ?> clearfix">
				<?php 
				if($res["result"]){
					$string="";
					$i=0;
					foreach($res as $key=>$value){
						if(is_int($key)){
							$i=$key;
							if($key==4){
								echo "<div class=\"profileUserHealthActiveHide\" style=\"display:none;\">";
							}
							echo "<span>";
							if($resProfile[0]["id_profile"]==USER_ID){
								echo "<b>delete</b>";
							}
							echo "<a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('p')."/".$value["url_topic"]."\">".ucwords(strtolower($value["name_topic"]))."</a>";
							echo "</span>";
						}
					}
					if($i>3){
						echo "</div>";
						echo "<div class=\"profileHideButton btn btn-blue";
						if($resProfile[0]["id_profile"]!=USER_ID){ echo " profileViewMoreOthers"; }
						echo " input100\">View More</div>";
					}
				}else if($resProfile[0]["id_profile"]!=USER_ID){
					echo "<span>none</span>";
				}
				if($resProfile[0]["id_profile"]==USER_ID){
					echo '<div id="addPinput" class="addProfileTopic"><input type="text" id="topicP" name="topicP" /></div>';
					$onload.="$('#topicP').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=p', { hintText: 'Type the name of the procedure',placeholder:'Add new procedure', noResultsText: 'No procedures with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicP').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addPinput'),$('#addP'),$('#iProfileBoxPro span'),$('#iProfileBoxPro'),'topicP'); } } });";
					
				}
				?>
				</div>
			</div>
			<div id="profileDetails">
				
				<?php
				$res = $topicClass->getAllUserFollowedFromTopic('d',$resProfile[0]["id_profile"]);
				?>
				<h2 class="clearfix"><img src="<?php echo WEB_URL; ?>inc/img/v2/profile/conditions.png" alt="transparent blue conditions" /><span>Conditions</span></h2>
				<div id="iProfileBoxCon" class="profileUserHealthActive <?php if(USER_ID==0){ echo "marginBottom20"; } ?> clearfix">
				<?php 
				if($res["result"]){
					$string="";
					$i=0;
					foreach($res as $key=>$value){
						if(is_int($key)){
							$i=$key;
							if($key==4){
								echo "<div class=\"profileUserHealthActiveHide\" style=\"display:none;\">";
							}
							echo "<span>";
							if($resProfile[0]["id_profile"]==USER_ID){
								echo "<b>delete</b>";
							}
							echo "<a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('d')."/".$value["url_topic"]."\">".ucwords(strtolower($value["name_topic"]))."</a>";
							echo "</span>";
						}
					}
					if($i>3){
						echo "</div>";
						echo "<div class=\"profileHideButton btn btn-blue";
						if($resProfile[0]["id_profile"]!=USER_ID){ echo " profileViewMoreOthers"; }
						echo " input100\">View More</div>";
					}
				}else if($resProfile[0]["id_profile"]!=USER_ID){
					echo "<span>none</span>";
				}
				if($resProfile[0]["id_profile"]==USER_ID){
					echo '<div id="addCinput" class="addProfileTopic"><input type="text" id="topicC" name="topicC" /></div>';
					$onload.="$('#topicC').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=d', { hintText: 'Type the name of the condition',placeholder:'Add new condition', noResultsText: 'No conditions with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicC').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addCinput'),$('#addC'),$('#iProfileBoxCon span'),$('#iProfileBoxCon'),'topicC'); } } });";
					
				}
				?>
				</div>
				<?php
				$res = $topicClass->getAllUserFollowedFromTopic('s',$resProfile[0]["id_profile"]);
				?>
				<h2 class="clearfix"><img src="<?php echo WEB_URL; ?>inc/img/v2/profile/symptoms.png" alt="transparent blue symptoms" /><span>Symptoms</span></h2>
				<div id="iProfileBoxSim" class="profileUserHealthActive <?php if(USER_ID==0){ echo "marginBottom20"; } ?> clearfix">
				<?php 
				if($res["result"]){
					$string="";
					$i=0;
					foreach($res as $key=>$value){
						if(is_int($key)){
							$i=$key;
							if($key==4){
								echo "<div class=\"profileUserHealthActiveHide\" style=\"display:none;\">";
							}
							echo "<span>";
							if($resProfile[0]["id_profile"]==USER_ID){
								echo "<b>delete</b>";
							}
							echo "<a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('s')."/".$value["url_topic"]."\">".ucwords(strtolower($value["name_topic"]))."</a>";
							echo "</span>";
						}
					}
					if($i>3){
						echo "</div>";
						echo "<div class=\"profileHideButton btn btn-blue";
						if($resProfile[0]["id_profile"]!=USER_ID){ echo " profileViewMoreOthers"; }
						echo " input100\">View More</div>";
					}
				}else if($resProfile[0]["id_profile"]!=USER_ID){
					echo "<span>none</span>";
				}
				if($resProfile[0]["id_profile"]==USER_ID){
					echo '<div id="addSinput" class="addProfileTopic"><input type="text" id="topicS" name="topicS" /></div>';
					$onload.="$('#topicS').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=s', { hintText: 'Type the name of the symptom',placeholder:'Add new symptom', noResultsText: 'No symptoms with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicS').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addSinput'),$('#addS'),$('#iProfileBoxSim span'),$('#iProfileBoxSim'),'topicS'); } } });";
					
				}
				?>
				</div>
			</div>
		</hgroup>
		<hgroup id="profileBottom" class="clearfix">
			<?php
			if(USER_ID!=0){
			?>
			<div id="profileCTA">
			<?php require_once(ENGINE_PATH."render/feed/ctadoctor.php"); ?>
			</div>
			<?php
			}
			$resPosts=$postClass->getPostsFromAndAboutUser($resProfile[0]["id_profile"]);
			if($resPosts["result"]){
			$backPath=$resProfile[0]["username_profile"];
			?>
			<div id="postHolder" class="clearfix">
			<?php require_once(ENGINE_PATH."render/feed/list.php"); ?>
			</div>
			<?php
			$ajaxUrl=WEB_URL."act/ajax/profile/postsNew.php";
			$onload.="endlessScroll('$ajaxUrl',$('#iHoldPosts'),".$resProfile[0]['id_profile'].");";
			require_once(ENGINE_PATH."render/feed/endless.php");
			}
			?>
		</hgroup>
	</hgroup>	
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');