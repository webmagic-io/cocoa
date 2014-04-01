<?php
class TagController extends baseController
{
  public function __construct($pathinfo,$controller) {
		
    parent::__construct($pathinfo,$controller);
    $this->_view->assign("active","thread");
  }
 
  public function nameAction() {
  
    $tag = urldecode($this->strVal(3));
    $this->display();
  }  

}






