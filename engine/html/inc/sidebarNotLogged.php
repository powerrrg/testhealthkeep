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
		<div class="iBoard3 margintop0">
			<div class="iDashboardHolder">
				<h3 class="iDashboardHeading" style="background:#fff url('<?php echo WEB_URL; ?>inc/img/v1/sidebar/new.png') no-repeat left center;">Register</h3>
				<div class="iDashboardContent">
					HealthKeep is a private secure way to share about your health with doctors and other people like you.<br /><br />
					<input type="button" onclick="location.href='<?php echo WEB_URL; ?>'" value="Register" class="btn btn-red" style="width:100%;" />
				</div>
			</div>
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
					<?php require_once(ENGINE_PATH."html/inc/shareButtons.php"); ?>
				</div>
			</div>
		</div>
	</div>
</div>