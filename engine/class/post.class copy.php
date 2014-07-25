<?php
class Post{

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
    
    public function getNextNewsPostToLookLanguage(){
	    $sql="select * from post, profile where id_profile_post=id_profile and type_profile=4 and english_post=0 and title_post!='' order by id_post desc limit 1";
	    $res=$this->config_Class->query($sql,array());
	    
	    if($res["result"]){
		    $sql="update post set english_post=1 where id_post=:id";
		    $this->config_Class->query($sql,array(":id"=>$res[0]["id_post"]));
	    }
	    
	    return $res;
    }
    
    public function isEnglish($string){
	    
	    $string=strip_tags($string);
	    
	    $sql="select * from language where string_lang=:str limit 1";
	    $its=$this->config_Class->query($sql,array(":str"=>$string));
	    
	    if($its["result"]){
		    return $its[0]["english_lang"];
	    }
	    
	    $res=file_get_contents("https://www.googleapis.com/language/translate/v2/detect?key=AIzaSyB-zuAVEeBKpfBT45yZl7mEktPV-rq6-Mc&q=".rawurlencode($string));
	    
	    $arr=json_decode($res);
	    
	    if(!isset($arr->data->detections[0][0]->language)){
	    	return true;
	    }
	    if($arr->data->detections[0][0]->language=="en"){
		    $resIs=1;
	    }else{
		    $resIs=0;
	    }
	    
	    $sql="insert into language (string_lang,english_lang) VALUES (:str,:its)";
	    $this->config_Class->query($sql,array(":str"=>$string,":its"=>$resIs));
	    
	    return $resIs;
    }
    
    public function getPostStartingWithTitle($title){
    
    	if($title==""){
	    	return array("result"=>false);
    	}

    	$sql="select * from post where title_post!='' and SUBSTRING_INDEX(title_post,' ',4) LIKE SUBSTRING_INDEX(:title,' ',4) limit 1";
    	return $this->config_Class->query($sql,array(":title"=>$title));
	    
    }
    
    public function getNextPostToAutoTweet(){
	    
	    $sql="select * from post, post_relation where id_post=id_post_pr and tweeted_post='0' and (id_topic_pr='34015' or id_topic_pr='112')
	    order by date_post desc limit 1";
	    $res=$this->config_Class->query($sql,array());
	    if($res["result"]){
		    $sql="update post set tweeted_post='1' where id_post=:id";
		    $this->config_Class->query($sql,array(":id"=>$res[0]["id_post"]));
	    }
	    return $res;
    }
    
    public function getPostWithLinkNotProcessed(){
	    
	    //and id_profile_post!='8' beacuse MNT only displays logo
	    $sql="select * from post, profile 
	    where link_post!='' and tried_image_post='0' and image_post='' 
	    and id_profile=id_profile_post and type_profile='4'
	    and id_profile_post!='8' order by date_post desc limit 1";
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getPostNextEmail(){
	    $sql="select * from post,profile 
	    	where email_post='0' and id_profile=id_profile_post and type_profile<3 
	    	order by id_post limit 1";
	    $res=$this->config_Class->query($sql,array());
	    if($res["result"]){
		    $sql="update post set email_post='1' where id_post=:id";
		    $this->config_Class->query($sql,array(":id"=>$res[0]["id_post"]));
	    }
	    return $res;
    }
    
    public function markPostWithLinkProcessed($id){
	    
	    $sql="update post set tried_image_post=1 where id_post=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function saveImage($image,$id){
    
    	$sql="update post set image_post=:image where id_post=:id";
	    $res = $this->config_Class->query($sql,array(":image"=>$image,":id"=>$id));
	    
	    if($res){
		    $this->updateSearchTable('updatePost',$id);
	    }
	    return $res;
	    
    }
    
    public function getNextUpdateRank(){
	    $sql="select *, UNIX_TIMESTAMP(date_post) as tsdate from post where social_rank_post='0' and link_post!='' order by date_post desc limit 1";
	    $res = $this->config_Class->query($sql,array());
	    
	    if($res["result"]){
	    $sql="update post set social_rank_post='1' where id_post=:id";
		    $resz = $this->config_Class->query($sql,array(":id"=>$res[0]["id_post"]));
	    }
	    return $res;
    }
    
    public function updateRank($id_post,$tstamp,$thumbs_up,$stumble,$reddit,$fb_total_count,$delicious,$gplus,$quantostweets,$digg,$lin,$pint){
	    $timerank=(time()-$tstamp)/86400;
	    
	    $total = $thumbs_up+$stumble+$reddit+$fb_total_count+$delicious+$gplus+$quantostweets+$digg+$lin+$pint-$timerank;
	    if($total<0){
		    $total=0;
	    }
	    
	    $sql="select * from post_rank where id_rank=:id limit 1";
	    $res = $this->config_Class->query($sql,array(":id"=>$id_post));
	    
	    if($res["result"]){
		    
		    $sql="UPDATE `post_rank` SET `stumble_rank`=:stumble,`reddit_rank`=:reddit,`fb_rank`=:fb,`delicious_rank`=:del,`gplus_rank`=:gplus,`tweets_rank`=:tweets,`digg_rank`=:digg,`lin_rank`=:lin,`pinterest_rank`=:pin,`tstamp_rank`=:timerank,`last_check_rank`=now() WHERE `id_rank`=:id";
		    
	    }else{
		    
		    $sql="INSERT INTO `post_rank`(`id_rank`, `stumble_rank`, `reddit_rank`, `fb_rank`, `delicious_rank`, `gplus_rank`, `tweets_rank`, `digg_rank`, `lin_rank`, `pinterest_rank`, `tstamp_rank`, `last_check_rank`) VALUES (:id,:stumble,:reddit,:fb,:del,:gplus,:tweets,:digg,:lin,:pin,:timerank,now())";
		    
	    }
	    
	    $res = $this->config_Class->query($sql,array(":id"=>$id_post,":stumble"=>$stumble,":reddit"=>$reddit,":fb"=>$fb_total_count,":del"=>$delicious,":gplus"=>$gplus,":tweets"=>$quantostweets,":digg"=>$digg,":lin"=>$lin,":pin"=>$pint,":timerank"=>$timerank));
	    
	    $sql="update post set social_rank_post=:total where id_post=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id_post,":total"=>$total));
	    
    }
    
    public function getByLinkPost($link){
	    $sql="select * from post where link_post=:link limit 1";
	    return $this->config_Class->query($sql,array(":link"=>$link));
    }
    
    public function addNewTemp($score,$description,$title,$url,$thumbnail_url,$id){
    	$title=$this->config_Class->escapeOddChars($title);
    	$description=$this->config_Class->escapeOddChars($description);
	    $text=$this->config_Class->processPostText($description);
	    $sql="INSERT INTO `post_temp`(`title_temp`, `descr_temp`, `image_temp`, `url_temp`, `score_temp`, `id_story_temp`) VALUES (:title,:description,:image,:url,:score,:id)";
	    $res= $this->config_Class->query($sql,array(":title"=>$title,"description"=>$text,":image"=>$thumbnail_url,":url"=>$url,":score"=>$score,":id"=>$id));
	    $sql="select * from post_temp where url_temp=:url order by id_temp desc limit 1";
	    return $this->config_Class->query($sql,array(":url"=>$url));

    }
    
    public function getTemps($id){
	    $sql="select * from post_temp where id_story_temp=:id order by score_temp desc";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getStories($id){
	    $sql="select * from post_stories,post,profile 
	    where id_story_ps=id_post and id_profile_post=id_profile and id_post_ps=:id order by social_rank_post desc, date_post desc";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function linkStories($id,$ids){
    	
    	$sql="INSERT INTO `post_stories`(`id_post_ps`, `id_story_ps`) VALUES (:id,:ids)";
	    return $this->config_Class->query($sql,array(":id"=>$id,":ids"=>$ids));
    }
    
     public function addNewsPostHKSource($title,$description,$id_profile,$video=''){
    	$title=$this->config_Class->escapeOddChars($title);
    	$description=$this->config_Class->escapeOddChars($description);
	    $text=$this->config_Class->processPostText($description);
	    
	    $sql="insert into post (text_post,id_profile_post,title_post,video_post,date_post) 
	    VALUES (:text,:user,:title,:video,now())";
	    $res = $this->config_Class->query($sql,array(":user"=>$id_profile,":text"=>$text,":title"=>$title,":video"=>$video));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>$id_profile));
		    
		    if($res["result"]){
			   $this->findNewsTopic($res[0]["id_post"],$title,$description);
			   $this->updateSearchTable('newPost',$res);
			   return $res;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function addNewsPost($title,$link,$description,$pubDate,$id_profile,$video=''){
    	$title=$this->config_Class->escapeOddChars($title);
    	$description=$this->config_Class->escapeOddChars($description);
	    $text=$this->config_Class->processPostText($description);
	    
	    $sql="insert into post (text_post,id_profile_post,title_post,link_post,date_post,video_post,english_post) VALUES (:text,:user,:title,:link,:pubDate,:video,1)";
	    $res = $this->config_Class->query($sql,array(":user"=>$id_profile,":text"=>$text,":title"=>$title,":link"=>$link,":pubDate"=>$pubDate,":video"=>$video));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>$id_profile));
		    
		    if($res["result"]){
			   $this->findNewsTopic($res[0]["id_post"],$title,$description);
			   $sql="select * from post, post_relation where id_post_pr=id_post and id_post=:id limit 1";
			   $resE = $this->config_Class->query($sql,array(":id"=>$res[0]["id_post"]));
			   if($resE["result"]){
			   		$this->updateSearchTable('newPost',$res);
			   }else{
				   $this->forceDeletePost($res[0]["id_post"]);
			   }
			   return $res;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function addNewsPostAuto($title,$link,$description,$pubDate,$id_profile,$img){
    	$title=$this->config_Class->escapeOddChars($title);
    	$description=$this->config_Class->escapeOddChars($description);
	    $text=$this->config_Class->processPostText($description);
	    
	    $imgPath=PUBLIC_HTML_PATH."img/post/";
	    $image=$this->config_Class->uploadImageURL($img, $imgPath);
	    if($image["image"]!=""){
			$image=$image["image"];
	    }else{
		    $image="";
	    }
	    
	    $sql="insert into post (text_post,id_profile_post,title_post,link_post,date_post,image_post,tried_image_post,english_post) VALUES (:text,:user,:title,:link,:pubDate,:image,'1',1)";
	    $res = $this->config_Class->query($sql,array(":user"=>$id_profile,":text"=>$text,":title"=>$title,":link"=>$link,":pubDate"=>$pubDate,":image"=>$image));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>$id_profile));
		    
		    if($res["result"]){
			   $this->findNewsTopic($res[0]["id_post"],$title,$description);
			   $sql="select * from post, post_relation where id_post_pr=id_post and id_post=:id limit 1";
			   $resE = $this->config_Class->query($sql,array(":id"=>$res[0]["id_post"]));
			   if($resE["result"]){
			   		$this->updateSearchTable('newPost',$res);
			   }else{
				   $this->forceDeletePost($res[0]["id_post"]);
			   }
			   return $res;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function doesNewsHaveTopic($title,$description){
    	$string=preg_replace("/[^A-Za-z0-9 ?!]/","",$title." ".$description);
    	
    	$sql="select * from topic left join topic_syn on id_topic=id_topic_ts where '$string' REGEXP CONCAT('[[:<:]]',name_topic,'[[:>:]]') OR '$string' REGEXP CONCAT('[[:<:]]',name_ts,'[[:>:]]') group by id_topic";
	    $res=$this->config_Class->query($sql,array());
	    
	    if(!$res["result"]){
		    return false;
	    }
	    
	    return true;
	    
    }
    
    public function findNewsTopic($id_post,$title,$description,$forceFollow=false){
    	$string=preg_replace("/[^A-Za-z0-9 ?!]/","",$title." ".$description);
    	
    	$sql="select * from topic left join topic_syn on id_topic=id_topic_ts where '$string' REGEXP CONCAT('[[:<:]]',name_topic,'[[:>:]]') OR '$string' REGEXP CONCAT('[[:<:]]',name_ts,'[[:>:]]') group by id_topic";
	    $res=$this->config_Class->query($sql,array());
	    
	    if($res["result"]){
	    	if($forceFollow){
		    	require_once(ENGINE_PATH.'class/topic.class.php');
		    	$topicClass=new Topic();
	    	}
		    foreach($res as $key=>$value){
		    	if(is_int($key)){
			    	$this->addRelation($id_post,$value["id_topic"]);
			    	if($forceFollow){
				    	$topicClass->follow($value["id_topic"]);
			    	}
		    	}
		    }
	    }
	    
	    return true;
	    
    }
    
    public function getPostWithMyVotes($id){
	    
	    $sql="select * from profile as pro, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
	    where pro.id_profile=p.id_profile_post and p.id_post=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getMCPosts($id){
	    $sql="select * from (select * from (select pro.*,p.*
	    from profile as pro,profile_follow as pf, post as p 
	    where pro.id_profile=p.id_profile_post and 
	    pf.id_profile_pf='".$id."' and p.id_profile_post=pf.id_follow_pf and 
	    p.title_post!='' and
	    p.date_post between date_sub(now(),INTERVAL 1 WEEK) and now() 
	    group by p.id_post order by p.social_rank_post desc, p.date_post desc) as aaa
	    UNION ALL
	    select * from (select pro.*,p.*
	    from profile as pro,topic_follow as tf, post_relation as pr, post as p 
	    where pro.id_profile=p.id_profile_post and 
	    tf.id_profile_tf='".$id."' and pr.id_topic_pr=tf.id_topic_tf and pr.id_post_pr=p.id_post and 
	    p.title_post!='' and
	    p.date_post between date_sub(now(),INTERVAL 1 WEEK) and now()
	    group by p.id_post order by p.social_rank_post desc, p.date_post desc) as bbb) as ccc 
	    order by social_rank_post desc, date_post desc limit 5";

	    $res = $this->config_Class->query($sql,array());
	    
	    if(!$res["result"]){
	    $sql="select * from profile as pro, post as p 
	    where pro.id_profile=p.id_profile_post and 
	    p.date_post between date_sub(now(),INTERVAL 1 WEEK) and now()
	    order by social_rank_post desc, date_post desc limit 5";
	    $res = $this->config_Class->query($sql,array());
	    }
	    
	    return $res;
    }
    
     public function getNewPosts($page=1,$order='recent',$what="all"){
	    
	    if($order=="rank"){
	    	$orderby='order by social_rank_post desc, thumb_up_post desc, date_post desc';
	    }else{
		    $orderby='order by date_post desc';
	    }
	    
	    if($what=="exp"){
		    $what="and pro.type_profile<='2'";
	    }else if($what=="news"){
		    $what="and pro.type_profile='4'";
	    }else{
		    $what="";
	    }
	    
	    $sql="select * from profile as pro, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
	    where pro.id_profile=p.id_profile_post $what ".$orderby;
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getTopicPosts($type,$page=1,$order='recent',$what="all"){
    
    	if($order=="rank"){
	    	$orderby='order by p.social_rank_post desc, p.thumb_up_post desc, p.date_post desc';
	    }else{
		    $orderby='order by p.date_post desc';
	    }
	    
	    if($what=="exp"){
		    $what="pro.type_profile<='2' and";
	    }else if($what=="news"){
		    $what="pro.type_profile='4' and";
	    }else{
		    $what="";
	    }
	    
	    $sql="select pro.*,p.*,pt.* 
	    from profile as pro,topic_follow as tf, topic as t, post_relation as pr, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."' 
	    where pro.id_profile=p.id_profile_post and $what t.id_topic=pr.id_topic_pr and t.type_topic=:type and
	    tf.id_profile_tf='".USER_ID."' and pr.id_topic_pr=tf.id_topic_tf and pr.id_post_pr=p.id_post
	    group by p.id_post $orderby";
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array(":type"=>$type));
	    
    }
    
    public function getTopicIdPosts($id,$page=1,$order='recent',$what="all"){
    
    	if($order=="rank"){
	    	$orderby='order by p.social_rank_post desc, p.thumb_up_post desc, p.date_post desc';
	    }else{
		    $orderby='order by p.date_post desc';
	    }
	    
	    if($what=="exp"){
		    $what="pro.type_profile<='2' and";
	    }else if($what=="news"){
		    $what="pro.type_profile='4' and";
	    }else{
		    $what="";
	    }
	    
	    $sql="select pro.*,p.*,pt.* 
	    from profile as pro, topic as t, post_relation as pr, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."' 
	    where pro.id_profile=p.id_profile_post and $what t.id_topic=pr.id_topic_pr and t.id_topic=:id and
	    pr.id_post_pr=p.id_post
	    group by p.id_post $orderby";
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getFeedPosts($page=1,$order='recent',$what="all"){
    
    	if($order=="rank"){
	    	$orderby='order by p.social_rank_post desc, p.thumb_up_post desc, p.date_post desc';
	    	$orderGlobal="order by social_rank_post desc, thumb_up_post desc, date_post desc";
	    }else{
		    $orderby='order by p.date_post desc';
		    $orderGlobal="order by date_post desc";
	    }
	    
	    if($what=="exp"){
		    $what="pro.type_profile<='2' and";
	    }else if($what=="news"){
		    $what="pro.type_profile='4' and";
	    }else{
		    $what="";
	    }
	    
	    $sql="select * from (select * from (select pro.*,p.*,pt.*
	    from profile as pro,profile_follow as pf, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."' 
	    where pro.id_profile=p.id_profile_post and $what 
	    pf.id_profile_pf='".USER_ID."' and p.id_profile_post=pf.id_follow_pf
	    group by p.id_post $orderby) as aaa
	    UNION ALL
	    select * from (select pro.*,p.*,pt.*
	    from profile as pro, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."' 
	    where pro.id_profile=p.id_profile_post and id_profile_post='".USER_ID."'
	    group by p.id_post $orderby) as aaa2
	    UNION ALL
	    select * from (select pro.*,p.*,pt.* 
	    from profile as pro,topic_follow as tf, post_relation as pr, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."' 
	    where pro.id_profile=p.id_profile_post and $what 
	    tf.id_profile_tf='".USER_ID."' and pr.id_topic_pr=tf.id_topic_tf and pr.id_post_pr=p.id_post 
	    group by p.id_post $orderby) as bbb) as ccc group by id_post $orderGlobal";
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array());
	    
    }
    
    public function getPostsFromUser($id){
	    
	    $sql="select * from post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
	    where p.id_profile_post=:id order by date_post desc limit 50";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getPostsFromAndAboutUser($id,$page=1){
	    
	    $sql="select * from profile as pro, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
	    left join post_about as pa on p.id_post=pa.id_post_pa 
	    where (p.id_profile_post=:id or pa.id_profile_pa=:id_profile) and pro.id_profile=p.id_profile_post
	    order by date_post desc";
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array(":id"=>$id,":id_profile"=>$id));
	    
    }
    
    public function getPostsWithKeyword($keyword){
	    $sql='select * from post 
	    	where text_post REGEXP "[[:<:]]'.$keyword.'[[:>:]]" 
	    	OR title_post REGEXP "[[:<:]]'.$keyword.'[[:>:]]"';
	    return $this->config_Class->query($sql,array());
    }
    
    public function getPostTopics($id){
	    $sql="select * from topic, post_relation where id_post_pr=:id and id_topic_pr=id_topic";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getPostAbout($id){
	    $sql="select * from profile, post_about where id_post_pa=:id and id_profile_pa=id_profile";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getPostRelation($id){
	    $sql="select * from post_relation,topic where id_post_pr=:id and id_topic_pr=id_topic";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getById($id){
	    
	    $sql="select * from post where id_post=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getAllPostComments($id){
	    $sql="select * from  profile as p, post_comment as pc
	    left join post_comment_thumb as pct on pc.id_pc=pct.id_pc_pct and pct.id_profile_pct='".USER_ID."'
	    where pc.id_post_pc=:id and pc.id_profile_pc=p.id_profile order by pc.date_pc asc";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getSomePostComments($id,$limit,$start=0){
	    $sql="select * from profile as p, post_comment as pc 
	    left join post_comment_thumb as pct on pc.id_pc=pct.id_pc_pct and pct.id_profile_pct='".USER_ID."'
	    where pc.id_post_pc=:id and pc.id_profile_pc=p.id_profile order by pc.date_pc asc limit $start,$limit";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function deletePost($id){
    
    	$res=$this->getById($id);
    	
    	if($res["result"]){
    	
    		if($res[0]["image_post"]!=""){
	    		$imgPath=PUBLIC_HTML_PATH."img/post/";
	    		@unlink($imgPath."tb/".$res[0]["image_post"]);
	    		@unlink($imgPath."med/".$res[0]["image_post"]);
	    		@unlink($imgPath."org/".$res[0]["image_post"]);
    		}
    		
	    	$sql="delete from post where id_post=:id and id_profile_post=:user";
	    	$resDel = $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    	if($resDel){
	    		$this->updateSearchTable('delPost',$res);
	    	}
	    	return $resDel;
	    }else{
		    return false;
	    }
    }
    
    public function forceDeleteComment($id){
    	
    	$resCom=$this->getCommentById($id);
    	
	    if($resCom["result"]){
    	
	    	$sql="delete from post_comment where id_pc=:id";
	    	$res = $this->config_Class->query($sql,array(":id"=>$id));

	    	$this->updateCommentCount($resCom[0]["id_post_pc"]);
	    	$this->updateSearchTable('delCommment',$id);
	    	
	    	return $res;
	    	
	    }else{
		    return false;
	    }
    }
    
    public function forceDeletePost($id){
    
    	$res=$this->getById($id);
    	
    	if($res["result"]){
    	
    		if($res[0]["image_post"]!=""){
	    		$imgPath=PUBLIC_HTML_PATH."img/post/";
	    		@unlink($imgPath."tb/".$res[0]["image_post"]);
	    		@unlink($imgPath."med/".$res[0]["image_post"]);
	    		@unlink($imgPath."org/".$res[0]["image_post"]);
    		}
    		
	    	$sql="delete from post where id_post=:id";
	    	$resDel = $this->config_Class->query($sql,array(":id"=>$id));
	    	if($resDel){
	    		$this->updateSearchTable('delPost',$res);
	    	}
	    	return $resDel;
	    }else{
		    return false;
	    }
    }
    
    public function getCommentById($id){
	    
	    $sql="select * from post_comment where id_pc=:id limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function deleteComment($id){
    	
    	$resCom=$this->getCommentById($id);
    	
	    if($resCom["result"]){
    	
	    	$sql="delete from post_comment where id_pc=:id and id_profile_pc=:user";
	    	$res = $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));

	    	$this->updateCommentCount($resCom[0]["id_post_pc"]);
	    	$this->updateSearchTable('delCommment',$id);
	    	
	    	return $res;
	    	
	    }else{
		    return false;
	    }
    }
    
    public function addTopic($id, $post){
    
    	$resPost=$this->getById($post);
    	if(!$resPost["result"]){
	    	return false;
    	}
    	$sql="select * from post_relation where id_post_pr=:post and id_topic_pr=:id";
    	$res=$this->config_Class->query($sql,array(":id"=>$id,":post"=>$post));
    	if(!$res["result"]){
	    	$sql="insert into post_relation (id_post_pr,id_topic_pr) VALUES (:post,:id)";
	    	$resi=$this->config_Class->query($sql,array(":id"=>$id,":post"=>$post));
	    	require_once(ENGINE_PATH.'class/topic.class.php');
		    $topicClass=new Topic();
		    //force user that posted
	    	$topicClass->forceFollow($id,$resPost[0]["id_profile_post"]);
	    	if($resi){
	    		$this->updateSearchTable('updatePost',$post);
	    	}
	    	return $resi;
	    }
	    return true;
    }
    
    public function removeTopic($id, $post){
	    $sql="delete from post_relation where id_post_pr=:post and id_topic_pr=:id";
	    $res=$this->config_Class->query($sql,array(":id"=>$id,":post"=>$post));
	    if($res){
	    	$this->updateSearchTable('updatePost',$post);
	    }
	    return $res;
    }
    
    public function getLastCommentFromUser($id){
	    $sql="select * from profile,post_comment 
	    left join post_comment_thumb on id_pc=id_pc_pct and id_profile_pct='".USER_ID."' 
	    where id_profile_pc=id_profile and id_profile_pc=:id order by id_pc desc limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function getLastPostFromUser($id){
	    $sql="select * from post where id_profile_post=:id order by id_post desc limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
    }
    
    public function addComment($id,$text){
    	$text=$this->config_Class->escapeOddChars($text);
    	$text=$this->config_Class->processPostText($text);
	    
	    $res=$this->getById($id);
	    
	    if(!$res["result"]){
		    return false;
	    }
	    
	    $sql="insert into post_comment (id_post_pc,text_pc,id_profile_pc,date_pc) 
	    	VALUES (:id_post,:text,:id_profile, now())";
	    $res = $this->config_Class->query($sql,array(":id_post"=>$id,":text"=>$text,":id_profile"=>USER_ID));
	 	
	 	if(!$res){
		    return false;
	    }
	    
	    $resUpdate=$this->updateCommentCount($id);
	    
	    $sql="select * from post_comment,post 
	    	where id_post_pc=id_post and id_post_pc=:id 
	    	and id_profile_pc=:user order by id_pc desc limit 1";
	    $resComment = $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    if($resComment["result"]){
		    $link=WEB_URL."post/".$resComment[0]["id_post_pc"]."#iMPostComment_".$resComment[0]["id_pc"];
		    require_once(ENGINE_PATH.'class/message.class.php');
		    $messageClass=new Message();
		    $resMsg=$messageClass->comment(USER_ID,$resComment[0]["id_profile_post"],$link);
		    $sql="select id_profile_pc from post_comment where id_post_pc=:id and id_profile_pc!=:user group by id_profile_pc";
		    $resComCom = $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
		    if($resComCom["result"]){
		    	foreach($resComCom as $keyCC=>$valueCC){
		    		if(is_int($keyCC)){
		    			$messageClass->comcom(USER_ID,$valueCC["id_profile_pc"],$link);
		    		}
		    	}
		    }
		    
		    $this->updateSearchTable('newComment',$resComment);
		    
	    }
	    
	    
	    
	    return true;
	    
    }
    
    public function updateCommentCount($id){
	    
	    $res=$this->getById($id);
	    
	    if(!$res["result"]){
		    return false;
	    }
	    
	    $sql="select count(id_post_pc) as tot from post_comment where id_post_pc=:id group by id_post_pc";
	    $res = $this->config_Class->query($sql,array(":id"=>$id));
	    
	    if(!$res["result"]){
		    $total=0;
	    }else{
		    $total=$res[0]["tot"];
	    }
	    
	    $sql="update post set comments_post=:tot where id_post=:id_post";
	    return $this->config_Class->query($sql,array(":id_post"=>$id,":tot"=>$total));
	    
    }
    public function removeVote($id){
	    
	    $res=$this->getById($id);
	    
	    if(!$res["result"]){
		    return false;
	    }
	    
	    $resVote=$this->alreadyVoted($id);
	    
	    if(!$resVote["result"]){
		    return false;
	    }
	    
	    $sql="delete from post_thumb where id_profile_pt=:user and id_post_pt=:id";
	    $res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    
	    if(!$res){
		    return false;
	    }
	    
	    
	    if($resVote[0]["vote_pt"]>0){
		    return $this->updateVoteCountUp($id);
	    }else if($resVote[0]["vote_pt"]<0){
		    return $this->updateVoteCountDown($id);
	    }
	    
	 }
	 
	 public function removeCommentVote($id){
	    
	    $res=$this->getCommentById($id);
	    
	    if(!$res["result"]){
		    return false;
	    }
	    
	    $resVote=$this->alreadyCommentVoted($id);
	    
	    if(!$resVote["result"]){
		    return false;
	    }
	    
	    $sql="delete from post_comment_thumb where id_profile_pct=:user and id_pc_pct=:id";
	    $res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    
	    if(!$res){
		    return false;
	    }
	    
	    
	    if($resVote[0]["vote_pct"]>0){
		    return $this->updateCommentVoteCountUp($id);
	    }else if($resVote[0]["vote_pct"]<0){
	    	return false;
		    //return $this->updateCommentVoteCountDown($id);
	    }
	    
	 }
	 
	 public function alreadyCommentVoted($id){
	    
	    $sql="select * from post_comment_thumb where id_pc_pct=:id and id_profile_pct=:user limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    
	 }
	 
	 public function updateCommentVoteCountUp($id){
	    $sql="select count(id_pct) as total from post_comment_thumb where id_pc_pct=:id and vote_pct>0 group by id_pc_pct";
	    $res = $this->config_Class->query($sql,array(":id"=>$id));
	    
	    if($res["result"]){
		    $total=$res[0]["total"];
	    }else{
		    $total=0;
	    }
	    
	    $sql="update post_comment set thumb_up_pc=:total where id_pc=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id,":total"=>$total));
    }
	 
	 public function addCommentVote($id,$vote){
	    
	    $resComment=$this->getCommentById($id);
	    
	    if(!$resComment["result"]){
		    return false;
	    }
	    
	    $res=$this->alreadyCommentVoted($id);
	    
	    if($res["result"]){
		    return false;
	    }
	    
	    $sql="insert into post_comment_thumb (id_pc_pct,id_profile_pct,vote_pct) VALUES (:id,:user,:vote)";
	    $res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID,":vote"=>$vote));
	    
	    if(!$res){
		    return false;
	    }
	    
	    if($vote>0){

    		$link=WEB_URL."post/".$resComment[0]["id_post_pc"]."#comment_".$resComment[0]["id_pc"];
	    	require_once(ENGINE_PATH.'class/message.class.php');
	    	$messageClass=new Message();
	    	$resMsg=$messageClass->commentLike(USER_ID,$resComment[0]["id_profile_pc"],$link);

		    return $this->updateCommentVoteCountUp($id);
	    }else if($vote<0){
	    	return false;
		    //return $this->updateVoteCountDown($id);
	    }
    }
    
    public function addVote($id,$vote){
	    
	    $resPost=$this->getById($id);
	    
	    if(!$resPost["result"]){
		    return false;
	    }
	    
	    $res=$this->alreadyVoted($id);
	    
	    if($res["result"]){
		    return false;
	    }
	    
	    $sql="insert into post_thumb (id_post_pt,id_profile_pt,vote_pt) VALUES (:id,:user,:vote)";
	    $res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID,":vote"=>$vote));
	    
	    if(!$res){
		    return false;
	    }
	    
	    if($vote>0){
	    	$link=WEB_URL."post/".$id;
		    require_once(ENGINE_PATH.'class/message.class.php');
		    $messageClass=new Message();
		    $resMsg=$messageClass->postLike(USER_ID,$resPost[0]["id_profile_post"],$link);
		    return $this->updateVoteCountUp($id);
	    }else if($vote<0){
	    	return false;
		    //return $this->updateVoteCountDown($id);
	    }
    }
    
    public function updateVoteCountUp($id){
	    $sql="select count(id_pt) as total from post_thumb where id_post_pt=:id and vote_pt>0 group by id_post_pt";
	    $res = $this->config_Class->query($sql,array(":id"=>$id));
	    
	    if($res["result"]){
		    $total=$res[0]["total"];
	    }else{
		    $total=0;
	    }
	    
	    $sql="update post set thumb_up_post=:total where id_post=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id,":total"=>$total));
    }
    
    public function updateVoteCountDown($id){
	    $sql="select count(id_pt) as total from post_thumb where id_post_pt=:id and vote_pt<0 group by id_post_pt";
	    $res = $this->config_Class->query($sql,array(":id"=>$id));
	    
	    if($res["result"]){
		    $total=$res[0]["total"];
	    }else{
		    $total=0;
	    }
	    
	    $sql="update post set thumb_down_post=:total where id_post=:id";
	    return $this->config_Class->query($sql,array(":id"=>$id,":total"=>$total));
    }
    
    public function alreadyVoted($id){
	    
	    $sql="select * from post_thumb where id_post_pt=:id and id_profile_pt=:user limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));
	    
    }
    
    public function getByTopicId($id,$page=1,$order="recent"){
    
	    if($order=="rank"){
	    	$orderby='order by p.social_rank_post desc, p.thumb_up_post desc, p.date_post desc';
	    }else{
		    $orderby='order by p.date_post desc';
	    }
	    
	    $sql="select * from post_relation as pr, profile as pro, post as p 
	    left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
	    where id_topic_pr=:id and p.id_post=pr.id_post_pr and pro.id_profile=p.id_profile_post $orderby";
	    $sql=$this->config_Class->getPagingQuery($sql,$page);
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    public function getPostById($id){
	    
	    $sql="select * from post as p, profile as pro
	    where id_post=:id and pro.id_profile=p.id_profile_post group by p.id_post limit 1";
	    return $this->config_Class->query($sql,array(":id"=>$id));
	    
    }
    
    
    
    public function addRelation($id_post,$id_topic){
	    
	    $sql="select * from post_relation where id_post_pr=:id_post and id_topic_pr=:id_topic";
	    $res = $this->config_Class->query($sql,array(":id_post"=>$id_post,":id_topic"=>$id_topic));
	    
	    if($res["result"]){
	    
		    return true;
		    
	    }else{
	    
		    $sql="insert into post_relation (id_post_pr,id_topic_pr) values (:id_post,:id_topic)";
		    return $this->config_Class->query($sql,array(":id_post"=>$id_post,":id_topic"=>$id_topic));
	    }
	    
	    
    }
    
    public function addAbout($id_post,$id_profile){
	    
	    $sql="select * from post_about where id_post_pa=:id_post and id_profile_pa=:id_profile";
	    $res = $this->config_Class->query($sql,array(":id_post"=>$id_post,":id_profile"=>$id_profile));
	    
	    if($res["result"]){
	    
		    return true;
		    
	    }else{
	    
		    $sql="insert into post_about (id_post_pa,id_profile_pa) values (:id_post,:id_profile)";
		    $res = $this->config_Class->query($sql,array(":id_post"=>$id_post,":id_profile"=>$id_profile));
		    
	    }
	    
	    
    }
    
    public function addNew($id,$text){
    	$text=$this->config_Class->escapeOddChars($text);
	    
	    $text=$this->config_Class->processPostText($text);
	    
	    $sql="insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())";
	    $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>USER_ID));
		    
		    if($res["result"]){
			   $this->addRelation($res[0]["id_post"],$id);
			   $this->findNewsTopic($res[0]["id_post"],'',$text);
			   $this->updateSearchTable('newPost',$res);
			   return $res;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function addNewNoTopic($text){
    	$text=$this->config_Class->escapeOddChars($text);
	    
	    $text=$this->config_Class->processPostText($text);
	    
	    $sql="insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())";
	    $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>USER_ID));
		    
		    if($res["result"]){
			   $this->findNewsTopic($res[0]["id_post"],'',$text, true);
			   $this->updateSearchTable('newPost',$res);
			   return $res;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function addNewV2Post($text,$img=""){
    	$text=$this->config_Class->escapeOddChars($text);
	    $text=$this->config_Class->processPostText($text);
	    $sql="insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())";
	    $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>USER_ID));
		    
		    if($res["result"]){
		    	
		    	
			   	$this->findNewsTopic($res[0]["id_post"],'',$text,true);
			   	$this->updateSearchTable('newPost',$res);
			   	
			   	if($img!="" && isset($_FILES[$img])){
			    	$imgPath=PUBLIC_HTML_PATH."img/post/";
				    $image=$this->config_Class->uploadImage($img, $imgPath);
				    if($image["image"]!=""){
						$image=$image["image"];
				    }else{
					    $image="";
				    }
				    $this->saveImage($image,$res[0]["id_post"]);
		    	}
			   	return true;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function addNewTopicsPost($topics,$text,$img=""){
    	$text=$this->config_Class->escapeOddChars($text);
	    $text=$this->config_Class->processPostText($text);
	    $sql="insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())";
	    $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>USER_ID));
		    
		    if($res["result"]){
		    	
		    	foreach($topics as $key=>$value){
			    	$resTopic=$this->addRelation($res[0]["id_post"],$value);
			    	if(!$resTopic){
				    	return false;
			    	}
			   	}
			   	$this->findNewsTopic($res[0]["id_post"],'',$text);
			   	$this->updateSearchTable('newPost',$res);
			   	
			   	if($img!="" && isset($_FILES[$img])){
			    	$imgPath=PUBLIC_HTML_PATH."img/post/";
				    $image=$this->config_Class->uploadImage($img, $imgPath);
				    if($image["image"]!=""){
						$image=$image["image"];
				    }else{
					    $image="";
				    }
				    $this->saveImage($image,$res[0]["id_post"]);
		    	}
			   	return true;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
    public function addNewAbout($id,$text){
	    $text=$this->config_Class->escapeOddChars($text);
	    $text=$this->config_Class->processPostText($text);
	    
	    $sql="insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())";
	    $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text));
	    
	    if($res){
		    
		    $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
		    $res = $this->config_Class->query($sql,array(":user"=>USER_ID));
		    
		    if($res["result"]){
			   $this->addAbout($res[0]["id_post"],$id);
			   $this->updateSearchTable('newPost',$res);
			   return $res;
		    }else{
			    return false;
		    }
		    
		    
	    }else{
		    return false;
	    }
	    
    }
    
}