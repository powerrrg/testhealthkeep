<?php
onlyLogged();

$pageTitle="Health Details - HealthKeep";
$pageDescr="Change your health details";

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$resProfile=$profileClass->getById(USER_ID);

if(!$resProfile["result"]){
	go404();
}
$active="account";

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull">
			<h2 class="iFullHeading">Health Details</h2>
			<form id="iMhealthForm" class="center" method="post" action="<?php echo WEB_URL; ?>act/profile/healthDetails.php">
				<?php
				$needNumeric=1;
				$onload.="$('.numeric').numeric();";
				?>
				<div>
					<h4>Please enter your weight</h4>
					<input type="text" id="weight" name="weight" <?php if($resProfile[0]["weight_profile"]>0){ echo 'value="'.$resProfile[0]["weight_profile"].'"'; } ?> class="numeric" placeholder="pounds" maxlength="6" style="width:80px;text-align:center;" /> 
				</div>
				<div>
					<h4>Please enter your height</h4>
					<input type="text" id="feets" name="feets" <?php if($resProfile[0]["feet_profile"]>0){ echo 'value="'.$resProfile[0]["feet_profile"].'"'; } ?> class="numeric center" style="width:50px;" maxlength="2" placeholder="feet" /> <input type="text" id="inches" <?php if($resProfile[0]["inch_profile"]>0){ echo 'value="'.(int)$resProfile[0]["inch_profile"].'"'; } ?> name="inches" class="numeric center" style="width:50px;" maxlength="4" placeholder="inches" />
				</div>
 				<div class="clearfix" style="margin-top:20px;">
 					<input type="submit" value="save" class="btn btn-red" style="float:left;" />
 					<a href="<?php echo WEB_URL.USER_NAME; ?>" class="colorBlue" style="float:left;margin:10px 0 0 10px;">cancel</a>
 				</div>
			</form>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');