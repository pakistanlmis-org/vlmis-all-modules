<?php

/**
 * ErrorController
 *
 * 
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
* Controller for Error
*/

class ErrorController extends App_Controller_Base {

    /**
     * Error action.
     */
    public function errorAction() {
        // Set layout.
        $this->_helper->layout->setLayout('error');
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        // Check exception types.
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message . " : " . $errors->exception, $priority);
            $log->log('Request Parameters' . " : " . implode("/", array_reverse($errors->request->getParams())) . "\n", $priority);
        } else {
            App_FileLogger::info($this->view->message . " : " . $errors->exception);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions')) {
            $this->view->exception = $errors->exception;
        }

        // set view.
        $this->view->request = $errors->request;
    }

    /**
     * Get Log
     * @return boolean
     */
    public function getLog() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        // Get resource.
        return $bootstrap->getResource('Log');
    }

    /**
     * Forbidden
     */
    public function forbiddenAction() {
        // Set layout.
        $this->_helper->layout->disableLayout();
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headLink()->appendStylesheet($base_url . '/common/assets/admin/pages/css/error.css');
    }

}
