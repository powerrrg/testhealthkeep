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
					$lastStatus="";
					if($resFeel["result"]){
						$lastStatus=" <span class=\"colorGray\">as of ".date('m/d/Y',strtotime($resFeel[0]["date_tm"]))."</span>";
					}
					?>
					<h2 class="profileUserHeadingName center">How are you feeling today?</h2>
				</div>
				<div id="iTimelineStatusContent" class="center">
					

					<div id="slider" style="width:270px;margin:10px auto;">
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
			</div>
			<div class="iHeading clearfix marginTop20">
				<span class="btn-group floatRight">
					<button class="btn btn-blue dropdown-toggle" data-toggle="dropdown">health measurement</button>
					<ul class="dropdown-menu pull-right">
					<li><a href="#">Diet</a></li>
					<li><a href="#">Weight</a></li>
					<li><a href="#">Blood Pressure</a></li>
					<li><a href="#">Blood Sugar</a></li>
					<li><a href="#">Exercise</a></li>
					</ul>
				</span>
				<span class="btn-group floatRight">
					<button class="btn btn-red dropdown-toggle" data-toggle="dropdown">add an health event</button>
					<ul class="dropdown-menu pull-right">
					<li><a href="<?php echo WEB_URL; ?>timeline/add/medication">Medications</a></li>
					<li><a href="<?php echo WEB_URL; ?>timeline/add/symptoms">Symptoms</a></li>
					<li><a href="<?php echo WEB_URL; ?>timeline/add/diagnosis">Conditions</a></li>
					<li><a href="<?php echo WEB_URL; ?>timeline/add/procedure">Procedures</a></li>
					<li><a href="<?php echo WEB_URL; ?>timeline/add/result">Test Result</a></li>
					<li><a href="<?php echo WEB_URL; ?>timeline/add/docvisit">Doctor Appointment</a></li>
					</ul>
				</span>
			</div>
			<div id="iHoldTimeline">
				<?php
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