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

$dashActive=$topicClass->pathPlural($resTopic[0]["type_topic"]);

$pageTitle=$resTopic[0]["name_topic"].", Followers - HealthKeep";
$pageDescr="See all the users that follow ".$resTopic[0]["name_topic"]." on HealthKeep";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div style="margin:55px 0;background:#fff;">
			<h2 class="iFullHeading" style="padding:30px !important;">
				<a href="<?php echo WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"]).'/'.$resTopic[0]["url_topic"];?>"><?php echo $resTopic[0]["name_topic"]; ?></a> <span style="color:#999;">followers</span></h2>
		
			<div id="holdMeet" class="clearfix">
			<?php
			require_once(ENGINE_PATH."html/inc/common/usStates.php");
			$res=$resFollowers;
			require_once(ENGINE_PATH.'class/location.class.php');
			$locationClass=new Location();
			
			require_once(ENGINE_PATH."render/others/meet_list.php");
			?>
			</div>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');