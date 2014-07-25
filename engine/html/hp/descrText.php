<?php
require_once(ENGINE_PATH."class/topic.class.php");
$topicClass=new Topic();
?>
<div class="iBox marginBottom30">
	<div class="iBoxHolder hpMainBox">
		<span class="colorBlue">HealthKeep is a secure and anonymous health network</span> that connects you
with <a href="<?php echo WEB_URL; ?>doctors" class="colorGray">doctors</a> and others who share your <a href="<?php echo WEB_URL.$topicClass->pathPlural('s'); ?>" class="colorRed">symptoms</a>, <a href="<?php echo WEB_URL.$topicClass->pathPlural('m'); ?>" class="colorRed">medications</a>, and <a href="<?php echo WEB_URL.$topicClass->pathPlural('d'); ?>" class="colorRed">conditions</a>.
	</div>
</div>