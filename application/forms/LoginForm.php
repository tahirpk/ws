<?php
	class Application_Form_LoginForm extends Zend_Form
	{
		public function __construct($options = null)
		{
			parent::__construct($options);
			
			$email = new Zend_Form_Element_Text('email');
			$email->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('email is required and can\'t be empty'))	
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));

			
			$password = new Zend_Form_Element_Password('password');
			$password->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Password is required and can\'t be empty'))
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));

			$this->addElements(array($email,$password));
		}
	}
?>