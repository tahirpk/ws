<?php

class ApiController extends Zend_Controller_Action
{
    
    public function init()
    {
        /* Initialize action controller here */
		$this->_helper->layout->disableLayout();
        
    }
    /**
	 * API to return all active categories
	 */
	 
    public function categoriesAction()
    {
       $categoryModel = new Application_Model_DbTable_Category();
	   $categories = $categoryModel -> getCategoriesForApi() ;
	   $result['results'] = $categories;
	   if (count($categories)){
		   $result['status'] = 'OK';
	   } else {
		   $result['status'] = 'ZERO_RESULTS';
	   }
	   
	   print Zend_Json::encode($result);
	   exit;
    }
	
	/**
	 * API to return all venue listing
	 */
	 public function venuesAction(){
		 
		 $page = $this->_request->getParam('page');
   		$per_page = $this->_request->getParam('per_page');
		$category =$this->_request->getParam('category');

  	 	if(!$per_page || $per_page < 1)$per_page = 10;
   		if(!$page || $page < 1) $page = 1;
		
	 
	 	$venueModel = new Application_Model_DbTable_Venue();
		$list = $venueModel->getVenueListForApi($page,$per_page,$category);
		$results = $venueModel->getVenueCountForApi($page,$per_page,$category);
		$result['results'] = $list;
		if(count($list)){
			$result['status'] = 'OK';
			$result['count']  = $results['total'];
			}
			 else {
				$result['status'] = 'ZERO_RESULT';
			}
			
			print Zend_Json::encode($result);
			
			exit;
	 }
	 
	/**
	 * Venue Detail
	 * @param id
	 * @return venue detail
	 */
	 public function venueAction(){
		 
		$venue_id = $this -> _request -> getParam('id');
		if(!$venue_id || !is_numeric($venue_id)){
			 $result['status'] = 'INVALID_QUERY';
			 print Zend_Json::encode($result);
			 exit;
	    }
		
	     $venueModel = new Application_Model_DbTable_Venue();
		 $venue = $venueModel->getVenueForApi($venue_id);
		 if($venue){
			// $result = $venue -> toArray();
			 $result['results'] =  $venue;
			 $result['status'] = 'OK';
		 } else {
			 $result['status'] = 'ZERO_RESULT';
		 }
	
		 print Zend_Json::encode($result);
		 exit;
	 }

	
	

	
	
	

}

