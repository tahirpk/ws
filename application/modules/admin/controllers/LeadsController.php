<?php

class Admin_LeadsController extends Zend_Controller_Action
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
    
    
		$breadCrumb =$this->translate->_('Free  sites reviews.');
	
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Free  sites reviews'));
	      //  $pageModel = new Application_Model_DbTable_Pages();
                $wbModel = new Application_Model_DbTable_NCustomer();
                
                
                $selectObj = $wbModel -> select();
                 	 	 	 	 	 	
		
		$selectObj -> from(array( 'ncustomers'),array('customerName','id','email','website','phoneNo','dateCreated','status'))
		 	  -> order('id desc');
				   
              
                $recCount= $wbModel->getCount();
               //print_r($selectObj); die();
                $perPage = 10; // set values per page
                $paginator = $adminPaginator->_getPaginatorData($this -> _getParam('page'), $perPage, $selectObj, $recCount);
		$this->view->paginator = $paginator;
                $this->view->result = $paginator;
                
	}
        //end of the cronjob listing page
	// delete action
	public function deleteAction(){
            $id = $this -> _request -> getParam('id');
            $webid = $this -> _request -> getParam('webid');
            $obj= new Application_Model_DbTable_Pages(); 
            $resul= $obj -> getById($id);
            $webid =$resul[0]['webId']; 
            $obj -> deletePages($id);
            $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $this->flashMessenger->addMessage('success');	
            $this->flashMessenger->addMessage($this->translate->_("Page Deleted"));	
            $this -> _redirect('/admin/pages/index/webid/'.$webid);
	}
	
	
	 /*****************************
	 *
	 * Pages Details
	 *
	 *****************************/
	public function detailAction()
        {
            $this->view->placeholder('heading')->set($this->translate->_('Customer Detail'));
            $this->view->placeholder('buttonCaption')->set($this->translate->_('Customer Detail'));
            $this->view->headTitle("{$this->translate->_('Customer Detail')} > {$this->translate->_('Customer site detail')} > {$this->translate->_('Customer Detail')}");
            $this->view->placeholder('breadCrumb') -> set("{$this->translate->_('leads')} > {$this->translate->_(' Customer Detail')} ");
            $id = $this->_request->getParam('id');
            $pgModel = new Application_Model_DbTable_NCustomer();
            $this->view->result = $pgModel->fetchRow("id = ".$id);
	}
	
	 /*****************************
	 *
	 * Add Page
	 *
	 *****************************/
	 public function addAction(){
	 
	 		$this->view->webid = $this->_request->getParam('id');
			$webid= $this->_request->getParam('id');
			$this->view->placeholder('heading')->set($this->translate->_('Add Page'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Page");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/pages>'.$this->translate->_('Page Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
		
			$form = new Admin_Form_AddPage();
                        
                        
			$this->view->form = $form;
			
			// includes model end
		if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$web_data = $form->getValues();
                                        if(!empty($web_data['pageTitle']))
                                          {
                                             $report =1;
                                             $reportChkStatus =0;
                                         }
                                         if(!empty($web_data['pageTitle']) && !empty($web_data['pageKeywords']))
                                            {
                                             $report =2;
                                             $reportChkStatus =0;
                                         }
                                         if(!empty($web_data['pageTitle']) && !empty($web_data['pageKeywords']) && !empty($web_data['pageContent']))    
                                             {
                                             $report =3;
                                             $reportChkStatus =1;
                                         }
                                        else {
                                            $report =0;
                                             $reportChkStatus =0;
                                        }
                                            
                                            $data = array(
                                            'webId' => $web_data['webId'],
                                            'pageUrl'=> $web_data['pageUrl'],
  					    'pageTitle' => $web_data['pageTitle'],
					    'pageKeywords' => $web_data['pageKeywords'],
                                            'pageContent' => $web_data['pageContent'],
                                            'reportStatus' => $report,
                                             'reportCheckStatus' => $reportChkStatus
					);
					$pgModel = new Application_Model_DbTable_Pages();
					$pgModel->save($data);
					$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
					$this->flashMessenger->addMessage('success');	
					$this->flashMessenger->addMessage($this->translate->_("page Added"));	
					$this->_redirect("admin/pages/index/webid/".$web_data['webId']);
				}
			}
			
	}

	public function statusAction(){
	  
            $id = $this -> _request -> getParam('id');
            $obj = new Application_Model_DbTable_Pages();
            $pageData =$obj->getById($id); 
           
            if(!empty($pageData[0][status]) && $pageData[0][status]==1)
            $status=0;
            else
            $status=1;
            $data = array(
            'id' => $id,
            'status'=> $status,

            );
            $obj->save($data);
           
            $this->_redirect("admin/pages/index/webid/".$pageData[0][webId]);
	}
        
        public function cronjobstatusAction(){
	  
            $id = $this -> _request -> getParam('id');
            $obj = new Application_Model_DbTable_Websites();
            $pageData =$obj->getById($id); 
           
            $pgObj = new Application_Model_DbTable_Pages();
           
            if(!empty($pageData[0][cronJobStatus]) && $pageData[0][cronJobStatus]==1)
            $status=0;
            else
            $status=1;
            $data = array(
            'id' => $id,
            'cronJobStatus'=> $status,
            );
            $obj->save($data);
            
             $data_pg = array(
            'webId' => $id,
            'cronJobStatus'=> $status,

            ); 
             
            $pgObj->edit($data_pg);
            
            $this->_redirect("admin/pages/cronjobpages");
	}
   
	
}



