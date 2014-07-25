<?php

onlyLogged();


$pageTitle="HealthKeep - Social Health Network";
$pageDescr="HealthKeep is for secure and private storage of all your and your family's health records and connecting with doctors and those who share conditions and medications.  HealthKeep empowers you to organize and improve your health.";

require_once(ENGINE_PATH.'html/header.php');
$active="home";
//require_once(ENGINE_PATH.'html/bar.php');
?>
<div id="main" class="iHold clearfix">

	<div class="iBoard alert-block center" id="thankyou" style="padding:40px;">
		<div style="margin-bottom:20px;">
		<img src="<?php echo WEB_URL; ?>inc/img/v1/logo/HealthKeep.png" id="soonLogo" />
		</div>
		<h4>Thanks for registering</h4>
		<p style="padding-bottom:30px;border-bottom:1px solid #ccc;margin-bottom:30px;">We will notify you as soon as we launch.</p>
		<p>Please follow us and share with your friends:</p>
		<p style="margin-top:20px;">
		<a href="http://facebook.com/healthkeep"><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/facebook.png" class="socialIcon" /></a>
		<a href="http://twitter.com/health_keep" style="margin-left:20px;"><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/twitter.png"  class="socialIcon" /></a>
		</p>
	</div>
	
</div>
</body>
</html>
<?php
//require_once(ENGINE_PATH.'html/footer.php');