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
class EpiDashboardController extends App_Controller_Base {

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


        // cold store stock 
        $xmlstore2 = $graphs->coldStoreStock(3);
        $this->view->xmlstore_cold_store_stock = $xmlstore2;


        // dry store stock 
        $xmlstore3 = $graphs->dryStoreStock(3);
        $this->view->xmlstore_dry_store_stock = $xmlstore3;


        // dry store stock 
        $xmlstore4 = $graphs->coldStoreMos(3);
        $this->view->xmlstore_cold_store_mos = $xmlstore4;


        // dry store stock 
        $xmlstore5 = $graphs->dryStoreMos(3);
        $this->view->xmlstore_dry_store_mos = $xmlstore5;


        $xmlstore6 = $graphs->summaryCrFr(3);
        $this->view->xmlstore_summary = $xmlstore6;
        // dry store stock 
        $xmlstore7 = $graphs->crCapacity(3);
        $this->view->xmlstore_crCapacity = $xmlstore7;

        // dry store stock 
        $xmlstore8 = $graphs->frCapacity(3);
        $this->view->xmlstore_frCapacity = $xmlstore8;

        // dry store stock 
        $xmlstore9 = $graphs->dryStoreCapacity(3);
        $this->view->xmlstore_dryStoreCapacity = $xmlstore9;


        $this->view->data = $graphs->getShipmentsData();

        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();

        if ($role_id == 4 || $role_id == 5 || $role_id == 6 || $role_id == 7) {
            $stock_master = new Model_StockMaster();
            $this->view->pending_receive = $stock_master->getPendingReceive();
        }

        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
    }

}
