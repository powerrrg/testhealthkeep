<?php
onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$profileClass->trackStart();

header('Location:'.WEB_URL.'track');