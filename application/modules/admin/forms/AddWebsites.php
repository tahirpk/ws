<?php
class Admin_Form_AddWebsites extends Zend_Form
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
		$validator_dupli = new Zend_Validate_Db_NoRecordExists(array('table' => 'websites', 'field' => 'url', 'exclude' => $exclude));
		$validator_dupli -> setMessage('URL already exists!');
				
		// form fields 
                
                
		$businessId = new Zend_Form_Element_Select('businessId');
		$table = new Application_Model_DbTable_Business();
		$businessId  -> setRequired(false)
					-> setSeparator("")
					-> setDecorators(array('Errors' => 'ViewHelper'));
					
		$businessId -> addMultiOption(0,'-- Business --');
		foreach($table -> fetchAll() as $b){
		  $businessId -> addMultiOption($b -> id, $b -> businessName);
		}			
		
		$webTitle = new Zend_Form_Element_Text('webTitle');
		$webTitle -> setRequired(true);
		$webTitle ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator_dupli));
			  
			  
			  
			$new_validator1 = new Zend_Validate_Regex('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i');
			$new_validator1->setMessage($this->getView()->translate('Valid URL including htttp(s):// is required'));
			$url = new Zend_Form_Element_Text('url');
			$url->addFilter('StripTags')
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->addValidator($validator_dupli, true)
						  //->setOptions(array('class' => 'loginInput textInput rounded last'))
						  ->addValidator($new_validator1, true)
						  ->setDecorators(array('Errors','ViewHelper'));	
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));
		
					  
		$this -> addElements(array($businessId,$webTitle,$url,$status));			  
					  
	}
	


}
?>