<?php

/**
 * Bootstrap
 * used to initialize
 * code that executes before anything else gets executed, 
 * 1 - autoloading,
 * 2 - initialize your plugins,
 * 3 - setting datetimezone
 */

/**
 * Bootstrap to initialize bootstrap related stuff.
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Initialize html doctype
     * XHTML5
     */
    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML5');
        $view->headTitle()->setSeparator(' - ');
    }

    /**
     * Initialize Init Helper and Layout Plugin
     */
    protected function _initHelper() {
        $initHelper = new App_Controller_Plugin_Helper_Init();
        Zend_Controller_Action_HelperBroker::addHelper($initHelper);
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/controllers/helpers');

        $UserClickPathHelper = new App_Controller_Plugin_Helper_UserClickPaths();
        Zend_Controller_Action_HelperBroker::addHelper($UserClickPathHelper);
        
        
        $UserAlertHelper = new App_Controller_Plugin_Helper_UserAlerts();
        Zend_Controller_Action_HelperBroker::addHelper($UserAlertHelper);
   
    }

    /**
     * Initialize configuration values
     * in zend registry
     * memory_limit
     * $configFile
     * $config
     * dbSetting
     * cacheManager
     * appName
     * baseurl
     * first_month
     * lang_support
     * api_from_date
     * barcode_products
     * smtpConfig
     * report_month
     */
    protected function _initConfig() {
        ini_set('memory_limit', '-1');
        $configFile = APPLICATION_PATH . '/configs/application.ini';
        $config = new Zend_Config_Ini($configFile, APPLICATION_ENV);
        Zend_Registry::set('config', $config);
        Zend_Registry::set('dbSetting', $config->resources->db);
        Zend_Registry::set('cacheManager', $config->resources->cachemanager->file);
        Zend_Registry::set('appName', $config->app->name);
        Zend_Registry::set('baseurl', $config->app->baseurl);
        Zend_Registry::set('first_month', $config->app->first_month);
        Zend_Registry::set('lang_support', $config->app->language_support);
        Zend_Registry::set('api_from_date', $config->app->api_from_date);
        Zend_Registry::set('barcode_products', $config->app->barcode_products);
        Zend_Registry::set('smtpConfig', $config->smtpConfig);
        $dateobj = new DateTime('-3 month');
        Zend_Registry::set('report_month', $dateobj->format("Y-m"));
        Zend_Registry::set('is_sms_enable', $config->app->is_sms_enable); 
    }

    /**
     * Initialize application autoloader
     * namespace
     * basePath
     */
    protected function _initAutoload() {
        return new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => dirname(__FILE__),
        ));
    }

    /**
     * _initDoctrine
     * @return type
     */
    protected function _initDoctrine() {

        require_once LIBRARY_PATH . '/Doctrine/Common/ClassLoader.php';
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $fmmAutoloader = new \Doctrine\Common\ClassLoader();
        $autoloader->pushAutoloader(array($fmmAutoloader, 'loadClass'));

        $options = $this->getOptions();
        $config = new Doctrine\ORM\Configuration();
        $config->addCustomDatetimeFunction('YEAR', 'Doctrine\Extensions\Query\Mysql\Year');
        $config->addCustomDatetimeFunction('MONTH', 'Doctrine\Extensions\Query\Mysql\Month');
        $config->addCustomDatetimeFunction('DAY', 'Doctrine\Extensions\Query\Mysql\Day');
        $config->addCustomStringFunction('DATEDIFF', 'Doctrine\Extensions\Query\Mysql\DateDiff');
        $config->addCustomStringFunction('DATE_FORMAT', 'Doctrine\Extensions\Query\Mysql\DateFormat');
        $config->addCustomStringFunction('IF', 'Doctrine\Extensions\Query\Mysql\IfElse');
        $config->addCustomStringFunction('GROUP_CONCAT', 'Doctrine\Extensions\Query\Mysql\GroupConcat');
        $config->addCustomStringFunction('IFNULL', 'Doctrine\Extensions\Query\Mysql\IfNull');
        $config->setProxyDir($options['doctrine']['metadata']['proxyDir']);
        $config->setProxyNamespace('Doctrine\Proxy');
        $config->setAutoGenerateProxyClasses((APPLICATION_ENV == 'localhost'));
        $driverImpl = new Doctrine\ORM\Mapping\Driver\YamlDriver($options['doctrine']['metadata']['entityDir']);

        $config->setMetadataDriverImpl($driverImpl);
        $cache = new Doctrine\Common\Cache\ArrayCache();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        $evm = new Doctrine\Common\EventManager();
        $em = Doctrine\ORM\EntityManager::create($options['doctrine']['db'], $config, $evm);
        Zend_Registry::set('doctrine', $em);

        $em_read = Doctrine\ORM\EntityManager::create($options['doctrine_read']['db'], $config, $evm);
        Zend_Registry::set('doctrine_read', $em_read);
    }

    /**
     * _initLog
     */
    protected function _initLog() {
        if ($this->hasPluginResource("log")) {
            $r = $this->getPluginResource("log");
            $log = $r->getLog();
            Zend_Registry::set('log', $log);
        }
    }

}
