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
		$jsfunctions.="mixpanel.track('User Privacy');";
	}
}

$pageTitle=$resProfile[0]["username_profile"]." - HealthKeep";
$pageDescr="HealthKeep profile page for the user ".$resProfile[0]["username_profile"].".";

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');

?>
<div id="main">
	<div class="iHold clearfix">
		<div id="userProfile" class="iBoard">
			<div class="iHeading iFull">
				<h1 class="iHeadingText  colorBlue">Privacy Notice</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<div id="privacyMain">
					<form style="margin:0 0 20px;" id="privacyForm" enctype="multipart/form-data" action="<?php echo WEB_URL; ?>act/account/privacy.php" method="POST">
						<div class="iBoard2 marginBottom20 margintop0">
							<h3 class="colorRed center margintop0">Be aware of your privacy! Do not use your real name.</h3>
							<div class="center">
								<input type="text" maxlength="50" placeholder="Username" name="username" id="username" value="<?php echo $resProfile[0]["username_profile"]; ?>" />
								<div id="usernameError" class="formError"></div>
							</div>
							<?php
							$onload.="$('#username').focus();";
							?>
						</div>
						
						<div class="iBoard2" style="color:#666;">
						<p><span class="colorBlue bold">Health</span><span class="colorRed bold">Keep</span> takes your privacy very seriously. To enable social sharing of private health matters HealthKeep requires all its members to remain anonymous and shows no specific personally identifiable information publicly.
					</p><p>We recommend you keep your screen name very non-specific so no one can figure out who you are.  Don't use your whole name or your last name or use screen names you've used on many other sites.
					</p><p>You can change your screen name now. While you may use your picture for your profile, keep in mind someone may recognize you.  Either choose your own custom photo that does not identify you, or choose a default avatar from below.
					</p>
						</div>
						<div class="iBoard2">
							<ul id="imageChoose" class="clearfix">
								<?php 
								if($resProfile[0]["gender_profile"]=="f"){
									$gender="woman";
								}else{
									$gender="man";
								}
								?>
								<h3 class="colorBlue" style="margin:0 10px 10px 10px;margin-top:0;">Choose an avatar</h3>
								<li class="imageChooseHolder active">
									<input type="radio" checked name="theimage" value="1" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>1.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="2" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>2.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="3" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>3.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="4" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>4.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="5" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>5.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="6" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>6.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="7" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>7.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="8" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>8.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="9" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>9.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="10" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>10.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="11" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>11.jpg" />
								</li>
								<li class="imageChooseHolder">
									<input type="radio" name="theimage" value="12" />
									<img src="<?php echo WEB_URL; ?>inc/img/avatar/<?php echo $gender; ?>12.jpg" />
								</li>
								<h3 class="colorBlue" style="padding:20px 10px 10px;clear:both;margin-bottom:0">Or upload your own image</h3>
								<li id="imageChooseUpload" class="imageChooseHolder">
									<input type="radio" name="theimage" value="99" />
									<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/plus100.png" style="border:1px solid #d8d9da;width:88px;height:88px;" />
								  
									
									<?php
									$onload.="$('#imageChooseUpload').click(function(){ $('#avatarFile').click(); });";
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
									  		}
									  	}
									});";
											?>
									</li>
									<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload" style="display:none;">
										<span class="btn-file" style="display:inline-block"><span class="fileupload-new"></span>
										<input type="file" name="avatarFile" id="avatarFile" /></span>
									</div>
									  <?php
									  $onload.="
										$('.imageChooseHolder').click(function(){
											var inChild=$(this).find('input');
											if(inChild.prop('checked')){
												inChild.prop('checked', false);
											}else{
												inChild.prop('checked', true);
											}
											$('.imageChooseHolder').removeClass('active');
											$('input[name=theimage]:checked', '#privacyForm').parent().addClass('active');
											
										});
									  ";
									  ?>
							</ul>
						</div>
						<div class="padding20">
							<p>
								<input type="checkbox" value="1" name="accepted" id="accepted" /> I agree with HealthKeep <a href="<?php echo WEB_URL; ?>tos" style="font-weight:bold;text-decoration:underline;" target="_blank">Terms of Use</a>
							</p>
							<input type="submit" value="save" disabled class="btn btn-large btn-red submitBtn" />
						</div>
					</form>
					<?php
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
							$('#username').focus();
							return false;
						}else if(!usernameok){
							$('#usernameError').show();
							$('#usernameError').html('Invalid or duplicate username');
							$('#username').focus();
							return false;
						}else if(($('input[name=theimage]:checked', '#privacyForm').val()==99 && $('#avatarFile').val()=='') || 
						($('input[name=theimage]:checked', '#privacyForm').val()==undefined && $('#avatarFile').val()=='') || 
						($('input[name=theimage]:checked', '#privacyForm').val()<1 && $('#avatarFile').val()=='') || 
						($('input[name=theimage]:checked', '#privacyForm').val()>12 && $('#avatarFile').val()=='')){
							alert('You need to choose an avatar or upload your own');
							$('html, body').animate({ scrollTop: $('#imageChoose').offset().top }, 1000);
							return false;
						}else if(!$('#accepted').is(':checked')){
							alert('You need to agree with the terms of use');
							$('#accepted').focus();
							return false;
						}else{
							$('.submitBtn').prop('disabled', true);
							return true;
						}
					});";
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if(defined(USER_TYPE) || USER_TYPE<5){
	require_once(ENGINE_PATH."mx/signup.php");
}
require_once(ENGINE_PATH.'html/footer.php');