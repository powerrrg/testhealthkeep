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
		<h1 class="colorRed margin10">Back Office</h1>
		</div>
			<div class="iFull iBoard2 margin20auto">
				<ul>
					<li><a href="<?php echo WEB_URL."ges/stats"; ?>">Stats</a></li>
					<?php /*<li><a href="<?php echo WEB_URL."ges/blog"; ?>">Blog</a></li>*/?>
					<li><a href="<?php echo WEB_URL."ges/emailall"; ?>">Email all users</a></li>
					<li><a href="<?php echo WEB_URL."ges/updatesearch"; ?>">Update Search Database</a></li>
					<li><a href="<?php echo WEB_URL."ges/mcCustom"; ?>">MailChimp Custom NewsLetter</a></li>
					<li><a href="<?php echo WEB_URL."ges/posts"; ?>">New Posts</a></li>
					<li><a href="<?php echo WEB_URL."ges/addpost"; ?>">Add Post by URL</a></li>
					<li><a href="<?php echo WEB_URL."ges/addSimplepost"; ?>">Add Post</a></li>
					<li><a href="<?php echo WEB_URL."ges/topics"; ?>">Topics</a></li>
					<li><a href="<?php echo WEB_URL."ges/badges"; ?>">Badges</a></li>
					<li><a href="<?php echo WEB_URL."ges/top5"; ?>">Top 5 of the week</a></li>
					<li><a href="<?php echo WEB_URL."ges/top5news"; ?>">Top 5 news of the week</a></li>
					<li><a href="<?php echo WEB_URL."ges/blist"; ?>">Black List</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');