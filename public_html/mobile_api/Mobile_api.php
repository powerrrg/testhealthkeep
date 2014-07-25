<?php
/**
 * Description of Mobile_api
 *
 * @author Игорь
 */
class Mobile_api {
    
    const RESPONSE_STATUS_SUCCESS = true;
    const RESPONSE_STATUS_ERROR = false;

    protected $answer = array();

    protected $config_Class;
    protected $user_id = 0;

    public function __construct() {
        if (isset($_POST['user_id']) && (int)$_POST['user_id'] > 0) {
            define("USER_ID", $_POST['user_id']);
            $this->user_id = $_POST['user_id'];
        }
        $this->config_Class = new Config();
    }
    
    protected function checkUserID() {
        if (!$this->user_id) {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'parametr user_id is required';
            $this->jsonOut();
        }
    }

    public function jsonOut() {
        $answer = $this->answer;
        if (is_array($answer)) {
            if (count($answer) == 0) {
                $answer['result'] = self::RESPONSE_STATUS_ERROR;
                $answer['error'] = 'This request doesn`t work correct or has development status.';
            } else {
                if ( array_key_exists('result', $answer)) {
                    if ($answer['result'] === 0) {
                        $answer['result'] = self::RESPONSE_STATUS_ERROR;
                    } elseif (is_int($answer['result'])) {
                        $answer['result'] = self::RESPONSE_STATUS_SUCCESS;
                    }
                }
            }
        }
        echo json_encode($answer);
        exit;
    }
    
    protected function getStamp() {
        $timestamp = '';
        if (isset($_POST['timestamp'])) {
            $timestamp = $_POST['timestamp'];
        }
        return $timestamp;
    }
    
    protected function getReqParam($param_name, $is_int = true) {
        if (isset($_POST[$param_name])) {
            $param = $_POST[$param_name];
            if (($is_int && (int)$param > 0) || (!$is_int && strlen(trim($param)) > 0)) {
                return $param;
            }
        }
        $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
        $error_part = ($is_int)?' integer and':'';
        $this->answer['error'] = "$param_name is$error_part requered";
        $this->jsonOut();
    }
}
