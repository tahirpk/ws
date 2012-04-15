<?php
  class Zend_Controller_Action_Helper_FrontAppHelper extends Zend_Controller_Action_Helper_Abstract
  {
    public function direct()
    {
        return '';
    }
	
	function getMetaInfo($id) {
		global $db;
			if(is_numeric($id)){
			  $where = "id=$id";
			}else{
			  $where = "sef_url='$id'";
			}
			
			$select = $db->select()
							->from('cms',array('title','meta_keywords','meta_desc'))
							->where($where);
			$result = $db->fetchAll($select);
			if(count($result)){
			  return $result[0];
			}
			
			return FALSE;
	}
	
	
	function setMetaInfo($obj , $meta){
	   $obj -> view -> placeholder('layout_title') -> set($meta['title']);
	   $obj -> view -> placeholder('layout_keywords') -> set($meta['meta_keywords']);
	   $obj -> view -> placeholder('layout_desc') -> set($meta['meta_desc']);
	}
	
	public function getAbsolutePath()
		{		
			 $pageURL = 'http';
			 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			 $pageURL .= "://";
			 if ($_SERVER["SERVER_PORT"] != "80") {
			  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
			 } else {
			  $pageURL .= $_SERVER["SERVER_NAME"];
			 }
			 return $pageURL;
		}
	
  }
?>