<?php

class Admin_WebsitesController extends Zend_Controller_Action
{

    public function init()
    {
       /* Initialize action controller here */
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirect('/admin/login/auth');
		}
		$this->translate = Zend_Registry::get('Zend_Translate');
		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
		// index actiom
    public function indexAction()
    {
	$breadCrumb =$this->translate->_('Websites Management');
	
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Websites Management'));
		
		$searchform = new Admin_Form_SearchForm();
				
		$session = new Zend_Session_Namespace('Link_Search');

			if(!$this->_request->isPost() && !isset($session->filter)) //second condition is if search is set an click next should not execute this code
			{		
				$webModel = new Application_Model_DbTable_Websites();
				$result = $webModel->getLinks(null,null);
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
					Zend_Session::namespaceUnset('Link_Search');
					$this->_redirect("/admin/websites/");
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
	
	public function detailAction(){
	    $this->view->placeholder('heading')->set($this->translate->_('More'));
		$this->view->placeholder('buttonCaption')->set($this->translate->_('More'));
		$this->view->headTitle("{$this->translate->_('iceage')} > {$this->translate->_('Category Managment')} > {$this->translate->_('More')}");
        $this->view->placeholder('breadCrumb') -> set("{$this->translate->_('iceage')} > {$this->translate->_('Category Managment')} > {$this->translate->_('More')}");
	    $id = $this->_request->getParam('id');
	    $catModel = new Application_Model_DbTable_Category();
		$this->view->result = $catModel->fetchRow("id = ".$id);
	}
	
	
	/**
	 * delete action
	 * Delete the specific user data
	 */
	public function deleteAction(){
	   global $db;
	   $id = $this -> _request -> getParam('id');
	   $objWeb= new Application_Model_DbTable_Websites();
	   $objWeb -> deleteWebById($id);
	  
	    $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->flashMessenger->addMessage('success');	
		$this->flashMessenger->addMessage($this->translate->_("Website Deleted"));	   
	   $this -> _redirect('/admin/websites');
	  
	}
	
	 /*****************************
	 *
	 * Add category
	 *
	 *****************************/
	 public function addAction(){
		 	
			$this->view->placeholder('heading')->set($this->translate->_('Add Website'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Website");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/websites>'.$this->translate->_('Websites Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddWebsites();
			
			$this->view->form = $form;

			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$web_data = $form->getValues();
					$data = array(
  					    'webTitle' => $web_data['webTitle'],
					    'url' => $web_data['url'],
						'status' => $web_data['status'],
						'filePdf' => 'frontend/webpdf/'.$web_data['filePdf'],
					);
					$webModel = new Application_Model_DbTable_Websites();
					$webModel->save($data);
					$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
					$this->flashMessenger->addMessage('success');	
					$this->flashMessenger->addMessage($this->translate->_("Website Added"));	
					$this->_redirect("admin/websites");
				}
			}
	}
	 /*****************************
	 *
	 * Edit Websites
	 *
	 *****************************/
	
	function editAction(){
	
	   $id = $this -> _request -> getParam('id');
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Website'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Website");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/websites>'.$this->translate->_('Website Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddWebsites(array('id' => $id));
			$this->view->form = $form;
			$id = $this->_request->getParam('id');
			$webModel = new Application_Model_DbTable_Websites();
			$where = "id = '".$id."'";
			$result = $webModel->fetchRow($where);
			$array2Populate = array
								(
								    'webTitle'=> stripslashes($result['webTitle']),
									'url'=> stripslashes($result['url']),
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
					$web_data = $form->getValues();
					
					$data = array(
					    'id' => $id,
						'webTitle' => $web_data['webTitle'],
						'url' => $web_data['url'],
						'filePdf' => $web_data['filePdf'],
						'status' => $web_data['status'],
					);
					//if icon not updated then it will not required to upload icon again
					
					if($form -> filePdf -> getFileName()){
						// pdf needs to be updated
						$webModel -> deletefile($id);//delete the icon which already exists
						$data['filePdf'] = 'frontend/frontend/webpdf/'.$web_data['filePdf'];
					}
					
                    $webModel = new Application_Model_DbTable_Websites();
					$webModel->save($data);
					$this->_redirect("admin/websites");
					
					
				}
				
			}
			
			
	}
	
	
	 /*****************************
	 *
	 *  Websites Sorting
	 *
	 *****************************/	
	
	private function _performSort($sort_col,$order_by,$perPage)
			{
			$session = new Zend_Session_Namespace('Link_Search');
			$linkModel = new Application_Model_DbTable_Websites();
			$result = $linkModel->getLinks(null,null);
			$totalRecords = count($result);
			$order = $sort_col.' '.$order_by;
			if (isset($session->filter) && isset($session->filterText ))
			{
				$filterText = $session->filterText;
				$filter = $session->filter;
				$where = "$filter LIKE '%$filterText%'";
				$result = $linkModel->getLinks($where,$order);
			}
			else
			{
				$result = $linkModel->getLinks(null,$order);
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
			$session = new Zend_Session_Namespace('Link_Search');
			$linkModel = new Application_Model_DbTable_Websites();
            $where ="$filter LIKE '%$filterText%'";
			$result = $linkModel->getLinks($where,null);
			$totalRecords = count($result);
			
			$result = $linkModel->getLinks($where,null);
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
			$session = new Zend_Session_Namespace('Link_Search');
			$linkModel = new Application_Model_DbTable_Websites();
             $where ="";
			$result = $linkModel->getLinks($where,null);
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
		
	/**
	 * status action
	 * Change Status the specific user data
	 */
	public function statusAction(){
	   global $db;
	   $id = $this -> _request -> getParam('id');
	    $obj = new Application_Model_DbTable_Websites();
	 	$rs=$obj->getWebID($id); 
		if(!empty($rs) && $rs==1)
		$status=0;
		else
		$status=1;
		$data = array(
					    'id' => $id,
						'status'=> $status,
						
					);
					$obj->save($data);
	   $this -> _redirect('/admin/websites');
	}
}

