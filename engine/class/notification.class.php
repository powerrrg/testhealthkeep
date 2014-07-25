<?php
class Notification extends Base {

    private $config_Class;

    function __construct()
    {
        $this->config_Class=new Config();


        require_once(ENGINE_PATH.'class/post.class.php');
        $this->_post = new Post();
    }
    public function getCountBadges($to_user_id) {
        $count_unread_messages = $this->_post->getCountUnreadMessagesForUser($to_user_id);
        $count_new_comments = $this->_post->getUserUnreadCommentsModel($to_user_id);
        $count_bandages =  $count_unread_messages + $count_new_comments;
        return $count_bandages;
    }

    public function pushNotification($to_user_id, $type_notification = 0, $is_badges = true, $is_sound = true, $is_text = true, $params = array()) {
        if ($type_notification == 1){
            $welcome_text = 'You have a message from '.$params['user_name'].'!';
        } elseif ($type_notification == 3) {
            $welcome_text = 'You have a reply to your request for help';
        } else {
            $welcome_text = 'Hello!';
        }
        if($is_badges) {
            $count_badges = $this->getCountBadges($to_user_id);
        }

        $sql = 'select token_profile from profile where id_profile =:to_user_id';
        $res = $this->config_Class->query($sql, array(':to_user_id' => $to_user_id));
        $device_token = $res[0]["token_profile"];
        if(isset($device_token) and $device_token != '') {

            // Adjust to your timezone
            date_default_timezone_set('Europe/Kiev');

            // Report all PHP errors
            error_reporting(-1);

            // Using Autoload all classes are loaded on-demand
            require_once ENGINE_PATH.'class/ApnsPHP/Autoload.php';

            // Instantiate a new ApnsPHP_Push object
            $push = new ApnsPHP_Push(
                ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
                ENGINE_PATH.'class/ApnsPHP/certificates/server_certificates_bundle_sandbox.pem'
            );

            // Set the Root Certificate Autority to verify the Apple remote peer
            $push->setRootCertificationAuthority(ENGINE_PATH.'class/ApnsPHP/certificates/entrust_root_certification_authority.pem');

            // Connect to the Apple Push Notification Service
            $push->connect();

            // Instantiate a new Message with a single recipient
            //$message = new ApnsPHP_Message('2fa889482a24b5cf4601b674c8eb9feb4eabab9f81b007fd0fda9be02d3dc6a4');
            $message = new ApnsPHP_Message($device_token);

            // Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
            // over a ApnsPHP_Message object retrieved with the getErrors() message.
            //$message->setCustomIdentifier("Message-Badge-3");

            if($is_badges) {
                // Set badge icon to "3"
                $message->setBadge($count_badges);
            }

            if($is_text) {
                // Set a simple welcome text
                $message->setText($welcome_text);
            }

            if ($is_sound) {
                // Play the default sound
                $message->setSound();
            }

            // Set a custom property
            $message->setCustomProperty('type', $type_notification);

            foreach ($params as $param => $val) {
                $message->setCustomProperty($param, $val);
            }
            // Set another custom property
            //$message->setCustomProperty('acme3', array('bing', 'bong'));

            // Set the expiry value to 30 seconds
            $message->setExpiry(30);

            // Add the message to the message queue
            $push->add($message);

            // Send all messages in the message queue
            $push->send();

            // Disconnect from the Apple Push Notification Service
            $push->disconnect();

            // Examine the error message container
            $aErrorQueue = $push->getErrors();
    /*        if (!empty($aErrorQueue)) {
                var_dump($aErrorQueue);
            }*/

            /*return $aErrorQueue;*/
        }

    }
}