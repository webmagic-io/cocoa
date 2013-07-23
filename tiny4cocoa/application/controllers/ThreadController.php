<?php
class ThreadController extends baseController
{
  public function __construct($pathinfo,$controller) {
		
    parent::__construct($pathinfo,$controller);
    $this->_view->assign("active","thread");
  }
 
  public function indexAction() {
    
    $thread = new ThreadModel();
    $threads = $thread->threads(1,20);
      
    $this->_mainContent->assign("threads",$threads);
    $this->setTitle("讨论区");
    $this->display();
  }
  
  public function showAction() {
    
    $id = $this->intVal(3);
    $threadModel = new ThreadModel();
    $discuz = new DiscuzModel();
    $userid = $discuz->checklogin();
    
    if($_POST){
      
      $userModel = new UserModel();
      if($userid>0)
        if(strlen($_POST["content"])>0) {
        
          $data = array();
          $time = time();
          $data["threadid"] = $id;
          $data["name"] = $userModel->username($userid);
          $data["userid"] = $userid;
          $data["content"] = $_POST["content"];
          $data["createdate"] = $time;
          $data["updatedate"] = $time;
          $replys = $threadModel->newReply($data);
          $thread = array();
          $thread["id"] = $id;
          $thread["replys"] = $replys;
          $thread["updatedate"] = $time;
          $thread["lastreply"] = $data["name"];
          $thread["lastreplyid"] = $data["userid"];
          $threadModel->updateThread($thread);
          header("location: /thread/show/$id/");
          die();
        }
    }
    $thread = $threadModel->threadById($id);
    $replysCount = $threadModel->replysCountById($id);
    $replys = $threadModel->replysById($id);
    
    $threads = $threadModel->threads(1,20);
    
    $this->_mainContent->assign("userid",$userid);
    $this->_mainContent->assign("thread",$thread);
    $this->_mainContent->assign("replysCount",$replysCount);
    $this->_mainContent->assign("replys",$replys);
    $this->_mainContent->assign("threads",$threads);
    
    $this->setTitle($thread["title"]);
    $this->display();
  }
 
  public function newAction() {
    
    $discuz = new DiscuzModel();
    $userModel = new UserModel();
    $userid = $discuz->checklogin();
    $username = $userModel->username($userid);
    if($userid==0) {
      header("location: /logging.php?action=login");
      die();
    }
    if($_POST){
      
      $data = array();
      $time = time();
      $data["title"] = $_POST["title"];
      $data["content"] = $_POST["content"];
      $data["createby"] = $username;
      $data["createbyid"] = $userid;
      $data["createdate"] = $time;
      $data["updatedate"] = $time;
      if(strlen($_POST["title"])>0)
        if(strlen($_POST["content"])>0) {
          $threadModel = new ThreadModel();
          $threadid = $threadModel->newThread($data);
          header("location: /thread/show/$threadid/");
          die();
      }
    }
    $this->display();
  }
}





