<?php

require_once('../engine/starter/config.php');

require_once(ENGINE_PATH.'class/doctor.class.php');
$doctorClass=new Doctor();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

require_once(ENGINE_PATH.'class/config.class.php');
$configClass=new Config();

if(!isset($_GET["lastname"]) || !isset($_GET["firstname"]) || !isset($_GET["miles"]) || !isset($_GET["ccode"]) || !isset($_GET["spec"])){
	go404();
}

$designV1=1;

$lname=urldecode($_GET["lastname"]);
$fname=urldecode($_GET["firstname"]);
$miles=$_GET["miles"];
$zip=$_GET["ccode"];
$spec=urldecode($_GET["spec"]);

$resDocs=$doctorClass->search(1,$lname,$fname,$miles,$zip,$spec);


	$headingTitle="Doctor results";
	$pageTitle="Doctor results- HealthKeep";
	$pageDescr="A simple way to search and find a medical doctor. HealthKeep has a full list of doctors in the United States.";


$active="homepage";
require_once(ENGINE_PATH.'render/base/header.php');
//require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix" style="background-color:#fff;padding:20px;">
	<h2><a href="<?php echo WEB_URL; ?>doctors" style="float:right;">search again</a></h2>
		<h2 class="" style="padding:30px;color:#678DCC;font-size:30px;"><?php echo $headingTitle; ?></h2>
		<div id="inDoctorList">
			<?php

			if($resDocs["result"]==0){
				echo "<div style=\"padding:10%;\"><h2>No results for that search</h2>";
				echo "<h2>Please, <a href=\"".WEB_URL."doctors\" style=\"color:#5F91CC\">Try again</a></h2></div>";
			}else{
				require_once(ENGINE_PATH."render/others/doctors_list_html.php");
			}
			$ajaxUrl=WEB_URL."act/ajax/search_doctors.php";
			$onload.="endlessScroll('$ajaxUrl',$('#inDoctorList'),'$lname','$fname','$miles','$zip','$spec');";
			require_once(ENGINE_PATH."html/inc/endless.php");
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');