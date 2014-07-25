<?php
class Medication_source{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    
    public function getAutoComplete($input){
	    
	    $sql="select * from medication_source where name_ms LIKE :name GROUP BY name_ms limit 10";
	    return $this->config_Class->query($sql,array(":name"=>"%$input%"));
	    
    }
    
}