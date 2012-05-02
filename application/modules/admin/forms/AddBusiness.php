<?php
class Admin_Form_AddBusiness extends Zend_Form
{
	
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
		
    }
	// constructor 
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'id', 'value' => 0);
		if(isset($options['id'])){
		   $exclude = array('field' => 'id', 'value' => $options['id']);
		  
		}
		// validate if name alrady exist
		$validator_dupli = new Zend_Validate_Db_NoRecordExists(array('table' => 'business', 'field' => 'businessName', 'exclude' => $exclude));
		$validator_dupli -> setMessage('Business name already exists!');
				
		// form fields 
		$business = new Zend_Form_Element_Text('businessName');
		$business -> setRequired(true);
		$business ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator_dupli));
			  
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));
		
					  
		$this -> addElements(array($business,$status));			  
					  
	}
	


}
?>