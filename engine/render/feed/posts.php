<?php
foreach($resPosts as $postKey=>$postValue){
if(is_int($postKey)){
if(isset($iMHealthTL)){
?>
<div class="healthTLdiv">
	<img src="<?php echo WEB_URL; ?>inc/img/v2/base/blueball.png" alt="blue ball timeline marker" class="healthTLimg" />
	<div class="healthTLdivHolder">
	<?php echo date('jS',strtotime($postValue["date_post"])); ?><br />
	<?php echo date('M',strtotime($postValue["date_post"])); ?>
	</div>
</div>
<?php
}
?>
<div id="iMPost_<?php echo $postValue["id_post"]; ?>" class="iMPost clearfix <?php if($postValue["pin_post"]==1){ echo "iMPostPinned";} ?>">
	<?php
	if($postValue["pin_post"]==1){
	 echo '<div id="iMPostPinnedHeader_'.$postValue["id_post"].'" class="iMPostPinnedHeader">HealthKeep Trusted Content</div>';
	}
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
	$postLove=$postValue["social_rank_post"]+$postValue["thumb_up_post"];
	if($postLove>9999){
		$postLove = substr($postLove, 0,2)."K";
	} 
	?>
	<div class="iMPostInfo">
		<?php 
		if(USER_ID!=0){
		?>
		<div class="iMPostInfoHeader2">
			<?php
			if($postValue["vote_pt"]!=""){
			?>
				<div id="iMPostInfoLove_<?php echo $postValue["id_post"]; ?>" class="iMPostInfoLoveLiked" onclick="removeVote('<?php echo $postValue["id_post"]; ?>')">
					<img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart-red.png" alt="Red Heart" /> Liked
				</div>
			<?php
			}else{
			?>
				<div id="iMPostInfoLove_<?php echo $postValue["id_post"]; ?>" class="iMPostInfoLoveLike" onclick="iVote('<?php echo $postValue["id_post"]; ?>')">
					<img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart-white.png" alt="White Heart" /> Like
				</div>
			<?php
			}
			?>
		</div>
		<?php
		}else{
		?>
		<div class="iMPostInfoHeader">
			<?php
			if(USER_ID!=0){
				if($postValue["vote_pt"]!=""){
				?>
					<span id="iMPostInfoLove_<?php echo $postValue["id_post"]; ?>" class="iMPostInfoLove" onclick="removeVote('<?php echo $postValue["id_post"]; ?>')"><span id="iMPostInfoLoveCount_<?php echo $postValue["id_post"]; ?>"><?php echo $postLove; ?></span><span id="iMPostInfoLoveImg_<?php echo $postValue["id_post"]; ?>"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart-red.png" alt="Red Heart" /></span></span>
				<?php
				}else{
				?>
					<span id="iMPostInfoLove_<?php echo $postValue["id_post"]; ?>" class="iMPostInfoLove" onclick="iVote('<?php echo $postValue["id_post"]; ?>')"><span id="iMPostInfoLoveCount_<?php echo $postValue["id_post"]; ?>" <?php if($postLove==0){ echo 'style="display:none;"'; } ?>><?php echo $postLove; ?></span><span id="iMPostInfoLoveImg_<?php echo $postValue["id_post"]; ?>"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart.png" alt="Heart" /></span></span>
				<?php
				}
			}
			if($postValue["comments_post"]>0){
			?>
				<span id="iMPostInfoComments_<?php echo $postValue["id_post"]; ?>" class="iMPostInfoComments" style="color:#5E93CC;cursor:pointer;"><p><?php echo $postValue["comments_post"]; ?></p><img src="<?php echo WEB_URL; ?>inc/img/v2/base/comments-blue.png" alt="Comments Blue" /></span>
			<?php
			}else{
			?>
				<span class="iMPostInfoComments"><p><?php echo $postValue["comments_post"]; ?></p><img src="<?php echo WEB_URL; ?>inc/img/v2/base/comments.png" alt="Comments" /></span>
			<?php
			}
			?>
		</div>
		<?php
		}
			$resPostTopics=$postClass->getPostTopics($postValue["id_post"]);
			if($resPostTopics["result"]){
				foreach($resPostTopics as $paKey=>$paValue){
					if(is_int($paKey)){
						if($paKey<3){
						echo '<div class="iMPostInfoTopic"><a href="'.WEB_URL.$topicClass->pathSingular($paValue["type_topic"])."/".$paValue["url_topic"].'">';
						echo $paValue["name_topic"].'</a></div>';
						}else{
							if($paKey==3){
								echo "<div id=\"iMPostInfoTopicMore_".$postValue["id_post"]."\" onclick=\"showAllTopics('".$postValue["id_post"]."');\" class=\"iMPostInfoTopicMore\">more</div>";
							}
							echo '<div class="iMPostInfoTopic iMPostInfoTopicHidden iMPostInfoTopicHidden_'.$postValue["id_post"].'"><a href="'.WEB_URL.$topicClass->pathSingular($paValue["type_topic"])."/".$paValue["url_topic"].'">';
							echo $paValue["name_topic"].'</a></div>';
						}
					}
				}
			}
			/*else if($postValue["id_profile_post"]==USER_ID || TYPE_USER==9){
			?>
			<div id="topicsHolder_<?php echo $postValue["id_post"]; ?>"></div>
			<div class="alert alert-info" style="margin-top:10px;">
				<p>What element of your health is this post <b>most</b> about?</p>
				<input type="text" class="input100 topic_<?php echo $postValue["id_post"]; ?>" name="topic" />
			</div>
			<?php	
			$needTokenInput=1;
			$onload.="$('.topic_".$postValue["id_post"]."').tokenInput('".WEB_URL."act/ajax/autoCompleteAllTopics.php', { hintText: 'Type the name', noResultsText: 'No health topic with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long', onAdd:function(item){ addTopic('".$postValue["id_post"]."',item.id); } });";
			}*/
			?>
	</div>
	<div class="iMPostMain">
		<div class="iMPostHeader">
			<?php
			if(USER_ID!=0){
				if(USER_ID==$postValue["id_profile"]){
				?>
					<span class="iMPostDelete"><a href="#" onclick="return confirmDelPost('<?php echo $postValue["id_post"]; ?>');"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/delete.png" alt="X" /></a></span>
				<?php
				}else{
					if(USER_TYPE==9){
						?>
						<span class="iMPostDelete"><span style="cursor:pointer;" onclick="return adminDeletePost('<?php echo $postValue["id_post"]; ?>');"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/delete.png" alt="X" /></span></span>
					<?php
					}else{
				?>
						<span class="iMPostReport"><a href="#" onclick="return reportPost('<?php echo $postValue["id_post"]; ?>');"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/report.png" alt="Warning" /></a></span>
				<?php
					}
				}
				if(USER_TYPE==9){
					echo '<span id="iMPostPin_'.$postValue["id_post"].'" class="iMPostPin">';
					if($postValue["pin_post"]==0){
					echo "<a href=\"#\" onclick=\"return togglePostPin('".$postValue["id_post"]."','0');\">";
					?>
					<img src="<?php echo WEB_URL; ?>inc/img/v2/base/pin.png" alt="pin" />
					<?php
					}else{
					echo "<a href=\"#\" onclick=\"return togglePostPin('".$postValue["id_post"]."','1');\">";
					?>
					<img src="<?php echo WEB_URL; ?>inc/img/v2/base/pin_active.png" alt="pinned" />
					<?php
					}
					echo '</a></span>';
				}
				if($postValue["vote_pt"]!=""){
				?>
					<span id="iMPostLove_<?php echo $postValue["id_post"]; ?>" class="iMPostLove" onclick="removeVote('<?php echo $postValue["id_post"]; ?>')">
						<span class="iMPostLoveCountNum" id="iMPostLoveCount_<?php echo $postValue["id_post"]; ?>"><?php echo $postLove; ?></span>
						<span class="iMPostLoveCountText" id="iMPostLoveCountTxt_<?php echo $postValue["id_post"]; ?>">Likes</span>
						<span id="iMPostLoveImg_<?php echo $postValue["id_post"]; ?>">
						<img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart-red.png" alt="Red Heart" />
						</span>
					</span>
				<?php
				}else{
				?>
					<span id="iMPostLove_<?php echo $postValue["id_post"]; ?>" class="iMPostLove" onclick="iVote('<?php echo $postValue["id_post"]; ?>')">
						<span class="iMPostLoveCountNum" id="iMPostLoveCount_<?php echo $postValue["id_post"]; ?>" <?php if($postLove==0){echo 'style="display:none;"';} ?>><?php echo $postLove; ?></span>
						<span class="iMPostLoveCountText" id="iMPostLoveCountTxt_<?php echo $postValue["id_post"]; ?>" <?php if($postLove==0){echo 'style="display:none;"';} ?>>Likes</span>
						<span id="iMPostLoveImg_<?php echo $postValue["id_post"]; ?>">
						<img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart.png" alt="Gray Heart" />
						</span>
					</span>
				<?php
				}
			}
			if(!isset($iMHealthTL)){
			if($postValue["url_post"]!=""){
				$urlToPost=$postValue["url_post"];
			}else{
				$urlToPost=$postValue["id_post"];
			}
			?>
			<span class="iMPostTime"><a href="<?php echo WEB_URL."post/".$urlToPost; ?>"><?php echo $configClass->ago($time); ?></a></span>
			<?php
			}
			?>
			<span class="iMPostUserImg">
				<a href="<?php echo WEB_URL.$postValue["username_profile"]; ?>"><img src="<?php echo $img; ?>" alt="<?php echo $imgAltText; ?>"></a>
			</span>
			<h6 class="iMPostUserName"><a href="<?php echo WEB_URL.$postValue["username_profile"]; ?>"><?php echo $displayName; ?></a></h6>	
		</div>
		<?php
		if($postValue["type_profile"]==2){
		?>
		<img src="<?php echo WEB_URL; ?>inc/img/doctor-icon.png" class="iMPostUserImgDoctor" alt="Doctor Icon">
		<?php
		}
		?>
		<div class="iMPostContent<?php if($postValue["url_post"]!=''){echo " pLinksUnderlined";} ?>">
			<?php
			if($postValue["image_post"]!=""){
				if(isset($postValue["title_post"])){
					$imageAlt=$postValue["title_post"];
				}else{
					$imageAlt=trim(substr(strip_tags($postValue["text_post"]), 0, 25));
				}
				if($postValue["link_post"]!=""){
					$imgLink=$postValue["link_post"];
				}else{
					$imgLink=WEB_URL."img/post/org/".$postValue["image_post"];
				}
			?>
				<a href="<?php echo $imgLink; ?>" target="_blank"><img src="<?php echo WEB_URL."img/post/med/".$postValue["image_post"]; ?>" class="iMPostContentImage" alt="<?php echo $imageAlt; ?>" /></a>
			<?php
			}
			?>
			<div>
				<?php
				if($postValue["title_post"]!=""){ 
					$imageAlt=$postValue["title_post"];
					echo '<h5 class="iMPostTitle">';
					if($postValue["link_post"]!=""){
					echo "<a href=\"".$postValue["link_post"]."\" target=\"_blank\">";
					}
					echo $postValue["title_post"];
					if($postValue["link_post"]!=""){
						echo "</a>";
					}
					echo '</h5>';
				}else if($postValue["link_post"]!=""){
					echo '<h5 class="iMPostTitle">';
					//echo "<a href=\"".$postValue["link_post"]."\" target=\"_blank\">".
					echo '<a href="'.WEB_URL.'post/'.$postValue["id_post"].'">';
					echo $postValue["link_post"]."</a>";
					echo '</h5>';
				}
				$postTxt=$postValue["text_post"];
				if(strlen($postTxt)>500 && !isset($showAllComments)){
					$postTxt0=substr(strip_tags($postTxt), 0,500);
					$postTxt0 = substr($postTxt0, 0, strrpos($postTxt0, ' '))."...";
					$postTxt="<span id=\"postTextPart_".$postValue["id_post"]."\">".$postTxt0.";";
					echo $postTxt;
					echo "<span onclick=\"$('#postTextPart_".$postValue["id_post"]."').hide();$('#postTextFull_".$postValue["id_post"]."').show();\" style=\"text-decoration:underline;color:#5F91CC;cursor:pointer;\">read more</span>";
					echo "</span><span id=\"postTextFull_".$postValue["id_post"]."\" style=\"display:none;\">".$postValue["text_post"];
				}else{
				?>
				<p><?php echo $postTxt; ?></p>
				<?php
				}
				?>
				<div class="starRatyHolder">
					<span>Helpful?</span>
					<div id="starRaty_<?php echo $postValue["id_post"]; ?>" class="starRaty" data-score="<?php echo $postValue["rating_post"]; ?>"></div>
					<b id="starRaty_count_<?php echo $postValue["id_post"]; ?>"><?php if($postValue["rating_count_post"]>0){ echo "(".$postValue["rating_count_post"].")"; } ?></b>
				</div>
			</div>
		</div>
		<?php
		include(ENGINE_PATH."render/feed/comments.php");
		?>
	</div>
</div>
<?php
}
}