<?php
require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/timeline.class.php');
$timelineClass=new Timeline();

$pageTitle=$resProfile[0]["username_profile"]." - HealthKeep";
$pageDescr="HealthKeep profile page for the user ".$resProfile[0]["username_profile"].".";

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');

?>
<div id="main">
	<div class="iHold clearfix">
		<div id="userProfile" class="iBoard">
			<?php
			require_once(ENGINE_PATH."html/profile/emailWarning.php");
			?>
			<div class="iHeading">
				<h1 class="profileUserHeadingName"><?php echo $resProfile[0]["username_profile"]; ?>'s <span class="colorGray">Profile</span></h1>
			</div>
			<div id="profileUserTop" class="clearfix">
				<div id="profileUserInfo" class="iBoard2">
					<?php
					if($resProfile[0]["image_profile"]==""){
						$imagePath=WEB_URL."inc/img/empty-avatar.png";
						$imageAlt="No Image Avatar";
					}else{
						$imagePath=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
						$imageAlt=$resProfile[0]["username_profile"];
					}
					?>
					<div id="profileUserInfoTop" class="clearfix">
						<div id="profileUserImage">
							<?php
							if($resProfile[0]["id_profile"]==USER_ID){
							?>
							<a href="<?php echo WEB_URL; ?>avatar">
							<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
							<span id="profileUserImageChange">change</span>
							</a>
							<?php
							$onload.="$('#profileUserImage a').hover(function(){ 
								$(this).css('opacity','.5'); 
								$('#profileUserImageChange').show();
							}, function(){ 
								$(this).css('opacity','1'); 
								$('#profileUserImageChange').hide();
							});";
							}else{
							?>
								<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
							<?php
							}
							?>
						</div>
						<ul id="profileUserDetails">
							<?php
							if($resProfile[0]["id_profile"]==USER_ID){
								$onload.="
								$('#profileUserDetails').css('cursor','pointer');
								$('#profileUserDetails').click(function(){
									location.href='".WEB_URL."account/details';
								});";
							}
							if($resProfile[0]["gender_profile"]=="m" || $resProfile[0]["gender_profile"]=="f"){
								if($resProfile[0]["gender_profile"]=="m"){
									$gender="Male";
								}else{
									$gender="Female";
								}
								?>
								<li><?php echo $gender; ?></li>
								<?php
							}
							if($resProfile[0]["dob_profile"]!="0000-00-00"){
							require_once(ENGINE_PATH."common/date.php");
							?>
							<li>
							Age <?php echo age($resProfile[0]["dob_profile"]); ?>
							</li>
							<?php
							}
							if($resProfile[0]["job_profile"]!=""){
							?>
							<li><?php echo $resProfile[0]["job_profile"]; ?></li>
							<?php
							}
							require_once(ENGINE_PATH.'class/location.class.php');
							$locationClass=new Location();
							if($resProfile[0]["country_profile"]!="US"){
								$resCountry=$locationClass->getCountryByIso($resProfile[0]["country_profile"]);
								if($resCountry["result"]){
							?>
								<li><?php echo $resCountry[0]["short_name"]; ?></li>
							<?php
								}
							}else{
								$resZip=$locationClass->getZipByZip($resProfile[0]["zip_profile"]);
								if($resZip["result"]){
									require_once(ENGINE_PATH."html/inc/common/usStates.php");
									if(isset($usStates[$resZip[0]["state"]])){
										echo "<li>".$usStates[$resZip[0]["state"]]."</li>";
									}
								}
							}
							?>
						</ul>
					</div>
					<div id="profileUserFollows" class="clearfix">
						<span id="profileUserNumFollowers" class="profileUserFollowCount">
							<?php
							$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
							$totalFers=0;
							if($res["result"]){
								$totalFers=$res[0]["total"];
							}
							if($totalFers>0){
								echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/followers/\" class=\"colorBlue\">";	
							}
							echo "<span class=\"colorBlue\">";
							echo $totalFers;
							echo "</span> Followers";
							if($totalFers>0){
								echo "</a>";	
							}
							?>
						</span>
						<span id="profileUserNumFollowing" class="profileUserFollowCount">
							<?php
							$countFollowingUsers=$profileClass->countFollowing($resProfile[0]["id_profile"]);
							$totalUsers=0;
							if($countFollowingUsers["result"]){
								$totalUsers=$countFollowingUsers[0]["total"];
							}
							if($totalUsers>0){
								echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/following/\" class=\"colorRed\">";	
							}
							echo "<span class=\"colorRed\">";
							echo $totalUsers;
							echo "</span> Following";
							if($totalUsers>0){
								echo "</a>";	
							}
							?>
						</span>
					</div>
					<?php
					if(USER_ID!=0){
						if(USER_ID!=$resProfile[0]["id_profile"]){	
						$resIfollow=$profileClass->doIFollow($resProfile[0]["id_profile"]);
						if($resIfollow["result"]){
						?>
						<div class="profileUserBtns">
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
						<div class="profileUserBtns">
							<button class="btn btn-red" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'">Follow</button>
						</div>
						<?php
						}
						?>
						
						<?php
						}
					}else{
						?>
						<div class="profileUserBtns btn-group">
							<a class="btn  btn-red dropdown-toggle" data-toggle="dropdown">Follow</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo WEB_URL; ?>login.php?go=<?php echo $resProfile[0]["username_profile"]; ?>">Login</a></li>
								<li><a href="<?php echo WEB_URL; ?>">Register</a></li>
							</ul>
						</div>
						<?php
					}
					if($resProfile[0]["id_profile"]==USER_ID){
						$onload.="
						$('#iHealthDetails').css('cursor','pointer');
						$('#iHealthDetails').click(function(){
							location.href='".WEB_URL."account/health';
						});";
					}
					?>
					<div id="iHealthDetails" class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_red clearfix">
							<h3>Health Status <?php echo $resProfile[0]["ifeel_profile"]; ?>/10</h3>
						</div>
						<div id="profileUserHealthStatus">
							<?php
							if($resProfile[0]["weight_profile"]!="0"){
							?>
							<div class="">
							Weight <span class="colorLighterBlue"><?php echo $resProfile[0]["weight_profile"]; ?></span> lb
							</div>
							<?php
							}
							if($resProfile[0]["feet_profile"]!="0"){
							?>
							<div class="">
							<?php 
							echo 'Height <span class="colorLighterBlue">'.$resProfile[0]["feet_profile"].'</span>\'';
							if($resProfile[0]["inch_profile"]!=0){ 
								echo '<span class="colorLighterBlue">'.(float)$resProfile[0]["inch_profile"].'</span>"';
							} 
							?>
							</div>
							<?php
							}
							?>
						</div>
					</div>
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
					<div id="iHealthGoals" class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_green clearfix">
							<h3>Health Goals</h3>
						</div>
						<div id="profileUserHealthGoals">
							<?php
							if($goals["result"]){
								foreach($goals as $keyG=>$valueG){
									if(is_int($keyG)){
										if(isset($availableGoals[$valueG["id_topic"]])){
											unset($availableGoals[$valueG["id_topic"]]);
										}
										echo '<div id="goal_'.$valueG["id_topic"].'"><a href="'.WEB_URL.$topicClass->pathSingular('g')."/".$valueG["url_topic"].'" class="goalNameClass">'.$valueG["name_topic"].'</a>';
										if($resProfile[0]["id_profile"]==USER_ID){
											echo ' <b>X</b>';	
										}
										echo '</div>';
									}
								}
							}
							if($resProfile[0]["id_profile"]==USER_ID){
								?>
								<div id="addGoalPre"><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader.gif" /></div>
								<div id="addGoalBtn" class="center" <?php if(count($availableGoals)==0){ echo 'style="display:none;"'; } ?>><button class="btn btn-blue">Add a Health Goal</button></div>
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
					$('#profileUserHealthGoals').delegate('div','mouseenter',function(){
						$(this).children('b').show();
					});
					$('#profileUserHealthGoals').delegate('div','mouseleave',function(){
						$(this).children('b').hide();
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
					$('#addGoalBtn').delegate('button','click',function(){ 
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
					$resDoc=$profileClass->getUserDoctorsFollowed($resProfile[0]["id_profile"]);
					if($resDoc["result"] || $resProfile[0]["id_profile"]==USER_ID){
						?>
					<div class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_gray clearfix">
							<h3>Doctors</h3>
						</div>
						
						<ul id="profileUserDoctors">
							<?php
							foreach($resDoc as $keyDoc=>$valueDoc){
								if(is_int($keyDoc)){
							?>
							<li id="doctor_<?php echo $valueDoc["id_profile"]; ?>">
							<?php
							if($valueDoc["image_profile"]!=""){
								$docImagePath=WEB_URL."img/profile/tb/".$valueDoc["image_profile"];
								$docImageAlt=$valueDoc["name_profile"];
							}else{
								$docImagePath=WEB_URL."inc/img/empty-avatar.png";
								$docImageAlt="No Image Avatar";
							}
							?>
							<a href="<?php echo WEB_URL.$valueDoc["username_profile"]; ?>"><img src="<?php echo $docImagePath; ?>" alt="<?php echo $docImageAlt; ?>" /></a>
							<a href="<?php echo WEB_URL.$valueDoc["username_profile"]; ?>" class="colorRed doctorNameClass"><?php echo $valueDoc["name_profile"]; ?></a><?php if($resProfile[0]["id_profile"]==USER_ID){ echo ' <b>X</b>'; } ?>
							</li>
							<?php
								}
							}
							?>
						</ul>
					<?php

					if($resProfile[0]["id_profile"]==USER_ID){
					$onload.="
					$('#profileUserDoctors').delegate('li','mouseenter',function(){
						$(this).children('b').show();
					});
					$('#profileUserDoctors').delegate('li','mouseleave',function(){
						$(this).children('b').hide();
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
					
					$onload.="$('#addDoctor').tokenInput('".WEB_URL."act/ajax/autoCompleteFullDoc.php', { hintText: 'Type the name of the Doctor', noResultsText: 'No doctor with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#addDoctor').tokenInput('clear'); }else{ addDocInline(item.id); } },
              resultsFormatter: function(item){ return '<li class=\"docDropDown clearfix\">' + '<img src=\"' + item.image + '\" />' + '<div class=\"docDropDownTxt\"><div class=\"docDropDownName\">' + item.name + '</div><div class=\"docDropDownLocation\">' + item.state + '</div></div></li>' }
               });";
              $onload.="
              	$('#addDocBtn a').click(function(){
              		$('#addDocBtn').hide();	
              		$('#addDoctor').tokenInput('clear');
              		$('#addDocInput').show();	
              		$('#addDocInput input').focus();
              		return false;
              	});
              ";
              $jsfunctions.="
              	function addDocInline(id){
              		$('#addDocInput').hide();
              		$('#addDocBtn').show();
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
						<div id="addDocBtn" class="center padding20">
						<a href="#" class="btn btn-blue">Add your doctors</a>
						</div>
						<div id="addDocInput" class="center padding20">
						<input type="text" id="addDoctor" style="text-align:left;" name="addDoctor" />
						</div>

					<?php
					}
					?>
					</div>
				
				<?php
				}
				?>					
				</div>
				<div id="profileUserHealth">
					<?php
					if($resProfile[0]["type_profile"]==1){
					
					$res = $topicClass->getAllUserFollowedFromTopic('m',$resProfile[0]["id_profile"]);
					?>
					<div class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_blue clearfix">
							<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/pill.png" alt="transparent white pill" /> Medications</h3>
						</div>
						<div id="iProfileBoxMed" class="profileUserHealthActive clearfix">
						<?php 
						if($res["result"]){
							$string="";
							foreach($res as $key=>$value){
								if(is_int($key)){
									echo "<span><a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('m')."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
									if($resProfile[0]["id_profile"]==USER_ID){
										echo " <b>X</b>";
									}
									echo "</span>";
								}
							}
						}else if($resProfile[0]["id_profile"]!=USER_ID){
							echo "<span>none</span>";
						}
						if($resProfile[0]["id_profile"]==USER_ID){
							echo "<div id=\"addM\" class=\"profileAddButton\"><button type=\"button\" class=\"btn btn-blue\">add</button></div>";
							$onload.="$('#addM').click(function(){ $(this).hide();$('#topicM').tokenInput('clear');$('#addMinput').show();$('#addMinput input').focus(); });";
							echo '<div id="addMinput" style="display:none;padding:10px;"><input type="text" id="topicM" name="topicM" /></div>';
							$needTokenInput=1;
							$onload.="$('#topicM').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=m', { hintText: 'Type the name of the medication', noResultsText: 'No medication with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicM').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addMinput'),$('#addM'),$('#iProfileBoxMed span'),$('#iProfileBoxMed')); } } });";
							
							$onload.="
								$('.profileUserHealthActive').delegate('span','mouseenter',function(){
									$(this).children('b').show();
								});
								$('.profileUserHealthActive').delegate('span','mouseleave',function(){
									$(this).children('b').hide();
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
							function addTopicInline(item,inputdiv,buttonspan,parentdiv,realparentdiv){
								inputdiv.hide();
								buttonspan.show();
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
					</div>
					<?php
					$res = $topicClass->getAllUserFollowedFromTopic('p',$resProfile[0]["id_profile"]);
					?>
					<div class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_brown clearfix">
							<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/inbed.png" alt="transparent white in bed" /> Procedures</h3>
						</div>
						<div id="iProfileBoxPro" class="profileUserHealthActive clearfix">
						<?php 
						if($res["result"]){
							$string="";
							foreach($res as $key=>$value){
								if(is_int($key)){
									echo "<span><a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('p')."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
									if($resProfile[0]["id_profile"]==USER_ID){
										echo " <b>X</b>";
									}
									echo "</span>";
								}
							}
						}else if($resProfile[0]["id_profile"]!=USER_ID){
							echo "<span>none</span>";
						}
						if($resProfile[0]["id_profile"]==USER_ID){
							echo "<div id=\"addP\" class=\"profileAddButton\"><button type=\"button\" class=\"btn btn-blue\">add</button></div>";
							$onload.="$('#addP').click(function(){ $(this).hide();$('#topicP').tokenInput('clear');$('#addPinput').show();$('#addPinput input').focus(); });";
							echo '<div id="addPinput" style="display:none;padding:10px;"><input type="text" id="topicP" name="topicP" /></div>';
							$needTokenInput=1;
							$onload.="$('#topicP').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=p', { hintText: 'Type the name of the procedure', noResultsText: 'No procedures with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicP').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addPinput'),$('#addP'),$('#iProfileBoxPro span'),$('#iProfileBoxPro')); } } });";
							
						}
						?>
						</div>
					</div>
					<?php
					$res = $topicClass->getAllUserFollowedFromTopic('d',$resProfile[0]["id_profile"]);
					?>
					<div class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_lightBlue clearfix">
							<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/heart.png" alt="transparent white heart" /> Conditions</h3>
						</div>
						<div id="iProfileBoxCon" class="profileUserHealthActive clearfix">
						<?php 
						if($res["result"]){
							$string="";
							foreach($res as $key=>$value){
								if(is_int($key)){
									echo "<span><a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('d')."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
									if($resProfile[0]["id_profile"]==USER_ID){
										echo " <b>X</b>";
									}
									echo "</span>";
								}
							}
						}else if($resProfile[0]["id_profile"]!=USER_ID){
							echo "<span>none</span>";
						}
						if($resProfile[0]["id_profile"]==USER_ID){
							echo "<div id=\"addC\" class=\"profileAddButton\"><button type=\"button\" class=\"btn btn-blue\">add</button></div>";
							$onload.="$('#addC').click(function(){ $(this).hide();$('#topicC').tokenInput('clear');$('#addCinput').show();$('#addCinput input').focus(); });";
							echo '<div id="addCinput" style="display:none;padding:10px;"><input type="text" id="topicC" name="topicC" /></div>';
							$needTokenInput=1;
							$onload.="$('#topicC').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=d', { hintText: 'Type the name of the condition', noResultsText: 'No conditions with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicC').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addCinput'),$('#addC'),$('#iProfileBoxCon span'),$('#iProfileBoxCon')); } } });";
							
						}
						?>
						</div>
					</div>
					<?php
					$res = $topicClass->getAllUserFollowedFromTopic('s',$resProfile[0]["id_profile"]);
					?>
					<div class="iBoxHeadingColoured">
						<div class="iBoxHeadingColouredHeadingWimage iBoxHeading_pink clearfix">
							<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/temperature.png" alt="transparent white temperature" /> Symptoms</h3>
						</div>
						<div id="iProfileBoxSim" class="profileUserHealthActive clearfix">
						<?php 
						if($res["result"]){
							$string="";
							foreach($res as $key=>$value){
								if(is_int($key)){
									echo "<span><a id=\"topic_".$value["id_topic"]."\" href=\"".WEB_URL.$topicClass->pathSingular('s')."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
									if($resProfile[0]["id_profile"]==USER_ID){
										echo " <b>X</b>";
									}
									echo "</span>";
								}
							}
						}else if($resProfile[0]["id_profile"]!=USER_ID){
							echo "<span>none</span>";
						}
						if($resProfile[0]["id_profile"]==USER_ID){
							echo "<div id=\"addS\" class=\"profileAddButton\"><button type=\"button\" class=\"btn btn-blue\">add</button></div>";
							$onload.="$('#addS').click(function(){ $(this).hide();$('#topicS').tokenInput('clear');$('#addSinput').show();$('#addSinput input').focus(); });";
							echo '<div id="addSinput" style="display:none;padding:10px;"><input type="text" id="topicS" name="topicS" /></div>';
							$needTokenInput=1;
							$onload.="$('#topicS').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=s', { hintText: 'Type the name of the symptom', noResultsText: 'No symptoms with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'profile',onAdd:function(item){ if(item.id==0){ $('#topicS').tokenInput('clear'); }else{ addTopicInline(item.id,$('#addSinput'),$('#addS'),$('#iProfileBoxSim span'),$('#iProfileBoxSim')); } } });";
							
						}
						?>
						</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
			<div id="profileUserBottom">
				<?php
				if(USER_ID!=0 && $resProfile[0]["id_profile"]!=USER_ID){
					if(!isset($needAutoGrow)){
						$needAutoGrow=1;
						$onload.="$('textarea').autogrow();";
					}
					?>
					<form class="iPost" id="postAbout" method="post" action="<?php echo WEB_URL; ?>act/post/postAbout.php?id=<?php echo $resProfile[0]["id_profile"]; ?>">
						<textarea placeholder="Share your health experience with <?php echo $resProfile[0]["username_profile"]; ?>" class="textArea100" name="txtPost" id="txtPost"></textarea>
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
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');