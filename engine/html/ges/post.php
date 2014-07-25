<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

if(!isset($_GET["l3"])){
	go404();
}

$id=(int)$_GET["l3"];

if($id==0){
	go404();
}

$pageTitle="Back Office";
$pageDescr="Back Office";

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();

$topicFilter="";
$dashActive="new";

$postToggle="recent";

$resPosts = $postClass->getPostById($id);


$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">New Posts</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<?php
				$jsfunctions.="
				function deletePost(id){
					if(confirm('Are you sure?')){
						$('#iMPost_'+id).slideUp('fast');
						$.ajax({
						  type: 'post',
						  url: '".WEB_URL."act/ajax/newfeed/deletePostBackOffice.php',
						  data: { id: id }
						}).done(function( msg ) {
						  if(msg=='error'){
						  	alert('An error has occurred! Please try again later.');
						  }
						});
					}
				}
				function confirmDelComment(id){
					if(confirm('Are you sure?')){
						$('#comment_'+id).slideUp('fast');
						$.ajax({
						  type: 'post',
						  url: '".WEB_URL."act/ajax/newfeed/delCommentBackOffice.php',
						  data: { id: id }
						}).done(function( msg ) {
						  if(msg!='ok'){
						  	alert('An error has occurred! Please try again later.');
						  }
						});
					}
				}
				";
				
				if($resPosts["result"]){
					$postValue=$resPosts[0];
					$displayName=$configClass->name($postValue, false);
					$imgAltText="";
					if($postValue["image_profile"]==""){
						if($postValue["type_profile"]==4){
							$img=WEB_URL."inc/img/news-avatar.png";
							$imgAltText="News Source Avatar";
						}else{
							$img=WEB_URL."inc/img/empty-avatar.png";
							$imgAltText="No Avatar";
						}
					}else{
						$img=WEB_URL."img/profile/tb/".$postValue["image_profile"];
						$imgAltText=$displayName;
					}
					$time=strtotime($postValue["date_post"]);
				?>
					<div id="postHolder" class="clearfix">
						<div id="iMPost_<?php echo $postValue["id_post"]; ?>">
							<h4><a href="<?php echo WEB_URL.$postValue["username_profile"]; ?>"><?php echo $displayName; ?></a> - <?php echo $configClass->ago($time); ?> - <span style="cursor:pointer;color:red;font-weight:normal;" onclick="deletePost('<?php echo $postValue["id_post"]; ?>')">Delete Post</span></h4>
							<p>
							<?php 
							if($postValue["image_post"]!=''){
								echo '<img src="'.WEB_URL.'img/post/med/'.$postValue["image_post"].'" style="float:right;max-width:300px;" />';
							}
							if($postValue["title_post"]!=''){
								echo '<b>'.$postValue["title_post"].'</b><br />';
							}
							echo $postValue["text_post"]; 
							?>
							</p>
						</div>
						<?php
						$comValue=$postClass->getAllPostComments($postValue["id_post"]);
						if($comValue["result"]){
						?>
						<div>
						<?php
						foreach($comValue as $comKey=>$comValue){
							if(is_int($comKey)){
								if($comValue["type_profile"]==1){
									$comDisplayName=$comValue["username_profile"];
								}else{
									$comDisplayName=$comValue["name_profile"];
								}
								$comImgAltText="";
								$comImg="";
								if($comValue["image_profile"]==""){
									$comImg=WEB_URL."inc/img/news-avatar.png";
									$comImgAltText="News Source Avatar";
								}else{
									$comImg=WEB_URL."img/profile/tb/".$comValue["image_profile"];
									$comImgAltText=$displayName;
								}
								$comTime=strtotime($comValue["date_pc"]);
								?>
								<div id="comment_<?php echo $comValue["id_pc"]; ?>" class="iMPostComment clearfix">
									<a href="<?php echo WEB_URL.$comValue["username_profile"]; ?>"><img src="<?php echo $comImg; ?>" alt="<?php echo $comImgAltText; ?>" class="iMPostCommentImage" width="40" height="40" style="margin-right:15px;" /></a>
									<div class="iMPostCommentHeadingRight">
										<a href="#" onclick="return confirmDelComment('<?php echo $comValue["id_pc"]; ?>');">Delete</a>
									</div>
									<a class="<?php echo $comClass; ?>" href="<?php echo WEB_URL.$comValue["username_profile"]; ?>"><?php echo $comDisplayName; ?></a><span class="iMPostCommentTime"><?php echo $configClass->ago($comTime); ?></span><span class="iMPostCommentVotes">votes: <span id="iMCommentTotalVotes_<?php echo $comValue["id_pc"]; ?>"><?php echo $comValue["thumb_up_pc"]; ?></span></span>
									<p class="iMPostCommentText">
										<?php echo $comValue["text_pc"]; ?>
									</p>
								</div>
								<?php
							}
						}
						?>
						</div>
						<?php
						}
						?>
					</div>
				<?php
				}else{
					echo "Post not found";
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');