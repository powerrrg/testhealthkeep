<?php
require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/timeline.class.php');
$timelineClass=new Timeline();

require_once(ENGINE_PATH.'class/location.class.php');
$locationClass=new Location();

$pageTitle=$resProfile[0]["username_profile"]." - HealthKeep";
$pageDescr="HealthKeep profile page for the user ".$resProfile[0]["username_profile"].".";

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main" style="padding-bottom:40px;">
	<hgroup class="iWrap clearfix">
		
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
		
		<hgroup id="profileTop" class="clearfix">
			<div id="profileInfo">
				<div id="profileBio" class="clearfix">
					<?php
					if($resProfile[0]["image_profile"]==""){
						$imagePath=WEB_URL."inc/img/empty-avatar.png";
						$imageAlt="No Image Avatar";
					}else{
						$imagePath=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
						$imageAlt=$resProfile[0]["username_profile"];
					}
					?>
					<div id="profileAvatar">
						<?php
						if($resProfile[0]["id_profile"]==USER_ID){
						?>
						<a href="<?php echo WEB_URL; ?>avatar">
						<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
						<span id="profileAvatarChange">change</span>
						</a>
						<?php
						$onload.="$('#profileAvatar a').hover(function(){ 
							$(this).css('opacity','.5'); 
							$('#profileAvatarChange').show();
						}, function(){ 
							$(this).css('opacity','1'); 
							$('#profileAvatarChange').hide();
						});";
						}else{
						?>
							<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
						<?php
						}
						?>
					</div>
					<div id="profileBioDetails">
						<h1 id="profileUserName"><?php echo $resProfile[0]["username_profile"]; ?></h1>
						<?php
						if($resProfile[0]["gender_profile"]=="m" || $resProfile[0]["gender_profile"]=="f" || $resProfile[0]["dob_profile"]!="0000-00-00"){
							echo "<p class=\"fontNormal\">";
							if($resProfile[0]["gender_profile"]=="m" || $resProfile[0]["gender_profile"]=="f"){
								if($resProfile[0]["gender_profile"]=="m"){
									$gender="Male";
								}else{
									$gender="Female";
								}
								echo $gender;
							}
							if($resProfile[0]["dob_profile"]!="0000-00-00"){
								if(isset($gender)){
									echo ', ';
								}
								require_once(ENGINE_PATH."common/date.php");
								echo age($resProfile[0]["dob_profile"])." Years Old"; 
							}
							echo "</p>";
						}
						$locationText="";
						if($resProfile[0]["country_profile"]!="US"){
							$resCountry=$locationClass->getCountryByIso($resProfile[0]["country_profile"]);
							if($resCountry["result"]){
								 $locationText=$resCountry[0]["short_name"];
							}
						}else{
							$resZip=$locationClass->getZipByZip($resProfile[0]["zip_profile"]);
							if($resZip["result"]){
								require_once(ENGINE_PATH."html/inc/common/usStates.php");
								if(isset($usStates[$resZip[0]["state"]])){
									$locationText=$usStates[$resZip[0]["state"]];
								}
							}
						}
						
						if($resProfile[0]["job_profile"]!="" || $locationText!=""){
							echo "<p class=\"fontNormal\">";
							if($resProfile[0]["job_profile"]!=""){
								echo $resProfile[0]["job_profile"];
							}
							if($locationText!=""){
								if($resProfile[0]["job_profile"]!=""){
									echo ', ';
								}
								echo $locationText;
							}
							echo "</p>";
						}
						if($resProfile[0]["weight_profile"]!="0" || $resProfile[0]["feet_profile"]!="0"){
							echo "<p>";
							if($resProfile[0]["weight_profile"]!="0"){
							?>
								<span style="margin-right:20px;">Weight: <span class="fontNormal"><?php echo $resProfile[0]["weight_profile"]; ?> lb</span></span>
							<?php
							}
							if($resProfile[0]["feet_profile"]!="0"){
								echo 'Height: <span class="fontNormal">'.$resProfile[0]["feet_profile"].'\'';
								if($resProfile[0]["inch_profile"]!=0){ 
									echo (float)$resProfile[0]["inch_profile"].'"';
								} 
								echo "</span>";
							}
							echo "</p>";
						}
						if($resProfile[0]["bio_profile"]!=""){
						?>
						<div id="profileMiniBio">
						<b>Bio</b>
						<p class="fontNormal"><?php echo $resProfile[0]["bio_profile"]; ?></p>
						</div>
						<?php
						}
						if($resProfile[0]["id_profile"]==USER_ID){
						?>
						<p><a href="<?php echo WEB_URL."account/details"; ?>" class="blueWithUnderline">edit bio</a> | <a href="<?php echo WEB_URL."account/health"; ?>" class="blueWithUnderline">edit health</a></p>
						<?php
						}
						$resProfileUser=$userClass->getById($resProfile[0]["id_profile"]);
						if($resProfileUser){
						?>
						<p>Last login: <span class="fontNormal"><?php echo $configClass->ago(strtotime($resProfileUser[0]["last_login_user"])); ?></span></p>
						<?php
						}
						if(USER_TYPE==9){
						?>
						<p>Email: <a href="mailto:<?php echo $resProfileUser[0]["email_user"]; ?>"><?php echo $resProfileUser[0]["email_user"]; ?></a></p>
						<?php
						}
						?>
					</div>
				</div>
				<div id="profileInfoDetails">
					<hgroup style="padding:10px 0 20px 0;margin:0;">
						<h3>Badges</h3>
						<div>
							<?php
							$sharingBadge=$resProfile[0]["sharing_profile"];
							$supportiveBadge=$resProfile[0]["supportive_profile"];
							$helpfulBadge=$resProfile[0]["helpful_profile"];
							$karmaBadge=$resProfile[0]["karma_profile"];
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
						<div>
							<span class="iBadgeName">Sharing</span>
							<span class="iBadgeName">Supportive</span>
							<span class="iBadgeName">Helpful</span>
							<span class="iBadgeName">Karma</span>
						</div>
					</hgroup>
					<hgroup style="margin-top:20px;">
						<?php
						$countFollowingUsers=$profileClass->countFollowing($resProfile[0]["id_profile"]);
						$totalUsers=0;
						if($countFollowingUsers["result"]){
							$totalUsers=$countFollowingUsers[0]["total"];
						}
						if($totalUsers>0){
							echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/following/\" class=\"underlineAlternative\">";	
						}
						echo "<b style=\"padding-right:10px;\">Following:</b> <span class=\"color999\">".$totalUsers."</span>";
						if($totalUsers>0){
							echo "</a>";	
						}
						$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
						$totalFers=0;
						if($res["result"]){
							$totalFers=$res[0]["total"];
						}
						if($totalFers>0){
							echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/followers/\" class=\"underlineAlternative\">";	
						}
						echo "<b style=\"margin-left:50px;padding-right:10px;\">Followers:</b> <span class=\"color999\">".$totalFers."</span>";
						if($totalFers>0){
							echo "</a>";	
						}
						?>
					</hgroup>
					<?php
					if(USER_ID!=0){
						if(USER_ID!=$resProfile[0]["id_profile"]){
							?>
							<div class="clearfix">
							<?php
							$resIfollow=$profileClass->doIFollow($resProfile[0]["id_profile"]);
							if($resIfollow["result"]){
							?>
								<div class="profileUserBtnFollow" style="float:left;">
									<button class="btn btn-red" id="followBtn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?no&id=".$resProfile[0]["id_profile"]; ?>'">Following</button>
								</div>
								<?php	
								$onload.="$('#followBtn').hover(function(){
									$(this).text('unfollow');	
								},function(){
									$(this).text('following');
								});";
								
								}else{
								?>
								<div class="profileUserBtnFollow" style="float:left;">
									<button class="btn btn-blue" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'">Follow</button>
								</div>
							<?php
							}
							?>
								<div style="float:left;margin-top: 20px;margin-left:20px;">
								<button class="btn btn-red" onclick="$('#msgPost').toggle();">Send Message</button>
								</div>
								<p id="msgPostSubNotice" style="display:none;text-align:center;margin:80px 0 30px 0;color:#666;clear:both;background:#DFE9F5;padding:30px 0;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;">submitting...</p>
								<?php
								require_once(ENGINE_PATH."render/feed/ctapostmessage.php");
								?>
							</div>
							<?php
						}
						
					}
					?>
					<hgroup>
						<?php
						$goals = $topicClass->getAllUserFollowedFromTopic('g',$resProfile[0]["id_profile"]);
						$arrayGoals=$topicClass->allGoals();
						
						$availableGoals=array();
						
						foreach($arrayGoals as $key=>$value){
							if(is_int($key)){
								$availableGoals[$value["id_topic"]]=$value["name_topic"];
							}
						}
						
						$maxNumberOfGoals=$arrayGoals["result"];
						if($goals["result"] || $resProfile[0]["id_profile"]==USER_ID){
						?>
						<div id="iHealthGoals">
							<h3>Health Goals</h3>
							<div id="profileUserHealthGoals">
								<?php
								if($goals["result"]){
									foreach($goals as $keyG=>$valueG){
										if(is_int($keyG)){
											if(isset($availableGoals[$valueG["id_topic"]])){
												unset($availableGoals[$valueG["id_topic"]]);
											}
											echo '<div id="goal_'.$valueG["id_topic"].'" class="iMGoal">';
											if($resProfile[0]["id_profile"]==USER_ID){
												echo ' <b>delete</b>';	
											}
											echo '<a href="'.WEB_URL.$topicClass->pathSingular('g')."/".$valueG["url_topic"].'" class="goalNameClass">'.$valueG["name_topic"].'</a>';
											
											echo '</div>';
										}
									}
								}
								if($resProfile[0]["id_profile"]==USER_ID){
									?>
									<div id="addGoalPre"><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader.gif" /></div>
									<div id="addGoalBtn" <?php if(count($availableGoals)==0){ echo 'style="display:none;"'; } ?>><span class="blueWithUnderline">Add a Health Goal</span></div>
									<div id="addGoal"></div>
									<?php
								}
								?>
							</div>
						</div>
						<?php
						}
						if($resProfile[0]["id_profile"]==USER_ID){
						?>
						<script type='text/javascript'>
						<?php
						$js_array = json_encode($availableGoals);
						echo "var availableGoals = ". $js_array . ";\n";
						?>
						</script>
						<?php
						$onload.="
						$('#profileUserHealthGoals').delegate('b','mouseenter',function(){
							$(this).parent().children('a').css('color','#E86271');
							$(this).css('color','#E86271');
						});
						$('#profileUserHealthGoals').delegate('b','mouseleave',function(){
							$(this).parent().children('a').css('color','#999999');
							$(this).css('color','#999999');
						});
						$('#profileUserHealthGoals').delegate('div b','click',function(){
							var goalName=$(this).parent().children('.goalNameClass').html();
							if(goalName!=undefined && goalName!='' && confirm('Are you sure you want to remove '+goalName+'?')){
								var goalId=$(this).parent().attr('id');
								goalId = parseInt(goalId.replace('goal_', ''));
								
								if(goalId>0){
									$(this).parent().hide();
									Object.size = function(obj) {
								    var size = 0, key;
								    for (key in obj) {
								        if (obj.hasOwnProperty(key)) size++;
								    }
								    return size;
									};
									
									var size = Object.size(availableGoals);
									if(size==0){
										$('#addGoalBtn').show();
									}
									availableGoals[goalId]=goalName;
									$.ajax({
									  type: 'POST',
									  url: '".WEB_URL."act/ajax/profile/removeTopic.php',
									  data: { id: goalId }
									}).done(function( msg ) {
										if(msg=='error'){
											alert('Sorry :( Something went wrong and we did not save that, please try again later and if it still does not work, please contact us.');
											delete(availableGoals[goalId]);
											if(availableGoals.length==0){
												$('#addGoalBtn').hide();
											}
											$(this).parent().show();
										}
	  								});
								}
							
							}
						});
						$('#addGoalBtn').delegate('span','click',function(){ 
							$('#addGoalBtn').hide();
							Object.size = function(obj) {
						    var size = 0, key;
						    for (key in obj) {
						        if (obj.hasOwnProperty(key)) size++;
						    }
						    return size;
							};
							
							var size = Object.size(availableGoals);
							if(size>0){
								$('#addGoal').html('');
								$('#addGoal').append('<ul>');
								$.each( availableGoals, function( key, value ) {
								  $('#addGoal ul').append('<li id=\"addgoal_'+key+'\">'+value+'</li>');
								});
								$('#addGoal').show();
							}
						});
						$('#addGoal').delegate('ul li','mouseenter',function(){
							$(this).css('text-decoration','underline');
						});
						$('#addGoal').delegate('ul li','mouseleave',function(){
							$(this).css('text-decoration','none');
						});
						
						$('#addGoal').delegate('ul li','click',function(){
							var goalId=$(this).attr('id');
							goalId = parseInt(goalId.replace('addgoal_', ''));
							if(goalId!=undefined && goalId!=''){
								$(this).hide();
								Object.size = function(obj) {
							    var size = 0, key;
							    for (key in obj) {
							        if (obj.hasOwnProperty(key)) size++;
							    }
							    return size;
								};
								
								var size = Object.size(availableGoals);
								if(size>1){
								$('#addGoalBtn').show();
								}
								$('#addGoal').hide();
								$('#addGoalPre').show();
								delete(availableGoals[goalId]);
								$.ajax({
								  type: 'POST',
								  url: '".WEB_URL."act/ajax/profile/addGoal.php',
								  data: { id: goalId }
								}).done(function( msg ) {
									$('#addGoalPre').hide();
									if(msg=='error'){
										alert('Sorry :( Something went wrong and we did not save that, please try again later and if it still does not work, please contact us.');
									}else if(msg=='repeat'){
										alert('You already have that goal :)');
									}else{
										$(msg).insertBefore('#addGoalBtn');
									}
								});
							}
	
						});
						";
						
						}
						?>
					</hgroup>
					<?php
					$resDoc=$profileClass->getUserDoctorsFollowed($resProfile[0]["id_profile"]);
					if($resDoc["result"] || $resProfile[0]["id_profile"]==USER_ID){
					?>
					<hgroup>
						<h2 class="clearfix"><img src="<?php echo WEB_URL; ?>inc/img/v2/profile/doctor_blue.png" alt="Doctors" /> <span>Doctors</span></h2>
						<ul id="profileUserDoctors">
							<?php
							foreach($resDoc as $keyDoc=>$valueDoc){
								if(is_int($keyDoc)){
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
							<?php if($resProfile[0]["id_profile"]==USER_ID){ echo '<b>delete</b>'; } ?>
							<a href="<?php echo WEB_URL.$valueDoc["username_profile"]; ?>"><img src="<?php echo $docImagePath; ?>" alt="<?php echo $docImageAlt; ?>" /></a>
							<a href="<?php echo WEB_URL.$valueDoc["username_profile"]; ?>" class="doctorNameClass"><?php echo $valueDoc["name_profile"]; ?></a>
							</li>
							<?php
								}
							}
							?>
						</ul>
						<?php
						if($resProfile[0]["id_profile"]==USER_ID){
							$onload.="
							$('#profileUserDoctors').delegate('li b','mouseenter',function(){
								$(this).parent().children('a').css('color','#E86271');
								$(this).css('color','#E86271');
							});
							$('#profileUserDoctors').delegate('li b','mouseleave',function(){
								$(this).parent().children('a').css('color','#999999');
								$(this).css('color','#999999');
							});
							
							$('#profileUserDoctors').delegate('li b','click',function(){
								var docName=$(this).parent().children('.doctorNameClass').html();
								if(docName!=undefined && docName!='' && confirm('Are you sure you want to remove '+docName+'?')){
									var docId=$(this).parent().attr('id');
									docId = parseInt(docId.replace('doctor_', ''));
									
									if(docId>0){
										$(this).parent().hide();
										$.ajax({
										  type: 'POST',
										  url: '".WEB_URL."act/ajax/profile/removeDoc.php',
										  data: { id: docId }
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
							$needTokenInput=1;
							$onload.="$('#addDoctor').tokenInput('".WEB_URL."act/ajax/autoCompleteFullDoc.php', { hintText: 'Type the name of the Doctor',placeholder:'Add new doctor', noResultsText: 'No doctor with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#addDoctor').tokenInput('clear'); }else{ addDocInline(item.id); } },
		              resultsFormatter: function(item){ return '<li class=\"docDropDown clearfix\">' + '<img src=\"' + item.image + '\" />' + '<div class=\"docDropDownTxt\"><div class=\"docDropDownName\">' + item.name + '</div><div class=\"docDropDownLocation\">' + item.state + '</div></div></li>' }
		               });";
		             
		              $jsfunctions.="
		              	function addDocInline(id){
		              		$('#addDoctor').tokenInput('clear');
		              		$.ajax({
							  type: 'POST',
							  url: '".WEB_URL."act/ajax/profile/addDoc.php',
							  data: { id: id }
							}).done(function( msg ) {
								if(msg=='error'){
									alert('Sorry :( Something went wrong and we did not save that, please try again later and if it still does not work, please contact us.');
								}else if(msg=='repeat'){
									alert('You already follow that doctor! :)');
								}else{
									$('#profileUserDoctors').append(msg);
								}
							});
		              	}
		              ";
							?>
								<div id="addDocInput">
								<input type="text" id="addDoctor" style="text-align:left;" name="addDoctor" />
								</div>
		
							<?php
							}
							?>
					</hgroup>
					<?php
					}
					?>
				</div>
			</div>
			<div id="profileDetails">
				<?php
				if($resProfile[0]["type_profile"]==1){
				
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
				<?php
				}
				?>
			</div>
		</hgroup>
	</hgroup>
</article>
			<?php
			if(USER_ID!=0){
			?>
			<div id="profileHealthTimelineHeader">
				<div class="iWrap">Health Timeline</div>
			</div>
			<?php
				if(isset($resProfile) && $resProfile[0]["id_profile"]==USER_ID){
					?>
					<div id="profileCTA2">
					<?php require_once(ENGINE_PATH."render/feed/ctaprofile.php"); ?>
					</div>
					<?php
				}
			}else{
				echo '<hr style="margin:0;" />';
			}
			?>
<article style="background: #f4f4f4;">
	<hgroup class="iWrap clearfix">
			<hgroup id="profileBottom" class="clearfix">
			<?php
			$resPosts=$postClass->getPostsFromAndAboutUser($resProfile[0]["id_profile"]);
			if($resPosts["result"]){
			$backPath=$resProfile[0]["username_profile"];
			$iMHealthTL=1;
			?>
			<div id="postHolder" class="clearfix iMHealthTL">
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