<?php
$pageTitle="About Us - HealthKeep";
$pageDescr="HealthKeep is a fully connected online community of people who care about their health and their health providers.";

$active="homepage";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Contact us</h2>
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
			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder],textarea[placeholder]').placeholder();";
			$token=sha1(microtime(true).mt_rand(10000,90000));
		    $_SESSION["token"]=$token;
			?>
			<form style="display:none;" class="marginBottom30 fullForm" id="contactForm" method="post" action="<?php echo WEB_URL; ?>act/contact.php">
				<div class="inputDiv">
					<input type="text" maxlength="100" placeholder="Full Name" name="name" id="name" />
					<div id="usernameErrorPro" class="formError"></div>
				</div>
				<div class="inputDiv">
					<input type="email" maxlength="150" placeholder="Email" name="email" id="reg_email" />
					<div id="emailError" class="formError"></div>
				</div>
				<div class="inputDiv">
					<textarea placeholder="Message" style="width:100%;height:150px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;" name="message" id="message"></textarea>
					<div id="messageError" class="formError"></div>
				</div>
				<div class="inputDiv hpRegisterBtn clearfix" style="padding-bottom:20px;">
					<input type="text" name="hpot" class="hpot" value="" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<input type="submit" class="btn submitBtn btn-blue" disabled value="Send" />
				</div>
				<hr style="padding-bottom:20px;" />
				<div>To reach our founder and CEO<br />Dr. Lyle Dennis send an email to<br />lyle at healthkeep dot com</div>
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
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');