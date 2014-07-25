<?php
$jsfunctions.="mixpanel.track('Landing Page Loaded');";

$designV1=1;
$active="homepage";
$ogImage=WEB_URL."inc/img/v1/logo/HealthKeep.png";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="hp">
	<div id="hpContent" class="iHold clearfix hackimg">
		<div id="hpContentHolder">
			<h1 id="hpHeading" class="center">Social Health Network</h1>
			<div class="clearfix">
				<div class="iBox">
					<div class="iBoxHolder hpMainBox">
						<span class="colorBlue">HealthKeep is a secure and anonymous health network</span> that connects you
with doctors and others who share your <span class="colorRed">symptoms, medications,</span> and <span class="colorRed">conditions</span>
					</div>
				</div>
				<div id="hpRegister" class="clearfix hpReg">
					<div id="hpMainRegister">
						<div class="iBox">
							<div class="iBoxHolder clearfix padding20">
								<?php require_once(ENGINE_PATH."html/hp/hpMainReg.php"); ?>
							</div>
						</div>
					</div>
					<div id="hpProRegister">
						<div class="iBox">
							<div class="iBoxHolder clearfix padding20">
								<?php require_once(ENGINE_PATH."html/hp/hpProReg.php"); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
require_once(ENGINE_PATH."html/hp/content.php");