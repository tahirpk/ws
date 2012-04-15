<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init() 
    {
        /* Initialize action controller here */
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirect('/admin/login/auth');
		}
    }

    public function indexAction()
    {
        // action body
		$this->view->headTitle("Dashboard");
		
    }


}

