<?php
require_once('../engine/starter/config.php');

if(isset($_GET["go"])){
	$goTo="?go=".ltrim($_GET["go"],"/");
}else{
    $goTo="";
}

if(USER_ID!=0){
	if(isset($_GET["go"])){
		header("Location:".WEB_URL.ltrim($_GET["go"],"/"));
	}else{
		header("Location:".WEB_URL);
	}
	exit;
}

$pageTitle="Login - HealthKeep";
$pageDescr="Login to your HealthKeep account.";


$token=sha1(microtime(true).mt_rand(10000,90000));
$_SESSION["token"]=$token;

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');

?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Login</h2>
			<form id="mainLoginForm" method="post" action="<?php echo WEB_URL; ?>act/login.php<?php echo $goTo; ?>">
				<?php
				if(isset($_SESSION["emailDup"]) && $_SESSION["emailDup"]!=""){
					$emailDup=$_SESSION["emailDup"];
				?>
					<div class="alert alert-warning">
						The email <?php echo $emailDup; ?> is already registered, please login
					</div>
				<?php
					$_SESSION["emailDup"]="";
				}
				if(isset($_GET["email"])){
				?>
				<div class="alert alert-error">
					Unknown email address
				</div>
				<?php
				}else if(isset($_GET["password"])){
				?>
				<div class="alert alert-error">
					Invalid email/password combination
				</div>
				<?php
				}else if(isset($_GET["refresh"])){
				?>
				<div class="alert alert-error">
					We could not log you in. Please try again.
				</div>
				<?php
				}
				?>
				<input type="text" maxlength="150" class="input100" placeholder="Email" name="email" id="main_email" <?php if(isset($emailDup)){ echo 'value="'.$emailDup.'"'; } ?> /><br />
				<input type="password" maxlength="20" class="input100" placeholder="Password" name="password" id="main_password" />
				<div class="remenberMe clearfix"><input type="checkbox" name="remember" /> Remember Me</div>
				<input type="text" name="hpot" class="hpot" value="" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<input type="submit" value="Login" disabled class="btn btn-red submitBtn" />
			</form>
			<?php
			if(isset($emailDup)){
				$onload.="$('#main_password').focus();";
			}else{
				$onload.="$('#main_email').focus();";
			}
			$onload.="
				$('.submitBtn').prop('disabled', false);
				$('input[placeholder]').placeholder();
				$('#mainLoginForm').submit(function(){
					if(!isValidEmailAddress($('#main_email').val())){
						alert('Invalid email address');
						$('#main_email').focus();
						return false;
					}else if($('#main_password').val().length<5){
						alert('Password needs to have more than 5 characters');
						$('#main_password').focus();
						return false;
					}else{
						return true;
					}
				});";
			?>
			<p style="text-align:center;"><a href="<?php echo WEB_URL; ?>forgot.php" class="colorBlue">Forgot Password</a></p>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');