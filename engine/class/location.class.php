<?php
class Location{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    public function getAllCountries(){
	    
	    $sql="select * from country order by short_name";
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getAutoCompleteZip($input){
    
	    $sql="select * from zipcode where zip LIKE :zip or city LIKE :city limit 10";
	    return $this->config_Class->query($sql,array(":zip"=>"%$input%",":city"=>"%$input%"));
	    
    }
    
    public function getCountryByIso($iso){
	    $sql="select * from country where iso2=:iso limit 1";
	    return $this->config_Class->query($sql,array(":iso"=>$iso));
    }
    
    public function getZipByZip($zip){
	    $sql="select * from zipcode where zip=:zip limit 1";
	    return $this->config_Class->query($sql,array(":zip"=>$zip));
    }
    
}