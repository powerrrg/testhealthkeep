<?php
onlyLogged();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();
$topic=(int)$_GET["l3"];
if($topic==0){
	go404();
}
$resTopic=$topicClass->getById($topic);
if(!$resTopic["result"]){
	go404();
}

$pageTitle="You are following a new topic - HealthKeep";
$pageDescr="You are following a new topic";

$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
$active="timeline";
require_once(ENGINE_PATH.'html/top.php');

require_once(ENGINE_PATH.'html/inc/common/typeArray.php');
$typeArray=typeArray($resTopic[0]["type_topic"]);

$total=$topicClass->getTotalNumberOfFollowers($topic);
if($total["result"]){
	$total=$total[0]["total"];
}else{
	$total=0;
}
?>
<div id="main">
	<div class="iHold">
		<div id="iTimeline" class="iBoard clearfix">
			<div class="iBoxHeadingColoured clearfix margin0">
				<div class="iBoxHeadingColouredHeading iBoxHeading_<?php echo $typeArray["color"]; ?> clearfix">
					<h3><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/<?php echo $typeArray["icon"]; ?>" /> Following new <?php echo strtolower($typeArray["singular"]); ?></h3>
				</div>
				<div id="tlNewFollow">
					You added <span class="bold color<?php echo ucfirst($typeArray["color"]); ?>"><?php echo $resTopic[0]["name_topic"]; ?></span> to your timeline.<br />
					<?php
					if($total>10){
					?>
					We have a community of <span class="bold color<?php echo ucfirst($typeArray["color"]); ?>"><?php echo $total; ?></span> users that also follow <span class="bold color<?php echo ucfirst($typeArray["color"]); ?>"><?php echo $resTopic[0]["name_topic"]; ?></span>.
					<?php
					}else{
					?>
					We also added you to the <span class="bold color<?php echo ucfirst($typeArray["color"]); ?>"><?php echo $resTopic[0]["name_topic"]; ?></span> community.
					<?php
					}
					?>
					<div class="group-btn" style="margin-top:30px;">
						<a href="<?php echo WEB_URL."timeline"; ?>" class="btn btn-blue">Back to timeline</a>
						<a href="<?php echo WEB_URL."timeline/add/".$topicClass->pathSingular($resTopic[0]["type_topic"]); ?>" class="btn btn-blue">Add another <?php echo strtolower($typeArray["singular"]); ?></a> 
						<a href="<?php echo WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"])."/".$resTopic[0]["url_topic"]; ?>" class="btn btn-red">Visit this community</a>
					</div>
				</div>
			</div>

		
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');