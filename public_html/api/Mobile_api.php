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

    protected $config;
    protected $user_id = 0;
    protected $request = array();

    public function __construct($request = array()) {
        $this->request = $request;
        define('USER_ID', $this->getReqParam('user_id', true, 0));
        define('RECORDS_LIMIT', $this->getReqParam('records_limit', true, 0));
        $this->config = new Config();
    }
    
    public function jsonOut() {
        $answer = $this->answer;
        if (is_array($answer)) {
            if (count($answer) == 0) {
                $answer['result'] = self::RESPONSE_STATUS_ERROR;
                $answer['error'] = 'Invalid request.';
            } else {
                if (array_key_exists('result', $answer)) {
                    if ($answer['result'] === 0) {
                        if (array_key_exists('error', $answer)) {
                            $answer['result'] = self::RESPONSE_STATUS_ERROR;
                        } else {
                            $answer['result'] = self::RESPONSE_STATUS_SUCCESS;
                        }
                    } elseif (is_int($answer['result'])) {
                        $answer['result'] = self::RESPONSE_STATUS_SUCCESS;
                    }
                } else {
                    $answer['result'] = self::RESPONSE_STATUS_ERROR;
                    $answer['error'] = 'There is an error in response to the request.';
                }
            }
        }
        echo json_encode($answer);
        exit;
    }

    protected function getReqParam($param_name, $is_int = true, $default = null) {
        if (isset($this->request[$param_name]) && is_string($this->request[$param_name])) {
            $param = trim($this->request[$param_name]);
            if ($is_int) {
                $param = (int)$param;
                if ($param > 0) {
                    return $param;
                }
            } else {
                return $param;
            }
        } elseif (!is_null($default)) {
            if ($is_int) {
                return (int)$default;
            } else {
                return $default;
            }
        }
        $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
        $error_part = $is_int ? ' integer and' : '';
        $this->answer['error'] = 'Parameter '.$param_name.' is'.$error_part.' requered';
        $this->jsonOut();
    }

    protected function getParam($param_name, $default = null) {
        if (isset($this->request[$param_name]) && is_string($this->request[$param_name])) {
            return  trim($this->request[$param_name]);
        } else {
            return $default;
        }
    }

    protected function getReq2Param($param_name, $is_int = true) {
        if (isset($this->request[$param_name]) && is_string($this->request[$param_name])) {
            $param = trim($this->request[$param_name]);
            if ($is_int) {
                $param = (int)$param;
                if ($param > 0) {
                    return $param;
                }
            } else {
                return $param;
            }
        }
        $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
        $error_part = $is_int ? ' integer and' : '';
        $this->answer['error'] = 'Parameter '.$param_name.' is'.$error_part.' requered';
        $this->jsonOut();
    }

    protected function rangeValidator($name, $value) {
        $_model_property = '_'.$name;
        $range_array = $this->$_model_property;
        if (in_array($value, $range_array)) {
            return $value;
        }
        $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
        $this->answer['error'] = 'Parameter '.$name.' should be in range ('.implode(', ', $range_array).')';
        $this->jsonOut();
    }
}