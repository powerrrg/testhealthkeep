<?php
if($resProfile[0]["id_profile"]==USER_ID && $resUser[0]["confirmed_email_user"]==0){
if(!isset($needAlert)){
	$needAlert=1;
	$onload.="$('.alert').alert();";
}?>
<div class="alert alert-error">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<strong>Notice! You have not confirmed your email.</strong><br />
	Please check your email and follow the instructions to confirm your email address.<br />
	If you don't see the email in your 'inbox', please look for it in the 'bulk', 'junk' or 'spam' folder.
</div>
<?php
}else if(USER_ID==0){

$jsfunctions.="mixpanel.track('Landing Profile Page NEW');";
?>
<div id="topWarnReg" class="alert alert-error" style="display:none;">
	<a class="close" data-dismiss="alert" href="#">&times;</a>
	<?php 
	echo "<h2 class=\"center\">Share and learn from health experiences</h2>";
	?>
	<form id="homeRegister" method="post" action="<?php echo WEB_URL; ?>doorstep.php">
		<textarea id="homeExperience" name="homeExperience" style="width:100%;height:100px;background:#fff;color:#666;" placeholder="Share a health experience. Share about a condition, medication or symptom. It can be about you or someone else."></textarea><br />
		<input type="text" name="hpot" class="hpot" value="" />
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<input type="hidden" name="profile" value="<?php echo $resProfile[0]["id_profile"]; ?>" />
		<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-large btn-success" value="Share" />
		<div id="homeRegisterAnonInfo">
		Don't worry, it's anonymous
		</div>
	</form>

</div>
<?php
if(!$jsTopFormIsSet){
	$onload.="$('.submitBtn').prop('disabled', false);
	$('input[placeholder]').placeholder();";
}

$onload.="setTimeout(function(){ 
        $('#topWarnReg').slideDown('slow', function(){ pushFooterDown(); });
    }, 1000);";

if(!isset($needAlert)){
$needAlert=1;
$onload.="$('.alert').alert();";
}
$onload.="$('#homeExperience').focus();";
$onload.="
$('#homeRegister').submit(function(){
	if($('#homeExperience').val().length<10){
		alert('You need to add a health experience, question or concern.');
		return false;
	}else{
		return true;
	}
});
";
}
?>