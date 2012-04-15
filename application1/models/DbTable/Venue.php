<?php

class Application_Model_DbTable_Venue extends Zend_Db_Table_Abstract
{ 

    protected $_name = 'venue';
    
	public function getFilterVenue($id)
	{
		global $db;
		$select = $db -> select();
		$select -> from ('venue')
				-> where(' status=1', $id);
		$stmt = $db->query($select);
		$result = $stmt->fetchAll();
		
		return $result;
	}
	/**
     * find name of merchant using it's name
     */
 public function find($id){
 		 global $db;
        $select = $db->select();
        $select -> from('venue','venue_name')
   				 -> where('id=?',$id);
		 $stmt = $select->query();
         $result = $stmt->fetch();
		 return $result['venue_name'];
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
	
	public function deleteVenue($id){
		// DELETE GALLERIES OF VENUE
	   $galleryModel = new Application_Model_DbTable_Gallery();
	   $galleryModel->deleteGalleryByVenue($id);
	   // DELETE VENUE
	   $this -> delete('id='.$id);
	    
	}
	public function deleteVenueByCat($id){
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
	* This function is used for Venue Detail
	*****************/
	public function getVenue(){
		return $this->select()->from($this -> _name, array('id','venue_name'))->where("status = '1'")->query()->fetchAll();
	}
	/*****************
	*
	* This function is used for Venue API
	*****************/
	public function getVenueForApi($id){
	
	 $select = $this->getAdapter()->select();
     $select->from(array('v'=>$this -> _name), array('v.*'))
            ->join(array('c'=>'categories'),'v.category= c.id',array('c.name as category_name'))
            ->where("v.id = ?",$id);
     return $this->getAdapter()->fetchRow($select);
	}
	/*****************
	*
	* This function is used for venue listing API
	*****************/
	public function getVenueListForApi($page,$per_page,$category_id){
	 	$limit = ($page-1)*$per_page;
		/*$results= $this->select()->from($this -> _name)->where("status = '1'")->limit($per_page,$limit)->query()->fetchAll();
 		 return $results;*/
		 if(!$category_id || $category_id==-1  ){
			 $select = $this->getAdapter()->select();
     $select->from(array('v'=>$this -> _name), array('v.*'))
            ->join(array('c'=>'categories'),'v.category= c.id',array('c.name as category_name'))
			->limit($per_page,$limit);
			//echo $select; die;
     		return $this->getAdapter()->fetchAll($select);
			}else {
				//show particular category id's record
			$select = $this->getAdapter()->select();
     $select->from(array('v'=>$this -> _name), array('v.*'))
            ->join(array('c'=>'categories'),'v.category= c.id',array('c.name as category_name'))
     		 ->where("v.category = ?",$category_id)->limit($per_page,$limit);
			 return $this->getAdapter()->fetchAll($select);	
			}
		}
	/*****************
	*
	* This function is used for venue Total record
	*****************/
/*	public function getAllVenue(){
		$results= $this->select()->from($this -> _name , array('total'=>'COUNT(*)'))->query()->fetchAll();
		return $results[0]['total'];
	}*/
	
	public function getVenueCountForApi($page,$per_page,$category_id){
	 	$limit = ($page-1)*$per_page;
		 if(!$category_id || $category_id==-1){
			/* $select = $this->getAdapter()->select();
     $select->from(array('v'=>$this -> _name), array('total'=>'COUNT(*)'))
            ->join(array('c'=>'categories'),'v.category= c.id',array('total'=>'COUNT(*)'));*/
			
			$results= $this->select()->from($this -> _name , array('total'=>'COUNT(*)'))->query()->fetchAll();
		return $results[0]['total'];
			
			//echo $select; die;
     		return $this->getAdapter()->fetchRow($select);
			}else {
				//show particular category id's record
			/*$select = $this->getAdapter()->select();
     $select->from(array('v'=>$this -> _name), array('total'=>'COUNT(*)'))
            ->join(array('c'=>'categories'),'v.category= c.id',array('total'=>'COUNT(*)'))
     		 ->where("v.category = ?",$category_id);
			 return $this->getAdapter()->fetchRow($select);*/	
			 $where = "category = '".$category_id."'";
			 $results= $this->select()->from($this -> _name , array('total'=>'COUNT(*)'))->where("category = '".$category_id."'")->query()->fetchAll();
		return $results[0]['total'];
			}
		}

	public function getCatID($name) {
		$select = $this -> select();
	   	$select -> from ($this -> _name, 'id')
	   		   -> where("venue_name='".$name."'");
		
		$stmt = $select->query();
		$result = $stmt->fetchAll();
		if(count($result)) {
			return $result[0]['id'];
		}
		return 0;
	}
}

