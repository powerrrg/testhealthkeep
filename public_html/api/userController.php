<?php
/**
 * Description of User
 *
 * @author Power (AppDragon)
 */
class userController extends Mobile_api {
    
    private $_social_types = array(
        1 => 'facebook_id'
      , 2 => 'twitter_id'
      , 3 => 'google_id'
    );
    protected $_gender = array(
        1 => 'm'
      , 2 => 'f'
    );
    private $_user;
    private $_profile;    

    public function __construct($request = array()) {
        parent::__construct($request);
        
        require_once(ENGINE_PATH.'class/user.class.php');
        $this->_user = new User();
    }
    
    private function getProfileClass() {
        if (!is_object($this->_profile)) {
             require_once(ENGINE_PATH.'class/profile.class.php');   
             $this->_profile = new Profile();
        }
        return $this->_profile;
    }

    public function registration() {
        $ar_email = explode('@', $this->getReqParam('email', false));
        $username = $ar_email[0];
        $gender = $this->rangeValidator('gender', $this->getReqParam('gender', false));
        $this->answer = $this->_user->addNew(
            $username
          , $this->getReqParam('email', false)
          , $this->getReqParam('password', false)
          , $gender
        );
        $this->newName($this->answer, $gender);
        $this->newAvatar($this->answer, $gender);
        if (isset($this->answer['user_id']) and $this->answer['user_id']>0) {
            $this->getProfileClass()->newDeviceToken($this->getParam('token'), $this->answer['user_id']);
            $this->answer = $this->getProfileClass()->getById($this->answer['user_id']);
        }
    }
    
    public function login() {
        $this->answer = $this->_user->doLogin($this->getReqParam('email', false), $this->getReqParam('password', false), $this->getParam('token'));
        if (isset($this->answer['user_id']) && $this->answer['user_id'] > 0) {
            $this->answer = $this->getProfileClass()->getById($this->answer['user_id']);
        }
    }
    
    public function socialAuth() {
        $social_id = $this->getReqParam('social_id', false);
        $social_type = $this->getReqParam('social_type');
        if (strlen($social_id) < 5) {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'Wrong social_id parameter value.';
        } elseif (!$this->validateSocialType($social_type)) {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'Wrong social_type parameter value.';
        } else {
            $field_name = $this->_social_types[$social_type];
            $res = $this->_user->getBySocial($field_name, $social_id);
            if ($res['result']) {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_SUCCESS;
                $this->answer['user_id'] = $res[0]['id_user'];
            } else {
                $gender = $this->rangeValidator('gender', $this->getReqParam('gender', false));
                $this->answer = $this->_user->addNewSocial(
                    $social_id
                  , $field_name
                  , $gender
                );
                $this->newName($this->answer, $gender);
                $this->newAvatar($this->answer, $gender);
            }

            $user_id =$this->answer['user_id'];
            if (isset($this->answer['user_id']) && $this->answer['user_id'] > 0) {
                $this->answer = $this->getProfileClass()->getById($this->answer['user_id']);

                if ($this->getParam('token') != NULL and $this->getParam('token') != '') {
                    $this->getProfileClass()->newDeviceToken($this->getParam('token'),  $user_id);
                    $this->answer[0]['token_profile'] = $this->getParam('token');
                }
            }
        }
        if (isset($this->answer['user_id']) && $this->answer['user_id'] > 0) {
            $this->getProfileClass()->newAvatar($this->answer['user_id'], 'man1.jpg');
        }
    }

    public function socialIsRegistred() {
        $social_id = $this->getReqParam('social_id', false);
        $social_type = $this->getReqParam('social_type');
        if (strlen($social_id) < 5) {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'Wrong social_id parameter value.';
        } elseif (!$this->validateSocialType($social_type)) {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'Wrong social_type parameter value.';
        } else {
            $field_name = $this->_social_types[$social_type];
            $res = $this->_user->getBySocial($field_name, $social_id);
            if ($res['result']) {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_SUCCESS;
                $this->answer['is_registered'] = 'true';
            } else {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_SUCCESS;
                $this->answer['is_registered'] = 'false';
            }
        }
    }
    
    private function validateSocialType($type) {
        $type_keys = array_keys($this->_social_types);
        if (in_array($type, $type_keys)) {
            return true;
        } else {
            return false;
        }
    }

    private function newAvatar (array $answer, $gender) {
        if($gender == 'f') {
            $image = 'woman';
        } else {
            $image = 'man';
        }
        $image .= rand(1, 12).'.jpg';
        if (isset($answer['user_id']) && $answer['user_id'] > 0) {
            $this->getProfileClass()->newAvatar($answer['user_id'], $image);
        }
    }

    private function newName(array $answer, $gender) {
        if($gender == 'f') {
            $file = 'woman.txt';
        } else {
            $file = 'man.txt';
        }
        if (file_exists(__DIR__.'/'.$file)) {
            $lines = file(__DIR__.'/'.$file);
            $names = array_rand($lines, 2);
            $name = trim($lines[$names[0]]).'-'.trim($lines[$names[1]]);

            if (isset($answer['user_id']) && $answer['user_id'] > 0) {
                $this->getProfileClass()->newName($answer['user_id'], $name);
            }
        }
    }

    public function forgotPassword() {
        $user_result = $this->_user->getByEmail($this->getReqParam('email', false));
        if ($user_result['result']) {
            $result = $this->_user->requestPassword($user_result[0]['id_user']);
            if ($result == 'ok') {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_SUCCESS;
            } elseif ($result == 'time') {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
                $this->answer['error'] = 'Try 10 minutes later, please.';
            } else {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
                $this->answer['error'] = 'Error password recovery.';
            }
        } else {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'User with this email doesn`t exist.';
        }
    }
    
    public function deleteUser() {
        $this->answer = $this->_user->deleteUser($this->getReqParam('user_id'));
    }

    public function logout() {
        $this->answer = $this->_user->logoutModel($this->getReqParam('user_id'));
    }
}