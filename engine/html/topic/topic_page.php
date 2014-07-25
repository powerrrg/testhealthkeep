<?php
require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();

$resTopic=$topicClass->getByUrl($_GET["l2"],$topicType);

if(!$resTopic["result"]){
	go404();
}

$active="feed";
$designV1=1;

$dashActive=$topicClass->pathPlural($resTopic[0]["type_topic"]);

if(isset($_GET["l2"]) && $_GET["l2"]=="fibromyalgia"){
$pageTitle="What is Fibro? - HealthKeep";	
}else{
$pageTitle=$resTopic[0]["name_topic"]." - HealthKeep";
}

$pageDescr="Find help and share your experience with ".$resTopic[0]["name_topic"];

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold">
		<div id="iFeed" class="iBoard clearfix">
			<div id="iFeedContent">
				<?php
				if(USER_ID==0){
				?>
				<div class="alert alert-info">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php
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
				}else{
				?>
				<h2 class="center">Share and learn from health experiences</h2>
				<?php
				}
				?>
					<form id="homeRegister" method="post" action="<?php echo WEB_URL; ?>doorstep.php">
					<?php
					if(isset($_GET["l2"]) && $_GET["l2"]=="fibromyalgia"){
					?>
					<textarea id="homeExperience" name="homeExperience" style="width:100%;height:100px;background:#fff;color:#666;" placeholder="Share your fibromyalgia health experience"></textarea><br />
					<?php
					}else{
					?>
						<textarea id="homeExperience" name="homeExperience" style="width:100%;height:100px;background:#fff;color:#666;" placeholder="Share a health experience. Share about a condition, medication or symptom. It can be about you or someone else."></textarea><br />
						<?php
						}
						?>
						<input type="text" name="hpot" class="hpot" value="" />
						<input type="hidden" name="token" value="<?php echo $token; ?>" />
						<input type="hidden" name="topic" value="<?php echo $resTopic[0]["id_topic"]; ?>" />
						<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-large btn-success" value="Share" />
						<div id="homeRegisterAnonInfo">
						Don't worry, it's anonymous
						</div>
					</form>

				</div>
				<?php
				$jsfunctions.="mixpanel.track('Landing Topic Page NEW');";
				if(!$jsTopFormIsSet){
					$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";
				}
				
				if(!isset($needAlert)){
				$needAlert=1;
				$onload.="$('.alert').alert();";
				}
				$onload.="$('#homeExperience').focus();";
				$onload.="
				$('#homeRegister').submit(function(){
					if($('#homeExperience').val().length<10){
						alert('You need to add a health experience, question or concern.');
						return false;
					}else{
						return true;
					}
				});
				";
				}
				?>
				<div class="iHeading clearfix marginBottom20">
					<h1 class="profileHeadingName"><?php echo $resTopic[0]["name_topic"]; ?></h1>
					<?php
					if(USER_ID!=0){
	
						$resfollow=$topicClass->isFollowing($resTopic[0]["id_topic"]);
						if($resfollow["result"]){
						?>
						<div class="profileHeadingBtns">
							<button class="btn btn-blue" id="followBtn" onclick="location.href='<?php echo WEB_URL."act/topic_follow.php?id=".$resTopic[0]["id_topic"]; ?>'">Following</button>
						</div>
						<?php	
						$onload.="$('#followBtn').hover(function(){
							$(this).text('unfollow');	
						},function(){
							$(this).text('following');
						});";
						
						}else{
						?>
						<div class="profileHeadingBtns">
							<button class="btn btn-red" onclick="location.href='<?php echo WEB_URL."act/topic_follow.php?id=".$resTopic[0]["id_topic"]; ?>'">Follow</button>
						</div>
						<?php
						}
						
					}else{
						?>
						<div class="profileHeadingBtns btn-group">
							<a class="btn btn-blue dropdown-toggle" data-toggle="dropdown">Follow</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="<?php echo WEB_URL; ?>login.php?go=<?php echo $_GET["l1"]."/".$_GET["l2"]; ?>">Login</a></li>
								<li><a href="<?php echo WEB_URL; ?>">Register</a></li>
							</ul>
						</div>
						<?php
					}
					?>
					
				</div>
				<?php
				if($resTopic[0]["definition_topic"]!=""){
					echo '<div class="iBoard2 marginBottom20 colorGray">';
					echo $resTopic[0]["definition_topic"]; 
					if($resTopic[0]["source_topic"]!=""){
						echo '<a href="'.$resTopic[0]["source_topic"].'" target="_blank" class="colorRed">Source »</a>';
					}
					echo "</div>";
				}			
				if(USER_ID!=0){
					if(!isset($needAutoGrow)){
						$needAutoGrow=1;
						$onload.="$('textarea').autogrow();";
					}
					?>
					<form class="iPost marginBottom20" id="topicPost" method="post" action="<?php echo WEB_URL."act/topic_post.php?id=".$resTopic[0]["id_topic"]; ?>">
						<textarea placeholder="Leave a post about <?php echo $resTopic[0]["name_topic"]; ?>" class="textArea100" name="txtPost" id="txtPost"></textarea>
						<div class="iPostBtns">
							<input type="submit" disabled class="btn btn-red submitBtn iPostSubmitBtn" value="post" />
						</div>
					</form>
					<?php
						$onload.="$('#txtPost').keyup(function(){
							if($('#txtPost').val().length>5){
								$('.iPostSubmitBtn').prop('disabled', false);
							}else{
								$('.iPostSubmitBtn').prop('disabled', true);
							}
						});";
						if(!$jsTopFormIsSet){
							$onload.="$('input[placeholder]','textarea[placeholder]').placeholder();";
							$jsTopFormIsSet=1;
						}
						$onload.="
						$('#topicPost').submit(function(){
							if($('#txtPost').val().length<5){
								alert('You need to type a message to be able to post!');
								$('#txtPost').focus();
								return false;
							}else{
								return true;
							}
						});
						";
				}
				
				if(isset($_SESSION["prank"]) && isset($_SESSION["prank"][$topicType]) && isset($_SESSION["prank"][$topicType][$resTopic[0]["id_topic"]]) && $_SESSION["prank"][$topicType][$resTopic[0]["id_topic"]]=="rank"){
					$postToggle="rank";
					$resPosts = $postClass->getByTopicId($resTopic[0]["id_topic"],1,'rank');
				}else{
					$postToggle="recent";
					$resPosts = $postClass->getByTopicId($resTopic[0]["id_topic"]);
				}
				
				
				if($resPosts["result"]){
					$backPath=$_GET["l1"]."/".$_GET["l2"];
					?>
					<div id="postToggle" class="center paddingBottom20">
					<?php
					if(!isset($postToggle) || (isset($postToggle) && $postToggle=="rank")){
						?>
						<a href="<?php echo WEB_URL."setOrderTopic.php?f=".$topicType."&o=recent&i=".$resTopic[0]["id_topic"]."&b=".urlencode($backPath); ?>">most recent</a> | <span>most voted</span>
						<?php
					}else{
						echo '<span>most recent</span> | <a href="'.WEB_URL.'setOrderTopic.php?f='.$topicType.'&o=rank&i='.$resTopic[0]["id_topic"].'&b='.urlencode($backPath).'">most voted</a>';
					}
					?>
					</div>
					<div id="specificPostHolder">
					<?php require_once(ENGINE_PATH."html/list/posts.php"); ?>
					</div>
					<?php
				
					$ajaxUrl=WEB_URL."act/ajax/topic/page.php";
					$onload.="endlessScroll('$ajaxUrl',$('#specificPostHolder'),".$resTopic[0]["id_topic"].",'".$_GET["l2"]."','$topicType');";
					require_once(ENGINE_PATH."html/inc/endless.php");
				}
				?>
				
			</div>
			<?php
			$showFollowing=1;
			$lookForSynonyms=1;
			if(USER_ID==0){
				require_once(ENGINE_PATH."html/inc/sidebarNotLogged.php");
			}else{
				require_once(ENGINE_PATH."html/inc/feedSidebar.php");
			}
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');