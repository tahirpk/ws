<?php

class Admin_ProductController extends Zend_Controller_Action
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
	 $this -> _redirect('/admin/product/list');
	}
	
	public function listAction()
    {
        // action body
		global $db;
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this->translate->_('Product Management'));
		$perPage = 25;
		$nullValue = $this->translate->_('N/A');
		$brandExp = new Zend_Db_Expr(sprintf('CASE brands.name WHEN brands.name THEN brands.name ELSE "%s" END AS brand', $nullValue));
		$categoryExp = new Zend_Db_Expr(sprintf('CASE categories.name WHEN categories.name THEN categories.name ELSE "%s" END AS category', $nullValue));
		$merchantExp = new Zend_Db_Expr(sprintf('CASE marchants.name WHEN marchants.name THEN marchants.name ELSE "%s" END AS merchant', $nullValue));
		$titleExp = new Zend_Db_Expr(sprintf('CASE products.title WHEN products.title THEN products.title ELSE "%s" END AS title', $nullValue));
		$skuExp = new Zend_Db_Expr(sprintf('CASE products.sku WHEN "" THEN "%s" ELSE products.sku END AS sku', $nullValue));
		$priceExp = new Zend_Db_Expr(sprintf('CASE products.price WHEN 0 THEN "%s" ELSE products.price END AS price', $nullValue));
		$statusExp = new Zend_Db_Expr(sprintf('CASE products.status WHEN products.status THEN products.status ELSE "%s" END AS status', $nullValue));
		$cmsModel = new Application_Model_DbTable_Product();
		$selectObj = $db -> select()
					->distinct();
		$selectObj -> from('products', array())
				   -> order('products.id desc')
				   ->joinLeft('brands', 'products.brand_id = brands.id', NULL)
				   ->joinLeft('marchants', 'products.merchant_id = marchants.id', NULL)
				   ->joinLeft('categories', 'products.category_id = categories.id', NULL)
				   ->joinLeft('products_categories', 'products.id = products_categories.products_id', NULL)
				   ->columns(array('products.id', 
				   				   $titleExp, 
								   $skuExp, 
								   $priceExp, 
								   $statusExp, 
								   $brandExp, 
								   $categoryExp, 
								   $merchantExp));
		//print $selectObj->__toString(); die;
		$pageCount = $cmsModel -> getCount();
		$paginator = $adminPaginator->_getPaginatorData($this -> _getParam('page'), $perPage, $selectObj, $pageCount);
		$categoryModel = new Application_Model_DbTable_Category();
		$categories = $categoryModel->getCategories();
		$prodCats = array();
		foreach($paginator as $key=>$prod){
			$prodCats[$prod['id']] = $this->getProdCategories($prod['id']);
		}		
		$this->view->paginator = $paginator;
		$this->view->result = $paginator;
		$this->view->prodCats = $prodCats;
    }

    public function addAction()
    {
        $this->view->placeholder('heading')->set($this->translate->_('Add Product'));
		$this->view->placeholder('buttonCaption')->set($this->translate->_('Add'));
		$this->view->headTitle("Add Page");	   
		$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/product>'.$this->translate->_('Product Management').'</a> &raquo; '.$this->translate->_('Add');
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		
		$form = new Admin_Form_AddProduct();
		$this->view->form = $form;

		if($this->_request->isPost())
		{
			if($form->isValid($_POST))
			{
				$cms_data = $form->getValues();
				$storage = new Zend_Auth_Storage_Session();
				$user = $storage->read();
				$data = array(
					'title' => $cms_data['name'],
					'status' => $cms_data['status'],
					'body' => $cms_data['body'],
					'created' => time(),
					'user_id' => $user->id,
					'sku' => $cms_data['sku'],
					'merchant_id' => $cms_data['merchant'],
					'category_id' => $cms_data['category'],
					'brand_id' => $cms_data['brand'],
					'short_description' => $cms_data['short_description'],
					'keywords' => $cms_data['keywords'],
					'price' => $cms_data['price'],
					'currency' => $cms_data['currency'],
					'link_url' => $cms_data['link_url'],
					'image_url' => $cms_data['image_url'],
					'gender' => $cms_data['gender']
				);
				$productModel = new Application_Model_DbTable_Product();
				$new_record_id = $productModel->save($data);
				$productMirrorModel = new Application_Model_DbTable_ProductsMirror();
				$data = array('id' => $new_record_id);
				$productMirrorModel->save($data);
				$this->_redirect("admin/product/list");
			}
		}
    }

    public function editAction()
    {
        $id = $this -> _request -> getParam('id');
		$this->view->placeholder('heading')->set($this->translate->_('Edit Product'));
		$this->view->placeholder('buttonCaption')->set($this->translate->_('Edit'));
		$this->view->headTitle($this->translate->_("Edit Product"));
		
		$breadCrumb = '<a href='.$this->view->baseUrl().'/admin/product>'.$this->translate->_('Product Management').'</a> &raquo; '.$this->translate->_('Edit');
		
		$this->view->placeholder('breadCrumb')->set($breadCrumb);
		
		$form = new Admin_Form_AddProduct();
		$this->view->form = $form;
		$id = $this->_request->getParam('id');
		$productModel = new Application_Model_DbTable_Product();
		$where = "id = '".$id."'";
		$result = $productModel->fetchRow($where);
		
		$array2Populate = array
							(
								'name'=> stripslashes($result['title']),
								'status' => $result['status'],
								'body'=> stripslashes($result['body']),
								'sku'=> stripslashes($result['sku']),
								'merchant'=> stripslashes($result['merchant_id']),
								'category'=> stripslashes($result['category_id']),
								'brand'=> stripslashes($result['brand_id']),
								'short_description'=> stripslashes($result['short_description']),
								'keywords'=> stripslashes($result['keywords']),
								'price'=> stripslashes($result['price']),
								'currency'=> stripslashes($result['currency']),
								'link_url'=> stripslashes($result['link_url']),
								'image_url'=> stripslashes($result['image_url']),
								'gender'=> stripslashes($result['gender'])
							);
		$form->populate($array2Populate );
		$this->view->form = $form;					
		
		if($this->_request->isPost())
		{
			if($form->isValid($_POST))
			{
				$cms_data = $form->getValues();
				$data = array(
					'id' => $id,
					'title' => $cms_data['name'],
					'status' => $cms_data['status'],
					'body' => $cms_data['body'],
					'sku' => $cms_data['sku'],
					'merchant_id' => $cms_data['merchant'],
					'category_id' => $cms_data['category'],
					'brand_id' => $cms_data['brand'],
					'short_description' => $cms_data['short_description'],
					'keywords' => $cms_data['keywords'],
					'price' => $cms_data['price'],
					'currency' => $cms_data['currency'],
					'link_url' => $cms_data['link_url'],
					'image_url' => $cms_data['image_url'],
					'gender' => $cms_data['gender']
				);
				$catModel = new Application_Model_DbTable_Product();
				$catModel->save($data);
				$mirrorData = $this->getMirrorData($result, $data);
				if(count($mirrorData)) {
					$mirrorData['id'] = $id;
					$miirorObj = new Application_Model_DbTable_ProductsMirror();
					$miirorObj->updateProduct($mirrorData);
				}
				$this->_redirect("admin/product/list");
			}
		}
    }

    public function deleteAction()
    {
       $id = $this -> _request -> getParam('id');
	   $obj = new Application_Model_DbTable_Product();
	   $obj -> deleteProduct($id);
	   $miirorObj = new Application_Model_DbTable_ProductsMirror();
	   $miirorObj -> deleteProduct($id);
	   $ratingObj= new Application_Model_DbTable_rating();
	   $ratingObj -> deleteProduct($id);
	   
	   $this -> _redirect('/admin/product/list');
    }

    public function detailAction()
    {
        // action body
    }
	
	public function activeAction()
    {
       global $db;
	   $data = array('status'=>1);
	   $id = $this -> _request -> getParam('id');
	   $where = 'id IN ('.$id.')';
	   $db -> update('products', $data, $where);
	   $db -> update('products_mirror', array('status'=>1), $where);
	   $this->_redirect("admin/product/list");
    }
	public function inactiveAction()
    {
       global $db;
	   $data = array('status'=>0);
	   $id = $this -> _request -> getParam('id');
	   $where = 'id IN ('.$id.')';
	   $db -> update('products', $data, $where);
	   $db -> update('products_mirror', array('status'=>1), $where);
	   $this->_redirect("admin/product/list");
    }
	
	private function getMirrorData($oldData, $newData) {
		$res = array();
		foreach($newData as $key=>$value){
			if($oldData[$key] != $newData[$key]){
				$res[$key] = 1;
			}
		}
		return $res;
	}

	public function categoryAction(){
		global $db;
		$id = $this -> _request -> getParam('id');
		$adminPaginator = $this -> _helper -> getHelper('AdminPaginator');
		$this -> view -> headTitle($this->translate->_('Product Management'));
		$perPage = 25;
		$nullValue = $this->translate->_('N/A');
		$categoryExp = new Zend_Db_Expr(sprintf('CASE categories.name WHEN categories.name THEN categories.name ELSE "%s" END AS category', $nullValue));
		$relationModel = new Application_Model_DbTable_ProductsCategories();
		$selectObj = $db -> select();
		$selectObj -> from('products_categories', array())
				   -> order('products_categories.id desc')
				   ->joinLeft('categories', 'products_categories.categories_id = categories.id', NULL)
				   ->columns(array('products_categories.id', 
								   $categoryExp))
					->where('products_id='.$id);
		//print $selectObj->__toString(); die;
		$pageCount = $relationModel -> getCount();
		$paginator = $adminPaginator->_getPaginatorData($this -> _getParam('page'), $perPage, $selectObj, $pageCount);
		$this->view->paginator = $paginator;
		$this->view->result = $paginator;
	}
	
	private function getProdCategories($prodID){
		global $db;
		$nullValue = $this->translate->_('N/A');
		$categoryExp = new Zend_Db_Expr(sprintf('CASE categories.name WHEN categories.name THEN categories.name ELSE "%s" END AS category', $nullValue));
		$relationModel = new Application_Model_DbTable_ProductsCategories();
		$selectObj = $db -> select();
		$selectObj -> from('products_categories', array())
				   -> order('products_categories.id desc')
				   ->joinLeft('categories', 'products_categories.categories_id = categories.id', NULL)
				   ->columns(array($categoryExp))
					->where('products_id='.$prodID);
		$stmt = $db->query($selectObj);
		$result = $stmt->fetchAll();
		$cats = '';
		foreach($result as $val){
			$cats .= $val['category'].',';
		}
		if(strlen($cats) == 0){
			$cats = $nullValue;
		}else{
			$cats = substr($cats,0,-1);
		}
		return $cats;
	}
	
	public function abcAction(){
		
	}	
	
	public function paymentAction(){
		
		$gateway = "http://support.saferpay.de/scripts/CreatePayInit.asp"; 
		$client = new Zend_Http_Client($gateway, array(
														'maxredirects' => 0,
														'timeout'      => 30));
		$client->setParameterPost(array(
									'ACCOUNTID'  => '99867-94913159',
									'AMOUNT'   => '1230',
									'CURRENCY' => 'EUR',
									'PAN' => '9451 1231 0000 0004',
									'EXP' => '12/12',
									'CVC' => '1234',
									'NAME' => 'Zaheer',
									'ORDERID' => '0815-4711',
									'DESCRIPTION' => "Test Purchase - saferpay ZEND sample"
								));
		$response = $client->request('POST');
	//	print $response->body;
		//print_r($response);
		try{
		$temp = explode('?', $response->getBody());
		$a = $temp[1];
	$array=array();
	parse_str($a,$array);
	$DATA = $array['DATA'];
	$SIGNATURE = $array['SIGNATURE']; 
	urldecode($DATA); 
	urldecode($SIGNATURE); 
	/* the hosting gateway URL to verify pay confirm */ 
	$gateway = "http://support.saferpay.de/scripts/VerifyPayConfirm.asp"; 
	$accountid = "99867-94913159"; /* saferpay account id */ 
	/* put it all together */ 
	$url = "$gateway?DATA=".urlencode($DATA)."&SIGNATURE=".urlencode($SIGNATURE); /* verify pay confirm message at hosting server */ 
	$result = join("", file($url)); /* check if result is OK... */ 
	if (substr($result, 0, 3) == "OK:") { 
		print $result;
		print("Your Order has been successfully processed."); 
		parse_str(substr($result, 3)); /* $ID = saferpay transaction identifier, store in DBMS */ 
		/* $TOKEN = token of transaction, store in DBMS */ /***** Optional: Finalize payment by capture of transaction *****/ /* the hosting gateway URL to complete payment */ 
		$gateway = "http://support.saferpay.de/scripts/PayComplete.asp"; 
		/* put it all together */ $url = "$gateway?ACCOUNTID=$accountid&ORDERID=786&AMOUNT=1295"; /* complete payment by hosting server */ 
		$result = join("", file($url)); 
		if (substr($result, 0, 2) == "OK") 
			print("Capture has been done successfully"); 
		else print("Error: retry capture later..."); 
	} else /* ...or if an error happened */ { print $result; }
	}catch(ex $e){}
		
	}
	
	
}









