<?php

class admin_Form_AddVenue extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
	
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'venue_name', 'value' => 0);
		if(isset($options['venue_name'])){
		   $exclude = array('field' => 'venue_name', 'value' => $options['venue_name']);
		}
		
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'venue', 'field' => 'venue_name', 'exclude' => $exclude));
		$validator -> setMessage('Name already exists!');
		
		
		
		$category = new Zend_Form_Element_Select('category');
		$table = new Application_Model_DbTable_Category();
		$category  -> setRequired(true)
				   -> setSeparator("")
				   -> setDecorators(array('Errors' => 'ViewHelper'))
					->setValidators(array($validator));
		$category -> addMultiOption('','-- Parent Category --');
		foreach($table -> fetchAll() as $c){
		  $category -> addMultiOption($c -> id, $c -> name);
		}			
		
		$venue_name = new Zend_Form_Element_Text('venue_name');
		$venue_name -> setRequired(true);
		$venue_name ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$house_no = new Zend_Form_Element_Text('house_no');
		$house_no -> setRequired(true);
		$house_no ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$street = new Zend_Form_Element_Text('street');
		$street -> setRequired(true);
		$street ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$city = new Zend_Form_Element_Text('city');
		$city -> setRequired(true);
		$city ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		$country = new Zend_Form_Element_Select('country');
		$table = new Application_Model_DbTable_Country();
		$country  -> setRequired(true)
					-> setSeparator("")
					-> setDecorators(array('Errors' => 'ViewHelper'));
					
		$country -> addMultiOption(0,'-- Country --');
		foreach($table -> fetchAll() as $c){
		  $country -> addMultiOption($c -> iso, $c -> name);
		}			
		
		
		$post_code = new Zend_Form_Element_Text('post_code');
		$post_code -> setRequired(true);
		$post_code ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		$telephone = new Zend_Form_Element_Text('telephone');
		$telephone -> setRequired(true);
		$telephone ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$latitude = new Zend_Form_Element_Text('latitude');
		$latitude -> setRequired(true);
		$latitude ->setDecorators(array('Errors','ViewHelper'))
		      	->setValidators(array($validator));
			  
		$longitude = new Zend_Form_Element_Text('longitude');
		$longitude -> setRequired(true);
		$longitude ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		
		$first_name = new Zend_Form_Element_Text('first_name');
		$first_name -> setRequired(true);
		$first_name ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		
		$last_name = new Zend_Form_Element_Text('last_name');
		$last_name -> setRequired(true);
		$last_name ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
				
		$username = new Zend_Form_Element_Text('username');
		$username -> setRequired(true);
		$username ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		$email = new Zend_Form_Element_Text('email');
		$email -> setRequired(true);
		$email ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		$facebook = new Zend_Form_Element_Text('facebook');
		$facebook -> setRequired(true);
		$facebook ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
		
		$twitter = new Zend_Form_Element_Text('twitter');
		$twitter -> setRequired(true);
		$twitter ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$youtube = new Zend_Form_Element_Text('youtube');
		$youtube -> setRequired(true);
		$youtube ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		$website = new Zend_Form_Element_Text('website');
		$website -> setRequired(true);
		$website ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));
			  
		
		//status
		$status = new Zend_Form_Element_Select('status');
		$status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		
		$this -> addElements(array($category, $venue_name, $house_no, $street, $city, $post_code, $country, $telephone,$latitude,
		$longitude, $first_name, $last_name, $username, $email, $facebook, $twitter, $youtube, $website, $status));			  
					  
	}
	


}

