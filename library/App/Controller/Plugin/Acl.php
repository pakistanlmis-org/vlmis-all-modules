<?php

class App_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    /**
     * @var Zend_Auth
     */
    private $_auth = null;

    /**
     * @var Zend_Acl
     */
    private $_acl = null;
    private $_em = null;

    public function __construct() {
        $this->_auth = App_Auth::getInstance();

        $cache = Zend_Registry::get('cacheManager')->getCache('file');

        if (!$this->_acl = $cache->load('acl')) {
            $this->_acl = new App_Acl();
            $cache->save($this->_acl, 'acl');
        }

        $this->_em = Zend_Registry::get("doctrine");
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
        // reset role & resource
        Zend_Registry::set('Role', 'guest');
        Zend_Registry::set('Resource', '');

        // check if ErrorHandler wasn't fired
        if ($request->getParam('error_handler')) {
            return;
        }

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $pathInfo = $request->getPathInfo();

        $allow = false;

        if ($this->_auth->hasIdentity()) {
            $userId = $this->_auth->getIdentity();
            $roleId = $this->_auth->getRoleId();

            $rolesList = $this->_em->find('Roles', $roleId);
            $roleName = $rolesList->getRoleName();
            $role = new Zend_Acl_Role($roleName);
        } else {
            $roleName = 'guest';
            $role = new Zend_Acl_Role($roleName);
        }

        $resource = ($action == '') ? trim($controller) . '/index' : trim($controller) . '/' . trim($action);
        $resource = ($module == 'default') ? $resource : $module . "/" . $resource;

        // on main page resource might be empty
        if ($resource == '')
            $resource = 'index/index';

        // if resource not exist in db then check permission for controller
        if (!$this->_acl->has($resource) && $action != '') {
            $resource = trim($controller);
        }

        // check if user is allowed to see the page
        $allow = $this->_acl->isAllowed($role, $resource);

        if ($allow == false && $this->_auth->hasIdentity()) {
            // user logged in but denied permission
            $request->setModuleName('default');
            $request->setControllerName('error');
            $request->setActionName('forbidden');

            /*
             * $this->_response->setHeader('Content-type', 'text/html');
             * $this->_response->setHttpResponseCode(403);
             * $this->_response->setBody('<h1>403 - Forbidden</h1>');
             * $this->_response->sendResponse();
             */
        }

        Zend_Registry::set('Role', $role);
        Zend_Registry::set('Resource', $resource);
        Zend_Registry::set('acl', $this->_acl);
    }

}
