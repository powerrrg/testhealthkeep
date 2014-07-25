<?php

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/timeline.class.php');
$timelineClass=new Timeline();

$pageTitle=$resProfile[0]["username_profile"]." - HealthKeep";
$pageDescr="HealthKeep profile page for ".$resProfile[0]["username_profile"].".";

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		
		<?php
		if(USER_ID==0){
		$token=sha1(microtime(true).mt_rand(10000,90000));
		$_SESSION["token"]=$token;
		?>
		<div id="feedCTA">
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
		$jsfunctions.="mixpanel.track('Profile Page V2 New Design');";
		echo "</div>";
		}else if($resProfile[0]["id_profile"]==USER_ID && $resUser[0]["confirmed_email_user"]==0){
		/*
		HIDE confirmation email notice. The email still goes but it doesn't matter if users cconfirms or not.
		?>
			<div class="alert alert-error" style="margin:30px 0 30px 0;">
				<strong>Notice! You have not confirmed your email.</strong><br />
				Please check your email and follow the instructions to confirm your email address.<br />
				If you don't see the email in your 'inbox', please look for it in the 'bulk', 'junk' or 'spam' folder.
			</div>
		<?php
		*/
		}
		?>
		<hgroup id="profileTop" class="clearfix">
			<div id="profileInfoOthers">
				<div id="profileBio" class="clearfix">
					<?php
					if($resProfile[0]["image_profile"]==""){
						$imagePath=WEB_URL."inc/img/empty-avatar.png";
						$imageAlt="No Image Avatar";
					}else{
						$imagePath=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
						$imageAlt=$resProfile[0]["username_profile"];
					}
					?>
					<div id="profileAvatar">
						<?php
						if($resProfile[0]["id_profile"]==USER_ID){
						?>
						<a href="<?php echo WEB_URL; ?>avatar">
						<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
						<span id="profileAvatarChange">change</span>
						</a>
						<?php
						$onload.="$('#profileAvatar a').hover(function(){ 
							$(this).css('opacity','.5'); 
							$('#profileAvatarChange').show();
						}, function(){ 
							$(this).css('opacity','1'); 
							$('#profileAvatarChange').hide();
						});";
						}else{
						?>
							<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
						<?php
						}
						?>
					</div>
					<div id="profileBioDetails">
						<h1 id="profileUserNameOthers"><?php echo $configClass->name($resProfile); ?></h1>
					</div>
				</div>
				<div id="profileInfoDetails">
					<hgroup>
						<?php
						$countFollowingUsers=$profileClass->countFollowing($resProfile[0]["id_profile"]);
						$totalUsers=0;
						if($countFollowingUsers["result"]){
							$totalUsers=$countFollowingUsers[0]["total"];
						}
						if($totalUsers>0){
							echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/following/\" class=\"underlineAlternative\">";	
						}
						echo "<b style=\"padding-right:10px;\">Following:</b> <span class=\"color999\">".$totalUsers."</span>";
						if($totalUsers>0){
							echo "</a>";	
						}
						$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
						$totalFers=0;
						if($res["result"]){
							$totalFers=$res[0]["total"];
						}
						if($totalFers>0){
							echo "<a href=\"".WEB_URL.$resProfile[0]["username_profile"]."/followers/\" class=\"underlineAlternative\">";	
						}
						echo "<b style=\"margin-left:50px;padding-right:10px;\">Followers:</b> <span class=\"color999\">".$totalFers."</span>";
						if($totalFers>0){
							echo "</a>";	
						}
						?>
					</hgroup>
					<?php
					if(USER_ID!=0){
						if(USER_ID!=$resProfile[0]["id_profile"]){	
							$resIfollow=$profileClass->doIFollow($resProfile[0]["id_profile"]);
							if($resIfollow["result"]){
							?>
								<div class="profileUserBtnFollow">
									<button class="btn btn-red" id="followBtn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?no&id=".$resProfile[0]["id_profile"]; ?>'">Following</button>
								</div>
								<?php	
								$onload.="$('#followBtn').hover(function(){
									$(this).text('unfollow');	
								},function(){
									$(this).text('following');
								});";
								
								}else{
								?>
								<div class="profileUserBtnFollow">
									<button class="btn btn-blue" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'">Follow</button>
								</div>
							<?php
							}
						}
					}
					?>
				</div>
			</div>
		</hgroup>
		<hgroup id="profileBottom" class="clearfix">
			<?php
			if(USER_ID!=0){
			?>
			<div id="profileCTA">
			<?php require_once(ENGINE_PATH."render/feed/cta.php"); ?>
			</div>
			<?php
			}
			$resPosts=$postClass->getPostsFromAndAboutUser($resProfile[0]["id_profile"]);
			if($resPosts["result"]){
			$backPath=$resProfile[0]["username_profile"];
			?>
			<div id="postHolder" class="clearfix">
			<?php require_once(ENGINE_PATH."render/feed/list.php"); ?>
			</div>
			<?php
			$ajaxUrl=WEB_URL."act/ajax/profile/postsNew.php";
			$onload.="endlessScroll('$ajaxUrl',$('#iHoldPosts'),".$resProfile[0]['id_profile'].");";
			require_once(ENGINE_PATH."render/feed/endless.php");
			}
			?>
		</hgroup>
	</hgroup>	
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');