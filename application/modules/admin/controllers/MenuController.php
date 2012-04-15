<?php

class Admin_MenuController extends Zend_Controller_Action
{

    public function init()
    {
        
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirect('/admin/login/auth');
		}
			
		
		$this->translate = Zend_Registry::get('Zend_Translate');
		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
		$this->view->vid = $vid = $this -> _request -> getParam('vid');
		//$this->view->eid = $eid = $this -> _request -> getParam('vid');
		//print($vid);exit;
		$id = $this -> _request -> getParam('id');
		
		$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/venue>'.$this->translate->_('Venue Management').'</a> &raquo; '.'<a href='.$this->view->baseUrl().'/admin/event/index/id/'.$vid.'>'.$this->translate->_('Event Management').'</a> &raquo; '.$this->translate->_('Menu Management');
		
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
        $adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Menu Management'));
		global $db;
		$perPage = 25; // set values per page
		
		$event = new Application_Model_DbTable_Event();
		$id=$this -> _request -> getParam('id');
		$myevent = $event->find($id);
		$this->view->assign("myevent",$myevent);
		
		
		$menuModel = new Application_Model_DbTable_Menu();
		$selectObj = $db->select();
        $selectObj->from(array('c' => 'menu'), array('c.*'))
		          -> joinLeft(array('p' => 'menu'),'c.p_id=p.id',array('p.menu_name as parent_menue'))
            ->where('c.event_id=?',$vid);
			//echo $selectObj;exit;
			
				   
		$menuCount = $menuModel -> getCount();
		$paginator = $adminPaginator->_getPaginatorData($this -> _getParam('page'), $perPage, $selectObj, $menuCount);
		$this->view->paginator = $paginator;
		$this->view->result = $paginator;
		
		
    }
	 public function addAction(){
		$this->view->eid = $eid = $this -> _request -> getParam('vid');
		//print_r($eid);exit;
		 $id = $this -> _request -> getParam('id');
		   // $this->view->eid = $eid = $this -> _request -> getParam('id');
			//print_r($id);exit;
			$this->view->placeholder('heading')->set($this->translate->_('Add Menu'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Menu");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/menu/index/id/'.$id.'/vid/'.$eid.'>'.$this->translate->_('Menu Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddMenu(array('event_id' => $eid));
			$this->view->form = $form;
			$menu_data = $form->getValues();

			if($this->_request->isPost())
			{
                          
				if($form->isValid($_POST))
				{
					$menu_data= $this->_request->getPost();
                          $data = array(
                                        'menu_name' => $menu_data['menu_name'],
                                      	'event_id' => $eid,
										'p_id' => $menu_data['p_id'],
                                        'menu_status' => $menu_data['menu_status']
                                    );
									 
									//print_r($data);die;
                                    $menuModel = new Application_Model_DbTable_Menu();
                                    $menuModel->save($data);
                                   
                                    $this->_redirect("admin/menu/index/id/".$id.'/vid/'.$eid);
				}
			} 
	}
	   public function deleteAction(){
	   global $db;
	   $eid = $this -> _request -> getParam('eid');
	   //print_r($eid);exit;
	   $id = $this -> _request -> getParam('id');
	   $obj = new Application_Model_DbTable_Menu();
	   $obj ->deleteRow($id);
	   $this -> _redirect('/admin/menu/index/id/'.$id.'/vid/'.$eid);
	}
	
	function editAction(){
	  $id = $this -> _request -> getParam('id');
	   $this->view->eid = $eid = $this -> _request -> getParam('eid');
	 // print_r($eid);exit;
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Menu'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Menu");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/menu/index/id/'.$eid.'>'.$this->translate->_('Menu Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddMenu(array('event_id' => $eid));
			//$this->view->form = $form;
			//$id = $this->_request->getParam('id');
			 
			$menuModel = new Application_Model_DbTable_Menu();
			$where = "id = '".$id."'";
			$result = $menuModel->fetchRow($where);
			$array2Populate = array
					  (
                                              'menu_name' => stripslashes($result['menu_name']),
											  'p_id' => $result['p_id'],
				             				  'menu_status' => $result['menu_status']    
                                          );
			$form->populate($array2Populate );
			//print_r($array2Populate);
			$this->view->form = $form;	
        	if($this->_request->isPost())
			{
				//print_r($_POST);exit();
				if($form->isValid($_POST))
				{
					$menu_data = $form->getValues();
					
					$data = array(
					    'menu_name' => $menu_data['menu_name'],
						'p_id' => $menu_data['p_id'],
				         'menu_status' => $menu_data['menu_status'],
					);
					
					$menuModel = new Application_Model_DbTable_Menu();
					$menuModel->save($data,$result['id']);
					
					$this->_redirect("admin/menu/index/id/".$eid); //redirection for particular venue id 
				}
			}
			
			
	}


}

