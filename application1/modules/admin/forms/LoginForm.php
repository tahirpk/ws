<?php

class admin_Form_LoginForm extends Zend_Form
{

		public function __construct($options = null)
		{
			parent::__construct($options);
			
			$user_intial = new Zend_Form_Element_Text('user_intial');
			$user_intial->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Username is required and can\'t be empty!'))	
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));

			
			$password = new Zend_Form_Element_Password('password');
			$password->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Password is required and can\'t be empty!'))
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));

			$this->addElements(array($user_intial,$password));
		}
}

