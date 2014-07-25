<?php
$pageTitle="Contact - HealthKeep";
$pageDescr="Use this page if you have any question, suggestion or just want to talk to us.";

$designV1=1;
$active="homepage";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iFull">
				<div id="okAlert" class="alert alert-success" style="margin:100px auto 250px;text-align:center;display:none;">
					Message successfully sent!<br /><br />
					We will get in touch as soon as possible.
				</div>
				<div id="errorAlert" class="alert alert-error" style="margin:100px auto 250px;text-align:center;display:none;">
					There was a problem sending your message<br /><br />
					Please email us at <a href="mailto:info@healthkeep.org">info@healthkeep.org</a>.
				</div>
				<?php
				$onload.="
				if(getHash()=='ok'){
					$('#okAlert').show();
				}else if(getHash()=='error'){
					$('#errorAlert').show();
				}else{
					$('#contactForm').show();
				}
				";
				if(!$jsTopFormIsSet){
					$onload.="$('.submitBtn').prop('disabled', false);
							$('input[placeholder],textarea[placeholder]').placeholder();";
					$token=sha1(microtime(true).mt_rand(10000,90000));
				    $_SESSION["token"]=$token;
				}
				?>
				<form style="display:none;" class="marginBottom30 fullForm" id="contactForm" method="post" action="<?php echo WEB_URL; ?>act/contact.php">
					<h1 class="colorRed">Contact us</h1>
					<div class="inputDiv">
						<input type="text" maxlength="100" placeholder="Full Name" name="name" id="name" />
						<div id="usernameErrorPro" class="formError"></div>
					</div>
					<div class="inputDiv">
						<input type="email" maxlength="150" placeholder="Email" name="email" id="reg_email" />
						<div id="emailError" class="formError"></div>
					</div>
					<div class="inputDiv">
						<textarea placeholder="Message" name="message" id="message"></textarea>
						<div id="messageError" class="formError"></div>
					</div>
					<div class="inputDiv hpRegisterBtn">
						<input type="text" name="hpot" class="hpot" value="" />
						<input type="hidden" name="token" value="<?php echo $token; ?>" />
						<input type="submit" class="btn submitBtn btn-blue" disabled value="Send" />
					</div>
					<hr class="marginTop20" />
					<div class="alert alert-info center marginTop20">To reach our founder and CEO<br />Dr. Lyle Dennis send an email to<br />lyle at healthkeep dot com</div>
				</form>
				<?php
				$onload.="
				$('#contactForm').submit(function(){
					if($('#name').val().length<3){
						$('#usernameErrorPro').show();
						$('#usernameErrorPro').html('Name needs to have more than 3 characters');
						$('#name').focus();
						return false;
					}else if(!isValidEmailAddress($('#reg_email').val())){
						$('#emailError').show();
						$('#emailError').html('Invalid email address');
						$('#reg_email').focus();
						return false;
					}else if($('#message').val().length<5){
						$('#messageError').show();
						$('#messageError').html('Message needs to have more than 5 characters');
						$('#message').focus();
						return false;
					}else{
						return true;
					}
				});
				$('#name').keyup(function() {
			    	$('#usernameErrorPro').hide();
			    });
			    $('#reg_email').keyup(function() {
			    	$('#emailError').hide();
			    });
			    $('#message').keyup(function() {
			    	$('#messageError').hide();
			    });
				";
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');