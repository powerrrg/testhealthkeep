<?php
onlyLogged();

$pageTitle="Add an event to timeline - HealthKeep";
$pageDescr="Add and event to timeline";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
		<div class="iBoxHeadingColoured clearfix margin0">
			<div class="iBoxHeadingColouredHeading iBoxHeading_red clearfix">
				<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/line.png" alt="transparent white line" /> Add a Doctor Appointment</h3>
			</div>
		</div>
		
		<form action="<?php echo WEB_URL; ?>act/timeline/add_docvisit.php" id="procedure" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<?php
				$cantFindWhat="doctor";
				require_once(ENGINE_PATH."html/timeline/cantFind.php");
				?>
				<h4>Who is the doctor?</h4>
				<div class="addEventFormInputs">
					<input type="text" id="topic" name="topic" />
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>When was or will be the appointment?</h4>
				<div class="addEventFormInputs">
				<?php
					//allow future dates the value is the number of years into the future that are allowed
					$allowFutureDate=5;
					require_once(ENGINE_PATH."html/timeline/common/formDate.php");
				?>
				</div>
			</div>
			<div class="addEventFormButtons clearfix">
				<button class="btn btn-red" type="button" onclick="location.href='<?php echo WEB_URL; ?>timeline'">Cancel</button>
				<input type="submit" class="btn btn-blue" value="save" />
			</div>
		</form>
		<?php
		$needTokenInput=1;
		$onload.="$('#topic').tokenInput('".WEB_URL."act/ajax/autoCompleteDoc.php', { hintText: 'Type the name of the Doctor', noResultsText: 'No doctor with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' });";
		
		$onload.="$('#procedure').submit(function(){
			if($('#topic').val()==''){
				alert('You need to set a condition');
				$('#topic').focus();
				return false;
			}else if(!isValidDate($('#month').val(),$('#day').val(),$('#year').val())){
				alert('The date is invalid!');
				$('#day').focus();
				return false;
			}else{
				return true;
			}
			
		});";
		?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');