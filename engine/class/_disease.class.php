<?php
class Disease{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    public function getById($id){
	    
	    $sql="select * from disease where id_disease=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getByUrl($url){
	    
	    $sql="select * from disease where url_disease=:url limit 1";
	    return $this->config_Class->query($sql,array(":url"=>$url));
	    
    }
    
    public function getWikiNotTried(){
	    $sql="select * from disease where tried_wiki_disease='0' limit 1";
	    return $this->config_Class->query($sql,array());
    }
    
    public function setSafeUri(){
	    $res=$this->getAll();
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $sql="update disease set url_disease=:url where id_disease=:id";
			    $url=$this->config_Class->safeUri($value["name_disease"]);
			    $this->config_Class->query($sql,array(":id"=>$value["id_disease"],":url"=>$url));
		    }
	    }
    }
    
    public function updateDefinition($id,$definition,$source){
	    
	    $sql="update disease set definition_disease=:def,source_disease=:source,tried_wiki_disease='1' where id_disease=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id,":def"=>$definition,":source"=>$source));
    }
    
    public function getAll(){
	    
	    $sql="select * from disease order by name_disease";
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getAutoComplete($input){
	    
	    $sql="select * from disease where name_disease LIKE :name limit 10";
	    return $this->config_Class->query($sql,array(":name"=>"%$input%"));
	    
    }
    
    public function addUdisease($id,$year){
	    
	    $res=$this->getById($id);
	    
	    if(!$res["result"]){
		    return false;
	    }

	    $sql="insert into udisease (id_profile_ud,id_disease_ud,year_ud) VALUES (:uid,:id,:year)";
	    return $this->config_Class->query($sql,array(":uid"=>USER_ID,":id"=>$id,":year"=>$year));
    }
    
    public function getAllMy(){
	    
	    $sql="select * from disease, udisease where id_profile_ud=:uid and id_disease_ud=id_disease order by year_ud desc, month_ud desc, day_ud desc";
	    return $this->config_Class->query($sql,array(":uid"=>USER_ID));
	    
    }
    
}