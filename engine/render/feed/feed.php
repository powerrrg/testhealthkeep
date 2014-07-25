<?php

if(!isset($_GET["l1"])){
	go404();
}

if($_GET["l1"]=="feed"){
	if(!isset($_GET["l2"])){
		onlyLogged();
	}else if($topicClass->pathPlural('d')==$_GET["l2"]){
		$topicLetter="d";
	}else if($topicClass->pathPlural('s')==$_GET["l2"]){
		$topicLetter="s";
	}else if($topicClass->pathPlural('m')==$_GET["l2"]){
		$topicLetter="m";
	}else if($topicClass->pathPlural('p')==$_GET["l2"]){
		$topicLetter="p";
	}else if($topicClass->pathPlural('g')==$_GET["l2"]){
		$topicLetter="g";
	}else{
		onlyLogged();
	}
}

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();


$pageTitle="Feed - HealthKeep";
$pageDescr="HealthKeep is a network to share and learn from health experiences anonymously.";

$active="feed";
$designV1=1;
$topicLetter="";
$topicFilter="";

if(isset($_GET["l3"])){
	
	//$_GET["l3"] should be ok to remove all links changed to category/topic but is here just in case I missed something
	if($topicClass->pathSingular('d')==$_GET["l2"]){
		$topicLetter="d";
	}else if($topicClass->pathSingular('s')==$_GET["l2"]){
		$topicLetter="s";
	}else if($topicClass->pathSingular('m')==$_GET["l2"]){
		$topicLetter="m";
	}else if($topicClass->pathSingular('p')==$_GET["l2"]){
		$topicLetter="p";
	}else if($topicClass->pathSingular('g')==$_GET["l2"]){
		$topicLetter="g";
	}else{
		go404();
	}
	$dashActive=$topicClass->pathPlural($topicLetter);
	
	$resTopic=$topicClass->getByUrl($_GET["l3"],$topicLetter);
	
	if(!$resTopic["result"]){
		go404();
	}
	
	$topicFilter="&topic=".$resTopic[0]["id_topic"];
	
	$ajaxUrl=WEB_URL."act/ajax/newfeed/topicId.php";
	
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
	
	if($_GET["l1"]!='feed'){
	
		if($topicClass->pathSingular('d')==$_GET["l1"]){
			$topicLetter="d";
		}else if($topicClass->pathSingular('s')==$_GET["l1"]){
			$topicLetter="s";
		}else if($topicClass->pathSingular('m')==$_GET["l1"]){
			$topicLetter="m";
		}else if($topicClass->pathSingular('p')==$_GET["l1"]){
			$topicLetter="p";
		}else if($topicClass->pathSingular('g')==$_GET["l1"]){
			$topicLetter="g";
		}else{
			go404();
		}
		$dashActive=$topicClass->pathPlural($topicLetter);
		
		$resTopic=$topicClass->getByUrl($_GET["l2"],$topicLetter);
		
		if(!$resTopic["result"]){
			go404();
		}
		
		//if(isset($_GET["l2"]) && $_GET["l2"]=="fibromyalgia"){
		//$pageTitle="What is Fibro? - HealthKeep";	
		//}else{
		$pageTitle=$resTopic[0]["name_topic"]." - HealthKeep";
		//}
		
		$pageDescr="Find help and share your experience with ".$resTopic[0]["name_topic"];
		
		$topicFilter="&topic=".$resTopic[0]["id_topic"];
		
		$ajaxUrl=WEB_URL."act/ajax/newfeed/topicId.php";
		
		if(isset($_SESSION["prank"]) && isset($_SESSION["prank"][$resTopic[0]["id_topic"]]) && $_SESSION["prank"][$resTopic[0]["id_topic"]]=="rank"){
				$postToggle="rank";
				if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"][$resTopic[0]["id_topic"]]) && $_SESSION["pfilter"][$resTopic[0]["id_topic"]]!="all"){
					$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank',$_SESSION["pfilter"][$resTopic[0]["id_topic"]]);
					$postFilter=$_SESSION["pfilter"][$resTopic[0]["id_topic"]];
				}else{
				
					//START OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 1/4
					//$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank');
					//THE PREVIOUS LINE WAS REPLACED BY THE FOLLOWING
					if(!isset($_SESSION["pfilter"]) || !isset($_SESSION["pfilter"][$resTopic[0]["id_topic"]])){
						$_SESSION["pfilter"][$resTopic[0]["id_topic"]]="exp";
						$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank',$_SESSION["pfilter"][$resTopic[0]["id_topic"]]);
						
						if(!$resPosts["result"]){
							$_SESSION["pfilter"][$resTopic[0]["id_topic"]]="all";
							$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank');
						}
						
						$postFilter=$_SESSION["pfilter"][$resTopic[0]["id_topic"]];
						
					}else{
						$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'rank');
					}
					//END OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 1/4
				}
			}else{
				$postToggle="recent";
				if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"][$resTopic[0]["id_topic"]]) && $_SESSION["pfilter"][$resTopic[0]["id_topic"]]!="all"){
					$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'recent',$_SESSION["pfilter"][$resTopic[0]["id_topic"]]);
					$postFilter=$_SESSION["pfilter"][$resTopic[0]["id_topic"]];
				}else{
				
					//START OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 2/4
					//$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"]);
					//THE PREVIOUS LINE WAS REPLACED BY THE FOLLOWING
					if(!isset($_SESSION["pfilter"]) || !isset($_SESSION["pfilter"][$resTopic[0]["id_topic"]])){
						$_SESSION["pfilter"][$resTopic[0]["id_topic"]]="exp";
						$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"],1,'recent',$_SESSION["pfilter"][$resTopic[0]["id_topic"]]);
						
						if(!$resPosts["result"]){
							$_SESSION["pfilter"][$resTopic[0]["id_topic"]]="all";
							$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"]);	
						}
						
						$postFilter=$_SESSION["pfilter"][$resTopic[0]["id_topic"]];
						
					}else{
						$resPosts = $postClass->getTopicIdPosts($resTopic[0]["id_topic"]);
					}
					//END OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 2/4

				}
			}
		
	}else if($_GET["l2"]=="new"){
		$dashActive="new";
		$ajaxUrl=WEB_URL."act/ajax/newfeed/all.php";
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
		$ajaxUrl=WEB_URL."act/ajax/newfeed/topic.php";
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
		$ajaxUrl=WEB_URL."act/ajax/newfeed/topic.php";
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
		$ajaxUrl=WEB_URL."act/ajax/newfeed/topic.php";
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
		
		$ajaxUrl=WEB_URL."act/ajax/newfeed/topic.php";
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
	$ajaxUrl=WEB_URL."act/ajax/newfeed/feed.php";
	if(isset($_SESSION["prank"]) && isset($_SESSION["prank"]["feed"]) && $_SESSION["prank"]["feed"]=="rank"){
		$postToggle="rank";
		if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["feed"]) && $_SESSION["pfilter"]["feed"]!="all"){
			$resPosts = $postClass->getFeedPosts(1,'rank',$_SESSION["pfilter"]["feed"]);
			$postFilter=$_SESSION["pfilter"]["feed"];
		}else{
		
			//START OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 3/4
			//$resPosts = $postClass->getFeedPosts(1,'rank');
			//THE PREVIOUS LINE WAS REPLACED BY THE FOLLOWING
			
			if(!isset($_SESSION["pfilter"]) || !isset($_SESSION["pfilter"]["feed"])){
				$_SESSION["pfilter"]["feed"]="exp";
				$resPosts = $postClass->getFeedPosts(1,'rank',$_SESSION["pfilter"]["feed"]);
				
				if(!$resPosts["result"]){
					$_SESSION["pfilter"]["feed"]="all";
					$resPosts = $postClass->getFeedPosts(1,'rank');
				}
				
				$postFilter=$_SESSION["pfilter"]["feed"];
				
			}else{
				$resPosts = $postClass->getFeedPosts(1,'rank');
			}
			
			//END OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 3/4
		}
	}else{
		$postToggle="recent";
		if(isset($_SESSION["pfilter"]) && isset($_SESSION["pfilter"]["feed"]) && $_SESSION["pfilter"]["feed"]!="all"){
			$resPosts = $postClass->getFeedPosts(1,'recent',$_SESSION["pfilter"]["feed"]);
			$postFilter=$_SESSION["pfilter"]["feed"];
		}else{
		
			//START OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 4/4
			//$resPosts = $postClass->getFeedPosts();
			//THE PREVIOUS LINE WAS REPLACED BY THE FOLLOWING
			
			if(!isset($_SESSION["pfilter"]) || !isset($_SESSION["pfilter"]["feed"])){
				$_SESSION["pfilter"]["feed"]="exp";
				$resPosts = $postClass->getFeedPosts(1,'recent',$_SESSION["pfilter"]["feed"]);
				
				if(!$resPosts["result"]){
					$_SESSION["pfilter"]["feed"]="all";
					$resPosts = $postClass->getFeedPosts();
				}
				
				$postFilter=$_SESSION["pfilter"]["feed"];
				
			}else{
				$resPosts = $postClass->getFeedPosts();
			}
			
			//END OF HACK TO MAKE THE DEFAULT EXPERIENCES PART 4/4
			
		}
	}
}

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div id="feedCTA">
			
			<?php
			if(USER_ID==0){
			$token=sha1(microtime(true).mt_rand(10000,90000));
			$_SESSION["token"]=$token;
			?>
			<div id="feedSignUp">
				<?php
				/*<div id="feedSignUp" <?php if(isset($_GET["l2"]) && $_GET["l2"]=="fibromyalgia"){ echo 'class="specialFeedSignUp"'; } ?>>
				
				if(isset($_GET["l2"]) && $_GET["l2"]=="fibromyalgia"){
				?>
			<h2>What is Fibro?</h2>
			Fibro is a shorthand version for the medical term fibromyalgia.
<br /><br />
Fibromyalgia is a chronic and often disabling condition which affects about 3-4% of the population.  Three women have fibromyalgia for every man.
<br /><br />
The cause of fibromyalgia is unknown, and the diagnoses can only be made after neurological, rheumatological and other medical illnesses have been fully excluded.
<br /><br />
The core feature of fibromyalgia is pain which can either be localized or generalized, mild or severe.  The pain usually affects muscles and joints but can also involve the spine and cause headaches.
<br /><br />
Patients may experience many other symptoms along with the pain.  This may include visual problems, dizziness, fatigue, and numbness or tingling.
<br /><br />
Patients often go for long periods before getting the diagnosis, and in recent years treatments are becoming increasingly effective.  Many medications may be tried including pain medications, antidepressants, and anti-seizure medications. However, despite the options available, failure of treatment often occurs.
<br /><br />
HealthKeep offers a community of people with fibromyalgia and those who worry they might have it.  Share experiences about fibromyalgia and get real time news about it from hundreds of sources.  Enter your experience below to join for free:<br /><br />
				<?php
				}else{*/
				?>
				<h2>Share and learn with others like you</h2>
				<?php
				$onload.="$('#hpSingleInput').focus();";
				//}
				?>
				<form id="homeRegister" method="post" class="clearfix" action="<?php echo WEB_URL; ?>act/registerNewDesign.php">
					<input type="email" id="hpSingleInput" name="email" placeholder="Enter your email adress" />
					<input type="hidden" name="username" value="user<?php echo time(); ?>" />
					<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
					<input type="hidden" name="gender" value="m" />
					<input type="text" name="hpot" class="hpot" value="" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<div class="clearfix">
						
						<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-red" value="Sign Up" />
					</div>
				</form>
			</div>
			<?php
			

			$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";

			$jsfunctions.="
			function testEmail(){
				if(isValidEmailAddress($('#hpSingleInput').val())){
					return true;
				}else{
					alert('Invalid email!');
					return false;
				}
			}";
			$onload.="
			$('#homeRegister').submit(function(){
				return testEmail();
			});
			";
			$_SESSION["mx_signup"]=1;
			$jsfunctions.="mixpanel.track('Topic Page V2 New Design');";
			
			}else{
			?>
			<div id="feedCTATeaser" class="clearfix">
				<div id="feedCTATeaserText">
					<?php
					if(isset($resTopic)){
					?>
						<h5>Share about <?php echo $resTopic[0]["name_topic"]; ?>!</h5>
					<?php
					}else{
					?>
						<h5>Share your health experiences!</h5>
					<?php
					}
					?>
					<p>Tell a story, ask a question, give a tip or share some news.</p>
				</div>
				<div id="feedCTATeaserImage">
					<img src="<?php echo WEB_URL."inc/img/v2/base/arrow.png"; ?>" alt="" />
				</div>
			</div>
			<?php
			$dontPushFooter=1;
			require_once(ENGINE_PATH."render/feed/cta.php");
			}
			?>
			
		</div>
		<?php
		if(isset($_GET["l3"])){
			$backPath="feed/".$_GET["l2"]."/".$_GET["l3"];
		}else if(isset($_GET["l2"])){
			if($_GET["l1"]=="feed"){
			$backPath="feed/".$_GET["l2"];
			}else{
			$backPath=$_GET["l1"]."/".$_GET["l2"];	
			}
		}else{
			$backPath="feed/";
		}
		?>
		<div id="feedBar" class="clearfix">
			<div id="feedBarOrder">
				<?php 
					if(isset($postFilter) && $postFilter!='all'){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=all&b=".urlencode($backPath); ?>">All</a><?php }else{ echo "<span class=\"active\">All</span>"; } ?><span class="feedBarDivider">|</span>
					<?php if(!isset($postFilter) || (isset($postFilter) && $postFilter!='exp')){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=exp&b=".urlencode($backPath); ?>">Experiences</a><?php }else{ echo "<span class=\"active\">Experiences</span>"; } ?><span class="feedBarDivider">|</span>
					<?php if(!isset($postFilter) || (isset($postFilter) && $postFilter!='news')){ ?><a href="<?php echo WEB_URL."setFilter.php?f=".$dashActive.$topicFilter."&o=news&b=".urlencode($backPath); ?>">News</a><?php }else{ echo "<span class=\"active\">News</span>"; } ?><span class="feedBarDivider">|</span>
				<div id="feedBarOrderBy" class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Order by &#9662;</a>
					<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
						<?php
						if(!isset($postToggle) || (isset($postToggle) && $postToggle=="rank")){
						?>
						<li><a href="<?php echo WEB_URL."setOrder.php?f=".$dashActive.$topicFilter."&o=recent&b=".urlencode($backPath); ?>">most recent</a></li>
						<li>
						<?php 
						echo '<a href="'.WEB_URL.'setOrder.php?f='.$dashActive.$topicFilter.'&o=rank&b='.urlencode($backPath).'" class="active">top voted</a>';
						?>
						</li>
						<?php
						}else{
						?>
						<li><a href="<?php echo WEB_URL."setOrder.php?f=".$dashActive.$topicFilter."&o=recent&b=".urlencode($backPath); ?>" class="active">most recent</a></li>
						<li>
						<?php 
						echo '<a href="'.WEB_URL.'setOrder.php?f='.$dashActive.$topicFilter.'&o=rank&b='.urlencode($backPath).'">top voted</a>';
						?>
						</li>
						<?php
						}
						?>
					<ul>
				</div>
			</div>
			<?php
			if(USER_ID!=0){
			?>
			<div id="feedBarTopics">
				<div class="feedBarTopic">
					<a href="<?php echo WEB_URL; ?>feed"<?php if($dashActive=="feed"){ echo ' class="active"'; } ?>>All</a>
				</div>
				<span class="feedBarDivider">|</span>
				<div class="feedBarTopic dropdown">
				<?php 
				if(USER_ID!=0){
					$resSub = $topicClass->getAllUserFollowedFromTopic('d',USER_ID);
					if($resSub["result"] && $resSub["result"]>1){
				?>
					<a href="#" class="dropdown-toggle<?php if($dashActive=="conditions"){ echo ' active'; } ?>" data-toggle="dropdown" >Conditions &#9662;</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<?php
						foreach($resSub as $key=>$value){
				?>
							<li><a href="<?php echo WEB_URL.$topicClass->pathSingular('d').'/'.$value["url_topic"]; ?>" <?php if(isset($resTopic) && $resTopic[0]["id_topic"]==$value["id_topic"]){ echo ' class="active"'; } ?>><?php echo $value["name_topic"]; ?></a></li>
				<?php
						}
					echo "</ul>";
					}else{
					?>
					<a href="<?php echo WEB_URL; ?>feed/conditions"<?php if($dashActive=="conditions"){ echo ' class="active"'; } ?>>Conditions</a>
					<?php
					}
				}else{
				?>
				<a href="<?php echo WEB_URL; ?>feed/conditions"<?php if($dashActive=="conditions"){ echo ' class="active"'; } ?>>Conditions</a>
				<?php
				} 
				?>
				</div>
				<span class="feedBarDivider">|</span>
				<div class="feedBarTopic dropdown">
				<?php 
				if(USER_ID!=0){
					$resSub = $topicClass->getAllUserFollowedFromTopic('s',USER_ID);
					if($resSub["result"] && $resSub["result"]>1){
				?>
					<a href="#" class="dropdown-toggle<?php if($dashActive=="symptoms"){ echo ' active'; } ?>" data-toggle="dropdown">Symptoms &#9662;</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<?php
						foreach($resSub as $key=>$value){
				?>
							<li><a href="<?php echo WEB_URL.$topicClass->pathSingular('s').'/'.$value["url_topic"]; ?>" <?php if(isset($resTopic) && $resTopic[0]["id_topic"]==$value["id_topic"]){ echo ' class="active"'; } ?>><?php echo $value["name_topic"]; ?></a></li>
				<?php
						}
					echo "</ul>";
					}else{
					?>
					<a href="<?php echo WEB_URL; ?>feed/symptoms"<?php if($dashActive=="symptoms"){ echo ' class="active"'; } ?>>Symptoms</a>
					<?php
					}
				}else{
				?>
				<a href="<?php echo WEB_URL; ?>feed/symptoms"<?php if($dashActive=="symptoms"){ echo ' class="active"'; } ?>>Symptoms</a>
				<?php
				} 
				?>
				</div>
				<span class="feedBarDivider">|</span>
				<div class="feedBarTopic dropdown">
				<?php 
				if(USER_ID!=0){
					$resSub = $topicClass->getAllUserFollowedFromTopic('m',USER_ID);
					if($resSub["result"] && $resSub["result"]>1){
				?>
					<a href="#" class="dropdown-toggle<?php if($dashActive=="medications"){ echo ' active'; } ?>" data-toggle="dropdown">Medications &#9662;</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<?php
						foreach($resSub as $key=>$value){
				?>
							<li><a href="<?php echo WEB_URL.$topicClass->pathSingular('m').'/'.$value["url_topic"]; ?>" <?php if(isset($resTopic) && $resTopic[0]["id_topic"]==$value["id_topic"]){ echo ' class="active"'; } ?>><?php echo $value["name_topic"]; ?></a></li>
				<?php
						}
					echo "</ul>";
					}else{
					?>
					<a href="<?php echo WEB_URL; ?>feed/medications"<?php if($dashActive=="medications"){ echo ' class="active"'; } ?>>Medications</a>
					<?php
					}
				}else{
				?>
				<a href="<?php echo WEB_URL; ?>feed/medications"<?php if($dashActive=="medications"){ echo ' class="active"'; } ?>>Medications</a>
				<?php
				} 
				?>
				</div>
				<span class="feedBarDivider">|</span>
				<div class="feedBarTopic dropdown">
				<?php 
				if(USER_ID!=0){
					$resSub = $topicClass->getAllUserFollowedFromTopic('p',USER_ID);
					if($resSub["result"] && $resSub["result"]>1){
				?>
					<a href="#" class="dropdown-toggle<?php if($dashActive=="procedures"){ echo ' active'; } ?>" data-toggle="dropdown">Procedures &#9662;</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
				<?php
						foreach($resSub as $key=>$value){
				?>
							<li><a href="<?php echo WEB_URL.$topicClass->pathSingular('p').'/'.$value["url_topic"]; ?>" <?php if(isset($resTopic) && $resTopic[0]["id_topic"]==$value["id_topic"]){ echo ' class="active"'; } ?>><?php echo $value["name_topic"]; ?></a></li>
				<?php
						}
					echo "</ul>";
					}else{
					?>
					<a href="<?php echo WEB_URL; ?>feed/procedures"<?php if($dashActive=="procedures"){ echo ' class="active"'; } ?>>Procedures</a>
					<?php
					}
				}else{
				?>
				<a href="<?php echo WEB_URL; ?>feed/procedures"<?php if($dashActive=="procedures"){ echo ' class="active"'; } ?>>Procedures</a>
				<?php
				} 
				?>
				</div>
			</div>
			<?php
			}
			?>
		</div>
		<?php
		if(isset($resTopic) && $resTopic["result"]){
		?>
		<div class="iMPost clearfix">
			<div id="iMPostTopInfo">
				<div id="iMPostInfoButtons">
				<?php
				if(USER_ID!=0){
					$resfollow=$topicClass->isFollowing($resTopic[0]["id_topic"]);
					if($resfollow["result"]){
					?>
					<div style="width:100%;margin-top:5px;">
						<button class="btn btn-blue" id="followBtn" style="width:100%;" onclick="location.href='<?php echo WEB_URL."act/topic_follow.php?id=".$resTopic[0]["id_topic"]; ?>'">Following</button>
					</div>
					<?php	
					$onload.="$('#followBtn').hover(function(){
						$(this).text('unfollow');	
					},function(){
						$(this).text('following');
					});";
					
					}else{
					?>
					<div style="width:100%;margin-top:5px;">
						<button class="btn btn-red" style="width:100%;" onclick="location.href='<?php echo WEB_URL."act/topic_follow.php?id=".$resTopic[0]["id_topic"]; ?>'">Follow</button>
					</div>
					<?php
					}
				}
				$resFol=$topicClass->countNumberOfUsersFollowingTopic($resTopic[0]["id_topic"],$resTopic[0]["type_topic"]);
				if($resFol["result"]){
				?>
				<a href="<?php echo WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"]).'/'.$resTopic[0]["url_topic"].'/followers';?>" style="display:block;text-align:center;margin-top:10px;"><?php echo $resFol[0]["total"]." Followers"; ?></a>
				<?php
				}
				?>
				</div>
			</div>
			<?php
			if($resTopic[0]["type_topic"]=="d"){
			?>
			<div class="iMPostMain" itemscope itemtype="http://schema.org/MedicalCondition">
				<h3 id="iMTopicName" itemprop="name"><?php echo $resTopic[0]["name_topic"]; ?></h3>
			<?php
			}else if($resTopic[0]["type_topic"]=="m"){
			?>
			<div class="iMPostMain" itemscope itemtype="http://schema.org/Drug">
				<h3 id="iMTopicName" itemprop="name"><?php echo $resTopic[0]["name_topic"]; ?></h3>
			<?php
			}else{
			?>
			<div class="iMPostMain"  itemtype="http://schema.org/MedicalWebPage">
				<h3 id="iMTopicName" itemprop="name"><?php echo $resTopic[0]["name_topic"]; ?></h3>
				<?php
				}
				if($resTopic[0]["definition_topic"]!=""){

					echo '<p itemprop="description">';
					echo $resTopic[0]["definition_topic"]; 
					if($resTopic[0]["source_topic"]!=""){
						echo ' <a href="'.$resTopic[0]["source_topic"].'" target="_blank" style="text-decoration:underline">Source »</a>';
					}
					echo "</p>";
				}
				?>
			</div>
		</div>
		</div>
		<?php
		}
		if(isset($_SESSION["welcome"]) && isset($_SESSION["welcome"]["first"]) && $_SESSION["welcome"]["first"]==true){
			$needAlert=1;
			?>
			<div class="alert alert-success">
				<a class="close" style="float:right;text-decoration:none;" data-dismiss="alert" href="#">&times;</a>
				Your experience is published! You should now <a href="<?php echo WEB_URL.USER_NAME; ?>" style="text-decoration:underline">start your diary</a>. On the <a href="<?php echo WEB_URL; ?>feed" style="text-decoration:underline">newsfeed</a> you will receive continuous experiences and news relevant to your health.  You can also <a href="<?php echo WEB_URL; ?>meet" style="text-decoration:underline">meet others</a> like you, and <a href="<?php echo WEB_URL; ?>track" style="text-decoration:underline">track</a> your health.  Check back often!
			</div>
			<?php
			$_SESSION["welcome"]["first"]=false;
			$jsfunctions.="mixpanel.track('Start Finished V2');";
			if(!$resPosts["result"]){
				$resPosts = $postClass->getNewPosts();
			}
		}else if(!$resPosts["result"] && $dashActive=="feed"){
			?>
			<div class="alert alert-info center">
				<h3>Your feed is not yet customized</h3>
				<p>
					Share a  health experience above or update your <a href="<?php echo WEB_URL.USER_NAME; ?>">profile</a> to get a personalized health feed.
				</p>
			</div>
			<?php
			$resPosts = $postClass->getNewPosts();
		}else if(!$resPosts["result"] && isset($resTopic)){
			?>
		<div class="alert alert-info center">
			<h3>There are no posts to present for <?php echo $resTopic[0]["name_topic"]; ?></h3>
			<p>
				Please check back later.
			</p>
		</div>
		<?php
		}else if(!$resPosts["result"] && isset($topicLetter)){
		?>
		<div class="alert alert-info center">
			<h3>There are no posts to present on your <?php echo strtolower($topicClass->namePlural($topicLetter)); ?> feed</h3>
			<p>
				To get a customized <?php echo strtolower($topicClass->namePlural($topicLetter)); ?> feed you need to follow <a href="<?php echo WEB_URL.$topicClass->pathPlural($topicLetter); ?>" class="underline"><?php echo strtolower($topicClass->namePlural($topicLetter)); ?></a>.
			</p>
		</div>
		<?php
		}	
		/*if(USER_ID!=0 && USER_TOUR==0){
			$jsfunctions.="
			var tour=1;
			function nextTour(){
				$('#tour_'+tour).slideUp();
				tour++;
				if(tour>12){
				tour=1;
				}
				$('#tour_'+tour).slideDown();
			}
			";
			?>
			<div id="tour" class="alert alert-info">
			<div id="tour_1">Below are a few tips to make make your experience on HealthKeep as simple and informative as possible. If you have any questions, feel free to <a href="<?php echo WEB_URL; ?>contact">email us</a>.<br /><br />
<b>1. <a href="<?php echo WEB_URL.USER_NAME; ?>">My Chart</a>.</b> On this page you will see your public profile. You should treat it as your  personal medical chart. On it you can add all of your medications, conditions, symptoms, procedures and doctors. You can also enter biographical and other health information, a brief bio, health goals, and a custom image.</div>
<div id="tour_2" style="display:none;"><b>2. <a href="<?php echo WEB_URL.USER_NAME; ?>#profileHealthTimelineHeader">Health Diary</a>.</b> Share experiences, photos and news often. Everything you share appears at the lower half of your chart. You should enter any and all health experiences you have or any tips, news, words of wisdom or photos you'd like others to see. It will serve as a permanent health timeline that you can share with your doctors.</div>
<div id="tour_3" style="display:none;"><b>3. Following.</b> You can follow users and health topics of interest by clicking the follow button on to the right of those items.
</div>
<div id="tour_4" style="display:none;"><b>4. Activity.</b> This is a real-time feed of information, customized just for you. It shows you all the news stories and member experiences that are relevant to the conditions or symptoms that you choose to follow or those that appear on your chart.</div>
<div id="tour_5" style="display:none;"><b>5. Filters.</b> At the top of the page is a bar where you can choose to see only specific topics based on your profile. You can also see news, experiences or both. To the right you can also sort the content by top voted or most recent as well.</div>
<div id="tour_6" style="display:none;"><b>6. Interact.</b> Like posts and comments and rate posts as helpful. You will be awarded badges for interacting.</div>
<div id="tour_7" style="display:none;"><b>7. Be helpful.</b> Write comments and give feedback to others going through health experience you can relate to. You will get badges.</div>
<div id="tour_8" style="display:none;"><b>8. Reply to your comments.</b> Check back to see responses to your posts and write back in the comment section.</div>
<div id="tour_9" style="display:none;"><b>9. Check in often.</b> To see latest content - your personal feed is constantly updating.</div>
<div id="tour_10" style="display:none;"><b>10. Customize settings.</b> Such as <a href="<?php echo WEB_URL; ?>account/notifications">inbox alerts</a>.</div>
<div id="tour_11" style="display:none;"><b>11. Message Users.</b> Go to any profile and click “Send Message” to send a message specific to that user.</div>
<div id="tour_12" style="display:none;"><b>12. Remember, you are always posting anonymously on HealthKeep</b> so feel free to share anything you like. We specifically ask you avoid marketing any products or soliciting people for any studies, surveys or other activities. Posts reported as inappropriate may flagged and removed.</b></div>
<div style="text-align:right;"><a href="<?php echo WEB_URL; ?>act/toggleTour.php?set=1">close</a> | <a href="#" onclick="nextTour();">next</a></div>
			</div>
			<?php
		}*/
		if($resPosts["result"]){
		?>
			<div id="postHolder" class="clearfix">
				<?php require_once(ENGINE_PATH."render/feed/list.php"); ?>
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
	
	</hgroup>	
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');