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
			<div class="iBoxHeadingColouredHeading iBoxHeading_green clearfix">
				<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/line.png" alt="transparent white line" /> Add a test result</h3>
			</div>
		</div>
		
		<form action="<?php echo WEB_URL; ?>act/timeline/add_result.php" enctype="multipart/form-data" id="tresult" method="post" class="addEventForm">
			<div class="addEventFormItem clearfix">
				<h4>This is a test result of</h4>
				<div class="addEventFormInputs">
					<input type="text" name="name" maxlength="200" id="name" class="addEventInputBig" />
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Attach a file</h4>
				<div class="addEventFormInputs">
				<?php
				require_once(ENGINE_PATH."html/timeline/common/formFileUpload.php");
				?>
				</div>
			</div>
			
			<div class="addEventFormItem clearfix">
				<h4>Date of the test</h4>
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
		$onload.="$('#tresult').submit(function(){
			if($('#name').val().length<3){
				alert('You need add a name to the test result');
				$('#name').focus();
				return false;
			}else if($('#file').val()==''){
				alert('You need choose a file to upload');
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