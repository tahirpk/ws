<?php

class Application_Model_DbTable_Websites extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'websites';
    
		
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
	
	public function deleteWebById($id){
	   
	   $cwModel = new Application_Model_DbTable_customerWebsites();
		$where = "webId = '".$id."'";
		$result = $cwModel->deleteByWebidOrCustomerId($where);

	   $this -> deletefile($id);	
	   $this -> delete('id='.$id);
	    
	}
	
	public function deletefile($id) { 
	   $obj = $this -> fetchRow('id='.$id);
	   $data = $obj -> toArray();
	   $fullfilePath = realpath(APPLICATION_PATH."/../public/frontend/webpdf") . '/' . $data['filePdf'];	
	   if(file_exists($fullfilePath)){
	     @unlink($fullfilePath);
	   }
	}
	  
	/*****************
	*
	* This function is used for websites lists
	*****************/
	public function getAllWebsites(){
		return $this->select()->from($this -> _name, array('id','webTitle'))->where('status = 1')
		-> order('id desc')->query()->fetchAll();
	}
	
	public function getexistingLecPaginator($page, $per_page,$cid=0){
		if(!$per_page || $per_page < 1)$per_page = 10;
		 if(!$page || $page < 1) $page = 1;
		 $limit = ($page-1)*$per_page;
		 	global $db;
			$results = $this->_db->select()-> from(array('w' => 'websites'),array('w.id','w.webTitle','w.url'))
					->joinLeft(array('cw' => 'customer_websites'),'w.id=cw.webId and cw.customerFid='.$cid,array('cid' => 'id'))
					-> where('status = 1')
					-> limit($per_page,$limit)
		 			-> order('w.webTitle asc')->query()->fetchAll();
					
     				return $results;
		}
		
	public function getExistCount($cid){
	
	  global $db;
		$results = $this->_db->select()-> from (array('w' => 'websites'), 'COUNT(*) as num')
				->joinLeft(array('cw' => 'customer_websites'),'w.id=cw.webId and cw.customerFid='.$cid,array('cid' => 'id'))
				-> where('cw.id is null')
				-> order('w.webTitle asc')->query()->fetchAll();
				//print_r($results);die;
	   return $results;
	   
	}
	
	/*****************
	*
	* This function is used for websites API
	*****************/
	public function getWebitesForApi(){
		return $this->select()->from($this -> _name)->where("status = '1'")->query()->fetchAll();
	}

	public function getWebID($id) {
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
				$select->order('webTitle ASC');
			}
			$result = $this->_db->fetchAll($select);
			
			return $result;
			
		}
	
	
}

