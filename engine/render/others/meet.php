<?php
onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/location.class.php');
$locationClass=new Location();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

$headingTitle="Meet Others";
$pageTitle="Meet Others - HealthKeep";
$pageDescr="Meet other users like you.";

$resTT=$topicClass->getTotalTopicsFollowed();

$totalTops=0;

if($resTT["result"]){
	$totalTops=$resTT[0]["total"];
}

if(!isset($_GET["l2"])){

	if($totalTops==0){
		header("Location:".WEB_URL."meet/active");
		exit;
	}
	$res=$profileClass->findSimilar();
	$headText="Meet Others Similar To You";
	$ajaxUrl=WEB_URL."act/ajax/meet/similar.php";
	if(!$res["result"]){
		$headText="Meet Others";
		$res=$profileClass->findPopular();
		$ajaxUrl=WEB_URL."act/ajax/meet/popular.php";
	}
	
}else if(isset($_GET["l2"]) && $_GET["l2"]=="active"){
	$headText="Meet Other Active Users";
	$res=$profileClass->findPopularByBadges();
	$ajaxUrl=WEB_URL."act/ajax/meet/active.php";
}else if(isset($_GET["l2"]) && $_GET["l2"]=="recent"){
	$headText="Meet Other Users Recently Online";
	$res=$profileClass->findRecent();
	$ajaxUrl=WEB_URL."act/ajax/meet/recent.php";
}else{
	go404();
}

$active="meet";
require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div style="margin:55px 0;background:#fff;">
			<div style="float:right;padding:30px;">
			<?php
			if($totalTops>0){
			?>
			<a href="<?php echo WEB_URL."meet"; ?>" <?php if(!isset($_GET["l2"])){ echo "style=\"color:#5F91CC;\""; } ?>>Similar</a> | 
			<?php
			}
			?>
			<a href="<?php echo WEB_URL."meet/active"; ?>" <?php if(isset($_GET["l2"]) && $_GET["l2"]=="active"){ echo "style=\"color:#5F91CC;\""; } ?>>Active</a> | <a href="<?php echo WEB_URL."meet/recent"; ?>" <?php if(isset($_GET["l2"]) && $_GET["l2"]=="recent"){ echo "style=\"color:#5F91CC;\""; } ?>>Recent</a>
			</div>
			<h2 class="iFullHeading" id="headMeet"><?php echo $headText; ?></h2>
			<div id="holdMeet" class="clearfix">
				<?php
				require_once(ENGINE_PATH."render/others/meet_list.php");
				?>
			</div>
			<?php
			$onload.="endlessScroll('$ajaxUrl',$('#holdMeet'));";
			require_once(ENGINE_PATH."render/feed/endless.php");
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');