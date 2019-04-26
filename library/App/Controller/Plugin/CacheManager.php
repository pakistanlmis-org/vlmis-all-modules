<?php

class App_Controller_Plugin_CacheManager extends Zend_Controller_Plugin_Abstract {

    public function __construct() {
        $configFile = APPLICATION_PATH . '/configs/application.ini';
        $config = new Zend_Config_Ini($configFile, APPLICATION_ENV);

        $cacheManager = new Zend_Cache_Manager;
        $cacheManager->setCacheTemplate('file', $config->resources->cachemanager->file);

        Zend_Registry::set('cacheManager', $cacheManager);
    }

    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * This callback allows for proxy or filter behavior.  By altering the
     * request and resetting its dispatched flag (via
     * {@link Zend_Controller_Request_Abstract::setDispatched() setDispatched(false)}),
     * the current action may be skipped.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
    }

}
