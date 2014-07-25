<div class="iMPostComments" <?php if($postValue["comments_post"]==0 && USER_ID==0){ echo 'style="display:none;"'; } ?>>
	<?php
	if($postValue["comments_post"]>0 && !isset($showAllComments)){
	?>
		<div id="iMPostMoreComments_<?php echo $postValue["id_post"]; ?>" class="iMPostMoreComments"><p>View Replies (<?php echo $postValue["comments_post"]; ?>)</p></div>
	<?php
	}
	if($postValue["comments_post"]>0){
		$resCom=$postClass->getAllPostComments($postValue["id_post"]);
		if($resCom["result"]){
			if(!isset($showAllComments)){
				echo "<div id=\"iMPostCommentsHolder_".$postValue["id_post"]."\" class=\"iMPostCommentsHolder\" style=\"display:none;\">";	
			}else{
				echo "<div id=\"iMPostCommentsHolder_".$postValue["id_post"]."\" class=\"iMPostCommentsHolder\">";
			}
			
			include(ENGINE_PATH."render/feed/comment.php");
			echo "</div>";
		}
	}
	if(USER_ID!=0){
		if(USER_IMAGE==""){
			$userImageUrl=$imagePath=WEB_URL."inc/img/empty-avatar.png";
		}else{
			$userImageUrl=WEB_URL."img/profile/tb/".USER_IMAGE;
		}
	?>
		<div id="iMPostCommentNew_<?php echo $postValue["id_post"]; ?>"></div>
		<div id="iMPostCommentNewLoader_<?php echo $postValue["id_post"]; ?>" class="iMPostCommentNewLoader" style="display:none;" ><img src="<?php echo WEB_URL; ?>inc/img/v1/inc/ajax-loader-bar.gif" /></div>
		<div class="iMPostLeaveComment clearfix">
			<img src="<?php echo $userImageUrl; ?>" />
			<div>
			<textarea name="iMPostCommentTA_<?php echo $postValue["id_post"]; ?>" class="iMPostCommentTA" id="iMPostCommentTA_<?php echo $postValue["id_post"]; ?>" placeholder="Leave a reply"></textarea>
			<input type="button" onclick="subComment('<?php echo $postValue["id_post"]; ?>',$('#iMPostCommentTA_<?php echo $postValue["id_post"]; ?>').val());$('#iMPostCommentTA_<?php echo $postValue["id_post"]; ?>').val('');" value="Post" class="btn btn-red btn-comment" />
			</div>
		</div>
	<?php
	}
	?>
</div>