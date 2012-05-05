<?php

class AccountController extends Zend_Controller_Action
{
    public function init()
    {
                $this ->_helper -> layout -> disableLayout();
		$this->_helper->layout->setLayout('front-layout');
		$this->translate = Zend_Registry::get('Zend_Translate');
		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		
    }

    public function loginAction()
    {
				
				
			$this->view->placeholder('heading')->set($this->translate->_('Customer Login'));
			$this->view->headTitle($this->translate->_('Customer Login'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Login'));
			$loginform = new Application_Form_LoginForm();
			$customer = new Application_Model_DbTable_Customer();
			
			$this->view->loginform = $loginform;
			if($this->_request->isPost())
			{	
				if($loginform->isValid($_POST))
				{
					$data = array
							(								
								'Email'=>$this->_request->getPost('email'),
								'Password'=>$this->_request->getPost('password')								
							);
					
					//$checked = $this->_request->getPost('checkbox');
					$password_encode=hash('sha256',$data['Password']);
					$auth = Zend_Auth::getInstance();
					$authAdapter = new Zend_Auth_Adapter_DbTable($customer->getAdapter(),'customers');
					$authAdapter->setIdentityColumn('Email')
								->setCredentialColumn('Password');
								
					//$data['password'] = sha1($data['password']);
					$authAdapter->setIdentity($data['Email'])
								->setCredential($password_encode);
					$result = $auth->authenticate($authAdapter);
					if($result->isValid())
					{						
						$storage = new Zend_Auth_Storage_Session();
						$userObj = $authAdapter->getResultRowObject();
						
						$userModel = new Application_Model_DbTable_Customer();
						
						$userRS = $userModel->fetchRow('id = "'.$userObj->id.'"');
						$storage->write($userObj);
						$data = $storage->read();

						if($data->Status != '1')
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
							$this->_redirect('account/websites');
						}					
					}
					else 
					{
                    	$this->view->message = $this->translate->_('Invalid user email or password!');
                	}			
				}
			}
		
		
	
	//	$this->_redirect("admin/");
	
    }
	public function websitesAction(){
	
			//$this->view->placeholder('heading')->set($this->translate->_('Customer'));
			//$this->view->headTitle("Customer Logout");
        		//print_r($_SESSION);
                        if(isset($_SESSION['Zend_Auth']['storage']->id) && !empty($_SESSION['Zend_Auth']['storage']->id) ){
                            $cusId=$_SESSION['Zend_Auth']['storage']->id;
                            $webModel = new Application_Model_DbTable_Websites();
                            $myWebResult = $webModel->getMyWebsites($cusId);
                            $this->view->result = $myWebResult;
                            $paginator = Zend_Paginator::factory($myWebResult);
                            $paginator->setPageRange(5); 
                            $paginator->setCurrentPageNumber($this->_getParam('page'));
                            $paginator->setItemCountPerPage(5);
                            $this->view->paginator = $paginator;
                        }else
                         $this->_redirect('/account/login');   
                        
	
	}
        
        	public function webreportsAction(){
	
			 $webid = $this->_request->getParam('id'); //die();
                        if(isset($_SESSION['Zend_Auth']['storage']->id) && !empty($_SESSION['Zend_Auth']['storage']->id) ){
                                $reportModel = new Application_Model_DbTable_Reports();
				$result = $reportModel->getLinks(null,null,$webid);
				$totalRecords = count($result);
				$this->view->result = $result;
				$paginator = Zend_Paginator::factory($result);
				$paginator->setPageRange(5);
                                $paginator->setCurrentPageNumber($this->_getParam('page'));
                                $paginator->setItemCountPerPage(5);
                                $this->view->paginator = $paginator;
                        }else
                         $this->_redirect('/account/login');   
                        
	
	}
        
	
	
	public	function logoutAction()
		{
			
			$this->_helper->layout->disableLayout();
			$storage = new Zend_Session_Namespace('allcustomer_session');	
			$sessionNamespace = new Zend_Session_Namespace('logout_session'); // for displaying logout banner on welcome text area
			$sessionNamespace->logoutFlag = 1;
			if(isset($storage->customer_id)){ 
				
				$sessionNamespace->email = ($storage->customer_email_address); //email
				$width = 380;
		   } 
			
			
			Zend_Session::namespaceUnset('allcustomer_session');
			Zend_Session::namespaceUnset('Message_Filter');	
			Zend_Session::namespaceUnset('Message_Perpage');	
			Zend_Session::namespaceUnset('Member_Search');	
			Zend_Session::namespaceUnset('User_Interest');	
			Zend_Session::namespaceUnset('App_Store_Function');	
			Zend_Session::namespaceUnset('App_duplicate_sesion');	
			
			session_destroy();
			//	Zend_Auth::getInstance()->clearIdentity();
			//setcookie('languageset[rememberlang]',0,time()+(60*60*24*30),'/');  
	   		$this->_redirect('/account/login');					 
				
		}
	public function forgotpasswordAction()
		{
			
                        $this->view->headTitle("Forgot Password");
			$form = new Application_Form_forgotForm();
			$this->view->form = $form;
			if($this->_request->isPost() && $this->_request->getParam("submit_forgot") == '1')
			{
				if($form->isValid($this->_request->getPost()))
				{	
					$data = $form->getValues();
					$cust_email = $data['email'];
					$customerModel = new Application_Model_DbTable_Customer();
					$customerrst = $customerModel->fetchRow("Email = '".$cust_email."' AND Status = '1'");
					if(empty($customerrst)) 
					{ //die('empty');
						$customerrst = $customerModel->fetchRow("Email = '".$cust_email."' AND Status = '0' ");
						if(!empty($customerrst) && count($customerrst) > 0) 
						{						
							$this->sendActivationLinkEmail($customerrst);
							$this->view->errorMsg = $this->translate->_('Activation link has been sent to your email address');
						}else{
							$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
							$this->flashMessenger->addMessage($this->translate->_('Please enter correct E-Mail Address'));
							$this->_redirect('/account/forgotpassword');		
						}
					}
					else
					{	
						if($customerrst['Status'] != '1')
						{
							$this->view->errorMsg = $this->translate->_('Your Account is deactivated by admin. Contact Admin for furhter detail');
						}
						else
						{
							$password = chr(mt_rand(97,122)).chr(mt_rand(97,122)).chr(mt_rand(97,122)).mt_rand(0,9).chr(mt_rand(97,122)).rand(0,9);
							
							list($usec,$sec) = explode(' ',microtime());
							// Seed the random number generator with above timings
							mt_srand((float) $sec + ((float) $usec * 1000000));
				
							// Generate hash using GLOBALS and PID
							$key = sha1(uniqid(mt_rand(),true));
							
							$password_encode=hash('sha256',$password);
					
		
							$customerModel = new Application_Model_DbTable_Customer();
							$customerModel->update(array('Password' =>$password_encode ,'salt_key' => $key), "id = '".$customerrst['id']."'");
							
							
							$emaildata = 1;
							
						
							
							if(count($emaildata)>0) 
							{
						
								
								$first_name = $customerrst['FirstName'];
								$last_name = $customerrst['LastName'];
								$cust_email = $customerrst['Email'];
								
																
								
								$customer_subject ='Your Forget Password Request.';
								$customer_body = 'Your Password has been succfully changed with some important information.<br>
								your email:'.$cust_email.'<br>your password:'.$password;
							//	$mail_body = "Dear user,\nYou can change your password from here. \n".$config -> site_url.$this -> view -> url(array('controller' => 'account', 'action' => 'recoverpass', 'id' => $key))."\nIf link is broken please copy the link and paste into browser address bar.\n";
								$config_mail = array(
								'port' => 587,
								'auth' => 'login',
								'username' => 'tahirpk@gmail.com',
								'password' => ''
								);

								$mail = new Zend_Mail('utf-8');
								$tr = new Zend_Mail_Transport_Smtp('127.0.0.1');
								Zend_Mail::setDefaultTransport($tr);
								$subject = $customer_subject;
								$bodyText = $customer_body;
								$mail->setFrom('tahir.pk@gmail.com','Tahir Khan');
								$mail->addTo($customer_data['Email']);
								$mail->setSubject($subject,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->setBodyHtml($bodyText,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->send($tr);
								
							}
							///////////////////////////////End Of Updation///////////////////////////////
							$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
							$this->flashMessenger->addMessage($this->translate->_('Your password has been e-mailed to you!'));
							$this->_redirect('/account/login');		
							//$this->view->errorMsg = $this->translate->_('Your password has been e-mailed to you!');
						}
					}	
				}
			}
		}
		
	private function sendActivationLinkEmail($customerrst)
		{


                        $password = chr(mt_rand(97,122)).chr(mt_rand(97,122)).chr(mt_rand(97,122)).mt_rand(0,9).chr(mt_rand(97,122)).rand(0,9);

                        list($usec,$sec) = explode(' ',microtime());
                        // Seed the random number generator with above timings
                        mt_srand((float) $sec + ((float) $usec * 1000000));

                        // Generate hash using GLOBALS and PID
                        $key = sha1(uniqid(mt_rand(),true));

                        $password_encode=hash('sha256',$password);
                        $customerModel = new Application_Model_DbTable_Customer();
                        $customerModel->update(array('Password' => $password_encode,'salt_key' => $key), "id = '".$customerrst['id']."'");


                        $cust_email = $customerrst['Email'];
                        $customer_subject ='Your Forget Password Request.';
                        $customer_body = 'Your Password has been succfully changed with some important information.<br>
                        your email:'.$cust_email.'<br>your password:'.$password;
                //	$mail_body = "Dear user,\nYou can change your password from here. \n".$config -> site_url.$this -> view -> url(array('controller' => 'account', 'action' => 'recoverpass', 'id' => $key))."\nIf link is broken please copy the link and paste into browser address bar.\n";
                        $config_mail = array(
                        'port' => 587,
                        'auth' => 'login',
                        'username' => 'tahirpk@gmail.com',
                        'password' => ''
                        );

                        $mail = new Zend_Mail('utf-8');
                        $tr = new Zend_Mail_Transport_Smtp('127.0.0.1');
                        Zend_Mail::setDefaultTransport($tr);
                        $subject = $customer_subject;
                        $bodyText = $customer_body;
                        $mail->setFrom('tahir.pk@gmail.com','Tahir Khan');
                        $mail->addTo($customer_data['Email']);
                        $mail->setSubject($subject,'UTF-8',Zend_Mime::ENCODING_8BIT);
                        $mail->setBodyHtml($bodyText,'UTF-8',Zend_Mime::ENCODING_8BIT);
                        $mail->send($tr);


		}
	
	public function regAction(){
                        
                        $customerModel = new Application_Model_DbTable_Customer();
			$form= new Application_Form_RegForm();
			$this->view->form = $form;
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{ 
                                   // print_r($form->getValues()); die('test');
                                     
					$customer_data = $form->getValues();
					
					if(!empty($customer_data['Password']))
                                             $password_org = $customer_data['Password'];
					else
                                            $password_org = chr(mt_rand(97,122)).chr(mt_rand(97,122)).chr(mt_rand(97,122)).mt_rand(0,9).chr(mt_rand(97,122)).rand(0,9);
                                            $data = array(
					        'FirstName' => $customer_data['FirstName'],
						'LastName' => $customer_data['LastName'],
						'Email' => $customer_data['Email'],
						'Password' => $password_org,
                                                'Password_org' => $password_org,
						'BusinessName' => $customer_data['BusinessName'],
						'PhoneNo' => $customer_data['PhoneNo'],
						'FaxNo' => $customer_data['FaxNo'],
						'Address1' => $customer_data['Address1'],
						'Address2' => $customer_data['Address2'],
						'PostalCode' => $customer_data['PostalCode'],
						'Country' => $customer_data['Country'],
						'State' => $customer_data['State'],
						'City' => $customer_data['City'],
						'status' => $customer_data['status']
                                                );
					            
				 			
							$customerModel->save($data);
							$customer_insert_id= $customerModel->getAdapter()->lastInsertId();
							if(!empty($customer_insert_id))
							{
							// print_r($data);die();    
								
								// password and salt key
								
							list($usec,$sec) = explode(' ',microtime());
							// Seed the random number generator with above timings
							mt_srand((float) $sec + ((float) $usec * 1000000));
				
							// Generate hash using GLOBALS and PID
							$key = sha1(uniqid(mt_rand(),true));
							
							$password_encode=hash('sha256',$password_org);
								
							$data= array('id' => $customer_insert_id,
												'salt_key' =>$key,
												'Password' => $password_encode);
												
											
								$customerModel->save($data);
							
								$customer_subject ='New customer confirmation';
								$customer_body = 'You are succfully registerd with wsportal with some important information.<br>
								your email:'.$customer_data['Email'].'<br>your password:'.$password_org;
								$config_mail = array(
								'port' => 587,
								'auth' => 'login',
								'username' => 'tahirpk@gmail.com',
								'password' => ''
								);

								$mail = new Zend_Mail('utf-8');
								$tr = new Zend_Mail_Transport_Smtp('localhost');
								Zend_Mail::setDefaultTransport($tr);
								$subject = $customer_subject;
								$bodyText = $customer_body;
								$mail->setFrom('tahir.pk@gmail.com','Tahir Khan');
								$mail->addTo($customer_data['Email']);
								$mail->setSubject($subject,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->setBodyHtml($bodyText,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->send($tr);
								$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
								$this->flashMessenger->addMessage('success');	
								$this->flashMessenger->addMessage($this->translate->_("Customer successfully inserted"));	

							}
							else
							{
                                                            $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
                                                            $this->flashMessenger->addMessage('success');	
                                                            $this->flashMessenger->addMessage($this->translate->_("Email is not sent to the customer or some thing is wrong"));	
							
							
							}
							
							$this->_redirect("account/login");
					
					 
				}
			}
	}
        
        public function profileAction()
	{
	  
	   
             if(isset($_SESSION['Zend_Auth']['storage']->id) && !empty($_SESSION['Zend_Auth']['storage']->id) ){
                                $id = $_SESSION['Zend_Auth']['storage']->id;
                        }else
                         $this->_redirect('/account/login');   
			// for the websites assing to the customer
			$cusWebModel = new Application_Model_DbTable_CustomerWebsites();
			$webModel = new Application_Model_DbTable_Websites();
			$allseries=$webModel->getAllWebsites();
			$customerWbs=$cusWebModel->getByCustomerId($id);
			$this->view->alreadyCustomerWbs = $customerWbs;
			$this->view->seriesarray = $allseries;
			$results =$webModel->getexistingLecPaginator(1,8,0);
			$total =$webModel->getExistCount(0);
			$this->view->forCutomerWeb=$results;
			$this->view->total=$total[0]['num'];
			$form = new Application_Form_ProfileForm(array('id' => $id));
			$this->view->form = $form;
			$customerModel = new Application_Model_DbTable_Customer();
			$where = "id = '".$id."'";
			$result = $customerModel->fetchRow($where);
			$array2Populate = array
								(
								   
						'FirstName' => stripslashes($result['FirstName']),
						'LastName' => stripslashes($result['LastName']),
						'Email' => $result['Email'],
						'Password' => stripslashes($result['Password']),
						'BusinessName' => $result['BusinessName'],
						'PhoneNo' => $result['PhoneNo'],
						'FaxNo' => $result['FaxNo'],
						'Address2' => stripslashes($result['Address2']),
						'Address1' => stripslashes($result['Address1']),
						'PostalCode' => $result['PostalCode'],
						'Country' => $result['Country'],
						'State' => $result['State'],
						'City' => stripslashes($result['City']),
						'Status' => $result['Status']
								);
			$form->populate($array2Populate );
			$this->view->form = $form;					
			
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$customer_data = $form->getValues();
                                        
                                        if(!empty($customer_data['Password']))
                                            $password_org =$customer_data['Password'];
					else
                                            $password_org =$result['Password_org'];
                                        $password_encode=hash('sha256',$password_org);
                                      
					$data = array(
					    'id' => $id,
						 'FirstName' => $customer_data['FirstName'],
						'LastName' => $customer_data['LastName'],
						'Email' => $customer_data['Email'],
						'Password' => $password_encode,
                                                'Password_org' => $password_org,
						'BusinessName' => $customer_data['BusinessName'],
						'PhoneNo' => $customer_data['PhoneNo'],
						'FaxNo' => $customer_data['FaxNo'],
						'Address1' => $customer_data['Address1'],
						'Address2' => $customer_data['Address2'],
						'PostalCode' => $customer_data['PostalCode'],
						'Country' => $customer_data['Country'],
						'State' => $customer_data['State'],
						'City' => $customer_data['City'],
						'status' => $customer_data['status']
					);
                                        
                    $customerModel = new Application_Model_DbTable_Customer();
					$customerModel->save($data);
					//first delted the old relation 
					$where_cusid = "customerFid = '".$id."'";
					
								$customer_subject ='customer update confirmation by admin';
								$customer_body = 'Your important information has updated.<br>
								your email:'.$customer_data['Email'].'<br>your password:'.$password_org;
								$config_mail = array(
								'port' => 587,
								'auth' => 'login',
								'username' => 'tahirpk@gmail.com',
								'password' => ''
								);

								$mail = new Zend_Mail('utf-8');
								$tr = new Zend_Mail_Transport_Smtp('localhost');
								Zend_Mail::setDefaultTransport($tr);
								$subject = $customer_subject;
								$bodyText = $customer_body;
								$mail->setFrom('tahir.pk@gmail.com','Tahir Khan');
								$mail->addTo($customer_data['Email']);
								$mail->setSubject($subject,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->setBodyHtml($bodyText,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->send($tr);
								
								$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
								$this->flashMessenger->addMessage('success');	
								$this->flashMessenger->addMessage($this->translate->_("Customer successfully inserted"));	
								$this->_redirect("accout/profile");
				}
			}
			
			
	}
}
