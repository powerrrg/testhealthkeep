<?php
class Search{

	private $config_Class;
	private $profile_Class;
	private $post_Class;
	private $topic_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
        require_once(ENGINE_PATH.'class/profile.class.php');
        $this->profile_Class=new Profile();
        require_once(ENGINE_PATH.'class/post.class.php');
        $this->post_Class=new Post();
        require_once(ENGINE_PATH.'class/topic.class.php');
        $this->topic_Class=new Topic();
    }
    
    public function getById($id){
	    $sql="select * from search where id_s=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function search($q,$page=1,$filter="all"){
	    
	    $conn=$this->config_Class->pdo();
	    $q=$conn->quote($q);
	    
	    if($filter=="topic"){
		    $filter="type_s='topic' and";
	    }else if($filter=="user"){
	    	 $filter="type_s='user' and";
	    }else if($filter=="post"){
	    	 $filter="type_s='post' and";
	    }else if($filter=="comment"){
	    	 $filter="type_s='comment' and";
	    }else{
		    $filter="";
	    }
	    
	    $sql="select *,(
	    (MATCH (snippet_s) AGAINST ($q)*if(type_s='topic',20,40))+
	    (MATCH (user_name_s) AGAINST ($q)*if(type_s='user',100,20))+
	    (MATCH (title_s) AGAINST ($q)*if(type_s='topic',100,40))+
	    (MATCH (topics_s) AGAINST ($q)*if(type_s='topic',100,10))
	    ) as rank from search 
	    WHERE $filter MATCH (title_s,snippet_s,topics_s,user_name_s) AGAINST ($q IN BOOLEAN MODE) order by rank desc
	    ";
	    
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array());

	    
    }
    
    public function updateUsers(){
    	$sql="delete from search where type_s='user'";
	    $res=$this->config_Class->query($sql,array());
	    
	    $sql="select * from profile left join doctor on npi_profile=npi_doctor 
	    left join zipcode on zip_profile=zip left join taxonomy on code_taxonomy=taxonomy_code_doctor";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers1(){
    	$sql="delete from search where type_s='user'";
	    $res=$this->config_Class->query($sql,array());
	    
	    $sql="select * from profile order by id_profile limit 0,100000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers2(){
	    
	    $sql="select * from profile order by id_profile limit 100000,100000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers3(){
	    
	    $sql="select * from profile order by id_profile limit 20000,100000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers4(){
	    
	    $sql="select * from profile order by id_profile limit 300000,100000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers5(){
	    
	    $sql="select * from profile order by id_profile limit 400000,100000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers6(){
	    
	    $sql="select * from profile order by id_profile limit 600000,100000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUsers7(){
	    
	    $sql="select * from profile order by id_profile limit 700000,500000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newUser(array(0=>$value));
		    }
	    }
    }
    
    public function updateUser($id){
    	$res=$this->profile_Class->getById($id);
    	if($res["result"]){
	    	$this->updateUserGlobal($res);
	    	$this->delUser($id);
	    	$this->newUser($res);
    	}
    }
    
    public function updateUserGlobal($array){
	    $sql="select * from search where ext_id_s=:id and type_s='user' limit 1";
	    $res = $this->config_Class->query($sql,array(":id"=>$array[0]["id_profile"]));
	    if($res["result"]){
		    if($res[0]["user_name_s"]!=$array[0]["username_profile"]){
			    $sql="update search set user_name_s=:uname, user_link_s=:link, user_image_s=:img 
			    	where user_name_s=:oldname";
			    $this->config_Class->query($sql,array(":uname"=>$array[0]["username_profile"],
			    	":img"=>$array[0]["image_profile"],
			    	":link"=>WEB_URL.$array[0]["username_profile"],":oldname"=>$res[0]["user_name_s"]));
		    }else if($res[0]["user_image_s"]!=$array[0]["image_profile"]){
		    	$sql="update search set user_image_s=:img where user_name_s=:uname";
		    	$this->config_Class->query($sql,array(":img"=>$array[0]["image_profile"],":uname"=>$array[0]["username_profile"]));
		    }
	    }
    }
    
    public function delUser($id){
	    $sql="delete from search where ext_id_s=:id and type_s='user'";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function newUser($array){
    	
	    if($array[0]["type_profile"]==2 && !isset($array[0]["npi_doctor"]) && $array[0]["npi_profile"]!=""){
		    $sql="select * from doctor where npi_doctor=:npi limit 1";
		    $res=$this->config_Class->query($sql,array(":npi"=>$array[0]["npi_profile"]));
		    if(!$res["result"]){
			    return false;
		    }
		    $array=array(0=>array_merge($array[0],$res[0]));
	    }
	    
	    $text="";
	    if($array[0]["type_profile"]==2 && $array[0]["npi_profile"]!=""){
		   $text.=$array[0]["address_1_doctor"]."<br />";
		   if($array[0]["address_2_doctor"]!=""){
		   	$text.=$array[0]["address_2_doctor"]."<br />";
		   }
		   if($array[0]["zip_profile"]!="" && !isset($array[0]["zip"])){
			   $sql="select * from zipcode where zip=:zip limit 1";
			   $res=$this->config_Class->query($sql,array(":zip"=>$array[0]["zip_profile"]));
			   if($res["result"]){
				   $array=array(0=>array_merge($array[0],$res[0]));
			   }
		   }
		   if(isset($array[0]["zip"]) && $array[0]["zip"]!=""){
		   	   $state=$array[0]["state"];
		   	   require_once(ENGINE_PATH."html/inc/common/usStates.php");
		   	   if(isset($usStates[$state])){
			   	   $state=$usStates[$state];
		   	   }
			   $text.=$state.", ".$array[0]["city"]." - ".$array[0]["zip"]."<br />";
		   }else{
			   	$text.=$array[0]["state_doctor"].", ".$array[0]["city_doctor"]." - ".$array[0]["postal_code_doctor"]."<br />";
		   }
		   
		   if(!isset($array[0]["name_taxonomy"])){
			   $sql="select * from taxonomy where code_taxonomy=:code limit 1";
			   $res=$this->config_Class->query($sql,array(":code"=>$array[0]["taxonomy_code_doctor"]));
			   if($res["result"]){
				   $array=array(0=>array_merge($array[0],$res[0]));
			   }
		   }
		   
		   if(isset($array[0]["name_taxonomy"]) && isset($array[0]["name_taxonomy"])!=""){
			   $text.=$array[0]["name_taxonomy"];
		   }
		   
		   
	    }
	    
	    $title="";
	    
	    if($array[0]["type_profile"]==2){
		    $title="Doctor";
	    }else if($array[0]["type_profile"]==3){
		    $title="Organization";
	    }else if($array[0]["type_profile"]==4){
		    $title="News Source";
	    }else{
		    $title="User";
	    }

	    $sql="insert into search (title_s,snippet_s,user_name_s,user_image_s,user_link_s,type_s,ext_id_s,date_s)
    		VALUES (:title,:text,:uname,:uimg,:ulink,'user',:id,now())";
    	return $this->config_Class->query($sql,array(":title"=>$title,
    		":text"=>$text,":uname"=>$this->config_Class->name($array),
    		":uimg"=>$array[0]["image_profile"],":ulink"=>WEB_URL.$array[0]["username_profile"],":id"=>$array[0]["id_profile"]
    		));
	    
    }
    
    public function updateTopics(){
    	$sql="delete from search where type_s='topic'";
	    $res=$this->config_Class->query($sql,array());
	    
	    $sql="select * from topic";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newTopic(array(0=>$value));
		    }
	    }
    }
    
    public function updateTopic($id){
    	$res=$this->topic_Class->getById($id);
    	if($res["result"]){
	    	$this->delTopic($id);
	    	$this->newTopic($res);
    	}
    }
    
    public function delTopic($id){
    	$sql="delete from search where ext_id_s=:id and type_s='topic'";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function newTopic($array){
    	require_once(ENGINE_PATH."html/inc/common/typeArray.php");
    	$typeArray=typeArray($array[0]["type_topic"]);
    	
    	$res=$this->topic_Class->getTopicSynonyms($array[0]["id_topic"]);
    	$topics="";
    	if($res["result"]){
	    	foreach($res as $key=>$value){
		    	if(is_int($key)){
			    	if($topics!=''){
				    	$topics.=", ";
			    	}
			    	$topics.=$value["name_ts"];
		    	}
	    	}
    	}
    	
    	$sql="insert into search (title_s,snippet_s,image_s,link_s,topics_s,type_s,ext_id_s,date_s)
    		VALUES (:title,:snippet,:image,:link,:topics,'topic',:id,now())";
    		if(isset($typeArray["icon"]) && $typeArray["icon"]!=null){
	    		$aimg=$typeArray["icon"];
    		}else{
	    		$aimg="";
    		}
    	return $this->config_Class->query($sql,array(
    		":title"=>$array[0]["name_topic"],":snippet"=>$array[0]["definition_topic"],":image"=>$aimg,
    		":link"=>WEB_URL.$this->topic_Class->pathSingular($array[0]["type_topic"])."/".$array[0]["url_topic"],
    		":topics"=>$topics,":id"=>$array[0]["id_topic"]
    		));
	    
    }
    
    public function updateComments(){
	    $sql="delete from search where type_s='comment'";
	    $res=$this->config_Class->query($sql,array());
	    
	    $sql="select * from post_comment";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newComment(array(0=>$value));
		    }
	    }
    }
    
    public function newComment($array){

    	$res=$this->profile_Class->getById($array[0]["id_profile_pc"]);
    	
    	if(!$res["result"]){
	    	return false;
    	}
	    
	    $sql="insert into search (snippet_s,link_s,user_name_s,user_image_s,user_link_s,type_s,ext_id_s,date_s)
    		VALUES (:snippet,:link,:uname,:uimg,:ulink,'comment',:id,now())";
    	return $this->config_Class->query($sql,array(
    		":snippet"=>$array[0]["text_pc"],
    		":link"=>WEB_URL.'post/'.$array[0]["id_post_pc"].'#comment_'.$array[0]["id_pc"],
    		":uname"=>$this->config_Class->name($res),
    		":uimg"=>$res[0]["image_profile"],":ulink"=>WEB_URL.$res[0]["username_profile"],":id"=>$array[0]["id_pc"]
    		));
    }
    
    public function delCommment($id){
    	$sql="delete from search where ext_id_s=:id and type_s='comment'";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function updatePosts(){
	    $sql="delete from search where type_s='post'";
	    $res=$this->config_Class->query($sql,array());
	    
	    $sql="select * from post order by id_post limit 0,1000000";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newPost(array(0=>$value));
		    }
	    }
    }
    
    public function updatePosts2(){
	    
	    $sql="select * from post order by id_post limit 1000001,9999999";
	    $res=$this->config_Class->query($sql,array());
	    
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			    $this->newPost(array(0=>$value));
		    }
	    }
    }
    
    public function updatePost($id){
	    $res=$this->post_Class->getById($id);
	    if($res["result"]){
	    	$resDel=$this->delPost($res);
	    	if($resDel){
		    	return $this->newPost($res);
		    }else{
			    return $res;
		    }
	    }else{
		    return false;
	    }
    }
    
    public function delPost($array){
    	$sql="delete from search where ext_id_s=:id and type_s='post'";
	    return $this->config_Class->query($sql,array(":id"=>$array[0]["id_post"]));
    }
    
    public function newPost($array){
    
    	$res=$this->profile_Class->getById($array[0]["id_profile_post"]);
    	if(!$res["result"]){
	    	return false;
    	}
        
        $topics="";
        
        $resRelation=$this->post_Class->getPostRelation($array[0]["id_post"]);
        if($resRelation["result"]){
        	require_once(ENGINE_PATH.'class/topic.class.php');
        	$topicClass=new Topic();
	        foreach($resRelation as $key=>$value){
		        if(is_int($key)){
			        if($topics!=""){
				        $topics.=", ";
			        }
			        $topics.='<a href="'.WEB_URL.$topicClass->pathSingular($value["type_topic"]).'/'.$value["url_topic"].'">'.$value["name_topic"].'</a>';
		        }
	        }
        }
        
        $resAbout=$this->post_Class->getPostAbout($array[0]["id_post"]);
        if($resAbout["result"]){
        	foreach($resAbout as $key=>$value){
		        if(is_int($key)){
			        if($topics!=""){
				        $topics.=", ";
			        }
			        $topics.='<a href="'.WEB_URL.$value["username_profile"].'">'.$this->config_Class->name($value,false).'</a>';
		        }
	        }
        }
    	
    	$sql="insert into search (title_s,snippet_s,image_s,link_s,ext_link_s,topics_s,
    		user_name_s,user_image_s,user_link_s,type_s,ext_id_s,date_s)
    		VALUES (:title,:snippet,:image,:link,:ext_link,:topics,:uname,:uimg,:ulink,'post',:id,now())";
    	return $this->config_Class->query($sql,array(
    		":title"=>$array[0]["title_post"],":snippet"=>$array[0]["text_post"],
    		":image"=>$array[0]["image_post"],":link"=>WEB_URL.'post/'.$array[0]["id_post"],
    		":ext_link"=>$array[0]["link_post"],":topics"=>$topics,":uname"=>$this->config_Class->name($res),
    		":uimg"=>$res[0]["image_profile"],":ulink"=>WEB_URL.$res[0]["username_profile"],":id"=>$array[0]["id_post"]
    		));
	    
    }
    
}