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
class WastagesController extends App_Controller_Base {

    /**
     * DashboardController index
     */
    public function indexAction() {
        $this->_helper->layout->setLayout("layout");
        $graphs = new Model_Graphs();

        // province
        $locations = new Model_Locations();
        $province = $locations->getAllProvinces();
        $this->view->province = $province;
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
        }



        // antigen
        $item_pack_sizes = new Model_ItemPackSizes();
        $item = $item_pack_sizes->itemsExpiryReport();
        $this->view->items = $item;
        if (isset($this->_request->item) && !empty($this->_request->item)) {
            $this->view->item_sel = $product = $this->_request->item;
        } else {
            $this->view->item_sel = $product = 6;
        }

        if (isset($this->_request->year) && !empty($this->_request->year)) {
            $this->view->year_sel = $year = $this->_request->year;
        } else {
            $this->view->year_sel = $year = date('Y') - 1;
        }

        if (isset($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $this->view->month_sel = $month = '';
        }



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
        $graphs->form_values['year'] = $year;
        $graphs->form_values['month'] = $month;
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
        $xmlstore1 = $graphs->wastagesCost();

        if ($product == 'all') {
            $this->view->xmlstore1 = $xmlstore1[0][0];

            $this->view->xmlstore2 = $xmlstore1[0][1];
            $this->view->conusmption = $xmlstore1[1];
            $graphs->form_values['allowed'] = 50;
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;

            $xmlstore_count = $graphs->facilities_count_all();
            $this->view->xml_sdpcount = $xmlstore_count;
        } else {
            $this->view->xmlstore1 = $xmlstore1[0];
            $this->view->xmlstore2 = $xmlstore1[1];
            $this->view->conusmption = $xmlstore1[2];
            $this->view->wastage = $xmlstore1[3];
            $this->view->unit_price = $xmlstore1[4];
            $this->view->permissible = $xmlstore1[5];
            $this->view->over_wastage = $xmlstore1[6];
            $this->view->a_perc = $xmlstore1[7];
            $this->view->n_perc = $xmlstore1[8];
            $this->view->allowed = $xmlstore1[9];
            $graphs->form_values['allowed'] = $xmlstore1[9];
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;
            $graphs->form_values['unit_price'] = $xmlstore1[4];
            //  $xmlstore_count = $graphs->facilities_count();
            $this->view->xml_sdpcount = $xmlstore1[10];

            $xmlstore_wastages_type = $graphs->wastagesByType();
            $this->view->xml_wastages_type = $xmlstore_wastages_type;
        }
        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();


        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
     
        $this->view->inlineScript()->appendFile($base_url . '/common/assets/plugins/jquery.pulsate.min.js');
    }

    /**
     * ajaxProductBatches
     */
    public function ajaxFundingSourceAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->item_id) && !empty($this->_request->item_id)) {
            $graphs = new Model_Graphs();
            $graphs->form_values['province'] = $this->_request->province;
            $graphs->form_values['product'] = $this->_request->item_id;
            $graphs->form_values['currency'] = $this->_request->currency;
            $graphs->form_values['usd'] = $this->_request->usd;
            $funding_source = $graphs->getFundingSourceUnitPrice();
            $this->view->data = $funding_source;
        }
    }

    /**
     * DashboardController index
     */
    public function ajaxGetConsumptionAction() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_Graphs();

        // province
        $locations = new Model_Locations();



        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
        }

        if (isset($this->_request->item) && !empty($this->_request->item)) {
            $this->view->item_sel = $product = $this->_request->item;
        } else {
            $this->view->item_sel = $product = 6;
        }

        if (isset($this->_request->year) && !empty($this->_request->year)) {
            $this->view->year_sel = $year = $this->_request->year;
        } else {
            $this->view->year_sel = $year = date('Y') - 1;
        }

        if (isset($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $this->view->month_sel = $month = '';
        }



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
        $graphs->form_values['year'] = $year;
        $graphs->form_values['month'] = $month;
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
        }
        $graphs->form_values['funding_source'] = $funding_source;
        $funding_source = $graphs->getFundingSourceUnitPrice();
        $this->view->funding_source = $funding_source;
        if ($cost_type == 3) {
            $graphs->form_values['manual_price'] = $this->_request->manual_price;
            $this->view->manual_price = $this->_request->manual_price;
        }


        // wastage cost
        $xmlstore1 = $graphs->wastagesCost();

        if ($product == 'all') {
            $this->view->xmlstore1 = $xmlstore1[0][0];

            $this->view->xmlstore2 = $xmlstore1[0][1];
            $this->view->conusmption = $xmlstore1[1];
            $graphs->form_values['allowed'] = 50;
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;

            $xmlstore_count = $graphs->facilities_count_all();
            $this->view->xml_sdpcount = $xmlstore_count;
        } else {
            $this->view->xmlstore1 = $xmlstore1[0];
            $this->view->xmlstore2 = $xmlstore1[1];
            $this->view->conusmption = $xmlstore1[2];
            $this->view->wastage = $xmlstore1[3];
            $this->view->unit_price = $xmlstore1[4];
            $this->view->permissible = $xmlstore1[5];
            $this->view->over_wastage = $xmlstore1[6];
            $this->view->a_perc = $xmlstore1[7];
            $this->view->n_perc = $xmlstore1[8];
            $this->view->allowed = $xmlstore1[9];
            $graphs->form_values['allowed'] = $xmlstore1[9];
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;
            $graphs->form_values['unit_price'] = $xmlstore1[4];
            //  $xmlstore_count = $graphs->facilities_count();
            $this->view->xml_sdpcount = $xmlstore1[10];

            $xmlstore_wastages_type = $graphs->wastagesByType();
            $this->view->xml_wastages_type = $xmlstore_wastages_type;
        }
        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();


        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();

        //  $base_url = Zend_Registry::get("baseurl");
        //  $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * DashboardController index
     */
    public function ajaxGetWastageAction() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_Graphs();

        // province
        $locations = new Model_Locations();

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
        }

        if (isset($this->_request->item) && !empty($this->_request->item)) {
            $this->view->item_sel = $product = $this->_request->item;
        } else {
            $this->view->item_sel = $product = 6;
        }

        if (isset($this->_request->year) && !empty($this->_request->year)) {
            $this->view->year_sel = $year = $this->_request->year;
        } else {
            $this->view->year_sel = $year = date('Y') - 1;
        }

        if (isset($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $this->view->month_sel = $month = '';
        }



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
        $graphs->form_values['year'] = $year;
        $graphs->form_values['month'] = $month;
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

        $funding_source = $graphs->getFundingSourceUnitPrice();
        $this->view->funding_source = $funding_source;
        if ($cost_type == 3) {
            $graphs->form_values['manual_price'] = $this->_request->manual_price;
            $this->view->manual_price = $this->_request->manual_price;
        }


        // wastage cost
        $xmlstore1 = $graphs->wastagesCost();

        if ($product == 'all') {
            $this->view->xmlstore1 = $xmlstore1[0][0];

            $this->view->xmlstore2 = $xmlstore1[0][1];
            $this->view->conusmption = $xmlstore1[1];
            $graphs->form_values['allowed'] = 50;
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;

            $xmlstore_count = $graphs->facilities_count_all();
            $this->view->xml_sdpcount = $xmlstore_count;
        } else {
            $this->view->xmlstore1 = $xmlstore1[0];
            $this->view->xmlstore2 = $xmlstore1[1];
            $this->view->conusmption = $xmlstore1[2];
            $this->view->wastage = $xmlstore1[3];
            $this->view->unit_price = $xmlstore1[4];
            $this->view->permissible = $xmlstore1[5];
            $this->view->over_wastage = $xmlstore1[6];
            $this->view->a_perc = $xmlstore1[7];
            $this->view->n_perc = $xmlstore1[8];
            $this->view->allowed = $xmlstore1[9];
            $graphs->form_values['allowed'] = $xmlstore1[9];
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;
            $graphs->form_values['unit_price'] = $xmlstore1[4];
            //  $xmlstore_count = $graphs->facilities_count();
            $this->view->xml_sdpcount = $xmlstore1[10];

            $xmlstore_wastages_type = $graphs->wastagesByType();
            $this->view->xml_wastages_type = $xmlstore_wastages_type;
        }
        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();


        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();

        //  $base_url = Zend_Registry::get("baseurl");
        //  $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * DashboardController index
     */
    public function ajaxGetExpiredAction() {
        $this->_helper->layout->disableLayout();
        $graphs = new Model_Graphs();

        // province
        $locations = new Model_Locations();

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
        }

        if (isset($this->_request->item) && !empty($this->_request->item)) {
            $this->view->item_sel = $product = $this->_request->item;
        } else {
            $this->view->item_sel = $product = 6;
        }

        if (isset($this->_request->year) && !empty($this->_request->year)) {
            $this->view->year_sel = $year = $this->_request->year;
        } else {
            $this->view->year_sel = $year = date('Y') - 1;
        }

        if (isset($this->_request->ending_month) && !empty($this->_request->ending_month)) {
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $this->view->month_sel = $month = '';
        }



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
        $graphs->form_values['year'] = $year;
        $graphs->form_values['month'] = $month;
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

        $funding_source = $graphs->getFundingSourceUnitPrice();
        $this->view->funding_source = $funding_source;
        if ($cost_type == 3) {
            $graphs->form_values['manual_price'] = $this->_request->manual_price;
            $this->view->manual_price = $this->_request->manual_price;
        }


        // wastage cost
        $xmlstore1 = $graphs->wastagesCost();

        if ($product == 'all') {
            $this->view->xmlstore1 = $xmlstore1[0][0];

            $this->view->xmlstore2 = $xmlstore1[0][1];
            $this->view->conusmption = $xmlstore1[1];
            $graphs->form_values['allowed'] = 50;
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;

            $xmlstore_count = $graphs->facilities_count_all();
            $this->view->xml_sdpcount = $xmlstore_count;
        } else {
            $this->view->xmlstore1 = $xmlstore1[0];
            $this->view->xmlstore2 = $xmlstore1[1];
            $this->view->conusmption = $xmlstore1[2];
            $this->view->wastage = $xmlstore1[3];
            $this->view->unit_price = $xmlstore1[4];
            $this->view->permissible = $xmlstore1[5];
            $this->view->over_wastage = $xmlstore1[6];
            $this->view->a_perc = $xmlstore1[7];
            $this->view->n_perc = $xmlstore1[8];
            $this->view->allowed = $xmlstore1[9];
            $graphs->form_values['allowed'] = $xmlstore1[9];
            $graphs->form_values['province'] = $province;
            $graphs->form_values['product'] = $product;
            $graphs->form_values['year'] = $year;
            $graphs->form_values['month'] = $month;
            $graphs->form_values['cost_type'] = $cost_type;
            $graphs->form_values['usd'] = $usd;
            $graphs->form_values['currency'] = $currency;
            $graphs->form_values['unit_price'] = $xmlstore1[4];
            //  $xmlstore_count = $graphs->facilities_count();
            $this->view->xml_sdpcount = $xmlstore1[10];

            $xmlstore_wastages_type = $graphs->wastagesByType();
            $this->view->xml_wastages_type = $xmlstore_wastages_type;
        }
        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();


        $this->view->user_role = $role_id;
        $this->view->warehousename = $this->_identity->getWarehouseName();
        $this->view->warehouse_id = $this->_identity->getWarehouseId();

        //  $base_url = Zend_Registry::get("baseurl");
        //  $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Reported UC
     */
    public function epiCentersAction() {
        $this->_helper->layout->setLayout("without-header-footer");
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $province = $param[0];
        $year = $param[1];
        $month = $param[2];
        $product = $param[3];
        $status = $param[4];
        $graphs = new Model_Graphs();
        $graphs->form_values['province'] = $province;
        $graphs->form_values['product'] = $product;
        $graphs->form_values['year'] = $year;
        $graphs->form_values['month'] = $month;
        $graphs->form_values['status'] = $status;
        $res = $graphs->getepiCentersWastages();
        // parameters
        $this->view->province = $province;
        $this->view->product = $product;
        $this->view->year = $year;
        $this->view->month = $month;
        $this->view->status = $status;
        // end
        $this->view->result = $res;
    }

    public function detailInformationAction() {
        $this->_helper->layout->setLayout("without-header-footer");
    }

}
