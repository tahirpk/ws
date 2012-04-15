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
 public function find($id){
 		 global $db;
        $select = $db->select();
        $select -> from('customers','FirstName')
   				 -> where('id=?',$id);
		 $stmt = $select->query();
         $result = $stmt->fetch();
		 return $result['FirstName'];
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
	  	$webModel = new Application_Model_DbTable_Websites();
	    $webModel->deleteWebById($id);
	  // DELETE Customer
	   $this -> delete('id='.$id);
	    
	}
	
	
	
	
	public function deletecustomerByWeb($id){
		$venueModel = new Application_Model_DbTable_Venue();
		$galleryModel = new Application_Model_DbTable_Gallery();
			$where = "category = '".$id."'";
			$result = $venueModel->fetchAll($where);
			 foreach($result as $rst) {
	   $galleryModel->deleteGalleryByVenue($rst['id']);
			 }
	   $this -> delete('category='.$id);
	    
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
	
}

