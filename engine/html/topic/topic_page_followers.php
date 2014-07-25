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

$resFollowers=$topicClass->usersFollowingTopic($resTopic[0]["id_topic"]);

if(!$resFollowers["result"]){
	go404();
}

$active="feed";
$designV1=1;

$dashActive=$topicClass->pathPlural($resTopic[0]["type_topic"]);

$pageTitle=$resTopic[0]["name_topic"].", Followers - HealthKeep";
$pageDescr="See all the users that follow ".$resTopic[0]["name_topic"]." on HealthKeep";

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
				<h2>Did you know?</h2>
				If you create an account you will get a personalized health feed, the ability to post and share, and a personal health timeline to track and manage your health.<br />
				<form id="boxRegister" method="post" style="text-align:center;margin-top:20px;" action="<?php echo WEB_URL; ?>act/register.php?v2">
					<input type="email" id="hpSingleInputSmall" name="email" placeholder="Enter your email adress" />
					<input type="hidden" name="username" value="user<?php echo time(); ?>" />
					<input type="hidden" name="password" value="<?php echo substr($token, 0,6); ?>" />
					<input type="hidden" name="gender" value="m" />
					<input type="text" name="hpot" class="hpot" value="" />
					<input type="hidden" name="token" value="<?php echo $token; ?>" /><br />
					<input type="submit" id="proFormBtn" disabled class="btn submitBtn btn-success" value="Create Account" />
					</form>

				</div>
				<?php
				$_SESSION["welcome"]=array('location'=>'topic','id'=>$resTopic[0]["id_topic"]);
				if(!$jsTopFormIsSet){
					$onload.="$('.submitBtn').prop('disabled', false);
					$('input[placeholder]').placeholder();";
				}
				
				if(!isset($needAlert)){
				$needAlert=1;
				$onload.="$('.alert').alert();";
				}
				$onload.="$('#hpSingleInputSmall').focus();";
				$jsfunctions.="
				function testEmail(){
					if(isValidEmailAddress($('#hpSingleInputSmall').val())){
						return true;
					}else{
						alert('Invalid email!');
						return false;
					}
				}";
				$onload.="
				$('#boxRegister').submit(function(){
					return testEmail();
				});
				";
				$jsfunctions.="mixpanel.track('Not Logged Topic Detail Page', {'topic':'".$_GET["l1"]." - ".$_GET["l2"]."'});";
				}
				?>
				<div class="iHeading clearfix marginBottom20">
					<h1 class="profileHeadingName"><a href="<?php echo WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"]).'/'.$resTopic[0]["url_topic"];?>" class="colorLighterBlue"><?php echo $resTopic[0]["name_topic"]; ?></a> <span style="color:#666;">followers</span></h1>
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
				<div id="iListFollowers">
				<?php
				require_once(ENGINE_PATH."html/inc/common/usStates.php");
				foreach($resFollowers as $key=>$value){
					if(is_int($key)){
					if($value["image_profile"]==""){
						$imagePath=WEB_URL."inc/img/empty-avatar.png";
						$imageAlt="No Image Avatar";
					}else{
						$imagePath=WEB_URL."img/profile/tb/".$value["image_profile"];
						$imageAlt=$configClass->name($value, false);
					}
					?>
						<a href="<?php echo WEB_URL.$value["username_profile"];?>"  class="clearfix">
							<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
							<div>
								<h4><?php echo $configClass->name($value, false); ?></h4>
								<p><?php 
								if($value["country_profile"]!='US' && $value["country_profile"]!=''){
									echo $value["short_name"];
								}else if($value["state"]!='' && isset($usStates[$value["state"]])){
									echo $usStates[$value["state"]];
								}else if($value["type_profile"]==2){
									if(isset($usStates[$value["state_doctor"]])){
										echo $usStates[$value["state_doctor"]];
									}
								}
								echo "<br />";
								if($value["job_profile"]!=''){
									echo $value["job_profile"];
								}else if($value["type_profile"]==2 && $value["name_taxonomy"]!=''){
									echo $value["name_taxonomy"];
								}
								?></p>
							</div>
						</a>
					<?php
					}
				}
				?>
				</div>
			</div>
			<?php
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