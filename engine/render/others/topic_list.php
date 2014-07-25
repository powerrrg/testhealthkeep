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

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull padding40">
			<h2 class="iFullHeading"><?php echo $heading; ?></h2>
			<?php
			$topicPluralName=$topicClass->namePlural($topicType);
			$folder=$topicClass->pathSingular($topicType);
			
			function linkToLetter($letter,$topicPluralName){
				echo '<div class="textAlignRight"><a href="'.WEB_URL.$_GET["l1"].'/'.strtolower($letter).'">'.$topicPluralName.' starting with '.strtoupper($letter).' =></a></div>';
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
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');