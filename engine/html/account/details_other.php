<?php

?>
<form action="<?php echo WEB_URL; ?>act/account/details_other.php" id="steps" method="post" class="addEventForm borderTop">
	<div class="addEventFormItem clearfix">
		<h4>Name</h4>
		<div class="addEventFormInputs">
		<input type="text" id="name" name="name" maxlength="150" class="input100" value="<?php echo $resProfile[0]["name_profile"]; ?>"  />
		</div>
	</div>

	<div class="addEventFormButtons clearfix">
		<input type="submit" class="btn btn-blue" value="save" />
	</div>
</form>
<?php

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