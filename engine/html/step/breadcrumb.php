<div id="stepsBreadcrumbHolder">
<ul id="stepsBreadcrumb" class="clearfix">
	<li><a href="<?php echo WEB_URL; ?>step/1" class="step1Active">Account Details</a></li>
	<li><a href="<?php echo WEB_URL; ?>step/2" <?php if($step>=2){ echo 'class="step2Active"';} ?>>Conditions</a></li>
	<li><a href="<?php echo WEB_URL; ?>step/3" <?php if($step>=3){ echo 'class="step3Active"';} ?>>Medications</a></li>
	<li><a href="<?php echo WEB_URL; ?>step/4" <?php if($step>=4){ echo 'class="step4Active"';} ?>>Symptoms</a></li>
	<li><a href="<?php echo WEB_URL; ?>step/5" <?php if($step>=5){ echo 'class="step5Active"';} ?>>Surgeries/Procedures</a></li>
	<?php
	if($resProfile[0]["type_profile"]==1){
	?>
	<li><a href="<?php echo WEB_URL; ?>step/6" <?php if($step>=6){ echo 'class="step6Active"';} ?>>Doctors</a></li>
	<?php
	}
	?>
</ul>
</div>