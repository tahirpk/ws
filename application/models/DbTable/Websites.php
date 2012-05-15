<?php

class Application_Model_DbTable_Websites extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'websites';
    
		
		public function getCount(){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num');
	   return $this -> fetchRow($select) -> num;
	}
	
	/*****************
	*
	* This function is used in admin side to return total record
	*****************/
	public function getCountCustomerWeb($customerId){
	  $select='SELECT COUNT(customerFid) as num FROM `websites` AS `w` INNER JOIN `customer_websites` AS `cw` ON w.id=cw.webId and status=1 and cw.customerFid='.$customerId;
	//  echo $select;die;
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
	   
	        $cwModel = new Application_Model_DbTable_CustomerWebsites();
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
	
            function getMyWebsites($customerId) 
            {
                $select = $this->_db->select()-> from(array('w' => 'websites'),array('w.id','w.webTitle','w.url','w.status'))
		->join(array('cw' => 'customer_websites'),'w.id=cw.webId and cw.customerFid='.$customerId,array('cid' => 'id'));
               return $result = $this->_db->fetchAll($select);
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
        
        public function getUrlById($id) {
		$select = $this -> select();
	   	$select -> from ($this -> _name, array('WebTitle','url'))
	   		   -> where("status=1 and id='$id'");
        	$stmt = $select->query();
		$result = $stmt->fetchAll();
		if(count($result)) {
			return $result;
		}
		return 0;
	}
	
	public function getLinks($where=null,$orderby = '',$customerId=null)
		{

							
			$select = $this->_db->select()
            		       ->from($this->_name );
							
			if($where != null)
			{
				
				$select->where($where);	
				
			}
			if($customerId != null)
			{
				$select = $this->_db->select()-> from(array('w' => 'websites'),array('w.id','w.webTitle','w.url','w.status'))
				->join(array('cw' => 'customer_websites'),'w.id=cw.webId and cw.customerFid='.$customerId,array('cid' => 'id'));
				
				
				//echo $select; die();
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
						$select = $this->_db->select()-> from(array('w' => 'websites'),array('w.id','w.webTitle','w.url'))
						->joinLeft(array('cw' => 'customer_websites'),'w.id=cw.webId and cw.customerFid='.$customerId,array('cid' => 'id'))
						-> where('status = 1')
						->order('w.id ASC');
						$result = $this->_db->fetchAll($select);
						return $result;
						
		}	
		
		public function findName($id){
				global $db;
				$select = $db->select();
				$select -> from('websites',array('webTitle','url'))
				-> where('id=?',$id);
				$stmt = $select->query();
				$result = $stmt->fetch();
				return $result;
		}

	public function getAllWebUrl(){
		return $this->select()->from($this -> _name, array('url'))->where('status = 1')
		-> order('id desc')->query()->fetchAll();
	}
	
}

