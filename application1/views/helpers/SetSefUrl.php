<?php
	class Zend_View_Helper_SetSefUrl extends Zend_View_Helper_Abstract
	{
		public function setSefUrl($title, $table,$field, $where = ' 1 ')
		{
		  
			$index = 0;
			$result = true;
			while($result){
			   $sef_url = $this -> view -> getSefUrl( stripcslashes($title) );
			   if($index > 0)$sef_url .= '-'.$index;
			   $result = $this -> getdetail($table,'sef_url', $where . ' AND sef_url="'.$sef_url.'.html"');
			   $index ++;
			}
			
			$sef_url .= '.html';
			return $sef_url;
		}
		
		function getdetail($table,$field,$where){
		  
		   global $db;
		   $select = $db -> select()
		                 -> from($table,array($field))
						 -> where($where);
					
		   $stmt = $db -> query($select);
		   $result = $stmt -> fetch();
		   
		   
		   if($result[$field]){
		     return $result[$field];
		   }else{
		     return false;
		   }	 
		}
		
		
	}
	
?>