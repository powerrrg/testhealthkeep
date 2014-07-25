<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

ini_set('display_errors','1');
error_reporting(E_ALL | E_STRICT);

if(isset($_GET["l3"])){
	require_once(ENGINE_PATH.'class/search.class.php');
	$searchClass=new Search();
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 0);
	
	if($_GET["l3"]=="posts"){
		$searchClass->updatePosts();
	}else if($_GET["l3"]=="posts2"){
		$searchClass->updatePosts2();
	}else if($_GET["l3"]=="comments"){
		$searchClass->updateComments();
	}else if($_GET["l3"]=="topics"){
		$searchClass->updateTopics();
	}else if($_GET["l3"]=="profiles1"){
		$searchClass->updateUsers1();
	}else if($_GET["l3"]=="profiles2"){
		$searchClass->updateUsers2();
	}else if($_GET["l3"]=="profiles3"){
		$searchClass->updateUsers3();
	}else if($_GET["l3"]=="profiles4"){
		$searchClass->updateUsers4();
	}else if($_GET["l3"]=="profiles5"){
		$searchClass->updateUsers5();
	}else if($_GET["l3"]=="profiles6"){
		$searchClass->updateUsers6();
	}else if($_GET["l3"]=="profiles7"){
		$searchClass->updateUsers7();
	}else{
		go404();
	}
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
				<h1 class="colorRed margin10 center">Update Search Database</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<ul>
					<?php
					if(isset($_GET["l3"])){
					?>
					<div id="result" class="alert alert-success center">
						All <?php echo $_GET["l3"]; ?> were updated
					</div>
					<?php
					$onload.="
					setTimeout(function(){ $('#result').slideUp(); }, 2000);
					";
					}
					$jsfunctions.="
					function confirmUpdate(url){
						if(confirm('Are you sure you want to do that? Updating can stop all searches of the site for several minutes!!!')){
							location.href=url;
						}
						return false;
					}
					";
					?>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/posts');">Update Posts</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/posts2');">Update Posts 2</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/comments');">Update Comments</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/topics');">Update Topics</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles1');">Update Profiles Step 1</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles2');">Update Profiles Step 2</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles3');">Update Profiles Step 3</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles4');">Update Profiles Step 4</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles5');">Update Profiles Step 5</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles6');">Update Profiles Step 6</a></li>
					<li><a href="#" onclick="return confirmUpdate('<?php echo WEB_URL; ?>ges/updatesearch/profiles7');">Update Profiles Step 7</a></li>
				</ul>
				<div style="border-top:1px solid #ccc;margin-top:20px;font-size:12px;color:#666;padding:10px 20px 0">
					Notice: After you click do not leave the page until you see the green message. Updating can take several minutes.
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');