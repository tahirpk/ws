<?php

class Application_Model_DbTable_Gallery extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'gallery';
    
	public function getFilterGallery($id)
	{
		global $db;
		$select = $db -> select();
		$select -> from ('gallery')
				-> where(' status=1', $id);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		
		return $result;
	}
	
	
	
	
	public function getCount($id){
	   $select = $this -> select();
	   $select -> from ($this -> _name, 'COUNT(*) as num')
	   		   ->where('venue_id ='.$id);
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
	
	public function deleteGallery($id){
	 
	   $this -> delete('id='.$id);
	    
	}
	public function deleteGalleryByVenue($id){
	 
	   $this -> delete('venue_id='.$id);
	    
	}
	/*****************
	*
	* This function is used for Categories dropdown
	*****************/
	public function getGallery(){
		return $this->select()->from($this -> _name, array('id','image','video','venue_id'))->where('status = 1')->query()->fetchAll();
	}

	public function getCatID($name) {
		$select = $this -> select();
	   	$select -> from ($this -> _name, 'id')
	   		   -> where("image='$name'");
		
		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if(count($result)) {
			return $result[0]['id'];
		}
		return 0;
	}
}

