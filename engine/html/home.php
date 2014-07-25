<?php

onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();


$pageTitle="HealthKeep - Social Health Network";
$pageDescr="HealthKeep is a fun and intuitive social health network. It helps you to understand, organize and share about your health.  You are automatically connected to others who share your health issues.  You can connect to your doctors, and you are empowered improve your health and the health of those you care for.";


$active="feed";
$designV1=1;
$topicLetter="";
$topicFilter="";

if(isset($_GET["l3"])){

	if($topicClass->pathSingular('d')==$_GET["l2"]){
		$topicLetter="d";
	}else if($topicClass->pathSingular('s')==$_GET["l2"]){
		$topicLetter="s";
	}else if($topicClass->pathSingular('m')==$_GET["l2"]){
		$topicLetter="m";
	}else if($topicClass->pathSingular('p')==$_GET["l2"]){
		$topicLetter="p";
	}else{
		go404();
	}
	$dashActive=$topicClass->pathPlural($topicLetter);
	
	$resTopic=$topicClass->getByUrl($_GET["l3"],$topicLetter);
	
	if(!$resTopic["result"]){
		go404();
	}
	
	$topicFilter="&topic=".$resTopic[0]["id_topic"];
	
	$ajaxUrl=WEB_URL."act/ajax/feed/topicId.php";
	
	if(isset($_SESSION["prank"]) && isset($_SESSION["prank"][$resTopic[0]["id_topic"]]) && $_SESSION["prank"][$resTopic[0]["id_topic"]]=="rank"){
			$postToggle="rank";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"][$resTopic[0]["id_topic"]]) && $_SESSION["pfilter"][$resTopic[0]["id_topic"]]!="all"){
				$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank',$_SESSION["pfilter"][$resTopic[0]["id_topic"]]);
				$postFilter=$_SESSION["pfilter"][$resTopic[0]["id_topic"]];
			}else{
				$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank');
			}
		}else{
			$postToggle="recent";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"][$resTopic[0]["id_topic"]]) && $_SESSION["pfilter"][$resTopic[0]["id_topic"]]!="all"){
				$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'recent',$_SESSION["pfilter"][$resTopic[0]["id_topic"]]);
				$postFilter=$_SESSION["pfilter"][$resTopic[0]["id_topic"]];
			}else{
				$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"]);
			}
		}
	
	
	

}else if(isset($_GET["l2"])){
	if($_GET["l2"]=="new"){
		$dashActive="new";
		$ajaxUrl=WEB_URL."act/ajax/feed/all.php";
		if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["new"]) && $_SESSION["prank"]["new"]=="rank"){
			$postToggle="rank";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["new"]) && $_SESSION["pfilter"]["new"]!="all"){
				$resPosts = $postClass->getNewPosts(1,'rank',$_SESSION["pfilter"]["new"]);
				$postFilter=$_SESSION["pfilter"]["new"];
			}else{
				$resPosts = $postClass->getNewPosts(1,'rank');
			}
		}else{
			$postToggle="recent";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["new"]) && $_SESSION["pfilter"]["new"]!="all"){
				$resPosts = $postClass->getNewPosts(1,'recent',$_SESSION["pfilter"]["new"]);
				$postFilter=$_SESSION["pfilter"]["new"];
			}else{
				$resPosts = $postClass->getNewPosts();
			}
			
		}
		
	}else if($_GET["l2"]=="conditions"){
		$topicLetter="d";
		$ajaxUrl=WEB_URL."act/ajax/feed/topic.php";
		$dashActive="conditions";
		if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["conditions"]) && $_SESSION["prank"]["conditions"]=="rank"){
			$postToggle="rank";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["conditions"]) && $_SESSION["pfilter"]["conditions"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank',$_SESSION["pfilter"]["conditions"]);
				$postFilter=$_SESSION["pfilter"]["conditions"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank');
			}
		}else{
			$postToggle="recent";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["conditions"]) && $_SESSION["pfilter"]["conditions"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'recent',$_SESSION["pfilter"]["conditions"]);
				$postFilter=$_SESSION["pfilter"]["conditions"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter);
			}
		}
		
	}else if($_GET["l2"]=="symptoms"){
		$topicLetter="s";
		$ajaxUrl=WEB_URL."act/ajax/feed/topic.php";
		$dashActive="symptoms";
		if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["symptoms"]) && $_SESSION["prank"]["symptoms"]=="rank"){
			$postToggle="rank";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["symptoms"]) && $_SESSION["pfilter"]["symptoms"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank',$_SESSION["pfilter"]["symptoms"]);
				$postFilter=$_SESSION["pfilter"]["symptoms"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank');
			}
		}else{
			$postToggle="recent";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["symptoms"]) && $_SESSION["pfilter"]["symptoms"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'recent',$_SESSION["pfilter"]["symptoms"]);
				$postFilter=$_SESSION["pfilter"]["symptoms"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter);
			}
		}
		
	}else if($_GET["l2"]=="medications"){
		$topicLetter="m";
		$ajaxUrl=WEB_URL."act/ajax/feed/topic.php";
		$dashActive="medications";
		if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["medications"]) && $_SESSION["prank"]["medications"]=="rank"){
			$postToggle="rank";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["medications"]) && $_SESSION["pfilter"]["medications"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank',$_SESSION["pfilter"]["medications"]);
				$postFilter=$_SESSION["pfilter"]["medications"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank');
			}
		}else{
			$postToggle="recent";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["medications"]) && $_SESSION["pfilter"]["medications"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'recent',$_SESSION["pfilter"]["medications"]);
				$postFilter=$_SESSION["pfilter"]["medications"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter);
			}
		}
		
	}else if($_GET["l2"]=="procedures"){
		$topicLetter="p";
		
		$ajaxUrl=WEB_URL."act/ajax/feed/topic.php";
		$dashActive="procedures";
		if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["procedures"]) && $_SESSION["prank"]["procedures"]=="rank"){
			$postToggle="rank";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["procedures"]) && $_SESSION["pfilter"]["procedures"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank',$_SESSION["pfilter"]["procedures"]);
				$postFilter=$_SESSION["pfilter"]["procedures"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'rank');
			}
		}else{
			$postToggle="recent";
			if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["procedures"]) && $_SESSION["pfilter"]["procedures"]!="all"){
				$resPosts = $postClass->getTopicPosts($topicLetter,1,'recent',$_SESSION["pfilter"]["procedures"]);
				$postFilter=$_SESSION["pfilter"]["procedures"];
			}else{
				$resPosts = $postClass->getTopicPosts($topicLetter);
			}
		}
		
	}else{
		go404();
	}
	
}else{
	
	$dashActive="feed";
	$ajaxUrl=WEB_URL."act/ajax/feed/feed.php";
	if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["feed"]) && $_SESSION["prank"]["feed"]=="rank"){
		$postToggle="rank";
		if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["feed"]) && $_SESSION["pfilter"]["feed"]!="all"){
			$resPosts = $postClass->getFeedPosts(1,'rank',$_SESSION["pfilter"]["feed"]);
			$postFilter=$_SESSION["pfilter"]["feed"];
		}else{
			$resPosts = $postClass->getFeedPosts(1,'rank');
		}
	}else{
		$postToggle="recent";
		if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["feed"]) && $_SESSION["pfilter"]["feed"]!="all"){
			$resPosts = $postClass->getFeedPosts(1,'recent',$_SESSION["pfilter"]["feed"]);
			$postFilter=$_SESSION["pfilter"]["feed"];
		}else{
			$resPosts = $postClass->getFeedPosts();
		}
	}
}

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');


?>
<div id="main">
	<div class="iHold">
		<div id="iFeed" class="iBoard clearfix">
			<div id="iFeedContent">
				<div class="marginBottom20">
				<?php
				echo '<div style="display:none;"><pre>';
				print_r($_SESSION);
				echo '</pre></div>';
				if(!isset($needAutoGrow)){
				$needAutoGrow=1;
				$onload.="$('textarea').autogrow({'minHeight':'100'});";
				}
				?>
				<form class="iPost" enctype="multipart/form-data" id="mainPost" style="margin:0;" method="post" action="<?php echo WEB_URL; ?>act/post/savePost.php">
					<textarea placeholder="Share your health experience" style="height:65px;" class="textArea100" name="txtPost" id="txtPost"></textarea>
					<div class="iPostTopic" style="display:none;">
					<p>What element of your health is this <b>most</b> about?</p>
					<input type="text" id="topic" class="input100" name="topic" />
					</div>
					<div class="iPostBtns" style="display:none;">
						<div class="fileupload fileupload-new avatarImgBtns clearfix" data-provides="fileupload" style="display:none;margin:0;padding:0;display:inline-block;">
							<span class="btn-file" style="display:none"><span class="fileupload-new"></span>
							<input type="file" style="display:none" name="avatarFile" id="avatarFile" /></span>
							<div class="fileupload-preview thumbnail" style="width: 20px; height: 20px;display:inline-block;"></div>
						</div>
						
						<img id="imageChooseUpload" src="<?php echo WEB_URL; ?>inc/img/v1/inc/camera.png" style="border:1px solid #d8d9da;width:20px;height:20px;margin-right:15px;cursor:pointer;" />
						<input type="submit" disabled class="btn btn-red submitBtn iPostSubmitBtn" value="share" />
						<?php
						$onload.="$('#imageChooseUpload').click(function(){ $('#avatarFile').click(); });";
						$needFupload=1;
						$onload.="$('#avatarFile').bind('change', function() {
							$('.fileupload-new').hide();
							if(this.files[0]!=undefined && this.files[0].size>2097152){
								alert('The Image cannot have more than 2 MB in size');
								$('.fileupload').fileupload('clear');
						  	}else if(this.files[0]!=undefined){
						  		var val = $(this).val();
						  		var val = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
						  		if(val!='gif' && val!='jpg' && val!='jpeg' && val!='png'){
							  		alert('That is not a valid image file!');
						  			$('.fileupload').fileupload('clear');		            
						  		}else{
						  			$('.fileupload-new').show();
						  		}
						  	}
						});";
						?>
					</div>
				</form>
			<?php
				$onload.="$('#txtPost').focus(function(){
					$('#txtPost').css('height','100px');
					$('#txtPost').css('min-height','100px');
					$('.iPostTopic').show();
					$('.iPostBtns').show();
				});";
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
				$needTokenInput=1;
				$onload.="$('#topic').tokenInput('".WEB_URL."act/ajax/autoCompleteAllTopics.php', { hintText: 'Type the name', noResultsText: 'No health element with that name', searchingText: 'Searching...',tokenLimit: 10,minChars: 2,searchDelay: 200,preventDuplicates: true, theme:'long' });";
				$onload.="
				$('#mainPost').submit(function(){
					if($('#txtPost').val().length<5){
						alert('You need to type a message to be able to post!');
						$('#txtPost').focus();
						return false;
					}else if($('#topic').val()==''){
						alert('You need to choose at least one element of your health that this post is MOST about!');
						$('#topic').focus();
						return false;
					}else{
						return true;
					}
				});
				";
				?>
				</div>
				<?php
				if(isset($_GET["l3"])){
					$backPath="feed/".$_GET["l2"]."/".$_GET["l3"];
				}else if(isset($_GET["l2"])){
					$backPath="feed/".$_GET["l2"];
				}else{
					$backPath="feed/";
				}
				?>
				<div id="postToggle" class="center paddingBottom20">
					<span style="padding-right:30px;">
						<?php if(isset($postFilter) && $postFilter!='all'){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=all&b=".urlencode($backPath); ?>">All</a><?php }else{ echo "All"; } ?> | 
						<?php if(!isset($postFilter) || (isset($postFilter) && $postFilter!='exp')){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=exp&b=".urlencode($backPath); ?>">Experiences</a><?php }else{ echo "Experiences"; } ?> | 
						<?php if(!isset($postFilter) || (isset($postFilter) && $postFilter!='news')){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=news&b=".urlencode($backPath); ?>">News</a><?php }else{ echo "News"; } ?>
					</span>
					<?php
					if(!isset($postToggle) || (isset($postToggle) && $postToggle=="rank")){
						?>
						<a href="<?php echo WEB_URL."setOrder.php?f=".$dashActive.$topicFilter."&o=recent&b=".urlencode($backPath); ?>">most recent</a> | <span>top voted</span>
						<?php
					}else{
						echo '<span>most recent</span> | <a href="'.WEB_URL.'setOrder.php?f='.$dashActive.$topicFilter.'&o=rank&b='.urlencode($backPath).'">top voted</a>';
					}
					?>
				</div>
				<?php
				if(isset($_SESSION["welcome"]) && isset($_SESSION["welcome"]["first"]) && $_SESSION["welcome"]["first"]==true){
				?>
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					Your experience is published and out there helping others!  You should now <a href="<?php echo WEB_URL.USER_NAME; ?>" style="text-decoration:underline">customize your profile</a> to receive experiences and news relevant to your health.
				</div>
				<?php
					$_SESSION["welcome"]["first"]=false;
					$jsfunctions.="mixpanel.track('Start Finished V2');";
					if(!isset($needAlert)){
						$needAlert=1;
						$onload.="$('.alert').alert();";
					}
					if(!$resPosts["result"]){
						$resPosts = $postClass->getNewPosts();
					}
				}else if(!$resPosts["result"] && $dashActive=="feed"){
				?>
				<div class="alert alert-info center">
					<h3>There are no posts to present on your feed</h3>
					<p>
						The list you see below is a list of the latest posts on HealthKeep.<br />
						To get a customised feed you need to follow other users or topics.
					</p>
				</div>
				<?php
				$resPosts = $postClass->getNewPosts();
				}else if(!$resPosts["result"] && $dashActive=="doctors"){
					?>
				<div class="alert alert-info center">
					<h3>There are no posts to present on your conditions feed</h3>
					<p>
						To get a customised conditions feed you need to follow <a href="<?php echo WEB_URL.$topicClass->pathPlural('d'); ?>" class="underline">conditions</a>.
					</p>
				</div>
				<?php
				}else if(!$resPosts["result"]){
					?>
				<div class="alert alert-info center">
					<h3>There are no posts to present on your <?php echo strtolower($topicClass->namePlural($topicLetter)); ?> feed</h3>
					<p>
						To get a customised <?php echo strtolower($topicClass->namePlural($topicLetter)); ?> feed you need to follow <a href="<?php echo WEB_URL.$topicClass->pathPlural($topicLetter); ?>" class="underline"><?php echo strtolower($topicClass->namePlural($topicLetter)); ?></a>.
					</p>
				</div>
				<?php
				}
				if($resPosts["result"]){
				?>
					<div id="postHolder" class="clearfix">
					<?php require_once(ENGINE_PATH."html/list/posts.php"); ?>
					</div>
					<?php
					if(!isset($postFilter) || ($postFilter!="exp" && $postFilter!="news")){
						$postFilter="all";
					}
					if($topicLetter!=""){
						if(isset($resTopic)){
							$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'".$resTopic[0]["id_topic"]."','$postToggle','$postFilter');";
						}else{
							$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'$topicLetter','$postToggle','$postFilter');";
						}
					}else{
						$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'$postToggle','$postFilter');";
					}
					require_once(ENGINE_PATH."html/inc/endless.php");
				}
				?>
			</div>
			<?php
			require_once(ENGINE_PATH."html/inc/feedSidebar.php");
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');