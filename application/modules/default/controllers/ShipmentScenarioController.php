<?php

/**
 * ShipmentScenarioController
 *
 * 
 *
 * @subpackage Default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */
require_once 'FusionCharts/Code/PHP/Includes/FusionCharts.php';

/**
 * Controller for Click Paths
 */
class ShipmentScenarioController extends App_Controller_Base {

    /**
     * shipment Scenario
     */
    public function shipmentScenarioAction() {
        $form = new Form_Scenario();
        $non_ccm_location = new Model_NonCcmLocations();
        if ($this->_request->getPost()) {
            $non_ccm_location->form_values = $this->_request->getPost();
            $var = $non_ccm_location->placement();
            if ($var == 0) {
                $this->redirect("/stock/placement?error=1");
            } else {
                $this->redirect("/stock/placement?success=1");
            }
        }
        $this->view->form = $form;
        $result = $non_ccm_location->getLocationsName();
        $this->view->result = $result;

        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();
        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;
        $xmlstore1 = $graphs->coldChainCapacityProduct(15);
        $this->view->xmlstore1 = $xmlstore1;
        $xmlstore2 = $graphs->coldChainCapacityProduct(16);
        $this->view->xmlstore2 = $xmlstore2;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->data = $graphs->coldChainCapacityProduct(2);

        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();

        if ($role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 7) {
            $stock_master = new Model_StockMaster();
            $this->view->pending_receive = $stock_master->getPendingReceive();
        }
        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/reports/dashlet/cold-chain-capacity.js');
    }

}
