<?php
if(defined(USER_TYPE) || USER_TYPE<5){
	$jsfunctions.="mixpanel.track('Landing Page 7 Loaded');";
}
$testHeading="Social Health Network";
$designV1=1;
$active="homepage";
$ogImage=WEB_URL."inc/img/v1/logo/HealthKeep.png";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="hp2">
	<div id="hpContent2" class="iHold clearfix hackimg">
		<div id="hpContentHolder2">
			<?php
			require_once(ENGINE_PATH."html/hp/singleinputSearch.php");
			?>
		</div>
	</div>

<?php
require_once(ENGINE_PATH."html/hp/content.php");