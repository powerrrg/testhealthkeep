<?php

onlyLogged();

if(PROFILE_TYPE!=1){
	go404();
}

require_once(ENGINE_PATH.'class/timeline.class.php');
$timelineClass=new Timeline();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();
$resProfile=$profileClass->getById(USER_ID);


if(!$resProfile["result"]){
	go404();
}

$pageTitle="HealthKeep - Social Health Network";
$pageDescr="HealthKeep is a fun and intuitive social health network. It helps you to understand, organize and share about your health.  You are automatically connected to others who share your health issues.  You can connect to your doctors, and you are empowered improve your health and the health of those you care for.";

$designV1=1;
$active="timeline";
$needSlider=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
			<div id="iTimelineStatus">
				<div id="iTimelineStatusHeader" class="clearfix">
					<?php
					$resFeel=$timelineClass->getLastIfeelChange(USER_ID);
					?>
					<div class="iHoldeTimelineBtns">
						<span class="btn-group floatRight">
							<button class="btn btn-blue dropdown-toggle" data-toggle="dropdown">health measurement</button>
							<ul class="dropdown-menu pull-right">
							<li><a href="<?php echo WEB_URL; ?>measurement/add/diet">Diet</a></li>
							<li><a href="<?php echo WEB_URL; ?>measurement/add/weight">Weight</a></li>
							<li><a href="<?php echo WEB_URL; ?>measurement/add/bp">Blood Pressure</a></li>
							<li><a href="<?php echo WEB_URL; ?>measurement/add/sugar">Blood Sugar</a></li>
							<li><a href="<?php echo WEB_URL; ?>measurement/add/exercise">Exercise</a></li>
							</ul>
						</span>
						<span class="btn-group floatRight marginRight10">
							<button class="btn btn-red dropdown-toggle" data-toggle="dropdown">add an health event</button>
							<ul class="dropdown-menu pull-right">
							<li><a href="<?php echo WEB_URL; ?>timeline/add/medication">Medications</a></li>
							<li><a href="<?php echo WEB_URL; ?>timeline/add/symptom">Symptoms</a></li>
							<li><a href="<?php echo WEB_URL; ?>timeline/add/condition">Conditions</a></li>
							<li><a href="<?php echo WEB_URL; ?>timeline/add/procedure">Procedures</a></li>
							<?php /*<li><a href="<?php echo WEB_URL; ?>timeline/add/result">Test Result</a></li>*/ ?>
							<li><a href="<?php echo WEB_URL; ?>timeline/add/docvisit">Doctor Appointment</a></li>
							</ul>
						</span>
					</div>
					<h2 class="profileUserHeadingName">Health Status</h2>
				</div>
				<div id="iTimelineStatusContent" class="clearfix">
					<div class="floatRight">
						<?php
						if($resProfile[0]["dob_profile"]!="0000-00-00"){
						require_once(ENGINE_PATH."common/date.php");
						?>
						<div class="iTimelineNumbers">
						Age <span class="colorLighterBlue"><?php echo age($resProfile[0]["dob_profile"]); ?></span>
						</div>
						<?php
						}
						if($resProfile[0]["weight_profile"]!="0"){
						?>
						<div class="iTimelineNumbers">
						Weight <span class="colorLighterBlue"><?php echo $resProfile[0]["weight_profile"]; ?></span> lb
						</div>
						<?php
						}
						if($resProfile[0]["feet_profile"]!="0"){
						?>
						<div class="iTimelineNumbers">
						<?php 
						echo 'Height <span class="colorLighterBlue">'.$resProfile[0]["feet_profile"].'</span>\'';
						if($resProfile[0]["inch_profile"]!=0){ 
							echo '<span class="colorLighterBlue">'.(float)$resProfile[0]["inch_profile"].'</span>"';
						} 
						?>
						</div>
						<?php
						}
						?>
					</div>
					<div class="floatLeft colorLighterBlue padding10" style="line-height:60px;">How do you feel?</div>
					<div class="floatLeft padding10" id="iHoldSlider" style="margin-top:15px;">
						<div id="slider" style="width:270px;margin:10px 0 8px;">
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
						<div style="width:270px;margin:0;position:relative;height:15px;font-size:12px;color:#666;">
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
				</div>
			</div>

			<div id="iHoldTimeline" class="clearfix">
				<?php
				$needToolTip=1;
				$onload.="$('.holdTooltip').tooltip();";
				$resTimeline=$timelineClass->getProfileTimeline($resProfile[0]["id_profile"]);
				require_once(ENGINE_PATH."html/timeline/timelineHTML.php");
				?>
			</div>
			<?php
			$ajaxUrl=WEB_URL."act/ajax/timeline/main.php";
			$onload.="endlessScroll('$ajaxUrl',$('#iHoldTimeline'));";
			require_once(ENGINE_PATH."html/inc/endless.php");
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');