<?php
require_once('../engine/starter/config.php');

if(USER_ID!=0 && !isset($_GET["ok"])){
	header("Location:".WEB_URL);
	exit;
}

if(!isset($_GET["ok"])){
if(!isset($_GET["token"]) || !isset($_GET["url"])){
	go404();
}

$thetoken=trim(urlencode($_GET["token"]));

if($thetoken==""){
	go404();	
}

$url=trim(urlencode($_GET["url"]));

if($url==""){
	go404();	
}
}

$pageTitle="Set new password - HealthKeep";
$pageDescr="Set a new password for your HealthKeep account";

$active="account";

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');

$token=sha1(microtime(true).mt_rand(10000,90000));
$_SESSION["token"]=$token;
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Set new password</h2>
		<?php
		if(isset($_GET["ok"])){
		?>
		<div class="alert alert-success center">
			Your new password was successfully saved.
		</div>
		<a href="<?php echo WEB_URL; ?>feed" class="btn btn-blue">Continue</a>
		<?php
		}else{
			$res=$userClass->canChangePassword($url,$thetoken);
			if(!$res){
			?>
			<div class="alert alert-error">
				Your password retrieval has expired or is invalid. Please <a href="<?php echo WEB_URL; ?>forgot.php">try again</a>.
			</div>
			<?php
			}else{
			?>
			<form id="mainLoginForm" class="margin0" method="post" action="<?php echo WEB_URL; ?>act/forgot/set.php?url=<?php echo $url; ?>">
				<?php
				if(isset($_GET["error"])){
				?>
				<div class="alert alert-error">
					There was an error and we could not process your request. Please try again
				</div>
				<?php
				}
				?>
				<div class="alert alert-info">
					Please insert a new password below.
				</div>
				<input type="password" maxlength="20" class="input100" placeholder="Password" name="password" id="main_password" /><br />
				<input type="text" name="hpot" class="hpot" value="" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<input type="submit" value="Save" disabled class="btn btn-blue submitBtn" />
			</form>
			<?php
			$onload.="
				$('#main_email').focus();
				$('.submitBtn').prop('disabled', false);
				$('input[placeholder]').placeholder();
				$('#mainLoginForm').submit(function(){
					if($('#main_password').val().length<5){
						alert('Password needs to have more than 5 characters');
						$('#main_password').focus();
						return false;
					}else{
						return true;
					}
				});";
			}
		}
		?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');