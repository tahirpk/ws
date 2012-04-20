<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    #stores a copy of the config object in the Registry for future references
	#!IMPORTANT: Must be runed before any other inits
   protected function _initConfig()
    {
    	$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'development');
		Zend_Registry::set('config', $config);
		$this -> set_application_locale_admin("de_DE");
		global $db;
		$db = Zend_Db::factory($config -> resources -> db -> adapter, array(
			'host' => $config -> resources -> db -> params->host,
			'username' => $config -> resources -> db -> params->username,
			'password' => $config -> resources -> db -> params->password,
			'dbname' => $config -> resources -> db -> params->dbname
		));
		
		Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH .'/controllers/helpers');
		
		$frontController = Zend_Controller_Front::getInstance();
		$router = $frontController->getRouter();
	
		$route = new Zend_Controller_Router_Route('page/:id',array('controller' => 'page', 'action' => 'show'));
		$router -> addRoute('page',$route);
		
	}
	
	protected function _initAutoloaders()
	{
	   $autoloader = Zend_Loader_Autoloader::getInstance();
	   $autoloader->setFallbackAutoloader(true);
	
	   $default_loader = new Zend_Application_Module_Autoloader(array(
			 'namespace' => '',
			 'basePath'  => APPLICATION_PATH
		 ));
		 
		 $admin_loader = new Zend_Application_Module_Autoloader(array(
				   'namespace' => 'Admin',
				   'basePath'  => APPLICATION_PATH . '/modules/admin'
		));
		 
		 
	}

        protected function _initResourceAutoloader()
        {
            $autoloader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'  => APPLICATION_PATH,
            'namespace' => 'Application',
            ));

            $autoloader->addResourceType( 'model', 'models', 'Model');
            return $autoloader;
        }


   // locale method for admin
	private function set_application_locale_admin($locale,$save=true)
	{
		$locale = 'en_PK'; 
		$locale = new Zend_Locale($locale);
		Zend_Locale_Format::setOptions(
                array('locale' => 'en',
                            'fix_date' => true,
                            'format_type' => 'php'
                            )
                );

		Zend_Registry::set('Zend_Locale', $locale);
		$translate = new Zend_Translate('gettext','../language/'.$locale.'.mo',$locale);
		Zend_Registry::set('Zend_Translate',$translate);
		
		
	}
   
}

