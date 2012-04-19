<?php

class Application_Model_DbTable_CustomerWebsites extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'customer_websites';
    
	/*****************
	*
	* This function is used in admin side to return total record
	*****************/
	public function getCount($where=NULL){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num')->where($where);
	  // echo $select;die;
	  return $this -> fetchRow($select) -> num;
	}
	
	/*****************
	*
	* This function is used in admin side to add  record
	*****************/
	public function save($data){
		
		return $this->insert($data);
	}
	
	
	/*****************
	*
	* This function is used in admin side to delete  record
	*****************/
		public function deleteByWebidOrCustomerId($where){
	   	
			$this -> delete($where);
		}
		
	  
	  
	 
	
	/*****************
	*
	* This function is used for newslist api to return records
	*****************/
	public function getById($id){
		
		global $db;
		$results = $this->_db->select()->from($this -> _name, array('id','customerFid','webId'))->where("id=".$id)->query()->fetchAll();
		return $results;
	}
	
		
	public function getCatlectureWhere($where){
		global $db;
			$results = $this->_db->select()->from(array('w' => 'websites'), array('w.id','webTitle','w.url'))
		->joinRight(array('cw' => ' customer_websites'),'cw.webId=w.id',array('cwid' => 'id'))
		-> where($where)->limit(10,0)
		 			-> order('l.id desc')->query()->fetchAll();
					

		//->limit($per_page,$limit)->query()->fetchAll();
		return $results;
		
		}
	
	
	
	
	
	
}

