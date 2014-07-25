<?php
$jsfunctions.="mixpanel.track('Landing Page 3 Loaded');";

$designV1=1;
$active="homepage";
$ogImage=WEB_URL."inc/img/v1/logo/HealthKeep.png";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="hp3">
	<div id="hpContent3" class="iHold clearfix hackimg">
		<div id="hpContentHolder3">
			<?php
			require_once(ENGINE_PATH."html/hp/singleinput.php");
			?>
		</div>
	</div>
<?php
require_once(ENGINE_PATH."html/hp/content.php");