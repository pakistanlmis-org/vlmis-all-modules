<?php

/**
 * Action Helper for initializing all views
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 * @author Ajmal Hussain <ajmal@deliver-pk.org>
 * 
 */
class App_Controller_Plugin_Helper_Init extends Zend_Controller_Action_Helper_Abstract {

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

        //$seconds = 60 * 60 * 24 * 1; // 1 day
        //Zend_Session::RememberMe($seconds);

        $appName = Zend_Registry::get('appName');
        $moduleName = $this->getRequest()->getModuleName();
        $controllerName = $this->getRequest()->getControllerName();
        $actionName = $this->getRequest()->getActionName();
        $controller = $this->getActionController();
        $view = new Zend_View();

        $auth = App_Auth::getInstance();

        if ($auth->hasIdentity()) {
            Zend_Layout::getMvcInstance()->assign('user_name', $auth->getUserName());
            Zend_Layout::getMvcInstance()->assign('user_role', $auth->getRoleId());
            Zend_Layout::getMvcInstance()->assign('user_role_name', $auth->getRoleName());
            Zend_Layout::getMvcInstance()->assign('user_store', $auth->getWarehouseName());
            Zend_Layout::getMvcInstance()->assign('total_user', $auth->getTotalUsers());
            Zend_Layout::getMvcInstance()->assign('user_record', $auth->getUserRecordId());
            Zend_Layout::getMvcInstance()->assign('user_count', $auth->getUserCount());
        }

        /* if ($auth->hasIdentity()) {
          $identity = $auth->getIdentity();
          if ($identity->role_id == 2 && $controllerName != 'accounts' && $controllerName != 'error' && $controllerName != 'index') {
          $accounts = new Application_Model_Accounts();
          $select = $accounts->select()->where("user_id=" . $identity->id);
          $result = $accounts->fetchRow($select);

          if (count($result) == 0) {
          $this->getResponse()->setRedirect('/accounts/create');
          }
          }
          } */

        /* if ($_SERVER['REQUEST_URI'] == '/index/login' || $_SERVER['REQUEST_URI'] == '/index/login/') {
          $this->getResponse()->setRedirect('/login');
          } */

        $baseUrl = Zend_Registry::get('baseurl');
        //echo $_SERVER['REQUEST_URI'];
        if ($controllerName == 'dashboard' && $actionName == 'vlmis') {
            // Do Nothing
        } else if ($controllerName == 'click-paths' || $controllerName == 'docs') {
            
        } else {
            if ($controllerName != 'index' && $moduleName != 'api' && $controllerName != 'error' && !$auth->hasIdentity()) {
                $this->getResponse()->setRedirect($baseUrl . '/index?referrer=' . base64_encode(str_replace("/vlmisr2/", "", $_SERVER['REQUEST_URI'])));
            }
            if ($moduleName == 'cadmin' && !$auth->hasIdentity()) {
                $this->getResponse()->setRedirect($baseUrl . '/index?referrer=' . base64_encode(str_replace("/vlmisr2/", "", $_SERVER['REQUEST_URI'])));
            }
            if ($moduleName == 'iadmin' && !$auth->hasIdentity()) {
                $this->getResponse()->setRedirect($baseUrl . '/index?referrer=' . base64_encode(str_replace("/vlmisr2/", "", $_SERVER['REQUEST_URI'])));
            }
            if ($moduleName == 'campaign' && !$auth->hasIdentity()) {
                $this->getResponse()->setRedirect($baseUrl . '/campaign/manage-campaigns?referrer=' . base64_encode(str_replace("/vlmisr2/", "", $_SERVER['REQUEST_URI'])));
            }
        }

        Zend_Registry::set('controller', $controllerName);
        Zend_Registry::set('action', $actionName);

        //Get the scripts and css directories
        $scripts = (empty($moduleName)) ? new App_Directory('js/' . $controllerName) : new App_Directory('js/' . $moduleName . '/' . $controllerName);
        $css = (empty($moduleName)) ? new App_Directory('css/' . $controllerName) : new App_Directory('css/' . $moduleName . '/' . $controllerName);

        //Append the scripts (if any) that pertain to this action and controller
        if ($scripts->exists()) {
            $scripts = $scripts->getFiles();

            foreach ($scripts as $script) {
                if ($script === $actionName . ".js") {
                    if (empty($moduleName)) {
                        $view->inlineScript()->appendFile($baseUrl . '/js/' . $controllerName . '/' . $script);
                    } else {
                        $view->inlineScript()->appendFile($baseUrl . '/js/' . $moduleName . '/' . $controllerName . '/' . $script);
                    }
                }
            }
        }

        //Append the stylesheets (if any) that pertain to this action and controller
        if ($css->exists()) {
            $css = $css->getFiles();
            foreach ($css as $stylesheet) {
                if ($stylesheet === $actionName . ".css") {
                    if (empty($moduleName)) {
                        $view->headLink()->appendStylesheet($baseUrl . '/css/' . $controllerName . '/' . $stylesheet);
                    } else {
                        $view->headLink()->appendStylesheet($baseUrl . '/css/' . $moduleName . '/' . $controllerName . '/' . $stylesheet);
                    }
                }
            }
        }

        $appName = Zend_Registry::get('appName');
        $view->inlineScript()->prependScript('var appName = "' . $appName . '"');
        $view->prefix = ($this->getRequest()->isXmlHttpRequest() || $this->getRequest()->getParam('ajax') == 'true') ? 'ajax' : 'page';
        $view->action = $actionName;
        $view->controller = $controllerName;
        if ($moduleName == 'default') {
            Zend_Registry::set('resource', $controllerName . "/" . $actionName);
        } else {
            Zend_Registry::set('resource', $moduleName . "/" . $controllerName . "/" . $actionName);
        }

        $arr_data = App_Controller_Functions::getPageTitleAndMeta(Zend_Registry::get('resource'));
        Zend_Registry::set('pageTitle', $arr_data['pageTitle']);
        Zend_Registry::set('metaTitle', $arr_data['metaTitle']);
        Zend_Registry::set('metaDescription', $arr_data['metaDescription']);
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