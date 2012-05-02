<?php

class Admin_BusinessController extends Zend_Controller_Action
{

   
    public function init()
    {
        /* Initialize action controller here */
		//$this -> _helper -> layout -> disableLayout();
		
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirect('/admin/login/auth');
		}
			
		
		$this->translate = Zend_Registry::get('Zend_Translate');
		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

   
	
public function indexAction()
    {
    
    
		$breadCrumb =$this->translate->_('Business Management');
	
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Business Management'));
		
		$searchform = new Admin_Form_BusinessSearchForm();
				
		    $session = new Zend_Session_Namespace('business_search');

			if(!$this->_request->isPost() && !isset($session->filter)) //second condition is if search is set an click next should not execute this code
			{		
				$businessModel = new Application_Model_DbTable_Business();
				$result = $businessModel->getLinks(null,null);
				$totalRecords = count($result);
				$this->view->result = $result;
				$paginator = Zend_Paginator::factory($result);
				$paginator->setPageRange(5); 
				$paginator->setCurrentPageNumber($this->_getParam('page'));
					//echo $paginator;
				if($session->perPage!='')
				{
					$paginator->setItemCountPerPage($session->perPage);
					
				}
				else
				{	
					$paginator->setItemCountPerPage(50);
					
				}		
				
				$this->view->result= $paginator;
				//$session->id = $this->_request->getParam('id');
				$web_page = $this->_request->getParam('page');
				$this->view->page = $web_page;
			}
			
			//************if page is posted in case of sort,search,perpage**************//
			
			if($this->_request->isPost() || isset($session->filter) )
			{
			   
				//*****************VIEWS PER PAGE*****************//
				
				$perPage = $this->_request->getParam("perPage");
				if($perPage != '')
				{
				  if (isset($session->filter) && isset($session->filterText )) // to maintain search when user change views per page
				   {
				   		 //echo 'first';
						
						 $filter = $session->filter;
					     $filterText = $session->filterText ;
						 $paginator = $this->_performSearch($filter,$filterText,$perPage);
						 $this->view->result = $paginator;
						 //$searchform->populate(array('filters' => $filter,'filterText' => $filterText,'perPage'=> $perPage));
				   }
				   else
				   {
				   	   $paginator= $this->_index($perPage);
					   $this->view->result = $paginator ;
					   
				   }
				  
				  $session->perPage = $perPage;				   
				}
				
				if(isset($session->perPage))  // if user hit search or sort when landed first time when already perPage session is already set
				{
					$perPage = $session->perPage;
				}
				
								
				//****************** SORTING CODE *******************//
				
				$sort_col = $this->_request->getParam("sort_col"); //// Get column to apply sort through post
				if($sort_col != '')
				{
					$session->sort_col = $sort_col;  /// save the column in seesion 
					$order_by = 'DESC';
				} 
				else
				{
					$order_by = 'DESC';
				}
				if(!isset($session->order_by))  
				{
					$session->order_by = $order_by;  /// check if order value is set in session and save orderby value in session
				} 
				if($session->sort_col != '')
				{
					if($session->order_by == 'DESC')
					{
						$session->order_by = 'ASC';
					} 
					else
					{
						$session->order_by = 'DESC';
					} 
				
				}
				
				
				
				//************** Search Code*********************//
				
				$button = $this->_request->getParam("submitAction");
				$filter = $this->_request->getParam("filters");
				$filterText = $this->_request->getParam("filterText");
				if ($filter != '' && $filterText != '') 
				{
					$session->filter = $filter;
					$session->filterText = $filterText ;
					
				} 
				
				if($this->_request->getParam("submitAction") == $this->translate->_("Reset"))
				{
					Zend_Session::namespaceUnset('business_search');
					$this->_redirect("/admin/business/");
				}
				if($this->_request->getParam("submitAction") == $this->translate->_("Search") || isset($session->filter))
				{
				
				//if($session->filter==='BusinessName')  $session->filter='webTitle'; else  $session->filter='webTitle'; 
				    $filter = $session->filter;
					$filterText=$session->filterText; 	
					$paginator = $this->_performSearch($filter,$filterText,$perPage);
					$this->view->result = $paginator;
				}
						
				$searchform->populate(array('filters' => $filter,'filterText' => $filterText));
				
				if($session->sort_col != '' && $session->order_by != '' )
				{
				 
				  $sort_col = $session->sort_col;
				  $order_by = $session->order_by;
				  $paginator= $this->_performSort($sort_col,$order_by,$perPage);
				  $this->view->result = $paginator;
				}
				
				if($session->sort_col != '' && $session->order_by != '')
				{
				 
				  $sort_col = $session->sort_col;
				  $order_by = $session->order_by;
				
				  $paginator= $this->_performSort($sort_col,$order_by,$perPage);
				  $this->view->result = $paginator;
				}
				
			}
			
			if(isset($session->perPage))
			{
				$searchform->populate(array('perPage'=> $session->perPage));	
			}
			else
			{
			 	
				$searchform->populate(array('perPage'=>50));
				$session->perPage = 50;
			}
			
			$this->view->order_by = $session->order_by;	
			$this->view->sort_col = $session->sort_col;	
			$this->view->searchform = $searchform;
		
    
	}
	
	// delete action
	public function deleteAction(){
	   global $db;
	   $id = $this -> _request -> getParam('id');
	   $obj= new Application_Model_DbTable_Business();
	   $obj -> deleteBusiness($id);
	    $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->flashMessenger->addMessage('success');	
		$this->flashMessenger->addMessage($this->translate->_("Business Deleted"));	
	   $this -> _redirect('/admin/business');
	}
	
	
	 /*****************************
	 *
	 * Business Details
	 *
	 *****************************/
	public function detailAction(){
            $this->view->placeholder('heading')->set($this->translate->_('More'));
            $this->view->placeholder('buttonCaption')->set($this->translate->_('More'));
            $this->view->headTitle("{$this->translate->_('iceage')} > {$this->translate->_('Business Managment')} > {$this->translate->_('More')}");
            $this->view->placeholder('breadCrumb') -> set("{$this->translate->_('Business')} > {$this->translate->_('Business Managment')} > {$this->translate->_('More')}");
            $id = $this->_request->getParam('id');
            $catModel = new Application_Model_DbTable_Business();
            $this->view->result = $catModel->fetchRow("id = ".$id);
	}
	
	 /*****************************
	 *
	 * Add Business
	 *
	 *****************************/
	 public function addAction(){
	 
	 		$this->view->webid = $this->_request->getParam('id');
			$webid= $this->_request->getParam('id');
			$this->view->placeholder('heading')->set($this->translate->_('Add Business'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Business");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/business>'.$this->translate->_('Business Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
		
			$form = new Admin_Form_AddBusiness();
                        
                        
			$this->view->form = $form;
			
			// includes model end
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
														
					$Business_data = $form->getValues();
		
					$data = array(
						'businessName' => $Business_data['businessName'],
						'status' => $Business_data['status']
					);
					                 
				 			$rpModel = new Application_Model_DbTable_Business();
							$rpModel->save($data);
							$business_insert_id= $rpModel->getAdapter()->lastInsertId();
							if(!empty($business_insert_id))
							{
							
								$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
								$this->flashMessenger->addMessage('success');	
								$this->flashMessenger->addMessage($this->translate->_("Business successfully inserted"));	

							}
							else
							{
							$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
							$this->flashMessenger->addMessage('success');	
							$this->flashMessenger->addMessage($this->translate->_("Somthing is wrong with your data"));	
							
								$this->_redirect("admin/business/add/id/".$id);
							}
							
							$this->_redirect("admin/business");
					
					
				}
			}
	}

	public function statusAction(){
	    global $db;
            $id = $this -> _request -> getParam('id');
            $obj = new Application_Model_DbTable_Business();
            $businessData=$obj->getById($id); 
            if(!empty($businessData) && $businessData==1)
            $status=0;
            else
            $status=1;
            $data = array(
            'id' => $id,
            'status'=> $status,

            );
            $obj->save($data);
            $this -> _redirect('/admin/business');
	}
	
	public function editAction()
	{
			
	
	   $id = $this -> _request -> getParam('id');
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Business'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Business");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/business>'.$this->translate->_('Business Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddBusiness(array('id' => $id));
			$this->view->form = $form;
			$businessModel = new Application_Model_DbTable_Business();
			$where = "id = '".$id."'";
			$result = $businessModel->fetchRow($where);
			
			$array2Populate = array
                                            (
                                            'businessName'=> $result['businessName'],
                                            'status' => $result['status'],
                                            
                                            );
								
								//print_r($array2Populate);exit;
			$this->view->assign('res',$array2Populate);								
			$form->populate($array2Populate );
			$this->view->form = $form;
			
			if($this->_request->isPost())
			{
                            
                            
				if($form->isValid($_POST))
				{
					$business_data = $form->getValues();					
					$data = array(
					    'id' => $id,
						'businessName' => $business_data['businessName'],
						'status' => $business_data['status'],
					);
									
					$businessModel->save($data);
                                        
                                      
					$this->_redirect("admin/business");
					
					
				}
				
			}
			
			
	
	
	}
	
	 /*****************************
	 *
	 *  business Sorting
	 *
	 *****************************/	
	
	private function _performSort($sort_col,$order_by,$perPage)
			{
			$session = new Zend_Session_Namespace('business_Search');
			$bsModel = new Application_Model_DbTable_Business();
			$result = $bsModel->getLinks(null,null);
			$totalRecords = count($result);
			$order = $sort_col.' '.$order_by;
			if (isset($session->filter) && isset($session->filterText ))
			{
				$filterText = $session->filterText;
				$filter = $session->filter;
				$where = "$filter LIKE '%$filterText%'";
				$result = $bsModel->getLinks($where,$order);
			}
			else
			{
				$result = $bsModel->getLinks(null,$order);
			}	
			$paginator = Zend_Paginator::factory($result);
			$paginator->setPageRange(5); 
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			if($perPage == 'All')
			{	
				$paginator->setItemCountPerPage($totalRecords);	
			}
			elseif($perPage != '')
			{
				$paginator->setItemCountPerPage($perPage);
			}	
			elseif($session->perPage!='')
			{
				$paginator->setItemCountPerPage($session->perPage);
				
			}
			else
			{	
				$paginator->setItemCountPerPage(50);
				
			}		
			return $paginator;
			
		}
	private function _performSearch($filter,$filterText,$perPage)
			{
			$filterText =  trim($filterText);
			$filter =  trim($filter);
			$session = new Zend_Session_Namespace('Business_Search');
			$bsModel = new Application_Model_DbTable_Business();
            $where ="$filter LIKE '%$filterText%'";
			$result = $bsModel->getLinks($where,null);
			$totalRecords = count($result);
			
			$result = $bsModel->getLinks($where,null);
			$paginator = Zend_Paginator::factory($result);
			$paginator->setPageRange(5); 
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			if($perPage == 'All')
			{	
				$paginator->setItemCountPerPage($totalRecords);	
			}	
			elseif($perPage != '')
			{
				$paginator->setItemCountPerPage($perPage);
			}
			elseif($session->perPage!='')
			{
				$paginator->setItemCountPerPage($session->perPage);
				
			}
			else
			{	
				$paginator->setItemCountPerPage(50);
				
			}		
			return $paginator;
			
		}
		
		
	private function _index($perPage)
		{
			$session = new Zend_Session_Namespace('business_Search');
			$bsModel = new Application_Model_DbTable_Business();
                        $where ="";
			$result = $bsModel->getLinks($where,null);
			$totalRecords = count($result);
			$this->view->result = $result;
			
			$paginator = Zend_Paginator::factory($result);
			$paginator->setPageRange(5); 
			$paginator->setCurrentPageNumber($this->_getParam('page'));
			if($perPage == 'All')
			{	
				$paginator->setItemCountPerPage($totalRecords);	
			}
			elseif($perPage != '')
			{
				$paginator->setItemCountPerPage($perPage);
			}			
			elseif($session->perPage!='')
			{
				$paginator->setItemCountPerPage($session->perPage);
			
			}
			else
			{	
				$paginator->setItemCountPerPage(50);
			
			}		
			return $paginator;
			
		}
		
		// end for the sorting searching
		
	
}



