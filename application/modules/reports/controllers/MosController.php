<?php

/**
 * DashboardController
 *
 * 
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */
require_once 'FusionCharts/Code/PHP/Includes/FusionCharts.php';

/**
 * Controller for Dashboard
 */
class Reports_MosController extends App_Controller_Base {

    /**
     * DashboardController index
     */
    public function mosAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_ExecutiveDashboard();
        $s_level = $this->_request->getPost('s_level');

        if ($s_level != "") {
            $graphs->form_values['s_level'] = $s_level;
            $this->view->s_level = $s_level;
        } else {
            $graphs->form_values['s_level'] = "all";
            $this->view->s_level = "all";
            
        }
       
        // stock status
        $dd1 = $graphs->getMosData();
        $this->view->dd1 = $dd1;
        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

}
