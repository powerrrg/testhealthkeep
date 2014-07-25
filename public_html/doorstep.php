<?php
require_once('../engine/starter/config.php');

if(USER_ID!=0){
		header("Location:".WEB_URL);
	exit;
}

if(!isset($_POST["homeExperience"]) || !isset($_POST["hpot"]) || !isset($_POST["token"])){
	go404();
}

if($_POST["hpot"]!=""){
	go404();
}

if($_SESSION["token"]!=$_POST["token"]){
	go404();
}

$_SESSION["token"]="";


$experience=$_POST["homeExperience"];

if(strlen($experience)<5){
	go404();
}

if(isset($_POST["topic"])){
	$jsfunctions.="mixpanel.track('Doorstep topic enter');";
	$doorStepComplete="Doorstep topic complete";
}else if(isset($_POST["profile"])){
	$jsfunctions.="mixpanel.track('Doorstep profile enter');";
	$doorStepComplete="Doorstep profile complete";
}else{
	$jsfunctions.="mixpanel.track('Doorstep enter');";
	$doorStepComplete="Doorstep complete";
}

$designV1=1;

$pageTitle="Share your health experience - HealthKeep";
$pageDescr="Share your health experience to your HealthKeep account.";
require_once(ENGINE_PATH.'html/header.php');
$hideLogin=1;
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15" style="max-width:400px;">
				<h1 class="colorRed margin0">Share experience</h1>
			</div>
			<div class="iFull iBoard2 margin20auto" style="max-width:400px;">
				<div id="dsRes" class="center" style="margin-bottom:20px;display:none;">
					<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" />
					<div id="dsResText" class="colorGray" style="padding:10px;"></div>
				</div>
				<form id="mainLoginForm" class="margin0" method="post">
					
					<input type="text" maxlength="50" class="input100" placeholder="Choose a username" name="username" id="username" /> 
					<div id="username_error" class="alert alert-error" style="display:none;"></div>
					<input type="text" maxlength="150" class="input100" placeholder="Email" name="email" id="main_email" /><br />
					<input type="password" maxlength="20" class="input100" placeholder="Password" name="password" id="main_password" /><br />
					<select name="gender" id="gender" style="width:100%;">
						<option value="m">Male</option>
						<option value="f">Female</option>
					</select>
					<input type="hidden" name="experience" id="experience" value="<?php echo $experience; ?>" />
					<input type="button" value="Share" id="shareBtn" class="btn btn-blue" />
				</form>
				<?php
				$_SESSION["welcome"]=array("first"=>true);
				$onload.="$('#username').focus();";

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
									$('#username_error').hide();
								}else if(data=='exists'){
									usernameok=false;
									$('#username_error').html('Username already in use. Please, choose another.');
									$('#username_error').show();
									usernamekeyup=true;
								}
							}
						});
					}
				}
				";
				$onload.="
					$('#username,#main_email,#main_password').keypress(function(e){
						if (e.which == 13) {
					    $('#shareBtn').trigger('click');
					  }
					});
					$('input[placeholder]').placeholder();
					$('#shareBtn').click(function(){
						if($('#username').val().length<5){
							alert('The username needs to have more than 5 characters');
							$('#username').focus();
							return false;
						}else if(!usernameok){
							alert('That username is already in use');
							$('#username').focus();
							return false;
						}else if(!isValidEmailAddress($('#main_email').val())){
							alert('Invalid email address');
							$('#main_email').focus();
							return false;
						}else if($('#main_password').val().length<5){
							alert('Password needs to have more than 5 characters');
							$('#main_password').focus();
							return false;
						}else{
							$('#dsRes').show();
							$('#shareBtn').hide();
							$.ajax({
								type: 'POST',
								url: '".WEB_URL."act/ajax/doorstep.php',
								data: { email: $('#main_email').val(),password: $('#main_password').val(), gender: $('#gender').val(),experience: $('#experience').val(), username: $('#username').val()},
								success: function(data) {
									if(data=='error'){
										alert('There was an error and we could not share your experience, please try again later or contact us.');
										$('#dsRes').hide();
										$('#shareBtn').show();
									}else if(data=='invalidpassword'){
										alert('That email is already registered but the provided password does not match the one we have stored');
										$('#dsRes').hide();
										$('#shareBtn').show();
									}else{
										mixpanel.track('$doorStepComplete');
										if(data=='loggedin'){
											$('#dsResText').html('Welcome Back');
										}else{
											$('#dsResText').html('Welcome');
										}
										$.ajax({
											type: 'POST',
											url: '".WEB_URL."act/ajax/doorstep2.php',
											data: { experience: $('#experience').val()},
											success: function(data) {
												if(data=='error'){
													alert('There was an error and we could not share your experience, please try again later or contact us.');
													$('#dsRes').hide();
													$('#shareBtn').show();
												}else{
													finished();
												}
											}
										});
									}
								}
							});
						}
					});";
					if(isset($_POST["topic"])){
						$jsfunctions.="
							function finished(){
								$.ajax({
									type: 'POST',
									url: '".WEB_URL."act/ajax/doorstepTopic.php',
									data: { topic: '".$_POST["topic"]."'},
									success: function(data) {
										if(data=='error'){
											alert('There was an error and we could not share your experience, please try again later or contact us.');
											$('#dsRes').hide();
											$('#shareBtn').show();
										}else{
											location.href='".WEB_URL."feed';
										}
									}
								});
							}
						";

					}else if(isset($_POST["profile"])){
						$jsfunctions.="
							function finished(){
								$.ajax({
									type: 'POST',
									url: '".WEB_URL."act/ajax/doorstepProfile.php',
									data: { profile: '".$_POST["profile"]."'},
									success: function(data) {
										if(data=='error'){
											alert('There was an error and we could not share your experience, please try again later or contact us.');
											$('#dsRes').hide();
											$('#shareBtn').show();
										}else{
											location.href='".WEB_URL."feed';
										}
									}
								});
							}
						";

					}else{
						$jsfunctions.="
							function finished(){
								location.href='".WEB_URL."feed';
							}
						";
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');