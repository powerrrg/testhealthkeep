<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

$pageTitle="Back Office";
$pageDescr="Back Office";

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
		<div class="iHeading iFull margin10auto padding15">
		<h1 class="colorRed margin10 center">Email all users</h1>
		</div>
			<div class="iFull iBoard2 margin20auto">
				<form method="post" id="goall" action="<?php echo WEB_URL."ges/emailall/send"; ?>" style="max-width:300px;margin:0 auto;">
					<div class="inputDiv">
						From:<br />
						<select name="from">
							<option value="info@healthkeep.com">info@healthkeep.com</option>
							<option value="team@healthkeep.com">team@healthkeep.com</option>
							<option value="lyle@healthkeep.com">lyle@healthkeep.com</option>
						</select>
					</div>
					<div class="inputDiv">
						<input type="text" name="subject" id="subject" placeholder="Subject" class="input100" />
					</div>
					<div class="inputDiv">
						<textarea name="message" id="message" placeholder="Message" class="textArea100gray" style="height:300px;"></textarea>
					</div>
					<div class="inputDiv">
						<input type="submit" value="save" disabled class="btn btn-red submitBtn"/>
					</div>
					<?php
					if(!$jsTopFormIsSet){
						$onload.="$('.submitBtn').prop('disabled', false);
								$('input[placeholder]').placeholder();";
					}
					$onload.="
					$('#goall').submit(function(){
						if($('#subject').val()==''){
							alert('Subject cannot be empty');
							$('#subject').focus();
							return false;
						}else if($('#message').val()==''){
							alert('Message cannot be empty');
							$('#message').focus();
							return false;
						}else{
							return true;
						}
					});
					";
					?>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');