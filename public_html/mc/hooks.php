<?php
require_once('../../engine/starter/config.php');

$val="<pre>".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."\r";
$val.=implode(",", $_POST);
$val.=implode(",", $_GET);
file_put_contents("hooks.txt", $val);