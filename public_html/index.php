<?php
require_once('../engine/starter/config.php');

if(USER_ID!=0){
    header("Location:".WEB_URL."feed");
    exit();
}

$pageTitle="HealthKeep - Social Health Network";
$pageDescr="HealthKeep is a network to share and learn from health experiences anonymously.";

require_once(ENGINE_PATH."render/base/hp.php");