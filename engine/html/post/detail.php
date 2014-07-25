<?php
$id=(int)$_GET["l2"];

if($id==0){
	go404();
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();
$resPosts = $postClass->getPostWithMyVotes($id);

if(!$resPosts["result"]){
	go404();
}


$cleanPostText=strip_tags(preg_replace("/\<br \/\>/", " ", $resPosts[0]["text_post"]));
$cleanPostText=preg_replace("/\"/", "'",$cleanPostText);
$cleanPostText = preg_replace('!\s+!', ' ', $cleanPostText);

if($resPosts[0]["title_post"]==""){
	$cleanPostTitle=substr($cleanPostText, 0,50)."...";
}else{
	$cleanPostTitle=$cleanPostText=preg_replace("/\"/", "'",$resPosts[0]["title_post"]);
}

$pageTitle=$cleanPostTitle." - HealthKeep";
$pageDescr=substr($cleanPostText, 0,150)."...";

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div id="postDetail" class="iBoard">
			<?php
				if(USER_ID==0){
				?>
				<div id="topWarnReg" class="alert alert-error" style="display:none;">
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
				$onload.="setTimeout(function(){ 
			        $('#topWarnReg').slideDown('slow', function(){ pushFooterDown(); });
			    }, 1000);";
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
				$jsfunctions.="mixpanel.track('Not Logged Post Detail Page', {'post':'".$_GET["l1"]."/".$_GET["l2"]."'});";
				}
				?>
			<?php
			$backPath="post/".$id;
			$showAllComments=1;
			?>
			<div id="postHolder">
			<?php require_once(ENGINE_PATH."html/list/posts.php"); ?>
			</div>
			
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');