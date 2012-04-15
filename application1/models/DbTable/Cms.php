<?php

class Application_Model_DbTable_Cms extends Zend_Db_Table_Abstract
{

    protected $_name = 'cms';
   /***************************
   *
   * Get count of brands
   *
   ***************************/
   public function getCount(){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num');
	   return $this -> fetchRow($select) -> num;
	}
   /***************************
   *
   * Save brand
   *
   ***************************/
	public function save($data){
		if(isset($data['id']))
		{
			return $this->update($data,"id = $data[id]");
		}
		return $this->insert($data);
	}
   /***************************
   *
   * Delete brand
   *
   ***************************/
   public function deleteCms($id){
      $this -> delete('id='.$id);
	  //TODO: Delete assosiations with brands and products	  
   }
	
	function getListSelectObj($where=null,$orderby = null)
	{
		global $db;
		$selectObj = new Zend_Db_Select($db);
		$selectObj->from(array('p' => $this->_name),
						array('p.id','p.title','p.status','p.created')
					);
		
		if($where != null)
		{
			
			$selectObj->where($where);	
			
		}
		
		if ($orderby != null)
		{
			$selectObj->order($orderby);
		}
		else
		{
			$selectObj->order('id DESC');
		}
		
		return $selectObj;
		
	}
}

