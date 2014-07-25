<form id="proRegister" method="post" class="fullSizeForm" action="">
	<h3 class="hpRegisterHeading"><span class="colorRed">Doctors</span> and <span class="colorRed">Healthcare Organizations</span> register here</h3>
	<div class="clearfix">
		<div class="hpRegisterProPos clearfix hpRegisterProPosLeft">
			<div class="inputDiv">
				<input type="text" maxlength="100" placeholder="Phone Number" name="phone" id="phone" />
				<div id="phoneErrorPro" class="formError"></div>
			</div>
			<div class="inputDiv">
				<input type="text" maxlength="100" placeholder="Full Name" name="name" id="name" />
				<div id="usernameErrorPro" class="formError"></div>
			</div>
			
		</div>
		<div class="hpRegisterProPos clearfix">
			<div class="inputDiv">
				<input type="email" maxlength="150" placeholder="Email" name="email" id="reg_emailPro" />
				<div id="emailErrorPro" class="formError"></div>
			</div>
			<div class="inputDiv">
				<input type="password" maxlength="20" placeholder="Password" name="password" id="reg_passwordPro" />
				<div id="passwordErrorPro" class="formError"></div>
			</div>
			
		</div>
		
	</div>
	<div class="clearfix">
		<div class="hpRegisterProPos clearfix hpRegisterProPosLeft hpRegisterProPosHack">
			
				<select name="regType" id="regType">
					<option value="">Select</option>
					<option value="doc">Doctor</option>
					<option value="fac">Healthcare Organization</option>
				</select>
				<div id="regErrorPro" class="formError"></div>

		</div>
		<div class="hpRegisterProPos clearfix">
			<div class="inputDiv" style="float:left;">
				<input type="text" name="hpot" class="hpot" value="" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<input type="submit" class="btn submitBtn btn-red" disabled value="Create Account" />
			</div>
		</div>
	</div>
	
</form>
<?php
$onload.="
$('#reg_emailPro').blur(function(){
	testEmailPro();
});
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
	$('#regType').blur(function(){
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
		}else if($('#regType').val()==''){
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
    $('#regType').change(function() {
    	$('#regErrorPro').hide();
    });
    $('#phone').keyup(function() {
    	$('#phoneErrorPro').hide();
    });
";

?>