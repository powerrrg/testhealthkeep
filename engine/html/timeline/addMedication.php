<?php
onlyLogged();

$pageTitle="Add an event to timeline - HealthKeep";
$pageDescr="Add and event to timeline";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/top.php');

require_once(ENGINE_PATH.'html/inc/common/typeArray.php');
$typeArray=typeArray("m");
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
		<div class="iBoxHeadingColoured clearfix margin0">
			<div class="iBoxHeadingColouredHeading iBoxHeading_<?php echo $typeArray["color"]; ?> clearfix">
				<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/<?php echo $typeArray["icon"]; ?>" /> Add medication</h3>
			</div>
		</div>
		
		<form action="<?php echo WEB_URL; ?>act/timeline/add_medication.php" id="medication" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<?php
				$cantFindWhat="medication";
				require_once(ENGINE_PATH."html/timeline/cantFind.php");
				?>
				<h4>What medication you want to add?</h4>
				<div class="addEventFormInputs">
					<input type="text" id="topic" name="topic" />
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>When did you start taking it?</h4>
				<div class="addEventFormInputs">
					<?php
					require_once(ENGINE_PATH."html/timeline/common/formDate.php");
					?>
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Are you still taking it?</h4>
				<div class="addEventFormInputs">
					<input type="radio" name="takingit" value="1" checked /> yes&nbsp;&nbsp;&nbsp;<input type="radio" name="takingit" value="0" /> no
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>How much are you taking?</h4>
				<div class="addEventFormInputs">
					<input type="text" value="1" class="numeric" id="frequency_id" name="frequency" style="width:80px;margin:0 0 10px;"/>
					<select name="unit" style="width:100px;">
						<option value="0">Unit</option>
						<option value="1">Microgram</option>
						<option value="2">Milligram</option>
						<option value="3">Gram</option>
						<option value="0">Other</option>
					</select>
					<select name="freq" style="width:120px;">
						<option value="0">Frequency</option>
						<option value="1">Once a day</option>
						<option value="2">Twice a day</option>
						<option value="3">Three times a day</option>
						<option value="0">Other</option>
					</select>
					<?php
					$needNumeric=1;
					$onload.="$('.numeric').numeric();";
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
		$onload.="$('#topic').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=m&cantFind=1', { hintText: 'Type the name of the medication', noResultsText: 'No medication with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long',onAdd:function(item){ if(item.id==0){ $('#topic').tokenInput('clear'); $('#cantFind').click(); } } });";
		
		$onload.="$('#medication').submit(function(){
			if($('#topic').val()==''){
				alert('You need to set a medication');
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