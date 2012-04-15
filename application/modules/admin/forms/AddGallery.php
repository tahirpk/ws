<?php

class admin_Form_AddGallery extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
	
	public function __construct($options = array()){
	    parent::__construct($options);
		
			
		  
					  
	}
	
	public function image($vid){
		$image = new Zend_Form_Element_File('image');
				$image->setLabel('Upload an image:')
       	 				  	->setDestination(APPLICATION_PATH.'/../public/gallery/')
							->addValidator('Count', false, 1)
							->setRequired(true)
							->addValidator('Size', false, 702400)
							// only JPEG, PNG, and GIFs
							->addValidator('Extension', true, 'jpg,png,gif,bmp');
//echo APPLICATION_PATH.'/../public/gallery/';die;
		
			  
			
		$venue = new Zend_Form_Element_Hidden('venue_id');
		$venue -> setValue($vid);
		
		
		
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		//exit();
		$this -> addElements(array($image, $venue, $status));			
	}
	
	public function video($vid){
		
		$image = $this->createElement('text', 'video');
    		 $image->setRequired(true)
			 ->setLabel('Video');
			  
			
		$venue = new Zend_Form_Element_Hidden('venue_id');
		$venue -> setValue($vid);
		
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		$this -> addElements(array($image, $venue, $status));			
	}

}

