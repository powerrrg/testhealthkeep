<?php
class Surgerie{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    
    public function getAll(){
	    
	    $sql="select * from surgerie order by name_surgerie";
	    return $this->config_Class->query($sql,array());
	    
    }
    
}