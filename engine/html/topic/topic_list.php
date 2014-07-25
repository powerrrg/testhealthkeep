<?php

if($topicType=="d"){
	$heading="Diseases and Conditions";
	$pageTitle="Diseases and medical conditions - HealthKeep";
	$pageDescr="Full list of diseases and medical conditions.";
}else if($topicType=="m"){
	$heading="Medications";
	$pageTitle="Medications - HealthKeep";
	$pageDescr="Full list of medication and medical drugs.";
}else if($topicType=="p"){
	$heading="Surgeries and Procedures";
	$pageTitle="Surgeries and medical procedures - HealthKeep";
	$pageDescr="Full list of surgeries and medical procedures.";
}else if($topicType=="s"){
	$heading="Symptoms";
	$pageTitle="Medical symptoms - HealthKeep";
	$pageDescr="Full list of medical symptoms.";
}else{
	go404();
}
require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

$active=$topicClass->pathPlural($topicType);
$designV1=1;
require_once(ENGINE_PATH.'html/header.php');
require_once(ENGINE_PATH.'html/top.php');

?>
<div id="main">
	<div class="iHold clearfix">
		<div class="iBoard">
			<div class="iHeading iFull margin10auto padding15">
				<h1 class="colorRed margin10"><?php echo $heading; ?></h1>
			</div>
			<?php
			if(USER_TYPE==9){
			?>
			<div class="iFull iBoard2 margin10auto">
				<p>Add an item to <?php echo $heading; ?></p>
				<form method="post" action="<?php echo WEB_URL; ?>pr1v/act/topic/addNew.php?type=<?php echo $topicType; ?>">
					<input type="text" name="newName" placeholder="name" /><br />
					<input type="submit" class="btn btn-red" />
				</form>
			</div>
			<?php
			}
			?>
			<div class="iFull iBoard2 margin20auto">
			
				<?php
				$topicPluralName=$topicClass->namePlural($topicType);
				$folder=$topicClass->pathSingular($topicType);
				
				function linkToLetter($letter,$topicPluralName){
					echo '<div class="textAlignRight marginRight20"><a href="'.WEB_URL.$_GET["l1"].'/'.strtolower($letter).'">'.$topicPluralName.' starting with '.strtoupper($letter).' =></a></div>';
				}
				
				$res = $topicClass->getAllFromTopic($topicType);
				$letter="";
				$i=1;
				$phparray=array();
				foreach($res as $key=>$value){
					if(is_int($key)){
						$fletter=strtolower(substr($value["name_topic"], 0,1));
						if(is_numeric($fletter)){
							$fletter="Numeric";
						}
						if($letter!=$fletter){
							$i=1;
							$onload.="$('#alphab_".$fletter."').addClass('active');";
							if($letter!=""){
								echo '</ul>';
								linkToLetter($letter,$topicPluralName);
							}
							
							echo '<h3 id="letter_'.$fletter.'" class="iLongListH3">'.strtoupper($fletter).'</h3>';
							
							$letter=$fletter;
							echo '<ul class="iLongList">';
						}
						if($i<6){
							$phparray[]=array("id"=>$value["id_topic"],"name"=>$value["name_topic"],"url"=>$value["url_topic"]);
							echo '<li><a href="'.WEB_URL.$folder.'/'.$value["url_topic"].'">'.$value["name_topic"].' </a></li>';
						}
						$i++;
					}	
				}
				echo "</ul>";
				linkToLetter($letter,$topicPluralName);
				?>
			</div>
		</div>
	</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');