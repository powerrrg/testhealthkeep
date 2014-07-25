<?php
if(USER_ID!=0){
	$logoLink=WEB_URL."feed";
}else{
	$logoLink=WEB_URL;
}

if(!isset($iamstarting)){
$needPanelMenu=1;
$onload.="var jPM = $.jPanelMenu({
    menu: '#jPanel',
    trigger: '#topMobileMenu'
});
jPM.on();
";

?>
<div id="jPanel" style="display:none;">
	<form method="get" action="<?php echo WEB_URL ;?>q.php" id="topSearchJpanel">
	<input type="text" name="q" id="q2" maxlength="100" style="margin:0 10px 0 10px;width:200px;" placeholder="Search" value="" />
	</form>
	<hr />
	<?php
	if(USER_ID==0){
	?>
	<a href="<?php echo WEB_URL; ?>about"<?php if(isset($active) && $active=="about"){ echo ' class="active"'; } ?>>About</a>
	<a href="<?php echo WEB_URL; ?>contact"<?php if(isset($active) && $active=="contact"){ echo ' class="active"'; } ?>>Contact</a>
	<?php	
	}else{
		if(USER_TYPE==9){
		?>
		<a href="<?php echo WEB_URL; ?>ges">Admin</a>
		<?php
		}
		?>
		<a href="<?php echo WEB_URL.USER_NAME; ?>">Diary</a>
		<hr />
		<?php
		if(PROFILE_TYPE==1){
		?>
		<a href="<?php echo WEB_URL; ?>meet">Meet Others</a>
		<hr />
			<?php
			/*if(TRACK_PROFILE==1){
			?>
			<a href="<?php echo WEB_URL; ?>track/activate">Acivate Tracking</a>
			<?php
			}else{*/
			?>
			<a href="<?php echo WEB_URL; ?>track">Tracking</a>
			
			<?php
			//}
			echo "<hr />";
		}
		?>
		<a href="<?php echo WEB_URL; ?>feed">NewsFeed</a>
		<?php
		$resSub = $topicClass->getAllUserFollowedFromTopic('d',USER_ID);
		if($resSub["result"] && $resSub["result"]>1){
		?>
			<a href="#" id="jPanelParent_d" class="jPanelParent" onclick="$('.jPanelParent_holder_d').toggle();">Conditions &#9662;</a>
			<div class="jPanelParent_holder jPanelParent_holder_d">
			<?php
			foreach($resSub as $key=>$value){
				?>
				<a href="<?php echo WEB_URL.$topicClass->pathSingular('d').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a>
			<?php
			}
			echo "</div>";
		}else{
		?>
			<a href="<?php echo WEB_URL; ?>feed/conditions">Conditions</a>
		<?php
		}
		$resSub = $topicClass->getAllUserFollowedFromTopic('s',USER_ID);
		if($resSub["result"] && $resSub["result"]>1){
		?>
			<a href="#" id="jPanelParent_s" class="jPanelParent" onclick="$('.jPanelParent_holder_s').toggle();">Symptoms &#9662;</a>
			<div class="jPanelParent_holder jPanelParent_holder_s">
			<?php
			foreach($resSub as $key=>$value){
				?>
				<a href="<?php echo WEB_URL.$topicClass->pathSingular('s').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a>
			<?php
			}
			echo "</div>";
		}else{
		?>
			<a href="<?php echo WEB_URL; ?>feed/symptoms">Symptoms</a>
		<?php
		}
		$resSub = $topicClass->getAllUserFollowedFromTopic('m',USER_ID);
		if($resSub["result"] && $resSub["result"]>1){
		?>
			<a href="#" id="jPanelParent_m" class="jPanelParent" onclick="$('.jPanelParent_holder_m').toggle();">Medications &#9662;</a>
			<div class="jPanelParent_holder jPanelParent_holder_m">
			<?php
			foreach($resSub as $key=>$value){
				?>
				<a href="<?php echo WEB_URL.$topicClass->pathSingular('m').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a>
			<?php
			}
			echo "</div>";
		}else{
		?>
			<a href="<?php echo WEB_URL; ?>feed/medications">Medications</a>
		<?php
		}
		$resSub = $topicClass->getAllUserFollowedFromTopic('p',USER_ID);
		if($resSub["result"] && $resSub["result"]>1){
		?>
			<a href="#" id="jPanelParent_p" class="jPanelParent" onclick="$('.jPanelParent_holder_p').toggle();">Procedures &#9662;</a>
			<div class="jPanelParent_holder jPanelParent_holder_p">
			<?php
			foreach($resSub as $key=>$value){
				?>
				<a href="<?php echo WEB_URL.$topicClass->pathSingular('p').'/'.$value["url_topic"]; ?>"><?php echo $value["name_topic"]; ?></a>
			<?php
			}
			echo "</div>";
		}else{
		?>
			<a href="<?php echo WEB_URL; ?>feed/procedures">Procedures</a>
		<?php
		}
		?>
		<hr />
		<a href="<?php echo WEB_URL; ?>msg"><?php if(PROFILE_MSGS>0){echo "(".PROFILE_MSGS.") ";} ?>Inbox</a>
		<hr />
		<a href="<?php echo WEB_URL; ?>account/details">Account Details</a>
		<?php
		if(PROFILE_TYPE==1){
		?>
		<a href="<?php echo WEB_URL; ?>account/health">Health Details</a>
		
		<?php
		}
		?>
		<a href="<?php echo WEB_URL; ?>account/notifications">Email Settings</a>
		<?php /*<a href="<?php echo WEB_URL; ?>act/toggleTour.php?set=0">Explore HealthKeep</a>*/ ?>
		<a href="<?php echo WEB_URL; ?>contact">Contact</a>
		<a href="<?php echo WEB_URL; ?>act/login.php?logout">Logout</a>
	<?php
		
	}
	/*<img src="<?php echo WEB_URL; ?>inc/img/v2/base/mobile_menu.png" alt="Mobile Menu Button" id="topMobileMenu" onclick="$('#topMenu').toggle();" />*/
	?>
</div>
<?php
}

?>
<header>
	<hgroup id="header" class="iWrap clearfix">
		<img src="<?php echo WEB_URL; ?>inc/img/v2/base/mobile_menu.png" alt="Mobile Menu Button" id="topMobileMenu" />
		<img src="<?php echo WEB_URL; ?>inc/img/v2/base/reload.png" alt="Reload Button" id="topMobileReload" onclick="$(this).css('opacity','0.1');location.reload();" />
		<div id="topLogoHolder">
			<a href="<?php echo $logoLink; ?>"><img src="<?php echo WEB_URL; ?>inc/img/v2/logo/HealthKeep.png" alt="HealthKeep Logo" id="topLogo" /></a>
		</div>
		<menu id="topMenu" <?php if(USER_ID>0){ echo 'style="right:375px;"'; } ?>>
			<?php
			if(USER_ID==0){
			?>
			<div style="padding-right:210px;">
			<a href="<?php echo WEB_URL; ?>about"<?php if(isset($active) && $active=="about"){ echo ' class="active"'; } ?>>About</a>
			<a href="<?php echo WEB_URL; ?>contact"<?php if(isset($active) && $active=="contact"){ echo ' class="active"'; } ?>>Contact</a>
			</div>
			<?php	
			}else{
			
			if(USER_TYPE==9){
			?>
			<a href="<?php echo WEB_URL; ?>ges">Admin</a>
			<?php
			}
			?>
			<a href="<?php echo WEB_URL.USER_NAME; ?>"<?php if(isset($_GET["l1"]) && $_GET["l1"]==USER_NAME){ echo ' class="active"'; } ?>>Diary</a>
			<a href="<?php echo WEB_URL; ?>feed"<?php if(isset($_GET["l1"]) && $_GET["l1"]=="feed"){ echo ' class="active"'; } ?>>NewsFeed</a>
			<?php
				if(PROFILE_TYPE==1){
				?>
				<a href="<?php echo WEB_URL; ?>meet"<?php if(isset($_GET["l1"]) && $_GET["l1"]=="meet"){ echo ' class="active"'; } ?>>Meet Others</a>
				<?php
				//if(TRACK_PROFILE==0){
				?>
						<a href="<?php echo WEB_URL; ?>track"<?php if(isset($_GET["l1"]) && $_GET["l1"]=="track"){ echo ' class="active"'; } ?>>Tracking</a>
				<?php
				//}
				}
			}
			?>
		</menu>
		<?php
		if(isset($_GET["l1"]) && isset($_GET["l2"]) && $_GET["l1"]=="q"){
				$qvalue=urldecode($_GET["l2"]);
			}else{
				$qvalue="";
			}
			?>
			<div id="topSearchHolder" <?php if(isset($active) && $active=="homepage"){ echo 'class="marginTop9"'; } ?>>
				<form method="get" action="<?php echo WEB_URL ;?>q.php" id="topSearch">
				<input type="text" name="q" id="q" maxlength="100" placeholder="Search" value="<?php echo $qvalue; ?>" autocomplete="off" />
				</form>
			</div>
			<?php
			$onload.="$('input[placeholder]').placeholder();";
			/*$needTypeAhead=1;
			$jsfunctions.="
			$(function () {
				$('#q').typeahead({
			        ajax: { url: '".WEB_URL."act/ajax/search/typeahead.php', triggerLength: 1 }, 
			        itemSelected: displayResult
			    });
	
			});
			function displayResult(item, val, text) {
				if(val==0){
					location.href='".WEB_URL."q.php?q='+$('#q').val();
				}else{
					location.href='".WEB_URL."q.php?id='+val;
				}
			}
			";*/
			$onload.="$('#topSearch').submit(function(){
				if($('#q').val().length<3){
				 alert('You can only search words with 3 characters or more!');
				 return false;
				}
			});";

		
		if(USER_ID==0){ 
			$goTo=$_SERVER["REQUEST_URI"];
		    if(isset($_GET["go"])){
		    	$goTo="?go=".ltrim($_GET["go"],"/");
		    }else if($goTo=="" || $goTo=="/"){
			    $goTo="";
		    }else{
			    $goTo="?go=".ltrim($goTo,"/");
		    }
		?>
			<div id="topLoginBtn" >
				<a href="<?php echo WEB_URL; ?>login.php<?php echo $goTo; ?>" class="btn btn-red">Login</a>
			</div>
		<?php
		}else{
			?>
		 	<div id="topInbox">
		 	<a href="<?php echo WEB_URL; ?>msg">
			<img src="<?php echo WEB_URL."inc/img/v2/base/message.png"; ?>" alt="img" /></a>
			<?php if(PROFILE_MSGS>0){echo "<span id=\"topInboxCount\">".PROFILE_MSGS."</spa>";} ?>
			</div>
			<div id="topAccount" class="btn-group">
				<?php
				if(USER_IMAGE==""){
					$userImageUrl=$imagePath=WEB_URL."inc/img/empty-avatar.png";
				}else{
					$userImageUrl=WEB_URL."img/profile/tb/".USER_IMAGE;
				}
				?>
				<a class="dropdown-toggle" id="topAccountBtn" style="background-image:url('<?php echo $userImageUrl; ?>');" data-toggle="dropdown">
					
					
					<span></span>
				</a>
	
				<ul class="dropdown-menu pull-right">
					<li><a href="<?php echo WEB_URL; ?>account/details">Account Details</a></li>
					<?php
					if(PROFILE_TYPE==1){
					?>
					<li><a href="<?php echo WEB_URL; ?>account/health">Health Details</a></li>
<?php /* 					<li><a href="<?php echo WEB_URL; ?>track/activate">Acivate Tracking</a></li>  */ ?>
					<?php
					}
					?>
					<li><a href="<?php echo WEB_URL; ?>account/notifications">Email Settings</a></li>
					<?php /*<li><a href="<?php echo WEB_URL; ?>act/toggleTour.php?set=0">Explore HealthKeep</a></li> */ ?>
					<li><a href="<?php echo WEB_URL; ?>contact">Contact</a></li>
					<li><a href="<?php echo WEB_URL; ?>act/login.php?logout">Logout</a></li>
				</ul>
			</div>
			<?php
		}
		
		?>
	</hgroup>
</header>
