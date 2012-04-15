<?php
class admin_Form_AddWebsites extends Zend_Form
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
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'websites', 'field' => 'url', 'exclude' => $exclude));
		$validator -> setMessage('URL already exists!');
				
		// form fields 
		$webTitle = new Zend_Form_Element_Text('webTitle');
		$webTitle -> setRequired(true);
		$webTitle ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$url = new Zend_Form_Element_Text('url');
		$url -> setRequired(true);
		$url ->setDecorators(array('Errors','ViewHelper'))
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
					  
		$filePdf = new Zend_Form_Element_File('filePdf');
		$filePdf->setLabel('Upload an url pdf:')
					   ->setDestination(realpath(APPLICATION_PATH."/../public/frontend/webpdf/"));
		 $filePdf->addErrorMessage($this->getView()->translate('file in pdf is required and can\'t be empty'))			 
			  ->setRequired($required);
					  $filePdf->addValidator('Count', false, 1);
					  $filePdf->addValidator('Size', false, 10240000);
					  $filePdf->addValidator('Extension', false, 'pdf');
					  
		$this -> addElements(array($webTitle,$url,$status,$filePdf));			  
					  
	}
	


}
?>