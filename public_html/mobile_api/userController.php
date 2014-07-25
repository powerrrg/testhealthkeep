<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class userController extends Mobile_api{
    
    private $_social_types = array(1 => 'facebook_id', 2 => 'twitter_id', 3 => 'google_id');
    private $user_Class;
    
    public function __construct() {
        parent::__construct();
        
        require_once(ENGINE_PATH."class/user.class.php");
        $this->user_Class = new User();
    }

    public function registration() {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $ar_email = explode('@',$email);
        $username = $ar_email[0];
        $gender = 'm';
        $this->answer = $this->user_Class->addNew($username,$email,$password,$gender);
    }
    
    public function login() {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $this->answer = $this->user_Class->doLogin($email,$password);
    }
    
    public function socialAuth() {
        $social_id = trim($_POST['social_id']);
        $social_type = (int)$_POST['social_type'];
        if (strlen($social_id) < 10 || !$this->validateSocialType($social_type)) {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'Wrong parameter values.';
        } else {
            $field_name = $this->_social_types[$social_type];
            $sql = "select * from user where ".$field_name."=:social_id limit 1";
            $res = $this->config_Class->query($sql, array(":social_id" => $social_id));
            if ($res['result']) {
                $this->answer['result'] = Mobile_api::RESPONSE_STATUS_SUCCESS;
                $this->answer['user_id'] = $res[0]['id_user'];
                $this->answer['new'] = false;
            } else {
                $this->answer = $this->user_Class->addNewSocial($social_id,$field_name);
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
    
    public function forgotPassword() {
        $result = $this->user_Class->requestPassword($this->user_id);
        if ($result == 'ok') {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_SUCCESS;
        } elseif ($result == 'time') {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = 'try 10 minutes later';
        } else {
            $this->answer['result'] = Mobile_api::RESPONSE_STATUS_ERROR;
            $this->answer['error'] = $result;
        }
    }
    
}
