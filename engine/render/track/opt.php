<?php
onlyLogged();

$pageTitle="Health Tracking - HealthKeep";
$pageDescr="Tack all your health values";

$active="track";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<div id="step1track">
			<?php
			require_once(ENGINE_PATH.'class/profile.class.php');
			$profileClass=new Profile();
			
			$res=$profileClass->getById(USER_ID);
			if($res[0]["tracking_profile"]==2){
			?>
			<h2 class="iFullHeading">Health Tracking</h2>
			<?php
			require_once(ENGINE_PATH.'class/timeline.class.php');
			$timelineClass=new Timeline();
			
			$resToday=$timelineClass->getWeightTrakedToday();
			if(!$resToday["result"]){
/*						<a href="<?php echo WEB_URL; ?>track/deactivate" style="float:right;margin-left:20px;margin-bottom:10px;color:#ccc;">Deactivate</a>*/
			?>

			
			<div style="margin:30px 10px 30px 10px !important;" id="measurementboxes" method="post" class="addEventForm">
				<div class="addEventFormItem clearfix">
					<h4>Enter your weight today</h4>
					<div class="addEventFormInputs">
						<input type="text" id="weight" name="weight" class="numeric" placeholder="pounds" maxlength="4" /> 
					</div>
				</div>
				<div class="addEventFormButtons clearfix">
					<button class="btn btn-red" type="button" class="step1btn" onclick="goSave();">save</button>
					<a href="<?php echo WEB_URL; ?>track"  class="step1btn" style="margin-left:10px;color:#ccc;">Cancel</a>
					
				</div>
			</div>
			<?php
			}
			$needNumeric=1;
			$onload.="$('.numeric').numeric();";
			$jsfunctions.="$('#weight').focus();
			function goSave(){
				$('#measurementboxes').hide();
				if($('#weight').val()==''){
					alert('You need to set a value');
					$('#weight').focus();
					return false;
				}else if($('#weight').val()<7 || $('#weight').val()>1500){
					alert('The weight you set is invalid!');
					$('#weight').focus();
					return false;
				}else{
					thefloat=parseFloat($('#weight').val());
					$.ajax({
					  type: 'POST',
					  url: '".WEB_URL."act/ajax/track/weight.php',
					  data: { weight: thefloat }
					}).done(function( msg ) {
					  if(msg!='ok'){
					  	alert('Ops! We could save that value. Please try again later or contact us.');
					  	$('#measurementboxes').show();	
					  }else{
					  	location.href='".WEB_URL."track';
					  }
					});
				}
				
			}";
			?>
			
			<div id="holdeWeight" class="clearfix" style="padding:30px 10px 30px 10px">
			<hr />
				<?php
				
				
				$res=$timelineClass->getMeasurements("weight",USER_ID,3,'desc');
				if($res["result"]){
					foreach($res as $key=>$value){
						if(is_int($key)){
							echo "<div><span>".$value["date_tm"]."</span>".(int)$value["frequency_tm"]."lb</div>";
						}
					}
				}
				?>
			</div>
				
				
				
				
			<?php
			}else{
			?>
			<h2 class="iFullHeading">Health Tracking</h2>
			<div id="okAlert" class="alert alert-success" style="margin:0 auto 50px;text-align:center;">
				We are starting a health tracking section.<br />
				It will start with weight tracking, do you want to participate?
			</div>
			<div style="margin:50px auto;text-align:center;">
				<button href="<?php echo WEB_URL; ?>track/start" id="yesBtn" class="btn btn-red" style="margin-right:60px !important;">yes</button>
				<button href="<?php echo WEB_URL; ?>track/deactivate" id="noBtn" class="btn btn-blue;">no</button>
			</div>
			</div>
			<?php
			//ga is event tracking for google analytics new event analytics.js
			$onload.="
			$('#yesBtn').click(function() {";
			if($config["branch"]=="prod"){
				$onload.="
				ga('send', 'event', 'button', 'click', 'yes', { 'hitCallback' : function() {
				  	location.href='".WEB_URL."track/start';
				  }
				  }
				);
				";
			}else{
			$onload.="location.href='".WEB_URL."track/start';";
			}
			$onload.="});
			$('#noBtn').click(function() {";
			if($config["branch"]=="prod"){
				$onload.="
				ga('send', 'event', 'button', 'click', 'no', { 'hitCallback' : function() {
				  	location.href='".WEB_URL."track/deactivate';
				  }
				  }
				);
				";
			}else{
				$onload.="location.href='".WEB_URL."track/deactivate';";
			}
			$onload.="});";
			
			/*
			
			ADD NEXT WEIXGHT IN X HOURS
			
			
			<div id="step2track" style="margin-top:30px;">
				<p>Suggest any other health measures you would like to track:</p>
				<input type="text" name="suggestions" id="suggentions" placeholder="" style="width:90%;" />
				<button class="btn btn-blue">Suggest</button>
			</div>
			<?php
			*/
			}
			/*$onload.="$('.step1btn').click(function(e){
			$('#step1track').hide();
			$('#step2track').hide();
			})";*/ 
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');