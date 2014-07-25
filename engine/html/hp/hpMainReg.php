<form id="homeRegister" method="post" action="<?php echo WEB_URL; ?>act/register.php">
	<h3 class="hpRegisterHeading"><span class="colorBlue">Register Here</span> - Learn & Share about Your Health </h3>
	<div class="clearfix">
		<div class="hpRegisterPos clearfix hpRegisterPosLeft">
			<div class="inputDiv">
				<input type="text" maxlength="50" placeholder="Username" name="username" id="username" />
				<div id="usernameError" class="formError"></div>
			</div>
			
			<div class="inputDiv">
				<input type="password" maxlength="20" placeholder="Password" name="password" id="reg_password" />
				<div id="passwordError" class="formError"></div>
			</div>
		</div>
		<div class="hpRegisterPos clearfix">
			<div class="inputDiv">
				<input type="email" maxlength="150" placeholder="Email" name="email" id="reg_email" />
				<div id="emailError" class="formError"></div>
			</div>
			<div class="inputDiv genderInput marginTop20">
				<input type="radio" name="gender" class="marginLeft10" id="genderMale" value="m"> Male
				<input type="radio" name="gender" class="marginLeft20" id="genderFemale" value="f"> Female
				<div id="genderError" class="formError"></div>
			</div>
		</div>
	</div>
	<div class="inputDiv hpRegisterBtn">
		<input type="text" name="hpot" class="hpot" value="" />
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<input type="submit" class="btn submitBtn btn-blue" disabled value="Create Account" />
	</div>
</form>
<?php
$onload.="
	$('#username').blur(function(){
		testUsername();
	});
";
$jsfunctions.="
var usernameok=false;
var usernamekeyup=false;
function testUsername(){
	if($('#username').val().length>4){
		$.ajax({
			type: 'POST',
			url: '".WEB_URL."act/ajax/username.php',
			data: { username: $('#username').val() },
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
$onload.="
	$('#reg_email').blur(function(){
		testEmail();
	});
";
$jsfunctions.="
var emailok=false;
var emailkeyup=false;
function testEmail(){
	if(isValidEmailAddress($('#reg_email').val())){
		$.ajax({
			type: 'POST',
			url: '".WEB_URL."act/ajax/email.php',
			data: { email: $('#reg_email').val() },
			success: function(data) {
				if(data=='ok'){
					emailok=true;
					$('#emailError').hide();
				}else if(data=='exists'){
					emailok=false;
					$('#emailError').show();
					$('#emailError').html('Email already registered. Please, use the login form above to enter your account.');
					if(!emailkeyup){
						$('#reg_email').keyup(function(){
							testEmail();
						});
						emailkeyup=true;
					}
				}
			}
		});
	}
}
";

if(!$jsTopFormIsSet){
	$onload.="$('.submitBtn').prop('disabled', false);
			$('input[placeholder]').placeholder();";
}
$onload.="
	$('#homeRegister').submit(function(){
		if($('#username').val().length<5){
			$('#usernameError').show();
			$('#usernameError').html('Username needs to have more than 5 characters');
			$('#username').focus();
			return false;
		}else if(!isValidEmailAddress($('#reg_email').val())){
			$('#emailError').show();
			$('#emailError').html('Invalid email address');
			$('#reg_email').focus();
			return false;
		}else if($('#reg_password').val().length<5){
			$('#passwordError').show();
			$('#passwordError').html('Password needs to have more than 5 characters');
			$('#reg_password').focus();
			return false;
		}else if($('#genderMale').is(':not(:checked)') && $('#genderFemale').is(':not(:checked)')){
			$('#genderError').show();
			$('#genderError').html('Please select the gender');
			return false;
		}else if(!usernameok){
			$('#usernameError').show();
			$('#usernameError').html('Invalid or duplicate username');
			$('#username').focus();
			return false;
		}else if(!emailok){
			$('#emailError').show();
			$('#emailError').html('Invalid or duplicate email');
			$('#reg_email').focus();
			return false;
		}else{
			return true;
		}
	});
	$('#username').keyup(function() {
		this.value = this.value.toLowerCase();
        if (this.value.match(/[^a-zA-Z0-9\_\-]/g)) {
            this.value = this.value.replace(/[^a-zA-Z0-9\_\-]/g, '');
        }
        $('#usernameError').hide();
    });
    $('#reg_password').keyup(function() {
    	$('#passwordError').hide();
    });
    $('#genderMale,#genderFemale').change(function() {
    	$('#genderError').hide();
    });
";

?>