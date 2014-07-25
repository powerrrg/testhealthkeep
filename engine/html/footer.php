<footer>
	<div class="iHold">
		<div id="footerFollow" style="">
		<?php require_once(ENGINE_PATH."html/inc/followButtons.php"); ?>
		</div>
		<div id="footerLinks">
			<?php /*<a href="#">Privacy</a> | */ ?>
			<a href="<?php echo WEB_URL; ?>tos">Terms of Use</a> |
			<a href="<?php echo WEB_URL; ?>conditions">Conditions</a> | 
			<a href="<?php echo WEB_URL; ?>medications">Medications</a> | 
			<a href="<?php echo WEB_URL; ?>procedures">Procedures</a> | 
			<a href="<?php echo WEB_URL; ?>symptoms">Symptoms</a> | 
			<a href="<?php echo WEB_URL; ?>doctors">Doctors</a>
		</div>
		<div id="footerRights">
			<span class="colorBlue">Health</span><span class="colorRed">Keep</span> - All rights reserved ©<?php echo date('Y'); ?>
		</div>
	</div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>!window.jQuery && document.write('<script src="<?php echo WEB_URL; ?>inc/js/jquery-1.8.3.js"><\/script>')</script>
<script src="<?php echo WEB_URL; ?>inc/js/jquery.placeholder.min.js"></script>
<script src="<?php echo WEB_URL; ?>inc/js/functions.js?v013"></script>
<?php
if(isset($needTokenInput)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.tokeninput.js?v002"></script>
<?php
}
if(isset($needTinyMCE)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    theme: "modern",
    width: 550,
    height: 300,
    menubar: false,
    statusbar: false,
    force_br_newlines : true,
    force_p_newlines : false,
    plugins: [
         "hr link anchor pagebreak spellchecker",
         "code fullscreen insertdatetime nonbreaking",
         "save table paste textcolor"
   ],
   toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link fullpage",
 });
</script>
<?php
}
if(isset($needNumeric)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.numeric.js"></script>
<?php
}
if(isset($needFupload)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.fupload.js"></script>
<?php
}
if(isset($needFitVid)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.fitvids.js"></script>
<?php
}
if(isset($needPanelMenu)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/jquery.jpanelmenu.min.js"></script>
<?php
}
if(isset($needSlider)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/ui/slider/jquery-ui-1.10.2.custom.min.js"></script>
<?php
}
if(isset($needToolTip)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-tooltip.js"></script>
<?php
}
if(isset($needModal)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-modal.js"></script>
<?php
}
if(isset($needAlert)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-alert.js"></script>
<?php
}
if(isset($needButton)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-button.js"></script>
<?php
}
if(isset($needTypeAhead)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/bootstrap/bootstrap-typeahead_tcrosen.js"></script>
<?php
}
if(isset($needInteractiveMap)){
?>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/interactiveMap/lib/raphael.js"></script>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/interactiveMap/color.jquery.js"></script>
	<script type="text/javascript" src="<?php echo WEB_URL; ?>inc/js/interactiveMap/jquery.usmap.js"></script>
<?php	
}
if(isset($needGoogleCharts)){
?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
      	var jsonData = $.ajax({
          url: chartUrl,
          dataType:"json",
          async: false
          }).responseText;
          var obj = jQuery.parseJSON(jsonData);
	      // Create our data table out of JSON data loaded from server.
	      var data = new google.visualization.arrayToDataTable(obj);
	      var options = { <?php echo $googleChartsOptions; ?>};
	      // Instantiate and draw our chart, passing in some options.
	      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	      chart.draw(data, options);
	  }
    </script>
<?php
}
if(isset($needAutoGrow)){
?>
<script src="<?php echo WEB_URL; ?>inc/js/jquery.autogrow-textarea.js"></script>
<?php	
}
if(isset($needScrollTo)){
?>
<script src="<?php echo WEB_URL; ?>inc/js/jquery.scrollTo-1.4.3.1-min.js"></script>
<?php	
}
$onload.="$('.dropdown-toggle').dropdown();pushFooterDown();";
?> 
<script>
$(document).ready(function() { <?php echo preg_replace("/\s+/", " ", $onload); ?> });
</script>
<?php
if(defined(USER_TYPE) || USER_TYPE<5){
	if(USER_ID!=0 && !isset($justSignedUp)){
	
	if(!isset($_SESSION["mx_name_tag"]) || (isset($_SESSION["mx_name_tag"]) && $_SESSION["mx_name_tag"]!=1)){
	
		if(!isset($resProfile) || 
			(isset($resProfile) && isset($resProfile[0]["id_profile"]) && $resProfile[0]["id_profile"]!=USER_ID) || 
			(isset($resProfile) && 
				(!isset($resProfile[0]["id_profile"]) || !isset($resProfile[0]["username_profile"]) || !isset($resProfile[0]["type_profile"]) || !isset($resProfile[0]["created_profile"]))
			)
		){
			if(!isset($profileClass)){
				require_once(ENGINE_PATH.'class/profile.class.php');
				$profileClass=new Profile();
			}
			$resProfile=$profileClass->getById(USER_ID);
		}
		$jsfunctions.="mixpanel.track('Logged in');";
		$jsfunctions.="mixpanel.register({'Username':'".$resProfile[0]["username_profile"]."','Account Type':'".$resProfile[0]["type_profile"]."','Creation Date':'".$resProfile[0]["created_profile"]."'});";
		$jsfunctions.="mixpanel.people.set({'Username':'".$resProfile[0]["username_profile"]."','Account Type':'".$resProfile[0]["type_profile"]."','Creation Date':'".$resProfile[0]["created_profile"]."'});";
		$jsfunctions.="mixpanel.name_tag('".$resProfile[0]["username_profile"]."');";
		$_SESSION["mx_name_tag"]=1;
		
	}
	
	$jsfunctions.="mixpanel.identify('".USER_ID."');";
	
	}
}
if($jsfunctions!=""){
?>
<script>
<?php echo preg_replace("/\s+/", " ", $jsfunctions); ?>
</script>
<?php
}
?>
<!-- UserVoice JavaScript SDK (only needed once on a page) -->
<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/gH1X7c5mNlWa39Q7muMsA.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

<!-- A tab to launch the Classic Widget -->
<script>
UserVoice = window.UserVoice || [];
UserVoice.push(['showTab', 'classic_widget', {
  mode: 'full',
  primary_color: '#9e2f38',
  link_color: '#295caf',
  default_mode: 'feedback',
  forum_id: 201040,
  tab_label: 'Feedback & Support',
  tab_color: '#9e2f38',
  tab_position: 'middle-left',
  tab_inverted: false
}]);
</script>
</body>
</html>