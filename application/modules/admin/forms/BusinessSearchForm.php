<?php 
	class Admin_Form_BusinessSearchForm extends Zend_Form
	{
		public function init()
		{
		
			$filters = new Zend_Form_Element_Select('filters');
			$filters->addMultiOption('businessName',$this->getView()->translate('Business Name'))
				   	->addMultiOption('id',$this->getView()->translate('Enter the digits only'));
			$filters->setAttrib('onchange','javascript:getDropDown(this.value)');		
			
			$filterText = new Zend_Form_Element_Text('filterText');
			$filterText->addFilter('StripTags')
					   ->addErrorMessage($this->getView()->translate('Valid Filter text is required'))
					   ->addFilter('StringTrim')
					   ->setDecorators(array('Errors','ViewHelper'));
			
			$perPage = new Zend_Form_Element_Select('perPage');
			$perPage->addMultiOption('25', '25')
					->addMultiOption('50', '50')
					->addMultiOption('100', '100')
					->addMultiOption('All', 'All');	
			$perPage->setAttrib('onchange','javascript:setPaging(this)');
								  
			$this->addElements(array($filters,$filterText,$perPage));
			$this->addDecorator('FormElements')
					->addDecorator('HtmlTag', array('tag' => '')) 
					->addDecorator('Form'); 
			$this->setElementDecorators(array( array('Errors',array('tag','ul')), array('ViewHelper') ));
		}
	}
	
	
?>