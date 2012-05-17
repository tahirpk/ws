<?php

class Admin_PagesController extends Zend_Controller_Action
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
    
    
		$breadCrumb =$this->translate->_('Pages Management');
	
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Pages Management'));
		$webid = $this->_request->getParam('webid');
                if(isset($webid) && !empty($webid))
                  $where='webId='.$webid;
                else
                $where='';
               // echo $where;
		$searchform = new Admin_Form_SearchPageForm();
			
                $pageModel = new Application_Model_DbTable_Pages();
                
                $selectObj = $pageModel -> select();
                 	 	 	 	 	 	
		
		$selectObj -> from(array('pg' => 'pages'),array('pg.pageTitle','pg.id','pg.pageCaption','pg.pageKeywords','pg.pageMetatags','pg.pageUrl','pg.pageContent','pg.pageCreated','pg.status'))
		 		   -> order('id desc')
                                   ->where($where);
				   
               // $result = $pageModel->getPageWebsites($where);
                $recCount= $pageModel->getCount($where);
              //  print_r($result); die();
                $perPage = 10; // set values per page
                $paginator = $adminPaginator->_getPaginatorData($this -> _getParam('page'), $perPage, $selectObj, $recCount);
		$this->view->paginator = $paginator;
                $this->view->result = $paginator;
                
	}
	
	// delete action
	public function deleteAction(){
	   $id = $this -> _request -> getParam('id');
	   $obj= new Application_Model_DbTable_Pages();
            $obj -> deletePages($id);
            $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $this->flashMessenger->addMessage('success');	
            $this->flashMessenger->addMessage($this->translate->_("Page Deleted"));	
	   $this -> _redirect('/admin/pages');
	}
	
	
	 /*****************************
	 *
	 * Pages Details
	 *
	 *****************************/
	public function detailAction()
        {
            $this->view->placeholder('heading')->set($this->translate->_('Page Detail'));
            $this->view->placeholder('buttonCaption')->set($this->translate->_('Page Detail'));
            $this->view->headTitle("{$this->translate->_('Page Detail')} > {$this->translate->_('Page Managment')} > {$this->translate->_('Page Detail')}");
            $this->view->placeholder('breadCrumb') -> set("{$this->translate->_('Pages')} > {$this->translate->_(' Page Detail')} ");
            $id = $this->_request->getParam('id');
            $pgModel = new Application_Model_DbTable_Pages();
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
	public function reportstatusAction()
        {
            $id = $this -> _request -> getParam('id'); 
            $obj = new Application_Model_DbTable_Pages();
            $pageData =$obj->getById($id); 
          
            if(!empty($pageData[0][reportCheckStatus]) && $pageData[0][reportCheckStatus]==0 || $pageData[0][reportCheckStatus]=='')
            {
             $status=1;
            }
            $data = array(
            'id' => $id,
            'reportCheckStatus'=> $status,

            );
            
            $obj->save($data);
           
            $this->_redirect("admin/pages/detail/webid/".$pageData[0][webId]."/id/".$id);
        }
	public function editAction()
	{
			
	
	   $id = $this -> _request -> getParam('id');
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Page'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Page");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/pages/>'.$this->translate->_('Business Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
                        $this->view->webid= $this -> _request -> getParam('webid');
			$form = new Admin_Form_AddPage(array('id' => $id));
			$this->view->form = $form;
			$pgModel = new Application_Model_DbTable_Pages();
			$where = "id = '".$id."'";
			$result = $pgModel->fetchRow($where);
			
			$array2Populate = array
                                            (
                                            'webId'=> $result['webId'],
                                            'pageUrl'=> $result['pageUrl'],
                                            'pageTitle'=> $result['pageTitle'],
                                            'pageKeywords'=> $result['pageKeywords'],
                                            'pageContent'=> $result['pageContent'],
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
					$pg_data = $form->getValues();	
                                         if(!empty($pg_data['pageTitle']))
                                         {
                                             $report =1;
                                             $reportChkStatus =0;
                                         }
                                         if(!empty($pg_data['pageTitle']) && !empty($pg_data['pageKeywords']))
                                         {
                                             $report =2;
                                              $reportChkStatus =0;
                                         }
                                         if(!empty($pg_data['pageTitle']) && !empty($pg_data['pageKeywords']) && !empty($pg_data['pageContent']))    
                                         {
                                             $report =3;
                                              $reportChkStatus =1;
                                         }
                                          else {
                                            $report =0;
                                             $reportChkStatus =0;
                                        }
					$data = array(
					    'id' => $id,
                                                'webId'=> $pg_data['webId'],
                                                'pageUrl'=> $pg_data['pageUrl'],
                                                'pageTitle'=> $pg_data['pageTitle'],
                                                'pageKeywords'=> $pg_data['pageKeywords'],
                                                'pageContent'=> $pg_data['pageContent'],
						'status' => $pg_data['status'],
                                                 'reportStatus' => $report ,
                                                 'reportCheckStatus' => $reportChkStatus
					);
                                     		
					$pgModel->save($data);
                                        
                                        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
					$this->flashMessenger->addMessage('success');	
					$this->flashMessenger->addMessage($this->translate->_("page updated"));	
					$this->_redirect("admin/pages/index/webid/".$pg_data['webId']);
					
					
				}
				
			}
			
			
	
	
	}
	
	
	public function geturlAction()
	{
			
	
            $id = $this -> _request -> getParam('id');
            $obj = new Application_Model_DbTable_Websites();
            $pageData =$obj->getUrlById($id); 
           
             if(!empty($pageData[0]['url']))
            {
                 
                 echo $pageData[0]['url'];die();
            
            }
            else 
                die('url');
        }
	
}



