<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class profileController extends Mobile_api{
    
    private $profile_Class;
    
    public function __construct() {
        parent::__construct();
        $this->checkUserID();
        
        require_once(ENGINE_PATH."class/profile.class.php");
        $this->profile_Class = new Profile();
    }
    
    public function getProfile() {
        $this->answer = $this->profile_Class->getById($this->user_id);
    }
    
    

}
