<?php
onlyLogged();

$pageTitle="Health Details - HealthKeep";
$pageDescr="Change your health details";

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}


$designV1=1;
$needSlider=1;

require_once(ENGINE_PATH.'html/header.php');
$active="account";
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iMessages" class="iBoard clearfix">
			<div class="iHeading iFull margin10auto padding15">
				<h2 class="colorBlue margin0 center">Change your health details</h2>
			</div>
			<div id="iMhealthHolder" class="iFull iBoard2 margin20auto" style="padding:5px 15px;">
				<div class="center" id="iHoldSlider" style="margin-top:15px;">
					<h4>How do you feel?</h4>
					<div id="slider" style="width:270px;margin:10px auto 8px;">
						<?php
						$onload.="
					    $( '#slider' ).slider({
					      value:".$resProfile[0]["ifeel_profile"].",
					      range: 'min',
					      min: 1,
					      max: 10,
					      step: 1,
					      stop: function( event, ui ) {
					     	 $.ajax({
							  type: 'POST',
							  url: '".WEB_URL."act/ajax/iFeel.php',
							  data: { iFeel: ui.value }
							});
					      }
					    });";
						?>
					</div>
					<div style="width:270px;margin:0 auto;position:relative;height:15px;font-size:12px;color:#666;">
						<span style="position:absolute;left:0px;">1</span>
						<span style="position:absolute;left:30px;">2</span>
						<span style="position:absolute;left:60px;">3</span>
						<span style="position:absolute;left:90px;">4</span>
						<span style="position:absolute;left:120px;">5</span>
						<span style="position:absolute;left:150px;">6</span>
						<span style="position:absolute;left:180px;">7</span>
						<span style="position:absolute;left:210px;">8</span>
						<span style="position:absolute;left:240px;">9</span>
						<span style="position:absolute;left:260px;">10</span>
					</div>
				</div>
				<hr />
				<form id="iMhealthForm" class="center" method="post" action="<?php echo WEB_URL; ?>act/profile/healthDetails.php">
					<?php
					$needNumeric=1;
					$onload.="$('.numeric').numeric();";
					?>
					<div>
						<h4>Please enter your weight</h4>
						<input type="text" id="weight" name="weight" <?php if($resProfile[0]["weight_profile"]>0){ echo 'value="'.$resProfile[0]["weight_profile"].'"'; } ?> class="numeric" placeholder="pounds" maxlength="6" style="width:80px;text-align:center;" /> 
					</div>
					<hr />
					<div>
						<h4>Please enter your height</h4>
						<input type="text" id="feets" name="feets" <?php if($resProfile[0]["feet_profile"]>0){ echo 'value="'.$resProfile[0]["feet_profile"].'"'; } ?> class="numeric center" style="width:50px;" maxlength="2" placeholder="feet" /> <input type="text" id="inches" <?php if($resProfile[0]["inch_profile"]>0){ echo 'value="'.(int)$resProfile[0]["inch_profile"].'"'; } ?> name="inches" class="numeric center" style="width:50px;" maxlength="4" placeholder="inches" />
						</div>
	 				<hr />
	 				<div>
	 					<a href="<?php echo WEB_URL.USER_NAME; ?>" class="colorGray">cancel</a> <input type="submit" value="save" class="btn btn-blue" />
	 				</div>
				</form>
			</div>	
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');