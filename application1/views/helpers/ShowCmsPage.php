<?php
class Zend_View_Helper_ShowCmsPage
{
	public function showCmsPage($id=0)
	{
	  
	  if(!$id > 0){
	    return FALSE;
	  } 
	
		global $db;
		$select = $db -> select();
		$select -> from('cms');
		$select -> where('id="'.$id.'"');
		$stmt = $db -> query($select);
		$result = $stmt -> fetchAll();
		
		if(isset($result[0]))return $result[0];
		else return false;
		
		
	}
	
}
?>