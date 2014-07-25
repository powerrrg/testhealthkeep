<?php
require_once('../engine/starter/config.php');

if(USER_ID!=0){
	header("Location:".WEB_URL."feed");
	exit;
}
$designV1=1;

$pageTitle="Health Professionals Registration - HealthKeep";
$pageDescr="Registration system for medical doctors, health news providers and health organisations";

$token=sha1(microtime(true).mt_rand(10000,90000));
$_SESSION["token"]=$token;

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');

?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Professional Registration</h2>
			<form id="proRegister" method="post" class="fullSizeForm" action="">
				<h4 style="font-weight:normal;margin-bottom:20px;">Doctors and Healthcare Organizations</h4>
				<div class="inputDiv">
					<input type="text" class="input100" maxlength="100" placeholder="Full Name" name="name" id="name" />
					<div id="usernameErrorPro" class="formError"></div>
				</div>
				<div class="inputDiv">
					<input type="email" class="input100" maxlength="150" placeholder="Email" name="email" id="reg_emailPro" />
					<div id="emailErrorPro" class="formError"></div>
				</div>
				<div class="inputDiv">
					<input type="password" class="input100" maxlength="20" placeholder="Password" name="password" id="reg_passwordPro" />
					<div id="passwordErrorPro" class="formError"></div>
				</div>
				<div class="inputDiv">
					<input type="text" class="input100" maxlength="100" placeholder="Phone Number" name="phone" id="phone" />
					<div id="phoneErrorPro" class="formError"></div>
				</div>
				<div class="inputDiv">
					<select name="regType" id="regTypePro" style="width:100%;">
						<option value="">Select</option>
						<option value="doc">Doctor</option>
						<option value="fac">Healthcare Organization</option>
					</select>
					<div id="regErrorPro" class="formError"></div>
				</div>
				<div class="inputDiv">
					<input type="text" name="hpot" class="hpot" value="" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<input type="submit" class="btn submitBtn btn-blue" disabled value="Create Account" />
				</div>
			</form>
			<?php
			$onload.="
			$('#reg_emailPro').blur(function(){
				testEmailPro();
			});
			$('.submitBtn').prop('disabled', false);
			$('input[placeholder]').placeholder();
			";
			$jsfunctions.="
			var emailokPro=false;
			var emailkeyupPro=false;
			function testEmailPro(){
				if(isValidEmailAddress($('#reg_emailPro').val())){
					$.ajax({
						type: 'POST',
						url: '".WEB_URL."act/ajax/email.php',
						data: { email: $('#reg_emailPro').val() },
						success: function(data) {
							if(data=='ok'){
								emailokPro=true;
								$('#emailErrorPro').hide();
							}else if(data=='exists'){
								emailokPro=false;
								$('#emailErrorPro').show();
								$('#emailErrorPro').html('Email already registered. Please, use the login form above to enter your account.');
								if(!usernamekeyup){
									$('#reg_emailPro').keyup(function(){
										testEmailPro();
									});
									emailkeyupPro=true;
								}
							}
						}
					});
				}
			}
			";
			
			$onload.="
				$('#phone').blur(function(){
					var res=isValidPhone($('#phone').val());
					if(res){
						$('#phone').val(res);
					}
				});
				$('#regTypePro').blur(function(){
					if($(this).val()=='doc'){
						$('#proRegister').attr('action', '".WEB_URL."act/doc_register.php');
					}else if($(this).val()=='fac'){
						$('#proRegister').attr('action', '".WEB_URL."act/pro_register.php');
					}
				});
				$('#proRegister').submit(function(){
					if($('#name').val().length<5){
						$('#usernameErrorPro').show();
						$('#usernameErrorPro').html('Name needs to have more than 5 characters');
						$('#name').focus();
						return false;
					}else if(!isValidEmailAddress($('#reg_emailPro').val())){
						$('#emailErrorPro').show();
						$('#emailErrorPro').html('Invalid email address');
						$('#reg_emailPro').focus();
						return false;
					}else if($('#reg_passwordPro').val().length<5){
						$('#passwordErrorPro').show();
						$('#passwordErrorPro').html('Password needs to have more than 5 characters');
						$('#reg_passwordPro').focus();
						return false;
					}else if($('#regTypePro').val()==''){
						$('#regErrorPro').show();
						$('#regErrorPro').html('Please select a registration type');
						return false;
					}else if(!isValidPhone($('#phone').val())){
						$('#phoneErrorPro').show();
						$('#phoneErrorPro').html('Invalid phone number');
						$('#phone').focus();
						return false;
					}else if(!emailokPro){
						$('#emailErrorPro').show();
						$('#emailErrorPro').html('Invalid or duplicate email');
						$('#reg_emailPro').focus();
						return false;
					}else{
						return true;
					}
				});
				$('#name').keyup(function() {
			    	$('#usernameErrorPro').hide();
			    });
			    $('#reg_passwordPro').keyup(function() {
			    	$('#passwordErrorPro').hide();
			    });
			    $('#regTypePro').change(function() {
			    	$('#regErrorPro').hide();
			    });
			    $('#phone').keyup(function() {
			    	$('#phoneErrorPro').hide();
			    });
			";
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');