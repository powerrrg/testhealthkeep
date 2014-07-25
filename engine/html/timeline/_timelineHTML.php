<?php
if($resTimeline["result"]){
	if(USER_ID==$resProfile[0]["id_profile"]){
		$jsfunctions.="
			function confirmDel(id){
				if(confirm('Are you sure?')){
					location.href='".WEB_URL."act/timeline/delete.php?id='+id;
				}
			}
		";
	}
	foreach($resTimeline as $key=>$value){
		if(is_int($key)){
		
			if($value["type_tm"]=="med"){
				$class="iBoxHeading_blue";
				$icon="pill.png";
			}else if($value["type_tm"]=="sym"){
				$class="iBoxHeading_pink";
				$icon="temperature.png";
			}else if($value["type_tm"]=="dis"){
				$class="iBoxHeading_lightBlue";
				$icon="heart.png";
			}else if($value["type_tm"]=="pro"){
				$class="iBoxHeading_brown";
				$icon="inbed.png";
			}else if($value["type_tm"]=="res"){
				$class="iBoxHeading_green";
				$icon="line.png";
			}else if($value["type_tm"]=="fel"){
				$class="iBoxHeading_gray";
				$icon="line.png";
			}else if($value["type_tm"]=="doc"){
				$class="iBoxHeading_red";
				$icon="line.png";
			}
			?>
			<div id="iMtimeline_<?php echo $value["id_tm"]; ?>" class="holdTimelineEvent clearfix">
			<div class="timelineDetails">
				<div class="timelineEventIcon <?php echo $class; ?>"><img src="<?php echo WEB_URL; ?>inc/img/v1/topic/white/<?php echo $icon; ?>" /></div>
				<div class="timelineEventDate">
					<span class="timelineEventDateMonth"><?php echo date('M',strtotime($value["date_tm"])); ?></span>
					<span class="timelineEventDateDay"><?php echo date('j',strtotime($value["date_tm"])); ?></span>
					<span class="timelineEventDateYear"><?php echo date('Y',strtotime($value["date_tm"])); ?></span>
				</div>
			</div>
			<div class="iBoxHeadingColoured timelineContent">
				<div class="iBoxHeadingColouredHeading <?php echo $class; ?> clearfix">
			<?php
			if(USER_ID==$resProfile[0]["id_profile"]){
			?>
			<span class="iMTimelineHeadingRight">
			<span class="btn-group">
				<a href="#" class="iMTimelineHeadingBtns iMTimelineHeadingOptions dropdown-toggle" data-toggle="dropdown">&nbsp;</a>
				<ul class="dropdown-menu pull-right">
					<?php
					if($value["currently_tm"]==1){
					?>

					<li><a href="#" onclick="location.href='<?php echo WEB_URL."act/timeline/notCurrently.php?id=".$value["id_tm"]; ?>'">No longer active</a></li>
					<?php
					}
					?>
					<li><a href="#" onclick="return confirmDel('<?php echo $value["id_tm"]; ?>');">Delete</a></li>
				</ul>
			</span>
			</span>
			<?php
			
			}
					echo "<h3 class=\"iTimelineHeadH3\">".$timelineType[$value["type_tm"]]."</h3>";
			echo "</div>";
			echo "<div class=\"iTimelineHoldText\">";
			
			if($value["type_tm"]=="med"){
				echo "<a href=\"".WEB_URL.$topicClass->pathSingular($value["type_topic"])."/".$value["url_topic"]."\">".$value["name_topic"]."</a><br />";
				if($value["currently_tm"]==1){
					echo "Still Active<br />";
				}else{
					echo "No Longer Active<br />";
				}
				echo "Dose: ".$value["frequency_tm"];
				if($value["unit_tm"]!=0){
					if($value["unit_tm"]==1){
						echo " Microgram";
					}else if($value["unit_tm"]==2){
						echo " Miligram";
					}else if($value["unit_tm"]==3){
						echo " Gram";
					}
				}
				if($value["real_freq_tm"]!=0){
					if($value["real_freq_tm"]==1){
						echo " once a day";
					}else if($value["real_freq_tm"]==2){
						echo " twice a day";
					}else if($value["real_freq_tm"]==3){
						echo " three times a day";
					}
				}
			}else if($value["type_tm"]=="sym"){
				$resTT=$timelineClass->getTopicsByTimelineId($value["id_tm"]);
				if($resTT["result"]){

					foreach($resTT as $keyTT=>$valueTT){
						if(is_int($keyTT)){
							echo "<a href=\"".WEB_URL.$topicClass->pathSingular($valueTT["type_topic"])."/".$valueTT["url_topic"]."\">".$valueTT["name_topic"]."</a><br />";
						}
					}

					if($value["currently_tm"]==1){
						echo "Still Active<br />";
					}else{
						echo "No Longer Active<br />";
					}
				}
			}else if($value["type_tm"]=="dis"){
				echo "<a href=\"".WEB_URL.$topicClass->pathSingular($value["type_topic"])."/".$value["url_topic"]."\">".$value["name_topic"]."</a><br />";
				if($value["currently_tm"]==1){
					echo "Still Active<br />";
				}else{
					echo "No Longer Active<br />";
				}
			}else if($value["type_tm"]=="pro"){
				echo "<a href=\"".WEB_URL.$topicClass->pathSingular($value["type_topic"])."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
			}else if($value["type_tm"]=="res"){
			
				echo "<a href=\"".WEB_URL."timeline/file/".$value["id_tm"]."\">";
				echo $value["name_tm"]."</a>";
				echo "</span>";
			}else if($value["type_tm"]=="fel"){
				echo "Overall ".(int)$value["frequency_tm"];
			}else if($value["type_tm"]=="doc"){
				$resPro=$profileClass->getById($value["doc_profile_tm"]);
				if($resPro["result"]){
					echo "<a href=\"".WEB_URL.$resPro[0]["username_profile"]."\">".$resPro[0]["name_profile"]."</a>";
				}
			}
			
			echo "</div></div></div>";
		}
	}
}