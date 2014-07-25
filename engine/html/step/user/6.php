<?php
$pageTitle="Profile step $step - HealthKeep";
$pageDescr="This is the step $step of $totalNumSteps to help you complete your profile";

if(defined(USER_TYPE) || USER_TYPE<5){
	if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==7){
		$jsfunctions.="
		if(window.location.hash!='#add'){
			mixpanel.track('User Step $step');
		}
		function mx_finish_steps(){
			if(window.location.hash!='#add'){
				mixpanel.track_links('User Finished Steps');
			}
		return true;
		}";
		$_SESSION["mx_signup"]=0;
	}
}

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
		<div class="iHeading">
				<h1 class="stepsHeading center colorGray">Enter your doctors'</h1>
		</div>
		<?php require_once(ENGINE_PATH."html/step/breadcrumb.php"); ?>
		<form action="<?php echo WEB_URL; ?>act/step/save.php?t=u&s=<?php echo $step; ?>" id="steps" method="post" class="addEventForm borderTop">
			<div class="addEventFormItem clearfix">
				<h4>Please enter your doctors names</h4>
				<div class="addEventFormInputs">
				<input type="text" id="topic" name="topic" />
				</div>
			</div>
			<div class="addEventFormButtons clearfix">
				<input type="submit" class="btn btn-blue" value="Finish" onclick="return mx_finish_steps();" />
			</div>
		</form>

		<?php
		$needTokenInput=1;
		$onload.="$('#topic').tokenInput('".WEB_URL."act/ajax/autoCompleteDoc.php', { hintText: 'Type the name of the Doctor', noResultsText: 'No doctor with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' });";

		?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');