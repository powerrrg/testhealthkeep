<?php




require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();
$id=(int)$_GET["l2"];
if($id>0){
	
	$resPosts = $postClass->getPostWithMyVotes($id);	
}else{
	$url=urlencode($_GET["l2"]);
	$resPosts = $postClass->getPostWithMyVotesByURL($url);
}


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

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix" style="padding:30px 0;">
		<?php
		if(USER_ID==0){
		$token=sha1(microtime(true).mt_rand(10000,90000));
		$_SESSION["token"]=$token;
		?>
		<div id="feedCTA" style="margin-top:0;">
		<div id="feedSignUp">
			<h2>Share and learn with others like you</h2>
			<form id="homeRegister" method="post" class="clearfix" action="<?php echo WEB_URL; ?>act/registerNewDesign.php">
				<input type="email" id="hpSingleInput" name="email" placeholder="Enter your email adress" />
				<input type="hidden" name="username" value="user<?php echo time(); ?>" />
				<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
				<input type="hidden" name="gender" value="m" />
				<input type="text" name="hpot" class="hpot" value="" />
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<div class="clearfix">
					
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-red" value="Sign Up" />
				</div>
			</form>
		</div>
		<?php
		$onload.="$('#hpSingleInput').focus();";
	
		$onload.="$('.submitBtn').prop('disabled', false);
				$('input[placeholder]').placeholder();";
	
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
		$_SESSION["mx_signup"]=1;
		$jsfunctions.="mixpanel.track('Post Details Page V2 New Design');";
		echo "</div>";
		}
		$backPath="post/".$id;
		$showAllComments=1;
		?>
		<div id="postHolder">
		<?php require_once(ENGINE_PATH."render/feed/list.php"); ?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');