<?php
class Blog{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    private function updateSearchTable($function,$array){
    
	    require_once(ENGINE_PATH.'class/search.class.php');
        $searchClass=new Search();
        return $searchClass->{$function}($array);
	    
    }
    
    public function getAll(){
	    $sql="select * from blog order by id_blog desc limit 100";
	    return $this->config_Class->query($sql,array());
    }
    
    public function getByURL($url){
	    $sql="select * from blog where url_blog=:slug limit 1";
	    return $this->config_Class->query($sql,array(":slug"=>$url));
    }
    
    public function getById($id){
    	$id=(int)$id;
	    $sql="select * from blog where id_blog=:id limit 1";
	    $res= $this->config_Class->query($sql,array(":id"=>$id));
	    return $res;
    }
    
    public function delById($id){
	    $res=$this->getById($id);
	    if($res["result"]){
		    $sql="delete from blog where id_blog=:id";
		    $this->config_Class->query($sql,array(":id"=>$id));
		    if($res[0]["img_blog"]!=""){
		    	$imgPath=PUBLIC_HTML_PATH."img/blog/";
	    		@unlink($imgPath."tb/".$res[0]["img_blog"]);
	    		@unlink($imgPath."med/".$res[0]["img_blog"]);
	    		@unlink($imgPath."org/".$res[0]["img_blog"]);
	    	}
	    }
    }
    
    public function addNew(){
	    $title=$_POST["title"];
	    $url=$_POST["url"];
	    $msg=$_POST["texto"];
	    
	    $imgPath=PUBLIC_HTML_PATH."img/blog/";
	    $image=$this->config_Class->uploadImage("imagem", $imgPath);
	    if($image["image"]!=""){
			$image=$image["image"];
	    }else{
		    $image="";
	    }
	    
	    $sql="INSERT INTO `blog`
	    (`title_blog`, `url_blog`, `text_blog`, `img_blog`, `created_blog`, `updated_blog`) 
	    VALUES (:titulo,:slug,:texto,:imagem,now(),now())";
	    return $this->config_Class->query($sql,array(":titulo"=>$title,":slug"=>$url,":texto"=>$msg,":imagem"=>$image));
    }
    
}