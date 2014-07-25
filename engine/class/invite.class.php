<?php
class Invite{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    public function save($name,$email){
	    $sql="INSERT INTO `invite`(`name_invite`, `email_invite`, `profile_id_invite`, `date_invite`) 
	    	VALUES (:name,:email,:id,now())";
	    return $this->config_Class->query($sql,array(":name"=>$name,":email"=>$email,":id"=>USER_ID));
    }   
}