<?php
class Timeline{

	private $config_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
    }
    
    public function getMeasurements($type,$id,$time = 2,$order="asc"){
    
    	$pieces=explode('_', $type);
    	$types='';
    	if($pieces[0]==''){
    		return false;
    	}else if(isset($pieces[1])){
    		$types="and (";
    		$i=0;
    		foreach($pieces as $value){
    			if($i!=0){
	    			$types.=" or ";
    			}
	    		$types.="measurement_tm='".$value."'";	
	    		$i++;	
    		}
    		$types.=")";
    	}else{
	    	$types="and measurement_tm='".$pieces[0]."'";
    	}
    
    	if($time==1){
	    	$only="and date_tm >= date_sub(current_date, INTERVAL 7 day)";
    	}else if($time==2){
	    	$only="and date_tm BETWEEN DATE_SUB(now(), INTERVAL 3 MONTH) AND now()";
    	}else{
	    	$only="and date_tm BETWEEN DATE_SUB(now(), INTERVAL 1 YEAR) AND now()";
    	}
	    $sql="select * from timeline where id_profile_tm=:id $types $only order by date_tm $order";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getMeasurementsChartAble(){
	    $sql="select measurement_tm, count(measurement_tm) as total from timeline where id_profile_tm=:id group by measurement_tm  HAVING total>1 order by measurement_tm";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function add($type_tm,$topic_tm="NULL",$currently_tm=0,$date_tm="0000-00-00",$date_stop_tm="0000-00-00",$frequency_tm=0,$real_freq_tm=0,$unit_tm=0){
    	$this->processNewTimelineEvent($type_tm,$topic_tm);
	    $sql="INSERT INTO `timeline`(`type_tm`, `id_topic_tm`, `currently_tm`, `date_tm`, `date_stop`, `id_profile_tm`, `frequency_tm`,`real_freq_tm`,`unit_tm`) 
	    	VALUES (:type,:topic,:currently,:date_tm,:date_stop,:id,:frequency,:realfreq,:unit)";
	    $res= $this->config_Class->query($sql,array(":type"=>$type_tm,":topic"=>$topic_tm,":currently"=>$currently_tm,
	    	":date_tm"=>$date_tm,":date_stop"=>$date_stop_tm,":id"=>USER_ID,":frequency"=>$frequency_tm,":realfreq"=>$real_freq_tm,":unit"=>$unit_tm));
	    
	    return $res;
    }
    
    public function addMeasurement($measurement,$value,$date_tm){
	    $sql="insert into timeline (type_tm, measurement_tm,frequency_tm,date_tm,id_profile_tm)
	    	VALUES ('mea',:measurement,:value,:date,:id)";
	    $res = $this->config_Class->query($sql,array(":measurement"=>$measurement,":value"=>$value,":date"=>$date_tm,":id"=>USER_ID));
	    
	    if($res && $measurement=='weight'){
	    
	    	$sql="select * from timeline where measurement_tm=:measurement and id_profile_tm=:id order by date_tm desc limit 1";
	    	$resWeight = $this->config_Class->query($sql,array(":measurement"=>$measurement,":id"=>USER_ID));
	    	
	    	if($resWeight["result"]){
		    	require_once(ENGINE_PATH.'class/profile.class.php');
		    	$profileClass=new Profile();
		    	return $profileClass->updateWeigth($resWeight[0]["frequency_tm"]);
	    	}else{
		    	return false;
	    	}
		    
	    }else{
		    return $res;
	    }
	    
	    
    }
    
    public function addMeasurement2Values($measurement,$value,$value2,$date_tm){
	    $sql="insert into timeline (type_tm, measurement_tm,frequency_tm,numeric_tm,date_tm,id_profile_tm)
	    	VALUES ('mea',:measurement,:value,:value2,:date,:id)";
	    return $this->config_Class->query($sql,array(":measurement"=>$measurement,":value"=>$value,"value2"=>$value2,":date"=>$date_tm,":id"=>USER_ID));
    }
    
    public function addDoctorVisit($id_profile,$date_tm){
    
    	$sql="select * from timeline where doc_profile_tm=:doc_id and id_profile_tm=:id limit 1";
    	$res= $this->config_Class->query($sql,array(":doc_id"=>$id_profile,":id"=>USER_ID));
    	
    	if(!$res["result"]){
	    	require_once(ENGINE_PATH.'class/profile.class.php');
	    	$profileClass=new Profile();
	    	$resDoc=$profileClass->getByID($id_profile);
	    	if(!$resDoc["result"]){
		    	return false;
	    	}
	    	$profileClass->follow($id_profile);
	    	require_once(ENGINE_PATH.'class/post.class.php');
	    	$postClass=new Post();
		    $postClass->addNewAbout($id_profile,"I started following ".$resDoc[0]["name_profile"]);
    	}
    
	    $sql="insert into timeline (`type_tm`, `doc_profile_tm`, `date_tm`, `id_profile_tm`) 
	    	VALUES (:type,:doc_id,:date_tm,:id)";
	    $res= $this->config_Class->query($sql,array(":type"=>"doc",":doc_id"=>$id_profile,":date_tm"=>$date_tm,":id"=>USER_ID));
	    
	    return $res;
    }
    
    
    public function topicAlreadyInTimeline($id){
    
    	$sql="select * from timeline where id_topic_tm=:id and id_profile_tm=:user limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
    }
    
    public function processNewTimelineEvent($type_tm,$id){
	    
	    $id=(int)$id;
	    
	    if($id==0){
		    return false;
	    }
	   
	    
	    $sql="select * from timeline where type_tm=:type and id_topic_tm=:id and id_profile_tm=:user limit 1";
	    $res= $this->config_Class->query($sql,array(":type"=>$type_tm,":id"=>$id,":user"=>USER_ID));
	    
	    if(!$res["result"]){
	    	require_once(ENGINE_PATH.'class/topic.class.php');
	    	$topicClass=new Topic();
	    	$resTopic=$topicClass->getByID($id);
	    	if(!$resTopic["result"]){
		    	return false;
	    	}
	    	$topicClass->follow($id);
	    }
	    
    }
    
    public function addSymptoms($topicIds,$currently_tm,$date_tm="0000-00-00"){
	    $sql="INSERT INTO `timeline`(`type_tm`,`date_tm`,`id_profile_tm`,`currently_tm`) 
	    	VALUES (:type,:date_tm,:id,:currently)";
	    $res = $this->config_Class->query($sql,array(":type"=>"sym",":date_tm"=>$date_tm,":id"=>USER_ID,":currently"=>$currently_tm));
	    
	    if($res){
		    $sql="select * from timeline where type_tm='sym' and id_profile_tm=:id order by id_tm desc limit 1";
		    $res = $this->config_Class->query($sql,array(":id"=>USER_ID));
		    
		    if($res["result"]){
		    
		    	foreach($topicIds as $value){
		    		$this->processNewTimelineEvent('sym',$value);
			    	$sql="insert into timeline_topic (id_tm_tt,id_topic_tt) VALUES (:id_tm,:id_topic)";
			    	$resTT=$this->config_Class->query($sql,array(":id_tm"=>$res[0]["id_tm"],":id_topic"=>$value));
			    	
		    	}
		    	
		    	return true;
		    	
		    }else{
			    return false;
		    }
	    }else{
		    return false;
	    }
    }
    
    public function addTestResult($name,$date_tm,$fileInputName){
    
	    $filename=$this->config_Class->uploadFile($fileInputName,ENGINE_PATH.'uploads/');
	    $filename=$filename["file"];
	    if($filename==""){
		    return false;
	    }
	    
	    $sql="insert into timeline (type_tm,name_tm,file_tm,date_tm,id_profile_tm) VALUES (:type,:name,:file_tm,:date_tm,:id)";
	    $res= $this->config_Class->query($sql,array(":type"=>"res",":name"=>$name,":file_tm"=>$filename,":date_tm"=>$date_tm,":id"=>USER_ID));
	    
	    return $res;
    }
    
    public function getUserCurrentByType($id,$type,$currently=0){
	    $sql="select * from timeline left join topic on id_topic_tm=id_topic where id_profile_tm=:id and type_tm=:type";
	    if($currently==1){
	    	$sql.=" and currently_tm='1'";
	    }
	    $sql.=" group by id_topic";
	    return $this->config_Class->query($sql,array(":id"=>$id,":type"=>$type));
    }
    
    public function getUserCurrentByTypeMultiple($id,$type,$currently=0){
	    $sql="select * from timeline, timeline_topic left join topic on id_topic_tt=id_topic where id_tm_tt=id_tm and id_profile_tm=:id and type_tm=:type";
	    if($currently==1){
	    	$sql.=" and currently_tm='1'";
	    }
	    $sql.=" group by id_topic";
	    return $this->config_Class->query($sql,array(":id"=>$id,":type"=>$type));
    }
    
    public function changeIfeel($iFeel){
    	$iFeel=(int)$iFeel;
    	
    	if($iFeel<1 || $iFeel>10){
	    	return false;
    	}
    	
    	$sql="select * from timeline where type_tm='fel' and date_tm=CURRENT_DATE() and id_profile_tm=:id";
    	$res=$this->config_Class->query($sql,array(":id"=>USER_ID));
    	
    	if(!$res["result"]){
	    	$sql="insert into timeline (type_tm,date_tm,id_profile_tm,frequency_tm) VALUES ('fel',now(),:id,:ifeel)";
    	}else{
	    	$sql="update timeline set frequency_tm=:ifeel where id_profile_tm=:id and date_tm=CURRENT_DATE()";
    	}
    	$res=$this->config_Class->query($sql,array(":ifeel"=>$iFeel,":id"=>USER_ID));
    	
	    $sql="update profile set ifeel_profile=:ifeel where id_profile=:id";
	    return $this->config_Class->query($sql,array(":ifeel"=>$iFeel,":id"=>USER_ID));
    }
    
    public function getLastIfeelChange($id){
	    $sql="select * from timeline where type_tm='fel' and id_profile_tm=:id order by date_tm desc limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getById($id){
	    $sql="select * from timeline where id_tm=:id limit 1";  
	  	return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function delete($id){
	    $sql="delete from timeline where id_tm=:id and id_profile_tm=:user";  
	  	return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
    }
    
    public function notCurrently($id){
	    $sql="update timeline set currently_tm='0' where id_tm=:id and id_profile_tm=:user";  
	  	return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
    }
    
    public function getWeightTrakedToday(){
	    $sql="select * from timeline where date_tm= DATE(NOW()) and measurement_tm='weight' and id_profile_tm=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function getTopicsByTimelineId($id){
	    $sql="select * from timeline_topic, topic where id_topic_tt=id_topic and id_tm_tt=:id order by name_topic limit 15";  
	  	return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getProfileTimeline($id,$page=1){
	  	$sql="select * from timeline left join topic on id_topic_tm=id_topic where id_profile_tm=:id order by date_tm desc, id_tm desc";
	  	$sql=$this->config_Class->getPagingQuery($sql,$page);  
	  	return $this->config_Class->query($sql,array(":id"=>$id));
    } 
    
}