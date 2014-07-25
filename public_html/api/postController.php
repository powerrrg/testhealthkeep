<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class postController extends Mobile_api {

    private $_post;
    private $_default_vote = 1; //Add one point to total amount post or comment points.

    public function __construct($request = array()) {
        parent::__construct($request);
        $this->getReqParam('user_id');
        
        require_once(ENGINE_PATH.'class/post.class.php');
        $this->_post = new Post();

        require_once(ENGINE_PATH.'class/notification.class.php');
        $this->_notification = new Notification();
    }
    
    public function allPosts() {
        $this->answer = $this->_post->getAllPosts($this->getReqParam('timestamp', true, 0));
        $this->afterPostFind();
    }

    public function getPostById() {
        $post_id = $this->getReq2Param('post_id');
        $this->answer = $this->_post->getPostByIdModel($post_id);
    }

    public function getCommentById() {
        $comment_id = $this->getReq2Param('comment_id');
        $this->answer = $this->_post->getCommentByIdModel($comment_id);
    }

    public function userPosts() {
        $this->answer = $this->_post->getAllPosts($this->getReqParam('timestamp', true, 0), $this->getReqParam('author_id'));
        $this->afterPostFind();        
    }
    
    public function topicPosts() {
        $this->answer = $this->_post->getPostsByTopicId($this->getReqParam('id_topic'), $this->getReqParam('timestamp', true, 0));
        $this->afterPostFind();        
    }

    public function searchPosts() {
        $this->answer = $this->_post->searchPosts($this->getReqParam('keyword', false), $this->getReqParam('timestamp', true, 0));
        $this->afterPostFind();        
    }

    public function getPostComments() {
        $this->answer = $this->_post->getAllPostComments($this->getReqParam('post_id'), $this->getReqParam('timestamp', true, 0));
        $this->afterCommentFind();        
    }
    
    public function addPost() {
        $title = $this->getReqParam('title', false);
        $content = $this->getReqParam('content', false);

        $this->answer = $this->_post->addNewNoTopic($content, $title, 'image');
    }

    public function getUserUnreadComments() {
        $to_user_id = $this->getReq2Param('to_user_id');
        $this->answer = $this->_notification->getCountBadges($to_user_id);
    }
    public function getCountUnreadComments() {
        $user_id = $this->getReq2Param('user_id');
        $this->answer = $this->_post->getCountUserUnreadCommentsModel($user_id);
    }
    public function updatePost() {
        $post_id = $this->getReq2Param('post_id');
        $title = $this->getParam('title');
        $content = $this->getParam('content');

        $this->answer = $this->_post->updatePostModel($post_id, $content, $title, 'image');
    }
    public function setReadComments() {
        $post_id = $this->getReq2Param('post_id');
        $this->answer = $this->_post->setReadCommentsModel($post_id);
    }
    public function addComment() {
        $comment = $this->getReqParam('comment', false);
        $post_id = $this->getReqParam('post_id');
        $video_web_url = $this->getReqParam('video_url_pc', false, "");
        $this->answer = $this->_post->addComment($post_id, $comment, 'image', $video_web_url);
        $ownerPost = $this->_post->getOwnerPost($this->answer[0]['id_post_pc']);
        if($this->answer[0]['id_profile_pc'] != $ownerPost) {
            $this->_post->updateUnreadCommentsCounter($post_id);
        }
        $this->_notification->pushNotification($ownerPost ,3, true, true, true, array("id" => $post_id));
    }

    public function updateComment() {
        $comment_id = $this->getReq2Param('comment_id');
        $comment = $this->getParam('comment');
        $video_web_url = $this->getParam('video_url_pc');

        $this->answer = $this->_post->updateCommentModel($comment_id, $comment, 'image', $video_web_url);
    }
    public function setBlockConversation(){
        $to_user_id = $this->getReqParam('to_user_id');
        $this->answer = $this->_post->setBlockConversationModel($to_user_id);
    }
    public function postLike() {
        $this->answer = $this->_post->postLike($this->getReqParam('post_id'), $this->_default_vote);
        $ownerPost = $this->_post->getPostOwner($this->getReqParam('post_id'));
        if (isset($ownerPost[0]["post_owner_id"])) {
            $this->_notification->pushNotification($ownerPost[0]["post_owner_id"], 5, false, false, false, array('id' => $this->getReqParam('post_id')) );
        }
    }

    public function commentLike() {
        $this->answer = $this->_post->commentLike($this->getReqParam('comment_id'), $this->_default_vote);
        $ownerComment = $this->_post->getCommentOwner($this->getReqParam('comment_id'));
        if (isset($ownerComment[0]["comment_owner_id"])) {
            $this->_notification->pushNotification($ownerComment[0]["comment_owner_id"], 4, false, false, false, array('id' => $this->getReqParam('comment_id')) );
        }
    }
    
    private function afterPostFind() {
        if (count($this->answer) > 0) {
            foreach ($this->answer as $key=>$post) {
                if ($key !== 'result') {
                    $timestamp = strtotime($this->answer[$key]['date_post']);
                    $this->answer[$key]['time_ago'] = $this->config->ago($timestamp);
                    $this->answer[$key]['post_topics'] = $this->_post->getPostTopics($post['id_post']);
                }
            }
        }
    }
    
    private function afterCommentFind() {
        if (count($this->answer) > 0) {
            foreach ($this->answer as $key=>$post) {
                if ($key !== 'result') {
                    $timestamp = strtotime($this->answer[$key]['date_pc']);
                    $this->answer[$key]['time_ago'] = $this->config->ago($timestamp);
                }
            }
        }
    }
    
}