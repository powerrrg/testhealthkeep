<?php
define("MOBILE_REQUEST", true);
require_once('../../engine/starter/config.php');
require_once ('Mobile_api.php');

$path_string = str_replace('/mobile_api/index.php/', '', $_SERVER['REQUEST_URI']);
$path_array = explode('/', $path_string);

if (count($path_array) !== 2) {
    error404();
} else {
    $class_name = $path_array[0].'Controller';
    $action_name = $path_array[1];
    $file_name = $class_name.'.php';
    if (file_exists($file_name)) {
        require_once($file_name);
        $controller_object = new $class_name();
        if (method_exists($controller_object, $action_name)) {
            $controller_object->$action_name();
            $controller_object->jsonOut();
        } else {
            error404();
        }
    } else {
        error404();
    }
    
}

function error404($error = '') {
    if (!$error) {
        $error = 'Something wrong with requested URL, it format should be something like: HOST_NAME/mobile_api/index.php/user/registration';
    }
    echo json_encode(array('result' => false, 'error' => $error));
    exit;
}

?>