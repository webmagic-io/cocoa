<?php
class SitemapController extends baseController
{
  public function __construct($pathinfo,$controller) {
		
    parent::__construct($pathinfo,$controller);
    $this->size = 4500;
  }

  public function homenewsAction() {
    
    $db = new SitemapModel();
    $homenews = $db->select("cocoacms_news")->fetchAll();
    foreach($homenews as $news){
      
      echo "http://ourcoders.com/home/s/$news[id]/\r\n";
    }
  }
  
  public function threadsAction() {
    
    $db = new SitemapModel();
    $homenews = $db->select("threads")->fetchAll();
    foreach($homenews as $news){
      
      echo "http://ourcoders.com/thread/show/$news[id]/\r\n";
    }
  }
  
  public function users_indexAction() {
    
    $db = new SitemapModel();
    $ret = $db->select("cocoabbs_uc_members")->fields("count(*) as c")->fetchOne();
    $count = $ret["c"];
    $len = ceil($count/$this->size);
    echo '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    for($i=0;$i<$len;$i++) {
      
      $j=$i+1;
      echo "<sitemap><loc>http://ourcoders.com/sitemap/users/$j/</loc></sitemap>";
    }
    echo "</sitemapindex>";
  }
  
  public function usersAction() {
    
    $page = $this->intVal(3);
    if($page==0)
      $page=1;
    $size = $this->size;
    $start = ($page-1)*$size;
    
    $db = new SitemapModel();
    $homenews = $db->select("cocoabbs_uc_members")->limit("$start,$size")->fetchAll();
    if(count($homenews)>0)
    foreach($homenews as $news){
      
      $username = urlencode($news["username"]);
      echo "http://ourcoders.com/user/show/$news[uid]/$username/\r\n";
    }
  }
  
}






