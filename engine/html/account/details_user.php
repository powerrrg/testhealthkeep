<form action="<?php echo WEB_URL; ?>act/account/details.php" id="steps" method="post" class="addEventForm borderTop">
			
	<div class="addEventFormItem clearfix">
		<h4>Date of birth?</h4>
		<div class="addEventFormInputs">
		<?php $forceYearZero =1; require_once(ENGINE_PATH."html/inc/common/DObirth.php"); ?>
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
			<input type="text" id="zip" name="zip" />
		</div>
		<?php
		if($resProfile[0]["zip_profile"]!=""){
			$resZip=$locationClass->getZipByZip($resProfile[0]["zip_profile"]);
			if($resZip["result"]){
			$prepopulate=',prePopulate: [{id: "'.$resProfile[0]["zip_profile"].'", name: "'.$resProfile[0]["zip_profile"].', '.$resZip[0]["city"].', '.$resZip[0]["state"].'"}]';
			}
		}else{
			$prepopulate="";
		}
		?>
	</div>
	<div class="addEventFormItem clearfix">
		<h4>What is your occupation</h4>
		<div class="addEventFormInputs">
		<input type="text" id="job" name="job" maxlength="100" value="<?php echo $resProfile[0]["job_profile"]; ?>" /> 
		</div>
	</div>
	<div class="addEventFormItem clearfix">
		<h4>What is your gender</h4>
		<div class="addEventFormInputs">
		<select name="gender">
			<option value="m" <?php if($resProfile[0]["gender_profile"]=="m"){ echo "selected";} ?>>Male</option>
			<option value="f" <?php if($resProfile[0]["gender_profile"]=="f"){ echo "selected";} ?>>Female</option>
		</select>
		</div>
	</div>
	<div class="addEventFormButtons clearfix">
		<a href="<?php echo WEB_URL.USER_NAME; ?>" class="colorGray">cancel</a> <input type="submit" class="btn btn-blue" value="save" />
	</div>
</form>
<?php
$needNumeric=1;
$onload.="$('.numeric').numeric();";
$needTokenInput=1;
$onload.="$('#zip').tokenInput('".WEB_URL."act/ajax/autoCompleteZip.php', { hintText: 'Type your zip code', noResultsText: 'No zip matches found', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' $prepopulate});";

$onload.="$('#steps').submit(function(){

		return true;
	
});";

?>