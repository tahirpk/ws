<?php

class Admin_UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		$this->translate = Zend_Registry::get('Zend_Translate');
			$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
			$auth = Zend_Auth::getInstance();
			if (!$auth->hasIdentity()) {
				$this->_redirect('/admin/login/auth');
			}
    }

    public function indexAction()
    {
        // action body
    }
	
	function editAction(){
			global $db;
			
			$sessionNamespace = new Zend_Session_Namespace('Admin_Menu');
			unset($sessionNamespace->keyIndex);
			unset($sessionNamespace->keyValue);
			unset($sessionNamespace->subkeyValue);
			
			
			$this->view->placeholder('heading')->set($this->translate->_('Change Password'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Change Password");
			$breadCrumb = $this->translate->_('Change Password');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_UserEditForm();
			$id = $this->_request->getParam('id');
				
			$userModel = new Application_Model_DbTable_User();
			$rst = $userModel->fetchRow("id = ".$id);	
			$this->view->rst=$rst;		
			if($this->_request->isPost()){	
			  $v = $form -> isValid($this -> getRequest() ->getPost());
			 
			 // print_r($form->getErrors());exit;
			    if($v){
				
					$flag_set=1;
					$usermodel=new Application_Model_DbTable_User();
					$flag_set=$usermodel->checkUserInitial($this->_request->getParam('user_intial'),$id);
					
					$flag = $userModel->checkUserEmail($this->_request->getParam('user_email'),$id);
						$UserModelUpdate = new Application_Model_DbTable_User();
						$data = $form->getValues();
						$update = array
								(
									'fname'=>$this->_request->getParam('first_name'),
									'lname'=>$this->_request->getParam('last_name'),
									'user_initial'=>$this->_request->getParam('user_intial'),
									'email'=>$this->_request->getParam('user_email'),
								);
						
						
								
						$UserModelUpdate->update($update,"id = ".$id);
						
						if($this->_request->getParam('cust_password')!='')
						{
							$update = array
								(
									'password'=>$this->_request->getParam('cust_password'),
								);
								$UserModelUpdate->update($update,"id = ".$id);
						}
						$this->flashMessenger->addMessage('success');	
						$this->flashMessenger->addMessage($this->translate->_('User is edited successfully'));	
						$this->_redirect("admin/user/edit/id/".$id);
		  }// is valid 
		 }
		 $this->view->form=$form ;	
	}
	
	
	


}

