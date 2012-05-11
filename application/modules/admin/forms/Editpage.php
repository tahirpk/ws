<?php
	class Admin_Form_EditPage extends Zend_Form
	{
		public function __construct($options = null)
		{
			parent::__construct($options);
			
			$page_caption = new Zend_Form_Element_Text('page_caption');
			$page_caption->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Valid Page Caption is required'))
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));
			
			$page_title = new Zend_Form_Element_Text('page_title');
			$page_title->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Valid Page Title is required'))
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));
			
			$page_keywords = new Zend_Form_Element_Text('page_keywords');
			$page_keywords->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Valid Page Keywords are required'))
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));
			
			$page_metatags = new Zend_Form_Element_Text('page_metatags');
			$page_metatags->addFilter('StripTags')
						  ->addErrorMessage($this->getView()->translate('Valid Page Meta Tags are required'))
						  ->addFilter('StringTrim')
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));
			
			$page_content = new Zend_Form_Element_Textarea('page_content');
			$page_content->addErrorMessage($this->getView()->translate('Valid Description is required'))
						  ->setRequired(true)
						  ->setDecorators(array('Errors','ViewHelper'));
			
			$status = new Zend_Form_Element_Radio('status');
			$status->setRequired(true)
						->addErrorMessage($this->getView()->translate('Valid Status is required'))
						->addMultiOption('1', ' Active')
						->addMultiOption('0', ' Inactive')
						->setSeparator("")
						->setDecorators(array('Errors','ViewHelper'));
			
			$this->addElements(array($page_caption, $page_title, $page_keywords, $page_metatags, $page_content, $status));
		}
	}
?>