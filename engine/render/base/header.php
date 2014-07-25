<?php
$ccsFile=WEB_URL."inc/styles.css?v291";
$pageDescr=str_replace("\"", "'", $pageDescr);
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="utf-8">
	<title><?php echo $pageTitle; ?></title>
	<meta name="description" content="<?php echo $pageDescr; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<link rel="stylesheet" href="<?php echo $ccsFile; ?>" />	
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]> <script src="<?php echo WEB_URL; ?>inc/js/html5-3.4-respond-1.1.0.min.js"></script> <![endif]-->
	<meta property="og:title" content="<?php echo $pageTitle; ?>"/>
	<meta property="og:site_name" content="HealthKeep"/>
	<meta property="og:description" content="<?php echo $pageDescr; ?>"/>
	<meta property="og:url" content="<?php echo WEB_URL.ltrim($_SERVER["REQUEST_URI"],"/"); ?>"/>
	<?php if(isset($ogImage)){ echo '<meta property="og:image" content="'.$ogImage.'"/>'; } ?>
	<?php include_once(ENGINE_PATH."starter/mixpaneltracking.php") ?>
	<?php
	if(isset($fbTracking)){
	?>
	<script type="text/javascript">
	var fb_param = {};
	fb_param.pixel_id = '6008342118241';
	fb_param.value = '0.00';
	fb_param.currency = 'USD';
	(function(){
	  var fpw = document.createElement('script');
	  fpw.async = true;
	  fpw.src = '//connect.facebook.net/en_US/fp.js';
	  var ref = document.getElementsByTagName('script')[0];
	  ref.parentNode.insertBefore(fpw, ref);
	})();
	</script>
	<noscript><img height="1" width="1" alt="" style="display:none"
	src="https://www.facebook.com/offsite_event.php?id=6008342118241&amp;value=0&amp;currency=USD"
	/></noscript>
	<?php
	}
	?>
</head>
<body>
<?php include_once(ENGINE_PATH."starter/analyticstracking.php") ?>