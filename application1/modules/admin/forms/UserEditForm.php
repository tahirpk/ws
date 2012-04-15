<?php

class admin_Form_UserEditForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
		public function __construct($options = null)
		{
			parent::__construct($options);
			
			////User first name
			$first_name = new Zend_Form_Element_Text('first_name');
			$new_validator = new Zend_Validate_NotEmpty();	
			$new_validator->setMessage($this->getView()->translate('Valid First name is required'));
			$new_validator2 = new Zend_Validate_Alnum();
			$new_validator2->setMessage($this->getView()->translate('Valid First name is required'));
	
			$first_name->addFilter('StripTags')
						  ->addFilter('StringTrim')
						  ->setAutoInsertNotEmptyValidator(false) 
						  ->setRequired(true)
						  ->addValidator($new_validator, true)
						  ->addValidator($new_validator2)
						  ->setDecorators(array('Errors','ViewHelper'));
			
						  
			////User last name
			$last_name = new Zend_Form_Element_Text('last_name');
			$new_validator = new Zend_Validate_NotEmpty();
			$new_validator->setMessage($this->getView()->translate('Valid Last name is required'));
			$new_validator2 = new Zend_Validate_Alnum();
			$new_validator2->setMessage($this->getView()->translate('Valid Last name is required'));
	
			$last_name->addFilter('StripTags')
						  ->addFilter('StringTrim')
						  ->setAutoInsertNotEmptyValidator(false) 
						  ->setRequired(true)
						  ->addValidator($new_validator, true)
						  ->addValidator($new_validator2)
						  ->setDecorators(array('Errors','ViewHelper'));
						 
						  
			
			////User Initials
			$user_intial = new Zend_Form_Element_Text('user_intial');
			$new_validator = new Zend_Validate_NotEmpty();
			$new_validator->setMessage($this->getView()->translate('Valid User intial is required'));
		
			$user_intial->addFilter('StripTags')
						  ->addFilter('StringTrim')
						  ->setAutoInsertNotEmptyValidator(false) 
						  ->setRequired(true)
						  ->addValidator($new_validator, true)
						  ->setDecorators(array('Errors','ViewHelper'));
			
						  			  
			///User email		  
			$user_email = new Zend_Form_Element_Text('user_email');
			$new_validator = new Zend_Validate_NotEmpty();
			$new_validator->setMessage($this->getView()->translate('Valid Email is required'));
			
			$new_validator1 = new Zend_Validate_EmailAddress();
			$new_validator1->setMessage($this->getView()->translate('Valid email is required'));
			$new_validator3 = new Zend_Validate_EmailAddress();
			$new_validator3->setMessage($this->getView()->translate('Valid Email is required'));
			
			$user_email->addFilter('StripTags')
						  //->addErrorMessage('Valid Email is required')		
						  ->addFilter('StringTrim')
						  ->setAutoInsertNotEmptyValidator(false)
						  ->setRequired(true)
						  ->addValidator($new_validator, true)
						  ->addValidator($new_validator1, true)
						   ->addValidator($new_validator3, true)
						  ->setDecorators(array('Errors','ViewHelper'));		
			
			
		
			///User password
			$cust_password = new Zend_Form_Element_Password('cust_password');
			$cust_password->addFilter('StripTags')	
						  ->addFilter('StringTrim')
						  ->setRequired(false)
						  ->setDecorators(array('Errors','ViewHelper'));			  
						  
						  
			$cust_confirm_password = new Zend_Form_Element_Password('cust_confirm_password');
			$new_validator1 = new Zend_Validate_Identical(@$_POST["cust_password"]);
			$new_validator1->setMessage($this->getView()->translate('Both password fields should match'));
			$cust_confirm_password->addFilter('StripTags')
						  //->addErrorMessage('Password mismatch')		
						  ->addFilter('StringTrim')
						  ->setAutoInsertNotEmptyValidator(false); 
						  if($_POST)
						  {
							   if($_POST["cust_password"]!=""){
							  $cust_confirm_password->setRequired(true);
							  } 
							  else{
							  $cust_confirm_password->setRequired(false);
							  }
						  }
						  else
						  {
							   $cust_confirm_password->setRequired(false);
						  }
						  $cust_confirm_password->addValidator($new_validator1)
						  ->setDecorators(array('Errors', 'ViewHelper'));			  
						  
			///User type	
			//get users
		
			
						  
			
			
			///User Status
			/*$user_status = new Zend_Form_Element_Select('user_status');
			$user_status->setRequired(true)
						->addErrorMessage($this->getView()->translate('Valid Status is required'))
						->addMultiOption('Active', ' Active')
						->addMultiOption('Inactive', ' Inactive')
						->setSeparator("")
						->setDecorators(array('Errors','ViewHelper'));
			*/
			
			$this->addElements(array($first_name, $last_name,$cust_password,$cust_confirm_password, $user_intial, $user_email/*, $user_type, $user_status*/));
		}

}

