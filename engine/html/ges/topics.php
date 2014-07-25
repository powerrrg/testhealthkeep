<?php

onlyLogged();

if(USER_TYPE!=9){
	go404();	
}

$pageTitle="Back Office";
$pageDescr="Back Office";

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

$designV1=1;
$active="backoffice";
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10 center">Topics</h1>
			</div>
			<div class="iFull iBoard2 margin20auto">
				<?php
				if(isset($_GET["l3"])){
					?>
					<p style="text-align:right"><a href="<?php echo WEB_URL; ?>ges/topics">Back to topics</a></p>
					<?php
					if($_GET["l2"]=='topic'){
						$id=(int)$_GET["l3"];
						$resTopic=$topicClass->getById($id);
						if($resTopic["result"]){
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
								if(confirm('Are you sure you want to delete ".htmlentities($resTopic[0]["name_topic"],ENT_QUOTES)."?')){
									location.href='".WEB_URL."pr1v/act/topic/del.php?id=".$resTopic[0]["id_topic"]."';
								}
							}
							";
							}
						}else{
							echo "Odd that topic was not found!";
						}
					}else{
					
						$search=rawurldecode($_GET["l3"]);
						$res=$topicClass->getAutoCompleteAll($search,50);
						if($res["result"]){
							foreach($res as $key=>$value){
								if(is_int($key)){
									echo '<li><a href="'.WEB_URL.'ges/topic/'.$value["id_topic"].'">'.$value["name_topic"].'</a> - '.$topicClass->namePlural($value["type_topic"]).'</li>';
								}
							}
						}else{
						?>
							<p>No topics with the name '<?php echo $search; ?>' found!</p>
						<?php
						}
					}
				}else{
				?>

					
					<p><input type="text" placeholder="Search Topic" id="topicSearch" /></p>
					<?php
					$onload.="
					$('#topicSearch').keypress(function(e) {
					    if(e.which == 13) {
					        location.href='".WEB_URL."ges/topics/'+$(this).val();
					    }
					});
					";
					?>
					<hr />
					<p>Add an topic</p>
					<form method="post" action="<?php echo WEB_URL; ?>pr1v/act/topic/addNew.php">
						<input type="text" name="newName" placeholder="name" /><br />
						<select name="type">
							<option value="d">Condition</option>
							<option value="m">Medication</option>
							<option value="p">Procedure</option>
							<option value="s">Symptoms</option>
						</select><br />
						<input type="submit" class="btn btn-red" />
					</form>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');