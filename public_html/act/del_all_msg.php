<?php
require_once('../../engine/starter/config.php');

onlyLogged();

require_once(ENGINE_PATH.'class/message.class.php');
$messageClass=new Message();

$messageClass->deleteAll();

header("Location:".WEB_URL."msg");