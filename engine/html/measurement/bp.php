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
				<h3>Add blood presure</h3>
			</div>
		</div>
		
		<form action="<?php echo WEB_URL; ?>act/measurement/add_bp.php" id="measurement" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<h4>Please enter your blood pressure</h4>
				<div class="addEventFormInputs">
				<input type="text" id="bpt" name="bpt" class="numeric" style="width:120px;" placeholder="Top (systolic)" maxlength="3" /> 
				<input type="text" id="bpb" name="bpb" class="numeric" style="width:120px;" placeholder="Bottom (diastolic)" maxlength="3" /> 
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
		$onload.="$('#bpt').focus();
		$('#measurement').submit(function(){
			if($('#bpt').val()==''){
				alert('You need to set a value');
				$('#bpt').focus();
				return false;
			}else if($('#bpb').val()==''){
				alert('You need to set a value');
				$('#bpb').focus();
				return false;
			}else if($('#bpt').val()<0 || $('#bpt').val()>300){
				alert('The top blood pressure you set is invalid!');
				$('#bpt').focus();
				return false;
			}else if($('#bpb').val()<0 || $('#bpb').val()>300){
				alert('The bottom blood pressure you set is invalid!');
				$('#bpb').focus();
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