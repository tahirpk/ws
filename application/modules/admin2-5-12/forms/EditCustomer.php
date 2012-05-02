<?php

class admin_Form_EditCustomer extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
	
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'Email', 'value' => 0);
		if(isset($options['Email'])){
		   $exclude = array('field' => 'Email', 'value' => $options['Email']);
		}
		
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'customers', 'field' => 'Email', 'exclude' => $exclude));
		$validator -> setMessage('Email already exists!');
		
		
		
				
		
		$Email = new Zend_Form_Element_Text('Email');
		$Email -> setRequired(true);
		$Email ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
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
		$Password ->setDecorators(array('Errors','ViewHelper'))
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
		
		$this -> addElements(array($FirstName, $LastName, $Email, $Password,$BusinessName, $PhoneNo, $FaxNo,$Address1,
		$Address2, $PostalCode, $Country, $State, $City, $status));			  
					  
	}
	


}

