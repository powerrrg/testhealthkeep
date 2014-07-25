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
				<h3>Add your daily calories intake</h3>
			</div>
		</div>
		
		<form action="<?php echo WEB_URL; ?>act/measurement/add_diet.php" id="measurement" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<h4>Please enter the number of calories</h4>
				<div class="addEventFormInputs">
				<input type="text" id="diet" name="diet" class="numeric" placeholder="calories" maxlength="5" /> 
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
		$onload.="$('#diet').focus();
		$('#measurement').submit(function(){
			if($('#diet').val()==''){
				alert('You need to set a value');
				$('#diet').focus();
				return false;
			}else if($('#diet').val()<7 || $('#weight').val()>1500){
				alert('The number of calories you set is invalid!');
				$('#diet').focus();
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