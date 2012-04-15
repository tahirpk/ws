<?php

class PageController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		$this->_helper->layout->setLayout('frontend');
		$this -> view -> placeholder('layout_body_id') -> set('innerpages');
    }

    public function indexAction()
    {
        // action body
    }
	public function showAction(){
	   
	   
	     
	   $id = $this -> _request -> getParam('id');
	   //set meta info
	   $metaHelper = $this -> _helper -> getHelper('FrontAppHelper');
	   $meta = $metaHelper -> getMetaInfo($id);
	   $metaHelper -> setMetaInfo($this,$meta);
	   // end set meta info
	   
	   
	   $model = new Application_Model_DbTable_Cms();
	   if(is_numeric($id)){
	     $data = $model -> fetchRow('id="'.$id.'" AND status=1');
	   }else{
	     $data = $model -> fetchRow('sef_url="'.$id.'" AND status=1');
	   }	 
	   
	   if(!(bool)$data -> id){
	      $this -> _redirect('/');
	   } 
	   $this -> view -> data = $data;
	}
	
	//tnc data
	public function tncAction(){
	   
	   $id = $this -> _request -> getParam('id');
	   //set meta info
	   $metaHelper = $this -> _helper -> getHelper('FrontAppHelper');
	   $meta = $metaHelper -> getMetaInfo($id);
	   $metaHelper -> setMetaInfo($this,$meta);
	   // end set meta info
	   
	   
	   $model = new Application_Model_DbTable_Marchant();
       $data = $model -> fetchRow('id='.$id);
	   
	   if(!(bool)$data -> id){
	      $this -> _redirect('/');
	   } 
	   $this -> view -> data = $data;
	}
	
	

}

