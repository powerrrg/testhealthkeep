<?php
onlyLogged();

$pageTitle="Add an event to timeline - HealthKeep";
$pageDescr="Add and event to timeline";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/top.php');

require_once(ENGINE_PATH.'html/inc/common/typeArray.php');
$typeArray=typeArray("d");
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
		<div class="iBoxHeadingColoured clearfix margin0">
			<div class="iBoxHeadingColouredHeading iBoxHeading_<?php echo $typeArray["color"]; ?> clearfix">
				<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/<?php echo $typeArray["icon"]; ?>" /> Add a condition</h3>
			</div>
		</div>

		<form action="<?php echo WEB_URL; ?>act/timeline/add_disease.php" id="diagnosis" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<?php
				$cantFindWhat="condition";
				require_once(ENGINE_PATH."html/timeline/cantFind.php");
				?>
				<h4>What was the condition?</h4>
				<div class="addEventFormInputs">
					<input type="text" id="topic" name="topic" />
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>When did it start?</h4>
				<div class="addEventFormInputs">
				<?php
					require_once(ENGINE_PATH."html/timeline/common/formDate.php");
				?>
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Is it still active?</h4>
				<div class="addEventFormInputs">
					<input type="radio" name="takingit" value="1" checked /> yes&nbsp;&nbsp;&nbsp;<input type="radio" name="takingit" value="0" /> no
				</div>
			</div>
			<div class="addEventFormButtons clearfix">
				<button class="btn btn-red" type="button" onclick="location.href='<?php echo WEB_URL; ?>timeline'">Cancel</button>
				<input type="submit" class="btn btn-blue" value="save" />
			</div>
		</form>
		<?php
		$needTokenInput=1;
		$onload.="$('#topic').tokenInput('".WEB_URL."act/ajax/autoCompleteTopic.php?type=d&cantFind=1', { hintText: 'Type the name of the condition', noResultsText: 'No condition with that name', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long',onAdd:function(item){ if(item.id==0){ $('#topic').tokenInput('clear'); $('#cantFind').click(); } } });";
		
		$onload.="$('#diagnosis').submit(function(){
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