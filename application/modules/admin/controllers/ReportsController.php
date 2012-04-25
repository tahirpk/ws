<?php

class Admin_ReportsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		//$this -> _helper -> layout -> disableLayout();
		//$this->_helper->layout->setLayout('frontend');
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirect('/admin/login/auth');
		}
			
		
		$this->translate = Zend_Registry::get('Zend_Translate');
		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

   
	
	public function indexAction()
    {
		
		$breadCrumb =$this->translate->_('Reports Management');
	
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Reports Management'));
		$webId = $this->_request->getParam('id');
		$this->view->webId=$webId;
		$searchform = new Admin_Form_ReportSearchForm();
				
		    $session = new Zend_Session_Namespace('report_search');

			if(!$this->_request->isPost() && !isset($session->filter)) //second condition is if search is set an click next should not execute this code
			{		
				$reportModel = new Application_Model_DbTable_Reports();
				$result = $reportModel->getLinks(null,null,$webId);
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
				
				//*************
				//*** for the customer website search for sorting
				//*************
								
				 $webId_set = $this->_request->getParam("webId");  //Get column to apply sort through post
					if($webId_set != '')
				{   
					$session->webId_set = $webId_set;  /// save the column in seesion 
					$order_by = 'DESC';
					
					
				} 
				else
				{
					$order_by = 'DESC';
				}
				
				if($session->webId_set != '')
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
					Zend_Session::namespaceUnset('report_search');
					$this->_redirect("/admin/reports/");
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
				
				if($session->sort_col != '' && $session->order_by != '' || $session->webId_set != '')
				{
				 
				  $sort_col = $session->sort_col;
				  $order_by = $session->order_by;
				  $webId_set = $session->webId_set; 
				  $paginator= $this->_performSort($sort_col,$order_by,$perPage,$webId_set);
				  $this->view->result = $paginator;
				}
				
				if($session->sort_col != '' && $session->order_by != '' && $session->webId_set != '')
				{
				 
				  $sort_col = $session->sort_col;
				  $order_by = $session->order_by;
				  $webId_set = $session->webId_set; 
				  $paginator= $this->_performSort($sort_col,$order_by,$perPage,$webId_set);
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
	   $obj= new Application_Model_DbTable_Reports();
	   $obj -> deleteById($id);
	    $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->flashMessenger->addMessage('success');	
		$this->flashMessenger->addMessage($this->translate->_("Report Deleted"));	
	   $this -> _redirect('/admin/reports');
	}
	
	
	 /*****************************
	 *
	 * customer Details
	 *
	 *****************************/
	public function detailAction(){
	    $this->view->placeholder('heading')->set($this->translate->_('More'));
		$this->view->placeholder('buttonCaption')->set($this->translate->_('More'));
		$this->view->headTitle("{$this->translate->_('iceage')} > {$this->translate->_('Customer Managment')} > {$this->translate->_('More')}");
        $this->view->placeholder('breadCrumb') -> set("{$this->translate->_('iceage')} > {$this->translate->_('Customer Managment')} > {$this->translate->_('More')}");
	    $id = $this->_request->getParam('id');
	    $catModel = new Application_Model_DbTable_Reports();
		$this->view->result = $catModel->fetchRow("id = ".$id);
	}
	
	 /*****************************
	 *
	 * Add customer
	 *
	 *****************************/
	 public function addAction(){
	 
	 		$this->view->webid = $this->_request->getParam('id');
			$webid= $this->_request->getParam('id');
			$this->view->placeholder('heading')->set($this->translate->_('Add Report'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Report");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/reports>'.$this->translate->_('Report Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			// for the websites assing to the customer
	
			$form = new Admin_Form_AddReports();
			$this->view->form = $form;
			
			// includes model end
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$originalFilename = pathinfo($form->filePdf->getFileName());
				//	print_r($originalFilename);die();
					$newFilename=time().'_'.$originalFilename['basename'];
					$form->filePdf->addFilter('Rename', $newFilename);
					
					$report_data = $form->getValues();
					$date = DateTime::createFromFormat('d-m-Y', $report_data['dateTime']);
					
					$data = array(
						'dateTime'=> $date->format('Y-m-d'),
						'webId' =>$webid,
					   	'filePdf' => $newFilename,
						'status' => $report_data['status']
					);
					                 
				 			$rpModel = new Application_Model_DbTable_Reports();
							$rpModel->save($data);
							$customer_insert_id= $rpModel->getAdapter()->lastInsertId();
							if(!empty($customer_insert_id))
							{
							
								$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
								$this->flashMessenger->addMessage('success');	
								$this->flashMessenger->addMessage($this->translate->_("Report successfully inserted"));	

							}
							else
							{
							$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
							$this->flashMessenger->addMessage('success');	
							$this->flashMessenger->addMessage($this->translate->_("Somthing is wrong with your data"));	
							
								$this->_redirect("admin/reports/add/id/".$id);
							}
							
							$this->_redirect("admin/reports");
					
					
				}
			}
	}

		public function statusAction(){
	    global $db;
	    $id = $this -> _request -> getParam('id');
	    $obj = new Application_Model_DbTable_Reports();
		$reportData=$obj->getById($id); 
		if(!empty($reportData) && $reportData==1)
		$status=0;
		else
		$status=1;
		$data = array(
					    'id' => $id,
						'status'=> $status,
						
					);
					$obj->save($data);
	   $this -> _redirect('/admin/reports');
	}
	
	public function editAction()
	{
			
	
	   $id = $this -> _request -> getParam('id');
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Report'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Report");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/reports>'.$this->translate->_('Report Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddReports(array('id' => $id));
			$this->view->form = $form;
			$id = $this->_request->getParam('id');
			$reportModel = new Application_Model_DbTable_Reports();
			$where = "id = '".$id."'";
			$result = $reportModel->fetchRow($where);
			$this->view->webid=$result['webId'];
			$array2Populate = array
								(
								    'dateTime'=> stripslashes($result['dateTime']),
									'webId'=> stripslashes($result['webId']),
									'status' => $result['status'],
									'filePdf' => $result['filePdf'],
								);
								
								//print_r($array2Populate['icon']);exit;
			$this->view->assign('res',$array2Populate);								
			$form->populate($array2Populate );
			$this->view->form = $form;
			
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$report_data = $form->getValues();
					$date = DateTime::createFromFormat('d-m-Y', $report_data['dateTime']);
					$data = array(
					    'id' => $id,
						'dateTime' => $date->format('Y-m-d'),
						'filePdf' => $report_data['filePdf'],
						'status' => $report_data['status'],
					);
					//if icon not updated then it will not required to upload icon again
					
					if($form -> filePdf -> getFileName()){
						// pdf needs to be updated
						$reportModel -> deletefile($id);//delete the icon which already exists
						$data['filePdf'] = 'frontend/frontend/webpdf/'.$report_data['filePdf'];
					}
				
					$reportModel->save($data);
					$this->_redirect("admin/reports");
					
					
				}
				
			}
			
			
	
	
	}
	
	 /*****************************
	 *
	 *  Customer Sorting
	 *
	 *****************************/	
	
	private function _performSort($sort_col,$order_by,$perPage,$webId_set)
			{
			$session = new Zend_Session_Namespace('Customer_Search');
			$custModel = new Application_Model_DbTable_Reports();
			$result = $custModel->getLinks(null,null,$webId_set);
			$totalRecords = count($result);
			$order = $sort_col.' '.$order_by;
			if (isset($session->filter) && isset($session->filterText ))
			{
				$filterText = $session->filterText;
				$filter = $session->filter;
				$where = "$filter LIKE '%$filterText%'";
				$result = $custModel->getLinks($where,$order,$webId_set);
			}
			else
			{
				$result = $custModel->getLinks(null,$order,$webId_set);
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
			$session = new Zend_Session_Namespace('Customer_Search');
			$custModel = new Application_Model_DbTable_Reports();
            $where ="$filter LIKE '%$filterText%'";
			$result = $custModel->getLinks($where,null);
			$totalRecords = count($result);
			
			$result = $custModel->getLinks($where,null);
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
			$session = new Zend_Session_Namespace('report_Search');
			$custModel = new Application_Model_DbTable_Reports();
             $where ="";
			$result = $custModel->getLinks($where,null);
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

