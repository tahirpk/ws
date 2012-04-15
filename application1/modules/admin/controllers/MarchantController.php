<?php

class Admin_MarchantController extends Zend_Controller_Action
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

    public function indexAction()
    {
        // action body
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this -> translate -> _('Merchant Management'));
		global $db;
		$perPage = 10;
		$catModel = new Application_Model_DbTable_Marchant();
		$selectObj = $db -> select();
		$selectObj -> from('marchants', array())
					->joinLeft('products', 'products.merchant_id = marchants.id', NULL) 
					->columns(array('marchants.id',
									'marchants.fullid',
									'marchants.name',
									'marchants.token',
									'marchants.shipping_cost',
									'marchants.status',
									'COUNT(products.id) AS products'
									))
				   -> order('id desc')
				   -> group('marchants.id');
		$catCount = $catModel -> getCount();
		$paginator = $adminPaginator->_getPaginatorData($this -> _getParam('page'), $perPage, $selectObj, $catCount);
		$this->view->paginator = $paginator;
		$this->view->result = $paginator;
		
    }
	
	// detailAction
	public function detailAction(){
	    $this->view->placeholder('heading')->set($this->translate->_('More'));
		$this->view->placeholder('buttonCaption')->set($this->translate->_('More'));
		$this->view->headTitle("{$this->translate->_('Shooz')} > {$this->translate->_('Merchant Managment')} > {$this->translate->_('More')}");
        $this->view->placeholder('breadCrumb') -> set("{$this->translate->_('Shooz')} > {$this->translate->_('Merchant Managment')} > {$this->translate->_('More')}");
	    $id = $this->_request->getParam('id');
	    $catModel = new Application_Model_DbTable_Marchant();
		$this->view->result = $catModel->fetchRow("id = ".$id);
	}
	
	// delete action
	public function deleteAction(){
	   global $db;
	   $id = $this -> _request -> getParam('id');
	   $productModel = new Application_Model_DbTable_Product();
	   $productModel->deleteProductMerchant($id);
	   $obj = new Application_Model_DbTable_Marchant();
	   $obj -> deleteMarchant($id);
	   $this -> _redirect('/admin/marchant');
	}
	
	
	 /*****************************
	 *
	 * Add marchant
	 *
	 *****************************/
	 public function addAction(){
			$this->view->placeholder('heading')->set($this->translate->_('Add Merchant'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
			$this->view->headTitle("Add Merchant");	   
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/marchant>'.$this->translate->_('Merchant Management').'</a> &raquo; '.$this->translate->_('Add');
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddMarchant();
			$this->view->form = $form;

			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$marchant_data = $form->getValues();
					$fullfilePath= realpath(APPLICATION_PATH."/../public/frontend/images/marchants/".$marchant_data['image']);
					//die($fullfilePath);
					$data = array(
					    'fullid' => $marchant_data['fullid'],
						'shipping_cost' => $marchant_data['shipping_cost'],
						'shipping_cost_type' => $marchant_data['shipping_type'],
						'shipping_min' => $marchant_data['shipping_min'],
						'swiss_postal_fee' => $marchant_data['swiss_postal_fee'],
						'url' => $marchant_data['url'],
						'address' => $marchant_data['address'],
						'country' => $marchant_data['country'],
						'vat' => $marchant_data['vat'],
						/*'vat_included' => (int)$marchant_data['vat_included'],*/
						'logo' => $marchant_data['logo'],
						'name' => $marchant_data['name'],
						'token' => $marchant_data['token'],
						'status' => $marchant_data['status'],
						'tnc' => $marchant_data['tnc'],
						/*'image' => $marchant_data['image'],*/
						'currency' => $marchant_data['currency']
					);
					
					if($form->image->isUploaded())
					{
						$data['image']= $marchant_data['image'];
						$thumb = new Application_Model_Thumbnail($fullfilePath);
				   		$thumb->resize(81,39);
				   		$thumb->save($fullfilePath);
					}
                    $catModel = new Application_Model_DbTable_Marchant();
                    $catModel = new Application_Model_DbTable_Marchant();
					$catModel->save($data);
					//$this->flashMessenger->addMessage('success');
					//$this->flashMessenger->addMessage($this->translate->_('Error :::: --- '));
					//echo $fullfilePath;die;
					
					$this->_redirect("admin/marchant");
				}
			}
	}
	
	function editAction(){
	   $id = $this -> _request -> getParam('id');
	   $this->view->placeholder('heading')->set($this->translate->_('Edit Merchant'));
			$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
			$this->view->headTitle("Edit Merchant");
			
			$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/marchant>'.$this->translate->_('Merchant Management').'</a> &raquo; '.$this->translate->_('Edit');
			
			$this->view->placeholder('breadCrumb')->set($breadCrumb);
			
			$form = new Admin_Form_AddMarchant(array('id' => $id));
			$this->view->form = $form;
			$id = $this->_request->getParam('id');
			$catModel = new Application_Model_DbTable_Marchant();
			$where = "id = '".$id."'";
			$this -> view -> result = $result = $catModel->fetchRow($where);
			
			$array2Populate = array
								(
									'fullid' => $result['fullid'],
									'shipping_cost' => $result['shipping_cost'],
									'shipping_cost_type' => $result['shipping_cost_type'],
									'shipping_min' => $result['shipping_min'],
									'swiss_postal_fee' => $result['swiss_postal_fee'],
									'url' => $result['url'],
									'address' => $result['address'],
									'country' => $result['country'],
									'vat' => $result['vat'],
									/*'vat_included' => (int)$result['vat_included'],*/
									'logo' => $result['logo'],
									'name' => $result['name'],
									'token' => $result['token'],
									'status' => $result['status'],
									'tnc' => $result['tnc'],
									'image' => $result['image'],
									'currency' => $result['currency']
								);
								
								
			$form->populate($array2Populate );
			$this->view->form = $form;	
			$oldfilePath=realpath(APPLICATION_PATH."/../public/frontend/images/marchants/".$result['image']);				
			
			//die("old file removed");
			if($this->_request->isPost())
			{
				if($form->isValid($_POST))
				{
					$marchant_data = $form->getValues();
					$fullfilePath= realpath(APPLICATION_PATH."/../public/frontend/images/marchants/".$marchant_data['image']);
					$data = array(
					    'id' => $id,
						'fullid' => $marchant_data['fullid'],
						'shipping_cost' => $marchant_data['shipping_cost'],
						'shipping_cost_type' => $marchant_data['shipping_cost_type'],
						'shipping_min' => $marchant_data['shipping_min'],
						'swiss_postal_fee' => $marchant_data['swiss_postal_fee'],
						'url' => $marchant_data['url'],
						'address' => $marchant_data['address'],
						'country' => $marchant_data['country'],
						'vat' => $marchant_data['vat'],
						/*'vat_included' => (int)$marchant_data['vat_included'],*/
						'logo' => $marchant_data['logo'],
						'name' => $marchant_data['name'],
						'token' => $marchant_data['token'],
						'status' => $marchant_data['status'],
						'tnc' => $marchant_data['tnc'],				
						'currency' => $marchant_data['currency']
					);
				
				    
					if($form->image->isUploaded())
					{
					unlink($oldfilePath);
					$thumb = new Application_Model_Thumbnail($fullfilePath);
				   	$thumb->resize(81,39);
				   	$thumb->save($fullfilePath);
					$data['image']= $marchant_data['image'];
					}
					$catModel = new Application_Model_DbTable_Marchant();
					$catModel->save($data);
					$this->_redirect("admin/marchant");
				}
			}
			
			
	}


}

