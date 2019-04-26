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
class ColdChainDashboardController extends App_Controller_Base {

    /**
     * DashboardController index
     */
    public function indexAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;



        $xmlstore6 = $graphs->summaryFun(3);
        $this->view->xmlstore_summary = $xmlstore6;
        // dry store stock 
        $xmlstore7 = $graphs->ColdChainCapacityGraphs(3);
        $this->view->xmlstore_crCapacity = $xmlstore7;

        $this->view->data = $graphs->getCcemSummaryReport();

        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();



        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
    }

}
