<?php

class Application_Model_DbTable_Menu extends Zend_Db_Table_Abstract
{

    protected $_name = 'menu';


	public function getCount(){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num');
	   return $this -> fetchRow($select) -> num;
	}
	// save
	public function save($data, $id){
		if(isset($id))
		{
			
	       return $this->update($data,'id = '.$id);
                        
		}
		return $this->insert($data);
	}
	
	public function deleteRow($id){
	   
          
	   $this ->delete('id='.$id);
	    
	}
	
	
		 
	/*****************
	*
	* This function is used for Categories dropdown
	*****************/
	public function getCategories(){
		/*return $this->select()->from($this -> _name, array('id','menu_name'))->where('status = 1')->query()->fetchAll();
	}

	public function getCatID($name) {
		$select = $this -> select();
	   	$select -> from ($this -> _name, 'id')
	   		   -> where("name='$name'");
		
		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if(count($result)) {
			return $result[0]['id'];
		}
		return 0;*/
	}
}


