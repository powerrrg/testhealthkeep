<?php

onlyLogged();

if(PROFILE_TYPE!=1){
	go404();
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}

$active="myProfile";

if(defined(USER_TYPE) || USER_TYPE<5){
	if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==1){
		$jsfunctions.="mixpanel.track('User OneStep');";
	}
}

$pageTitle=$resProfile[0]["username_profile"]." - HealthKeep";
$pageDescr="HealthKeep profile page for the user ".$resProfile[0]["username_profile"].".";

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');

?>
<div id="main">
	<div class="iHold">
		<div class="iBoard clearfix">
			
			<?php
			if(defined(USER_TYPE) || USER_TYPE<5){
				if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==1){
					//$jsfunctions.="mixpanel.track_forms('#privacyForm', 'User Finished OneStep');";
				}
			}
			?>
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<b>Create your health profile below.</b> Please be as complete as you can so we can give you the most personalized health information and connections as possible.
			</div>
			<?php
			$needAlert=1;
			$onload.="$('.alert').alert();";
			?>
			<form id="privacyForm" action="<?php echo WEB_URL; ?>act/account/onestep.php" method="POST" class="addEventForm borderTop">
			
			<div class="addEventFormItem clearfix">
				<div id="usernameError" class="addEventFormError"></div>
				<h4>Username (do not use real name)</h4>
				<div class="addEventFormInputs">
					<input type="text" maxlength="50" placeholder="Username" name="username" id="username" value="<?php echo $resProfile[0]["username_profile"]; ?>" /> 
					<?php
					$onload.="$('#username').focus();";
					?>
					
				</div>
				
			</div>
			<div class="addEventFormItem clearfix">
				<div id="passwordError" class="addEventFormError"></div>
				<h4>Password</h4>
				<div class="addEventFormInputs">
					<input type="password" maxlength="50" placeholder="Choose Password" name="password" id="password" value="" /> 		
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Gender</h4>
				<div class="addEventFormInputs">
					<?php $jsfunctions.="var currentGender='m';"; ?>
				<select id="yourGender" name="gender">
					<option value="m" <?php if($resProfile[0]["gender_profile"]=="m"){ echo "selected";} ?>>Male</option>
					<option value="f" <?php if($resProfile[0]["gender_profile"]=="f"){ echo "selected";} ?>>Female</option>
				</select>
					<?php
					$onload.="
						$('#yourGender').change(function(){
							if(currentGender!=$(this).val()){
								if($(this).val()=='f'){
									var thisGender='woman';
									currentGender='f';
								}else{
									var thisGender='man';
									currentGender='m';
								}
								var i=1;
								while(i<=12){
									$('#avatarImg'+i).attr('src','".WEB_URL."inc/img/avatar/'+thisGender+i+'.jpg');
									i++;
								}
							}
						});
					";
					?>
				</div>
			</div>
			<?php
			$prepopulateWd="";
			$prepopulateWm="";
			$prepopulateWs="";
			if(isset($_SESSION["welcome"]) && isset($_SESSION["welcome"]["location"])
			 && isset($_SESSION["welcome"]["id"]) && $_SESSION["welcome"]["location"]=="topic"){
			 	if(!isset($topicClass)){
				 	require_once(ENGINE_PATH.'class/topic.class.php');
				 	$topicClass=new Topic();
			 	}
				$restop=$topicClass->getById((int)$_SESSION["welcome"]["id"]);
				if($restop["result"]){
					if($restop[0]["type_topic"]=="d"){
						$prepopulateWd=',prePopulate: [{id: "'.$restop[0]["id_topic"].'", name: "'.$restop[0]["name_topic"].'"}]';
					}else if($restop[0]["type_topic"]=="m"){
						$prepopulateWm=',prePopulate: [{id: "'.$restop[0]["id_topic"].'", name: "'.$restop[0]["name_topic"].'"}]';
					}else if($restop[0]["type_topic"]=="s"){
						$prepopulateWs=',prePopulate: [{id: "'.$restop[0]["id_topic"].'", name: "'.$restop[0]["name_topic"].'"}]';
					}
				}
			}
			?>
			<div class="addEventFormItem clearfix">
				<h4>Enter your medical conditions/diagnoses</h4>
				<div class="addEventFormInputs">
				<input type="text" id="condition" name="condition" />
				</div>
				<?php
				$needTokenInput=1;
				$onload.="$('#condition').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=d', { hintText: 'Type the name of the condition', noResultsText: 'No condition with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true,onAdd:function(item){ pushFooterDown(); }, theme:'long' $prepopulateWd});";
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Enter any medications you take</h4>
				<div class="addEventFormInputs">
				<input type="text" id="meds" name="meds" />
				</div>
				<?php
				$onload.="$('#meds').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=m', { hintText: 'Type the name of the medication', noResultsText: 'No medication with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true,onAdd:function(item){ pushFooterDown(); }, theme:'long' $prepopulateWm});";
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Enter any symptoms you currently have</h4>
				<div class="addEventFormInputs">
				<input type="text" id="symptoms" name="symptoms" />
				</div>
				<?php
				$onload.="$('#symptoms').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=s', { hintText: 'Type the name of the symptom', noResultsText: 'No symptom with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true,onAdd:function(item){ pushFooterDown(); }, theme:'long' $prepopulateWs});";
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Enter your doctors' names here</h4>
				<div class="addEventFormInputs">
				<input type="text" id="docs" name="docs" />
				</div>
				<?php
				$prepopulateWdoc="";
				if(isset($_SESSION["welcome"]) && isset($_SESSION["welcome"]["location"])
				 && isset($_SESSION["welcome"]["id"]) && $_SESSION["welcome"]["location"]=="profile"){
					$resDoc=$profileClass->getById((int)$_SESSION["welcome"]["id"]);
					if($resDoc["result"]){
						if($resDoc[0]["type_profile"]==2){
							$prepopulateWdoc=',prePopulate: [{id: "'.$resDoc[0]["id_profile"].'", name: "'.$resDoc[0]["name_profile"].'"}]';
						}
					}
				}
				$onload.="$('#docs').tokenInput('".WEB_URL."act/ajax/autoCompleteDoc.php', { hintText: 'Type the name of the Doctor', noResultsText: 'No doctor with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true,onAdd:function(item){ pushFooterDown(); }, theme:'long' $prepopulateWdoc});";
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Do you have any of these health goals?</h4>
				
				<div class="addEventFormInputs" style="line-height:1.4em;padding:10px 20px;">
				
				<input type="checkbox" name="goal_lose-weight" value="lose-weight"> Lose Weight <br />

				<input type="checkbox" name="goal_quit-smoking" value="quit-smoking"> Quit Smoking <br />

				<input type="checkbox" name="goal_exercise-more" value="exercise-more"> Exercise More <br />

				<input type="checkbox" name="goal_reduce-stress" value="reduce-stress"> Reduce Stress 
				</div>
			</div>
			<input type="hidden" value="US" name="country" />
			<div class="addEventFormButtons clearfix">
				<input type="submit" value="Save" disabled class="btn btn-large btn-red submitBtn" />
				<a href="<?php echo WEB_URL; ?>tos" class="addEventFormTerms" target="_blank">Clicking the save button you state that you agree with the Terms of Use</a>
			</div>
		</form>		
					
		<?php
		$_SESSION["welcome"]=array("first"=>true);
		$onload.="
			$('#username').keyup(function() {
				this.value = this.value.toLowerCase();
		        if (this.value.match(/[^a-zA-Z0-9\_\-]/g)) {
		            this.value = this.value.replace(/[^a-zA-Z0-9\_\-]/g, '');
		        }
		        testUsername();
		    });
			$('#username').change(function(){
				testUsername();
			});
		";
		if(!$jsTopFormIsSet){
			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";
		}
		$onload.="testUsername();";
		$jsfunctions.="
		var usernameok=true;
		var usernamekeyup=false;
		function testUsername(){
			if($('#username').val().length>4){
				$.ajax({
					type: 'POST',
					cache: false,
					url: '".WEB_URL."act/ajax/username.php',
					data: { username: $('#username').val(), notme:'1' },
					success: function(data) {
						if(data=='ok'){
							usernameok=true;
							$('#usernameError').hide();
						}else if(data=='exists'){
							usernameok=false;
							$('#usernameError').show();
							$('#usernameError').html('Username already in use. Please, choose another.');
							if(!usernamekeyup){
								$('#username').keyup(function(){
									testUsername();
								});
								usernamekeyup=true;
							}
						}
					}
				});
			}
		}
		";
		$onload.="$('#privacyForm').submit(function(){
			if($('#username').val().length<5){
				$('#usernameError').show();
				$('#usernameError').html('Username needs to have more than 5 characters');
				$('html, body').animate({
				         scrollTop: $('#usernameError').offset().top
				     }, 200);
				$('#username').focus();
				return false;
			}else if(!usernameok){
				$('#usernameError').show();
				$('#usernameError').html('Invalid or duplicate username');
				$('html, body').animate({
				         scrollTop: $('#usernameError').offset().top
				     }, 200);
				$('#username').focus();
				return false;
			}else if($('#password').val().length<5){
				$('#passwordError').show();
				$('#passwordError').html('Password needs to have more than 5 characters');
				$('html, body').animate({
				         scrollTop: $('#passwordError').offset().top
				     }, 200);
				$('#password').focus();
				return false;
			}else{
				$('.submitBtn').prop('disabled', true);
				return true;

			}
		});";
		$onload.="
		$('#password').change(function(){ passwordChecks(); });$('#password').keyup(function(){ passwordChecks(); });
		";
		$jsfunctions.="function passwordChecks(){
			if($('#password').val().length>=5){
				$('#passwordError').hide();
			}
		}";
		$onload.="
		$('#username').change(function(){ usernameChecks(); });$('#username').keyup(function(){ usernameChecks(); });
		";
		$jsfunctions.="function usernameChecks(){
			if($('#username').val().length>=5){
				$('#usernameError').hide();
			}
		}";
		?>
		</div>
	</div>
</div>
<?php
if(defined(USER_TYPE) || USER_TYPE<5){
	require_once(ENGINE_PATH."mx/signup.php");
	if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==2){
		$_SESSION["mx_signup"]=0;
	}
}
require_once(ENGINE_PATH.'html/footer.php');
