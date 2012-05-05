<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this ->_helper -> layout -> disableLayout();
		//$this->_helper->layout->setLayout('frontend');
		$this->translate = Zend_Registry::get('Zend_Translate');
		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
				$this->_redirect('account/login');
				

    }
	
}
