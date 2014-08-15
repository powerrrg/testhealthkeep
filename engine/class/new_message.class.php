<?php
/**
 * Created by PhpStorm.
 * User: ulikus
 * Date: 8/15/14
 * Time: 6:22 PM
 */


class NewMessage extends Base {

    private $config_Class;
    private $profile_Class;

    //private $limit = 10;
    private $user_id_array = array();

    function __construct()
    {
        $this->config_Class=new Config();
        require_once(ENGINE_PATH.'class/profile.class.php');
        $this->profile_Class=new Profile();
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
        // Check current user id1 or id2 in conversation
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

    public function createConversation($to_user_id) {
        if ($conv_id = $this->getConversationId($to_user_id)) {
            return $conv_id;
        } else {
            $sql = "insert into conversations (user_id1_conv, user_id2_conv) values (:user_id, :to_user_id)";
            $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id));
            return $this->getConversationId($to_user_id); // ?
        }
    }

    public function getConvMessages($to_user_id, $timestamp) {
        $conv_id = $this->getConversationId($to_user_id);
        $sql = "SELECT * FROM new_message nm
                JOIN conversations c ON nm.conversation_id = c.id_conv
                LEFT JOIN profile ON nm.from_user_id=profile.id_profile
                WHERE c.id_conv=:id_conv"
            .$this->timePostSQL($timestamp, 'nm.timestamp')." ORDER BY nm.timestamp DESC LIMIT ".$this->getLimit();
        return $this->config_Class->query($sql, array(":id_conv" => $conv_id));//, ":user_to" => $to_user_id,":user_from2" => USER_ID, ":user_to2" => $to_user_id));
    }

    public function getCountUnreadMessagesModel(){
        $sql="select count(to_user_id) as count_unread_messages from new_message where to_user_id =:user and is_read = 0";
        $result = $this->config_Class->query($sql,array(":user"=>USER_ID));
        return $result;
    }

    public function getCountUnreadMessagesInConversationModel($from_user){
        $sql="select count(from_user_id) as count_unread_messages from new_message where to_user_id=:user and from_user_id=:from_user and is_read = 0";
        $result = $this->config_Class->query($sql,array(":from_user"=>$from_user, ":user"=>USER_ID));
        return $result[0]['count_unread_messages'];
    }

    public function sendIsReadMessageModel($message_id){
        $sql = "update new_message set is_read = 1  where message_id=:message_id";
        $result = $this->config_Class->query($sql, array(":message_id"=>$message_id));
        return array("result" => $result);
    }

    public function setReadMessagesModel($to_user_id){
        // get conv id
        $sql = "update new_message set is_read = 1 where to_user_id=:to_user_id and from_user_id=:user_id";
        $result = $this->config_Class->query($sql, array(":to_user_id"=>$to_user_id, ":user_id"=>USER_ID));
        return array("result" => $result);
    }

    public function getIsReadMessageModel($message_id){
        $sql = "select is_read as read_post from new_message where message_id=:message_id";
        $result = $this->config_Class->query($sql, array(":message_id"=>$message_id));
        if(isset($result[0]['read_post']) and $result[0]['read_post'] == 1){
            return array("result" => true);
        } else {
            return array("result" => false);
        }

    }

    public function deleteMessageModel($message_id){
        $sql="delete from new_message where message_id=:message_id";
        $result = $this->config_Class->query($sql,array(":message_id" => $message_id));
        return array("result" => $result);
    }

    public function isBlockConversation($to_user_id) {
        $sql = "select * from conversations where (user_id1_conv=:user_id and user_id2_conv=:to_user_id and status_conv = 'block') or (user_id2_conv=:user_id2 and user_id1_conv=:to_user_id2 and status_conv = 'block')";
        return $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
    }

    public function getAllConversations() {
        $users = $this->getUsersID();
        if (!empty($users)) {
            $sql = "SELECT * FROM profile WHERE id_profile IN (".implode(',', $users).")";
            $conversations = $this->config_Class->query($sql);

            foreach ($conversations as $key => $value) {
                if ($key == 'result') {
                    continue;
                }
                $conv_to_user_id = $conversations[$key]["id_profile"];
                $conv = $this->isBlockConversation($conv_to_user_id);
                $conversations[$key]["blocked"] = array();
                if(isset($conv[0]["id_conv"])) {
                    $conversations[$key]["blocked"] = "true";
                    if ((($conv[0]["user_id1_conv"] == USER_ID) && ($conv[0]["blocked_u1_conv"] == 1)) or (($conv[0]["user_id2_conv"] == USER_ID) && ($conv[0]["blocked_u2_conv"] == 1)) ) {
                        $conversations[$key]["blockedByMyself"] = true;
                    } else {
                        $conversations[$key]["blockedByMyself"] = false;
                    }
                } else {
                    $conversations[$key]["blocked"] = "false";
                }
            }
            return  $conversations;
        } else {
            return array('result' => true);
        }
    }

    public function addMessage($to_user_id, $text, $img = '') {
        $conv_id = $this->createConversation($to_user_id);

        $text=$this->config_Class->escapeOddChars($text);
        $text=$this->config_Class->processPostText($text);

        $sql = 'insert into new_message (to_user_id, from_user_id, conversation_id, message) VALUES (:to_user_id, :from_user_id, :conv_id, :message)';
        $res = $this->config_Class->query($sql,array(":to_user_id"=>$to_user_id,":from_user_id"=>USER_ID, ':conv_id'=>$conv_id,':message' => $text));

        if ($res) {
            // get correct data
            $sql="select * from new_message nm, conversations c where
                nm.conversation_id = c.id_conv
                and c.id_conv=:conv order by nm.message_id desc limit 1";
            $res = $this->config_Class->query($sql,array(":conv"=>$conv_id));

            if ($res["result"]) {
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
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function getUsersID() {
        $sql = "SELECT user_id1_conv AS user_id FROM conversations WHERE user_id2_conv=:id";
        $res1 = $this->config_Class->query($sql, array(":id" => USER_ID));
        $this->addUsersIDs($res1);
        $sql = "SELECT user_id2_conv AS user_id FROM conversations WHERE user_id1_conv=:id";
        $res2 = $this->config_Class->query($sql, array(":id" => USER_ID));
        $this->addUsersIDs($res2);
        return $this->user_id_array;
    }

    private function addUsersIDs($ids) {
        $result = $ids['result'];
        unset($ids['result']);
        if ($result > 0) {
            foreach ($ids as $id) {
                if (!in_array($id['user_id'], $this->user_id_array))
                    $this->user_id_array[] = $id['user_id'];
            }
        }
    }

    public function getConversationId($to_user_id) {
        $sql = "select id_conv from conversations where
                (user_id1_conv=:user_id and user_id2_conv=:to_user_id) or
                (user_id1_conv=:to_user_id2 and user_id2_conv=:user_id2)";
        $result = $this->config_Class->query($sql, array(":user_id"=>USER_ID, ":to_user_id"=>$to_user_id, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));//, ":user_id2"=>USER_ID, ":to_user_id2"=>$to_user_id));
        return $result[0]['id_conv'];
    }

    public function saveImage($image,$id) {
        $sql="update new_message set image=:image where message_id=:id";
        $res = $this->config_Class->query($sql,array(":image"=>$image,":id"=>$id));
        return $res;

    }

}