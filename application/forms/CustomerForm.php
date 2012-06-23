<?php

class Application_Form_CustomerForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        
        $captcha = new Zend_Form_Element_Captcha('captcha', array(
    		'label' => "Please verify you're a human",
    		'captcha' => array(
        	'captcha' => 'reCaptcha',
                'pubkey' => '6LfXMtMSAAAAAERj-E8AmP-ZkzJpaJddcBmJ91uq',
                'privkey' => '6LfXMtMSAAAAANf_1wh9sCeeRf5GgKQtOFJdxG09',
                 'theme' => 'clean',
        	'wordLen' => 4,
        	'timeout' => 300,
		),));
		$submit = new Zend_Form_Element_Submit('submit');
		$this->addElements(array($captcha, $submit));
                
              
       
                
               

    }
	 
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'id', 'value' => 0);
		if(isset($options['id'])){
		   $exclude = array('field' => 'id', 'value' => $options['id']);
		}
		
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'ncustomers', 'field' => 'email', 'exclude' => $exclude));
		$validator -> setMessage('Email already exists!');
		
		
		
		$Email = new Zend_Form_Element_Text('email');
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
                ->addValidator($validator, true)
		->setDecorators(array('ViewHelper', 'Errors'));
			  
		$customerName = new Zend_Form_Element_Text('customerName');
		$customerName -> setRequired(false);
		$customerName ->setDecorators(array('Errors','ViewHelper'));
		
                $validatorUrl_taken = new Zend_Validate_Db_NoRecordExists(array('table' => 'ncustomers', 'field' => 'website', 'exclude' => $exclude));
		$validatorUrl_taken -> setMessage('Url already exists!');
		
		$new_validatorUrl = new Zend_Validate_Regex('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i');
			$new_validatorUrl->setMessage($this->getView()->translate('Valid URL including htttp(s):// is required'));
			$url = new Zend_Form_Element_Text('website');
			$url->addFilter('StripTags')
                        ->addFilter('StringTrim')
                        ->addValidator($new_validatorUrl, true)
                        ->addValidator($validatorUrl_taken, true)
                        ->setRequired(true)
                        ->setDecorators(array('Errors','ViewHelper'));	
		
					    
		$validator_digit = new Zend_Validate_Digits();
		$PhoneNo = new Zend_Form_Element_Text('phoneNo');
                $PhoneNo->addFilter('StripTags')
                        ->addFilter('StringTrim')
                        ->addValidator($validator_digit, true)
                        -> setRequired(false)
                        ->setDecorators(array('Errors','ViewHelper'));
			  
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(false)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		$status =0;
                
              
		$this -> addElements(array($customerName, $Email, $PhoneNo,$url, $status));
                
                
                
					  
	}
	


}

