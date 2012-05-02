<?php

class admin_Model_DbTable_Module extends Zend_Db_Table_Abstract
{

    protected $_name = 'modules';
   
    function getModules()
  		{
			$select = $this->_db->select()
            		->from($this->_name,array('modules.modules_name','modules.modules_id','modules.action'))
					->where("modules_status = '1'");
			$result = $this->_db->fetchAll($select);
			return $result;
		} 

}

