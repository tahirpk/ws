<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';
    
	function checkUser($data)
  		{
			global $db;
			$select = $this->_db->select()
            	->from($this->_name)
            	->where("user_initial = '".$data['user_intial']."'");
			return $result = $this->_db->fetchRow($select);
		}
	
	function checkUserInitial($userinitial, $id)
	{
		$select = $this->select()
			->from($this->_name,
				array('user_initial'))
			->where("user_initial = '".$userinitial."' AND id != '".$id."'");
		$result = $this->fetchAll($select);
			
		if(count($result)>0) {
			return 1;
		} else {
			return 2;
		}
	}
	// check user email
	function checkUserEmail($email, $id)
	{
		$select = $this->select()
			->from($this->_name,
				array('email'))
			->where("email = '".$email."' AND id != '".$id."'");
		$result = $this->fetchAll($select);
			
		if(count($result)>0) {
			return 1;
		} else {
			return 2;
		}
	}	
		
	
	
		
}

