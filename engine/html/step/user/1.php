<?php
$pageTitle="Profile step $step - HealthKeep";
$pageDescr="This is the step $step of $totalNumSteps to help you complete your profile";

if(defined(USER_TYPE) || USER_TYPE<5){
	if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==2){
		$jsfunctions.="mixpanel.track('User Step $step');";
		$_SESSION["mx_signup"]=3;
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
				<h1 class="stepsHeading center colorGray">Welcome to <span class="colorLighterBlue">HealthKeep</span> Please take a few minutes to set up your profile</h1>
		</div>
		<?php require_once(ENGINE_PATH."html/step/breadcrumb.php"); ?>
		
		<form action="<?php echo WEB_URL; ?>act/step/save.php?t=u&s=<?php echo $step; ?>" id="steps" method="post" class="addEventForm borderTop">
			<div class="addEventFormItem clearfix">
				<h4>What is your date of birth?</h4>
				<div class="addEventFormInputs">
				<?php require_once(ENGINE_PATH."html/inc/common/DObirth.php"); ?>
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Where do you live?</h4>
				<div class="addEventFormInputs">
				<select id="country" name="country">
				<?php
				require_once(ENGINE_PATH.'class/location.class.php');
				$locationClass=new Location();
				$resCountries=$locationClass->getAllCountries();
				foreach($resCountries as $key=>$value){
					if(is_int($key)){
						echo '<option value="'.$value["iso2"].'"';
						if(($resProfile[0]["country_profile"]=="" && $value["iso2"]=="US") || $resProfile[0]["country_profile"]==$value["iso2"]){ 
						echo "selected"; 
						}
						echo '>'.$value["short_name"].'</a>';					
					}
				}
				?>
				</select>
				<?php
				$onload.="$('#country').change(function(){
					if($(this).val()!='US'){
						$('#zipHolder').hide();
					}else{
						$('#zipHolder').show();
					}
				});";
				?>
				</div>
			</div>
			<div id="zipHolder" class="addEventFormItem clearfix" <?php if($resProfile[0]["country_profile"]!="US" && $resProfile[0]["country_profile"]!=""){ echo 'style="display:none;"'; }?>>
				<h4>What is your zip code?</h4>
				<div class="addEventFormInputs">
					<input type="text" id="zip" name="zip"/>
				</div>
				<?php
				if($resProfile[0]["zip_profile"]!=""){
					$resZip=$locationClass->getZipByZip($resProfile[0]["zip_profile"]);
					if($resZip["result"]){
					$prepopulate=',prePopulate: [{id: "'.$resProfile[0]["zip_profile"].'", name: "'.$resProfile[0]["zip_profile"].', '.$resZip[0]["city"].'"}]';
					}
				}else{
					$prepopulate="";
				}
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Please enter your weight</h4>
				<div class="addEventFormInputs">
				<input type="text" id="weight" name="weight" class="numeric" placeholder="pounds" maxlength="6" <?php if($resProfile[0]["weight_profile"]!=0){ echo 'value="'.$resProfile[0]["weight_profile"].'"';} ?> /> 
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Please enter your height</h4>
				<div class="addEventFormInputs">
				<input type="text" id="feets" name="feets" class="numeric" style="width:50px;" maxlength="2" placeholder="feet" <?php if($resProfile[0]["feet_profile"]!=0){ echo 'value="'.$resProfile[0]["feet_profile"].'"';} ?> />
				<input type="text" id="inches" name="inches" class="numeric" style="width:50px;" maxlength="4" placeholder="inches" <?php if($resProfile[0]["inch_profile"]!=0){ echo 'value="'.$resProfile[0]["inch_profile"].'"';} ?>/> 
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>What is your occupation</h4>
				<div class="addEventFormInputs">
				<input type="text" id="job" name="job" maxlength="100" value="<?php echo $resProfile[0]["job_profile"]; ?>" /> 
				</div>
			</div>
			<div class="addEventFormButtons clearfix">
				<button class="btn btn-gray" type="button" onclick="location.href='<?php echo WEB_URL."step/".($step+1); ?>'">Skip</button>
				<input type="submit" class="btn btn-blue" value="save" />
			</div>
		</form>
		<?php
		$needNumeric=1;
		$onload.="$('.numeric').numeric();";
		$needTokenInput=1;
		$onload.="$('#zip').tokenInput('".WEB_URL."act/ajax/autoCompleteZip.php', { hintText: 'Type your zip code', noResultsText: 'No zip matches found', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' $prepopulate});";
		
		$onload.="$('#steps').submit(function(){
			if(!isValidDate($('#month').val(),$('#day').val(),$('#year').val())){
				alert('Invalid date of birth!');
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