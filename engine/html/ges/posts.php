<?php

onlyLogged();

if(USER_TYPE!=9){
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

$backPath="ges/posts";

$ajaxUrl=WEB_URL."act/ajax/newfeed/allBackOffice.php";

$postToggle="recent";
if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["new"]) && $_SESSION["pfilter"]["new"]!="all"){
	$resPosts = $postClass->getNewPosts(1,'recent',$_SESSION["pfilter"]["new"]);
	$postFilter=$_SESSION["pfilter"]["new"];
}else{
	$resPosts = $postClass->getNewPosts();
}

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
				<p class="center" style="color:#ccc;">
				<?php 
				if(isset($postFilter) && $postFilter!='all'){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=all&b=".urlencode($backPath); ?>">All</a><?php }else{ echo "<span class=\"active\">All</span>"; } ?><span class="feedBarDivider"> | </span>
					<?php if(!isset($postFilter) || (isset($postFilter) && $postFilter!='exp')){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=exp&b=".urlencode($backPath); ?>">Experiences</a><?php }else{ echo "<span class=\"active\">Experiences</span>"; } ?><span class="feedBarDivider"> | </span>
					<?php if(!isset($postFilter) || (isset($postFilter) && $postFilter!='news')){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=news&b=".urlencode($backPath); ?>">News</a><?php }else{ echo "<span class=\"active\">News</span>"; } ?>
				</p>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<?php
				$jsfunctions.="
				function removeTopic(id,post){
					$('#postTopic_'+id).hide();
					$.ajax({
					  type: 'post',
					  url: '".WEB_URL."act/ajax/newfeed/removeTopic.php',
					  data: { id: id,post:post }
					}).done(function( msg ) {
					  if(msg=='error'){
					  	alert('An error has occurred! Please try again later.');
					  }
					});
					return false;
				}
				function addTopics(id){
					$('#addTopics_'+id).show();
					$('#addTopicsInput_'+id).tokenInput('".WEB_URL."act/ajax/autoCompleteAllTopicsReal.php', { hintText: 'Type the name', noResultsText: 'No health topic with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long', onAdd:function(item){ addTopic(id,item.id); } });
				}
				function addTopic(post,id){
					$.ajax({
					  type: 'post',
					  url: '".WEB_URL."act/ajax/newfeed/addTopic.php',
					  data: { id: id,post:post }
					}).done(function( msg ) {
					  if(msg=='error'){
					  	alert('An error has occurred! Please try again later.');
					  }
					});
				}
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
				";
				$needTokenInput=1;
				if($resPosts["result"]){
				?>
					<div id="postHolder" class="clearfix">
						<div id="iHoldDiscussion" class="clearfix">
							<div id="iHoldPosts">
								<?php require_once(ENGINE_PATH."html/ges/postSingle.php"); ?>
							</div>
						</div>
					</div>
				<?php
					if(!isset($postFilter) || ($postFilter!="exp" && $postFilter!="news")){
						$postFilter="all";
					}
					if($topicLetter!=""){
						if(isset($resTopic)){
							$onload.="endlessScroll('$ajaxUrl',$('#iHoldPosts'),'".$resTopic[0]["id_topic"]."','$postToggle','$postFilter');";
						}else{
							$onload.="endlessScroll('$ajaxUrl',$('#iHoldPosts'),'$topicLetter','$postToggle','$postFilter');";
						}
					}else{
						$onload.="endlessScroll('$ajaxUrl',$('#iHoldPosts'),'$postToggle','$postFilter');";
					}
					require_once(ENGINE_PATH."render/feed/endless.php");
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');