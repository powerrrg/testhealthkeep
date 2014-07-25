<?php
$jsfunctions.="mixpanel.track('Landing Page 4 Loaded');";

$designV1=1;
$active="homepage";
$ogImage=WEB_URL."inc/img/v1/logo/HealthKeep.png";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="hp4">
	<div id="hpContent4" class="iHold clearfix hackimg">
		<div id="hpContentHolder4">
			<?php
			require_once(ENGINE_PATH."html/hp/singleinput.php");
			?>
		</div>
	</div>

<?php
require_once(ENGINE_PATH."html/hp/content.php");