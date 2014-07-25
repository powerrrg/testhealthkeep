<?php
if(!isset($noHolderDiv)){
	?>
	<div id="iHoldDiscussion" class="clearfix">
	<?php
	if(isset($iMHealthTL)){
	?>
	<div id="iHoldPosts" class="iHoldPostsUser" style="padding-left:80px;background:#f4f4f4 url('<?php echo WEB_URL; ?>inc/img/v2/base/bluesquare.png') repeat-y 60px 0;position:relative;">
	<?php
		if(isset($resPosts) && isset($resPosts[0]) && isset($resPosts[0]["date_post"])){
			echo '<div style="position:absolute;top:-20px;left:1px;width:60px;height:20px;background:#DDE9F6;line-height:20px;text-align:center;color:#5F91CC;">';
			echo date('Y',strtotime($resPosts[0]["date_post"]));
			echo "</div>";
		}
	
	}else{
	?>
	<div id="iHoldPosts">
	<?php
	}
}

if(USER_ID>0){
	if(!isset($needAutoGrow)){
		$needAutoGrow=1;
		$onload.="$('textarea').autogrow();";
	}
	if(USER_TYPE==9){
		$jsfunctions.="
		function togglePostPin(id,status){
			$.ajax({
			  type: 'POST',
			  url: '".WEB_URL."act/ajax/newfeed/togglePostPin.php',
			  data: { id: id }
			}).done(function( msg ) {
			  if(msg!='ok'){
			  	alert('Ops! We could not toggle the pin of that post. Please try again later or contact us.');	
			  }else{
			  	var ele=$('#iMPostPin_'+id).children('a');
			  	if(status==0){
			  		ele.attr('onClick','togglePostPin('+id+',1);');
			  		$('#iMPost_'+id).addClass('iMPostPinned');
			  		$('#iMPost_'+id).prepend('<div id=\"iMPostPinnedHeader_'+id+'\" class=\"iMPostPinnedHeader\">HealthKeep Trusted Content</div>');
			  	}else{
			  		ele.attr('onClick','togglePostPin('+id+',0);');
			  		$('#iMPost_'+id).removeClass('iMPostPinned');
			  		$('#iMPostPinnedHeader_'+id).remove();	  		
			  	}
			  }
			});
			return false;
		}
		function adminDeletePost(id){
			if(confirm('Are you sure you want to delete this post?')){
				$('#iMPost_'+id).slideUp('fast');
				$.ajax({
				  type: 'post',
				  url: '".WEB_URL."act/ajax/newfeed/deletePostBackOffice.php',
				  data: { id: id }
				}).done(function( msg ) {
				  if(msg=='error'){
				  	alert('An error has occurred! Please try again later.');
				  }
				  return false;
				});
			}
		}
		
		";
	}
}

$starRaty=1;
$onload.="loadRaty();";
if(USER_ID>0){
	$jsfunctions.="function loadRaty(){
		$('.starRaty').raty({ path: '".WEB_URL."inc/js/raty/img', 
			score: function() {
			    return $(this).attr('data-score');
			}, 
			click: function(score, evt) {
				ratePost($(this).attr('id'),score);
			},
			hints: ['poor', 'fair', 'good', 'great', 'awesome']
		});
	}
	function ratePost(idString,rate){
		var id=parseInt(idString.replace('starRaty_',''));
		if(id>0){
			$.ajax({
				type: 'POST',
				url: '".WEB_URL."act/ajax/newfeed/ratePost.php',
				data: { id: id, rate: rate }
				}).done(function( msg ) {
					if( Math.floor(msg) == msg && $.isNumeric(msg)){
						$('#starRaty_count_'+id).html('('+msg+')');
					}else{
						alert('Ops! We could not rate that post. Please try again later or contact us.');		  
					}
			});
		}
	}
	";
}else{
	$jsfunctions.="function loadRaty(){
		$('.starRaty').raty({ path: '".WEB_URL."inc/js/raty/img', readOnly: true, 
			score: function() {
			    return $(this).attr('data-score');
			} 
		});
	}";
}

$jsfunctions.="
function confirmDelPost(id){
	if(confirm('Are you sure you want to delete this post?')){
		$('#iMPost_'+id).slideUp('fast');
		$.ajax({
		  type: 'POST',
		  url: '".WEB_URL."act/ajax/newfeed/delPost.php',
		  data: { id: id }
		}).done(function( msg ) {
		  if(msg!='ok'){
			  alert('Ops! We could not remove that post. Please try again later or contact us.');	
			$('#iMPost_'+id).show();	  
		  }
		});
	}
	return false;
}
function reportPost(id){
	if(confirm('Are you sure you want to report this post?')){
		$('#iMPost_'+id).slideUp('fast');
		$.ajax({
		  type: 'POST',
		  url: '".WEB_URL."act/ajax/newfeed/reportPost.php',
		  data: { id: id }
		}).done(function( msg ) {
		  if(msg!='ok'){
			  alert('Ops! We could not remove that post. Please try again later or contact us.');	
			$('#iMPost_'+id).show();	  
		  }
		});
	}
	return false;
}
function confirmDelComment(id){
	if(confirm('Are you sure you want to delete this comment?')){
		$('#iMPostComment_'+id).slideUp('fast');
		$.ajax({
		  type: 'POST',
		  url: '".WEB_URL."act/ajax/newfeed/delComment.php',
		  data: { id: id }
		}).done(function( msg ) {
		  if(msg!='ok'){
			  alert('Ops! We could not remove that comment. Please try again later or contact us.');	
			$('#iMPostComment_'+id).show();	  
		  }
		});
	}
	return false;
}
function reportComment(id){
	if(confirm('Are you sure you want to report this comment?')){
		$('#iMPostComment_'+id).slideUp('fast');
		$.ajax({
		  type: 'POST',
		  url: '".WEB_URL."act/ajax/newfeed/reportComment.php',
		  data: { id: id }
		}).done(function( msg ) {
		  if(msg!='ok'){
			  alert('Ops! We could not remove that comment. Please try again later or contact us.');	
			$('#iMPostComment_'+id).show();	  
		  }
		});
	}
	return false;
}
";
$jsfunctions.="
function subComment(id,text){
	if(text.length>2){
		$('#iMPostCommentNewLoader_'+id).show();
		$.ajax({
		  type: 'post',
		  url: '".WEB_URL."act/ajax/newfeed/postComment.php',
		  data: { id: id,text:text }
		}).done(function( msg ) {
			$('#iMPostCommentNewLoader_'+id).hide();
		  if(msg=='error'){
		  	alert('An error has occurred! Please try again later.');
		  }else{
		  	$('#iMPostMoreComments_'+id).hide();
		  	$('#iMPostCommentsHolder_'+id).slideDown('fast');
		  	$('#iMPostCommentNew_'+id).append(msg);
		  }
		});
	}else{
		alert('You need to type more than 2 characters');
	}

}
";
$onload.="
$('#iHoldPosts').delegate('.iMPostCommentTA', 'keydown', function(e) {
	if(e.keyCode == '13'){
		var text=$(this).val();
		$(this).val('');
		$(this).blur();
		var idString=$(this).attr('id');
		var id=parseInt(idString.replace('iMPostCommentTA_',''));
		if(id>0){
			subComment(id,text);
		}
	}
});
$('#iHoldPosts').delegate('.iMPostMoreComments p','click',function(){
	$(this).parent().hide();
	var idString=$(this).parent().attr('id');
	var id=parseInt(idString.replace('iMPostMoreComments_',''));
	$('#iMPostCommentsHolder_'+id).slideDown('fast');
});
$('#iHoldPosts').delegate('.iMPostInfoComments','click',function(){
	var idString=$(this).attr('id');
	var id=parseInt(idString.replace('iMPostInfoComments_',''));
	$('#iMPostMoreComments_'+id).hide();
	$('#iMPostCommentsHolder_'+id).slideDown('fast');
});
";
$jsfunctions.="
function iCommentVote(id){
	var ele=$('#iMPostCommentLove_'+id);
	ele.fadeOut(100).fadeIn(500);
	ele.children('img').attr('src','".WEB_URL."inc/img/v2/base/heart-red.png');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_comment_thumb.php',
	  data: { id: id, vote: 'up' }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	alert('Ops! Something went wrong and we could not save that. Please try again later or contact us.');
	  	ele.children('img').attr('src','".WEB_URL."inc/img/v2/base/heart.png');
	  }else{
	 	ele.attr('onClick','removeCommentVote('+id+');');
	 	updateCommentCount(id);
	  }
	});
}";
$jsfunctions.="
function removeCommentVote(id){
	var ele=$('#iMPostCommentLove_'+id);
	ele.fadeOut(100).fadeIn(500);
	ele.children('img').attr('src','".WEB_URL."inc/img/v2/base/heart.png');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_comment_thumb_remove.php',
	  data: { id: id }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	alert('Ops! Something went wrong and we could not save that. Please try again later or contact us.');
	  	ele.children('img').attr('src','".WEB_URL."inc/img/v2/base/heart-red.png');
	  }else{
	  	ele.attr('onClick','iCommentVote('+id+');');
	  	updateCommentCount(id);
	  }
	});
}";
$jsfunctions.="
function updateCommentCount(id){
	$.ajax({
	  type: 'POST',
	  url: '".WEB_URL."act/ajax/post/post_comment_count.php',
	  data: { id: id }
	}).done(function( msg ) {
		var quantos=parseInt(msg);

		if(quantos==0){
			$('#iMPostCommentLoveTxt_'+id).hide();
		}else{

			$('#iMPostCommentLoveTxt_'+id).show();
			$('#iMPostCommentLoveTxt_'+id).html('('+quantos+')');
	  	}
	});
}
";
$jsfunctions.="
function updateTotalVotes(id,addNum){
if(addNum=='-1'){
	$('#iMPostInfoLove_'+id).removeClass('iMPostInfoLoveLiked');
	$('#iMPostInfoLove_'+id).addClass('iMPostInfoLoveLike');
	$('#iMPostInfoLove_'+id).html('<img src=\"".WEB_URL."inc/img/v2/base/heart-white.png\" /> Like');
}else{
	$('#iMPostInfoLove_'+id).removeClass('iMPostInfoLoveLike');
	$('#iMPostInfoLove_'+id).addClass('iMPostInfoLoveLiked');
	$('#iMPostInfoLove_'+id).html('<img src=\"".WEB_URL."inc/img/v2/base/heart-red.png\" /> Liked');
}
$('#iMPostInfoLove_'+id).animate({ opacity: 1 });
$('#iMPostLove_'+id).animate({ opacity: 1 });

$('#iMPostLoveCountTxt_'+id).show();
var ele=$('#iMPostLoveCount_'+id);
var ele2=$('#iMPostInfoLoveCount_'+id);
ele.show();
ele2.show();
var total=parseInt($('#iMPostLoveCount_'+id).html());
ele.html(total+parseInt(addNum));
ele2.html(total+parseInt(addNum));
}
";
$jsfunctions.="
function showAllTopics(id){
	$('.iMPostInfoTopicHidden_'+id).slideDown('fast');
	$('#iMPostInfoTopicMore_'+id).hide();
}
";
$jsfunctions.="
function iVote(id){
	var ele=$('#iMPostInfoLove_'+id);
	var ele2=$('#iMPostLove_'+id);
	ele.attr('onClick','');
	ele2.attr('onClick','');
	ele.animate({ opacity: 0 });
	ele2.animate({ opacity: 0 });
	$('#iMPostLoveImg_'+id).html('<img src=\"".WEB_URL."inc/img/v2/base/heart-red.png\" id=\"iMPostInfoLoveImg_'+id+'\" alt=\"Red Heart\" />');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_thumb.php',
	  data: { id: id, vote: 'up' }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	alert('Ops! Something went wrong and we could not save that. Please try again later or contact us.');
	  	ele.attr('onClick','iVote('+id+');');
	  	ele2.attr('onClick','iVote('+id+');');
	  	ele.animate({ opacity: 1 });
	  	ele2.animate({ opacity: 1 });
	  }else{
	  	updateTotalVotes(id,'+1');
	 	ele.attr('onClick','removeVote('+id+');');
	 	ele2.attr('onClick','removeVote('+id+');');
	  }
	});
}";
$jsfunctions.="
function removeVote(id){
	var ele=$('#iMPostInfoLove_'+id);
	var ele2=$('#iMPostLove_'+id);
	ele.attr('onClick','');
	ele2.attr('onClick','');
	ele.animate({ opacity: 0 });
	ele2.animate({ opacity: 0 });
	$('#iMPostLoveImg_'+id).html('<img src=\"".WEB_URL."inc/img/v2/base/heart.png\" id=\"iMPostInfoLoveImg_'+id+'\" alt=\"Gray Heart\" />');
	$.ajax({
	  type: 'GET',
	  url: '".WEB_URL."act/ajax/post/post_thumb_remove.php',
	  data: { id: id }
	}).done(function( msg ) {
	  if(msg!='ok'){
	  	alert('Ops! Something went wrong and we could not save that. Please try again later or contact us.');
	  	ele.attr('onClick','removeVote('+id+');');
	 	ele2.attr('onClick','removeVote('+id+');');
	 	ele.animate({ opacity: 1 });
	  	ele2.animate({ opacity: 1 });
	  }else{
	  	updateTotalVotes(id,'-1');
	  	ele.attr('onClick','iVote('+id+');');
	  	ele2.attr('onClick','iVote('+id+');');
	  }
	});
}";


include(ENGINE_PATH."render/feed/posts.php");

if(!isset($noHolderDiv)){
?>
</div>
</div>
<?php
}
?>