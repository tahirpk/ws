<?php

class Admin_CustomerController extends Zend_Controller_Action
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
	   $breadCrumb =$this->translate->_('Customer Management');
	
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Customer Management'));
		//$searchform = new forms_CustomerSearchForm();
		$searchform = new Admin_Form_CustomerSearchForm();
				
		$session = new Zend_Session_Namespace('Customer_Search');

			if(!$this->_request->isPost() && !isset($session->filter)) //second condition is if search is set an click next should not execute this code
			{		
				$cusModel = new Application_Model_DbTable_Customer();
				$result = $cusModel->getLinks(null,null);
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
					Zend_Session::namespaceUnset('Customer_Search');
					$this->_redirect("/admin/customer/");
				}
				if($this->_request->getParam("submitAction") == $this->translate->_("Search") || isset($session->filter))
				{
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
	
	// delete action
	public function deleteAction(){
	   global $db;
	   $id = $this -> _request -> getParam('id');
	   $venueModel = new Application_Model_DbTable_Customer();
	   $venueModel->deleteCustomer($id);
	   $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
					$this->flashMessenger->addMessage('success');	
					$this->flashMessenger->addMessage($this->translate->_("Customer Deleted"));	
	   $this -> _redirect('/admin/customer');
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
	    $catModel = new Application_Model_DbTable_Customer();
		$this->view->result = $catModel->fetchRow("id = ".$id);
	}
	
	 /*****************************
	 *
	 * Add customer
	 *
	 *****************************/
	 public function addAction(){
			$this->view->placeholder('heading')->set($this->translate->_('Add Customer'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Customer");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/customer>'.$this->translate->_('Customer Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			// for the websites assing to the customer
			$cusWebModel = new Application_Model_DbTable_CustomerWebsites();
			$webModel = new Application_Model_DbTable_Websites();
			$allseries=$webModel->getAllWebsites();
			$this->view->seriesarray = $allseries;
			$form = new Admin_Form_AddCustomer();
			$this->view->form = $form;
			$results =$webModel->getexistingLecPaginator(1,8,0);
			$total =$webModel->getExistCount(0);
			$this->view->forCutomerWeb=$results;
			$this->view->total=$total[0]['num'];
			
			// includes model end
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$customer_data = $form->getValues();
					
					
					
					$data = array(
					    'FirstName' => $customer_data['FirstName'],
						'LastName' => $customer_data['LastName'],
						'Email' => $customer_data['Email'],
						'Password' => $customer_data['Password'],
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
							$customer_insert_id= $customerModel->getAdapter()->lastInsertId();
							if(!empty($customer_insert_id))
							{
							
								if(count($_POST['websites'])>0){
										
											for ($j=0; $j<count($_POST['websites']);$j++) {
												if($cusWebModel->getCount("`webId` =".$_POST['websites'][$j]." AND `customerFid` =".$customer_insert_id)<1){
												$data = array(
															'webId' => $_POST['websites'][$j],
															'customerFid' => $customer_insert_id,
															
														);
														$cusWebModel->save($data);
												}
											}
									}
							
								/*$customer_subject ='New customer confirmation';
								$customer_body = 'Your customer is succfully created with some important information';
								$mail = new Zend_Mail('utf-8');
								$tr = new Zend_Mail_Transport_Smtp('stage1.uraan.net');
								Zend_Mail::setDefaultTransport($tr);
								$subject = $customer_subject;
								$bodyText = $customer_body;
								$mail->setFrom('tahir.pk@gmail.com','Tahir Khan');
								$mail->addTo($customer_data['Email']);
								$mail->setSubject($subject,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->setBodyHtml($bodyText,'UTF-8',Zend_Mime::ENCODING_8BIT);
								$mail->send($tr);*/
								
								$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
					$this->flashMessenger->addMessage('success');	
					$this->flashMessenger->addMessage($this->translate->_("Customer successfully inserted"));	

							}
							else
							{
							$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
					$this->flashMessenger->addMessage('success');	
					$this->flashMessenger->addMessage($this->translate->_("Email is not sent to your customer or some thing is wrong"));	
							
							//	$this->_redirect("admin/customer");
							}
							
							$this->_redirect("admin/customer");
					//$this->flashMessenger->addMessage($this->translate->_('Error :::: --- '));
					
				}
			}
	}
		public function allwebpagingAction(){
		 $page = $this -> _request -> getParam('id');
		 if(isset($_POST['catid'])){
		 $catid =$_POST['catid'];
		 if(!empty($catid) && $catid!=""){
			 	$catid=$catid;
			 }else{
				 $catid=0;
				 }
		 }else{$catid=0;}
		 $next=$page+1;
		 $previous=$page-1;
		 $content="No Record found";
		$perpage = 8;
		
			
		$obj = new Application_Model_DbTable_Websites();
		
		$total =$obj->getExistCount($catid);
		$totalpages=  ceil($total[0]['num'] / $perpage);
		$results =$obj->getexistingLecPaginator($page,$perpage,$catid);
		if(!empty($results)){
				$i = 0;
				
				$content='<div style="height:270px; vertical-align:top; overflow:auto"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="navigatepage" style="text-align:right;">';
						if($previous>0){$content=$content. '&lt;&lt; <a href="javascript:void(0)" onclick="javascript:callajax('.$previous.');">  Previous</a>';}else{ $content=$content. '&lt;&lt; <a  href="javascript:void(0)"  > Previous</a>';} $content=$content."&nbsp;&nbsp;&nbsp;".$page." of ".$totalpages." pages"; if($next>$totalpages){$content=$content.'&nbsp;&nbsp;&nbsp;&nbsp; <a  href="javascript:void(0)"   >Next </a>&gt;&gt;';}else{$content=$content.' &nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:callajax('.$next.');"> Next </a>&gt;&gt;';}$content=$content.'</td>
                      </tr>
                   </table><table width="100%" cellpadding="0" cellspacing="0">
                   
                     ';
				foreach($results as $rst){
					
					$content=$content.' <tr class="autoWidth"><td >
									<input type="checkbox" name="websites[]" value="'.$rst['id'].'"'; if(!empty($_POST["websites"]) &&$_POST["websites"]!="" ){ $exlecarr=explode(',',$_POST["websites"]); if(in_array($rst['id'],$exlecarr)){$content=$content."checked";}
} $content=$content.' /> 
									'.$rst['url'].' 
                            </td></tr>';
                           $i++;
							
					}
				
					$content=$content. '
                      </table></div><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="navigatepage" style="text-align:right;">';
						if($previous>0){$content=$content. '&lt;&lt; <a href="#" onclick="javascript:callajax('.$previous.');">  Previous</a>';}else{ $content=$content. '&lt;&lt; <a  href="#"  > Previous</a>';} $content=$content."&nbsp;&nbsp;&nbsp;".$page." of ".$totalpages." pages"; if($next>$totalpages){$content=$content.'&nbsp;&nbsp;&nbsp;&nbsp; <a  href="#"   >Next </a>&gt;&gt;';}else{$content=$content.' &nbsp;&nbsp;&nbsp;<a href="#" onclick="javascript:callajax('.$next.');"> Next </a>&gt;&gt;';}$content=$content.'</td>
                      </tr>
                   </table> ';
					 
			}
			echo $content; die;
		}

		public function statusAction(){
	    global $db;
	    $id = $this -> _request -> getParam('id');
	    $obj = new Application_Model_DbTable_Customer();
		$catIdenties=$obj->getById($id);
		if(!empty($catIdenties[0]['Status']) && $catIdenties[0]['Status']==1)
		$status=0;
		else
		$status=1;
		$data = array(
					    'id' => $id,
						'Status'=> $status,
						
					);
					$obj->save($data);
	   $this -> _redirect('/admin/customer');
	}
	
	public function editAction()
	{
	   $id = $this -> _request -> getParam('id');
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Customer'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Customer");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/customer>'.$this->translate->_('Customer Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_EditCustomer(array('id' => $id));
			$this->view->form = $form;
			$id = $this->_request->getParam('id');
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
					$data = array(
					    'id' => $id,
						 'FirstName' => $customer_data['FirstName'],
						'LastName' => $customer_data['LastName'],
						'Email' => $customer_data['Email'],
						'Password' => $customer_data['Password'],
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
					$this->_redirect("admin/customer");
				}
			}
			
			
	}
	
	
	
	 /*****************************
	 *
	 *  Customer Sorting
	 *
	 *****************************/	
	
	private function _performSort($sort_col,$order_by,$perPage)
			{
			$session = new Zend_Session_Namespace('Customer_Search');
			$custModel = new Application_Model_DbTable_Customer();
			$result = $custModel->getLinks(null,null);
			$totalRecords = count($result);
			$order = $sort_col.' '.$order_by;
			if (isset($session->filter) && isset($session->filterText ))
			{
				$filterText = $session->filterText;
				$filter = $session->filter;
				$where = "$filter LIKE '%$filterText%'";
				$result = $custModel->getLinks($where,$order);
			}
			else
			{
				$result = $custModel->getLinks(null,$order);
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
			$custModel = new Application_Model_DbTable_Customer();
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
			$session = new Zend_Session_Namespace('Customer_Search');
			$custModel = new Application_Model_DbTable_Customer();
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

