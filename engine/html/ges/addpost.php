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
				<h1 class="colorRed margin10 center">Add Post</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<form method="post" action="<?php echo WEB_URL; ?>ges/addpost/save">
					<input type="text" name="URL" placeholder="Add a URL" style="width:90%;" /><br /><br />
					<input type="submit" />
				</form>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');