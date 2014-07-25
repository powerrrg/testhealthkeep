<?php
require_once('../../engine/starter/config.php');

if(!isset($_GET["isvalid"])){
	go404();
}

onlyLogged();

$res=$userClass->getById(USER_ID);

if(!$res["result"]){
	go404();
}

$validated=true;
$userClass->validateEmail();

$designV1=1;

$pageTitle="Confirm your email - HealthKeep";
$pageDescr="Confirm your email";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15" style="max-width:300px;">
				<h1 class="colorBlue margin0">Confirmed Email</h1>
			</div>
			<div class="iFull iBoard2 margin20auto" style="max-width:300px;">
				<?php
				if($validated){
				?>
				<div class="alert alert-success center">
					Thank you!<br /><br />
					Your email was confirmed.<br /><br />
					<a href="<?php echo WEB_URL; ?>feed" class="btn btn-success">OK</a>
				</div>
				<?php
				}else{
				?>
				<div class="alert alert-error center">
					Something went wrong<br /><br />
					We could not confirm your email.<br /><br />
					<a href="<?php echo WEB_URL; ?>contact"  class="btn btn-red">Please, contact us</a>
				</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');