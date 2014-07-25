<?php
class External{

	private $config_Class;
	private $post_Class=false;
    
    function __construct()
    {
        $this->config_Class=new Config();
        if(!$this->post_Class){
        	include(ENGINE_PATH."class/post.class.php");
        	$this->post_Class=new Post();
        }
    }
    
    public function getLinkToUpdate(){
	    $sql="select * from external_sources order by last_updated_es asc limit 1";
	    $res=$this->config_Class->query($sql,array());
	    if($res["result"]){
		    $sql="update external_sources set last_updated_es=now() where id_es=".$res[0]["id_es"];
		    $this->config_Class->query($sql,array());
	    }
	    return $res;
    }
    
   public function processNewsPost($title,$link,$description,$pubDate,$id_profile,$dontDelete=0){
    	
    	$res=$this->post_Class->getByLinkPost($link);
    	
    	if(!$res["result"]){
    	
    		$res=$this->post_Class->getPostStartingWithTitle($title);
    		
    		if(!$res["result"]){
    		
    			$res=$this->post_Class->isEnglish($title);
    			if($res){
	    			$this->post_Class->addNewsPost($title,$link,$description,$pubDate,$id_profile,'',$dontDelete);
	    		}
	    	}

    	}
	    
    }
    
    public function processNewsPostAuto($title,$link,$description,$pubDate,$id_profile,$img,$dontDelete=0){
    	
    	$res=$this->post_Class->getByLinkPost($link);
    	
    	if(!$res["result"]){
    	
    		$res=$this->post_Class->getPostStartingWithTitle($title);
    		
    		if(!$res["result"]){
    			
    			$res=$this->post_Class->isEnglish($title);
    			if($res){
			    	$this->post_Class->addNewsPostAuto($title,$link,$description,$pubDate,$id_profile,$img,$dontDelete);
			    }
		    }
    	}
	    
    }
    
}