<?php
$pageTitle="Profile step $step - HealthKeep";
$pageDescr="This is the step $step of $totalNumSteps to help you complete your profile";

require_once(ENGINE_PATH.'class/doctor.class.php');
$doctorClass=new Doctor();
$resDoc=$doctorClass->getByNPI($resProfile[0]["npi_profile"]);
if(!$resDoc["result"]){
	go404();
}
if(defined(USER_TYPE) || USER_TYPE<5){
	if(isset($_SESSION["mx_signup"]) && $_SESSION["mx_signup"]==1){
		$jsfunctions.="mixpanel.track('Doctor Step $step');";
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
		
		<form action="<?php echo WEB_URL; ?>act/step/save.php?t=d&s=<?php echo $step; ?>" id="steps" method="post" class="addEventForm borderTop">
			<div class="addEventFormItem clearfix">
				<h4>Name</h4>
				<div class="addEventFormInputs">
				<input type="text" id="name" name="name" maxlength="150" class="input100" value="<?php echo $resProfile[0]["name_profile"]; ?>"  />
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Address</h4>
				<div class="addEventFormInputs">
				<?php
				if($resDoc[0]["address_2_doctor"]!=""){
					$address=$resDoc[0]["address_1_doctor"]." ".$resDoc[0]["address_2_doctor"];
				}else{
					$address=$resDoc[0]["address_1_doctor"];
				}
				?>
				<input type="text" id="address" name="address" maxlength="100" class="input100" value="<?php echo $address; ?>"  />
				<input type="hidden" id="address2" name="address2" />
				</div>
			</div>
			<div id="zipHolder" class="addEventFormItem clearfix">
				<h4>Zip code</h4>
				<div class="addEventFormInputs">
					<input type="text" id="zip" name="zip" />
				</div>
				<?php
				require_once(ENGINE_PATH.'class/location.class.php');
				$locationClass=new Location();
				if($resProfile[0]["zip_profile"]!=""){
					
					$resZip=$locationClass->getZipByZip($resProfile[0]["zip_profile"]);
					if($resZip["result"]){
					$prepopulate2=',prePopulate: [{id: "'.$resProfile[0]["zip_profile"].'", name: "'.$resProfile[0]["zip_profile"].', '.$resZip[0]["city"].', '.$resZip[0]["state"].'"}]';
					}
				}else{
					$docZip=$resDoc[0]["postal_code_doctor"];
					if(strlen($docZip)<5){
						$docZip=str_pad($docZip, 5, "0", STR_PAD_LEFT);
					}else if(strlen($docZip)>5){
						$docZip=substr($docZip, 0,5);
					}
					$resZip=$locationClass->getZipByZip($docZip);
					if($resZip["result"]){
						$prepopulate2=',prePopulate: [{id: "'.$resZip[0]["zip"].'", name: "'.$resZip[0]["zip"].', '.$resZip[0]["city"].', '.$resZip[0]["state"].'"}]';
					}else{
						$prepopulate2="";
					}
				}
				?>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Phone Number</h4>
				<div class="addEventFormInputs">
				<input type="text" id="phone" name="phone" maxlength="20" class="input100" value="<?php echo $resDoc[0]["telephone_doctor"]; ?>"  />
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Fax Number</h4>
				<div class="addEventFormInputs">
				<input type="text" id="fax" name="fax" maxlength="20" class="input100" value="<?php echo $resDoc[0]["fax_doctor"]; ?>"  />
				</div>
			</div>
			
			<div class="addEventFormItem clearfix">
				<h4>Specialty</h4>
				<div class="addEventFormInputs">
				<input type="text" id="taxonomy" name="taxonomy"  /> 
				</div>
				<?php
				if($resDoc[0]["name_taxonomy"]!=""){
					$prepopulate=',prePopulate: [{id: "'.$resDoc[0]["code_taxonomy"].'", name: "'.$resDoc[0]["name_taxonomy"].' - '.$resDoc[0]["group_taxonomy"].'"}]';
				}else{
					$prepopulate="";
				}
				?>
			</div>
		
			<div class="addEventFormButtons clearfix">
				<button class="btn btn-gray" type="button" onclick="location.href='<?php echo WEB_URL."step/".($step+1); ?>'">Skip</button>
				<input type="submit" class="btn btn-blue" value="save" />
			</div>
		</form>
		<?php
		
		$needTokenInput=1;
		$onload.="$('#taxonomy').tokenInput('".WEB_URL."act/ajax/autoCompleteTaxonomy.php', { hintText: 'Enter your specialty', noResultsText: 'No specialty matches found', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme: 'long' $prepopulate});";
		$onload.="$('#zip').tokenInput('".WEB_URL."act/ajax/autoCompleteZip.php', { hintText: 'Type your zip code', noResultsText: 'No zip matches found', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' $prepopulate2});";
		
		$onload.="$('#steps').submit(function(){
		
			if($('#name').val().length<6){
				alert('Name needs to have more than 5 characters!');
				$('#name').focus();
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
if(defined(USER_TYPE) || USER_TYPE<5){
	require_once(ENGINE_PATH."mx/signup.php");
}
require_once(ENGINE_PATH.'html/footer.php');