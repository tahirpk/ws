<?php

class admin_Form_AddMenu extends Zend_Form
{

   public function init()
    {
        /* Form Elements & Other Definitions Here ... */
    }
	
	public function __construct($options = array()){
	    parent::__construct($options);
		
		$exclude =array('field' => 'menu_name', 'value' => 0);
		if(isset($options['id'])){
		   $exclude = array('field' => 'menu_name', 'value' => $options['menu_name']);
		}
		
		
		$validator = new Zend_Validate_Db_NoRecordExists(array('table' => 'menu', 'field' => 'menu_name', 'exclude' => $exclude));
		$validator -> setMessage('Name already exists!');
		
		
		$menu = new Zend_Form_Element_Select('p_id');
		$table = new Application_Model_DbTable_Menu();
		$menu  -> setRequired(true)
				   -> setSeparator("")
				   -> setDecorators(array('Errors' => 'ViewHelper'))
					->setValidators(array($validator));
		$menu -> addMultiOption('0','-- Parent Menu --');
		$where = "p_id = 0 AND event_id=".$options['event_id'];
		foreach($table -> fetchAll($where) as $c){
		  $menu -> addMultiOption($c -> id, $c -> menu_name );
		}
		
		
		
		$menu_name = new Zend_Form_Element_Text('menu_name');
		$menu_name -> setRequired(true);
		$menu_name ->setDecorators(array('Errors','ViewHelper'))
		      ->setValidators(array($validator));

                
              
		
		//status
		$menu_status = new Zend_Form_Element_Select('menu_status');
		$menu_status->addMultiOption(1, 'Active')	
					  ->addErrorMessage($this->getView()->translate('Status is required and can\'t be empty'))			 
					  ->addMultiOption(0, 'Inactive')
					  ->setRequired(true)
					  ->setSeparator("")	
					  ->setDecorators(array('Errors','ViewHelper'));	
		
		$this -> addElements(array($menu_name, $menu_status,$menu));			  
					  
	}
	


}