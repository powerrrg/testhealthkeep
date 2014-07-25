<?php
if(isset($designV1)){
	$ccsFile=WEB_URL."inc/styles_v1.css?v194";
}else{
	$ccsFile=WEB_URL."inc/styles_old.css?v038";
}
$pageDescr=str_replace("\"", "'", $pageDescr);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $pageTitle; ?></title>
	<meta name="description" content="<?php echo $pageDescr; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<link rel="stylesheet" href="<?php echo $ccsFile; ?>" />
	<?php if(isset($needSlider)){ echo '<link rel="stylesheet" href="'.WEB_URL.'inc/js/ui/slider/jquery-ui-1.10.2.custom.min.css" />'; } ?>
	<!--[if lt IE 9]> <script src="<?php echo WEB_URL; ?>inc/js/html5-3.4-respond-1.1.0.min.js"></script> <![endif]-->
	<?php
	if(isset($needMarkitup)){
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL; ?>inc/js/markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WEB_URL; ?>inc/js/markitup/sets/hk/style.css" />
	<?php
	}
	?>
	<meta property="og:title" content="<?php echo $pageTitle; ?>"/>
	<meta property="og:site_name" content="HealthKeep"/>
	<meta property="og:description" content="<?php echo $pageDescr; ?>"/>
	<meta property="og:url" content="<?php echo WEB_URL.ltrim($_SERVER["REQUEST_URI"],"/"); ?>"/>
	<?php if(isset($ogImage)){ echo '<meta property="og:image" content="'.$ogImage.'"/>'; } ?>
	<?php include_once(ENGINE_PATH."starter/mixpaneltracking.php") ?>
</head>
<body>
<?php include_once(ENGINE_PATH."starter/analyticstracking.php") ?>