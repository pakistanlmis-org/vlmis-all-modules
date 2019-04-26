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
class ExecutiveDashboardController extends App_Controller_Base {

    /**
     * DashboardController index
     */
    public function indexAction() {
        $this->_helper->layout->setLayout("layout");
        // products
        $item = new Model_ItemPackSizes();
        $items = $item->getAllVacc();

        $this->view->products = $items;
        $data = $this->_request->getPost();
        $to_date = $data['last_month'];
        $product = $data['products'];

        $graphs->form_values['to_date'] = $to_date;
        $graphs->form_values['product'] = $product;

        $this->view->to_date = $to_date;
        $this->view->product = $product;

        $re_month = explode('/', $to_date);

        if (!empty($re_month['1'])) {
            $this->view->month = $re_month['1'];
        } else {
            $this->view->month = date('m') - 1;
        }
        if (!empty($re_month['2'])) {
            $this->view->year = $re_month['2'];
        } else {
            $this->view->year = date('Y');
        }



        //$auth = App_Auth::getInstance();
        //  $role_id = $auth->getRoleId();
    }

    /**
     * DashboardController index
     */
    public function d1Action() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_ExecutiveDashboard();
        $to_date = $this->_request->getPost('last_date');
        $product = $this->_request->getPost('product');

        $graphs->form_values['to_date'] = $to_date;
        $graphs->form_values['product'] = $product;
        $this->view->to_date = $to_date;
        $this->view->product = $product;

        $re_month = explode('/', $to_date);

        if (!empty($re_month['1'])) {
            $this->view->month = $re_month['1'];
        } else {
            $this->view->month = date('m') - 1;
        }
        if (!empty($re_month['2'])) {
            $this->view->year = $re_month['2'];
        } else {
            $this->view->year = date('Y');
        }

        // stock status
        $dd1 = $graphs->getD1Data();
        $this->view->dd1 = $dd1;
    }

    /**
     * DashboardController index
     */
    public function d2Action() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_ExecutiveDashboard();

        // province
        $locations = new Model_Locations();
        $province = $locations->getAllProvinces();
        $this->view->province = $province;

        $to_date = $this->_request->getPost('last_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('last_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;

        // stock status
        $xmlstore2_1 = $graphs->overallStockStatus(3);
        $this->view->xml_stock_status_1 = $xmlstore2_1;

        // stock status
        $xmlstore2_2 = $graphs->coldStoreStockProvince(10);
        $this->view->xml_stock_status_2 = $xmlstore2_2;
        // stock status
        $xmlstore2_3 = $graphs->coldStoreStockProvince(1);
        $this->view->xml_stock_status_3 = $xmlstore2_3;

        // stock status
        $xmlstore2_3 = $graphs->coldStoreStockProvince(2);
        $this->view->xml_stock_status_4 = $xmlstore2_3;

        // stock status
        $xmlstore2_4 = $graphs->coldStoreStockProvince(3);
        $this->view->xml_stock_status_5 = $xmlstore2_4;

        // stock status
        $xmlstore2_5 = $graphs->coldStoreStockProvince(4);
        $this->view->xml_stock_status_6 = $xmlstore2_5;

        // stock status
        $xmlstore2_7 = $graphs->coldStoreStockProvince(5);
        $this->view->xml_stock_status_7 = $xmlstore2_7;
        // stock status
        $xmlstore2_8 = $graphs->coldStoreStockProvince(6);
        $this->view->xml_stock_status_8 = $xmlstore2_8;

        // stock status
        $xmlstore2_9 = $graphs->coldStoreStockProvince(7);
        $this->view->xml_stock_status_9 = $xmlstore2_9;
    }

    /**
     * DashboardController index
     */
    public function d3Action() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_ExecutiveDashboard();
        // province
        $locations = new Model_Locations();
        $province = $locations->getAllProvinces();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 'all';
        }



        // antigen
        $item_pack_sizes = new Model_ItemPackSizes();
        $item = $item_pack_sizes->itemsExpiryReport();
        $this->view->items = $item;


        if (isset($this->_request->product) && !empty($this->_request->product)) {
            $this->view->product = $product = $this->_request->product;
        } else {
            $product = 6;
        }


        $to_date = $this->_request->getPost('last_date');
        $re_month = explode('/', $to_date);

        $this->view->month = $re_month['1'];
        $this->view->year = $re_month['2'];



        if (isset($this->_request->cost_type) && !empty($this->_request->cost_type)) {
            $this->view->cost_type = $cost_type = $this->_request->cost_type;
        } else {
            $this->view->cost_type = $cost_type = 2;
        }

        if (isset($this->_request->usd) && !empty($this->_request->usd)) {
            $this->view->usd = $usd = $this->_request->usd;
        } else {
            $this->view->usd = $usd = 1;
        }
        if (isset($this->_request->currency) && !empty($this->_request->currency)) {
            $this->view->currency = $currency = $this->_request->currency;
        } else {
            $this->view->currency = $currency = 1;
        }

        if (isset($this->_request->d_c) && !empty($this->_request->d_c)) {
            $this->view->d_c = $d_c = $this->_request->d_c;
        } else {
            $this->view->d_c = $d_c = 1;
        }




        $graphs->form_values['province'] = $province;
        $graphs->form_values['product'] = $product;
        $graphs->form_values['year'] = $re_month['2'];
        $graphs->form_values['month'] = $re_month['1'];
        $graphs->form_values['cost_type'] = $cost_type;
        $graphs->form_values['usd'] = $usd;
        $graphs->form_values['currency'] = $currency;
        $graphs->form_values['dose_cost'] = $d_c;
        // unit price
        if ($cost_type == 1) {
            $graphs->form_values['un_price'] = $this->_request->un_price;
            if (isset($this->_request->funding_source) && !empty($this->_request->funding_source)) {
                $this->view->funding_source_sel = $funding_source = $this->_request->funding_source;
            } else {
                $this->view->funding_source_sel = $funding_source = 1;
            }
            $graphs->form_values['funding_source'] = $funding_source;
        }

        $funding_source1 = $graphs->getFundingSourceUnitPrice();
        $this->view->funding_source = $funding_source1;
        if ($cost_type == 3) {
            $graphs->form_values['manual_price'] = $this->_request->manual_price;
            $this->view->manual_price = $this->_request->manual_price;
        }

        // wastage cost
        $xmlstore3 = $graphs->wastagesCost();
        $this->view->xmlstore_cold_store_stock = $xmlstore3;

        // $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();
    }

    /**
     * DashboardController index
     */
    public function d4Action() {
        $this->_helper->layout->disableLayout();

        $graphs = new Model_ExecutiveDashboard();

        // province
        $locations = new Model_Locations();
        $province = $locations->getAllProvinces();
        $this->view->province = $province;

        // products
        $item = new Model_ItemPackSizes();
        $items = $item->getAllVacc();

        $this->view->products = $items;

        $to_date = $this->_request->getPost('to_date');
        if (empty($to_date)) {
            $to_date = $this->_request->getParam('to_date', date("d/m/Y"));
        }
        $graphs->form_values['to_date'] = $to_date;
        $this->view->to_date = $to_date;
        // Storage status
        if (isset($this->_request->product) && !empty($this->_request->product)) {
            $graphs->form_values['product'] = $product = $this->_request->product;
        } else {
            $graphs->form_values['product'] = $product = 'all';
        }

        $xmlstore4 = $graphs->coldStoreMos(3);
        $this->view->xmlstore_cold_store_mos = $xmlstore4;
    }

}
