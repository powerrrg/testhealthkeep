<?php
class Profile extends Base{

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

    public function trackActivate(){
        $sql="update profile set tracking_profile=0 where id_profile=:id";
        return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function trackStart(){
        $sql="update profile set tracking_profile=2 where id_profile=:id";
        return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function trackDeactivate(){
        $sql="update profile set tracking_profile=1 where id_profile=:id";
        return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function findRecent($page=1){
        $sql="select profile.*
        from profile, post
        where id_profile_post=id_profile
        and type_profile=1
        and id_profile!=:id
        group by id_profile
        ORDER BY date_post DESC";
        $sql=$this->config_Class->getPagingQuery($sql,$page);
        return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function findPopular($page=1){
        $sql="select profile.*, count(id_topic_tf) as tot
        from profile,
        topic_follow
        where id_profile_tf=id_profile
        and type_profile=1
        and id_profile!=:id
        group by id_profile_tf
        ORDER BY tot DESC";
        $sql=$this->config_Class->getPagingQuery($sql,$page);
        return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function findPopularByBadges($page=1){
        $sql="select profile.*, sharing_profile+supportive_profile+helpful_profile+karma_profile as tot
        from profile
        where type_profile=1
        and id_profile!=:id
        ORDER BY tot DESC";
        $sql=$this->config_Class->getPagingQuery($sql,$page);
        return $this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function findSimilar($page=1){
        $sql="SELECT profile.*, count(tf.id_topic_tf) as tot
        FROM profile,
        topic_follow as tf
        left join topic_follow as tt on tf.id_topic_tf=tt.id_topic_tf
        where tf.id_profile_tf=id_profile
        and type_profile=1
        and id_profile!=:id2
        and tt.id_profile_tf=:id
        and tf.id_topic_tf IS NOT NULL
        group by tf.id_profile_tf
        ORDER BY tot DESC";
        $sql=$this->config_Class->getPagingQuery($sql,$page);
        return $this->config_Class->query($sql,array(":id"=>USER_ID,":id2"=>USER_ID));
    }

    public function updateBadge($badge,$user){
        if($badge=="sharing"){
            $sql="update profile set
            sharing_profile=(select count(id_post) from post where id_profile_post=:user group by id_profile_post)
            where id_profile=:id";
            return $this->config_Class->query($sql,array(":user"=>$user,":id"=>$user));
        }else if($badge=="supportive"){
            $sql="update profile set
            supportive_profile=(
            IFNULL((select count(id_pt) from post_thumb where id_profile_pt=:user group by id_profile_pt), 0)+
            IFNULL((select count(id_pct) from post_comment_thumb where id_profile_pct=:user2 group by id_profile_pct), 0) )
            where id_profile=:id";
            return $this->config_Class->query($sql,array(":user"=>$user,":user2"=>$user,":id"=>$user));
        }else if($badge=="helpful"){
            $sql="update profile set
            helpful_profile=(select count(id_pc) from post_comment where id_profile_pc=:user group by id_profile_pc)
            where id_profile=:id";
            return $this->config_Class->query($sql,array(":user"=>$user,":id"=>$user));
        }else if($badge=="karma"){

            $sql="select sum(thumb_up_post) as tot from post
            where id_profile_post=:id group by id_profile_post";
            $res1= $this->config_Class->query($sql,array(":id"=>$user));
            if($res1["result"]){
                $val1=$res1[0]["tot"];
            }else{
                $val1=0;
            }
            $sql="select sum(thumb_up_pc) as tot from post_comment
            where id_profile_pc=:id group by id_profile_pc";
            $res2= $this->config_Class->query($sql,array(":id"=>$user));
            if($res2["result"]){
                $val2=$res2[0]["tot"];
            }else{
                $val2=0;
            }
            $total=$val1+$val2;
            $sql="update profile set karma_profile=:tot where id_profile=:id";
            return $this->config_Class->query($sql,array(":id"=>$user,":tot"=>$total));
        }else{
            return false;
        }
    }

    public function updateDetails($dob,$country,$zip,$job,$gender,$bio=''){
        $bio=$this->config_Class->processPostText($bio);
        $sql="update profile set dob_profile=:dob, country_profile=:country, zip_profile=:zip, job_profile=:job, gender_profile=:gender,bio_profile=:bio where id_profile=:id";
        $res= $this->config_Class->query($sql,array(":dob"=>$dob,":country"=>$country,":zip"=>$zip,":job"=>$job,":gender"=>$gender,":bio"=>$bio,":id"=>USER_ID));
        if($res){
            $this->updateSearchTable('updateUser',USER_ID);
        }
        return $res;
    }
    
    public function updateProfile ($attributes, $img = '') {
        $sql = "update profile set";
        $first_attr = true;
        $params_array = array();

        foreach ($attributes as $key => $attribute) {
            if (in_array($key, array('zip_profile')) && strlen(trim($attribute)) == 0) {} else {
                if ($key == 'country_profile' && strlen(trim($attribute)) == 0) {
                    $attribute = NULL;
                }
                $params_array[":$key"] = $attribute;
                if (!$first_attr) $sql.=",";
                $sql.=" $key=:$key";
                $first_attr = false;
            }
        }
        $sql .= " where id_profile=:id_profile";
        $params_array[":id_profile"] = USER_ID;
        
        $res= $this->config_Class->query($sql, $params_array);

        if($res){
            if($img!="" && isset($_FILES[$img])){
                $imgPath=PUBLIC_HTML_PATH."img/profile/";
                $image=$this->config_Class->uploadImage($img, $imgPath);
                if($image["image"]!=""){
                    $image=$image["image"];
                }else{
                    $image="";
                }
                $this->config_Class->query("update profile set image_profile='$image' where id_profile=".USER_ID);
            }
            $this->updateSearchTable('updateUser',USER_ID);
        }
        return $this->getById(USER_ID);
    }
    
    public function checkZip($zip) {
        $sql="select zip from zipcode where zip=:zip";
        $result = $this->config_Class->query($sql,array(":zip"=>$zip));
        return $result['result'];
    }

    public function updateBio($bio){
        $sql="update profile set bio_profile=:bio where id_profile=:id";
        return $this->config_Class->query($sql,array(":bio"=>$bio,":id"=>USER_ID));
    }

    public function updateWeigth($weight){
        $sql="update profile set weight_profile=:weight where id_profile=:id";
        return $this->config_Class->query($sql,array(":weight"=>$weight,":id"=>USER_ID));
    }

    public function updateWeightHeight($weight,$feets,$inches){
        $sql="update profile set weight_profile=:weight, feet_profile=:feet, inch_profile=:inch where id_profile=:id";
        return $this->config_Class->query($sql,array(":weight"=>$weight,":feet"=>$feets,":inch"=>$inches,":id"=>USER_ID));
    }

    public function updateDocsDetails($name,$zip){
        $sql="update profile set name_profile=:name, zip_profile=:zip where id_profile=:id";
        $res = $this->config_Class->query($sql,array(":name"=>$name,":zip"=>$zip,":id"=>USER_ID));
        if($res){
            $this->updateSearchTable('updateUser',USER_ID);
        }
        return $res;
    }

    public function updateName($name){
        $sql="update profile set name_profile=:name where id_profile=:id";
        $res = $this->config_Class->query($sql,array(":name"=>$name,":id"=>USER_ID));
        if($res){
            $this->updateSearchTable('updateUser',USER_ID);
        }
        return $res;
    }


    public function newName($user_id, $name){
        $sql = 'update profile set name_profile=:name where id_profile=:id';
        $res = $this->config_Class->query($sql, array(':name' => $name, ':id' => $user_id));
        if ($res) {
            $this->updateSearchTable('updateUser', USER_ID);
        }
        return $res;
    }

    public function newDeviceToken($token, $user_id){
        $sql = 'update profile set token_profile=:token where id_profile=:id';
        $res = $this->config_Class->query($sql, array(':token' => $token, ':id' => $user_id));
        if ($res) {
            $this->updateSearchTable('updateUser', $user_id);
        }
        return $res;
    }

    public function saveStep($step){

        $sql="select steps_profile from profile where id_profile=:id limit 1";
        $res = $this->config_Class->query($sql,array(":id"=>USER_ID));

        if($res["result"]){
            if(!preg_match('/'.$step.'/', $res[0]['steps_profile'])){
                $sql="update profile set steps_profile='".$res[0]['steps_profile'].$step."' where id_profile=:id";
                return $this->config_Class->query($sql,array(":id"=>USER_ID));
            }
        }

    }

    public function step1($dob,$country,$zip,$weight,$feets,$inches,$job){

        $sql="update profile set
        country_profile=:country, zip_profile=:zip, dob_profile=:dob, weight_profile=:weight,
        feet_profile=:feet, inch_profile=:inch, job_profile=:job, updated_profile=now()
        where id_profile=:id";
        return $this->config_Class->query($sql,array(
            ":country"=>$country,":zip"=>$zip,":dob"=>$dob,":weight"=>$weight,
            ":feet"=>$feets,":inch"=>$inches,":job"=>$job,":id"=>USER_ID));

    }

    public function nameSingular($type){

        if($type=="1"){
            return "User";
        }else if($type=="2"){
            return "Doctor";
        }else if($type=="3"){
            return "Facility";
        }else if($type=="4"){
            return "News Source";
        }else{
            return false;
        }

    }

    public function getAutoCompleteDoctor($input){

        $input=str_replace(" ", "%", $input);

        $sql="select * from doctor, profile left join zipcode on zip=zip_profile where npi_profile=npi_doctor and name_profile LIKE :name and type_profile=2 limit 10";
        return $this->config_Class->query($sql,array(":name"=>"%$input%"));

    }

    public function getByUsername($username,$notMe=false){

        $sql="select * from profile where username_profile=:uname";
        if($notMe){
            $sql.=" and id_profile!='".USER_ID."'";
        }
        $sql.=" limit 1";
        return $this->config_Class->query($sql,array(":uname"=>$username));

    }

    public function lookForNewsSource($username){

        $sql="select * from profile where name_profile=:uname limit 1";
        return $this->config_Class->query($sql,array(":uname"=>$username));

    }

    public function getUserDoctorsFollowed($id){
        $sql="select * from profile, profile_follow where type_profile=2 and id_profile_pf=:id and id_follow_pf=id_profile limit 10";
        return $this->config_Class->query($sql,array(":id"=>$id));
    }

    public function getAllDoctors($page=1){
        $sql="select * from profile
        left join zipcode on zip_profile=zip,doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy

        where type_profile=2 and npi_profile=npi_doctor order by created_profile desc";
        $sql=$this->config_Class->getPagingQuery($sql,$page,30);
        return $this->config_Class->query($sql,array());
    }

    public function getAllDoctorsSearch($name,$state,$city,$taxo,$page=1){

        $sqlWhere="";
        include(ENGINE_PATH."html/inc/common/usStates.php");

        if($state!=""){
            $matches  = preg_grep ('/'.$state.'/i', $usStates);
            if(count($matches)>0){
                $stateIni=" and (";
                $sqlWhere.=$stateIni;
                foreach($matches as $key=>$value){
                    if($sqlWhere!=$stateIni){
                        $sqlWhere.=" or ";
                    }
                    $sqlWhere.="(state='".$key."' or state_doctor='".$key."')";
                }
                $sqlWhere.=")";
            }
        }

        if($name!=''){
            $sqlWhere.=" and name_profile LIKE '%".$name."%'";
        }

        if($city!=''){
            $sqlWhere.=" and (city_doctor LIKE '%".$city."%' OR city LIKE '%".$city."%')";
        }

        if($taxo!=''){
            $sqlWhere.=" and name_taxonomy LIKE '%".$taxo."%'";
        }

        $sql="select * from profile
        left join zipcode on zip_profile=zip,doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy
        where type_profile=2 and npi_profile=npi_doctor $sqlWhere order by created_profile desc";
        $sql=$this->config_Class->getPagingQuery($sql,$page,30);
        return $this->config_Class->query($sql,array());
    }

    public function getAllDoctorsFromState($state,$page=1){
        $sql="select * from profile
        left join zipcode on zip_profile=zip,doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy
        where type_profile=2 and npi_profile=npi_doctor and (state_doctor=:state or state=:state1) order by created_profile desc";
        $sql=$this->config_Class->getPagingQuery($sql,$page,30);
        return $this->config_Class->query($sql,array(":state"=>$state,":state1"=>$state));
    }

    public function getAllDoctorsWithTaxonomy($taxo,$page=1){
        $sql="select * from profile
        left join zipcode on zip_profile=zip,doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy
        where type_profile=2 and npi_profile=npi_doctor and taxonomy_code_doctor=:taxo order by created_profile desc";
        $sql=$this->config_Class->getPagingQuery($sql,$page,30);
        return $this->config_Class->query($sql,array(":taxo"=>$taxo));
    }

    public function getAllDoctorsFromcity($state,$city,$page=1){
        $sql="select * from (select * from taxonomy, zipcode, profile, doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy
        where state=:state and city LIKE :city and zip_profile=zip,doctor) as aa
        UNION ALL
        select * from (select * from taxonomy, profile left join zipcode on zip_profile=zip,doctor, doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy
        where state_doctor=:state1 and city_doctor LIKE :city1 and npi_profile=npi_doctor) as bb
        ";

        $sql="select * from profile
        left join zipcode on zip_profile=zip,doctor
        left join taxonomy on taxonomy_code_doctor=code_taxonomy
        where type_profile=2 and npi_profile=npi_doctor and (state_doctor=:state or state=:state1) and (city_doctor LIKE :city or city LIKE :city1) order by created_profile desc";
        $sql=$this->config_Class->getPagingQuery($sql,$page,30);
        return $this->config_Class->query($sql,array(":state"=>$state,":state1"=>$state,":city"=>$city,":city1"=>$city));
    }

    public function getRecommendedDoctors($howmany=3){
        //$sql="select * from profile where type_profile='2' order by rand() limit $howmany";
        //FASTER RANDOM
        $sql="select * from profile AS r1 JOIN
                (SELECT (RAND() *
                     (SELECT MAX(id_profile)
                        FROM profile)) AS id)
        AS r2 where r1.id_profile >= r2.id and type_profile='2' ORDER BY r1.id_profile ASC limit 3";
        return $this->config_Class->query($sql,array());
    }

    public function getNewProfiles(){

        $sql="select * from profile where `created_profile` !=  '0000-00-00 00:00:00' order by created_profile desc limit 50";
        return $this->config_Class->query($sql,array());

    }

     public function getById($id){

        $sql="select * from profile where id_profile=:id limit 1";
        return $this->config_Class->query($sql,array(":id"=>$id));

    }

    public function getByIdWithTopics($id) { //API
        $profile = $this->getById($id);

        if ($profile['result']) {
            $sql = 'select topic.* from topic_follow, topic
            where id_topic_tf=id_topic and id_profile_tf=:user order by gnews_topic desc';
            $profile['topic'] = $this->config_Class->query($sql, array(':user'=>$id));
        }
        return $profile;
    }

    public function getByType($type){

        $sql="select * from profile where type_profile=:type order by name_profile";
        return $this->config_Class->query($sql,array(":type"=>$type));

    }

    public function numberUsersType($type){

        $total=0;
        $sql="select count(id_profile) as total
        from profile,user where id_user=id_profile and type_profile=:type";
        $res = $this->config_Class->query($sql,array(":type"=>$type));

        if($res["result"]){
            $total=$res[0]["total"];
        }

        return $total;
    }

    public function getByIdComplete($id){

        $sql="select *, TIME_TO_SEC(TIMEDIFF(now(),forgot_date_user)) as forgot_date_diff
        from profile,user where id_user=id_profile and id_profile=:id limit 1";
        return $this->config_Class->query($sql,array(":id"=>$id));

    }

    public function countFollowers($id){

        $sql="select count(id_follow_pf) as total from profile_follow where id_follow_pf=:id group by id_follow_pf";
        return $this->config_Class->query($sql,array(":id"=>$id));

    }

    public function listFollowers($id){

        $sql="select * from profile_follow,profile
            left join country on country_profile=iso2
            left join zipcode on zip_profile=zip
            left join doctor on npi_profile=npi_doctor
            left join taxonomy on taxonomy_code_doctor=code_taxonomy
            where id_profile=id_profile_pf and id_follow_pf=:id order by id_profile desc";
        return $this->config_Class->query($sql,array(":id"=>$id));

    }


    public function countFollowing($id){

        $sql="select count(id_profile_pf) as total from profile_follow where id_profile_pf=:id group by id_profile_pf";
        return $this->config_Class->query($sql,array(":id"=>$id));

    }

    public function listFollowing($id){

        $sql="select * from profile_follow, profile
            left join country on country_profile=iso2
            left join zipcode on zip_profile=zip
            left join doctor on npi_profile=npi_doctor
            left join taxonomy on taxonomy_code_doctor=code_taxonomy
            where id_profile=id_follow_pf and id_profile_pf=:id order by id_profile desc";
        return $this->config_Class->query($sql,array(":id"=>$id));

    }

    public function follow($id){

        if(USER_ID==$id){
            return false;
        }

        $res=$this->doIFollow($id);

        if($res["result"]){
            return true;
        }

        $sql="insert into profile_follow (id_profile_pf,id_follow_pf) VALUES (:me,:id)";
        $resFollow=$this->config_Class->query($sql,array(":me"=>USER_ID,":id"=>$id));

        if($resFollow){
            require_once(ENGINE_PATH.'class/message.class.php');
            $messageClass=new Message();
            $resMsg=$messageClass->follow(USER_ID,$id);
            return true;
        }else{
            return false;
        }
    }

    public function unfollow($id){
        $sql="delete from profile_follow where id_profile_pf=:me and id_follow_pf=:id";
        return $this->config_Class->query($sql,array(":me"=>USER_ID,":id"=>$id));
    }

    public function doIFollow($id){
        $sql="select * from profile_follow where id_profile_pf=:me and id_follow_pf=:id limit 1";
        return $this->config_Class->query($sql,array(":me"=>USER_ID,":id"=>$id));
    }

    public function deleteAvatar(){

        $sql="select * from profile where id_profile=:id limit 1";
        $res = $this->config_Class->query($sql,array(":id"=>USER_ID));

        if($res["result"]){

            $imgPath=PUBLIC_HTML_PATH."img/profile/";

            if($res[0]["image_profile"]!=""){

                @unlink($imgPath."org/".$res[0]["image_profile"]);
                @unlink($imgPath."med/".$res[0]["image_profile"]);
                @unlink($imgPath."tb/".$res[0]["image_profile"]);
            }

            $sql="update profile set image_profile=:img where id_profile=:id";
            $res = $this->config_Class->query($sql,array(":img"=>'',":id"=>USER_ID));
            if($res){
                $this->updateSearchTable('updateUser',USER_ID);
            }
            return $res;

        }else{
            return false;
        }


    }

    public function changeAvatar($inputName){

        $sql="select * from profile where id_profile=:id limit 1";
        $res = $this->config_Class->query($sql,array(":id"=>USER_ID));

        if($res["result"]){

            $imgPath=PUBLIC_HTML_PATH."img/profile/";
            $image=$this->config_Class->uploadImage($inputName, $imgPath);

            if($image["image"]==""){
                return false;
            }

            if($res[0]["image_profile"]!=""){

                @unlink($imgPath."org/".$res[0]["image_profile"]);
                @unlink($imgPath."med/".$res[0]["image_profile"]);
                @unlink($imgPath."tb/".$res[0]["image_profile"]);
            }

            $sql="update profile set image_profile=:img where id_profile=:id";
            $res = $this->config_Class->query($sql,array(":img"=>$image["image"],":id"=>USER_ID));
            if($res){
                $this->updateSearchTable('updateUser',USER_ID);
            }
            return $res;

        }else{
            return false;
        }


    }

    public function getByNPI($npi){

        $sql="select * from profile where npi_profile=:npi limit 1";
        return $this->config_Class->query($sql,array(":npi"=>$npi));

    }

    public function addNewDoc($name,$phone,$npi){

        if(strlen($phone)<10){
            return array("result"=>false,"error"=>"Invalid phone number");
        }

        if(strlen($name)<5){
            return array("result"=>false,"error"=>"Invalid name");
        }

        $resNPI=$this->getByNPI($npi);

        if(!$resNPI["result"]){
            return array("result"=>false,"error"=>"Invalid npi");
        }

        $sql="update profile set name_profile=:name,phone_profile=:phone,created_profile=now(),updated_profile=now() where id_profile=:id";
        $res=$this->config_Class->query($sql, array(":name"=>$name,":phone"=>$phone,":id"=>$resNPI[0]["id_profile"]));

        if(!$res){
            return array("result"=>false,"error"=>"There was an error while creating your profile. Please try again or contact us.");
        }else{
            return $resNPI;
        }


    }

    public function copyAvatar($image){

        $sql="select * from profile where id_profile=:id limit 1";
        $res = $this->config_Class->query($sql,array(":id"=>USER_ID));

        if($res["result"]){
            $imgPath=PUBLIC_HTML_PATH."img/profile/";
            $image=$this->config_Class->uploadImageURL(WEB_URL."inc/img/avatar/".$image,$imgPath);

            if($image["image"]==""){
                return false;
            }

            if($res[0]["image_profile"]!=""){

                @unlink($imgPath."org/".$res[0]["image_profile"]);
                @unlink($imgPath."med/".$res[0]["image_profile"]);
                @unlink($imgPath."tb/".$res[0]["image_profile"]);
            }

            $sql="update profile set image_profile=:img where id_profile=:id";
            $res = $this->config_Class->query($sql,array(":img"=>$image["image"],":id"=>USER_ID));
            if($res){
                $this->updateSearchTable('updateUser',USER_ID);
            }
            return $res;
        }else{
            return false;
        }

    }

    public function newAvatar($user_id, $image) {
        $sql = 'select * from profile where id_profile=:id limit 1';
        $res = $this->config_Class->query($sql, array(':id'=>$user_id));

        if ($res['result']) {
            $imgPath = PUBLIC_HTML_PATH.'img/profile/';
            $image = $this->config_Class->uploadImageURL(WEB_URL.'inc/img/avatar/'.$image, $imgPath);

            if ($image['image'] == '') {
                return false;
            }

            if ($res[0]['image_profile'] != '') {
                @unlink($imgPath.'org/'.$res[0]['image_profile']);
                @unlink($imgPath.'med/'.$res[0]['image_profile']);
                @unlink($imgPath.'tb/'.$res[0]['image_profile']);
            }

            $sql = 'update profile set image_profile=:img where id_profile=:id';
            $res = $this->config_Class->query($sql, array(':img'=>$image['image'], ':id'=>$user_id));
            if ($res) {
                $this->updateSearchTable('updateUser', $user_id);
            }
            return $res;
        } else {
            return false;
        }
    }

    public function updateGender($gender){
        $sql="update profile set gender_profile=:gender where id_profile=:id";
        return $this->config_Class->query($sql, array(":gender"=>$gender,":id"=>USER_ID));
    }

    public function updateUsername($username){
        $username = preg_replace("/[^a-zA-Z0-9\_\-]/", "", strtolower($username));
        if(strlen($username)<1){
            return array("result"=>false,"error"=>"Username needs to have more than 5 characters");
        }
        $sql="update profile set username_profile=:uname where id_profile=:id";
        $res = $this->config_Class->query($sql, array(":uname"=>$username,":id"=>USER_ID));
        if($res){
            $this->updateSearchTable('updateUser',USER_ID);
        }
        return $res;
    }

    public function addNewPro($name,$phone,$type){

        if(strlen($name)<5){
            return array("result"=>false,"error"=>"Invalid name");
        }

        $username=$this->config_Class->safeUri($name);


        if($type=="doc"){
            $type=2;
        }else if($type=="fac"){
            $type=3;
        }else if($type=="news"){
            $type=4;
            $sql="select * from blacklist where url_bl='$name' limit 1";
            $res=$this->config_Class->query($sql, array());
            if($res["result"]){
            return array("result"=>false,"error"=>"User in Black List ".$name);
            }
        }else{
            return array("result"=>false,"error"=>"Invalid registration type");
        }

        if(strlen($phone)<10){
            return array("result"=>false,"error"=>"Invalid phone number");
        }

        $res=$this->getByUsername($username);

        if($res["result"]){
            $username=md5(rand() * time());
        }

        $sql="INSERT INTO `profile`
            (`username_profile`, `name_profile`,`phone_profile`,`type_profile`, `created_profile`, `updated_profile`)
            VALUES (:uname,:name,:phone,:type,now(),now())";
        $res=$this->config_Class->query($sql, array(":uname"=>$username,":name"=>$name,":phone"=>$phone,":type"=>$type));

        if(!$res){
            return array("result"=>false,"error"=>"There was an error while creating your profile. Please try again or contact us.");
        }else{
            $res=$this->getByUsername($username);

            if($res["result"]){
                $this->updateSearchTable('newUser',$res);
                return $res;
            }else{
                return array("result"=>false,"error"=>"There was an error while creating your profile. Please try again or contact us.");
            }
        }


    }

    public function addNew($username, $gender) {
        $username = preg_replace("/[^a-zA-Z0-9\_\-]/", '', strtolower($username));
        if (strlen($username) < 1) {
            return array('result'=>false, 'error'=>'Username needs to have more than 5 characters');
        }

        if ($gender!='m' && $gender!='f' && $gender!='') {
            return array('result' => false, 'error'=>'Gender not set');
        }

        $res = $this->getByUsername($username);

        if ($res['result']) {
            return array('result'=>false, 'error'=>'Username already exists');
        }

        $sql = 'INSERT INTO `profile`
            (`username_profile`, `gender_profile`, `created_profile`, `updated_profile`)
            VALUES (:uname,:gender,now(),now())';
        $res=$this->config_Class->query($sql, array(':uname'=>$username, ':gender'=>$gender));

        if (!$res) {
            return array('result'=>false, 'error'=>'There was an error while creating your profile. Please try again or contact us.');
        } else {
            $res = $this->getByUsername($username);

            if ($res['result']) {
                $this->updateSearchTable('newUser', $res);
                return $res;
            } else {
                return array('result'=>false, 'error'=>'There was an error while creating your profile. Please try again or contact us.');
            }
        }
    }
}