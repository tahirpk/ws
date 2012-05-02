<?php

class admin_Form_AddEvent extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
	
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'event_title', 'value' => 0);
		if(isset($options['id'])){
		   $exclude = array('field' => 'event_title', 'value' => $options['event_title']);
		}
		
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'event', 'field' => 'event_title', 'exclude' => $exclude));
		$validator -> setMessage('Name already exists!');
		
		
		
	        $event_title = new Zend_Form_Element_Text('event_title');
		//$event_title -> setRequired(true);
//		$event_title->setDecorators(array('Errors','ViewHelper'))
//		      ->setValidators(array($validator));	
                
                $event_description = new Zend_Form_Element_Textarea('event_description');
		//$event_description -> setRequired(true);
		$event_description->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
                
//		$dj1 = new Zend_Form_Element_Text('dj1');
//		$dj1 -> setRequired(true);
//		$dj1 ->setDecorators(array('Errors','ViewHelper'))
//		      ->setValidators(array($validator));
                
                
                
                $entrance_fee = new Zend_Form_Element_Text('entrance_fee');
		//$entrance_fee -> setRequired(true);
//		$entrance_fee ->setDecorators(array('Errors','ViewHelper'))
//		      ->setValidators(array($validator));
                
                $total_guests_allowed = new Zend_Form_Element_Text('total_guests_allowed');
		//$total_guests_allowed -> setRequired(true);
//		$total_guests_allowed ->setDecorators(array('Errors','ViewHelper'))
//		      ->setValidators(array($validator));
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		
		$this -> addElements(array($event_title, $event_description, $entrance_fee, $total_guests_allowed, $status));			  
					  
	}
	


}

