<?php
class Message{

	private $config_Class;
	private $profile_Class;
    
    function __construct()
    {
        $this->config_Class=new Config();
        require_once(ENGINE_PATH.'class/profile.class.php');
        $this->profile_Class=new Profile();
    }
    
    public function getNext10UnReadWarn(){
	    $sql="select to_msg, count(id_msg) as tot from message
	    	where read_msg=0 and warned_msg=0 and type_msg='newpost'
	    	group by to_msg having tot>9";
	    $res=$this->config_Class->query($sql,array());
	    if($res["result"]){
	    	if($res[0]["tot"]>9){

		    	$sql="update message set warned_msg='1' where to_msg=:id and type_msg='newpost'";
	    		$this->config_Class->query($sql,array(":id"=>$res[0]["to_msg"]));
	    		
	    		return $res[0]["to_msg"];

		    }
	    }
	    return false;
	    
    }
    
    public function getInbox(){
	    $sql="select * from message, profile 
	    	where id_profile=from_msg and to_msg=:id and deleted_to_msg=0 
	    	order by date_msg desc limit 50";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function markRead($id){
	    $sql="update message set read_msg=1 where id_msg=:id and to_msg=:user";
	    $res= $this->config_Class->query($sql,array(":id"=>$id, ":user"=>USER_ID));
	    
	    if($res){
		    $res=$this->updateMessageCount(USER_ID);
	    }
	    
	    return $res;
    }
    
    public function delete($id){
	    $sql="delete from message where id_msg=:id and to_msg=:user";
	    $res= $this->config_Class->query($sql,array(":id"=>$id, ":user"=>USER_ID));
	    
	    if($res){
		    $res=$this->updateMessageCount(USER_ID);
	    }
	    
	    return $res;
    }
    
    public function deleteAll(){
	    $sql="delete from message where to_msg=:user";
	    $res= $this->config_Class->query($sql,array(":user"=>USER_ID));
	    
	    if($res){
		    $res=$this->updateMessageCount(USER_ID);
	    }
	    
	    return $res;
    }
    
    public function follow($from,$to){
    
    	if($from==$to){
	    	return true;
    	}
		
		$resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - You've got a new follower";
    	
    	$res=$this->saveMsg($to,'','follower',$from);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->followText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom));
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'follower',$msg,$subject);
	    
    }
    
    private function newUserCommunitySend($from, $to, $topic, $link){
    
	    $resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - New user joined your community";
    	
    	$res=$this->saveMsg($to,$link,'newuser',$from,$topic["id"]);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->newuserText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link,$topic);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'newuser',$msg,$subject);
    }
    
    public function newUserCommunity($from,$topic,$link){
		
		require_once(ENGINE_PATH.'class/topic.class.php');
   		$topicClass=new Topic();
   		
   		$topicArray=array("name"=>$topic[0]["name_topic"],"link"=>WEB_URL.$topicClass->pathSingular($topic[0]["type_topic"])."/".$topic[0]["url_topic"],"id"=>$topic[0]["id_topic"]);
   		
   		$resTopic=$topicClass->getFollowers($topic[0]["id_topic"]);
   		
   		if($resTopic["result"]){
	   		foreach($resTopic as $key=>$value){
	   			if(is_int($key)){
	   				if($from!=$value["id_profile_tf"]){
		   			$this->newUserCommunitySend($from, $value["id_profile_tf"], $topicArray, $link);
		   			}
	   			}
	   		}
   		}
   		
   		return true;
	    
    }
    
    private function newPostCommunitySend($from, $to, $link,$topicId=0){
    
	    $resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - New post about a topic you follow";
    	
    	if($topicId!=0){
	    	$res=$this->saveMsg($to,$link,'newpost',$from,$topicId);
    	}else{
    		$res=$this->saveMsg($to,$link,'newpost',$from);
    	}
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->newpostText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link,$topicId);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return true;
	    //return $this->sendEmail($to,'newpost',$msg,$subject);
    }
    
    public function newPost($res){

		require_once(ENGINE_PATH.'class/topic.class.php');
   		$topicClass=new Topic();
   		
   		$resFollow=$topicClass->getFollowersFromPost($res[0]["id_post"],$res[0]["id_profile"]);
   		
   		if($resFollow["result"]){
   		
   			//echo "<pre>";
   			//print_r($resFollow);
	   		foreach($resFollow as $key=>$value){
	   			if(is_int($key)){
	   				
		   			$this->newPostCommunitySend($res[0]["id_profile"],$value["id_profile"],WEB_URL."post/".$res[0]["id_post"],$value["id_topic"]);
		   			
	   			}
	   		}
   		}
   		
   		return true;
	    
    }
    
    public function post4User($from,$to,$link){
    
    	if($from==$to){
	    	return true;
    	}
		
		$resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - User ".$this->config_Class->name($resFrom)." shared a post with you";
    	
    	$res=$this->saveMsg($to,$link,'post4user',$from);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->post4userText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'post4user',$msg,$subject);
	    
    }
    
    public function comment($from,$to,$link){
    
    	if($from==$to){
	    	return true;
    	}
		
		$resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - New comment on one of your posts";
    	
    	$res=$this->saveMsg($to,$link,'comment',$from);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->commentText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'comment',$msg,$subject);
	    
    }
    
    public function comcom($from,$to,$link){
    
    	if($from==$to){
	    	return true;
    	}
		
		$resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - New comment on a post you commented";
    	
    	$res=$this->saveMsg($to,$link,'comcom',$from);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->comcomText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'comcom',$msg,$subject);
	    
    }
    
    public function commentLike($from,$to,$link){
    
    	if($from==$to){
	    	return true;
    	}
		
		$resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - Your comment as been voted up";
    	
    	$res=$this->saveMsg($to,$link,'likecomment',$from);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->likecommentText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'likecomment',$msg,$subject);
	    
    }
    
    public function postLike($from,$to,$link){
    
    	if($from==$to){
	    	return true;
    	}
		
		$resTo=$this->profile_Class->getById($to);
		$resFrom=$this->profile_Class->getById($from);
		
		if(!$resFrom["result"] || !$resTo["result"]){
			return false;
		}
		
		$subject="HealthKeep - Your post as been voted up";
    	
    	$res=$this->saveMsg($to,$link,'likepost',$from);
    	
    	if(!$res){
	    	return false;
    	}
    	
    	$res=$this->updateMessageCount($to);
    	
    	$msg=$this->beginEmailText($this->config_Class->name($resTo));
    	$msg.=$this->likepostText($resFrom[0]["username_profile"],$this->config_Class->name($resFrom),$link);
    	$msg.=$this->config_Class->endEmailText();
	    
	    return $this->sendEmail($to,'likepost',$msg,$subject);
	    
    }
    
    public function beginEmailText($name){
	    return "Hello ".$name."<br /><br />";
    }
    
    public function post4userText($username,$name,$link){
	    return "The user <a href=\"".WEB_URL.$username."\">".$name."</a> shared <a href=\"$link\">this post</a> with you.";
    }
    
    public function commentText($username,$name,$link){
	    return "The user <a href=\"".WEB_URL.$username."\">".$name."</a> commented on one of <a href=\"$link\">your posts</a>.";
    }
    
    public function comcomText($username,$name,$link){
	    return "The user <a href=\"".WEB_URL.$username."\">".$name."</a> added a comment on a <a href=\"$link\">post</a> you commented.";
    }
    
    public function newuserText($username,$name,$link,$topic){
	    return "We just wanted to let you know a new person named <a href=\"".WEB_URL.$username."\">".$name."</a> has joined the <a href=\"".$topic["link"]."\">".$topic["name"]."</a> community.<br />It might be helpful to stop by and <a href=\"".$link."\">say hello</a>, and let them know they have a friend.";
    }
    
    public function newpostText($username,$name,$link,$topicId=0){
    	$res["result"]=false;
    	if($topicId>0){
    		require_once(ENGINE_PATH.'class/topic.class.php');
    		$topicClass=new Topic();
	    	$res=$topicClass->getById($topicId);
    	}
    	if($res["result"]){
    		return "<a href=\"".WEB_URL.$username."\">".$name."</a> posted a <a href=\"".$link."\">new experience</a> about ".$res[0]["name_topic"].".";
    	}else{
    		return "<a href=\"".WEB_URL.$username."\">".$name."</a> posted a <a href=\"".$link."\">new experience</a> about a topic you follow.";
    	}
    }
    
    public function followText($username,$name){
	    return "The user <a href=\"".WEB_URL.$username."\">".$name."</a> started following you.";
    }
    
    public function likecommentText($username,$name,$link){
	    return "The user <a href=\"".WEB_URL.$username."\">".$name."</a> voted up <a href=\"$link\">your comment</a>.";
    }
    
    public function likepostText($username,$name,$link){
	    return "The user <a href=\"".WEB_URL.$username."\">".$name."</a> voted up <a href=\"$link\">your post</a>.";
    }
    
    public function updateMessageCount($id){
	    
	    $total=0;
	    
	    $sql="select count(to_msg) as total from message where to_msg=:id and read_msg=0 group by to_msg";
	    $res=$this->config_Class->query($sql,array(":id"=>$id));
	    
	    if($res["result"]){
		    $total=$res[0]["total"];
	    }
	    
	    $sql="update profile set msgs_profile=:total where id_profile=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id,":total"=>$total));
	    
    }
    
    public function saveMsg($to,$msg,$type,$from=0,$topic=0){
    	if($topic==0){
	    	$sql="insert into message (to_msg,msg_msg,from_msg,type_msg,date_msg) VALUES (:to,:msg,:from,:type,now())";
	    	return $this->config_Class->query($sql,array(":to"=>$to,":msg"=>$msg,":from"=>$from,":type"=>$type));
	    }else{
		    $sql="insert into message (to_msg,msg_msg,from_msg,type_msg,date_msg,topic_msg) 
		    	VALUES (:to,:msg,:from,:type,now(),:topic)";
	    	return $this->config_Class->query($sql,array(":to"=>$to,":msg"=>$msg,":from"=>$from,":type"=>$type,":topic"=>$topic));
	    }
    }   
     
    public function getById($id){
	    $sql="select * from message where id_msg=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    } 
    
    public function doesntAllowEmail($to,$type){
	    $sql="select * from notifications where id_profile_not=:id and type_not=:type limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$to,":type"=>$type));
    }
    
    public function getAllEmailSettings(){
	    $sql="select * from notifications where id_profile_not=:id ";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function removeAllBlockEmail(){
	    $sql="delete from notifications where id_profile_not=:id ";
	    return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }
    
    public function blockEmailNot($type){
    	$res=$this->doesntAllowEmail(USER_ID,$type);

    	if($res["result"]){
	    	return true;
    	}else if($type=='newsletter'){
    	
    		 
    		$resProfile=$this->profile_Class->getByIdComplete(USER_ID);
    		if(!$resProfile["result"]){
				return false;
			}
			$my_email=$resProfile['0']["email_user"];
    	
    		include(ENGINE_PATH."class/mc/mcAPI.php");
    		include(ENGINE_PATH."class/mc/MCAPI.class.php");
	    	$api = new MCAPI(mcAPI);
 
			$retval = $api->listUnsubscribe(mcNewsLetter,$my_email);
			if ($api->errorCode){

			    $resMC= array("result"=>'errord',"text"=>$api->errorMessage);
			} else {
			    $resMC= array("result"=>'ok',"text"=>'Success! Check your admin email for confirmation.');

			}
			
			//echo implode(',',$resMC)."\r";
			
    	}
    	
	    $sql="insert into notifications (type_not,id_profile_not) VALUES (:type,:id)";
	    $resok= $this->config_Class->query($sql,array(":type"=>$type,":id"=>USER_ID));

	    return $resok;
    }
    
    public function sendEmail($to,$type,$msg,$subject){
	    
	    $res=$this->doesntAllowEmail($to,$type);
	    
	    if($res["result"]){
		    return true;
	    }
		
		$resProfile=$this->profile_Class->getByIdComplete($to);
		
		if(!$resProfile["result"]){
			return false;
		}
		
		include(ENGINE_PATH."starter/mail.php");
		
		$mail->AddReplyTo($fromEmail, $fromEmailName);
		
		$mail->Subject    = $subject;
		
		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($msg);
		
		$name=$this->config_Class->name($resProfile);
		$email=$resProfile[0]["email_user"];
		
		if(!filter_var( $email, FILTER_VALIDATE_EMAIL )){
			return false;
		}
		
		$mail->AddAddress($email, $name);
		
		if(!$mail->Send()) {
		  return false;
		} else {
		  return true;
		}
	    
    }
    
}