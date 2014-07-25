<?php
if($resTimeline["result"]){

	require_once(ENGINE_PATH.'html/inc/common/typeArray.php');
	
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
				$topicColor="timelineColorBlue";
			}else if($value["type_tm"]=="sym"){
				$class="iBoxHeading_pink";
				$icon="temperature.png";
				$topicColor="timelineColorPink";
			}else if($value["type_tm"]=="dis"){
				$class="iBoxHeading_lightBlue";
				$icon="heart.png";
				$topicColor="timelineColorLightBlue";
			}else if($value["type_tm"]=="pro"){
				$class="iBoxHeading_brown";
				$icon="inbed.png";
				$topicColor="timelineColorBrown";
			}else if($value["type_tm"]=="res"){
				$class="iBoxHeading_green";
				$icon="line_green.png";
				$topicColor="timelineColorGreen";
			}else if($value["type_tm"]=="fel"){
				$class="iBoxHeading_gray";
				$icon="line_gray.png";
				$topicColor="timelineColorGray";
			}else if($value["type_tm"]=="doc"){
				$class="iBoxHeading_red";
				$icon="line_red.png";
				$topicColor="timelineColorRed";
			}else if($value["type_tm"]=="mea"){
				$class="iBoxHeading_purple";
				$icon="line_purple.png";
				$topicColor="timelineColorPurple";
			}
			
			if ($key % 2 == 0){
				$classPos="timelineItemEven";
			}else{
				$classPos="timelineItemOdd";
			}
			?>
			<div class="timelineItem <?php echo $classPos; ?> clearfix">
				<div class="iBoxHeadingColoured clearfix">
					<div class="iBoxHeadingColouredHeading <?php echo $class; ?> clearfix">
						<?php
						if(USER_ID==$resProfile[0]["id_profile"]){
						?>
						<span class="timelineButtons">
							<span class="btn-group">
								<a href="#" class="iMTimelineHeadingBtns iMTimelineHeadingOptions dropdown-toggle" data-toggle="dropdown">&nbsp;</a>
								<ul class="dropdown-menu oddEvenPull">
									<li><a href="#" onclick="return confirmDel('<?php echo $value["id_tm"]; ?>');">Delete</a></li>
								</ul>
							</span>
							<?php
							if($value["currently_tm"]==1){
							?>
							<span class="btn-group">
								<a href="#" class="iMTimelineHeadingBtns iMTimelineHeadingActive dropdown-toggle" data-toggle="dropdown">&nbsp;</a>
								<ul class="dropdown-menu oddEvenPull">
									<li><a href="<?php echo WEB_URL."act/timeline/notCurrently.php?id=".$value["id_tm"]; ?>">No longer active</a></li>
								</ul>
							</span>
							<?php
							}
							if($value["type_tm"]=="mea" && $value["measurement_tm"]!=""){
							?>
							<span class="btn-group">
								<a href="<?php echo WEB_URL."graphs/".$value["measurement_tm"]; ?>" class="iMTimelineHeadingBtns iMTimelineHeadingGraph">&nbsp;</a>
							</span>
							<?php
							}
							if($value["type_tm"]=="res"){
							?>
							<span class="btn-group">
								<a href="#" class="iMTimelineHeadingBtns iMTimelineHeadingClip dropdown-toggle"toggle="dropdown">&nbsp;</a>
								<ul class="dropdown-menu oddEvenPull">
									<li><a href="<?php echo WEB_URL."timeline/file/".$value["id_tm"]; ?>">Download Test Result</a></li>
								</ul>
							</span>
							<?php
							}
							?>
						</span>
						<?php
						
						}
						?>
						<div class="timelineEventDate clearfix">
							<div class="timelineEventDateDay"><?php echo date('j',strtotime($value["date_tm"])); ?></div>
							<div class="timelineEventDateHolder">
								<div class="timelineEventDateDayOfWeek"><?php echo date('l',strtotime($value["date_tm"])); ?></div>
								<div class="timelineEventDateMonthYear"><?php echo date('F Y',strtotime($value["date_tm"])); ?></div>
							</div>
						</div>
					</div>
				</div>
				<div class="timelinePointer"></div>
				<?php
				$typeArray=typeArray($value["type_tm"]);
				?>
				<div class="timelineMark holdTooltip" data-toggle="tooltip" title="<?php echo $typeArray["singular"]; ?>" style="background-image:url(<?php echo WEB_URL."inc/img/v1/topic/".$icon; ?>)"></div>
				<?php
				$mainContent="";
				$detailsContent="";
				if($value["type_tm"]=="med"){
				$mainContent.="<a href=\"".WEB_URL.$topicClass->pathSingular($value["type_topic"])."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
				if($value["frequency_tm"]!=1 || $value["unit_tm"]!=0 || $value["real_freq_tm"]!=0){
				$detailsContent.="Dose: ".$value["frequency_tm"];
				}
				if($value["unit_tm"]!=0){
					if($value["unit_tm"]==1){
						$detailsContent.= " Microgram";
					}else if($value["unit_tm"]==2){
						$detailsContent.= " Miligram";
					}else if($value["unit_tm"]==3){
						$detailsContent.= " Gram";
					}
				}
				if($value["real_freq_tm"]!=0){
					if($value["real_freq_tm"]==1){
						$detailsContent.= " once a day";
					}else if($value["real_freq_tm"]==2){
						$detailsContent.= " twice a day";
					}else if($value["real_freq_tm"]==3){
						$detailsContent.= " three times a day";
					}
				}
			}else if($value["type_tm"]=="sym"){
				$resTT=$timelineClass->getTopicsByTimelineId($value["id_tm"]);
				if($resTT["result"]){

					foreach($resTT as $keyTT=>$valueTT){
						if(is_int($keyTT)){
							if($mainContent!=""){
								$mainContent.=" - ";
							}
							$mainContent.= "<a href=\"".WEB_URL.$topicClass->pathSingular($valueTT["type_topic"])."/".$valueTT["url_topic"]."\">".$valueTT["name_topic"]."</a>";
						}
					}
				}
			}else if($value["type_tm"]=="dis"){
				$mainContent.="<a href=\"".WEB_URL.$topicClass->pathSingular($value["type_topic"])."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";

			}else if($value["type_tm"]=="pro"){
				$mainContent.="<a href=\"".WEB_URL.$topicClass->pathSingular($value["type_topic"])."/".$value["url_topic"]."\">".$value["name_topic"]."</a>";
			}else if($value["type_tm"]=="res"){
				$mainContent.= $value["name_tm"];
			}else if($value["type_tm"]=="fel"){
				$mainContent.= "Overall ".(int)$value["frequency_tm"];
			}else if($value["type_tm"]=="doc"){
				$resPro=$profileClass->getById($value["doc_profile_tm"]);
				if($resPro["result"]){
					$mainContent.="<a href=\"".WEB_URL.$resPro[0]["username_profile"]."\">".$resPro[0]["name_profile"]."</a>";
				}
			}else if($value["type_tm"]=="mea"){
				if($value["measurement_tm"]=="weight"){
				$mainContent.= "Weight: ".$value["frequency_tm"];
				}else if($value["measurement_tm"]=="diet"){
				$mainContent.= "Diet: ".(int)$value["frequency_tm"]." calories";
				}else if($value["measurement_tm"]=="bp"){
				$mainContent.= "Blood Pressure: ".(int)$value["frequency_tm"]."/".(int)$value["numeric_tm"];
				}else if($value["measurement_tm"]=="sugar"){
				$mainContent.= "Blood Sugar: ".(int)$value["frequency_tm"];
				}else if($value["measurement_tm"]=="exercise"){
				$mainContent.= "Exercise: ".(int)$value["frequency_tm"]." minutes";
				}
			}
				?>
				<div class="timelineItemContent">
					<div class="timelineItemContentMain <?php echo $topicColor; ?>">
					<?php echo $mainContent; ?>
					</div>
					<div class="timelineItemContentDetails" <?php if($detailsContent==""){ echo 'style="display:none;"';} ?>>
					<?php echo $detailsContent; ?>
					</div>
				</div>
			</div>	
			<?php
		}
	}

}
