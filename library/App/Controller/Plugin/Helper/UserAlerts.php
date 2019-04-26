<?php

/**
 * Action Helper for initializing all views
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 * @author Ajmal Hussain <ajmal@deliver-pk.org>
 * 
 */
class App_Controller_Plugin_Helper_UserAlerts extends Zend_Controller_Action_Helper_Abstract {

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



        $auth = App_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $stock_master = new Model_StockMaster();
            $stock_out = $stock_master->getStockOuts();
            Zend_Layout::getMvcInstance()->assign('stock_out', $stock_out);
            $expiry_alerts = $stock_master->getExpiryAlerts();
            Zend_Layout::getMvcInstance()->assign('expiry_alerts', $expiry_alerts);
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