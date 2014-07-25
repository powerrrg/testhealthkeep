<?php
if(!isset($noHolderDiv)){
?>
<div id="iHoldDiscussion" class="clearfix">
<div id="iHoldPosts">
<?php
}
/*
Needed to remove shortcuts because when you type in search it moves on click kj
$needScrollTo=1;
$jsfunctions.="
	function scroll(direction) {
        var scroll, i, positions = [],
            here = $(window).scrollTop(),
            collection = $('.iMPost');

        collection.each(function () {
            positions.push(parseInt($(this).offset()['top'], 10));
        });
        for (i = 0; i < positions.length; i++) {
            if (direction == 'next' && positions[i] > here) {
                scroll = collection.get(i);
                break;
            }
            if (direction == 'prev' && i > 0 && positions[i] >= here) {
                scroll = collection.get(i - 1);
                break;
            }
        }
        if (scroll) {
            $.scrollTo(scroll, {
                duration: 250
            });
        }
        return false;
    }
";

$onload.="
	$(window).keydown (function(event) {
        switch (event.which) {
            case 74:
            case 39:
                scroll ('next');
                break;
            case 75:
            case 37:
                scroll ('prev');
                break;
        }
	});
    $('#next,#prev').click(function() {        
         return scroll($(this).attr('id'));
    });";
*/
$jsfunctions.="
function iVote(id){
	var ele=$('#iMPostLikeButton_'+id);
	ele.fadeOut(100).fadeIn(500);
	ele.removeClass('iMPostHeadingTup').addClass('iMPostHeadingTupActive');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_thumb.php',
	  data: { id: id, vote: 'up' }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	ele.removeClass('iMPostHeadingTupActive').addClass('iMPostHeadingTup');
	  }else{
	  	updateTotalVotes(id,'+1');
	 	ele.attr('onClick','removeVote('+id+');');
	  }
	});
}";

$jsfunctions.="
function iCommentVote(id){
	var ele=$('#iMCommentLikeButton_'+id);
	ele.fadeOut(100).fadeIn(500);
	ele.removeClass('iMPostHeadingTup').addClass('iMPostHeadingTupActive');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_comment_thumb.php',
	  data: { id: id, vote: 'up' }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	ele.removeClass('iMPostHeadingTupActive').addClass('iMPostHeadingTup');
	  }else{
	  	updateTotalCommentVotes(id,'+1');
	 	ele.attr('onClick','removeCommentVote('+id+');');
	  }
	});
}";
$jsfunctions.="
function updateTotalCommentVotes(id,addNum){
var ele=$('#iMCommentTotalVotes_'+id);
var total=parseInt($('#iMCommentTotalVotes_'+id).html());
ele.html(total+parseInt(addNum));
}
";
$jsfunctions.="
function updateTotalVotes(id,addNum){
var ele=$('#iMPostTotalVotes_'+id);
var total=parseInt($('#iMPostTotalVotes_'+id).html());
ele.html(total+parseInt(addNum));
}
";
$jsfunctions.="
function removeVote(id){
	var ele=$('#iMPostLikeButton_'+id);
	ele.fadeOut(100).fadeIn(500);
	ele.removeClass('iMPostHeadingTupActive').addClass('iMPostHeadingTup');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_thumb_remove.php',
	  data: { id: id }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	ele.removeClass('iMPostHeadingTup').addClass('iMPostHeadingTupActive');
	  }else{
	  	updateTotalVotes(id,'-1');
	  	ele.attr('onClick','iVote('+id+');');
	  }
	});
}";
$jsfunctions.="
function removeCommentVote(id){
	var ele=$('#iMCommentLikeButton_'+id);
	ele.fadeOut(100).fadeIn(500);
	ele.removeClass('iMPostHeadingTupActive').addClass('iMPostHeadingTup');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_comment_thumb_remove.php',
	  data: { id: id }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	ele.removeClass('iMPostHeadingTup').addClass('iMPostHeadingTupActive');
	  }else{
	  	updateTotalCommentVotes(id,'-1');
	  	ele.attr('onClick','iCommentVote('+id+');');
	  }
	});
}";

if(!isset($needAutoGrow)){
	$needAutoGrow=1;
	$onload.="$('textarea').autogrow();";
}

if(!$jsTopFormIsSet){
	$onload.="$('input[placeholder],textarea[placeholder]').placeholder();";
	$jsTopFormIsSet=1;
}

$jsfunctions.="
function commentSubmit(id,text){
	$('#iMPostCommentNewLoader_'+id).show();
	$('#iMPostCommentTA_'+id).val('');
	$.ajax({
	  type: 'post',
	  url: '".WEB_URL."act/ajax/post/post_comment.php',
	  data: { id: id,text:text }
	}).done(function( msg ) {
		$('#iMPostCommentNewLoader_'+id).hide();
	  if(msg=='error'){
	  	alert('An error has occurred! Please try again later.');
	  }else{
	  	$('#iMPostCommentNew_'+id).append(msg);
	  }
	});
		
	return false;

}


";

$jsfunctions.="
function testSubmitBtn(ele){
	if(ele.val().length>5){
		ele.parent().children('.iMPostCommentSubmit').prop('disabled', false);
	}else{
		ele.parent().children('.iMPostCommentSubmit').prop('disabled', true);
	}
}";


function displayContent($postValue,&$onload,$showall=false){
	if($postValue["video_post"]!=''){
	?>
	<p id="video1" class="clearfix marginBottom20" >
	<?php echo $postValue["video_post"]; ?></p>
	<?php
	$onload.="$('#video1 object').prepend(><param name=\"PLAY\" value=\"false\" />)";
	}
	if($postValue["image_post"]!=""){
	
	if(isset($postValue["title_post"])){
		$imageAlt=$postValue["title_post"];
	}else{
		$imageAlt=substr(strip_tags($postValue["text_post"]), 0, 25);
	}
	
	?>
	<img src="<?php echo WEB_URL."img/post/med/".$postValue["image_post"]; ?>" class="iMPostContentImage clearfix" alt="<?php echo $imageAlt; ?>" />
	<?php
	}
	if($showall){
		echo "<p>".$postValue["text_post"]."<p>";
	}else{
		/*if(strlen($postValue["text_post"])>500){
			echo "<p>".mb_substr($postValue["text_post"],0,400)." [...]<p>";
		}else{*/
			echo "<p>".$postValue["text_post"]."<p>";
		//}
	}
}

$needFitVid=1;
$onload.="$('.iMPostContent').fitVids();";

foreach($resPosts as $postKey=>$postValue){
if(is_int($postKey)){
?>
<div id="iMPost_<?php echo $postValue["id_post"]; ?>" class="iMPost clearfix">
	<?php
	if($postValue["type_profile"]==1){
		$displayName=$postValue["username_profile"];
	}else{
		$displayName=$postValue["name_profile"];
	}
	$imgAltText="";
	if($postValue["image_profile"]==""){
		if($postValue["type_profile"]==1 && $postValue["gender_profile"]=="m"){
			$img=WEB_URL."inc/img/avatar/avatar_male.jpg";
			$imgAltText="Man avatar";
		}else if($postValue["type_profile"]==1 && $postValue["gender_profile"]=="f"){
			$img=WEB_URL."inc/img/avatar/avatar_female.jpg";
			$imgAltText="Woman avatar";
		}else if($postValue["type_profile"]==2){
			$img=WEB_URL."inc/img/avatar/avatar_doctor.jpg";
			$imgAltText="Doctor avatar";
		}else if($postValue["type_profile"]==3){
			$img=WEB_URL."inc/img/avatar/avatar_facility.jpg";
			$imgAltText="Professional avatar";
		}else if($postValue["type_profile"]==4){
			$img=WEB_URL."inc/img/avatar/avatar_news.jpg";
			$imgAltText="News avatar";
		}else{
			$img=WEB_URL."inc/img/avatar/avatar_male.jpg";
			$imgAltText="Man avatar";
		}
	}else{
		$img=WEB_URL."img/profile/tb/".$postValue["image_profile"];
		$imgAltText=$displayName;
	}
	$time=strtotime($postValue["date_post"]);

	?>
	<div class="iMPostLeft">
		<span class="iMPostImage">
			<a href="<?php echo WEB_URL.$postValue["username_profile"]; ?>">
				<img src="<?php echo $img; ?>" alt="<?php echo $imgAltText; ?>" width="60" height="60">
			</a>
		</span>
		<span id="iMPostTotalVotes_<?php echo $postValue["id_post"]; ?>" class="iMPostTotalVotes">
			<?php 
			if($postValue["social_rank_post"]>9999){
				echo substr($postValue["social_rank_post"], 0,2)."K";
			}else{
			echo $postValue["social_rank_post"];
			} 
			?>
		</span>
		<?php
		if($postValue["title_post"]!=""){
			$addTitle=substr($postValue["title_post"], 0,150);
		}else{
			$addTitle=substr(strip_tags($postValue["text_post"]), 0,150);
		}
		
		$addDescr=substr(strip_tags($postValue["text_post"]), 0,400);
		?>
		
		<div class="addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="<?php echo WEB_URL."post/".$postValue["id_post"]; ?>"
        addthis:title="<?php echo $addTitle; ?>"
        addthis:description="<?php echo $addDescr; ?>" style="margin:16px;">
		<a class="addthis_button_compact"></a>
		</div>
		
	</div>
	<div class="iMPostRight">
		<div class="iMPostMain">
			<span class="iMPostPointer"></span>
			<div class="iMPostHeading clearfix">
				<span class="iMPostHeadingLeft">
					<a href="<?php echo WEB_URL.$postValue["username_profile"]; ?>" class="imPostAuthor colorRed marginLeft20"><?php echo $displayName; ?></a>
					<a href="<?php echo WEB_URL."post/".$postValue["id_post"]; ?>" class="iMPostTime"><?php echo $configClass->ago($time); ?></a>
				</span>
				<span class="iMPostHeadingRight">
					
					<?php
					if(USER_ID!=0){
						if($postValue["vote_pt"]!=""){
						?>
						<button id="iMPostLikeButton_<?php echo $postValue["id_post"]; ?>" onclick="removeVote('<?php echo $postValue["id_post"]; ?>');" class="iMPostHeadingBtns iMPostHeadingTupActive">&nbsp;</button>
						<?php
						}else{
						?>
						<button id="iMPostLikeButton_<?php echo $postValue["id_post"]; ?>" onclick="iVote('<?php echo $postValue["id_post"]; ?>');" class="iMPostHeadingBtns iMPostHeadingTup">&nbsp;</button>
						<?php
						}
						
						if(USER_ID==$postValue["id_profile"]){
						?>
						<span class="btn-group">
						<a href="#" class="iMPostHeadingBtns iMPostHeadingOptions dropdown-toggle" data-toggle="dropdown">&nbsp;</a>
						<ul class="dropdown-menu pull-right">
							<li><a href="#" onclick="return confirmDelPost('<?php echo $postValue["id_post"]; ?>');">Delete</a></li>
						</ul>
						</span>
						<?php
						}
					}
					?>
				</span>
			</div>
			<div class="iMPostContent clearfix">
			<?php
			if($postValue["title_post"]!=""){ 
				$imageAlt=$postValue["title_post"];
				echo '<div class="iMPostTitle">';
				if($postValue["id_profile_post"]=="721528"){
					echo '<a href="'.WEB_URL.'post/'.$postValue["id_post"].'">';
				}else if($postValue["link_post"]!=""){
					echo "<a href=\"".$postValue["link_post"]."\" target=\"_blank\">";
					//echo '<a href="'.WEB_URL.'post/'.$postValue["id_post"].'">';
				}
				echo $postValue["title_post"];
				if($postValue["link_post"]!="" || $postValue["id_profile_post"]=="721528"){
					echo "</a>";
				}
				echo '</div>';
			}else if($postValue["link_post"]!=""){
				echo '<div class="iMPostTitle">';
				//echo "<a href=\"".$postValue["link_post"]."\" target=\"_blank\">".
				echo '<a href="'.WEB_URL.'post/'.$postValue["id_post"].'">';
				echo $postValue["link_post"]."</a>";
				echo '</div>';
			}
			
			if($postValue["id_profile_post"]=="721528"){
				echo "<p>";
					$resStories = $postClass->getStories($postValue["id_post"]);
					if($resStories["result"]){
						foreach($resStories as $keyrs=>$valuers){
							if(is_int($keyrs)){
								if($valuers["image_profile"]==""){
									$imagePath=WEB_URL."inc/img/empty-avatar.png";
									$imageAlt="No Image Avatar";
								}else{
									$imagePath=WEB_URL."img/profile/tb/".$valuers["image_profile"];
									$imageAlt=$configClass->name($valuers,false);
								}
								?>
								<div class="iMPostContentStory clearfix">
									<div class="iMPostContentStoryHeader clearfix">
									<?php
									$time=strtotime($valuers["date_post"]);
									?>
									<a href="<?php echo WEB_URL.$valuers["username_profile"]; ?>"><img src="<?php echo $imagePath; ?>" title="<?php echo $imageAlt; ?>" alt="<?php echo $imageAlt; ?>" /> <span class="iMPostContentStoryHeaderTitle"><a href="<?php echo WEB_URL."post/".$valuers["id_post"]; ?>"><?php echo $valuers["title_post"]; ?></a> - <?php echo $configClass->ago($time); ?></span>
									
									</div>
									<div class="iMPostContentStoryContent">
									<?php
									if(isset($showAllComments)){
										displayContent($valuers,$onload,true);
									}else{
										displayContent($valuers,$onload);
									}
									?>
									</div>
								</div>
								<?php
							}
						}
					}
					$resTemps = $postClass->getTemps($postValue["id_post"]);
					if($resTemps["result"]){
						foreach($resTemps as $keyrs=>$valuers){
							if(is_int($keyrs)){
								
								?>
								<div class="iMPostContentTemp clearfix">
									<div class="iMPostContentTempHeader">
									<a href="<?php echo $valuers["url_temp"]; ?>"><?php echo $valuers["title_temp"]; ?></a>
									<?php
									$sourceTemp=parse_url($valuers["url_temp"]);
									if(isset($sourceTemp["host"])){
										echo ' - <a href="https://'.$sourceTemp["host"].'" alt="'.$sourceTemp["host"].'" class="colorGray" >'.$sourceTemp["host"].'</a>';
									}
									?>
									</div>
									<div class="iMPostContentTempContent">
									<?php
									if($valuers["image_temp"]!=""){
										$tempStyle="";
										if(isset($showAllComments)){
											$tempStyle='style="max-width:50%;"';	
										}
										echo '<img src="'.$valuers["image_temp"].'" alt="'.$valuers["title_temp"].'" '.$tempStyle.' />';
									}
									echo $valuers["descr_temp"];
									?>
									</div>
								</div>
								<?php
							}
						}
					}
				echo "<p>";
			}else{
				if(isset($showAllComments)){
					displayContent($postValue,$onload,true);
				}else{
					displayContent($postValue,$onload);
				}
			}
			?>
			</div>
			<div class="iMPostFooter clearfix" >
				<div class="iMPostFooterShow" id="iMPostFooterShow_<?php echo $postValue["id_post"]; ?>">
					<span>▼</span>
				</div>
				<?php
				$onload.="
				if(isOverflowHidden($('#iMPostFooterTagsHolder_".$postValue["id_post"]."'),$('#iMPostFooterTags_".$postValue["id_post"]."'))){
					$('#iMPostFooterShow_".$postValue["id_post"]."').show();
					$('#iMPostFooterShow_".$postValue["id_post"]."').click(function(){
						if($('#iMPostAllTags_".$postValue["id_post"]."').is(':visible')){
							$(this).html('<span>▼</span>');
							$('#iMPostAllTags_".$postValue["id_post"]."').slideUp('fast', function(){
								$('#iMPostFooterTagsHolder_".$postValue["id_post"]."').html($('#iMPostAllTags_".$postValue["id_post"]."').html());
							});
							
						}else{
							$(this).html('<span>▲</span>');
							$('#iMPostAllTags_".$postValue["id_post"]."').html($('#iMPostFooterTagsHolder_".$postValue["id_post"]."').html());
							$('#iMPostAllTags_".$postValue["id_post"]."').slideDown('fast');
							$('#iMPostFooterTagsHolder_".$postValue["id_post"]."').html('');
						}
					});
				}
				";
				?>
				<div class="iMPostFooterTags" id="iMPostFooterTags_<?php echo $postValue["id_post"]; ?>">
					<span class="iMPostFooterTagsHolder" id="iMPostFooterTagsHolder_<?php echo $postValue["id_post"]; ?>">
					<?php
					$resPostAbout=$postClass->getPostAbout($postValue["id_post"]);
					if($resPostAbout["result"]){
						foreach($resPostAbout as $paKey=>$paValue){
							if(is_int($paKey)){
								if($paValue["type_profile"]==1){
									$paName=$paValue["username_profile"];
								}else{
									if($paValue["name_profile"]!=""){
										$paName=$paValue["name_profile"];
									}else{
										$paName=$paValue["username_profile"];
									}
								}
								echo '<div><a href="'.WEB_URL.$paValue["username_profile"].'">'.$paName.'</a><span> - '.$profileClass->nameSingular($paValue["type_profile"]).'</span></div>';
							}
						}
					}
					$resPostTopics=$postClass->getPostTopics($postValue["id_post"]);
					if($resPostTopics["result"]){
						foreach($resPostTopics as $paKey=>$paValue){
							if(is_int($paKey)){
								echo '<div><a id="xxx_'.$paKey.'" href="'.WEB_URL.$topicClass->pathSingular($paValue["type_topic"])."/".$paValue["url_topic"].'">';
								echo $paValue["name_topic"].'</a><span> - '.$topicClass->nameSingular($paValue["type_topic"]).'</span></div>';
							}
						}
					}
					?>
					</span>
				</div>
				<div class="iMPostAllTags" id="iMPostAllTags_<?php echo $postValue["id_post"]; ?>">
				
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php
if($postValue["comments_post"]>0 || USER_ID!=0){
include(ENGINE_PATH."html/list/comments.php");
}

//end foreach and if
}
}
$jsfunctions.="
function confirmDelPost(id){
	if(confirm('Are you sure you want to delete this post?')){
	location.href='".WEB_URL."act/post/post_delete_post.php?id='+id+'&back=".$backPath."#iHoldPosts';
	}
	return false;
}
function confirmDelComment(id){
	if(confirm('Are you sure you want to delete this cooment?')){
	location.href='".WEB_URL."act/post/post_comment_delete.php?id='+id+'&back=".$backPath."#iHoldPosts';
	}
	return false;
}
";
if(!isset($noHolderDiv)){
?>
</div>
</div>
<?php
}
?>