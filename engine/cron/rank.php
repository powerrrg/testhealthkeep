<?php
$iamcron=1;

require_once('../starter/config.php');

require_once(ENGINE_PATH.'cron/functions.php');

require_once(ENGINE_PATH.'class/post.class.php');
$postClass=new Post();

$resNext=$postClass->getNextUpdateRank();

require_once(ENGINE_PATH."cron/rank_action.php");