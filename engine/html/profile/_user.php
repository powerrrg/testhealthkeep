<?php
require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

require_once(ENGINE_PATH.'class/timeline.class.php');
$timelineClass=new Timeline();

$pageTitle=$resProfile[0]["username_profile"]." - HealthKeep";
$pageDescr=$resProfile[0]["username_profile"]." HealthKeep profile page.";

require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/bar.php');

?>
<div id="main" class="iHold clearfix">
	<div id="pageSidebarSmall">
		<div  class="iRounded iBoardWithHeader clearfix">
			<h3 class="iBoardHeader">Profile</h3>
			<div id="profileImage">
			<?php
			if($resProfile[0]["image_profile"]==""){
				$imagePath=WEB_URL."inc/img/empty-avatar.png";
				$imageAlt="No Image Avatar";
			}else{
				$imagePath=WEB_URL."img/profile/med/".$resProfile[0]["image_profile"];
				$imageAlt=$resProfile[0]["username_profile"];
			}
			if($resProfile[0]["id_profile"]==USER_ID){
			?>
			<form action="<?php echo WEB_URL; ?>act/profile/uploadAvatar.php" enctype="multipart/form-data" id="avatarImg" method="post">
			<div class="fileupload fileupload-new" data-provides="fileupload">
			  <div class="fileupload-new thumbnail" style="width: 100%;">
			  	<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
			  </div>
			  <div class="fileupload-preview fileupload-exists thumbnail" style="width: 100%;"></div>
			  <div>
			    <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>
			    <input type="file" accept="image/*"  name="avatarFile" id="avatarFile" /></span>
			    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
			  </div>
			</div>
			<div id="subImg" style="display:none;">
				<input type="submit" class="btn btn-success" value="Save image" />
			</div>
			</form>
			<?php
			$needFupload=1;
			$onload.="$('#avatarFile').bind('change', function() {
				$('#subImg').hide();
				if(this.files[0]!=undefined && this.files[0].size>2097152){
					alert('The Image cannot have more than 2 MB in size');
					$('.fileupload').fileupload('clear');
			  	}else if(this.files[0]!=undefined){
			  		var val = $(this).val();
			  		var val = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
			  		if(val!='gif' && val!='jpg' && val!='jpeg' && val!='png'){
				  		alert('That is not a valid image file!');
			  			$('.fileupload').fileupload('clear');			            
			  		}else{
			  			$('#subImg').show();
			  		}
			  	}
			});";
			}else{
				?>
				<img src="<?php echo $imagePath; ?>" alt="<?php echo $imageAlt; ?>" />
				<?php
			}
			?>
			<b><?php echo $resProfile[0]["username_profile"]; ?></b>
			</div>
			<?php
			if($resProfile[0]["id_profile"]!=USER_ID && USER_ID!=0){
			
			$resIfollow=$profileClass->doIFollow($resProfile[0]["id_profile"]);
			?>
			<div style="margin:20px 0;" class="center">
				<?php
				if($resIfollow["result"]){
				?>
				<button class="btn btn-info" id="followBtn" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?no&id=".$resProfile[0]["id_profile"]; ?>'">Following</button>
				<?php	
				
				$onload.="$('#followBtn').hover(function(){
					$(this).text('unfollow');	
				},function(){
					$(this).text('following');
				});";
				
				}else{
				?>
				<button class="btn btn-primary" onclick="location.href='<?php echo WEB_URL."act/profile/follow.php?yes&id=".$resProfile[0]["id_profile"]; ?>'">Follow</button>
				<?php
				}
				?>
			</div>
			<?php	
			}
			?>
			<ul class="profileData">
			<?php
			if($resProfile[0]["type_profile"]=="1"){
				
			?>
			
				<?php
				if($resProfile[0]["gender_profile"]=="m" || $resProfile[0]["gender_profile"]=="f"){
					if($resProfile[0]["gender_profile"]=="m"){
						$gender="Male";
					}else{
						$gender="Female";
					}
					?>
					<li>Gender: <?php echo $gender; ?></li>
					<?php
				}
				?>
				<li>Overall Feeling: <?php echo $resProfile[0]["ifeel_profile"]; ?></li>
				<?php
				/*
				<li>Ocupation:</li>
				<li>Height:</li>
				<li>Weight:</li>
				<li>Blood Pressure:</li>
				*/
				
				
			}
			
			echo "<hr />";
			
			$res=$profileClass->countFollowers($resProfile[0]["id_profile"]);
			$totalFers=0;
			if($res["result"]){
				$totalFers=$res[0]["total"];
			}
			echo "<li>Followers: $totalFers</li>";
			$res=$profileClass->countFollowing($resProfile[0]["id_profile"]);
			$totalFings=0;
			if($res["result"]){
				$totalFings=$res[0]["total"];
			}
			echo "<li>Following: $totalFings</li>";
			
			echo "<hr />";
			
			$res=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"d");
			$total=0;
			if($res["result"]){
				$total=$res[0]["total"];
			}
			echo "<li>Conditions: $total</li>";
			
			$res=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"m");
			$total=0;
			if($res["result"]){
				$total=$res[0]["total"];
			}
			echo "<li>Medications: $total</li>";
			
			$res=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"p");
			$total=0;
			if($res["result"]){
				$total=$res[0]["total"];
			}
			echo "<li>Procedures: $total</li>";
			
			$res=$topicClass->countUserFollowingTopic($resProfile[0]["id_profile"],"s");
			$total=0;
			if($res["result"]){
				$total=$res[0]["total"];
			}
			echo "<li>Symptoms: $total</li>";

			?>
			</ul>
		</div>
	</div>
	<div id="pageMainBig">
		<?php
		//timeline only exists for profiles type 1
		if($resProfile[0]["type_profile"]==1){
		
			//resumo da timeline
			$res = $timelineClass->getUserCurrentByType($resProfile[0]["id_profile"],"dis",1);
			?>
			<div class="iRounded iBoardWithHeader clearfix marginbottom30">
				<h3 class="iBoardHeader">Diagnosis</h3>
				<?php 
				if($res["result"]){
					$string="";
					foreach($res as $key=>$value){
						if(is_int($key)){
							$string.=$value["name_topic"].", ";
						}
					}
					echo rtrim($string,", ");
				}else{
					echo "none";
				}
				?>
			</div>
			<?php
			$res = $timelineClass->getUserCurrentByType($resProfile[0]["id_profile"],"med",1);
			?>
			<div class="iRounded iBoardWithHeader clearfix marginbottom30">
				<h3 class="iBoardHeader">Medications</h3>
				<?php 
				if($res["result"]){
					$string="";
					foreach($res as $key=>$value){
						if(is_int($key)){
							$string.=$value["name_topic"].", ";
						}
					}
					echo rtrim($string,", ");
				}else{
					echo "none";
				}
				?>
			</div>
			<?php
			$res = $timelineClass->getUserCurrentByType($resProfile[0]["id_profile"],"sym",1);
			?>
			<div class="iRounded iBoardWithHeader clearfix marginbottom30">
				<h3 class="iBoardHeader">Symptoms</h3>
				<?php 
				if($res["result"]){
					foreach($res as $key=>$value){
						if(is_int($key)){
							$resSym = $timelineClass->getTopicsByTimelineId($value["id_tm"]);
							if($resSym["result"]){
								$string="";
								foreach($resSym as $keys=>$values){
									$string.=$values["name_topic"].", ";
								}
								echo rtrim($string,", ");
							}
						}
					}

				}else{
					echo "none";
				}
				?>
			</div>
			<?php
			$res = $timelineClass->getUserCurrentByType($resProfile[0]["id_profile"],"pro");
			?>
			<div class="iRounded iBoardWithHeader clearfix marginbottom30">
				<h3 class="iBoardHeader">Procedures</h3>
				<?php 
				if($res["result"]){
					$string="";
					foreach($res as $key=>$value){
						if(is_int($key)){
							$string.=$value["name_topic"].", ";
						}
					}
					echo rtrim($string,", ");
				}else{
					echo "none";
				}
				?>
			</div>
			<?php
		}
		?>
		<div class="iRounded iBoardWithHeader clearfix">
			<h3 class="iBoardHeader">Latest Posts</h3>
			<?php
			
			$res=$postClass->getPostsFromUser($resProfile[0]["id_profile"]);
			
			if($res["result"]){
				foreach($res as $key=>$value){
					if(is_int($key)){
						$resTop=$postClass->getPostTopics($value["id_post"]);
						$time=strtotime($value["date_post"]);
					?>
						<div>
						<p><?php 
						if($value["title_post"]!=""){ 
							echo "<b>";
							if($value["link_post"]!=""){
								echo "<a href=\"".$value["link_post"]."\" target=\"_blank\">";
							}
							echo $value["title_post"];
							if($value["link_post"]!=""){
								echo "</a>";
							}
							echo "</b><br />";
						}else if($value["link_post"]!=""){
							echo "<a href=\"".$value["link_post"]."\" target=\"_blank\">".$value["link_post"]."</a>";
						} 
						echo $value["text_post"]; ?></p>
						<?php
						if($resTop["result"]){
							foreach($resTop as $chave=>$valor){
								if(is_int($chave)){
									echo "<a href=\"".WEB_URL.$topicClass->pathSingular($valor["type_topic"])."/".$valor["url_topic"]."\">".$valor["name_topic"]."</a> ";
								}
							}
						}
						?>
						<div class="textAlignRight"><?php echo $configClass->ago($time); ?></div>
						</div>
					<?php
					}
				}
			}else{
				echo "<div>No post by ".$resProfile[0]["username_profile"].", yet</div>"; 
			}
			?>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');