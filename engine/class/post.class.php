<?php
class Post extends Base {

    private $config_Class;
    //private $limit = 10;
    private $user_id_array = array();

    function __construct()
    {
        $this->config_Class=new Config();

    }

    private function updateSearchTable($function, $array) {
        require_once(ENGINE_PATH.'class/search.class.php');
        $searchClass = new Search();
        return $searchClass->{$function}($array);
    }

    public function goBlackList($id){
        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass=new Profile();

        $res=$profileClass->getById($id);
        if($res["result"]){
            $sql="insert into blacklist (url_bl) VALUES ('".$res[0]["name_profile"]."')";
            $quais=$this->config_Class->query($sql,array());
        }

        $sql="select * from post, profile
        where id_profile=id_profile_post and id_profile='$id'";
        $res= $this->config_Class->query($sql,array());
        foreach($res as $key=>$value){
            if(is_int($key)){
                $sql="delete from post where id_post='".$value["id_post"]."'";
                $this->config_Class->query($sql,array());
            }
        }

        $sql="delete from profile where id_profile='$id'";
        $this->config_Class->query($sql,array());

    }

    public function deleteMessageModel($message_id){
        $sql="delete from post where id_post=:message_id";
        $result = $this->config_Class->query($sql,array(":message_id" => $message_id));
        return array("result" => $result);
    }

    public function deletePostsWith($title, $description){
        if($description!=""){
        $sql="select * from post, profile
        where id_profile=id_profile_post and type_profile=4 and (title_post LIKE '%$title%' or text_post LIKE '%$description%')";
        }else{
            $sql="select * from post, profile
        where id_profile=id_profile_post and type_profile=4 and title_post LIKE '%$title%'";
        }
        $res= $this->config_Class->query($sql,array());
        foreach($res as $key=>$value){
            if(is_int($key)){
                $sql="delete from post where id_post='".$value["id_post"]."'";
                $this->config_Class->query($sql,array());
            }
        }
    }

    public function ratePost($id,$rate){
        $sql="select * from post_rate where id_post_prate=:id and id_profile_prate='".USER_ID."' limit 1";
        $res=$this->config_Class->query($sql,array(":id"=>$id));
        if($res["result"]){
            $sql="update post_rate set id_rate_prate=:rate where id_prate=:id";
            $this->config_Class->query($sql,array(":id"=>$res[0]["id_prate"], ":rate"=>$rate));
        }else{
            $sql="insert into post_rate (id_post_prate, id_profile_prate, id_rate_prate)
                VALUES (:id,'".USER_ID."',:rate)";
            $this->config_Class->query($sql,array(":id"=>$id,":rate"=>$rate));
        }
        $sql="select ROUND(AVG(id_rate_prate)) as theRate, COUNT(id_prate) as countRate from post_rate where id_post_prate=:id group by id_post_prate";
        $res=$this->config_Class->query($sql,array(":id"=>$id));
        if($res["result"]){
            $sql="update post set rating_post=:rate, rating_count_post=:countr where id_post=:id";
            $this->config_Class->query($sql,array(":id"=>$id, ":rate"=>$res[0]["theRate"], ":countr"=>$res[0]["countRate"]));
        }
        return true;
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

    public function addNewsPost($title,$link,$description,$pubDate,$id_profile,$video='',$dontDelete=0){
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
               }else if($dontDelete==0){
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

    public function addNewsPostAuto($title,$link,$description,$pubDate,$id_profile,$img,$dontDelete=0){
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
               }else if($dontDelete==0){
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

    private function cleanStringToAutoTag($t){
        $string=preg_replace("/,/", ' ', $t);
        $string=preg_replace("/\./", ' ', $string);
        $string=preg_replace("/!/", ' ', $string);
        $string=preg_replace("/\?/", ' ', $string);
        $string=preg_replace("/[^A-Za-z0-9 ?!]/","",$string);
        return $string;
    }

    public function doesNewsHaveTopic($title,$description){
        $string=$this->cleanStringToAutoTag($title." ".$description);

        $sql="select * from topic left join topic_syn on id_topic=id_topic_ts where '$string' REGEXP CONCAT('[[:<:]]',name_topic,'[[:>:]]') OR '$string' REGEXP CONCAT('[[:<:]]',name_ts,'[[:>:]]') group by id_topic";
        $res=$this->config_Class->query($sql,array());

        if(!$res["result"]){
            return false;
        }

        return true;

    }

    public function findNewsTopic($id_post, $title, $description, $forceFollow = false) {
        $string = $this->cleanStringToAutoTag($title.' '.$description);

        $sql = "select * from topic left join topic_syn on id_topic=id_topic_ts where '$string' REGEXP CONCAT('[[:<:]]',name_topic,'[[:>:]]') OR '$string' REGEXP CONCAT('[[:<:]]',name_ts,'[[:>:]]') group by id_topic";
        $res = $this->config_Class->query($sql,array());

        if ($res['result']) {
            if ($forceFollow) {
                require_once(ENGINE_PATH.'class/topic.class.php');
                $topicClass = new Topic();
            }
            foreach ($res as $key => $value) {
                if (is_int($key)) {
                    $this->addRelation($id_post,$value['id_topic']);
                    if ($forceFollow) {
                        $topicClass->follow($value['id_topic']);
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

    public function getPostWithMyVotesByURL($url){

        $sql="select * from profile as pro, post as p
        left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
        where pro.id_profile=p.id_profile_post and p.url_post=:url limit 1";
        return $this->config_Class->query($sql,array(":url"=>$url));

    }

    public function getTop5Week(){
         $sql="select * from profile as pro, post as p
        where pro.id_profile=p.id_profile_post and
        p.date_post between date_sub(now(),INTERVAL 1 WEEK) and now() and type_profile=1
        order by comments_post desc, thumb_up_post desc, date_post desc limit 5";
        return $this->config_Class->query($sql,array());
    }
    public function getTop5NewsWeek(){
         $sql="select * from profile as pro, post as p
        where pro.id_profile=p.id_profile_post and
        p.date_post between date_sub(now(),INTERVAL 1 WEEK) and now() and type_profile>2
        order by social_rank_post desc, thumb_up_post desc, date_post desc limit 8";
        return $this->config_Class->query($sql,array());
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
            $orderby='order by p.pin_post desc, p.thumb_up_post desc, p.date_post desc';
        }else{
            $orderby='order by p.pin_post desc, p.date_post desc';
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

        $doNotDisplayMyMessages="";

        if($what=="exp"){
            $what_sql="pro.type_profile<='2' and";
        }else if($what=="news"){
            $what_sql="pro.type_profile='4' and ";
            $doNotDisplayMyMessages="where id_profile_post!='".USER_ID."'";
        }else{
            $what_sql="";
        }

        $sql="select * from (select * from (select pro.*,p.*,pt.*
        from profile as pro,profile_follow as pf, post as p
        left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
        where pro.id_profile=p.id_profile_post and $what_sql
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
        where pro.id_profile=p.id_profile_post and $what_sql
        tf.id_profile_tf='".USER_ID."' and pr.id_topic_pr=tf.id_topic_tf and pr.id_post_pr=p.id_post
        group by p.id_post $orderby) as bbb) as ccc $doNotDisplayMyMessages group by id_post $orderGlobal";
        $sql=$this->config_Class->getPagingQuery($sql,$page);
        $query_result = $this->config_Class->query($sql,array());
        if ($query_result['result'] <= 1) {
            $query_result = $this->getNewPosts($page,$order,$what);
        }
        return $query_result;

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
        where (p.id_profile_post=:id or pa.id_profile_pa=:id_profile or p.share_with_post=:id2) and pro.id_profile=p.id_profile_post
        order by date_post desc";
        $sql=$this->config_Class->getPagingQuery($sql,$page);
        return $this->config_Class->query($sql,array(":id"=>$id,":id_profile"=>$id,":id2"=>$id));

    }

    public function getPostsWithKeyword($keyword){
        $sql='select * from post
            where text_post REGEXP "[[:<:]]'.$keyword.'[[:>:]]"
            OR title_post REGEXP "[[:<:]]'.$keyword.'[[:>:]]"';
        return $this->config_Class->query($sql,array());
    }

    public function getPostTopics($id){
        $sql="select * from topic, post_relation where id_post_pr=:id and id_topic_pr=id_topic";
        $res = $this->config_Class->query($sql,array(":id"=>$id));
        if (defined('MOBILE_REQUEST') && MOBILE_REQUEST) {
            unset($res['result']);
        }
        require_once(ENGINE_PATH.'class/topic.class.php');
        $topicClass = new Topic();
        foreach ($res as $key => $val){
            if(is_array($res[$key])){
                $res[$key]['full_url_topic'] = WEB_URL . $topicClass->PathSingular($res[$key]['type_topic']) . "/" . $res[$key]['url_topic'];
            }
        }
       return $res;
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

    public function getAllPostComments($id, $timestamp){ 
        $sql="select p.*, pc.*, IFNULL(pct.vote_pct, 0) as already_voted from  profile as p, post_comment as pc
        left join post_comment_thumb as pct on pc.id_pc=pct.id_pc_pct and pct.id_profile_pct='".USER_ID."'
        where pc.id_post_pc=:id and pc.id_profile_pc=p.id_profile ".$this->timePostSQL($timestamp, 'pc.date_pc')." order by pc.date_pc";
        return $this->config_Class->query($sql,array(":id"=>$id));
    }

    public function getSomePostComments($id,$limit,$start=0){
        $sql="select * from profile as p, post_comment as pc
        left join post_comment_thumb as pct on pc.id_pc=pct.id_pc_pct and pct.id_profile_pct='".USER_ID."'
        where pc.id_post_pc=:id and pc.id_profile_pc=p.id_profile order by pc.date_pc asc limit $start,$limit";
        return $this->config_Class->query($sql,array(":id"=>$id));
    }

    public function togglePostPin($id){

        $res=$this->getById($id);

        if($res["result"]){

            if($res[0]["pin_post"]==0){
                $pinvalue=1;
            }else{
                $pinvalue=0;
            }
            $sql="update post set pin_post=:pin where id_post=:id";
            return $this->config_Class->query($sql,array(":id"=>$id,":pin"=>$pinvalue));
        }
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

    public function updateCommentModel($comment_id, $text = null, $img = "", $video_web_url = null){
        $image="";
        if($img!="" && isset($_FILES[$img])){
            $imgPath=PUBLIC_HTML_PATH."img/post/";
            $image=$this->config_Class->uploadImage($img, $imgPath);
            if($image["image"]!=""){
                $image=$image["image"];
            }else{
                $image="";
            }
        }

        if(!is_null($text)){
            $text=$this->config_Class->escapeOddChars($text);
            $text=$this->config_Class->processPostText($text);
            $sub_sql[0] = "text_pc=:text_comment";
            $params[":text_comment"] = $text;
        }

        if(!is_null($video_web_url)){
            $sub_sql[1] = "video_url_pc=:video_web_url";
            $params[":video_web_url"] = $video_web_url;
        }

        if($image!=""){
            $sub_sql[2] = "image_pc=:image_comment";
            $params[":image_comment"] = $image;
        }

        if (isset($params)) {
            $params[":comment_id"] = $comment_id;
            $sql="update post_comment set ".implode(',', $sub_sql)."  where id_pc=:comment_id";

            if($this->config_Class->query($sql, $params) == true) {
                $response_sql = "select * from post_comment where id_pc=:comment_id";
                $result = $this->config_Class->query($response_sql, array(":comment_id"=>$comment_id));
                return  $result;
            } else {
                return array("result" => false);
            }
        } else {
            return array("result" => false);
        }



    }
    public function getOwnerPost($post_id = 0){
        $sql = "select id_profile_post from post where id_post=:post_id";
        $result = $this->config_Class->query($sql, array(":post_id"=>$post_id));
        if (isset($result[0]['id_profile_post'])){
            return $result[0]['id_profile_post'];
        } else {
            return 0;
        }
    }

    public function updateUnreadCommentsCounter($post_id){
        $sql = "update post set count_unread_comments_post = count_unread_comments_post+1  where id_post=:post_id";
        $result = $this->config_Class->query($sql, array(":post_id"=>$post_id));
        return array("result"=>$result);
    }

    public function setReadCommentsModel($post_id){
        $sql = "select count_unread_comments_post from post where id_post=:post_id";
        $result = $this->config_Class->query($sql, array(":post_id"=>$post_id));

        $sql = "update post set count_unread_comments_post = 0  where id_post=:post_id";
        $this->config_Class->query($sql, array(":post_id"=>$post_id));
        return array("result"=>$result);
    }

    public function setBlockConversationModel($to_user_id){
        // check exist conversation
        $sql = "select id_conv from conversations where (user_id1_conv=:user_id and user_id2_conv=:to_user_id) or (user_id2_conv=:user_id2 and user_id1_conv=:to_user_id2)";
        $conv = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
        if(isset($conv[0]["id_conv"])) {
            // if exist conversation - update column status -> block
            $sql = "update conversations set status_conv = 'block'  where id_conv=:id_conv";
            $result = $this->config_Class->query($sql, array(":id_conv"=>$conv[0]["id_conv"]));
        } else {
            // if not exist conversation - create new conversation
            $sql = "insert into conversations (user_id1_conv, user_id2_conv, status_conv) VALUES (:user_id, :to_user_id, :status)";
            $result = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":status"=>"block"));
        }
        return array("result"=>$result);
    }

    public function addComment($id, $text, $img = "", $video_web_url = ""){
        $text=$this->config_Class->escapeOddChars($text);
        $text=$this->config_Class->processPostText($text);

        $res=$this->getById($id);

        if(!$res["result"]){
            return false;
        }

        $sql="insert into post_comment (id_post_pc,text_pc,id_profile_pc, video_url_pc,date_pc)
            VALUES (:id_post,:text,:id_profile, :video_web_url, now())";
        $res = $this->config_Class->query($sql,array(":id_post"=>$id,":text"=>$text,":id_profile"=>USER_ID, ":video_web_url"=>$video_web_url));

        $last_insert_comment_id = $this->getLastInsertID();

        if(!$res){
            return false;
        }

        $this->getProfileClass()->updateBadge('helpful', USER_ID);

        if($img!="" && isset($_FILES[$img])){
            $imgPath=PUBLIC_HTML_PATH."img/post/";
            $image=$this->config_Class->uploadImage($img, $imgPath);
            if($image["image"]!=""){
                $image=$image["image"];
            }else{
                $image="";
            }
            $this->config_Class->query("update post_comment set image_pc='$image' where id_pc=$last_insert_comment_id");
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
        return $this->getCommentById($last_insert_comment_id);
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
    private function removeVote($id){

        $res=$this->getById($id);

        if(!$res["result"]){
            return false;
        }

        $resVote=$this->alreadyVoted($id);

        if(!$resVote["result"]){
            return false;
        }

        $sql="delete from post_thumb where id_profile_pt=:user and id_post_pt=:id";
        $delete_res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));

        if(!$delete_res){
            return false;
        }

        if($resVote[0]["vote_pt"]>0){
            $final_result = $this->updateVoteCountUp($id);
        }else if($resVote[0]["vote_pt"]<0){
            $final_result = $this->updateVoteCountDown($id);
        }

        $this->getProfileClass()->updateBadge('supportive', USER_ID);
        $this->getProfileClass()->updateBadge('karma', $res[0]['id_profile_post']);

        return $final_result;
    }

     private function removeCommentVote($id){

        $res=$this->getCommentById($id);

        if(!$res["result"]){
            return false;
        }

        $resVote=$this->alreadyCommentVoted($id);

        if(!$resVote["result"]){
            return false;
        }

        $sql="delete from post_comment_thumb where id_profile_pct=:user and id_pc_pct=:id";
        $delete_res= $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));

        if(!$delete_res){
            return false;
        }

        if($resVote[0]["vote_pct"]>0){
            $final_result = $this->updateCommentVoteCountUp($id);
        }else if($resVote[0]["vote_pct"]<0){
            $final_result = $this->updateCommentVoteCountDown($id);
        }

        $this->getProfileClass()->updateBadge('supportive', USER_ID);
        $this->getProfileClass()->updateBadge('karma', $res[0]['id_profile_pc']);

        return $final_result;
     }

     public function alreadyCommentVoted($id){

        $sql="select * from post_comment_thumb where id_pc_pct=:id and id_profile_pct=:user limit 1";
        return $this->config_Class->query($sql,array(":id"=>$id,":user"=>USER_ID));

     }

     public function getCommentOwner($comment_id){
        $sql="select id_profile_pc as comment_owner_id from post_comment where id_pc=:comment_id";
        return $this->config_Class->query($sql,array(":comment_id"=>$comment_id));
     }

     public function getPostOwner($post_id){
        $sql="select id_profile_post as post_owner_id from post where id_post=:post_id";
        return $this->config_Class->query($sql,array(":post_id"=>$post_id));
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

     private function addCommentVote($id,$vote){

        $resComment = $this->getCommentById($id);

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

            $resUpdated=$this->updateCommentVoteCountUp($id);

            $link=WEB_URL."post/".$resComment[0]["id_post_pc"]."#comment_".$resComment[0]["id_pc"];
            require_once(ENGINE_PATH.'class/message.class.php');
            $messageClass=new Message();
            $resMsg=$messageClass->commentLike(USER_ID,$resComment[0]["id_profile_pc"],$link);

            $this->getProfileClass()->updateBadge('supportive', USER_ID);
            $this->getProfileClass()->updateBadge('karma', $resComment[0]['id_profile_pc']);

            return $resUpdated;

        }else if($vote<0){
            return false;
        }
    }

    public function postLike($id,$vote) {
        $resVote=$this->alreadyVoted($id);

        $answer = array('result' => false);
        if($resVote["result"]){
            if ($this->removeVote($id)) {
                $answer['result'] = true;
                $answer['like'] = false;
            }
        } else {
            if ($this->addVote($id,$vote)) {
                $answer['result'] = true;
                $answer['like'] = true;
            }
        }
        return $answer;
    }

    public function commentLike($id,$vote) {
        $resVote=$this->alreadyCommentVoted($id);

        $answer = array('result' => false);
        if($resVote["result"]){
            if ($this->removeCommentVote($id)) {
                $answer['result'] = true;
                $answer['like'] = false;
            }
        } else {
            if ($this->addCommentVote($id,$vote)) {
                $answer['result'] = true;
                $answer['like'] = true;
            }
        }
        return $answer;
    }

    private function addVote($id,$vote){

        $resPost=$this->getById($id);

        if(!$resPost["result"]){
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
            $final_res = $this->updateVoteCountUp($id);

            $this->getProfileClass()->updateBadge('supportive', USER_ID);
            $this->getProfileClass()->updateBadge('karma', $resPost[0]['id_profile_post']);

            return $final_res;
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


    public function searchPosts($keyword, $timestamp) {   //API Request
        $sql = "SELECT DISTINCT
            p.*
          , pro.*
          , IFNULL(pt.vote_pt, 0) as already_voted
        FROM
          post as p
          INNER JOIN profile AS pro ON (p.id_profile_post=pro.id_profile)
          LEFT JOIN post_relation AS pr ON (p.id_post=pr.id_post_pr)
          LEFT JOIN topic AS t ON (pr.id_topic_pr=t.id_topic)
          LEFT JOIN post_thumb AS pt ON (p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."')
        WHERE
            pro.type_profile <= 2
            AND p.share_with_post IS NULL "
            .$this->timePostSQL($timestamp, 'p.date_post')."
            AND (p.text_post LIKE '%".$keyword."%'
                OR p.title_post LIKE '%".$keyword."%'
                OR t.name_topic LIKE '%".$keyword."%'
            )
        ORDER BY
            p.date_post DESC
        LIMIT ".$this->getLimit();
        return $this->config_Class->query($sql);
    }

    public function getPostByIdModel($post_id){
        $sql="select * from post where id_post=:post_id";
        return $this->config_Class->query($sql,array(":post_id"=>$post_id));
    }

    public function getCommentByIdModel($comment_id){
        $sql="select * from post_comment where id_pc=:comment_id";
        return $this->config_Class->query($sql,array(":comment_id"=>$comment_id));
    }

    public function getPostsByTopicId($id_topic, $timestamp){   //API Request
        $sql="select p.*, pro.*, IFNULL(pt.vote_pt, 0) as already_voted
        from post_relation as pr, profile as pro, post as p
        left join post_thumb as pt on p.id_post=pt.id_post_pt and pt.id_profile_pt='".USER_ID."'
        where id_topic_pr=:id and p.id_post=pr.id_post_pr and pro.id_profile=p.id_profile_post
        and pro.type_profile<=2 and p.share_with_post is null ".$this->timePostSQL($timestamp, 'date_post')." order by date_post desc limit ".$this->getLimit();
        return $this->config_Class->query($sql,array(":id"=>$id_topic));

    }

    public function getAllPosts($timestamp, $user_id = 0){   //API Request
        $sql="select p.*, pro.*, IFNULL(pt.vote_pt, 0) as already_voted
        from post as p inner join profile as pro
        left join post_thumb as pt on pt.id_profile_pt=".USER_ID." and pt.id_post_pt=p.id_post
        where pro.id_profile=p.id_profile_post and pro.type_profile<=2 and p.share_with_post is null ".
        $this->userSQL($user_id).$this->timePostSQL($timestamp, 'date_post')." order by date_post desc limit ".$this->getLimit();
        return $this->config_Class->query($sql);
    }
    public function getCountUserUnreadCommentsModel($user_id = 0){   //API Request
        $sql="select sum(count_unread_comments_post) as count_unread_comments
              from post
              where id_profile_post=:user_id";
        $result = $this->config_Class->query($sql, array(":user_id" => $user_id));
        return $result;
    }
    public function getUserUnreadCommentsModel($user_id = 0){   //API Request
        $sql="select sum(p.count_unread_comments_post) as count_unread_comments
        from post as p inner join profile as pro
        left join post_thumb as pt on pt.id_profile_pt=".$user_id." and pt.id_post_pt=p.id_post
        where pro.id_profile=p.id_profile_post and pro.type_profile<=2 and p.share_with_post is null ".
        $this->userSQL($user_id);
        $result = $this->config_Class->query($sql);
        return $result[0]["count_unread_comments"];
    }

    public function getAllPostsPaged($offset, $limit, $user_id = 0) {
        $sql = "select p.*, pro.*, IFNULL(pt.vote_pt, 0) as already_voted
        from post as p inner join profile as pro
        left join post_thumb as pt on pt.id_profile_pt=".USER_ID." and pt.id_post_pt=p.id_post
        where pro.id_profile=p.id_profile_post and pro.type_profile<=2 ".$this->userSQL($user_id)." order by date_post desc limit ".$offset.', '.$limit;
        return $this->config_Class->query($sql);
    }

    public function getAllPostsCount($what) {
        if ($what == 'exp') {
            $what_sql = " and pro.type_profile<='2'";
        } else if ($what == 'news') {
            $what_sql = " and pro.type_profile='4'";
        } else {
            $what_sql = '';
        }
        $sql = 'SELECT COUNT(*) as postCount FROM post as p inner join profile as pro where pro.id_profile=p.id_profile_post'.$what_sql;
        return $this->config_Class->query($sql);
    }

    private function userSQL($user_id) {
        $subquery = "";
        if ($user_id != 0){
            $subquery = " AND id_profile_post = ".$user_id;
        } 
        return $subquery;
    }

    private function getLastInsertID() {
        $last_insert_array = $this->config_Class->query('select LAST_INSERT_ID() as last_id');
        if ($last_insert_array['result'] && $last_insert_array[0]['last_id'] > 0) {
            return $last_insert_array[0]['last_id'];
        } else {
            return 0;
        }
    }


    public function addRelation($id_post, $id_topic) {
        $sql = 'select * from post_relation where id_post_pr=:id_post and id_topic_pr=:id_topic';
        $res = $this->config_Class->query($sql, array(':id_post'=>$id_post, ':id_topic'=>$id_topic));
        if ($res['result']) {
            return true;
        } else {
            $sql = 'insert into post_relation (id_post_pr,id_topic_pr) values (:id_post,:id_topic)';
            $res = $this->config_Class->query($sql, array(':id_post'=>$id_post, ':id_topic'=>$id_topic));
            return $res;
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

    public function addNew($id, $text) {
        $text = $this->config_Class->escapeOddChars($text);
        $text = $this->config_Class->processPostText($text);

        $sql = 'insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())';
        $res = $this->config_Class->query($sql,array(':user'=>USER_ID, ':text'=>$text));

        if ($res) {
            $sql = 'select * from post where id_profile_post=:user order by id_post desc limit 1';
            $res = $this->config_Class->query($sql, array(':user'=>USER_ID));

            if ($res['result']) {
               $this->addRelation($res[0]['id_post'], $id);
               $this->findNewsTopic($res[0]['id_post'], '', $text);
               $this->updateSearchTable('newPost', $res);
               return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function addNewNoTopic($text, $title, $img = "") {
        $text = $this->config_Class->escapeOddChars($text);
        $text = $this->config_Class->processPostText($text);
        $title = $this->config_Class->escapeOddChars($title);
        $title = $this->config_Class->processPostText($title);

        $sql = 'insert into post (text_post,id_profile_post,date_post,title_post) VALUES (:text,:user,now(),:title)';
        $res = $this->config_Class->query($sql,array(':user'=>USER_ID, ':text'=>$text, ':title'=>$title));

        if ($res) {
            $this->getProfileClass()->updateBadge('sharing', USER_ID);

            if($img!="" && isset($_FILES[$img])){
                $imgPath=PUBLIC_HTML_PATH."img/post/";
                $image=$this->config_Class->uploadImage($img, $imgPath);
                if($image["image"]!=""){
                    $image=$image["image"];
                }else{
                    $image="";
                }
                $this->saveImage($image,$this->getLastInsertID());
            }
            $sql = "select * from post where id_profile_post=:user order by id_post desc limit 1";
            $res = $this->config_Class->query($sql,array(':user'=>USER_ID));

            if ($res['result']) {
               $this->findNewsTopic($res[0]['id_post'], $title, $text, true);
               $this->updateSearchTable('newPost', $res);
               return $res;
            } else {
                return false;
            }
        }else{
            return false;
        }

    }

    public function updatePostModel($post_id, $text = null, $title = null, $img = "") {
        $image="";
        if($img!="" && isset($_FILES[$img])){
            $imgPath=PUBLIC_HTML_PATH."img/post/";
            $image=$this->config_Class->uploadImage($img, $imgPath);
            if($image["image"]!=""){
                $image=$image["image"];
            }else{
                $image="";
            }
        }

        if(!is_null($text)){
            $text = $this->config_Class->escapeOddChars($text);
            $text = $this->config_Class->processPostText($text);
            $sub_sql[0] = "text_post=:text_post";
            $params[":text_post"] = $text;
        }

        if(!is_null($title)){
            $title = $this->config_Class->escapeOddChars($title);
            $title = $this->config_Class->processPostText($title);
            $sub_sql[1] = "title_post=:title_post";
            $params[":title_post"] = $title;
        }

        if($image!=""){
            $sub_sql[2] = "image_post=:image_post";
            $params[":image_post"] = $image;
        }

        if (isset($params)) {
            $params[":id_post"] = $post_id;
            $sql="update post set ".implode(',', $sub_sql)."  where id_post=:id_post";
            if($this->config_Class->query($sql, $params) == true) {
                $response_sql = "select * from post where id_post=:id_post";
                $result = $this->config_Class->query($response_sql, array(":id_post"=>$post_id));
                return  $result;
            } else {
                return array("result" => false);
            }
        } else {
            return array("result" => false);
        }
    }

    public function sendIsReadMessageModel($post_id){
        $sql = "update post set read_post = 1  where id_post=:id_post";
        $result = $this->config_Class->query($sql, array(":id_post"=>$post_id));
        return array("result" => $result);
    }

    public function setReadMessagesModel($to_user_id){
        $sql = "update post set read_post = 1  where id_profile_post=:to_user_id and share_with_post=:user_id";
        $result = $this->config_Class->query($sql, array(":to_user_id"=>$to_user_id, ":user_id"=>USER_ID));
        return array("result" => $result);
    }

    public function getIsReadMessageModel($post_id){
        $sql = "select read_post from post where id_post=:id_post";
        $result = $this->config_Class->query($sql, array(":id_post"=>$post_id));
        if(isset($result[0]['read_post']) and $result[0]['read_post'] == 1){
            return array("result" => true);
        } else {
            return array("result" => false);
        }

    }
    public function showConversationModel($to_user_id) {
        $sql = "update conversations set hide_messages_u1 = 0, hide_messages_u2 = 0  where (user_id1_conv=:user_id and user_id2_conv=:to_user_id) or (user_id1_conv=:to_user_id2 and user_id2_conv=:user_id2)";
        $result = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
        return $result["result"];
    }
    public function isHideConversationOtherUserModel($to_user_id) {
        $sql = "select user_id1_conv, user_id2_conv from conversations where (user_id1_conv=:user_id and user_id2_conv=:to_user_id and (hide_messages_u1 = 1 or hide_messages_u2 = 1)) or (user_id1_conv=:to_user_id2 and user_id2_conv=:user_id2 and (hide_messages_u1 = 1 or hide_messages_u2 = 1))";
        $result = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
        return $result["result"];
    }
    public function isHideConversationModel($to_user_id) {
        $sql = "select user_id1_conv, user_id2_conv from conversations where (user_id1_conv=:user_id and user_id2_conv=:to_user_id and hide_messages_u1 = 1) or (user_id1_conv=:to_user_id2 and user_id2_conv=:user_id2 and hide_messages_u2 = 1)";
        $result = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
        return $result["result"];
    }
    public function hideConversationModel($to_user_id) {
        $sql = "select user_id1_conv, user_id2_conv from conversations where (user_id1_conv=:user_id and user_id2_conv=:to_user_id) or (user_id1_conv=:to_user_id2 and user_id2_conv=:user_id2)";
        $conv = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
        // Check current user id1 or id in conversation
        if (isset($conv[0]["user_id1_conv"])) {
            // if conversation exist
            if ($conv[0]["user_id1_conv"] == USER_ID) {
                $sql = "update conversations set hide_messages_u1 = 1";
            } else {
                $sql = "update conversations set hide_messages_u2 = 1";
            }
            $result = $this->config_Class->query($sql);
        } else {
            // if conversation not exist
            $sql = "insert into conversations (user_id1_conv, user_id2_conv, hide_messages_u1) values (:user_id, :to_user_id, 1)";
            $result = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id));
        }
        if ($result) {
           return array("result" => true);
        } else {
           return array("result" => false);
        }
    }

    public function addNewV2Post($text="",$img="",$forceTopic=0,$asMessage=0){
        $text=$this->config_Class->escapeOddChars($text);
        $text=$this->config_Class->processPostText($text);

        if($asMessage!=0 && $asMessage!=USER_ID){
            $sql="select * from profile where id_profile=:id limit 1";
            $resProfile = $this->config_Class->query($sql,array(":id"=>$asMessage));
            if($resProfile["result"]){
                if (!defined('MOBILE_REQUEST')) {
                    $text="<a class=\"inPostMention\" href=\"".WEB_URL.$resProfile[0]["username_profile"]."\">@".$resProfile[0]["username_profile"]."</a> ".$text;                     
                }
            }
        }

        if($asMessage!=0 && isset($resProfile["result"])){
            $sql="insert into post (text_post,id_profile_post,date_post,share_with_post) VALUES (:text,:user,now(),:id)";
            $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text,":id"=>$resProfile[0]["id_profile"]));
         }else{
            $sql="insert into post (text_post,id_profile_post,date_post) VALUES (:text,:user,now())";
            $res = $this->config_Class->query($sql,array(":user"=>USER_ID,":text"=>$text));
         }



        if($res){

            $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
            $res = $this->config_Class->query($sql,array(":user"=>USER_ID));

            if($res["result"]){

                if($asMessage!=0 && isset($resProfile["result"])){
                    $link=WEB_URL."post/".$res[0]["id_post"];
                    require_once(ENGINE_PATH.'class/message.class.php');
                    $messageClass=new Message();
                    $resMsg=$messageClass->post4User(USER_ID,$resProfile[0]["id_profile"],$link);
                }

                $this->findNewsTopic($res[0]["id_post"],'',$text,true);
                if($forceTopic!=0){
                    $this->addRelation($res[0]["id_post"],$forceTopic);
                }
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
                    $res[0]["image_post"] = $image;
                }
                return $res;
            }else{
                return false;
            }

        }else{
            return false;
        }

    }
    public function isBlockConversation($to_user_id) {
        $sql = "select id_conv from conversations where (user_id1_conv=:user_id and user_id2_conv=:to_user_id and status_conv = 'block') or (user_id2_conv=:user_id2 and user_id1_conv=:to_user_id2 and status_conv = 'block')";
        return $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
    }

    public function getAllConversations() {
        $conversations = array();
        $users = $this->getUsersID();
        if (!empty($users)) {
        $sql = "SELECT * FROM profile WHERE id_profile IN (".implode(',', $this->getUsersID()).")";
            $conversations = $this->config_Class->query($sql);
            foreach ($conversations as $key => $value) {
                $conv_to_user_id = $conversations[$key]["id_profile"];
                $conv = $this->isBlockConversation($conv_to_user_id);
                error_reporting(E_ALL ^ E_WARNING);
                $conversations[$key]["blocked"] = array();
                if(isset($conv[0]["id_conv"])) {
                    $conversations[$key]["blocked"] = "true";
                } else {
                    $conversations[$key]["blocked"] = "false";
                }
            }
            return  $conversations;
        } else {
            return array('result' => true);
        }
    }

    public function getCountUnreadMessagesForUser($to_user_id) {
        $sql = "SELECT count(id_post) as coun_unread_mess FROM post WHERE share_with_post =:to_user_id AND read_post = 0";
        $count_unread_user_messsages = $this->config_Class->query($sql, array(":to_user_id" => $to_user_id));
        return $count_unread_user_messsages[0]["coun_unread_mess"];
    }

    private function getUsersID() {
            $sql = "SELECT id_profile_post AS user_id FROM post WHERE share_with_post=:id";
            $res1 = $this->config_Class->query($sql, array(":id" => USER_ID));
            $this->addUsersIDs($res1);
            $sql = "SELECT share_with_post AS user_id FROM post WHERE share_with_post IS NOT NULL AND id_profile_post=:id";
            $res2 = $this->config_Class->query($sql, array(":id" => USER_ID));
            $this->addUsersIDs($res2);                
            return $this->user_id_array;
    }

    private function getUsersID2($user_id) {
        $sql = "SELECT id_profile_post AS user_id FROM post WHERE share_with_post=:id";
        $res1 = $this->config_Class->query($sql, array(":id" => $user_id));
        $this->addUsersIDs($res1);
        $sql = "SELECT share_with_post AS user_id FROM post WHERE share_with_post IS NOT NULL AND id_profile_post=:id";
        $res2 = $this->config_Class->query($sql, array(":id" => $user_id));
        $this->addUsersIDs($res2);
        return $this->user_id_array;
    }

    private function addUsersIDs($ids) {
        $result = $ids['result'];
        unset($ids['result']);
        if ($result > 0) {
            foreach ($ids as $key => $id) {
                if (!in_array($id['user_id'], $this->user_id_array))
                $this->user_id_array[] = $id['user_id'];
            }
        }
    }

    public function getConvMessages($to_user_id, $timestamp) {
        $sql = "SELECT * FROM post LEFT JOIN profile ON post.id_profile_post=profile.id_profile
                WHERE (id_profile_post=:user_to AND share_with_post=:user_from) OR (id_profile_post=:user_from2 AND share_with_post=:user_to2)".$this->timePostSQL($timestamp, 'date_post')." ORDER BY date_post DESC LIMIT ".$this->getLimit();
        return $this->config_Class->query($sql, array(":user_from" => USER_ID, ":user_to" => $to_user_id,":user_from2" => USER_ID, ":user_to2" => $to_user_id));
    }

    public function getConvMessages2($user_id, $to_user_id) {
        $sql = "SELECT * FROM post LEFT JOIN profile ON post.id_profile_post=profile.id_profile
                WHERE (id_profile_post=:user_to AND share_with_post=:user_from) OR (id_profile_post=:user_from2 AND share_with_post=:user_to2) ORDER BY date_post DESC LIMIT ".$this->getLimit();
        return $this->config_Class->query($sql, array(":user_from" => $user_id, ":user_to" => $to_user_id,":user_from2" => $user_id, ":user_to2" => $to_user_id));
    }

/*    private function getConditionSQL() {
            return " (id_profile_post=:user_to AND share_with_post=:user_from) OR (id_profile_post=:user_from2 AND share_with_post=:user_to2) ";
    }*/

    public function addNewV2SimplePost($title,$url,$text,$forceTopic=1){

        $imgPath=PUBLIC_HTML_PATH."img/post/";
        $image=$this->config_Class->uploadImage("imagem", $imgPath);
        if($image["image"]!=""){
            $image=$image["image"];
        }else{
            $image="";
        }

            $sql="insert into post (title_post,url_post,text_post,id_profile_post,image_post,date_post) VALUES (:title,:url,:text,:user,:image,now())";
            $res = $this->config_Class->query($sql,array(":user"=>'743706',":title"=>$title,":url"=>$url,":text"=>$text,":image"=>$image));



        if($res){

            $sql="select * from post where id_profile_post=:user order by id_post desc limit 1";
            $res = $this->config_Class->query($sql,array(":user"=>'743706'));

            if($res["result"]){

                if(isset($resProfile["result"])){
                    $link=WEB_URL."post/".$res[0]["id_post"];
                    require_once(ENGINE_PATH.'class/message.class.php');
                    $messageClass=new Message();
                    $resMsg=$messageClass->post4User('743706',$resProfile[0]["id_profile"],$link);
                }

                $this->findNewsTopic($res[0]["id_post"],'',$text.' '.$title,true);
                if($forceTopic!=0){
                    $this->addRelation($res[0]["id_post"],$forceTopic);
                }
                $this->updateSearchTable('newPost',$res);

                return $res;
            }else{
                return $res;
            }


        }else{
            return $res;
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

    public function getCountUnreadMessagesModel(){
        $sql="select count(share_with_post) as count_unread_messages from post where share_with_post=:user and read_post = 0";
        $result = $this->config_Class->query($sql,array(":user"=>USER_ID));
        return $result;
    }

    public function getCountUnreadMessagesInConversationModel($from_user){
        $sql="select count(share_with_post) as count_unread_messages from post where share_with_post=:user and id_profile_post=:from_user and read_post = 0";
        $result = $this->config_Class->query($sql,array(":from_user"=>$from_user, ":user"=>USER_ID));
        return $result[0]['count_unread_messages'];
    }

}