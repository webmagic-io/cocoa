<?php
class LogController extends baseController
{
  
  public function sitesearchAction() {
  
    $keyword = $_POST["keyword"];
    $logModel = new LogModel();
    $logModel->sitesearch($keyword);
    echo $keyword;
  }
  
}
