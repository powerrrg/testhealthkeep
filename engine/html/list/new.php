<?php
require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH."class/post.class.php");
$postClass=new Post();

$pageTitle="New posts - HealthKeep";
$pageDescr="Latest health posts by HealthKeep users";

$designV1=1;

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div id="iFeed" class="iBoard  clearfix">
			<div id="iFeedContent">
				<div id="postDetail">
					<div class="iHeading clearfix marginBottom20">
					<h3 class="feedHeading"><span class="colorLighterBlue">New</span> <span class="colorGray">Posts</span></h3>
					</div>
					<?php
					$backPath="new";
					$resPosts = $postClass->getNewPosts();
					?>
					<div id="postHolder">
					<?php require_once(ENGINE_PATH."html/list/posts.php"); ?>
					</div>
					<?php
					$ajaxUrl=WEB_URL."act/ajax/feed/all.php";
					$onload.="endlessScroll('$ajaxUrl',$('#postHolder'));";
					require_once(ENGINE_PATH."html/inc/endless.php");
					?>
				</div>
			</div>
			<?php
			$needPanelMenu=1;
			$onload.="var jPM = $.jPanelMenu({
			menu: '#jPMHolder',
			trigger: '#goPanelMenu'
			});
			jPM.on();
			";
			//ATT:I changed the file jquery.jpanelmenu.min.js added $('#goPanelMenu').hide(); and $('#goPanelMenu').show(); to beforeOpen and beforeClose
			?>
			<div id="goPanelMenu">menu</div>
			<div id="iFeedSidebar">
			<div id="jPMHolder">
				<div class="iHeading clearfix marginBottom20">
					<h3 class="feedHeading"><span class="colorLighterBlue">New</span> <span class="colorGray">Users</span></h3>
				</div>
				<div class="iBoard3">
					<div class="iDashboardHolder clearfix">
						<?php
						$resPro=$profileClass->getNewProfiles();
						foreach($resPro as $proKey=>$proValue){
							if(is_int($proKey)){
								if($proValue["type_profile"]==1){
									$displayName=$proValue["username_profile"];
								}else{
									$displayName=$proValue["name_profile"];
								}
								$imgAltText="";
								if($proValue["image_profile"]==""){
									if($proValue["type_profile"]==1 && $proValue["gender_profile"]=="m"){
										$img=WEB_URL."inc/img/avatar/avatar_male.jpg";
										$imgAltText="Man avatar";
									}else if($proValue["type_profile"]==1 && $proValue["gender_profile"]=="f"){
										$img=WEB_URL."inc/img/avatar/avatar_female.jpg";
										$imgAltText="Woman avatar";
									}else if($proValue["type_profile"]==2){
										$img=WEB_URL."inc/img/avatar/avatar_doctor.jpg";
										$imgAltText="Doctor avatar";
									}else if($proValue["type_profile"]==3){
										$img=WEB_URL."inc/img/avatar/avatar_facility.jpg";
										$imgAltText="Professional avatar";
									}else if($proValue["type_profile"]==4){
										$img=WEB_URL."inc/img/avatar/avatar_news.jpg";
										$imgAltText="News avatar";
									}else{
										$img=WEB_URL."inc/img/avatar/avatar_male.jpg";
										$imgAltText="Man avatar";
									}
								}else{
									$img=WEB_URL."img/profile/tb/".$proValue["image_profile"];
									$imgAltText=$displayName;
								}
								$time=strtotime($proValue["created_profile"]);
								?>
								<div class="iDashboardProfile clearfix">
									<a href="<?php echo WEB_URL.$proValue["username_profile"]; ?>" ><img src="<?php echo $img; ?>" alt="<?php echo $imgAltText; ?>" width="40" height="40"></a>
									<div style="float:left;">
									<h5><a href="<?php echo WEB_URL.$proValue["username_profile"]; ?>" ><?php echo $displayName; ?></a></h5>
									<p><?php echo $configClass->ago($time); ?></p>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');