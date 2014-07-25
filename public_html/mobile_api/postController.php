<?php
/**
 * Description of User
 *
 * @author Игорь
 */
class postController extends Mobile_api{
    
    private $post_Class;
    
    public function __construct() {
        parent::__construct();
        $this->checkUserID();
        
        require_once(ENGINE_PATH."class/post.class.php");
        $this->post_Class = new Post();
    }
    
    public function allPosts() {
        $this->answer = $this->post_Class->getAllPosts($this->getStamp());
    }
    
    public function userPosts() {
        $this->answer = $this->post_Class->getAllPosts($this->getStamp(), $this->getReqParam('author_id'));
    }
    
    public function getPostComments() {
        $this->answer = $this->post_Class->getAllPostComments($this->getReqParam('post_id'), $this->getStamp());
    }
    

}
