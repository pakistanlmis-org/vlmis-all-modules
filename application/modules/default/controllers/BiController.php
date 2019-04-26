<?php

/**
 * IndexController
 * 
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Controller for Index
 */
class BiController extends App_Controller_Base {

    /**
     * indexAction index
     */
    public function indexAction() {
        $this->_helper->layout->setLayout("layout");

        $this->view->headTitle('BI Tool');
    }    
    public function importAction() {
        $this->_helper->layout->setLayout("layout");

        $this->view->headTitle('Data Import');
    }    
    public function msAction() {
        $this->_helper->layout->setLayout("layout");
        $this->view->headTitle('BI Tool');
    }
}
