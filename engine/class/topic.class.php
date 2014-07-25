<?php
class Topic extends Base{

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
    
    public function getTotalTopicsFollowed(){
	    $sql="select count(id_topic_tf) as total 
	    from topic_follow where id_profile_tf=:id group by id_profile_tf";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function getUserTopics($timestamp){   //API
	    $sql="select topic.* from topic_follow, topic
	    where id_topic_tf=id_topic and id_profile_tf=:user ".$this->timePostSQL($timestamp, 'gnews_topic')." order by gnews_topic desc limit ".$this->getLimit();
	    return $this->config_Class->query($sql,array(":user"=>USER_ID));
    }
    
        
    public function addNew($name, $type){
    
    	if(strlen($name)<3){
	    	return false;
    	}
	    
	    $sql="select id_topic from topic where name_topic=:name and type_topic=:type limit 1";
	    $res = $this->config_Class->query($sql,array(":name"=>$name,":type"=>$type));
	    
	    if(!$res["result"]){
	    
	    	$url=$this->config_Class->safeUri($name);
	    	
	    	$res=$this->getByUrl($url, $type);
	    	
	    	if(!$res["result"]){
		    
		    	$sql="insert into topic (name_topic,url_topic,type_topic) VALUES (:name,:url,:type)";
		    	$res = $this->config_Class->query($sql,array(":name"=>$name,":url"=>$url,":type"=>$type));
		    	
		    	if($res){
			    	
			    	$res=$this->getByUrl($url, $type);
			    	
			    	if($res){
				    	$id_topic=$res[0]["id_topic"];
				    	
				    	require_once(ENGINE_PATH.'class/post.class.php');
				    	$postClass=new Post();
				    	
				    	$resKW=$postClass->getPostsWithKeyword($name);
				    	
				    	if($resKW["result"]){
					    	foreach($resKW as $key=>$value){
						    	if(is_int($key)){
							    	$postClass->addRelation($value["id_post"],$id_topic);
							    	$this->forceFollow($id_topic,$value["id_profile_post"]);
						    	}
					    	}	
				    	}
				    	
				    	$this->updateSearchTable('newTopic',$res);
				    	
				    	return true;
				    	
			    	}else{
				    	return false;
			    	}
			    	
		    	}else{
			    	return false;
		    	}
		    	
	    	}else{
		    	return false;
	    	}
		    
	    }else{
		    return false;
	    }
    }
    
    public function getAutoComplete($input,$type){
	    
	    $sql="select * from topic left join topic_syn on id_topic_ts=id_topic 
	    	where type_topic LIKE :type and (name_topic LIKE :name OR name_ts LIKE :name2) 
	    	group by id_topic limit 10";
	    return $this->config_Class->query($sql,array(":name"=>"%$input%",":name2"=>"%$input%",":type"=>$type));
	    
    }
    
    public function getAutoCompleteAll($input,$limit=10){
	    
	    $sql="select * from topic left join topic_syn on id_topic_ts=id_topic
	    where (name_topic LIKE :name0 OR name_ts LIKE :name00) group by id_topic limit $limit
	    UNION
	    select * from topic left join topic_syn on id_topic_ts=id_topic
	    where (name_topic LIKE :name OR name_ts LIKE :name2) group by id_topic limit $limit";
	    return $this->config_Class->query($sql,array(":name"=>"%$input%",":name2"=>"%$input%",":name0"=>"$input",":name00"=>"$input"));
	    
    }
    
    public function getAutoCompleteAllForUser($input){
	    //get the topics the user follows
	    $sql="select * from topic_follow,topic left join topic_syn on id_topic_ts=id_topic
	    where id_topic_tf=id_topic and id_profile_tf=:user 
	    and (name_topic LIKE :name OR name_ts LIKE :name2) 
	    group by id_topic limit 10";
	    $res=$this->config_Class->query($sql,array(":name"=>"%$input%",":name2"=>"%$input%",":user"=>USER_ID));
	    if(!$res["result"]){
	    	//if it fails get the others
	    	return $this->getAutoCompleteAll($input);
	    }else{
		    return $res;
	    }
	    
    }
    
    public function delete($id){
	    $sql="delete from topic where id_topic=:id";
	    $res=$this->config_Class->query($sql,array(":id"=>$id));
	    $this->updateSearchTable('delTopic',$id);
	    return $res;
    }
    
    public function getWikiNotTried($type){
	    $sql="select * from topic where tried_wiki_topic='0' and type_topic=:type limit 1";
	    return $this->config_Class->query($sql,array(":type"=>$type));
    }
    
    public function updateDefinition($id,$definition,$source){
	    
	    $sql="update topic set definition_topic=:def,source_topic=:source,tried_wiki_topic='1' where id_topic=:id";
	    $res = $this->config_Class->query($sql,array(":id"=>$id,":def"=>$definition,":source"=>$source));
	    
	    $this->updateSearchTable('updateTopic',$id);
	    return $res;
    }
    
    public function pathPlural($letter){
    
	    if($letter=="d"){
		    return "conditions";
	    }else if($letter=="m"){
		    return "medications";
	    }else if($letter=="p"){
		    return "procedures";
	    }else if($letter=="s"){
		    return "symptoms";
	    }else if($letter=="g"){
		    return "goals";
	    }else{
		    return false;
	    }
	    
    }
    
    public function pathSingular($letter){
    
	    if($letter=="d"){
		    return "condition";
	    }else if($letter=="m"){
		    return "medication";
	    }else if($letter=="p"){
		    return "procedure";
	    }else if($letter=="s"){
		    return "symptom";
	    }else if($letter=="g"){
		    return "goal";
	    }else{
		    return false;
	    }
	    
    }
    
    public function nameSingular($letter){
    
	    if($letter=="d"){
		    return "Condition";
	    }else if($letter=="m"){
		    return "Medication";
	    }else if($letter=="p"){
		    return "Procedure";
	    }else if($letter=="s"){
		    return "Symptom";
	    }else if($letter=="g"){
		    return "Goal";
	    }else{
		    return false;
	    }
	    
    }
    
    public function namePlural($letter){
    
	    if($letter=="d"){
		    return "Conditions";
	    }else if($letter=="m"){
		    return "Medications";
	    }else if($letter=="p"){
		    return "Procedures";
	    }else if($letter=="s"){
		    return "Symptoms";
	    }else if($letter=="g"){
		    return "Goals";
	    }else{
		    return false;
	    }
	    
    }
    
    public function allGoals(){
    	 $sql="select * from topic where type_topic='g'";
	    return $this->config_Class->query($sql,array());
    	//return array('lose-weight'=>'Lose Weight','quit-smoking'=>'Quit Smoking','exercise-more'=>'Exercise More','reduce-stress'=>'Reduce Stress');
    }
    
    public function getFollowersFromPost($id,$notme){
	    $sql="select * from post_relation,topic, topic_follow,profile 
	    where id_post_pr=:id and id_topic=id_topic_pr and id_topic_tf=id_topic 
	    and id_profile_tf=id_profile and id_profile!=:notme group by id_profile";
	    return $this->config_Class->query($sql,array(":id"=>$id,":notme"=>$notme));
    }
    
    public function getAllUserFollowedFromTopic($topic,$id){
	    $sql="select * from topic, topic_follow 
	    where id_topic_tf=id_topic and type_topic=:topic and id_profile_tf=:id order by name_topic";
	    return $this->config_Class->query($sql,array(":topic"=>$topic,":id"=>$id));
    }
    
    public function getFollowers($id){
	    $sql="select * from topic, topic_follow 
	    where id_topic_tf=id_topic and id_topic=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getTotalNumberOfFollowers($id){
	    
	    $sql="select count(id_profile_tf) as total from topic, topic_follow 
	    where id_topic_tf=id_topic and id_topic=:id group by id_profile_tf";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getTotalTopicFollowed($id,$type){
	    
	    $sql="select count(id_profile_tf) as total from topic, topic_follow 
	    where id_topic_tf=id_topic and id_profile_tf=:id and type_topic=:type group by id_profile_tf";
	    return $this->config_Class->query($sql,array(":id"=>$id,":type"=>$type));
    }
    
    public function countUserFollowingTopic($id,$type){
	    $sql="select count(id_profile_tf) as total from topic_follow, topic 
	    	where id_topic_tf=id_topic and type_topic=:type and id_profile_tf=:id group by id_profile_tf";
	    return $this->config_Class->query($sql,array(":id"=>$id,":type"=>$type));
    }
    
    public function countNumberOfUsersFollowingTopic($id){
	    $sql="select count(id_topic_tf) as total from topic_follow, topic 
	    	where id_topic_tf=id_topic and id_topic_tf=:id group by id_topic_tf";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function usersFollowingTopic($id){
	    $sql="select * from topic_follow, topic, profile 
	    	left join country on country_profile=iso2 
	    	left join zipcode on zip_profile=zip
	    	left join doctor on npi_profile=npi_doctor
	    	left join taxonomy on taxonomy_code_doctor=code_taxonomy
	    	where id_topic_tf=id_topic and id_topic_tf=:id and id_profile_tf=id_profile
	    	order by id_profile desc";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getTopicToUpdateGoogleNews(){
	    
	    $sql="select * from topic order by gnews_topic asc limit 1";
	    $res=$this->config_Class->query($sql,array());
	    
	    if($res["result"]){
		    $sql="update topic set gnews_topic=now() where id_topic=:id";
		    $this->config_Class->query($sql,array(":id"=>$res[0]["id_topic"]));
	    }
	    
		return $res;
	    
    }
    
    public function getById($id){
	    
	    $sql="select * from topic where id_topic=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getLastByTopic($type){
	    
	    $sql="select * from topic where type_topic=:type order by id_topic desc limit 1";
	    return $this->config_Class->query($sql,array(":type"=>$type));
	    
    }
    
    public function findGoal($goal){
	    
	    $sql="select * from topic where url_topic=:goal and type_topic='g' limit 1";
	    return $this->config_Class->query($sql,array(":goal"=>$goal));
	    
    }
    
    public function getByUrl($url, $type){
	    
	    $sql="select * from topic where url_topic=:url and type_topic=:type limit 1";
	    return $this->config_Class->query($sql,array(":url"=>$url,":type"=>$type));
	    
    }
    
    public function getTopicSynonyms($id){
	    
	    $sql="select * from topic_syn where id_topic_ts=:id order by name_ts";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function addSynonym($name, $id){
    	$sql="select * from topic_syn where name_ts=:name and id_topic_ts=:id limit 1";
    	$res = $this->config_Class->query($sql,array(":name"=>$name,":id"=>$id));
    	
    	if(!$res["result"]){
	    	$sql="insert into topic_syn (name_ts,id_topic_ts) VALUES (:name,:id)";
	    	$res = $this->config_Class->query($sql,array(":name"=>$name,":id"=>$id));
	    	
	    	if($res){
		    	
		    	require_once(ENGINE_PATH.'class/post.class.php');
		    	$postClass=new Post();
		    	
		    	$res=$postClass->getPostsWithKeyword($name);
		    	
		    	if($res["result"]){
			    	foreach($res as $key=>$value){
				    	if(is_int($key)){
					    	$postClass->addRelation($value["id_post"],$id);
					    	$this->forceFollow($id,$value["id_profile_post"]);
				    	}
			    	}	
		    	}
		    	
		    	$this->updateSearchTable('updateTopic',$id);
		    	
		    	return true;
		    	
	    	}else{
		    	return false;
	    	}
    	}
    }
    
    public function getSynById($id){
	    
	    $sql="select * from topic_syn where id_ts=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function deleteSynonym($id){
    	$sql="select * from topic_syn where id_ts=:id limit 1";
    	$resTopic=$this->config_Class->query($sql,array(":id"=>$id));
    	if($resTopic["result"]){
		    $sql="delete from topic_syn where id_ts=:id";
		    $res = $this->config_Class->query($sql,array(":id"=>$id));
		    $this->updateSearchTable('updateTopic',$resTopic[0]["id_topic_ts"]);
		    return $res;
	    }else{
		    return false;
	    }
    }
    
    public function getAllFromTopic($type){
	    
	    $sql="select *,group_concat(name_ts) as synonyms from topic left join topic_syn on id_topic=id_topic_ts 
	    	where type_topic=:type group by id_topic order by name_topic";
	    return $this->config_Class->query($sql,array(":type"=>$type));
	    
    }
    
    public function getAllFromTopicStartingWith($type,$letter){
	    if($letter=="numeric"){
	    	$sql="select *,group_concat(name_ts) as synonyms from topic left join topic_syn on id_topic=id_topic_ts 
		    	where type_topic=:type and name_topic NOT REGEXP '^[[:alpha:]]' group by id_topic order by name_topic";
	    	return $this->config_Class->query($sql,array(":type"=>$type));
	    }else{
		    $sql="select *,group_concat(name_ts) as synonyms from topic left join topic_syn on id_topic=id_topic_ts 
		    	where type_topic=:type and name_topic LIKE :letter group by id_topic order by name_topic";
	    	return $this->config_Class->query($sql,array(":type"=>$type,":letter"=>"$letter%"));
	    }
	    
	    
    }
    
    public function isFollowing($id){
    
	    $sql="select * from topic_follow where id_topic_tf=:id and id_profile_tf=:user limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    
    }
    
    public function userIsFollowing($id,$user){
    
	    $sql="select * from topic_follow where id_topic_tf=:id and id_profile_tf=:user limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id,":user"=>$user));
	    
    }
    
    public function forceFollow($id,$user){
    
    	$sql="select type_profile from profile where id_profile=:user limit 1";
    	$resU= $this->config_Class->query($sql,array(":user"=>$user));
    	
    	if($resU["result"]){
	    	if($resU[0]["type_profile"]==4){
		    	return true;
	    	}
    	}
    	
    	$res=$this->userIsFollowing($id,$user);
    	
    	if(!$res["result"]){
    	
	    	$sql="insert into topic_follow (id_topic_tf,id_profile_tf) VALUES (:id,:user)";
	    	$res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>$user));
	    	
	    	if(!$res){
		    	return false;
	    	}else{
		    	
		    	/*$resTopic=$this->getById($id);
		    	
		    	if($resTopic["result"]){
		    		require_once(ENGINE_PATH.'class/post.class.php');
		    		$postClass=new Post();
		    		$postClass->addNew($id,"I joined the ".$resTopic[0]["name_topic"]." community.");
		    		$resLast=$postClass->getLastPostFromUser(USER_ID);
		    		if($resLast["result"]){
			    		$link=WEB_URL."post/".$resLast[0]["id_post"];
			    		//require_once(ENGINE_PATH.'class/message.class.php');
			    		//$messageClass=new Message();
			    		//$resMsg=$messageClass->newUserCommunity(USER_ID,$resTopic,$link);
		    		}
			    }*/
			    return true;
		    }
	    
	    }else{
		    return false;
	    }
	    
    }
    
    public function follow($id){
    	
    	$sql="select type_profile from profile where id_profile=:user limit 1";
    	$resU= $this->config_Class->query($sql,array(":user"=>USER_ID));
    	
    	if($resU["result"]){
	    	if($resU[0]["type_profile"]==4){
		    	return true;
	    	}
    	}
    	
    	$res=$this->isFollowing($id);
    	
    	if(!$res["result"]){
    	
	    	$sql="insert into topic_follow (id_topic_tf,id_profile_tf) VALUES (:id,:user)";
	    	$res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    	
	    	if(!$res){
		    	return false;
	    	}else{
		    	
		    	/*$resTopic=$this->getById($id);
		    	
		    	if($resTopic["result"]){
		    		require_once(ENGINE_PATH.'class/post.class.php');
		    		$postClass=new Post();
		    		$postClass->addNew($id,"I joined the ".$resTopic[0]["name_topic"]." community.");
		    		$resLast=$postClass->getLastPostFromUser(USER_ID);
		    		if($resLast["result"]){
			    		$link=WEB_URL."post/".$resLast[0]["id_post"];
			    		//require_once(ENGINE_PATH.'class/message.class.php');
			    		//$messageClass=new Message();
			    		//$resMsg=$messageClass->newUserCommunity(USER_ID,$resTopic,$link);
		    		}
			    }*/
			    return true;
		    }
	    
	    }else{
		    return false;
	    }
	    
    }
    
    public function unfollow($id){
    	
    	$res=$this->isFollowing($id);
    	
    	if($res["result"]){
    	
	    	$sql="delete from topic_follow where id_topic_tf=:id and id_profile_tf=:user";
	    	return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    
	    }else{
		    return false;
	    }
	    
    }
    
    public function setSafeUri(){

    	//$sql="select * from topic where url_topic=''";
    	$sql="SELECT *  FROM `topic` WHERE `type_topic` = 'm' limit 10000";
	    $res=$this->config_Class->query($sql,array());
	    foreach($res as $key=>$value){
		    if(is_int($key)){
			   	$sql="update topic set url_topic=:url where id_topic=:id";
			    $url=$this->config_Class->safeUri($value["name_topic"]);
			    $this->config_Class->query($sql,array(":id"=>$value["id_topic"],":url"=>$url));
		    }
	    }
	    
    }
    
}