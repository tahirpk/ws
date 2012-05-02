<?php

class Application_Model_DbTable_Business extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'business';
    
		
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
	
	
	  
	/*****************
	*
	* This function is used for Businesss lists
	*****************/
	public function getAllBusiness(){
		return $this->select()->from($this -> _name, array('id','businessName'))->where('status = 1')
		-> order('id desc')->query()->fetchAll();
	}
	

	public function getBusinessId($id) {
		$select = $this -> select();
	   	$select -> from ($this -> _name, 'status')
	   		   -> where("id='$id'");
		
		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if(count($result)) {
			return $result[0]['status'];
		}
		return 0;
	}
	
	public function deleteBusiness($id){
	   
	   
	  // DELETE Business
	   $this -> delete('id='.$id);
	    
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
				$select->order('id ASC');
			}
			$result = $this->_db->fetchAll($select);
			
			return $result;
			
		}
		
	public	function getCustomerWebData($customerId) {
						$select = $this->_db->select()-> from(array('w' => 'businesss'),array('w.id','w.webTitle','w.url'))
						->joinLeft(array('cw' => 'customer_Business'),'w.id=cw.webId and cw.customerFid='.$customerId,array('cid' => 'id'))
						-> where('status = 1')
						->order('w.id ASC');
						$result = $this->_db->fetchAll($select);
						return $result;
						
		}	
		
		public function findName($id){ 
				global $db;
				$select = $db->select();
				$select -> from('business',array('id','businessName'))
				-> where('id=?',$id);
				$stmt = $select->query();
				$result = $stmt->fetch();
                                return $result;
		}

	
	
}

