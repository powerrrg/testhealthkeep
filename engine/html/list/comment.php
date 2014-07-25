<?php
if($resCom["result"]){
	foreach($resCom as $comKey=>$comValue){
		if(is_int($comKey)){
		if($comValue["type_profile"]==1){
			$comDisplayName=$comValue["username_profile"];
		}else{
			$comDisplayName=$comValue["name_profile"];
		}
		$comImgAltText="";
		$comImg="";
		if($comValue["image_profile"]==""){
			if($comValue["type_profile"]==1 && $comValue["gender_profile"]=="m"){
				$comImg=WEB_URL."inc/img/avatar/avatar_male.jpg";
				$comImgAltText="Man avatar";
			}else if($comValue["type_profile"]==1 && $comValue["gender_profile"]=="f"){
				$comImg=WEB_URL."inc/img/avatar/avatar_female.jpg";
				$comImgAltText="Woman avatar";
			}else if($comValue["type_profile"]==2){
				$comImg=WEB_URL."inc/img/avatar/avatar_doctor.jpg";
				$comImgAltText="Doctor avatar";
			}else if($comValue["type_profile"]==3){
				$comImg=WEB_URL."inc/img/avatar/avatar_facility.jpg";
				$comImgAltText="Professional avatar";
			}else if($comValue["type_profile"]==4){
				$comImg=WEB_URL."inc/img/avatar/avatar_news.jpg";
				$comImgAltText="News avatar";
			}else{
				$comImg=WEB_URL."inc/img/avatar/avatar_male.jpg";
				$comImgAltText="Man avatar";
			}
		}else{
			$comImg=WEB_URL."img/profile/tb/".$comValue["image_profile"];
			$comImgAltText=$displayName;
		}
		$comTime=strtotime($comValue["date_pc"]);
		if($comKey % 2 == 0){
			$comClass="colorBlue";
		}else{
			$comClass="colorRed";
		}
		?>
		<div id="comment_<?php echo $comValue["id_pc"]; ?>" class="iMPostComment clearfix">
			<a href="<?php echo WEB_URL.$comValue["username_profile"]; ?>"><img src="<?php echo $comImg; ?>" alt="<?php echo $comImgAltText; ?>" class="iMPostCommentImage" width="40" height="40" /></a>
			<div class="iMPostCommentContent">
				<div class="iMPostCommentHeading">
					<?php
					if(USER_ID!=0){
					?>
					<div class="iMPostCommentHeadingRight">
					<?php
						if($comValue["vote_pct"]!=""){
						?>
						<button id="iMCommentLikeButton_<?php echo $comValue["id_pc"]; ?>" onclick="removeCommentVote('<?php echo $comValue["id_pc"]; ?>');" class="iMPostHeadingBtns iMPostHeadingTupActive">&nbsp;</button>
						<?php
						}else{
						?>
						<button id="iMCommentLikeButton_<?php echo $comValue["id_pc"]; ?>" onclick="iCommentVote('<?php echo $comValue["id_pc"]; ?>');" class="iMPostHeadingBtns iMPostHeadingTup">&nbsp;</Button>
					<?php
						}
						?>
						<?php	
						if(USER_ID==$comValue["id_profile"]){
						?>
						<span class="btn-group">
						<a href="#" class="iMPostCommentHeadingBtns iMPostCommentHeadingOptions dropdown-toggle" data-toggle="dropdown">&nbsp;</a>
						<ul class="dropdown-menu pull-right">
							<li><a href="#" onclick="return confirmDelComment('<?php echo $comValue["id_pc"]; ?>');">Delete</a></li>
						</ul>
						</span>
						<?php
						}
						?>
					</div>
					<?php
					}
					?>
					<a class="<?php echo $comClass; ?>" href="<?php echo WEB_URL.$comValue["username_profile"]; ?>"><?php echo $comDisplayName; ?></a><span class="iMPostCommentTime"><?php echo $configClass->ago($comTime); ?></span><span class="iMPostCommentVotes">votes: <span id="iMCommentTotalVotes_<?php echo $comValue["id_pc"]; ?>"><?php echo $comValue["thumb_up_pc"]; ?></span></span>
					<p class="iMPostCommentText">
						<?php echo $comValue["text_pc"]; ?>
					</p>
				</div>
			
			</div>
		</div>
		<?php
		}
	}
}