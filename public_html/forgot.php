<?php
require_once('../engine/starter/config.php');

if(USER_ID!=0){
	header("Location:".WEB_URL);
	exit;
}

$pageTitle="Forgot Password - HealthKeep";
$pageDescr="Request a new password for your HealthKeep account";

$active="account";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');

$token=sha1(microtime(true).mt_rand(10000,90000));
$_SESSION["token"]=$token;
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Forgot Password</h2>
		<?php
		if(isset($_GET["ok"])){
		?>
		<div class="alert alert-success">
			An email was sent with the instructions to set up a new password
		</div>
		<?php
		}else{
		?>
		<form id="mainLoginForm" class="margin0" method="post" action="<?php echo WEB_URL; ?>act/forgot/request.php">
			<?php
			if(isset($_GET["email"])){
			?>
			<div class="alert alert-error">
				Unknown email address
			</div>
			<?php
			}else if(isset($_GET["error"])){
			?>
			<div class="alert alert-error">
				There was an error and we could not process your request. Please try again
			</div>
			<?php
			}else if(isset($_GET["time"])){
			?>
			<div class="alert alert-error">
				You made a request less than 10 minutes ago. Please check your spam folder and/or wait a few more minutes. If you still don't get the email, please try again in a few minutes.
			</div>
			<?php
			}
			?>
			<div class="alert alert-info">
				To request a new password, please insert your email below.
			</div>
			<input type="text" maxlength="150" class="input100" placeholder="Email" name="email" id="main_email" /><br />
			<input type="text" name="hpot" class="hpot" value="" />
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<input type="submit" value="Request" disabled class="btn btn-blue submitBtn" />
		</form>
		<?php
		$onload.="
			$('#main_email').focus();
			$('.submitBtn').prop('disabled', false);
			$('input[placeholder]').placeholder();
			$('#mainLoginForm').submit(function(){
				if(!isValidEmailAddress($('#main_email').val())){
					alert('Invalid email address');
					$('#main_email').focus();
					return false;
				}else{
					return true;
				}
			});";
		}
		?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');