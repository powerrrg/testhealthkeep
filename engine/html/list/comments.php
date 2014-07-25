<div class="iMPostComments">
	<?php
	if($postValue["comments_post"]>2 && !isset($showAllComments)){
	?>
	<div class="iMPostCommentsViewMore">
	<a href="<?php echo WEB_URL."post/".$postValue["id_post"]; ?>">view more</a>
	</div>
	<?php	
	}
	if($postValue["comments_post"]>0){
		if(isset($showAllComments)){
			$resCom=$postClass->getAllPostComments($postValue["id_post"]);
		}else{
			$resCom=$postClass->getSomePostComments($postValue["id_post"],2);
		}

		require_once(ENGINE_PATH."html/list/comment.php");
	}
	
	if(USER_ID!=0){
	$formUrl=WEB_URL."act/post/post_comment.php?id=".$postValue["id_post"]."&back=".$backPath."#iMPost_".$postValue["id_post"];
	
	if(!isset($userImageUrl)){
		if(USER_IMAGE==""){
			$userImageUrl=$imagePath=WEB_URL."inc/img/empty-avatar.png";
		}else{
			$userImageUrl=WEB_URL."img/profile/tb/".USER_IMAGE;
		}
	}
	?>	<div id="iMPostCommentNew_<?php echo $postValue["id_post"]; ?>"></div>
	<div id="iMPostCommentNewLoader_<?php echo $postValue["id_post"]; ?>" class="center padding10 borderBottom" style="display:none;" ><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" /></div>
		<div class="iMPostComment clearfix">
			<img src="<?php echo $userImageUrl; ?>" class="iMPostCommentImage" width="40" height="40" />
			<div class="iMPostCommentContent">
				<form action="<?php echo $formUrl; ?>" method="post" class="iMPostCommentForm" onsubmit="return commentSubmit('<?php echo $postValue["id_post"]; ?>',$('#iMPostCommentTA_<?php echo $postValue["id_post"]; ?>').val());" id="iMPostCommentForm_<?php echo $postValue["id_post"]; ?>">
					<textarea onkeyup="testSubmitBtn($(this));" name="text" class="iMPostCommentTA" id="iMPostCommentTA_<?php echo $postValue["id_post"]; ?>" placeholder="Leave a reply"></textarea>
					<input type="submit" class="btn iMPostCommentSubmit btn-blue" id="iMPostCommentSubmit_<?php echo $postValue["id_post"]; ?>" disabled value="Post" />
				</form>
			</div>
		</div>
	<?php
	}
	?>
</div>