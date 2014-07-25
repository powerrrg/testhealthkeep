<?php
if(!isset($_GET["l2"]) || strlen($_GET["l2"])<2){
	header ('HTTP/1.1 301 Moved Permanently');
	header("Location:".WEB_URL."feed");
	exit;
}

$q=urldecode($_GET["l2"]);

require_once(ENGINE_PATH.'class/search.class.php');
$searchClass=new Search();

if(isset($_GET["l3"])){
	$ofilter=$_GET["l3"];
	if($ofilter!='topic' && $ofilter!='user' && $ofilter!='post' && $ofilter!='comment'){
		go404();
	}
	
	$pageTitle="Results for ".$ofilter."s with the word ".$q." - HealthKeep";
	$pageDescr="Search results for ".$ofilter."s with the word ".$q." from all of HealthKeep's content.";
	
}else{
	$pageTitle="Results for ".$q." - HealthKeep";
	$pageDescr="Search results for ".$q." from all of HealthKeep's content.";
}

require_once(ENGINE_PATH.'render/base/header.php');
require_once(ENGINE_PATH.'render/base/top.php');
?>
<article id="main">
	<hgroup class="iWrap clearfix">
		<div class="iFull iText">
			<h2 class="iFullHeading center">Search Results for '<?php echo $q; ?>'</h2>
			<?php
			if(isset($_GET["l3"])){
				$resSearch = $searchClass->search($q,1,$_GET["l3"]);
			}else{
				$resSearch = $searchClass->search($q);
			}
			?>
			<div id="postHolder" class="clearfix">
				<?php require_once(ENGINE_PATH."html/list/search_list.php"); ?>
			</div>
			<?php
			$ajaxUrl=WEB_URL."act/ajax/feed/search.php";
			if(isset($_GET["l3"])){
			$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'$q','".$_GET["l3"]."');";
			}else{
			$onload.="endlessScroll('$ajaxUrl',$('#postHolder'),'$q');";
			}
			require_once(ENGINE_PATH."html/inc/endless.php");
			?>
		</div>
	</hgroup>
</article>
<?php
require_once(ENGINE_PATH.'render/base/footer.php');