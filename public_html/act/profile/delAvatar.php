<?php
require_once('../../../engine/starter/config.php');

onlyLogged();

require_once(ENGINE_PATH.'class/profile.class.php');
$profileClass=new Profile();

$res=$profileClass->deleteAvatar();

header("Location:".WEB_URL.USER_NAME);
