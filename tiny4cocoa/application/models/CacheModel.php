<?php

class CacheModel {
	
	public $cachePath;
	public $lifetime;
	
	public function __construct() {
		
		global $TA_PathInfo;
		$this->cachePath = $TA_PathInfo['cache'];
		$this->lifetime = 60*5;
	}
	
	public function checkCache($catelog,$id) {
		
		$filePath = $this->cachePath . "/" . $catelog . "-" . $id . ".dat";
		
		if(file_exists($filePath)) {
			
			if((time()-filemtime($filePath)) > $this->lifetime) {
				
				unlink($filePath);
				return false;
			}
			return true;
		}
		return false;
	}
	
	public function getCache($catelog,$id) {
	
    if(!$this->checkCache($catelog,$id))
      return NULL;
		$filePath = $this->cachePath . "/" . $catelog . "-" . $id . ".dat";
		$content = file_get_contents($filePath);
		return unserialize($content);
	}
	
	public function createCache($catelog,$id,$object) {
		
		$filePath = $this->cachePath . "/" . $catelog . "-" . $id . ".dat";
		$content = serialize($object);
		file_put_contents($filePath,$content);
	}
}
