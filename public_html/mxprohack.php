<?php
require_once('../engine/starter/config.php');
?>
<html>
<head>
<?php 
if(defined(USER_TYPE) || USER_TYPE<5){
include_once(ENGINE_PATH."starter/mixpaneltracking.php");
}
?>
</head>
<body>
<?php
if(defined(USER_TYPE) || USER_TYPE<5){
require_once(ENGINE_PATH."mx/signup.php");
}
if($jsfunctions!=""){
?>
<script>
	<?php echo $jsfunctions; ?>
</script>
<?php
}
?>
<script>
	location.href='<?php echo WEB_URL; ?>feed';
</script>
</body>
</html>