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

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div id="doctorProfile" class="iBoard">
			<?php
			require_once(ENGINE_PATH."html/profile/emailWarning.php");
			?>
			<div class="iHeading clearfix">
				<h1 class="profileHeadingName"><?php echo $resProfile[0]["name_profile"]; ?></h1>
				<?php
				if(USER_ID!=0){
					if(USER_ID!=$resProfile[0]["id_profile"]){	
					$resIfollow=$profileClass->doIFollow($resProfile[0]["id_profile"]);
					if($resIfollow["result"]){
					?>
					<div class="profileHeadingBtns">
						<button class="btn btn-blue" id="followBtn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?no&id=".$resProfile[0]["id_profile"]; ?>'">Following</button>
					</div>
					<?php	
					$onload.="$('#followBtn').hover(function(){
						$(this).text('unfollow');	
					},function(){
						$(this).text('following');
					});";
					
					}else{
					?>
					<div class="profileHeadingBtns">
						<button class="btn btn-red" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'">Follow</button>
					</div>
					<?php
					}
					?>
					
					<?php
					}
				}else{
					?>
					<div class="profileHeadingBtns btn-group">
						<a class="btn btn-blue dropdown-toggle" data-toggle="dropdown">Follow</a>
						<ul class="dropdown-menu pull-right">
							<li><a href="<?php echo WEB_URL; ?>login.php?go=<?php echo $resProfile[0]["username_profile"]; ?>">Login</a></li>
							<li><a href="<?php echo WEB_URL; ?>">Register</a></li>
						</ul>
					</div>
					<?php
				}
				?>
				
			</div>
			<div class="iBoard2 clearfix">
				<div id="profileMain1">
					<div id="profileImage">
						<?php
						if($resProfile[0]["image_profile"]==""){
							$imagePath=WEB_URL."inc/img/empty-avatar.png";
							$imageAlt="No Image Avatar";
						}else{
							$imagePath=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
							$imageAlt=$resProfile[0]["username_profile"];
						}
						if($resProfile[0]["id_profile"]==USER_ID){
						?>
						<img src="<?php echo $imagePath; ?>" id="profileImageTag" alt="<?php echo $imageAlt; ?>" />
						<form action="<?php echo WEB_URL; ?>act/profile/uploadAvatar.php" enctype="multipart/form-data" id="avatarImg" method="post">
							<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload">
							<?php
							if($resProfile[0]["image_profile"]!=""){
							?>
							<a style="display:inline-block;margin-left:15px;" onclick="confirmDelete();" title="delete" class="btn btn-red">x</a>
							<?php
							$jsfunctions.="function confirmDelete(){
								if(confirm('Are you sure you want to delete your profile image?')){
									location.href='".WEB_URL."act/profile/delAvatar.php';
								}
							}";
							}
							?>
							  <span class="btn btn-file btn-blue" style="display:inline-block"><span class="fileupload-new">Change</span>
							  <input type="file" name="avatarFile" id="avatarFile" /></span>
							  
							</div>
							
						</form>
						<?php
						$needFupload=1;
						$onload.="$('#avatarFile').bind('change', function() {
							$('.fileupload-new').hide();
							$('#subImg').hide();
							if(this.files[0]!=undefined && this.files[0].size>2097152){
								alert('The Image cannot have more than 2 MB in size');
								$('.fileupload').fileupload('clear');
								$('.fileupload-new').show();
						  	}else if(this.files[0]!=undefined){
						  		var val = $(this).val();
						  		var val = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
						  		if(val!='gif' && val!='jpg' && val!='jpeg' && val!='png'){
							  		alert('That is not a valid image file!');
						  			$('.fileupload').fileupload('clear');	
						  			$('.fileupload-new').show();		            
						  		}else{
						  			$('#avatarImg').submit();
						  		}
						  	}
						});";
						}else{
						?>
							<img src="<?php echo $imagePath; ?>" id="profileImageTag" alt="<?php echo $imageAlt; ?>" />
						<?php
						}
						?>
					</div>
					<?php
					$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
					$totalFers=0;
					if($res["result"]){
						$totalFers=$res[0]["total"];
					}
					?>
					<div class="profileFollowerBox">
						<div class="padding5_10">
						<img src="<?php echo WEB_URL; ?>inc/img/v1/profile/followers.png" alt="Followers" /><span class="bigBlue marginX5"><?php echo $totalFers; ?></span><span class="followersGray">followers</span>
						</div>
					</div>
			
			
				</div>
				<div id="profileMain2">
					
				</div>
				<div id="profileMain3">
					
				</div>
			</div>
			<?php
			if(USER_ID!=0){
			if(!isset($needAutoGrow)){
				$needAutoGrow=1;
				$onload.="$('textarea').autogrow();";
			}
			?>
			<form class="iPost" id="postAbout" method="post" action="<?php echo WEB_URL; ?>act/post/postAbout.php?id=<?php echo $resProfile[0]["id_profile"]; ?>">
				<textarea placeholder="Share your health experience with <?php echo $resProfile[0]["username_profile"]; ?>" class="textArea100" name="txtPost" id="txtPost"></textarea>
				<div class="iPostBtns">
					<input type="submit" disabled class="btn btn-red submitBtn iPostSubmitBtn" value="share" />
				</div>
			</form>
			<?php
				$onload.="$('#txtPost').keyup(function(){
					if($('#txtPost').val().length>5){
						$('.iPostSubmitBtn').prop('disabled', false);
					}else{
						$('.iPostSubmitBtn').prop('disabled', true);
					}
				});";
				if(!$jsTopFormIsSet){
					$onload.="$('input[placeholder],textarea[placeholder]').placeholder();";
					$jsTopFormIsSet=1;
				}
				$onload.="
				$('#postAbout').submit(function(){
					if($('#txtPost').val().length<5){
						alert('You need to type a message to be able to post!');
						$('#txtPost').focus();
						return false;
					}else{
						return true;
					}
				});
				";
			}
			$resPosts=$postClass->getPostsFromAndAboutUser($resProfile[0]["id_profile"]);
			if($resPosts["result"]){
			$backPath=$resProfile[0]["username_profile"];
			?>
			<div id="profilePostHolder">
			<?php require_once(ENGINE_PATH."html/list/posts.php"); ?>
			</div>
			<?php
			$ajaxUrl=WEB_URL."act/ajax/profile/posts.php";
			$onload.="endlessScroll('$ajaxUrl',$('#profilePostHolder'),".$resProfile[0]['id_profile'].");";
			require_once(ENGINE_PATH."html/inc/endless.php");
			}
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');