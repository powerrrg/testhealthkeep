<?php
if(strlen($_GET["l2"])>1 && $_GET["l2"]!="numeric"){
	go404();
}else if(!preg_match('/[a-z]/', $_GET["l2"])){
	go404();
}

if($_GET["l2"]=="numeric"){
	$whatLetter="Number";
}else{
	$whatLetter=strtoupper($_GET["l2"]);
}

if($topicType=="d"){
	$heading="Diseases and Conditions";
	$pageTitle="Diseases and medical conditions";
	$pageDescr="Full list of diseases and medical conditions";
}else if($topicType=="m"){
	$heading="Medications";
	$pageTitle="Medications";
	$pageDescr="Full list of medication and medical drugs";
}else if($topicType=="p"){
	$heading="Surgeries and Procedures";
	$pageTitle="Surgeries and medical procedures";
	$pageDescr="Full list of surgeries and medical procedures";
}else if($topicType=="s"){
	$heading="Symptoms";
	$pageTitle="Medical symptoms";
	$pageDescr="Full list of medical symptoms";
}else{
	go404();
}

$heading=$heading." starting with ".$whatLetter;
$pageTitle=$pageTitle." starting with ".$whatLetter." - HealthKeep";
if($_GET["l2"]=="numeric"){
	$pageDescr=$pageDescr." starting with a number.";
}else{
	$pageDescr=$pageDescr." starting with the letter ".$whatLetter.".";
}

require_once(ENGINE_PATH.'class/topic.class.php');
$topicClass=new Topic();

$res = $topicClass->getAllFromTopicStartingWith($topicType,strtolower($_GET["l2"]));

$active=$topicClass->pathPlural($topicType);

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull padding40">
			<h2 class="iFullHeading"><?php echo $heading; ?></h2>
			<?php
			$folder=$topicClass->pathSingular($topicType);
			
			
			$letter="";
			$phparray=array();
			foreach($res as $key=>$value){
				if(is_int($key)){
					$fletter=strtolower(substr($value["name_topic"], 0,1));
					if(is_numeric($fletter)){
						$fletter="Numeric";
					}
					if($letter!=$fletter){
						$onload.="$('#alphab_".$fletter."').addClass('active');";
						if($letter!=""){
							echo '</ul>';
						}
						
						echo '<h3 id="letter_'.$fletter.'" class="iLongListH3">'.strtoupper($fletter).'</h3>';
						
						$letter=$fletter;
						echo '<ul class="iLongList">';
					}

					$phparray[]=array("id"=>$value["id_topic"],"name"=>$value["name_topic"],"url"=>$value["url_topic"]);
					echo '<li><a href="'.WEB_URL.$folder.'/'.$value["url_topic"].'">'.$value["name_topic"].'</a>';
					if($value["name_ts"]!=""){
						echo "<div class=\"iLongListSyn\">(".$value["synonyms"].")</div>";
					}
					echo '</li>';

				}	
			}
			echo "</ul>";
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');