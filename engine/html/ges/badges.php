<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$userClass->getAll(true);

foreach($res as $key=>$value){
	if(is_int($key)){
		$profileClass->updateBadge("sharing",$value["id_profile"]);
		$profileClass->updateBadge("supportive",$value["id_profile"]);
		$profileClass->updateBadge("helpful",$value["id_profile"]);
		$profileClass->updateBadge("karma",$value["id_profile"]);
	}
}

$pageTitle="Back Office";
$pageDescr="Back Office";

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Badges</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<div class="alert alert-success" style="margin-bottom:0;padding:30px 0;text-align:center;">
					Badges were updated!
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');