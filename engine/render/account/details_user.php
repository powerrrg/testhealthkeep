<?php
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull">
			<h2 class="iFullHeading">Bio</h2>
			<form action="<?php echo WEB_URL; ?>act/account/details.php" style="max-width:300px;" id="iMhealthForm" method="post" class="addEventForm borderTop">
			
			<div class="addEventFormItem clearfix">
				<h4>Date of birth?</h4>
				<div class="addEventFormInputs">
				<?php $forceYearZero =1; require_once(ENGINE_PATH."render/common/DObirth.php"); ?>
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>Where do you live?</h4>
				<div class="addEventFormInputs">
				<select id="country" name="country" class="inputW300">
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
				<input type="text" id="job" name="job" maxlength="100" class="inputW300" value="<?php echo $resProfile[0]["job_profile"]; ?>" /> 
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>What is your gender</h4>
				<div class="addEventFormInputs">
				<select name="gender" class="inputW300">
					<option value="m" <?php if($resProfile[0]["gender_profile"]=="m"){ echo "selected";} ?>>Male</option>
					<option value="f" <?php if($resProfile[0]["gender_profile"]=="f"){ echo "selected";} ?>>Female</option>
				</select>
				</div>
			</div>
			<div class="addEventFormItem clearfix">
				<h4>About you <span style="font-size:10px">(Maximum characters: 200)</span></h4>
				<div class="addEventFormInputs">
				<?php 
				require_once(ENGINE_PATH."class/config.class.php");
				$configClass= new Config(); 
				$txtBio= $configClass->br2nl($resProfile[0]["bio_profile"]); 
				?>
				<textarea name="miniBio" id="miniBio" style="width:100%;height:100px;"><?php echo $txtBio; ?></textarea><br />
				<div style="font-size:10px">You have <span id="charleft"></span> characters left.</div>
				<?php
				$onload.="
				$('#miniBio').keyup(function(){
					updateCharLeft();
				});
				updateCharLeft();
				";
				$jsfunctions.="
					function updateCharLeft(){
						var num=$('#miniBio').val().length;
						if((200-num)<1){
						num=0;
						}else{
						num=200-num;
						}
						$('#charleft').text(num);
					}
				";
				?>
				</div>
			</div>
			<div class="clearfix" style="margin-top:20px;">
				<input type="submit" value="save" class="btn btn-red" style="float:left;" />
				<a href="<?php echo WEB_URL.USER_NAME; ?>" class="colorBlue" style="float:left;margin:10px 0 0 10px;">cancel</a>
			</div>
		</form>
		<?php
		$needNumeric=1;
		$onload.="$('.numeric').numeric();";
		$needTokenInput=1;
		$onload.="$('#zip').tokenInput('".WEB_URL."act/ajax/autoCompleteZip.php', { hintText: 'Type your zip code', noResultsText: 'No zip matches found', searchingText: 'Searching...',tokenLimit: 1,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' $prepopulate});";
		
		$onload.="$('#iMhealthForm').submit(function(){
		
				return true;
			
		});";
		
		?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');