<?php
onlyLogged();

$pageTitle="Add an event to timeline - HealthKeep";
$pageDescr="Add and event to timeline";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/top.php');

require_once(ENGINE_PATH.'html/inc/common/typeArray.php');
$typeArray=typeArray("measurement");
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
		<div class="iBoxHeadingColoured clearfix margin0">
			<div class="iBoxHeadingColouredHeading iBoxHeading_<?php echo $typeArray["color"]; ?> clearfix">
				<h3>Add weight result</h3>
			</div>
		</div>
		
		<form action="<?php echo WEB_URL; ?>act/measurement/add_weight.php" id="measurement" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<h4>Please enter your weight</h4>
				<div class="addEventFormInputs">
				<input type="text" id="weight" name="weight" class="numeric" placeholder="pounds" maxlength="4" /> 
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Date for this value</h4>
				<div class="addEventFormInputs">
					<?php
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
		$needNumeric=1;
		$onload.="$('.numeric').numeric();";
		$onload.="$('#weight').focus();
		$('#measurement').submit(function(){
			if($('#weight').val()==''){
				alert('You need to set a value');
				$('#weight').focus();
				return false;
			}else if($('#weight').val()<7 || $('#weight').val()>1500){
				alert('The weight you set is invalid!');
				$('#weight').focus();
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