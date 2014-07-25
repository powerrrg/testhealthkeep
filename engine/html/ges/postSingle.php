<?php
foreach($resPosts as $postKey=>$postValue){
if(is_int($postKey)){
	$displayName=$configClass->name($postValue, false);
	$imgAltText="";
	if($postValue["image_profile"]==""){
		if($postValue["type_profile"]==4){
			$img=WEB_URL."inc/img/news-avatar.png";
			$imgAltText="News Source Avatar";
		}else{
			$img=WEB_URL."inc/img/empty-avatar.png";
			$imgAltText="No Avatar";
		}
	}else{
		$img=WEB_URL."img/profile/tb/".$postValue["image_profile"];
		$imgAltText=$displayName;
	}
	$time=strtotime($postValue["date_post"]);
	$postLove=$postValue["social_rank_post"]+$postValue["thumb_up_post"];
	if($postLove>9999){
		$postLove = substr($postLove, 0,2)."K";
	} 
	?>
	<div id="iMPost_<?php echo $postValue["id_post"]; ?>">
		<h4><a href="<?php echo WEB_URL.$postValue["username_profile"]; ?>"><?php echo $displayName; ?></a> - <?php echo $configClass->ago($time); ?> - <span style="cursor:pointer;color:red;font-weight:normal;" onclick="deletePost('<?php echo $postValue["id_post"]; ?>')">Delete Post</span></h4>
		<p>
		<?php 
		if($postValue["image_post"]!=''){
			echo '<img src="'.WEB_URL.'img/post/med/'.$postValue["image_post"].'" style="float:right;max-width:300px;" />';
		}
		if($postValue["title_post"]!=''){
			echo '<b>'.$postValue["title_post"].'</b><br />';
		}
		echo $postValue["text_post"]; 
		?>
		</p>
		<?php
			$resPostTopics=$postClass->getPostTopics($postValue["id_post"]);
			if($resPostTopics["result"]){
				foreach($resPostTopics as $paKey=>$paValue){
					if(is_int($paKey)){
						echo '<div id="postTopic_'.$paValue["id_topic"].'" class="iMPostInfoTopic"><a href="'.WEB_URL.$topicClass->pathSingular($paValue["type_topic"])."/".$paValue["url_topic"].'">';
						echo $paValue["name_topic"].'</a> - <a href="#" onclick="return removeTopic('.$paValue["id_topic"].','.$postValue["id_post"].')" style="color:red;">X</a></div>';
					}
				}
			}
?>
		<div>
			<p id="addTopics_<?php echo $postValue["id_post"]; ?>" style="display:none;"><input type="text" id="addTopicsInput_<?php echo $postValue["id_post"]; ?>" /></p>
			<span style="cursor:pointer;color:#ccc;" onclick="addTopics('<?php echo $postValue["id_post"]; ?>')">Add Topics</span>
		</div>
	</div>
	<?php
	}
}
?>