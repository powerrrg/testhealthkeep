<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

$pageTitle="Back Office";
$pageDescr="Back Office";

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Stats</h1>
				<p>Number of users: <b><?php echo $profileClass->numberUsersType(1); ?></b></p>
				<p>Number of doctors: <b><?php echo $profileClass->numberUsersType(2); ?></b></p>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');