<?php

class Application_Model_DbTable_Reports extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'reports';
    
		
	public function getCount($where){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num')->where($where);
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
	// delet
	public function deleteById($id){
	   
	  
	   $this -> deletefile($id);	
	   $this -> delete('id='.$id);
	    
	}
	// delete files
	public function deletefile($id) { 
	   $obj = $this -> fetchRow('id='.$id);
	   $data = $obj -> toArray();
	   $fullfilePath = realpath(APPLICATION_PATH."/../public/frontend/webpdf/") .'/'. $data['filePdf'];	
	   if(file_exists($fullfilePath)){ 
	     @unlink($fullfilePath);
	   }
	}
	  
	/*****************
	*
	* This function is used for websites lists
	*****************/
	public function getAllWebsites(){
		return $this->select()->from($this -> _name, array('id','createdAt','filePdf'))->where('status = 1')
		-> order('id desc')->query()->fetchAll();
	}
	
	
	
	/*****************
	*
	* 
	*****************/
	

	public function getById($id) {
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
	
	public function getLinks($where=null,$orderby = '',$webId=null)
		{

							
			$select = $this->_db->select()
            		       ->from($this->_name );
							
			if($where != null)
			{
				
				$select->where($where);	
				
			}
			if($webId != null)
			{
			$select = $this->_db->select()-> from(array('rp' => 'reports'),array('rp.id','rp.dateTime','rp.webId','rp.filePdf','rp.createdAt','rp.status'))
			->join(array('w' => 'websites'),'w.id=rp.webId and rp.webId='.$webId,array('wid' => 'id'))
			->order('rp.id ASC');
			
				//echo $select; die();
			}
			if ($orderby != null)
			{
				$select->order($orderby);
			}
			else
			{
				$select->order('dateTime ASC');
			}
			$result = $this->_db->fetchAll($select);
			
			return $result;
			
		}
	
	
}

