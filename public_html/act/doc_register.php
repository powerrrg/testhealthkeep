<?php
require_once('../../engine/starter/config.php');

require_once(ENGINE_PATH."html/doctors/reg_validation.php");

if($type!="doc"){
	go404();
}

$pageTitle="Doctor registration - HealthKeep";
$pageDescr="Please confirm your doctor registration";

$active="homepage";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');

?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFullLogin">
			<h2 class="iFullHeading">Doctor Registration</h2>
					
					<div id="npiTest" class="inputDiv">
						<div style="color:#999;margin-bottom:10px;">Please insert your NPI<br />so we can validate your registration</div>
						<input type="text" maxlength="11" placeholder="NPI" name="npi" id="npi" />
						<?php
						$needNumeric=1;
						$onload.="$('#npi').numeric({ decimal: false, negative: false });";
						?>
					</div>
					<div id="regError" class="formError"></div>
					<div class="inputDiv" id="validateBtnHolder">
						<button id="validateBtn" class="btn btn-blue">Validate</button>
					</div>
					<div class="inputDiv center" id="validateLoader" style="display:none;">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader.gif" />
					</div>
					<?php
				$onload.="
				$('#npi,#license').keypress(function(e) {
				    if(e.which == 13) {
				        $('#validateBtn').trigger('click');
				    }
				});
				";
				$jsfunctions.="
				$('#validateBtn').click(function(){
					$('#validateBtnHolder').hide();
					if($('#npi').val().length<10){
						$('#regError').show();
						$('#regError').html('The NPI you provided is invalid!');
						$('#validateBtnHolder').show();
					}else{
						$('#validateLoader').show();
						$.ajax({
							type: 'POST',
							url: '".WEB_URL."act/ajax/doc_validation.php',
							data: { npi: $('#npi').val() },
							success: function(data) {
								if(data=='nop'){
									$('#regError').show();
									$('#regError').html('The NPI you provided is invalid!');
									$('#validateBtnHolder').show();
									$('#validateLoader').hide();
								}else{
									$('#npiTest').hide();
									$('#validateLoader').hide();
									$('#regOKtxt').html('Do you confirm that your name is '+data+'?');
									$('#formHolder').show();
									return false;
								}
							}
						});
					}
				});
				$('#npi,#license').keyup(function(e) {
					if(e.which != 13) {
			    		$('#regError').hide();
			    	}
			    });
				";
				?>
				<div id="formHolder" class="marginBottom30 fullForm clearfix" style="display:none;">
					<form id="docReg" method="post" style="margin-bottom:10px;padding:0;" action="<?php echo WEB_URL; ?>act/doc_register_save.php">
						<div class="alert alert-success" id="regOKtxt"></div>
						<input type="hidden" maxlength="100" value="<?php echo $phone; ?>" name="phone" id="phone" />
						<input type="hidden" maxlength="100" value="<?php echo $name; ?>" name="name" id="name" />
						<input type="hidden" maxlength="150" value="<?php echo $email; ?>" name="email" id="reg_emailPro" />
						<input type="hidden" maxlength="20" value="<?php echo $password; ?>" name="password" id="reg_passwordPro" />
						<input type="hidden" maxlength="11" value="" name="npi_reg" id="npi_reg" />
					</form>
					<div class="inputDiv">
						<button onclick="yesReg();" class="btn btn-red">Yes</button>
						<button onclick="noReg();" class="btn btn-blue">No</button>
					</div>
				</div>
				<?php
				$jsfunctions.="
				function noReg(){
					$('#validateLoader').hide();
					$('#validateBtnHolder').show();
					$('#formHolder').hide();
					$('#regOKtxt').html('');
					$('#npiTest').show();
				}
				function yesReg(){
					$('#npi_reg').val($('#npi').val());
					$('#docReg').submit();
				}
				";
				/*$onload.="
				$('#noReg').click(function(){
					$('#docReg').hide();
					$('#regOKtxt').html('');
					$('#npiTest').show();
				});
				$('#noReg').click(function(){
					$('#npi_reg').val($('#npi').val());
					$('#docReg').submit();
				});
				";*/
				?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');