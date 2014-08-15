<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class profileController extends Mobile_api {

    private $_profile;
    private $_available_attr = array('name_profile', 'gender_profile', 'dob_profile', 'country_profile', 'zip_profile', 'job_profile', 'bio_profile');

    public function __construct($request = array()) {
        parent::__construct($request);
        $this->getReqParam('user_id');

        require_once(ENGINE_PATH.'class/profile.class.php');
        $this->_profile = new Profile();
    }

    public function getProfile() {
        $this->answer = $this->_profile->getByIdWithTopics($this->getReqParam('user_id'));
    }

    public function updateProfile() {
        $attributes = $this->getProfileAttr();
        $should_show_location = $this->getParam('should_show_location');
        $location_profile = $this->getParam('location_profile');
        if (isset($should_show_location)) {
            $attributes['should_show_location'] =  $this->getParam('should_show_location');
        }
        if (isset($location_profile)) {
            $attributes['location_profile'] =  $this->getParam('location_profile');
        }

         if (count($attributes) >= count($this->_available_attr) ) {
            if (!isset($this->answer['error'])) {
                $this->answer = $this->_profile->updateProfile($attributes, 'image');
            } else {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            }
        } else {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'We need all this params ('.implode(',', $this->_available_attr).').';
        }
    }

    private function getProfileAttr() {
        $request = $this->request;
        unset($request['user_id']);
        if (count($request)) {
            foreach ($request as $key => $param) {
                if (!in_array($key, $this->_available_attr)) {
                    unset($request[$key]);
                } else {
                    if ($key == 'zip_profile' && strlen($request[$key]) > 0) {
                        if (!$this->_profile->checkZip($request['zip_profile'])) {
                            $this->answer['error'] = 'Wrong zip code, please enter real zip code.';
                        }
                    } else {
                        $request[$key] = trim(strip_tags($param));
                    }
                }
            }
        }
        return $request;
    }

}