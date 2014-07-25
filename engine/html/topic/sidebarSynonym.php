<?php
if(isset($lookForSynonyms)){
	$resSyn=$topicClass->getTopicSynonyms($resTopic[0]["id_topic"]);
	if($resSyn["result"]){
	?>
	<div class="iBoard3">
		<div class="iDashboardHolder">
			<h3 class="iDashboardHeading">Synonyms</h3>
			<div class="iDashboardContent">
				<ul style="padding-left:15px;">
				<?php
				foreach($resSyn as $key=>$value){
					if(is_int($key)){
						echo "<li>".$value["name_ts"];
						if(USER_TYPE==9){
							echo " - <a href=\"#\" onclick=\"delSyn(".$value["id_ts"].");\" style=\"color:red\">delete</a>";
						}
						echo "</li>";
					}
				}
				?>
				</ul>
			</div>
		</div>
	</div>
	<?php
	}
	if(USER_TYPE==9){
	$jsfunctions.="
	function delSyn(id){
		if(confirm('Are you sure you want to delete this synonym?')){
			location.href='".WEB_URL."pr1v/act/topic/delSyn.php?id='+id;
		}
	}
	";
	?>
	<div class="iBoard3">
		<div class="iDashboardHolder">
			<h3 class="iDashboardHeading">Add Synonyms</h3>
			<div class="iDashboardContent">
				<form method="post" class="margin0" action="<?php echo WEB_URL; ?>pr1v/act/topic/addSyn.php?id=<?php echo $resTopic[0]["id_topic"]; ?>">
					<input type="text" class="input100" name="syn" placeholder="synonym" />
					<input type="submit" class="btn btn-blue" style="width:100%;" />
				</form>
			</div>
		</div>
	</div>
	<div class="iBoard3">
		<div class="iDashboardHolder">
			<h3 class="iDashboardHeading">Delete <?php echo $resTopic[0]["name_topic"]; ?></h3>
			<div class="iDashboardContent center">
				<button onclick="delTopic();" class="btn btn-red">delete</button>
			</div>
		</div>
	</div>
	<?php
	$jsfunctions.="
	function delTopic(){
		if(confirm('Are you sure you want to delete ".$resTopic[0]["name_topic"]."?')){
			location.href='".WEB_URL."pr1v/act/topic/del.php?id=".$resTopic[0]["id_topic"]."';
		}
	}
	";
	}
}
?>