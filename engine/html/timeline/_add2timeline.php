<?php
onlyLogged();

$pageTitle="Add an event to timeline - HealthKeep";
$pageDescr="Add and event to timeline";

require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/bar.php');

?>
<div id="main" class="iHold clearfix">
	<div class="iRounded iBoardWithHeader clearfix center">
		<h3 class="iBoardHeader">Choose the event type</h3>
		<div id="largeButtonsHolder">
		<button onclick="location.href='<?php echo WEB_URL; ?>timeline/add/medication'" class="btn btn-large btn-block btn-primary">Medications</button>
		<button onclick="location.href='<?php echo WEB_URL; ?>timeline/add/symptoms'" class="btn btn-large btn-block btn-primary">Symptoms</button>
		<button onclick="location.href='<?php echo WEB_URL; ?>timeline/add/diagnosis'"  onclick="" class="btn btn-large btn-block btn-primary">New Diagnosis</button>
		<button onclick="location.href='<?php echo WEB_URL; ?>timeline/add/procedure'" class="btn btn-large btn-block btn-primary">Procedure</button>
		<button onclick="location.href='<?php echo WEB_URL; ?>timeline/add/result'" class="btn btn-large btn-block btn-primary">Test Results</button>
		<!--<button onclick="" class="btn btn-large btn-block btn-primary">Doctor/Hospital Visit</button>-->
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');