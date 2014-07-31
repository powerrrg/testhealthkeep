<?php
require_once(ENGINE_PATH."class/base.class.php");
class User extends Base {
    private $config_Class;

    function __construct()
    {
        $this->config_Class=new Config();
    }

    public function getByEmail($email){
        $sql="select * from user where email_user=:email limit 1";
        return $this->config_Class->query($sql,array(":email"=>$email));
    }

    public function canChangePassword($url,$token){

       $sql="select *, TIME_TO_SEC(TIMEDIFF(now(),forgot_date_user)) as forgot_date_diff 
       from user, profile where id_user=id_profile and username_profile=:url and forgot_token_user=:token limit 1";
       $res=$this->config_Class->query($sql,array(":url"=>$url,":token"=>$token));
       if(!$res["result"]){
           return false;
       }
       if($res[0]["forgot_date_diff"]<86400){
            return true;
       }else{
           return false;
       }

    }

    public function toggleTour($v){
        $sql="update user set tour_user=:v where id_user=:id";
        return $this->config_Class->query($sql,array(":v"=>$v,":id"=>USER_ID));
    }

    public function requestPassword($id) {

        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass = new Profile();

        $resProfile = $profileClass->getByIdComplete($id);
        if (!$resProfile['result']) {
            return false;
        }
        if ($resProfile[0]['forgot_token_user'] != '' && $resProfile[0]['forgot_date_diff'] < 600) {
            return 'time';
        }

        $token = sha1(microtime(true).mt_rand(10000, 90000));
        $sql = 'update user set forgot_token_user=:token, forgot_date_user=now() where id_user=:id';
        $res = $this->config_Class->query($sql, array(':token'=>$token, ':id'=>$id));

        if ($res) {
            require_once(ENGINE_PATH.'starter/mail.php');

            $mail->AddReplyTo($fromEmail, $fromEmailName);

            $mail->Subject = 'HealthKeep - New password request';

            //$mail->AltBody    = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test
            $msg = 'Hello '.$this->config_Class->name($resProfile)."<br /><br />We are sending you this email because a password request was made for your <a href=\"".WEB_URL."\">HealthKeep</a> account.<br ><br />If it was not you that made this request, please ignore this email.<br /><br />If you want to set a new password please visit: ".WEB_URL."pw.php?url=".$resProfile[0]["username_profile"]."&token=".$token."<br /><br />";
            $mail->MsgHTML($msg);

            $name = $this->config_Class->name($resProfile);
            $email = $resProfile[0]['email_user'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            $mail->AddAddress($email, $name);

            if (!$mail->Send()) {
              return false;
            } else {
              return 'ok';
            }
        }
    }

    public function getTotalCount(){
        $sql="select count(id_user) as total from user";
        return $this->config_Class->query($sql,array());
    }

    public function getAll($real=false){
        if($real){
            $sql="select * from user,profile where id_user=id_profile and type_profile<3";
            return $this->config_Class->query($sql,array());
        }else{
            $sql="select * from user,profile where id_user=id_profile";
            return $this->config_Class->query($sql,array());
        }
    }

    public function getById($id){
        $sql="select * from user where id_user=:id limit 1";
        return $this->config_Class->query($sql,array(":id"=>$id));
    }

    private function addCommonStart($email,$password){
        if(!filter_var( $email, FILTER_VALIDATE_EMAIL )){
            return array("result"=>false,"error"=>"Invalid email address");
        }
        if(strlen($password)<5){
            return array("result"=>false,"error"=>"Password needs to have more than 5 characters");
        }
        $res=$this->getByEmail($email);
        if($res["result"]){
            return array("result"=>false,"error"=>"Email adress already registered","emailDup"=>true);
        }
        return array("result"=>true);

    }

    public function updatePassword($id, $password){
        $res=$this->getById($id);
        if(!$res["result"]){
            return false;
        }

        $password=$this->doPassword($password);

        $sql="update user set password_user=:pw,forgot_token_user='',forgot_date_user='0000-00-00 00:00:00' where id_user=:id";
        return $this->config_Class->query($sql,array(":id"=>$id,":pw"=>$password));
    }

    public function saveNewPassword($id, $password){

        $resPw=$this->updatePassword($id, $password);

        if(!$resPw){
            return false;
        }

        $cookie=$this->setCookie();
        $session=$this->setSession($id);

        $sql="update user set session_user=:session, cookie_user=:cookie, last_login_user=now()
            where id_user=:id";
        $res=$this->config_Class->query($sql,
            array(":session"=>$session,":cookie"=>$cookie,":id"=>$id));

        return $res;

    }

    private function addCommonEndAuto($resProfile,$email,$password){

        $password=$this->doPassword($password);

        $sql="INSERT INTO `user`
            (`id_user`, `email_user`, `password_user`)
            VALUES (:id, :email,:password)";
        $res=$this->config_Class->query($sql,array(":id"=>$resProfile[0]["id_profile"],":email"=>$email,":password"=>$password));

        if(!$res){
            return array("result"=>false,"error"=>"Something really odd happened. Please try again or contact us!");
        }

        $res=$this->getByEmail($email,true);

        if(!$res["result"]){
            return array("result"=>false,"error"=>"Something strange happened. Please try to login or contact us!");
        }

        return array("result"=>true);
    }

    private function addCommonEnd($resProfile,$email,$password){

        $password=$this->doPassword($password);

        $sql="INSERT INTO `user`
            (`id_user`, `email_user`, `password_user`)
            VALUES (:id, :email,:password)";
        $res=$this->config_Class->query($sql,array(":id"=>$resProfile[0]["id_profile"],":email"=>$email,":password"=>$password));

        if(!$res){
            return array("result"=>false,"error"=>"Something really odd happened. Please try again or contact us!");
        }

        $res=$this->getByEmail($email,true);

        if(!$res["result"]){
            return array("result"=>false,"error"=>"Something strange happened. Please try to login or contact us!");
        }

        $cookie=$this->setCookie();
        $session=$this->setSession($res[0]["id_user"]);
        $_SESSION["mx_signup"]=1;
        $id_user = $res[0]["id_user"];
        $sql="update user set session_user=:session, cookie_user=:cookie, last_login_user=now()
            where id_user=:id";
        $res=$this->config_Class->query($sql,
            array(":session"=>$session,":cookie"=>$cookie,":id"=>$id_user));

        $resEmail=$this->sendEmailValidation($email,$resProfile);

        return array("result"=>true, "user_id"=>$id_user);
    }

    public function sendEmailValidation($email,$resProfile){

        $token=sha1(microtime(true).mt_rand(10000,90000));

        $sql="update user set token_email_user=:token where id_user=:id";
        $res=$this->config_Class->query($sql,array(":token"=>$token,":id"=>$resProfile[0]["id_profile"]));

        include(ENGINE_PATH."starter/mail.php");

        $mail->Subject    = "Welcome to HealthKeep";

        //$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        //CANNOT HAVE NAME BECAUSE THE USERNAME AUTOGENERATED WOULD LOOK ODD
        //$name=$this->config_Class->name($resProfile);

        $msg="Hello,<br /><br />Welcome to HealthKeep!<br /><br />For you to be able to enjoy all that HealthKeep has to offer we need to validate your email.<br /><br />Please, <a href=\"".WEB_URL."confirmEmail.php?t=$token\">click here to confirm this email address</a>.<br /><br />We really want to help you get the most out of your HealthKeep experience. Be sure to customize your profile adding all of your meds, symptoms, diagnoses and doctors. Also share what is going on with your health as often as possible. You could keep it as a diary to share with your doctor, and you will get feedback from the HealthKeep community.<br /><br />Please check back frequently to read feedback on your experiences, see experiences from others, and get the latest news relevant to your health.<br /><br />We are very interested in your feedback and suggestions, so feel free to email us!<br /><br />-HealthKeep Team";

        $mail->MsgHTML($msg);

        $mail->AddAddress($email, "HealthKeep User");

        if(!$mail->Send()) {
          return false;
        } else {
          return true;
        }

        include(ENGINE_PATH."class/mc/mcAPI.php");
        include(ENGINE_PATH."class/mc/MCAPI.class.php");

        $api = new MCAPI(mcAPI);

        // grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
        // Click the "settings" link for the list - the Unique Id is at the bottom of that page.
        $list_id = mcNewsLetter;



        if($api->listSubscribe($list_id, $email, array("UID"=>$resProfile[0]["id_profile"],"UNAME"=>$resProfile[0]["username_profile"],"RNAME"=>$this->config_Class->name($resProfile),"UTYPE"=>$resProfile[0]["type_profile"]),'html',true,true,true,false) === true) {
            // It worked!
            $resMC= array("result"=>'ok',"text"=>'Success! Check your email to confirm sign up.');
        }else{
            // An error ocurred, return error message
            $resMC= array("result"=>'error',"text"=>$api->errorMessage);
        }

           return $resMC;
    }

    public function validateEmail(){
        $sql="update user set token_email_user='', confirmed_email_user='1' where id_user=:id";
        $res=$this->config_Class->query($sql,array(":id"=>USER_ID));
    }

    public function addNew($username, $email, $password, $gender) {
        $res = $this->addCommonStart($email, $password);

        if (!$res['result']) {
            return $res;
        }

        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass = new Profile();
        $res = $profileClass->addNew($username, $gender);

        if (!$res['result']) {
            return $res;
        }

        $resEnd = $this->addCommonEnd($res, $email, $password);

        return $resEnd;
    }
    
    public function getBySocial($field_name, $social_id) {
        $sql = 'select * from user where '.$field_name.'=:social_id limit 1';
        return $this->config_Class->query($sql, array(':social_id' => $social_id));
    }

    public function addNewSocial($social_id, $field_name, $gender) {
        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass = new Profile();
        $username = $social_id;
        $resProfile = $profileClass->addNew($username, $gender);

        if (!$resProfile['result']) {
            return $resProfile;
        }
        $sql = "INSERT INTO `user`
            (`id_user`, `".$field_name."`, `email_user`, last_login_user)
            VALUES (:id, :social_id, :email, now())";
        $res = $this->config_Class->query($sql,array(':id'=>$resProfile[0]['id_profile'], ':social_id'=>$social_id, 'email'=>$social_id));
        if (!$res) {
            return array('result' => false, 'error' => 'Something really odd happened. Please try again or contact us!');
        }

        return array('result'=>true, 'user_id'=>$resProfile[0]['id_profile']);
    }

    public function addNewDoc($name,$email,$password,$phone,$npi){

        $res = $this->addCommonStart($email,$password);

        if(!$res["result"]){
            return $res;
        }

        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass=new Profile();
        $res=$profileClass->addNewDoc($name,$phone,$npi);

        if(!$res["result"]){
            return $res;
        }

        $sql="update doctor set claimed_doctor=1 where npi_doctor=:npi";
        $resDoc=$this->config_Class->query($sql,array(":npi"=>$npi));

        return $this->addCommonEnd($res,$email,$password);

    }

    public function addNewPro($name,$email,$password,$phone,$type){

        $res = $this->addCommonStart($email,$password);

        if(!$res["result"]){
            return $res;
        }

        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass=new Profile();
        $res=$profileClass->addNewPro($name,$phone,$type);

        if(!$res["result"]){
            return $res;
        }

        return $this->addCommonEnd($res,$email,$password);

    }

    public function addNewProAuto($name,$email,$password,$phone,$type){

        $res = $this->addCommonStart($email,$password);

        if(!$res["result"]){
            return $res;
        }

        require_once(ENGINE_PATH.'class/profile.class.php');
        $profileClass=new Profile();
        $res=$profileClass->addNewPro($name,$phone,$type);

        if(!$res["result"]){
            return $res;
        }

        return $this->addCommonEndAuto($res,$email,$password);

    }
    
    public function deleteUser($user_id) {
        $sql = "select * from user where id_user=:id_user limit 1";
        $res = $this->config_Class->query($sql,array(':id_user' => $user_id));
        if ($res['result']) {
            $sql = "delete from user where id_user=:id_user";
            $res = $this->config_Class->query($sql,array(':id_user' => $user_id));
            return array('result'=>$res);
        } else {
            return array('result'=>false, 'error'=>'User with this id_user not found.');            
        }
    }

    public function doLogin($email, $password, $token = NULL) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('result'=>false, 'error'=>'Parameter email is not valid.');
        }

        if (strlen($password) < 5) {
            return array('result'=>false, 'error'=>'Parameter password is not valid.');
        }

        $res = $this->getByEmail($email);

        if (!$res['result']) {
            return array('result'=>false, 'error'=>'There is no such email.');
        }

        $password = $this->doPassword($password);

        $sql = 'select * from user where email_user=:email and password_user=:password limit 1';
        $res = $this->config_Class->query($sql, array(':email'=>$email, ':password'=>$password));

        if (!$res['result']) {
            return array('result'=>false, 'error'=>'The password is wrong.');
        }

        $this->realDoLogin($res[0]['id_user']);
        $this->userLogAndRemember($res[0]['id_user']);
        if ($token != NULL) {
            $this->getProfileClass()->newDeviceToken($token, $res[0]['id_user']);
        }
        return array('result'=>true, 'user_id'=>$res[0]['id_user']);
    }

    private function realDoLogin($id){

        $cookie=$this->setCookie();
        $session=$this->setSession($id);

        $sql="update user set session_user=:session, cookie_user=:cookie, last_login_user=now()
            where id_user=:id";
        $resUpdate=$this->config_Class->query($sql,
            array(":session"=>$session,":cookie"=>$cookie,":id"=>$id));

    }

    public function logoutModel($id){
        $sql="update profile set token_profile = '' where id_profile=:id";
        $result = $this->config_Class->query($sql, array(":id"=>$id));
        return array("result" => $result);
    }

    private function getUserAgentDetails(){
        require_once(ENGINE_PATH.'class/useragent_details/class.browser.php');
        $browserClass=new Browser();

        require_once(ENGINE_PATH.'class/useragent_details/class.os.php');
        $osClass=new OS();

        $ctx=stream_context_create(array('http'=>
            array(
                'timeout' => 2 // 2 seconds
            )
        ));

        $ip=$this->config_Class->getRealIpAddr();

        if($geo=@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip,false,$ctx)){
            $geo = unserialize($geo);
        }else{
            $geo = array('geoplugin_city' => '', 'geoplugin_region' => '', 'geoplugin_countryCode' => '',
                'geoplugin_countryName' => '', 'geoplugin_latitude' => '', 'geoplugin_longitude' => '');
        }
        $browser=array("name"=>$browserClass->getBrowser(),"version"=>$browserClass->getVersion(),"platform"=>$browserClass->getPlatform());

        return array("browser"=>$browser,"os"=>$osClass->getOS(),"ip"=>$ip,"geo"=>$geo);
    }

    private function userLogAndRemember($id,$forceRemember=false,$useragentdata=false){

        $remember="";
        if(isset($_POST["remember"]) || $forceRemember==true){
            $remember=$this->setRememberCookie();
        }

        if(!$useragentdata){
            $userData=$this->getUserAgentDetails();
        }else{
            $userData=$useragentdata;
        }


        $ip=$userData["ip"];
        $geo=$userData["geo"];
        $browser=$userData["browser"];
        $os=$userData["os"];
        $arrayQuery=array(
        ":id"=>$id,":remember"=>$remember,":browser"=>$browser["name"],":browser_v"=>$browser["version"],
            ":platform"=>$browser["platform"],":os"=>$os,":ip"=>$ip,":city"=>$geo["geoplugin_city"],
            ":region"=>$geo["geoplugin_region"],":country"=>$geo["geoplugin_countryName"],
            ":country_code"=>$geo["geoplugin_countryCode"],":lat"=>$geo["geoplugin_latitude"],":long"=>$geo["geoplugin_longitude"]);

        $sql="INSERT INTO `user_log`(`user_ul`, `token_ul`, `date_ul`, `browser_ul`, `browser_v_ul`, `platform_ul`, `os_ul`,
             `ip_ul`, `city_ul`, `region_ul`, `country_ul`, `country_code_ul`, `lat_ul`, `long_ul`)
             VALUES (:id,:remember,now(),:browser,:browser_v,:platform,:os,:ip,:city,:region,:country,:country_code,:lat,:long)";
        $resLog=$this->config_Class->query($sql,$arrayQuery);

        return $resLog;

    }


    public function isLoggedUser(){
        if(isset($_SESSION["user_id"]) && isset($_SESSION["user_token"]) && isset($_COOKIE["healthkeep"])){
            $user_id=(int)$_SESSION["user_id"];
            if($user_id!=0 && $_SESSION["user_token"]!="" && $_COOKIE["healthkeep"]!=""){
                $sql="select * from user,profile
                    where id_user=id_profile and id_user=:id and session_user=:session and cookie_user=:cookie limit 1";
                $res=$this->config_Class->query($sql,
                    array(
                          ":session"=>$_SESSION["user_token"]
                         ,":cookie"=>$_COOKIE["healthkeep"]
                         ,":id"=>$user_id
                         )
                    );
                if($res["result"]){
                    $this->doDefine($res);
                    return $res[0];
                }
            }
        }

        if(isset($_COOKIE["rememberHK"])){

            $userData=$this->getUserAgentDetails();

            $browser=$userData["browser"];
            $os=$userData["os"];

            $sql="select * from user_log where token_ul!='1' and token_ul=:cookie and browser_ul=:browser and platform_ul=:platform and os_ul=:os limit 1";
            $resR=$this->config_Class->query($sql,array(":cookie"=>$_COOKIE["rememberHK"],
                ":browser"=>$browser["name"],":platform"=>$browser["platform"],":os"=>$os));
            if($resR["result"]){

                $this->realDoLogin($resR[0]["user_ul"]);

                $sql="update user_log set token_ul=:token where id_ul=:id";
                $res=$this->config_Class->query($sql,array(":token"=>'1',":id"=>$resR[0]["id_ul"]));

                $this->userLogAndRemember($resR[0]["user_ul"],true,$userData);

                $sql="select * from user,profile
                    where id_user=id_profile and id_user=:id limit 1";
                $res=$this->config_Class->query($sql,array(":id"=>$resR[0]["user_ul"]));

                if($res["result"]){

                    //renew mixpanel info
                    $_SESSION["mx_name_tag"]=0;

                    $this->doDefine($res);
                    return $res[0];

                }
            }
        }
        define("USER_ID",0);
        define("USER_TYPE",1);
        return false;
    }

    private function doDefine($res){
        define("USER_ID",$res[0]["id_user"]);
        define("USER_IMAGE",$res[0]["image_profile"]);
        define("USER_TYPE",$res[0]["type_user"]);
        define("USER_TOUR",$res[0]["tour_user"]);
        define("USER_NAME",$res[0]["username_profile"]);
        define("PROFILE_TYPE",$res[0]["type_profile"]);
        define("PROFILE_MSGS",$res[0]["msgs_profile"]);
        define("PROFILE_TRACK",$res[0]["tracking_profile"]);
        if($res[0]["tracking_profile"]==1){
            define("TRACK_PROFILE","1");
        }else{
            define("TRACK_PROFILE","0");
        }
    }

    public function doLogout(){
        if(isset($_COOKIE["rememberHK"]) && $_COOKIE["rememberHK"]!=""){
            $sql="update user_log set token_ul='1' where token_ul=:cookie and user_ul=:id";
            $this->config_Class->query($sql,array(":cookie"=>$_COOKIE["rememberHK"],":id"=>USER_ID));
        }
        setcookie('healthkeep','',0,"/");
        setcookie('rememberHK','',0,"/");
        $_SESSION["user_id"]=0;
        $_SESSION["user_token"]="";

        header("Location:".WEB_URL,TRUE,301);
        exit;
    }

    private function setCookie(){
        $cookie=sha1(microtime(true).mt_rand(10000,90000));
        setcookie("healthkeep", $cookie, time()+43200, "/");
        return $cookie;
    }

    private function setRememberCookie(){
        $remember=$this->rememberCookie();
        setcookie("rememberHK", $remember, time()+2592000, "/");
        return $remember;
    }

    private function rememberCookie(){
        $token=sha1(microtime(true).mt_rand(10000,90000));
        $sql="select token_ul from user_log where token_ul=:token limit 1";
        $res=$this->config_Class->query($sql,array(":token"=>$token));
        if($res["result"]){
            return $this->rememberCookie();
        }
        return $token;
    }

    private function setSession($id){
        $session=sha1(microtime(true).mt_rand(10000,90000));
        $_SESSION["user_id"]=$id;
        $_SESSION["user_token"]=$session;
        return $session;
    }

    private function doPassword($password){

        return sha1("agrainofsalt".$password);

    }

}