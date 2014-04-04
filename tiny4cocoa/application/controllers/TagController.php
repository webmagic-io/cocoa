<?php
class TagController extends baseController
{
  public function __construct($pathinfo,$controller) {
		
    parent::__construct($pathinfo,$controller);
    $this->_view->assign("active","thread");
  }
 
  public function nameAction() {
  
    $tag = urldecode($this->strVal(3));
  	
  	$page = $this->intVal(4);
    if($page==0)
      $page=1;

    $threadModel = new ThreadModel();

    $threadCount = $threadModel->threadsCountByTag($tag);
    $threadPageSize = 40;
    
    $threads = $threadModel->threadsByTag($tag, $page, $threadPageSize);
    $pageControl = ToolModel::pageControl(
                      $page,
                      $threadCount,
                      $threadPageSize,
                      "<a href='/tag/name/$tag/#page#/'>",
                      0);
    $object["threads"] = $threads;
    $object["pageControl"] = $pageControl;
    $content = $this->doTemplate("Module","thread",$object);
    $this->_mainContent->assign("content",$content);
    
    $relatedTags = $threadModel->relatedTags($tag);
    $this->_mainContent->assign("relatedTags",$relatedTags);

    $this->_mainContent->assign("tag",$tag);

    $this->display();
  }  

}






