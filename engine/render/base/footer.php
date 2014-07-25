<footer>
	<hgroup id="footer" class="iWrap">
		<div id="footerLinks">
			<?php /*<a href="#">Privacy</a> | */ ?>
			<a href="<?php echo WEB_URL; ?>tos">Terms of Use</a> |
			<a href="<?php echo WEB_URL; ?>conditions">Conditions</a> | 
			<a href="<?php echo WEB_URL; ?>medications">Medications</a> | 
			<a href="<?php echo WEB_URL; ?>procedures">Procedures</a> | 
			<a href="<?php echo WEB_URL; ?>symptoms">Symptoms</a> | 
			<a href="<?php echo WEB_URL; ?>doctors">Doctors</a>
		</div>
		<div id="footerAppLinks">
			<a href="https://itunes.apple.com/us/app/healthkeep/id722890218" target="_blank"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/appstore.png" alt="Available on the App Store" /></a>
		</div>
		<div id="footerRights">
			HealthKeep - All rights reserved ©<?php echo date('Y'); ?> - <a href="https://plus.google.com/107208560957097523537" rel="publisher">Google+</a>
		</div>
	</hgroup>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>!window.jQuery && document.write('<script src="<?php echo WEB_URL; ?>inc/js/jquery-1.8.3.js"><\/script>')</script>
<script src="<?php echo WEB_URL; ?>inc/js/jquery.placeholder.min.js"></script>
<script src="<?php echo WEB_URL; ?>inc/js/functions.js?v016"></script>
<?php
if(isset($needTokenInput)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.tokeninput.1.6.1.js?v002"></script>
<?php
}
if(isset($needFormTrack)){
$onload.="$('body').analyticsEventTracking();";
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.analytics-event-tracking.min.js"></script>
<?php
}
if(isset($needNumeric)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.numeric.js"></script>
<?php
}
if(isset($needFupload)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.fupload.js"></script>
<?php
}
if(isset($needPanelMenu)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.jpanelmenu.min.js"></script>
<?php
}
if(isset($needTypeAhead)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-typeahead_tcrosen.js"></script>
<?php
}
if(isset($needGudbergur)){
?>
<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/gudbergur/bootstrap-typeahead.js"></script>
<?php	
}

if(isset($needOldTypeAhead)){
?>
<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-typeahead.js"></script>
<?php
}
if(isset($needNewTypeAhead)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/typeahead.js/bloodhound.js"></script>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/typeahead.js/typeahead.bundle.min.js"></script>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/typeahead.js/typeahead.jquery.js"></script>
<?php
}

if(isset($needAlert)){
$onload.="$('.alert').alert()";
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-alert.js"></script>
<?php
}
if(isset($starRaty)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/raty/jquery.raty.min.js"></script>
<?php
}
if(isset($needAutoGrow)){
?>
<script src="<?php echo WEB_URL; ?>inc/js/jquery.autogrow-textarea.js"></script>
<?php	
}
if(isset($needScrollTo)){
?>
<script src="<?php echo WEB_URL; ?>inc/js/jquery.scrollTo-1.4.3.1-min.js"></script>
<?php	
}

$onload.="$('.dropdown-toggle').dropdown();";
if(!isset($dontPushFooter)){
$onload.="pushFooterDown();";
}
//hack to push down iOS app - http://coenraets.org/blog/2013/09/phonegap-and-cordova-with-ios-7/
$onload.="if (navigator.userAgent.indexOf('iPhone OS ') != -1 && navigator.userAgent.indexOf('Safari') == -1) {
			var hkapp_temp1 = navigator.userAgent.split('iPhone OS ');
			var hkapp_temp2 = hkapp_temp1[1].split(' ');
			var hkapp_temp3 = hkapp_temp2[0].split('_');
			var hkapp_version = parseInt(hkapp_temp3[0]);
			if (hkapp_version >= 7) {
				document.body.style.marginTop = '20px';
				$('body').append('<div style=\"background-color:#fff;height:20px;width:100%;position:fixed;top:0;left:0;\"></div>');
			}
		}
		else if (navigator.userAgent.indexOf('iPad') != -1 && navigator.userAgent.indexOf('Safari') == -1) {
			var hkapp_temp1 = navigator.userAgent.split('CPU OS ');
			var hkapp_temp2 = hkapp_temp1[1].split(' ');
			var hkapp_temp3 = hkapp_temp2[0].split('_');
			var hkapp_version = parseInt(hkapp_temp3[0]);
			if (hkapp_version >= 7) {
				document.body.style.marginTop = '30px';
				$('body').append('<div style=\"background-color:#fff;height:30px;width:100%;position:fixed;top:0;left:0;\"></div>');
			}
		}";
?> 
<script>
$(document).ready(function() { <?php echo preg_replace("/\s+/", " ", $onload); ?> });
</script>
<?php
if(defined(USER_TYPE) || USER_TYPE<5){
	if(USER_ID!=0 && !isset($justSignedUp)){
	
	if(!isset($_SESSION["mx_name_tag"]) || (isset($_SESSION["mx_name_tag"]) && $_SESSION["mx_name_tag"]!=1)){
	
		if(!isset($resProfile) || 
			(isset($resProfile) && isset($resProfile[0]["id_profile"]) && $resProfile[0]["id_profile"]!=USER_ID) || 
			(isset($resProfile) && 
				(!isset($resProfile[0]["id_profile"]) || !isset($resProfile[0]["username_profile"]) || !isset($resProfile[0]["type_profile"]) || !isset($resProfile[0]["created_profile"]))
			)
		){
			if(!isset($profileClass)){
				require_once(ENGINE_PATH.'class/profile.class.php');
				$profileClass=new Profile();
			}
			$resProfile=$profileClass->getById(USER_ID);
		}
		$jsfunctions.="mixpanel.track('Logged in');";
		$jsfunctions.="mixpanel.register({'Username':'".$resProfile[0]["username_profile"]."','Account Type':'".$resProfile[0]["type_profile"]."','Creation Date':'".$resProfile[0]["created_profile"]."'});";
		$jsfunctions.="mixpanel.people.set({'Username':'".$resProfile[0]["username_profile"]."','Account Type':'".$resProfile[0]["type_profile"]."','Creation Date':'".$resProfile[0]["created_profile"]."'});";
		$jsfunctions.="mixpanel.name_tag('".$resProfile[0]["username_profile"]."');";
		$_SESSION["mx_name_tag"]=1;
		
	}
	
	$jsfunctions.="mixpanel.identify('".USER_ID."');";
	
	}
}

if($jsfunctions!=""){
?>
<script>
<?php echo preg_replace("/\s+/", " ", $jsfunctions); ?>
</script>
<?php
}
?>
</body>
</html>