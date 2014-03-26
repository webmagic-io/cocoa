<?php

class DonateController extends baseController
{
  public function __construct($pathinfo,$controller) {
    
    parent::__construct($pathinfo,$controller);
    $this->_view->assign("active","donate");
  }

  public function indexAction() {
    
    $this->display();
  }

}
