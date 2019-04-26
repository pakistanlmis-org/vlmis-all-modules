<?php

/**
 * Reports_WastageController
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ahmad Saib
 * @version    2.5.1
 */

/**
 *  Controller for Reports Wastage Controller
 */
class Reports_WastageController extends App_Controller_Base {

    /**
     * Reports_WastageController index
     */
    public function indexAction() {
        // action body
    }

    /**
     * Wastage Report
     */
    public function wastageReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';

        $this->view->actionpage = 'wastage-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->getAllProvinces();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = 01;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = 12;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
        }
        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel =  $this->_request->wh_prov_sel;
        } else {
             $this->view->wh_prov_sel = 1;
        }
       
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district_id = $district_id = $this->_request->district;
        } else {
            $this->view->district_id = $district_id = 30;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 4;
        }
        if (isset($this->_request->report_type) && !empty($this->_request->report_type)) {
            $this->view->report_type = $report_type = $this->_request->report_type;
        } else {
            $this->view->report_type = $report_type = 1;
        }

        if ($this->_request->wh_type == 2) {
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        } else if ($this->_request->wh_type == 4) {
            $locations->form_values['pk_id'] = $this->_request->district;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $locations->form_values['pk_id'] = 2;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        }

        $item = $item_pack_sizes->coverageProductsReport();
        $this->view->item_id = $item;

        if (isset($this->_request->prod_sel) && !empty($this->_request->prod_sel)) {
            $this->view->sel_item = $sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_item = $sel_item = 'all';
        }
//        $item_name = $item_pack_sizes->itemName($sel_item);
//        $this->view->report_title = $item_name[0]['itemName'] . ' Coverage ';
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;


        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel_coverage.js');
    }
    
    
    /**
     * Wastage By Product
     */
    public function wastageByProductAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';

        $this->view->actionpage = 'wastage-by-product';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->getAllProvinces();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = 01;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = 12;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
        }
        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel =  $this->_request->wh_prov_sel;
        } else {
             $this->view->wh_prov_sel = 1;
        }
       
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district_id = $district_id = $this->_request->district;
        } else {
            $this->view->district_id = $district_id = 30;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 4;
        }
        if (isset($this->_request->report_type) && !empty($this->_request->report_type)) {
            $this->view->report_type = $report_type = $this->_request->report_type;
        } else {
            $this->view->report_type = $report_type = 1;
        }

        if ($this->_request->wh_type == 2) {
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        } else if ($this->_request->wh_type == 4) {
            $locations->form_values['pk_id'] = $this->_request->district;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $locations->form_values['pk_id'] = 2;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        }

        $item = $item_pack_sizes->coverageProductsReport();
        $this->view->item_id = $item;

        if (isset($this->_request->prod_sel) && !empty($this->_request->prod_sel)) {
            $this->view->sel_item = $sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_item = $sel_item = 'all';
        }
//        $item_name = $item_pack_sizes->itemName($sel_item);
//        $this->view->report_title = $item_name[0]['itemName'] . ' Coverage ';
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;


        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel_coverage.js');
    }

  /**
     * Wastage By Product
     */
    public function dropOutAction() {
      

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'VDROPOUTRATE';
        $this->view->actionpage = 'drop-out';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Categorization of Union Councils';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        //filters
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
        } else {
            $this->view->district1 = 30;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 4;
        }
        $locations = new Model_Locations();
        if ($this->_request->wh_type == 4) {
            $locations->form_values['pk_id'] = $this->_request->district;
            if ($this->_request->district == "all") {
                $this->view->loc_name = "District:" . ' ' . 'All';
            } else {
                $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
            }
        } elseif ($this->_request->wh_type == 5) {
            $locations->form_values['pk_id'] = $this->_request->tehsil;
            $this->view->loc_name = "Tehsil:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->loc_name = "District:" . ' ' . 'Bahawalpur';
        }

        if (!empty($this->_request->date_from)) {

            $this->view->from_date = $from_date = $this->_request->date_from;
            list($dd, $mm, $yy ) = explode("/", $from_date);
            $this->view->from_year_sel = $yy;
            $this->view->from_month_sel = $mm;
        } else {
            $from_date = date('d/m/Y', strtotime('-4 months'));
            $this->view->from_date = $from_date;
            list($dd, $mm, $yy ) = explode("/", $from_date);
            $this->view->from_year_sel = $yy;
            $this->view->from_month_sel = $mm;
        }
        if (!empty($this->_request->date_to)) {
            $this->view->to_date = $to_date = $this->_request->date_to;

            list($dd1, $mm1, $yy1 ) = explode("/", $to_date);
            $from_date = $yy1 . "-" . $mm1;
            $this->view->to_year_sel = $yy1;
            $this->view->to_month_sel = $mm1;
        } else {
            $to_date = date('d/m/Y', strtotime('-4 months'));
            $this->view->to_date = $to_date;
            list($dd1, $mm1, $yy1 ) = explode("/", $to_date);
            $from_date = $yy1 . "-" . $mm1;
            $this->view->to_year_sel = $yy1;
            $this->view->to_month_sel = $mm1;
        }

        $month = 12;
        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->prod_sel) && !empty($this->_request->prod_sel)) {
            $this->view->sel_item = $sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_item = $sel_item = 6;
        }
        $this->view->sel_item = $sel_item;
        $end_date1 = $year . '-' . ($month) . '-01';

        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-11 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;

        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    
        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel_coverage.js');
    }


}
