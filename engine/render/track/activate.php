<?php
onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$profileClass->trackActivate();

header('Location:'.WEB_URL.'track');