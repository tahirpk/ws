<?php

class admin_Form_AddCategory extends Zend_Form
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
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'categories', 'field' => 'name', 'exclude' => $exclude));
		$validator -> setMessage('Name already exists!');
				
		// form name
		$name = new Zend_Form_Element_Text('name');
		$name -> setRequired(true);
		$name ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));
		
		$required = TRUE;
		if(isset($options['id'])){
			// this is edit form so make icon field optional
			$required = false;
		}
					  
		$icon = new Zend_Form_Element_File('icon');
		$icon->setLabel('Upload an icon:')
					   ->setDestination(realpath(APPLICATION_PATH."/../public/frontend/images/category_icon"));
		 $icon->addErrorMessage($this->getView()->translate('icon is required and can\'t be empty'))			 
			  ->setRequired($required);
					  $icon->addValidator('Count', false, 1);
					  $icon->addValidator('Size', false, 102400);
					  $icon->addValidator('Extension', false, 'jpg,png,gif,jpeg');
					  
		$this -> addElements(array($name,$status,$icon));			  
					  
	}
	


}

