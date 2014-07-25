<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class messageController extends Mobile_api {

    private $_post;
    private $_notification;
    private $_mailer;
    private $_message_topic = 0;

    public function __construct($request = array()) {
        parent::__construct($request);
        $this->getReqParam('user_id');
        
        require_once(ENGINE_PATH.'class/post.class.php');
        $this->_post = new Post();

        require_once(ENGINE_PATH.'class/notification.class.php');
        $this->_notification = new Notification();

        require_once(ENGINE_PATH.'class/profile.class.php');
        $this->_profile = new Profile();

        require_once(ENGINE_PATH."starter/mail.php");
        $this->_mailer = new PHPMailer();
    }
    
    public function sendMessage() {
        $message = $this->getReqParam('message', false, "");
        $to_user_id = $this->getReqParam('to_user_id', true);

        // check if conversation hide -> show
        if ($this->_post->isHideConversationOtherUserModel($to_user_id)) {
            $this->_post->showConversationModel($to_user_id);
        }
        // check status conversation
        $conv_blocked = $this->_post->isBlockConversation($to_user_id);
        // if conversation not blocked -> send message
        if(!isset($conv_blocked[0]["id_conv"])){
            $this->answer = $this->_post->addNewV2Post($message, 'image', $this->_message_topic, $to_user_id);
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
        $this->answer = $this->_post->hideConversationModel($to_user_id);
    }

    public function deleteMessage() {
        $message_id = $this->getReq2Param('message_id');
        $this->answer = $this->_post->deleteMessageModel($message_id);
    }

    public function getConversations() {
        $this->answer = $this->_post->getAllConversations($this->getReqParam('timestamp', true, 0));
        //var_dump($this->answer);
        if (count($this->answer) > 0) {
            foreach ($this->answer as $key=>$post) {
                if ($key !== 'result') {
                    // check if conversation hide then delete
                    if ($this->_post->isHideConversationModel($this->answer[$key]['id_profile']) == true) {
                        unset($this->answer[$key]);
                    } else {
                        // add date_post, time_ago, count_unread_messages if conversation not hide
                        $last_post = $this->_post->getConvMessages($this->answer[$key]['id_profile'], $this->getReqParam('timestamp', true, 0));
                        if (isset($last_post[0]['date_post'])) {
                            $this->answer[$key]['date_post'] = $last_post[0]['date_post'];
                            $this->answer[$key]['time_ago'] = $this->config->ago(strtotime($last_post[0]['date_post']));
                            $this->answer[$key]['count_unread_messages'] = $this->_post->getCountUnreadMessagesInConversationModel($this->answer[$key]['id_profile']);
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
        $this->answer = $this->_post->getConvMessages($to_user_id, $this->getReqParam('timestamp', true, 0));

        if (count($this->answer) > 0) {
            foreach ($this->answer as $key=>$post) {
                if ($key !== 'result') {
                    $timestamp = strtotime($this->answer[$key]['date_post']);
                    //$this->answer[$key]['time_ago'] = $this->config->ago($timestamp);
                    $this->answer[$key]['time_ago'] = $this->config->ago($timestamp);
                }
            }
        }
    }

    public function sendIsReadMessage() {
        $post_id = $this->getReq2Param('post_id');
        $this->answer = $this->_post->sendIsReadMessageModel($post_id);
    }

    public function getIsReadMessage() {
        $post_id = $this->getReq2Param('post_id');
        $this->answer = $this->_post->getIsReadMessageModel($post_id);
    }

    public function getCountUnreadMessages() {
        $this->answer = $this->_post->getCountUnreadMessagesModel();
    }

    public function setReadMessages() {
        $to_user_id = $this->getReq2Param('to_user_id');
        $this->answer = $this->_post->setReadMessagesModel($to_user_id);
    }

}