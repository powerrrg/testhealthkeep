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
	<div id="iDashboard" class="iBoard3">
		<a href="<?php echo WEB_URL; ?>feed"<?php if($dashActive=="feed"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/feed<?php if($dashActive=="feed"){ echo '_w'; } ?>.png" alt="Feed" />
		My Feed
		</a>
		<?php
		/*
		<a href="<?php echo WEB_URL; ?>feed/doctors"<?php if($dashActive=="doctors"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/docs<?php if($dashActive=="doctors"){ echo '_w'; } ?>.png" alt="doctors" />
		Doctors
		</a>
		*/
		?>
		<a href="<?php echo WEB_URL; ?>feed/conditions"<?php if($dashActive=="conditions"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/cond<?php if($dashActive=="conditions"){ echo '_w'; } ?>.png" alt="conditions" />
		Conditions
		</a>
		<?php
		if(USER_ID!=0 && $dashActive=="conditions"){ 
			$resSub = $topicClass->getAllUserFollowedFromTopic('d',USER_ID);
			if($resSub["result"] && $resSub["result"]>1){
				foreach($resSub as $key=>$value){
		?>
					<span><a href="<?php echo WEB_URL.'feed/'.$topicClass->pathSingular('d').'/'.$value["url_topic"]; ?>" <?php if(isset($resTopic) && $resTopic[0]["id_topic"]==$value["id_topic"]){ echo ' class="subActive"'; } ?>><?php echo $value["name_topic"]; ?></a></span>
		<?php
				}
			}
		} 
		?>
		<a href="<?php echo WEB_URL; ?>feed/symptoms"<?php if($dashActive=="symptoms"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/feed<?php if($dashActive=="symptoms"){ echo '_w'; } ?>.png" alt="symptoms" />
		Symptoms
		</a>
		<?php
		if(USER_ID!=0 && $dashActive=="symptoms"){ 
			$resSub = $topicClass->getAllUserFollowedFromTopic('s',USER_ID);
			if($resSub["result"] && $resSub["result"]>1){
				foreach($resSub as $key=>$value){
		?>
					<span><a href="<?php echo WEB_URL.'feed/'.$topicClass->pathSingular('s').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a></span>
		<?php
				}
			}
		} 
		?>
		<a href="<?php echo WEB_URL; ?>feed/medications"<?php if($dashActive=="medications"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/meds<?php if($dashActive=="medications"){ echo '_w'; } ?>.png" alt="medications" />
		Medications
		</a>
		<?php
		if(USER_ID!=0 && $dashActive=="medications"){ 
			$resSub = $topicClass->getAllUserFollowedFromTopic('m',USER_ID);
			if($resSub["result"] && $resSub["result"]>1){
				foreach($resSub as $key=>$value){
		?>
					<span><a href="<?php echo WEB_URL.'feed/'.$topicClass->pathSingular('m').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a></span>
		<?php
				}
			}
		} 
		?>
		<a href="<?php echo WEB_URL; ?>feed/procedures"<?php if($dashActive=="procedures"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/pro<?php if($dashActive=="procedures"){ echo '_w'; } ?>.png" alt="procedures" />
		Procedures
		</a>
		<?php
		if(USER_ID!=0 && $dashActive=="procedures"){ 
			$resSub = $topicClass->getAllUserFollowedFromTopic('p',USER_ID);
			if($resSub["result"] && $resSub["result"]>1){
				foreach($resSub as $key=>$value){
		?>
					<span><a href="<?php echo WEB_URL.'feed/'.$topicClass->pathSingular('p').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a></span>
		<?php
				}
			}
		} 
		?>
		<a href="<?php echo WEB_URL; ?>feed/new"<?php if($dashActive=="new"){ echo ' class="active"'; } ?>>
		<img src="<?php echo WEB_URL; ?>inc/img/v1/sidebar/new<?php if($dashActive=="n32"){ echo '_w'; } ?>.png" alt="Feed" />
		All Activity
		</a>
	</div>
	<?php 
	require_once(ENGINE_PATH."html/topic/sidebarFollowing.php"); 
	require_once(ENGINE_PATH."html/topic/sidebarSynonym.php"); 
	?>
	<div class="iBoard3">
		<div class="iDashboardHolder">
			<h3 class="iDashboardHeading" style="background:#fff url('<?php echo WEB_URL; ?>inc/img/v1/sidebar/friends.png') no-repeat 10px center;">Invite Friends</h3>
			<div class="iDashboardContent">
				<?php require_once(ENGINE_PATH."html/inc/inviteFriends.php"); ?>
			</div>
		</div>
	</div>
	<div class="iBoard3">
		<div class="iDashboardHolder">
			<h3 class="iDashboardHeading" style="background:#fff url('<?php echo WEB_URL; ?>inc/img/v1/sidebar/feed.png') no-repeat left center;">Share</h3>
			<div class="iDashboardContent" style="padding:15px 0px;">
				<?php require_once(ENGINE_PATH."html/inc/shareButtonsPrivatePages.php"); ?>
			</div>
		</div>
	</div>
	</div><?php //JPMHolder ?>
</div>