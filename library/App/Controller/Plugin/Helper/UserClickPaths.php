<?php

/**
 * Action Helper for initializing all views
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 * @author Ajmal Hussain <ajmal@deliver-pk.org>
 * 
 */
class App_Controller_Plugin_Helper_UserClickPaths extends Zend_Controller_Action_Helper_Abstract {

    /**
     * @var Zend_Loader_PluginLoader
     */
    public $pluginLoader;

    /**
     * Constructor: initialize plugin loader
     *
     * @return void
     */
    public function __construct() {
        $this->pluginLoader = new Zend_Loader_PluginLoader();
    }

    /**
     * Initializes the application with global standards for the view
     *
     * @access public
     * @return void
     */
    public function init() {
        $moduleName = $this->getRequest()->getModuleName();
        $controllerName = $this->getRequest()->getControllerName();
        $actionName = $this->getRequest()->getActionName();

        if ($moduleName == 'default') {
            $resource_name = $controllerName . "/" . $actionName;
        } else {
            $resource_name = $moduleName . "/" . $controllerName . "/" . $actionName;
        }

        $auth = App_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $ucp = new Model_UserClickPaths();
            $ucp->addUserClickPaths($resource_name);
        }
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param  string $name
     * @param  array|Zend_Config $options
     * @return Zend_Form
     */
    public function direct() {
        return $this->init();
    }

}

?>