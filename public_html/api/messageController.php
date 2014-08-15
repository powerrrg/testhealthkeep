<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class messageController extends Mobile_api {

    private $_new_message;
    private $_notification;

    public function __construct($request = array()) {
        parent::__construct($request);
        $this->getReqParam('user_id');

        require_once(ENGINE_PATH.'class/notification.class.php');
        $this->_notification = new Notification();

        require_once(ENGINE_PATH.'class/profile.class.php');
        $this->_profile = new Profile();

        require_once(ENGINE_PATH.'class/new_message.class.php');
        $this->_new_message = new NewMessage();
    }
    
    public function sendMessage() {
        $message = $this->getReqParam('message', false, "");
        $to_user_id = $this->getReqParam('to_user_id', true);

        // check if conversation hide -> show
        if ($this->_new_message->isHideConversationOtherUserModel($to_user_id)) {
            $this->_new_message->showConversationModel($to_user_id);
        }
        // check status conversation
        $conv_blocked = $this->_new_message->isBlockConversation($to_user_id);
        // if conversation not blocked -> send message
        if(!isset($conv_blocked[0]["id_conv"])){
            $this->answer = $this->_new_message->addMessage($to_user_id, $message, 'image'); // @@@@@@@@@@@@@@@@@@@@@@@@@@@@
            $profile = $this->_profile->getByIdWithTopics($this->getReqParam('user_id'));
            if(isset($profile)){
                $profile_name = $profile[0]["name_profile"];
            } else {
                $profile_name = "";
            }
            $this->_notification->pushNotification($to_user_id ,1, true, true, true, array("user_name" => $profile_name));
        } else {
            $this->answer["blocked"] = true;
            $this->answer["result"] = true;
        }
    }

    public function hideConversation() {
        $to_user_id = $this->getReq2Param('to_user_id');
        $this->answer = $this->_new_message->hideConversationModel($to_user_id);
    }

    public function deleteMessage() { // MOVE
        $message_id = $this->getReq2Param('message_id');
        $this->answer = $this->_new_message->deleteMessageModel($message_id);
    }

    public function getConversations() {
        $this->answer = $this->_new_message->getAllConversations($this->getReqParam('timestamp', true, 0));
        //var_dump($this->answer);
        if (count($this->answer) > 0) {
            foreach ($this->answer as $key=>$post) {
                if ($key !== 'result') {
                    // check if conversation hide then delete
                    if ($this->_new_message->isHideConversationModel($this->answer[$key]['id_profile']) == true) {
                        unset($this->answer[$key]);
                    } else {
                        // add date_post, time_ago, count_unread_messages if conversation not hide
                        $last_post = $this->_new_message->getConvMessages($this->answer[$key]['id_profile'], $this->getReqParam('timestamp', true, 0));
                        if (isset($last_post[0]['timestamp'])) {
                            $this->answer[$key]['date_post'] = $last_post[0]['timestamp'];
                            $this->answer[$key]['time_ago'] = $this->config->ago(strtotime($last_post[0]['timestamp']));
                            $this->answer[$key]['count_unread_messages'] = $this->_new_message->getCountUnreadMessagesInConversationModel($this->answer[$key]['id_profile']);
                        } else {
                            unset($this->answer[$key]);
                        }
                    }

                }
            }
        }
    }

    public function getConversationMessages() {
        $to_user_id = $this->getReqParam('to_user_id', true);
        $this->answer = $this->_new_message->getConvMessages($to_user_id, $this->getReqParam('timestamp', true, 0));

        if (count($this->answer) > 0) {
            foreach ($this->answer as $key=>$post) {
                if ($key !== 'result') {
                    $timestamp = strtotime($this->answer[$key]['timestamp']);
                    //$this->answer[$key]['time_ago'] = $this->config->ago($timestamp);
                    $this->answer[$key]['time_ago'] = $this->config->ago($timestamp);
                }
            }
        }
    }

    public function sendIsReadMessage() {
        $post_id = $this->getReq2Param('post_id');
        $this->answer = $this->_new_message->sendIsReadMessageModel($post_id);
    }

    public function getIsReadMessage() {
        $post_id = $this->getReq2Param('post_id');
        $this->answer = $this->_new_message->getIsReadMessageModel($post_id);
    }

    public function getCountUnreadMessages() {
        $this->answer = $this->_new_message->getCountUnreadMessagesModel();
    }

    public function setReadMessages() {
        $to_user_id = $this->getReq2Param('to_user_id');
        $this->answer = $this->_new_message->setReadMessagesModel($to_user_id);
    }

}