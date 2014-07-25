<?php
class Medication{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    
    public function getAll(){
	    
	    $sql="select * from medication order by name_medication";
	    return $this->config_Class->query($sql,array());
	    
    }
    
}