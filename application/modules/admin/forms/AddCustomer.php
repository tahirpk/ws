<?php

class admin_Form_AddCustomer extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
	 
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'id', 'value' => 0);
		if(isset($options['id'])){
		   $exclude = array('field' => 'id', 'value' => $options['id']);
		}
		
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'customers', 'field' => 'Email', 'exclude' => $exclude));
		$validator -> setMessage('Email already exists!');
		
		
		
		$Email = new Zend_Form_Element_Text('Email');
		$new_validator = new Zend_Validate_NotEmpty();
		$new_validator->setMessage($this->getView()->translate('Valid Email is required'));
		
		$new_validator1 = new Zend_Validate_EmailAddress();
		$new_validator1->setMessage($this->getView()->translate('Valid email is required'));
		
		$Email->addFilter('StripTags')
		//->addErrorMessage('Valid Email is required')		
		->addFilter('StringTrim')
		->setAutoInsertNotEmptyValidator(false)
		->setRequired(true)
		->addValidator($new_validator, true)
		->addValidator($new_validator1, true)
		->setDecorators(array('ViewHelper', 'Errors'));
			  
		$FirstName = new Zend_Form_Element_Text('FirstName');
		$FirstName -> setRequired(true);
		$FirstName ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$LastName = new Zend_Form_Element_Text('LastName');
		$LastName -> setRequired(true);
		$LastName ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  	  
		$BusinessName = new Zend_Form_Element_Text('BusinessName');
		$BusinessName -> setRequired(true);
		$BusinessName ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$Password = new Zend_Form_Element_Password('Password');
		$Password -> setRequired(true);
		$Password->addValidator('StringLength', false, array(4,15));
		$Password->addErrorMessage('Please choose a password between 4-15 characters');
		$Password ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$PasswordConfirm = new Zend_Form_Element_Password('PasswordConfirm');
		$PasswordConfirm -> setRequired(true);
		$PasswordConfirm->addValidator('Identical', true, array('token' => 'Password'));
		$PasswordConfirm ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		
		$Country = new Zend_Form_Element_Select('Country');
		$table = new Application_Model_DbTable_Country();
		$Country  -> setRequired(false)
					-> setSeparator("")
					-> setDecorators(array('Errors' => 'ViewHelper'));
					
		$Country -> addMultiOption(0,'-- Country --');
		foreach($table -> fetchAll() as $c){
		  $Country -> addMultiOption($c -> iso, $c -> name);
		}			
		
		
		$PostalCode = new Zend_Form_Element_Text('PostalCode');
		$PostalCode -> setRequired(false);
		$PostalCode ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		$PhoneNo = new Zend_Form_Element_Text('PhoneNo');
		$PhoneNo -> setRequired(false);
		$PhoneNo ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$FaxNo = new Zend_Form_Element_Text('FaxNo');
		$FaxNo -> setRequired(false);
		$FaxNo ->setDecorators(array('Errors','ViewHelper'))
		      	->setValidators(array($validator));
			  
		$Address1 = new Zend_Form_Element_Text('Address1');
		$Address1 -> setRequired(false);
		$Address1 ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		
		$Address2 = new Zend_Form_Element_Text('Address2');
		$Address2 -> setRequired(false);
		$Address2 ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		
		$State = new Zend_Form_Element_Text('State');
		$State -> setRequired(false);
		$State ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
				
		$City = new Zend_Form_Element_Text('City');
		$City -> setRequired(false);
		$City ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
	
		
	
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		
		$this -> addElements(array($FirstName, $LastName, $Email, $Password,$PasswordConfirm, $BusinessName, $PhoneNo, $FaxNo,$Address1,
		$Address2, $PostalCode, $Country, $State, $City, $status));			  
					  
	}
	


}

