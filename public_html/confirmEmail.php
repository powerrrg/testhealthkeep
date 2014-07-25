<?php
require_once('../engine/starter/config.php');

if(!isset($_GET["t"])){
	go404();
}

$token=$_GET["t"];

onlyLogged();

$res=$userClass->getById(USER_ID);

if(!$res["result"]){
	go404();
}

if($res[0]["confirmed_email_user"]!=0){
	header("Location:".WEB_URL."feed");
	exit;
}else if($res[0]["token_email_user"]==$token){
	$validated=true;
	$userClass->validateEmail();
}else{
	$validated=false;
}

$designV1=1;

$pageTitle="Confirm your email - HealthKeep";
$pageDescr="Confirm your email";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');

?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Email Confirmation</h2>
				<?php
				if($validated){
				?>
				<div class="alert alert-success center">
					Thank you!<br /><br />
					Your email was confirmed.
				</div>
				<a href="<?php echo WEB_URL; ?>feed" class="btn btn-blue">Continue</a>
				<?php
				}else{
				?>
				<div class="alert alert-error center">
					Something went wrong<br /><br />
					We could not confirm your email.
				</div>
				<a href="<?php echo WEB_URL; ?>contact"  class="btn btn-red">Please, contact us</a>
				<?php
				}
				?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');