<?php

class Admin_LoginController extends Zend_Controller_Action
{
		/***********************************
		*
		*  init function 
		*
		************************************/
		function init()
		{
			$this->translate = Zend_Registry::get('Zend_Translate');
		}
		
		/***********************************
		*
		*  login function 
		*
		************************************/

		function authAction()
		{
			$this->_helper->layout->disableLayout();				/* disable layout for this Action*/
			
			$this->view->placeholder('heading')->set($this->translate->_('Admin Login'));
			$this->view->headTitle($this->translate->_('Admin Login'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Login'));
			$form = new Admin_Form_LoginForm();
			$users = new Application_Model_DbTable_User();
			$this->view->form = $form;
			if($this->_request->isPost())
			{	
				if($form->isValid($_POST))
				{
					$data = array
							(								
								'user_intial'=>$this->_request->getPost('user_intial'),
								'password'=>$this->_request->getPost('password')								
							);
					
					//$checked = $this->_request->getPost('checkbox');
					$auth = Zend_Auth::getInstance();
					$authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'users');
					$authAdapter->setIdentityColumn('user_initial')
								->setCredentialColumn('password');
								
					//$data['password'] = sha1($data['password']);
					$authAdapter->setIdentity($data['user_intial'])
								->setCredential($data['password']);
					$result = $auth->authenticate($authAdapter);
					if($result->isValid())
					{						
						$storage = new Zend_Auth_Storage_Session();
						$userObj = $authAdapter->getResultRowObject();
						
						$userModel = new Application_Model_DbTable_User();
						
						$userRS = $userModel->fetchRow('id = "'.$userObj->id.'"');
						$storage->write($userObj);
						$data = $storage->read();

						if($data->status != 'Active')
						{
							$this->view->message = $this->translate->_('User is not active');
							 Zend_Auth::getInstance()->clearIdentity();
							 return;
						}
						
						else
						{
							$importInfo = new Zend_Session_Namespace('importInfo');
							$importInfo->file = time();
							$_SESSION['IsAuthorized'] = true;
							$this->_redirect('/admin');
						}					
					}
					else 
					{
                    	$this->view->message = $this->translate->_('Invalid user name or password!');
                	}			
				}
			}
		}


		/***********************************
		*
		*  logout function 
		*
		************************************/

		function logoutAction()
		{
			$this->_helper->layout->disableLayout();	
			Zend_Auth::getInstance()->clearIdentity();
	        Zend_Session::namespaceUnset('Admin_Menu');
			setcookie('languageset[rememberlang]',0,time()+(60*60*24*30),'/');
	   		$this->_redirect('/admin/login/auth');					 
		}
		
		/***********************************
		*
		*  login with cookie  function 
		*
		************************************/

		private function cookie_login($data)
		{

			$users = new Application_Model_DbTable_User();
			$auth = Zend_Auth::getInstance();
			$authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'users');
			
			$select = $users->select()
             		     ->from('users', array('user_initial', 'password'))
             			 ->where('md5(user_initial) =?', $data['elang']);
			
			$usersArr = $users->fetchRow($select);
			
			if(count($usersArr)>0)
			{
					if(md5($usersArr['password']) == $data['plang'])					
					{
						$authAdapter->setIdentityColumn('user_initial')
									->setCredentialColumn('password');
				
						$authAdapter->setIdentity($usersArr['user_initial'])
									->setCredential($usersArr['password']);
						$result = $auth->authenticate($authAdapter);
						if($result->isValid())
						{
							$storage = new Zend_Auth_Storage_Session();
							$storage->write($authAdapter->getResultRowObject());
							$data = $storage->read();
							if($data->status != 'Active')
							{
							//	$this->view->message = $this->translate->_('User is not active');
								 Zend_Auth::getInstance()->clearIdentity();
								 return;
							}
							
							else
							{								
								//$this->_redirect('/setting/affiliatepartner/list');
								$this->_redirect('/admin');							
							}
						}					
					}						
			 }
						
		}

		/***********************************
		*
		*  forget password function 
		*
		************************************/

		function forgetpasswordAction()
		{
			$this->_helper->layout->disableLayout();				/* disable layout for this Action*/
			
			$this->view->placeholder('heading')->set($this->translate->_('Forgetpassword'));
		
			$this->view->headTitle($this->translate->_('Forgetpassword'));			
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Submit'));
			
			$form = new Admin_Form_LoginForm();
			$form->removeElement('password');
			//$form->removeElement('checkbox');
			
			
			$this->view->form = $form;
			/* getting mail stuff from registry*/
			 Zend_Registry::get('config');
			global $db;
			$profiler = $db->getProfiler();
			
		//	$mail_sub = new Zend_Config_Ini(APPLICATION_PATH.'config/config.ini', 'mail');
 			$base_url ='http://localhost/wsportal/'; //$mail_sub->site_url;
			$mailFrom ='tahirpk@gmail.com';// $mail_sub->mail_from;
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					//$currentDateTime = Zend_Date::now()->toString('YYYY-MM-dd HH:mm:ss');
					$data = array
							(								
								'user_intial'=>$this->_request->getPost('user_intial')													
							);
					$user = new Application_Model_DbTable_User();
					$result = $user->checkUser($data);
					if(!empty($result)) { 
						list($usec,$sec) = explode(' ',microtime());
	
						// Seed the random number generator with above timings
						mt_srand((float) $sec + ((float) $usec * 1000000));
				
						// Generate hash using GLOBALS and PID
						$key = sha1(uniqid(mt_rand(),true));
					
						$this->varkey($result['email'],$key);
						# body for mail : it comes from registry
						$mail_body = str_ireplace("[change_password_link]","<a href='".$base_url."/admin/login/recoverpass/".$key."'>Recover Password</a>",$mail_sub->password_body);
						
						$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com');					
						$mail = new Zend_Mail('UTF-8');
						
						$mail->setBodyHtml($mail_body,'UTF-8',Zend_Mime::ENCODING_8BIT);
						
						$mail->setFrom($mailFrom, 'ws portal');
						$mail->addTo($result['email'],'password');					
						$mail->setSubject($this->translate->_('ws portal password recovery'));
						$mail->send($transport);
	
						$this->view->message = $this->translate->_('Mail send to your email address for password recovery');
					} else {
						$this->view->message = $this->translate->_('Email is not correct');
					}
				}
				
			}
		}
		
		/***********************************
		*
		*  Recover password function 
		*
		************************************/

		function recoverpassAction()
		{
			$this->_helper->layout->disableLayout();				/* disable layout for this Action*/
			
			$this->view->placeholder('heading')->set($this->translate->_('Recover Password'));
			$this->view->headTitle($this->translate->_('Recover Password'));			
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Submit'));
			
			$lengthValid = new Zend_Validate_Between(5,50);
			$varkey = $this->_request->getParam('id');
			if($varkey == "")
			{
				$this->_redirect('/admin/login/auth');
			}			
			$this->view->varkey = $varkey;
			
			if($this->_request->isPost())
			{
				$password = $this->_request->getParam('password');
				$conpassword = $this->_request->getParam('conpassword');
				if($password != $conpassword)
				{
					$this->view->message = $this->translate->_('Password and Confirm Password do not Match');
					return;
				}
				if($password =="" || $conpassword == "")
				{
					$this->view->message = $this->translate->_('Value is Required and cant be Empty');
					return;
				}
				if(strlen($password)<6)
				{
					$this->view->message = $this->translate->_('Password Length must be Greater than 5 characters');
					return;
				}
				$this->updatepassword($password,$varkey);
				$this->view->message = 'Password has been changed. <a href="'.$this->view->baseUrl().'/admin/login/auth">Login</a>.';
			}
		}
		private function varkey($email,$key)
		{
			$users = new Application_Model_DbTable_User();
			$data = array('varification_key'=>$key);			
			$users->update($data,"email = '$email'");
			
		}
		private function updatepassword($password,$varkey)
		{
			$users = new Application_Model_DbTable_User();
			$data = array('password'=>$password);				
			$users->update($data,"varification_key = '$varkey'");
			
		}

}

