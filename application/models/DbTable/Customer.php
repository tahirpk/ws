<?php

class Application_Model_DbTable_Customer extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'customers';
    
	public function getFilterCustomer($id)
	{
		global $db;
		$select = $db -> select();
		$select -> from ('customers')
				-> where(' Status=1', $id);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		
		return $result;
	}
	/**
     * find name of customer using it's name
     */
 public function findName($id){
 		 global $db;
        $select = $db->select();
        $select -> from('customers',array('FirstName','LastName'))
   				 -> where('id=?',$id);
		 $stmt = $select->query();
         $result = $stmt->fetch();
		 return $result;
 }
	
	
	
	public function getCount(){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num');
	   return $this -> fetchRow($select) -> num;
	}
	// save
	public function save($data){
		if(isset($data['id']))
		{
			return $this->update($data,"id = $data[id]");
		}
		return $this->insert($data);
	}
	
	public function deleteCustomer($id){
	   // DELETE Websites OF Customer
	    $cwModel = new Application_Model_DbTable_CustomerWebsites();
		$where = "customerFid = '".$id."'";
		$result = $cwModel->deleteByWebidOrCustomerId($where);
	  // DELETE Customer
	   $this -> delete('id='.$id);
	    
	}
	
	
	
	
	/*****************
	*
	* This function is used for Customer Detail
	*****************/
	public function getCustomer(){
		return $this->select()->from($this -> _name, array('id','FirstName'))->where("Status = '1'")->query()->fetchAll();
	}
	
	
	public function getById($id){
		
		$results= $this->select()->from($this -> _name, array('id','FirstName','LastName','Address1','Status'))->where("id=".$id)-> order('firstname asc')->query()->fetchAll();
		return $results;
	}
	
	public function getLinks($where=null,$orderby = '')
		{

							
			$select = $this->_db->select()
            		       ->from($this->_name );
							
			if($where != null)
			{
				
				$select->where($where);	
				
			}
			
			if ($orderby != null)
			{
				$select->order($orderby);
			}
			else
			{
				$select->order('FirstName ASC');
			}
			$result = $this->_db->fetchAll($select);
			
			return $result;
			
		}
		
		
		// login customer
		
		function checkUserName($email)
		{
			global $db;
			$select = $db->select()
						->from($this->_name, array('id','FirstName','LastName','Email'))
						->where("Email = '".$email."' AND Status = '1'");
			$result = $db->fetchAll($select);
			if(count($result)>0){
				return true;
			}
			return false;		
		}
	
}

