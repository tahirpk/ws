<?php

class admin_Form_AddCmsForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
		
    }

	public function __construct($options = array()){
	    parent::__construct($options);
		
		$name = new Zend_Form_Element_Text('name');
		$name -> setRequired(true);
		$name ->setDecorators(array('Errors','ViewHelper'))
		      ->addErrorMessage($this->getView()->translate('Page title is required'));
		
		
		//meta keywords
		$meta_keywords = new Zend_Form_Element_Textarea('meta_keywords');
		$meta_keywords ->setDecorators(array('Errors','ViewHelper'));
		
		$meta_desc = new Zend_Form_Element_Textarea('meta_desc');
		$meta_desc ->setDecorators(array('Errors','ViewHelper'));
		
		
		$body = new Zend_Form_Element_Textarea('body');
		$body -> setRequired(true);
		$body ->setDecorators(array('Errors','ViewHelper'))
		      ->addErrorMessage($this->getView()->translate('Page body is required'));
					
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));
					  
		//status
		/*$showfooter = new Zend_Form_Element_Select('showfooter');
		$showfooter->addMultiOption(0, '-- Select footer --')			 
					  ->addMultiOption(1, 'Services')
					  ->addMultiOption(2, 'Websites')
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	*/			  
					  	
		
		$this -> addElements(array($name,$meta_keywords,$meta_desc, $body, $status));			  
					  
	}

}

