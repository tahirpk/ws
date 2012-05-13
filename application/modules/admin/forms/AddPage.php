<?php

class Admin_Form_AddPage extends Zend_Form
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
		
		$validator_pg = new Zend_Validate_Db_NoRecordExists(array('table' => 'pages', 'field' => '	pageUrl', 'exclude' => $exclude));
		$validator_pg -> setMessage('Page already exists!');
		
		
                $webId = new Zend_Form_Element_Select('webId');
		$table = new Application_Model_DbTable_Websites();
		$webId  -> setRequired(true)
                        ->addErrorMessage('Page URL is required')
                        -> setSeparator("")
                        -> setDecorators(array('Errors' => 'ViewHelper'));
		$webId -> addMultiOption('','-- Site URL --');
		foreach($table -> fetchAll() as $b){
		  $webId -> addMultiOption($b -> id, $b -> url);
		}
                
               
		$pageTitle = new Zend_Form_Element_Text('pageTitle');
                $pageTitle->addFilter('StripTags')
                                            ->addErrorMessage($this->getView()->translate('Valid Page Title is required'))
                                            ->addFilter('StringTrim')
                                            ->setRequired(false)
                                            ->setDecorators(array('Errors','ViewHelper'));
			
                $pageCaption = new Zend_Form_Element_Text('pageCaption');
                $pageCaption->addFilter('StripTags')
                                            ->addErrorMessage($this->getView()->translate('Valid Page Caption is required'))
                                            ->addFilter('StringTrim')
                                            ->setRequired(false)
                                            ->setDecorators(array('Errors','ViewHelper'));
			
                $pageKeywords = new Zend_Form_Element_Text('pageKeywords');
                $pageKeywords->addFilter('StripTags')
                                            ->addErrorMessage($this->getView()->translate('Valid Page Keywords is required'))
                                            ->addFilter('StringTrim')
                                            ->setRequired(false)
                                            ->setDecorators(array('Errors','ViewHelper'));

                $pageMetatags = new Zend_Form_Element_Text('pageMetatags');
                $pageMetatags->addFilter('StripTags')
                                            ->addErrorMessage($this->getView()->translate('Valid Page Meta Tags is required'))
                                            ->addFilter('StringTrim')
                                            ->setRequired(false)
                                            ->setDecorators(array('Errors','ViewHelper'));

                        $url_validator1 = new Zend_Validate_Regex('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i');
			$url_validator1->setMessage($this->getView()->translate('Valid URL including htttp(s):// is required'));
			
			$pageUrl = new Zend_Form_Element_Text('pageUrl');
			$pageUrl->addFilter('StripTags')
						  ->addErrorMessage('Valid Page URL is required')
						  ->addFilter('StringTrim')
                                                    ->setRequired(true)
                                                  
                                                   ->addValidator($url_validator1,true)
						   ->setDecorators(array('Errors','ViewHelper'));
			
			$pageContent = new Zend_Form_Element_Textarea('pageContent');
			$pageContent->addErrorMessage($this->getView()->translate('Valid Description is required'))
						  ->setRequired(false)
						  ->setDecorators(array('Errors','ViewHelper'));
						
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(false)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));
		

			
			$this->addElements(array( $webId,$pageCaption, $pageTitle, $pageKeywords, $pageMetatags,$pageUrl, $pageContent, $status));
					  
	}
	


}

