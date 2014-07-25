<?php
if(defined(USER_TYPE) || USER_TYPE<5){
$jsfunctions.="mixpanel.track('Landing Page NEW');";
}
$testHeading="Health Sharing Network";
$designV1=1;
$active="homepage";
$ogImage=WEB_URL."inc/img/v1/logo/HealthKeep.png";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="hp2">
	<div id="hpContent2" class="iHold clearfix hackimg">
		<div id="hpContentHolder2">
			<h1 id="hpHeading" class="center"><?php echo $testHeading; ?></h1>
			<div class="clearfix">
				<?php
				require_once(ENGINE_PATH."class/topic.class.php");
				$topicClass=new Topic();
				?>
				<div class="iBox marginBottom30">
					<div class="iBoxHolder hpMainBox">
						<span class="colorBlue">Share and learn</span> <span class="colorRed">from health experiences</span>
					</div>
				</div>
				<div class="iBox" style="margin-bottom:85px;">
					<div class="iBoxHolder hpMainBox" style="position:relative;">
					<a href="<?php echo WEB_URL; ?>pro_register.php" style="position:absolute;bottom:10px;left:10px;font-size:14px;text-decoration:underline" class="colorBlue bold">Doctors register here</a>
						<div id="hpSingleInputHolder">
							<form id="homeRegister" method="post" action="<?php echo WEB_URL; ?>doorstep.php">
							<textarea id="homeExperience" name="homeExperience" style="width:100%;height:100px;background:#e0ecff;color:#666;" placeholder="Share a health experience. Share about a condition, medication or symptom. It can be about you or someone else."></textarea><br />
							<input type="text" name="hpot" class="hpot" value="" />
							<input type="hidden" name="token" value="<?php echo $token; ?>" />
							<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-large btn-success" value="Share" />
							<div id="homeRegisterAnonInfo">
							Don't worry, it's anonymous
							</div>
							</form>
							
						</div>
					</div>
				</div>
				<?php
				$onload.="$('#hpSingleInput').focus();";
				if(!$jsTopFormIsSet){
					$onload.="$('.submitBtn').prop('disabled', false);
							$('input[placeholder]').placeholder();";
				}
				
				$onload.="
				$('#homeRegister').submit(function(){
					if($('#homeExperience').val().length<10){
						alert('You need to add a health experience, question or concern.');
						return false;
					}else{
						return true;
					}
				});
				";
				?>
			</div>
		</div>
		</div>
	</div>

<?php
require_once(ENGINE_PATH."html/hp/content.php");