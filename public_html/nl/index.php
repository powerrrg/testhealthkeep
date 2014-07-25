<?php
require_once('../../engine/starter/config.php');


$designV1=1;

$pageTitle="HealthKeep Newsletter Archive";
$pageDescr="A list of all the newsletters sent by HealthKeep";
require_once(ENGINE_PATH.'html/header.php');

require_once(ENGINE_PATH.'html/top.php');
?>
<div id="main">
	<div class="iHold clearfix">
	<div class="iBoard">
		<div class="iHeading iFull margin10auto padding15" style="max-width:300px;">
			<h1 class="colorRed margin0">Newsletter Archive</h1>
		</div>
	<div class="iFull iBoard2 margin20auto" style="max-width:300px;">
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-37217243-3', 'healthkeep.com');
		  ga('send', 'pageview');
		
		</script>
		<style type="text/css">
		<!--
		.display_archive {font-family: arial,verdana; font-size: 12px;}
		.campaign {line-height: 125%; margin: 5px;}
		//-->
		</style>
		<script language="javascript" src="http://us4.campaign-archive1.com/generate-js/?u=ca7e6532cb6cfeacd74fb2a3f&fid=7469&show=10" type="text/javascript"></script>
	</div>
	</div>
</div>
</div>
<?php
require_once(ENGINE_PATH.'html/footer.php');