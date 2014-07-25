<?php
if(!isset($_GET["l2"]) || strlen($_GET["l2"])<2){
	header ('HTTP/1.1 301 Moved Permanently');
	header("Location:".WEB_URL."feed");
	exit;
}

$q=urldecode($_GET["l2"]);

require_once(ENGINE_PATH.'class/search.class.php');
$searchClass=new Search();

$pageTitle="Results for ".$q." - HealthKeep";
$pageDescr="Search results for ".$q." from all of HealthKeep's content.";

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div id="iFeed" class="iBoard  clearfix">
			<div id="iFeedContent">
				<div id="postDetail">
					<?php
					if(USER_ID==0 && !isset($pageNum)){
					?>
					<div class="alert alert-info">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<h2>Did you know?</h2>
					If you create an account you will get a personalized health feed, the ability to post and share, and a personal health timeline to track and manage your health.<br />
					<form id="boxRegister" method="post" style="text-align:center;margin-top:20px;" action="<?php echo WEB_URL; ?>act/register.php?v2">
						<input type="email" id="hpSingleInputSmall" name="email" placeholder="Enter your email adress" />
						<input type="hidden" name="username" value="user<?php echo time(); ?>" />
						<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
						<input type="hidden" name="gender" value="m" />
						<input type="text" name="hpot" class="hpot" value="" />
						<input type="hidden" name="token" value="<?php echo $token; ?>" /><br />
						<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-success" value="Create Account" />
						</form>
	
					</div>
					<?php
					if(!$jsTopFormIsSet){
						$onload.="$('.submitBtn').prop('disabled', false);
						$('input[placeholder]').placeholder();";
					}
					
					if(!isset($needAlert)){
					$needAlert=1;
					$onload.="$('.alert').alert();";
					}
					$onload.="$('#hpSingleInputSmall').focus();";
					$jsfunctions.="
					function testEmail(){
						if(isValidEmailAddress($('#hpSingleInputSmall').val())){
							return true;
						}else{
							alert('Invalid email!');
							return false;
						}
					}";
					$onload.="
					$('#boxRegister').submit(function(){
						return testEmail();
					});
					";
					$jsfunctions.="mixpanel.track('Not Logged Search Result Page', {'search':'".$_GET["l2"]."'});";
					}
					?>
					<div class="iHeading clearfix marginBottom20">
					<h3 class="feedHeading"><span class="colorLighterBlue">Search</span> <span class="colorGray">results</span></h3>
					</div>
					<?php
					$resSearch = $searchClass->search($q);
					?>
					<div id="postHolder" class="clearfix">
						<?php require_once(ENGINE_PATH."html/list/search_list.php"); ?>
					</div>
					<?php
					$ajaxUrl=WEB_URL."act/ajax/feed/search.php";
					$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'$q');";
					require_once(ENGINE_PATH."html/inc/endless.php");
					?>
				</div>
			</div>
			<?php
			$dashActive="search";
			require_once(ENGINE_PATH."html/inc/feedSidebar.php");
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');