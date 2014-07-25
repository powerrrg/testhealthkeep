<?php
if(isset($showFollowing)){
	$resFol=$topicClass->countNumberOfUsersFollowingTopic($resTopic[0]["id_topic"],$resTopic[0]["type_topic"]);
	if($resFol["result"]){
	?>
	<div class="iBoard3">
		<div class="iDashboardHolder">
			<h3 class="iDashboardHeading" style="margin:0;border:none;"><a href="<?php echo WEB_URL.$topicClass->pathSingular($resTopic[0]["type_topic"]).'/'.$resTopic[0]["url_topic"].'/followers';?>" style="display:block;color:#A03439;"><?php echo $resFol[0]["total"]." Followers"; ?></a></h3>
		</div>
	</div>
	<?php
	}
}
?>