<?php

class Admin_HeadmenuController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		//$this->translate = Zend_Registry::get('Zend_Translate');
    }

    public function indexAction()
    {
        // action body
    }
	//////////////////////////////////////////////////////
	function menuAction() {
		$key = $this->_request->getParam('mainmenu');
		$index = $this->_request->getParam('submenu');
		$action = $this->_request->getParam('target');
		$sessionNamespace = new Zend_Session_Namespace('Admin_Menu');
		$sessionNamespace->keyValue = $key;
		if(is_numeric($index)) {
		
			$sessionNamespace->keyIndex = $index;
			$result = $this->_getMainMenu($index);	
			
            if($result){
				$controller = $result -> modules_name;
				
			}else{
			    $controller = $key;
			
			}
			$sessionNamespace->subkeyValue = $controller;
		} else {
		    $parent_id = $this->_getParentId($index);
			$sessionNamespace->keyIndex = $parent_id;
			$sessionNamespace->subkeyValue = $index;
			$controller = $index;
		}
		
	    $this->_redirect('/admin/'.$controller.'/'.$action);

	}
	
	//////////////////////////////////////////////////////////
	private function &_getMainMenu($id){
		$moduleModel = new Admin_Model_DbTable_Module();
		return $moduleModel->fetchRow("parent_id = '".$id."' AND modules_status=1", "sort_id ASC");
	}	
	private function _getParentId($name){
		$moduleModel = new Admin_Model_DbTable_Module();		
		$result = $moduleModel->fetchRow("modules_name = '".$name."'");
		return $result->parent_id;
	}	 
	private function _getAction($name,$parent_id){
		$moduleModel = new Admin_Model_DbTable_Module();		
		$result = $moduleModel->fetchRow("modules_name = '".$name."' and parent_id=".$parent_id);
		return $result->parent_id;
	}	 
}

