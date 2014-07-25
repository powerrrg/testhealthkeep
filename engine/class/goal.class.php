<?php
class Goal{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    public function addNew($type){
    	$sql="select * from goal where type_goal=:type and id_profile_goal=:id limit 1";
    	$res=$this->config_Class->query($sql,array(":type"=>$type,":id"=>USER_ID));
    	if(!$res["result"]){
	    	$sql="INSERT INTO `goal`(`type_goal`, `id_profile_goal`, `date_goal`) VALUES (:type,:id,now())";
	    	return $this->config_Class->query($sql,array(":type"=>$type,":id"=>USER_ID));
	    }else{
		    return true;
	    }
    }
    
    public function getAllByUser($id){
	    $sql="select * from `goal`where `id_profile_goal`=:id";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function cleanAll($id){
	    $sql="delete from `goal`where `id_profile_goal`=:id";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function name($goal){
	    if($goal=='weight'){
		    return 'Lose Weight';
	    }else if($goal=='smoke'){
	    	return 'Quit Smoking';
	    }else if($goal=='exercise'){
	    	return 'Exercise More';
	    }else if($goal=='stress'){
	    	return 'Reduce Stress';
	    }else{
		    return false;
	    }
    }

    
}