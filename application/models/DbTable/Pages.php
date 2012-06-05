<?php

class Application_Model_DbTable_Pages extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'pages';
    
		
    	public function getCount($where=null){
	   $select = $this -> select();
           if($where!=null)
	   $select -> from ($this -> _name, 'COUNT(*) as num')->where($where);
           else
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
	
	public function edit($data){
		
                 
		return	 $this->update($data,"webId = $data[webId]");
                
	}
	
	 
        public function getPageIds($webid){
		//$pageIds = $this->select()->from($this -> _name, array('id'))
               //         ->where('status = 1 and webId='.$webid)->query()->fetchAll();
                $select = $this -> select();
                $tpWhere='webId='.$webid;
                $select -> from ($this -> _name, 'COUNT(*) as num')->where($tpWhere);
                $totalPages= $this -> fetchRow($select) -> num;
                $greenRedWhere='reportCheckStatus=1 and webId='.$webid;
                $greenRedPages= $select -> from ($this -> _name, 'COUNT(*) as num')->where($greenRedWhere);
                $greenRedPages= $this -> fetchRow($select) -> num;
                if($greenRedPages==$totalPages) {
			
                        return '1'.$greenRedPages; 
		}else
                {
                   
                    return '0';
                }
		
	}
        /*****************
	*
	* This function is used for pages lists
	*****************/
	public function getAllPages(){
		return $this->select()->from($this -> _name, array('id','pageTitle'))->where('status = 1')
		-> order('id desc')->query()->fetchAll();
	}
	

	public function getById($id) {
		$select = $this -> select();
	   	$select -> from ($this -> _name, array('status','webId','reportCheckStatus','cronJobStatus'))
	   		   -> where("id='$id'");
		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if(count($result)) {
			return $result;
		}else
		return 0;
	}
	
	public function deletePages($id){
	    
	  // DELETE Page
	   $this -> delete('id='.$id);
	   
	}
        
        public function deleteByWebidPages($where){
	    
	  // DELETE Pages by webid
	   $this -> delete($where);
	   
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
                       // echo $select;
			$result = $this->_db->fetchAll($select);
			
			return $result;
			
		}
        
           function getPageWebsites($where=null) 
            {
               
               $select = $this->_db->select()
            		       ->from($this->_name );
                if($where != null)
                {

                    $select->where($where);	

                }
                
               // echo $select;
            return $result = $this->_db->fetchAll($select);            }
        
		
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
				$select -> from('pages',array('id','pageTitle'))
				-> where('id=?',$id);
				$stmt = $select->query();
				$result = $stmt->fetch();
                                return $result;
		}

                function getMaxPageId(){
			$maxPageId = 0;
			$select = $this->_db->select()
            		->from($this->_name, array('id' => 'MAX(id)'));
			$result = $this->_db->fetchAll($select);
			$maxPageId = $result[0]['id'];
			return $maxPageId;
		}
	
}

