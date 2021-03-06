<?php
class HomeadminController extends baseController
{
  public $userid;
  public function __construct($pathinfo,$controller) {
		
    parent::__construct($pathinfo,$controller);
    if($this->userid!=2 && $this->userid!=46 && $this->userid!=20949) {
      header ('HTTP/1.1 301 Moved Permanently');
      header('location: /home/');
    }
  }
  
  public function indexAction() {
    
    $newsModel = new NewsModel();
    $news = $newsModel->news(1,10);
    $this->_mainContent->assign("news",$news);
    $this->display();
  }
  
  public function newarticleAction() {
    
    $this->display();
  }
  
  public function editarticleAction() {
    
    $index = $this->intVal(3);
    $newsModel = new NewsModel();
    $news = $newsModel->oneNews($index);
    $this->_mainContent->assign("threads",$threads);
    $this->_mainContent->assign("news",$news);
    
    $this->viewFile = "Homeadmin/newarticle.html";
    $this->display();
  }
  
  public function articlesAction() {
    
    $this->display();
  } 
  
  public function applenewsAction(){
    
    $filter = $this->strVal(3);
    $page = $this->intVal(4);
    if($filter=="")
      $filter = "all";
    if($page<1)
      $page=1;
    $size = 10;
    $newscenter = new NewscenterModel();
    $count = $newscenter->count($filter);
    $news = $newscenter->news($page,$size,$filter);
    $nnews = array();
    if(count($news)>0)
    foreach($news as $item){
      
      $item["content"] = mb_substr(strip_tags($item["content"]),0,250);
      $nnews[] = $item;
    }
    $news = $nnews;
    
		$pageControl = ToolModel::pageControl($page,$count,$size,"<a href='/homeadmin/applenews/$filter/#page#/'>");
    
    $this->_mainContent->assign("filter",$filter);
    $this->_mainContent->assign("page",$page);
    $this->_mainContent->assign("count",$count);
    $this->_mainContent->assign("news",$news);
    $this->_mainContent->assign("pageControl",$pageControl);
    
    $this->display();
  }
  
  
  public function markapplenewsAction() {
    
    $page = $_GET["page"];
    $filter = $_GET["filter"];
    $newscenter = new NewscenterModel();
    
    if($_POST) {
      
      $ids = $_POST["ids"];
      $newscenter->checked($ids);
      header("location:/homeadmin/applenews/$filter/$page/");
    }
    $id = $_GET["newsid"];
    $apple = $_GET["apple"];
    $newscenter->markApple($id,$apple);
    header("location:/homeadmin/applenews/$filter/$page/");
  }
  public function commentsAction() {
    
    $newModel = new NewsModel();
    $comments = $newModel->comments(1,20);
    $this->_mainContent->assign("comments",$comments);
    $this->display();
  }
   
  public function emptyspamAction() {
    
    $newsModel = new NewsModel();
    $newsModel->emptySpam();
    header("location:/homeadmin/comments/");
  }
  
  public function markspamAction(){
    
    $id = $this->intVal(3);
    if($id==0)
      header("location:/homeadmin/comments/");
    $newsModel = new NewsModel();
    $newsModel->markSpam($id,1);
    
    $akismet = new Akismet();
    $akismet->key = "5a3c4dc9f909";
    $akismet->blog = "http://tiny4cocoa.org/home/";
    if(!$akismet->verifyKey())
      die("akismet verify error");
    $comment = $newsModel->commentById($id);
    if(!$comment)
      die("can not find comment");
    $data = array('blog' => 'http://tiny4cocoa.org/home/',
                  'user_ip' => $comment["ip"],
                  'user_agent' => $comment["useragent"],
                  'referrer' => $comment["referrer"],
                  'permalink' => "http://tiny4cocoa.org/home/s/$comment[newsid]",
                  'comment_type' => 'comment',
                  'comment_author' => $comment["poster"],
                  'comment_author_email' => '',
                  'comment_author_url' => '',
                  'comment_content' => $comment["content"]);
    $ret = $akismet->submitSpam($data);
    header("location:/homeadmin/comments/");
  }
  
  public function unmarkspamAction(){
    
    $id = $this->intVal(3);
    if($id==0)
      header("location:/homeadmin/comments/");
    $newsModel = new NewsModel();
    $newsModel->markSpam($id,0);
    
    $akismet = new Akismet();
    $akismet->key = "5a3c4dc9f909";
    $akismet->blog = "http://tiny4cocoa.org/home/";
    if(!$akismet->verifyKey())
      die("akismet verify error");
    $comment = $newsModel->commentById($id);
    if(!$comment)
      die("can not find comment");
    $data = array('blog' => 'http://tiny4cocoa.org/home/',
                  'user_ip' => $comment["ip"],
                  'user_agent' => $comment["useragent"],
                  'referrer' => $comment["referrer"],
                  'permalink' => "http://tiny4cocoa.org/home/s/$comment[newsid]",
                  'comment_type' => 'comment',
                  'comment_author' => $comment["poster"],
                  'comment_author_email' => '',
                  'comment_author_url' => '',
                  'comment_content' => $comment["content"]);
    $ret = $akismet->submitHam($data);
    header("location:/homeadmin/comments/");
  }
  
  
  public function recheckSpamAction(){
    
    $id = $this->intVal(3);
    if($id==0)
      header("location:/homeadmin/comments/");
    $newsModel = new NewsModel();
    
    $akismet = new Akismet();
    $akismet->key = "5a3c4dc9f909";
    $akismet->blog = "http://tiny4cocoa.org/home/";
    if(!$akismet->verifyKey())
      die("akismet verify error");
    $comment = $newsModel->commentById($id);
    if(!$comment)
      die("can not find comment");
    $data = array('blog' => 'http://tiny4cocoa.org/home/',
                  'user_ip' => $comment["ip"],
                  'user_agent' => $comment["useragent"],
                  'referrer' => $comment["referrer"],
                  'permalink' => "http://tiny4cocoa.org/home/s/$comment[newsid]",
                  'comment_type' => 'comment',
                  'comment_author' => $comment["poster"],
                  'comment_author_email' => '',
                  'comment_author_url' => '',
                  'comment_content' => $comment["content"]);
    $ret = $akismet->commentCheck($data);
    if($ret) {
      $newsModel->markSpam($comment["id"],1);
      //echo "comment # $comment[id] is spam!\r\n";
    }
    else {
      $newsModel->markSpam($comment["id"],0);
      //echo "comment # $comment[id] is not spam.\r\n";
    }
    header("location:/homeadmin/comments/");
  }
  public function savearticleAction() {
    
    if(empty($_POST))
      header("location:/homeadmin/");
    
    $news = new NewsModel();
    
    $data = $_POST;
    $data["createdate"] = time();
    $data["updatedate"] = time();
    $username = $news->usernameById($this->userid);
    $data["posterid"] = $this->userid;
    $data["poster"] = $username;
    $news->select("cocoacms_news")->insert($data);
    header("location:/homeadmin/");
  }
  
  public function newsimageuploadAction() {
    
    /**
      给图片起名字
      把图片转换成合适的尺寸/保存
      给出预览图片
    */
    $discuzPath = dirname(dirname(dirname(dirname(__FILE__))));
    $savepath = "$discuzPath/newsupload/";
    $upload = new UploadModel();
    $filename = $upload->filename;
    if(!$filename)
      die('上传失败，请稍后重试！');
    $sizes = array(
      array("s",220,146),
      array("m",300,-1)
    );
    $upload->cropAndSave($sizes,$savepath);
    $ret["filename"] = $filename;
    $ret["ext"] = $upload->ext;
    echo json_encode($ret);
  }
  
  public function settongjiAction() {
    
    $tongji = new TongjiModel();
    $tongji->check($_GET["code"]);
  }
  
  public function feedbackAction() {
    
    $db = new PlaygroundModel();
    $sql = "SELECT * FROM `playground_feedback` order by `id` DESC;";
    $ret = $db->fetchArray($sql);
    $feedbacks = array();
    foreach($ret as $line) {
      
      $line["feedback"] = urldecode($line["feedback"]);
      $line["feedback"] = ToolModel::toHtml($line["feedback"]);
      if($line["createtime"]!=0)
        $line["createtime"] = ToolModel::countTime($line["createtime"]);
      else
        $line["createtime"] = "";
      $feedbacks[] = $line;
    }
    $this->_mainContent->assign("feedbacks",$feedbacks);
    $this->display();
  }
  
  public function statAction(){
    
    $index = $this->strVal(3);
    $stat = new StatModel();
    $days = $stat->days();
    if(strlen($index)==0)
      $index = $days[0]["datename"];
    $data = $stat->data($index);
    $time = explode(",",$data["time"]["content"]);
    
    $ipdata = $data["ip"]["content"];
    $iparray = explode("\n",$ipdata);
    $ips = array();
    foreach($iparray as $ip) {
      $v = explode(" ",trim($ip));
      if(isset($v[0])&&isset($v[1])) {
        $ipline = array();
        $ipline["count"] = $v[0];
        $ipline["ip"] = $v[1];
        $ips[] = $ipline;
      }
    }
    
    $actiondata = $data["action"]["content"];
    $actionarray = explode("\n",$actiondata);
    $actions = array();
    foreach($actionarray as $action) {
      $v = explode(" ",trim($action));
      if(isset($v[0])&&isset($v[1])) {
        $ipline = array();
        $ipline["count"] = $v[0];
        $ipline["action"] = $v[1];
        $actions[] = $ipline;
      }
    }
    
    $userdata = $data["user"]["content"];
    $userarray = explode("\n",$userdata);
    $users = array();
    foreach($userarray as $user) {
      $v = explode(" ",trim($user));
      if(isset($v[0])&&isset($v[1])) {
        $ipline = array();
        $ipline["count"] = $v[0];
        $ipline["user"] = $v[1];
        $users[] = $ipline;
      }
    }
    
    $this->_mainContent->assign("time",$time);
    $this->_mainContent->assign("ips",$ips);
    $this->_mainContent->assign("actions",$actions);
    $this->_mainContent->assign("users",$users);
    $this->_mainContent->assign("index",$index);
    $this->_mainContent->assign("days",$days);
    $this->display();
  }
  
  public function toplinkAction() {
    
    $toplinkadsModel = new ToplinkadsModel();
    if($_POST) {
      
      $toplinkadsModel->add($_POST);
      header("location:/homeadmin/toplink/");
      die();
    }
    $links = $toplinkadsModel->links();
    $this->_mainContent->assign("links",$links);  
    $this->display();
  }
  
  public function banuserAction() {
    
    $userModel = new UserModel();
    $threadModel = new ThreadModel();
    
    if($_POST) {
      
      $userid = $_POST["userid"];
      if($userid<=0 || $userid==2) {
        header ('HTTP/1.1 301 Moved Permanently');
        header("location:/home/");
        die();
      }
      $userModel->banUser($userid);
      if($_POST["delallthread"])
        $threadModel->delUserAllThread($userid);
      if($_POST["delallreply"])
        $threadModel->delUserAllReply($userid);
      header ('HTTP/1.1 301 Moved Permanently');
      header("location:/user/show/$userid/");
      die();
    }
    $id = $this->intVal(3);
    if($id==0 || $id==2 || $this->userid!=2) {
      header ('HTTP/1.1 301 Moved Permanently');
      header("location:/home/");
    }
    $userinfo = $userModel->userInfo($id);
    $userinfo["image"] = DiscuzModel::get_avatar($id,"middle");
    $userinfo["threadscreate"] = $threadModel->threadsByUserid($id,5);
    $this->_mainContent->assign("user",$userinfo);
    $this->display();
  }
}
