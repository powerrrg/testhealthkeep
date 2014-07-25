<?php
onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();
$resProfile=$profileClass->getById(USER_ID);

$pageTitle="Account details - HealthKeep";
$pageDescr="Update your account details";

$active="account";
if($resProfile[0]["type_profile"]=="1"){
	require_once(ENGINE_PATH."render/account/details_user.php");
	exit;
}else if($resProfile[0]["type_profile"]=="2"){
	require_once(ENGINE_PATH."render/account/details_doctor.php");
	exit;
}

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div class="iBoard clearfix">
		<div class="iHeading marginBottom20">
				<h1 class="iHeadingText center colorGray">Account Details</h1>
		</div>
		<div id="updateOK" style="display:none" class="alert alert-success center">
			Account details successfully updated!
		</div>
		<?php
		$onload.="if(window.location.hash=='#ok'){ 
			$('#updateOK').show();
			setTimeout(function () {
				$('#updateOK').slideUp('slow');
				cleanHash();
        	}, 3000);
        	}";
        if($resProfile[0]["type_profile"]=="2"){
        	require_once(ENGINE_PATH."html/account/details_doctor.php");
        }else{
	        require_once(ENGINE_PATH."html/account/details_other.php");
        }
		?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');
