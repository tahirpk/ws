<?php
	class Application_Form_forgotForm extends Zend_Form
	{
		public function init() {
			
			$email = new Zend_Form_Element_Text('email');
			$new_validator = new Zend_Validate_Db_RecordExists('customers', 'Email');
			$new_validator->setMessage($this->getView()->translate('Email address does not exist'), Zend_Validate_Db_RecordExists::ERROR_NO_RECORD_FOUND);
			$new_validator1 = new Zend_Validate_EmailAddress();
			$new_validator1->setMessage($this->getView()->translate('Valid email address is required'), Zend_Validate_EmailAddress::INVALID);
			$new_validator2 = new Zend_Validate_NotEmpty();
			$new_validator2->setMessage($this->getView()->translate('Valid Email is required'));

			$email->addFilter('StripTags');
			$email->addFilter('StringTrim');
			$email->addValidator($new_validator2, true);
			$email->addValidator($new_validator1, true);
			$email->addValidator($new_validator);
			$email->setRequired(true);
			$email->setDecorators(array('ViewHelper','Errors'));
						  
			$this->addElements(array($email));
			
		}
	}
?>