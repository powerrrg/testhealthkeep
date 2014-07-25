<h1 id="hpHeading" class="center"><?php echo $testHeading; ?></h1>
	<div class="clearfix">
		<?php require_once(ENGINE_PATH."html/hp/descrText.php"); ?>
		<div class="iBox" style="margin-bottom:85px;">
			<div class="iBoxHolder hpMainBox" style="position:relative;">
			<a href="<?php echo WEB_URL; ?>pro_register.php" style="position:absolute;bottom:10px;right:10px;font-size:14px;text-decoration:underline" class="colorBlue bold">Doctors register here</a>
				<div id="hpSingleInputHolder">
					<form id="homeRegister" method="post" action="<?php echo WEB_URL; ?>act/register.php?v2">
					<input type="email" id="hpSingleInput" name="email" placeholder="Enter your email adress" />
					<input type="hidden" name="username" value="user<?php echo time(); ?>" />
					<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
					<input type="hidden" name="gender" value="m" />
					<input type="text" name="hpot" class="hpot" value="" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-large btn-success" value="Join the Community" />
					<?php if(isset($emailSafety)){ echo '<div style="font-size:11px;text-align:left;margin-left:130px;">Your email is confidential and won\'t be shared or sold</div>'; } ?>
					</form>
					
				</div>
			</div>
		</div>
		<?php
		$onload.="$('#hpSingleInput').focus();";
		if(!$jsTopFormIsSet){
			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";
		}
		$jsfunctions.="
		function testEmail(){
			if(isValidEmailAddress($('#hpSingleInput').val())){
				return true;
			}else{
				alert('Invalid email!');
				return false;
			}
		}";
		$onload.="
		$('#homeRegister').submit(function(){
			return testEmail();
		});
		";
		?>
	</div>
</div>