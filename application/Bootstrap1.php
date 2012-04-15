<?php
/**
 * Bootstrap of Coeus Framework
 */
 //die("hello");

class Bootstrap1 extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize the autoloader
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        // Ensure front controller instance is present
        $this->bootstrap('frontController');
       // Get frontController resource
        $this->_front = $this->getResource('frontController');
       // Return it, so that it can be stored by the bootstrap

	    $autoloader = Zend_Loader_Autoloader::getInstance();
	    $autoloader->setFallbackAutoloader(true);

            $default_loader = new Zend_Application_Module_Autoloader(array(
			 'namespace' => '',
			 'basePath'  => APPLICATION_PATH,
                         'resourceTypes' => array(
                            
                            'model' => array(
                                'path'      => 'models/',
                                'namespace' => 'Application_Model_'
                            ),
				
                          )

		 ));
           $layout = Zend_Layout::disableLayout();
           $this->_front->setParam('noViewRenderer', true);

     }


    /**
     * Initialize the site configuration
     *
     * @return Zend_Config_Xml
     */
    protected function _initConfig()
    {
        // Retrieve configuration from file
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/gameapplication.ini', 'development');

        // Add config to the registry so it is available sitewide
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);
        // Return it, so that it can be stored by the bootstrap
        return $config;
    }


    /**
     * Initialize data base
     *
     */
    protected function _initDb()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');
        // Setup database
        $db = Zend_Db::factory($config -> resources -> db -> adapter, $config -> resources -> db -> params);
       // $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $db->query("SET NAMES 'utf8'");
        $db->query("SET CHARACTER SET 'utf8'");
        Zend_Db_Table::setDefaultAdapter($db);
        // Return it, so that it can be stored by the bootstrap
        return $db;
    }
    
    protected function _initRoutes(){
        $router = $this->_front->getRouter();
        $router->addRoute('api_routes',new Zend_Controller_Router_Route(':controller/:action/:id/:id2/:value/',array('id' => 0,'id2' => 0,'value' => 0)));
    }
    protected function ____initCache() {
       try{
           
        // Cache options
        $frontendOptions = array(
           'lifetime' => 10000,                      // Cache lifetime of 20 minutes
           'automatic_serialization' => true,
        );
        $backendOptions = array(
            'lifetime' => 80000,                     // Cache lifetime of 1 hour
            'cache_dir' =>  '../cache',   // Directory where to put the cache files
        );
        // Get a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('areacache', $cache);
        // Return it, so that it can be stored by the bootstrap
        return $cache;
       } catch(Exception $e){
           echo $e; die;
       }
    }

}

