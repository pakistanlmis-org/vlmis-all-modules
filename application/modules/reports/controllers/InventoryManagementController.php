<?php

/**
 * Reports_InventoryManagementController
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Controller for Reports Inventory Management
 */
class Reports_InventoryManagementController extends App_Controller_Base {

    /**
     * Reports_InventoryManagementController index
     */
    public function indexAction() {
        // action body
    }

    // function to generate national summary report
    /**
     * National Report
     */
    public function nationalReportAction() {
        $this->_helper->layout->setLayout('reports');
        $item_pack_sizes = new Model_ItemPackSizes();
        $item = $item_pack_sizes->productsReport();

        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $locations = new Model_Locations();
            $lct = $locations->nationalReport();
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SNASUM';
            $this->view->report_title = 'National Report for';
            $this->view->actionpage = '';
            $this->view->parameters = 'T';
            $this->view->parameter_width = '40%';
            $this->view->location = $lct;
            $this->view->in_item = 1;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'N';
            $this->view->in_type = 'N';
            $this->view->stk = $stk;
            $this->view->actionpage = 'national-report';
        } else {
            $warehouse_data = new Model_HfDataMaster();
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);
            $warehouse_data->getMaxMonth($year);

            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();

            $locations = new Model_Locations();
            $lct = $locations->nationalReport();
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SNASUM';
            $this->view->report_title = 'National Report for';
            $this->view->actionpage = 'national-report';
            $this->view->parameters = 'T';
            $this->view->parameter_width = '40%';
            $this->view->location = $lct;
            $this->view->in_item = 1;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'N';
            $this->view->in_type = 'N';
            $this->view->stk = $stk;
        }
        $this->view->item_id = $item;
        $this->view->geo_level_id = 1;
    }

    /**
     * Provincial Vaccine Coverage
     */
    public function provincialVaccineCoverageAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'PVCOVERAGE';
        $this->view->actionpage = 'provincial-vaccine-coverage';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Annualized Vaccines Coverage Report';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();

        $item = $item_pack_sizes->VaccineProductsReport();
        $this->view->item_id = $item;
        //filters
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->province = $province = 1;
            $this->view->loc_name = "Province:" . ' ' . 'Punjab';
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
        } else {
            $this->view->district1 = 33;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }


        if (!empty($this->_request->year_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = 12;
        } else {
            $year = date("Y");

            $this->view->year_sel = $year;
            $this->view->month_sel = 12;
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
        $this->view->sel_item = $sel_item;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Provincial Vaccine Coverage
     */
    public function provincialVaccineCoverageDoseAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'PVCOVERAGEDOSE';
        $this->view->actionpage = 'provincial-vaccine-coverage-dose';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Annualized Vaccines Coverage Report';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();

        $item = $item_pack_sizes->VaccineProductsReport();
        $this->view->item_id = $item;
        //filters
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->province = $province = 1;
            $this->view->loc_name = "Province:" . ' ' . 'Punjab';
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
        } else {
            $this->view->district1 = 33;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }

        if (!empty($this->_request->year_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = 12;
        } else {
            $year = date("Y");

            $this->view->year_sel = $year;
            $this->view->month_sel = 12;
        }
        if ($this->_request->prod_dose != '' && $this->_request->prod_dose != 'all') {
            $this->view->dose_sel = $prod_dose = $this->_request->prod_dose;
        } else {
            $this->view->dose_sel = "all";
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
        $this->view->sel_item = $sel_item;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Provincial Report
     */
    public function provincialReportAction() {
        $this->_helper->layout->setLayout('reports');
        $item_pack_sizes = new Model_ItemPackSizes();
        $item = $item_pack_sizes->productsReport();

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $warehouse_data = new Model_HfDataMaster();
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SPROVINCEREPORT';
            $this->view->report_title = 'Province/Region Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'TS01I';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'P';
            $this->view->in_type = 'P';
            $this->view->in_rg1 = 'P';
            $this->view->in_type1 = 'P';
            $this->view->sel_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $this->view->location = $lct;
            $this->view->in_item = $this->_request->prod_sel;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->actionpage = 'provincial-report';
        } else {
            $warehouse_data = new Model_HfDataMaster();
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SPROVINCEREPORT';
            $this->view->report_title = 'Province/Region Report';
            $this->view->actionpage = 'provincial-report';
            $this->view->parameters = 'TS01I';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'P';
            $this->view->in_type = 'P';
            $this->view->in_rg1 = 'P';
            $this->view->in_type1 = 'P';
            $this->view->sel_item = 28;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $this->view->location = $lct;
            $this->view->in_item = 28;
            $this->view->in_stk = 1;
            $this->view->in_prov = 1;
            $this->view->in_dist = 1;
            $this->view->counter = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if (!empty($this->_request->prod_sel)) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {
            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }
        $this->view->item_id = $item;
        $this->view->geo_level_id = 2;
        //Province list
        //used for Percent Implementation of vLMIS
        $locations = new Model_Locations;
        $all_provinces = $locations->getAllProvinces();
        $this->view->all_provinces = $all_provinces;
    }

    /**
     * Divisional Report
     */
    public function divisionalReportAction() {
        $this->_helper->layout->setLayout('reports');
        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'Divisional Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'PIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type_1 = 'V';
            $this->view->in_type = 'P';
            $this->view->sel_item = 1;
            $locations = new Model_Locations();
            $this->view->sel_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $lct = $locations->devisionalReport();
            $locations->province_id = $this->_request->prov_sel;
            $lct2 = $locations->devisionalLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 1;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->actionpage = 'divisional-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();

            $this->view->item_id = $item;
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'Divisional Report';
            $this->view->actionpage = 'divisional-report';
            $this->view->parameters = 'PIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'P';
            $this->view->in_type_1 = 'V';
            $this->view->in_type = 'P';
            $this->view->sel_item = 1;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->province_id = 1;
            $lct2 = $locations->devisionalLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 1;
            $this->view->in_stk = 1;
            $this->view->in_prov = 0;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {
            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }
        $this->view->geo_level_id = 4;
    }

    /**
     * District Report
     */
    public function districtReportAction() {
        $this->_helper->layout->setLayout('reports');
        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'District Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '95%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->in_rg1 = 'R';
            $this->view->in_type1 = 'D';
            $locations = new Model_Locations();
            $this->view->sel_item = $this->_request->prod_sel;
            $this->view->in_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $lct2 = $locations->districtLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = $this->_request->prov_sel;

            $this->view->in_stk = 1;
            $this->view->in_prov = $this->_request->prov_sel;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
            $this->view->actionpage = 'district-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();

            $this->view->item_id = $item;
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'SDISTRICTREPORT';
            $this->view->report_title = 'District Report';
            $this->view->actionpage = 'district-report';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '95%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->in_rg1 = 'R';
            $this->view->in_type1 = 'D';
            $this->view->sel_item = 28;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = '1';
            $lct2 = $locations->districtLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $this->view->prov_sel = 1;
            $this->view->in_item = 28;
            $this->view->in_stk = 1;
            $this->view->in_prov = 1;
            $this->view->in_dist = 0;
            $this->view->counter = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {
            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }
        $this->view->geo_level_id = 4;
    }

    /**
     * Tehsil Report
     */
    public function tehsilReportAction() {
        $this->_helper->layout->setLayout('reports');
        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'TEHSILREPORT';
            $this->view->report_title = 'Tehsil Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->in_type_1 = 'H';
            $this->view->in_type1 = 'T';
            $this->view->sel_item = 1;
            $locations = new Model_Locations();
            $this->view->sel_item = $this->_request->prod_sel;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $lct2 = $locations->tehsilLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->sel_item = $this->_request->prod_sel;
            $this->view->sel_dist = $this->_request->dist_id;
            $this->view->dist_sel = $this->_request->dist_id;
            if ($this->_request->dist_id) {
                $this->view->in_dist = $this->_request->dist_id;
                $this->view->dist_sel = $this->_request->dist_id;
            } else {
                $this->view->in_dist = $this->_request->prov_sel;
                $this->view->dist_sel = 0;
            }
            $this->view->in_item = $this->_request->prod_sel;
            $this->view->in_stk = 1;
            $this->view->in_prov = $this->_request->prov_sel;
            $this->view->counter = 1;
            $this->view->actionpage = 'tehsil-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $year = date("Y", strtotime("-1 year"));
            $month = date("m", strtotime("-2 month"));

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'TEHSILREPORT';
            $this->view->report_title = 'Tehsil Report';
            $this->view->actionpage = 'tehsil-report';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '100%';
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'R';
            $this->view->in_type = 'D';
            $this->view->in_type_1 = 'H';
            $this->view->in_type1 = 'T';
            $this->view->sel_item = 28;
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->nationReport();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = '1';
            $locations->form_values['district_id'] = '';
            $lct2 = $locations->tehsilLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = '1';
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 28;
            $this->view->in_stk = 1;
            $this->view->in_prov = 1;
            $this->view->prov_sel = 1;
            $this->view->in_dist = 0;
            $this->view->dist_sel = 0;
            $this->view->counter = 1;
        }

        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {

            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {

            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }
        $this->view->geo_level_id = 5;
    }

    /**
     * UC Report
     */
    public function ucReportAction() {
        $this->_helper->layout->setLayout('reports');

        if (isset($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();
            $locations = new Model_Locations();

            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->getStakholder();
            $lct = $locations->devisionalReport();
            $this->view->item_id = $item;
            $year = $this->_request->year_sel;
            $month = $this->_request->month_sel;
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'UCREPORT';
            $this->view->report_title = 'Union Council Report';
            $this->view->actionpage = '';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '90%';
            $sel_item = $this->_request->prod_sel;
            $this->view->in_col = 'CABM';
            $this->view->in_rg = 'U';
            $this->view->in_type = 'D';
            $this->view->in_type1 = 'UD';
            $this->view->sel_item = $this->_request->stkid;
            $this->view->in_type_1 = 'U';
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->sel_item = $this->_request->prod_sel;
            $this->view->stk = $stk;
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $locations->form_values['tehsil_id'] = $this->_request->teh_id;
            $locations->form_values['uc_id'] = $this->_request->uc_id;
            $lct2 = $locations->ucLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;

            $locations->form_values['geo_level_id'] = 5;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $tehsil = $locations->getLocationsByLevelByDistrict();
            $this->view->combo_teh = $tehsil;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['parent_id'] = $this->_request->teh_id;
            $uc = $locations->getLocationsByLevelByTehsil();
            $this->view->combo_uc = $uc;
            $this->view->in_item = $this->_request->prod_sel;
            $this->view->in_stk = 1;
            $this->view->in_prov = $this->_request->prov_sel;
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->counter = 1;
            $this->view->sel_dist = $this->_request->dist_id;
            $this->view->sel_teh = $this->_request->teh_id;
            $this->view->sel_uc = $this->_request->uc_id;
            $item_pack_sizes->form_values['pk_id'] = $sel_item;
            $item_name = $item_pack_sizes->getProductName();
            $this->view->item_name = $item_name;
            $locations->form_values['pk_id'] = $this->_request->dist_id;
            $location_name = $locations->getLocationName();
            $this->view->district_name = $location_name;
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $location_name = $locations->getLocationName();
            $this->view->province_name = $location_name;
            $locations->form_values['pk_id'] = $this->_request->teh_id;
            $location_name = $locations->getLocationName();
            $this->view->tehsil_name = $location_name;
            $this->view->actionpage = 'uc-report';
        } else {
            $item_pack_sizes = new Model_ItemPackSizes();
            $item = $item_pack_sizes->productsReport();

            $this->view->item_id = $item;
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
            $this->view->report_id = 'UCREPORT';
            $this->view->report_title = 'Union Council Report';
            $this->view->actionpage = 'uc-report';
            $this->view->parameters = 'SPIT';
            $this->view->parameter_width = '90%';
            $this->view->in_col = 'SPIT';
            $this->view->in_rg = 'U';
            $this->view->in_type = 'D';
            $this->view->in_type1 = 'UD';
            $this->view->sel_item = 28;
            $this->view->in_type_1 = 'U';
            $stakeholder = new Model_Stakeholders();
            $stk = $stakeholder->getStakholder();
            $this->view->stk = $stk;
            $locations = new Model_Locations();
            $lct = $locations->devisionalReport();
            $locations->form_values['province_id'] = "";
            $locations->form_values['district_id'] = "";
            $locations->form_values['tehsil_id'] = "";
            $locations->form_values['uc_id'] = "";
            $lct2 = $locations->ucLocations();
            $this->view->loc = $lct2;
            $this->view->location = $lct;
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = '1';
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->prov_sel = $this->_request->prov_sel;
            $this->view->in_item = 28;
            $this->view->in_stk = 1;
            $this->view->in_prov = 1;
            $this->view->in_dist = 33;
            $this->view->sel_dist = 33;
            $this->view->counter = 1;
            $item_pack_sizes->form_values['pk_id'] = 28;
            $item_name = $item_pack_sizes->getProductName();
            $this->view->item_name = $item_name;
            $locations->form_values['pk_id'] = 0;
            $location_name = $locations->getLocationName();
            $this->view->location_name = $location_name;
            $locations->form_values['pk_id'] = 1;
            $location_name = $locations->getLocationName();
            $this->view->province_name = $location_name;
            if (empty($this->_request->dist_id)) {
                $this->view->district_name = "All";
            }
            if (empty($this->_request->teh_id)) {
                $this->view->tehsil_name = "All";
            }
            if (empty($this->_request->uc_id)) {
                $this->view->uc_name = "All";
            }
        }
        $this->view->geo_level_id = 6;
    }

    /**
     * Province 2 District
     */
    function prov2distAction() {
        $this->_helper->layout->disableLayout();
        $type = $this->_request->combo;
        $locations = new Model_Locations();
        if ($type == '4' && isset($this->_request->dist_sel) && !empty($this->_request->dist_sel)) {
            $dist_id = $this->_request->dist_sel;
            $locations->form_values['geo_level_id'] = 5;
            $locations->form_values['district_id'] = $dist_id;
            $result = $locations->getLocationsByLevelByDistrict();
            $this->view->result = $result;
        }

        if ($type == '2' && isset($this->_request->prov_sel) && !empty($this->_request->prov_sel)) {
            $prov_id = $this->_request->prov_sel;
            $locations->form_values['geo_level_id'] = 4;
            $locations->form_values['province_id'] = $prov_id;
            $result = $locations->getLocationsByLevelByProvince();
            $this->view->result = $result;
//            echo 'In prov2dist action';
        }

        if ($type == '5' && !empty($this->_request->teh_sel)) {
            $teh_sel = $this->_request->teh_sel;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['parent_id'] = $teh_sel;
            $result = $locations->getLocationsByLevelByTehsil();
            $this->view->result = $result;
        }

        if ($type == '5' && !empty($this->_request->dist_sel)) {
            $dist_sel = $this->_request->dist_sel;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['district_id'] = $dist_sel;
            $result = $locations->getLocationsByLevelByDistrict();
            $this->view->result = $result;
        }
        if ($type == '10' && !empty($this->_request->dist_sel)) {
            $this->view->type = 10;
        }
        if ($type == '9') {
            $this->view->type = 9;
        }
        $report_id = $this->_request->report_id;
        if ($report_id == 'PW' && $type == '2' && isset($this->_request->prov_sel) && !empty($this->_request->prov_sel)) {
            $prov_id = $this->_request->prov_sel;
            $locations->form_values['parent_id'] = $prov_id;
            $result = $locations->getDivisionsByProvince();
            $this->view->result = $result;
        }
        if ($report_id == 'PW' && $type == '3' && isset($this->_request->div_sel) && !empty($this->_request->div_sel)) {
            $div_id = $this->_request->div_sel;
            $locations->form_values['div_id'] = $div_id;
            $result = $locations->getDistrictsByDivision();
            $this->view->result = $result;
        }
    }

    /**
     * Provincial Yearly Report
     */
    public function provincialYearlyReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'PROVINCIALWAREHOUSE';
        $this->view->report_title = 'Provincial Yearly Report';
        $this->view->actionpage = 'provincial-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $div_sel = "";
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $locations = new Model_Locations();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['parent_id'] = $prov_sel;
            $division = $locations->getDivisionsByProvince();
            $this->view->division = $division;
        } else {
            $prov_sel = 1;
            $locations->form_values['parent_id'] = $prov_sel;
            $division = $locations->getDivisionsByProvince();
            $this->view->division = $division;
        }
        if (!empty($this->_request->division)) {
            $div_sel = $this->_request->division;
            $locations->form_values['div_id'] = $div_sel;
            $district = $locations->getDistrictsByDivision();
            $this->view->district = $district;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        $this->view->prov_sel = $prov_sel;
        $this->view->div_sel = $div_sel;


        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = "Province:" . ' ' . $locations->getLocationName();
        } else {

            $this->view->location_name = "Province:" . ' ' . "Punjab";
        }

        if ($this->_request->division) {
            $this->view->in_div = $this->_request->division;
            $this->view->sel_div = $this->_request->division;
            $locations->form_values['pk_id'] = $this->_request->division;
            $this->view->location_name = "Division:" . ' ' . $locations->getLocationName();
        }

        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;
            $locations->form_values['pk_id'] = $this->_request->dist_id;
            $this->view->district_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->in_dist = '';
            $this->view->sel_dist = '';
            $this->view->district_name = "";
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            //If province, division are district filters are selected
            if (!empty($this->_request->prov_sel) && !empty($this->_request->division) && !empty($this->_request->dist_id)) {
                $str_indicator = "\'Stock on Hand(District-Taluka-Ucs)\'";
            } //If province and division filters are selected
            else if (!empty($this->_request->prov_sel) && !empty($this->_request->division) && empty($this->_request->dist_id)) {
                $str_indicator = "\'Stock on Hand(Division+District+Taluka+Ucs)\'";
            } //If only province filter is selected
            else if (!empty($this->_request->prov_sel) && empty($this->_request->division) && empty($this->_request->dist_id)) {
                $str_indicator = "\'Stock on Hand(Province+District+Taluka+Ucs)\'";
            }
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';

        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * store Issuance Report
     */
    public function storeIssuanceReportAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'STOREISSUANCEREPORT';
        $this->view->actionpage = 'store-issuance-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Store Issuance Report';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();

        $item = $item_pack_sizes->VaccineProductsReport();
        $this->view->item_id = $item;
        //filters
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $province = $this->_request->province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
        } else {
            $this->view->district1 = 33;
        }
        if (isset($this->_request->division) && !empty($this->_request->division)) {
            $this->view->division = $this->_request->division;
        } else {
            $this->view->division = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }

        if (!empty($this->_request->year_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = 12;
        } else {
            $year = date("Y");
            $this->view->year_sel = $year;
            $this->view->month_sel = 12;
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

        $end_date1 = $year . '-' . ($month) . '-01';

        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-11 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $sel_item;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Store Issuance Report Old
     */
    public function storeIssuanceReportOldAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'STOREISSUANCEREPORT';
        $this->view->report_title = 'Store Issuance Report';
        $this->view->actionpage = 'store-issuance-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $location = new Model_Locations();

        if ($this->_request->prov_sel) {
            $province = $this->_request->prov_sel;
        }

        if (!empty($this->_request->year_sel)) {
            $year = $this->_request->year_sel;
        } else {
            $year = 2015;
        }
        $this->view->year_sel = $year;

        if (!empty($this->_request->prod_sel)) {
            $this->view->sel_prod = $this->_request->prod_sel;
            $this->view->sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_prod = 3;
        }
        if (!empty($this->_request->prov_sel)) {
            $this->view->prov_sel = $this->_request->prov_sel;
        }

        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {
            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }

        if (isset($this->_request->dist_id) && !empty($this->_request->dist_id)) {
            $locations = new Model_Locations();
            $locations->form_values['geo_level_id'] = 4;
            $locations->form_values['province_id'] = $this->_request->prov_sel;

            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->sel_dist = $this->_request->dist_id;

            $location->form_values['province_id'] = $province;
            $location->form_values['district_id'] = $this->_request->dist_id;
            $location_name = $location->ucLocations();
            $provinces = $location->nationalReport();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $this->view->location_id = $location_name;
            $this->view->location = $provinces;
        } else {
            $locations = new Model_Locations();
            $location_name = $location->nationalReport();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $this->view->location_id = $location_name;
            $this->view->location = $location_name;
            $this->view->district = array();
        }

        $end_date1 = $year . '-12-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
    }

    /**
     * Districts Yearly Report
     */
    public function districtsYearlyReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'DISTRICTWAREHOUSE';
        $this->view->report_title = 'District Yearly Report';
        $this->view->actionpage = 'districts-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $location = new Model_Locations();

        if ($this->_request->prov_sel) {
            $province = $this->_request->prov_sel;
        } else {
            $province = 1;
        }

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        if (!empty($this->_request->prod_sel)) {
            $this->view->sel_prod = $this->_request->prod_sel;
        } else {
            $this->view->sel_prod = 28;
        }
        if (!empty($this->_request->prov_sel)) {
            $this->view->prov_sel = $this->_request->prov_sel;
        } else {
            $this->view->prov_sel = 1;
        }

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        if ($this->_request->prod_sel) {

            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->item_name = $item_pack_sizes->getProductName();
        } else {

            $item_pack_sizes->form_values['pk_id'] = '28';
            $this->view->item_name = $item_pack_sizes->getProductName();
        }
        if (isset($this->_request->dist_id) && !empty($this->_request->dist_id)) {
            $locations = new Model_Locations();
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;

            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->sel_dist = $this->_request->dist_id;

            $location->form_values['province_id'] = $province;
            $location->form_values['district_id'] = $this->_request->dist_id;
            $location_name = $location->tehsilLocationsDistrict();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $this->view->location_id = $location_name;

            $locations->form_values['pk_id'] = $this->_request->dist_id;
            $this->view->district_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $locations = new Model_Locations();
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = '1';
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
            $this->view->sel_dist = "";

            $location->form_values['province_id'] = $province;
            $location_name = $location->getLocations();
            $item = $item_pack_sizes->productsReport();
            $this->view->item_id = $item;
            $this->view->location_id = $location_name;
            $this->view->district_name = "";
        }

        $locations = new Model_Locations();
        if ($this->_request->prov_sel) {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = "Province:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->location_name = "Province:" . ' ' . "Punjab";
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Vaccination\'";
        } else if ($sel_indicator == 10) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand(District+Talukas+UCs)\'";
        } else if ($sel_indicator == 5) {
            $str_indicator = "\'Stock on Hand(District)\'";
        } else if ($sel_indicator == 6) {
            $str_indicator = "\'Stock on Hand(District+Talukas)\'";
        } else if ($sel_indicator == 7) {
            $str_indicator = "\'Stock on Hand(Talukas+UCs)\'";
        } else if ($sel_indicator == 8) {
            $str_indicator = "\'Stock on Hand(UCs)\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        } else if ($sel_indicator == 9) {
            $str_indicator = "\'Wastages\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
        $locations->form_values['province_id'] = '1';
        $locations->form_values['district_id'] = '';
        $lct2 = $locations->tehsilLocations();
        $this->view->loc = $lct2;
    }

    /**
     * Districts Yearly Report
     */
    public function wastagesReportAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'WASTAGESREPORTING';
        $this->view->report_title = 'Reporting and Wastages Rate';
        $this->view->actionpage = 'wastages-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $location = new Model_Locations();

        $item = $item_pack_sizes->VaccineProductsReport();
        if (!empty($this->_request->level)) {
            $this->view->sel_level = $level_sel = $this->_request->level;
        } else {
            $this->view->sel_level = $level_sel = '2';
        }
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $province = $this->_request->province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $district = $this->_request->district;
        } else {
            $this->view->district = $district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }

        if ($this->_request->wh_type == 2) {
            $location->form_values['pk_id'] = $this->_request->province;
            $this->view->loc_name = "Province:" . ' ' . $location->getLocationName();
        } elseif ($this->_request->wh_type == 4) {
            $location->form_values['pk_id'] = $this->_request->district;
            $this->view->loc_name = "District:" . ' ' . $location->getLocationName();
        } else {
            $this->view->loc_name = "Province:" . ' ' . 'Punjab';
        }

        $location->form_values['level_id'] = $wh_type;
        $location->form_values['province_id'] = $province;
        $location->form_values['district_id'] = $district;
        $location_name = $location->getLocationWastages();
        $this->view->item_id = $item;
        $this->view->location_id = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->prod_sel)) {
            $this->view->sel_prod = $this->_request->prod_sel;
            $this->view->sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_prod = 6;
            $this->view->sel_item = 6;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
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
    }

    /**
     * Shipment Report
     */
    public function shipmentReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SHIPMENTREPORT';
        $this->view->report_title = 'Stock Shipment Report';
        $this->view->actionpage = 'shipment-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->division) && !empty($this->_request->division)) {
            $this->view->division = $this->_request->division;
        } else {
            $this->view->division = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Central Provincial Warehouse
     */
    public function centralProvincialWarehouseAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'CENTRALWAREHOUSE';
        $this->view->report_title = 'Federal/Provincial Store Report';
        $this->view->actionpage = 'central-provincial-warehouse';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 159;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Issue\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Stock Availability Report
     */
    public function stockAvailabilityReportAction() {
        $this->_helper->layout->setLayout('reports');
        $warehouses_data = new Model_HfDataMaster();
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();
        $stakeholders = new Model_Stakeholders();
        $request_data = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $this->view->report_id = 'SNASUMSTOCKLOC';
        $this->view->report_title = 'Stock Availability Report';
        $this->view->parameters = 'TS01P01I';
        $this->view->cwh_name = '';
        $this->view->num_item2 = '';
        $this->view->total = 0;
        $this->view->cwhtotal = 0;
        $this->view->ppiutotal = 0;
        $this->view->disttotal = 0;
        $this->view->old1 = '';
        $this->view->actionpage = 'stock-availability-report';
        if (!empty($this->_request->prod_sel)) {
            $this->view->sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_item = 0;
        }
        $this->view->sel_stk = 1;
        $this->view->prov_sel = $this->_request->prov_sel;

        $lct = $locations->devisionalReport();
        $this->view->location = $lct;

        $warehouses_data->temp = $request_data;
        $rs_items = $warehouses_data->generateStockAvailabilityReport();
        $this->view->rs_items = $rs_items;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->prod_sel)) {
            $item_pack_sizes->form_values['pk_id'] = $this->_request->prod_sel;
            $this->view->product_name = $item_pack_sizes->getProductName();
        }

        if ($this->_request->prov_sel == 'all') {
            $this->view->location_name = "All";
        } else if ($this->_request->prov_sel == '') {
            $this->view->location_name = "Punjab";
        } else {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        }

        if ($this->_request->stk_id == 'all' || $this->_request->stk_id == "") {
            $this->view->stakeholder_name = "All";
        } elseif (!empty($this->_request->stk_id)) {
            $stakeholders->form_values['pk_id'] = $this->_request->stk_id;
            $this->view->stakeholder_name = $stakeholders->getStakeholderName();
        }
    }

    /**
     * Reported Provinces
     */
    public function reportedProvincesAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Consumption Data Reporting Status (Province-wise)';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-2 month", strtotime($end_date1)));

        if ($this->_request->prov_sel == 'all') {
            $this->view->location_name = "All";
        } else if ($this->_request->prov_sel == '') {
            $this->view->location_name = "Punjab";
        } else {
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
        }

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Stock On Hand
     */
    public function stockOnHandAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SOH';
        $this->view->report_title = 'Provincial/Regional Stock on Hand';
        $this->view->actionpage = 'stock-on-hand';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;

        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Stock Analysis District Report
     */
    public function stockAnalysisDistrictReportAction() {
        $form = new Form_Reports_MonthYear();
        $this->_helper->layout->setLayout('reports');
        $this->view->report_title = 'Monthly Stock Analysis Report';

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->form_values = $form->getValues();
            $this->view->xml_store = $warehouse_data->getStockAnalysisDistrictWiseReport();
        }

        $this->view->form = $form;
    }

    /**
     * Month Of Stock
     */
    public function monthOfStockAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'MOS';
        $this->view->report_title = 'Provincial/Regional Months of Stock';
        $this->view->actionpage = 'month-of-stock';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;

        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Consumption
     */
    public function consumptionAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'CONSUMPTION';
        $this->view->report_title = 'Provincial/Regional Consumption';
        $this->view->actionpage = 'consumption';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;

        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * AMC
     */
    public function amcAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'AMC';
        $this->view->report_title = 'Provincial/Regional Average Monthly Consumption';
        $this->view->actionpage = 'amc';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $items_name = $item_pack_sizes->stockOnHandItems();
        $this->view->item_name = $items_name;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Reported Districts By UC
     */
    public function reportedDistrictsByUcAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By UC)';
        $this->view->actionpage = 'reported-districts-by-uc';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 5;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 162;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }

        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }


        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-2 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Reported Districts By UC
     */
    public function reportedDistrictsAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By UC)';
        $this->view->actionpage = 'reported-districts';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 5;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 162;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }

        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }


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
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Reported Districts By User
     */
    public function reportedDistrictsByUserAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By User)';
        $this->view->actionpage = 'reported-districts-by-user';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $province;
        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;
        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Reported District By Facility
     */
    public function reportedDistrictByFacilityAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Consumption Data Reporting Status (By Facility)';
        $this->view->actionpage = 'reported-district-by-facility';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 2;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-5 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Non Reported Districts By Facility
     */
    public function nonReportedDistrictsByFacilityAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->actionpage = 'non-reported-districts-by-facility';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';

        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
        } else {
            $this->view->district1 = 33;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;
        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }


        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }


        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $locations = new Model_Locations();
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = '1';
        $district = $locations->getLocationsByLevelByProvince();
        $this->view->district = $district;
    }

    /**
     * Non Reported Districts By UC
     */
    public function nonReportedDistrictsByUcAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'Non-reported EPI Centers Report for';
        $this->view->actionpage = 'non-reported-districts-by-uc';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';

        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
        } else {
            $this->view->district1 = 33;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        $location->form_values['province_id'] = $province;
        $location_name = $location->districtLocations();
        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $locations = new Model_Locations();
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->location_name = $locations->getLocationName();
        } else {
            $this->view->location_name = "Punjab";
        }


        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = '1';
        $district = $locations->getLocationsByLevelByProvince();
        $this->view->district = $district;
    }

    /**
     * Province To Warehouse
     */
    public function provinceToWarehouseAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $locations = new Model_Locations();
        $locations->form_values = $form_values;
        $result = $locations->getProvinceToWarehouse();
        $this->view->result = $result;
    }

    /**
     * ajaxCombos
     */
    public function ajaxCombosAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $this->view->form_values = $form_values;
    }

    /**
     * Transaction Detail
     */
    public function transactionDetailAction() {
        $this->_helper->layout->setLayout('print-report');
        $param = explode('|', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    /**
     * Reported UC
     */
    public function reportedUcAction() {
        $this->_helper->layout->setLayout('print-report');
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    /**
     * Reported UC User
     */
    public function reportedUcUserAction() {
        $this->_helper->layout->setLayout('print-report');
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    /**
     * Reported Warehouse List
     */
    public function reportedWarehouseListAction() {
        $this->_helper->layout->setLayout('print-report');
        $param = explode('-', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

    /**
     * Popup Data Entry
     */
    public function popupDataEntryAction() {
        $this->_helper->layout->setLayout("print-report");
        $param = explode('|', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
        $this->view->is_new_report = 2;
        $this->view->province_id = 1;
    }

    /**
     * Popup Data Entry Facility
     */
    public function popupDataEntryFacilityAction() {
        $this->_helper->layout->setLayout("print-report");
        $param = $this->_request->getParam('param', '');
        $this->view->is_new_report = 2;
        $this->view->param = $param;
    }

    /**
     * Data Entry Status
     */
    public function dataEntryStatusAction() {
        $this->view->report_id = 'DataEntryStatus';
        $this->view->parameters = 'TS01I';

        $search_form = new Form_ReportsSearch();
        $stock_master = new Model_StockMaster();
        $role_id = $this->_identity->getRoleId();

        if ($this->_request->wh_type) {
            $stock_master->form_values['wh_type'] = $this->_request->wh_type;
        } else {
            $stock_master->form_values['role_id'] = $role_id;
        }
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
            $stock_master->form_values['province'] = $this->_request->province;
        } else {
            $this->view->province = $this->_identity->getProvinceId();
            $stock_master->form_values['province'] = $this->_identity->getProvinceId();
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
            $stock_master->form_values['district'] = $this->_request->district;
        } else {
            $district_id = $this->_identity->getDistrictId($this->_identity->getIdentity());
            $this->view->district = $district_id;
            $stock_master->form_values['district'] = $district_id;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
            $stock_master->form_values['tehsil'] = $this->_request->tehsil;
        } else {
            $tehsil_id = $this->_identity->getTehsilId($this->_userid);
            if (empty($tehsil_id)) {
                $tehsil_id = '1';
            }
            $this->view->tehsil = $tehsil_id;
            $stock_master->form_values['tehsil'] = $tehsil_id;
        }

        $this->view->data = $stock_master->getFederalWarehouses();
        $this->view->data1 = $stock_master->getProvincialWarehouses();
        $this->view->data2 = $stock_master->getDistrictWarehouses();
        $this->view->data3 = $stock_master->getTehsilWarehouses();

        $this->view->role_id = $this->_identity->getRoleId();

        $this->view->main_heading = "Data Entry Status";
        $this->view->report_title = "Data Entry Status";

        $this->view->search_form = $search_form;

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            if ($role_id == 3 || $role_id == 17) {
                $wh_type_role = 1;
            } else if ($role_id == 4) {
                $wh_type_role = 2;
            } else if ($role_id == 6) {
                $wh_type_role = 4;
            } else if ($role_id == 7) {
                $wh_type_role = 5;
            }

            $this->view->wh_type = $wh_type = $wh_type_role;
        }
        $this->view->actionpage = 'data-entry-status';
    }

    /**
     * Batch Tracking
     */
    public function batchTrackingAction() {
        $form = new Form_BatchTracking();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $product_id = $this->_request->getPost('product');
            $from_wh = $this->_request->getPost('from_wh');
            $to_wh = $this->_request->getPost('to_wh');

            $form->product->setValue($product_id);
            $stock_master = new Model_StockMaster();
            $result1 = $stock_master->getWarehousesByProduct($product_id);
            if ($result1 && count($result1) > 0) {
                foreach ($result1 as $whs) {
                    $result1set[$whs['pkId']] = $whs['warehouseName'];
                }
            }
            $form->from_wh->setMultiOptions($result1set);
            $form->from_wh->setValue($from_wh);
            $result2 = $stock_master->getToWarehousesByProduct($from_wh, $product_id);
            if ($result2 && count($result1) > 0) {
                foreach ($result2 as $whs) {
                    $result2set[$whs['pkId']] = $whs['warehouseName'];
                }
            }
            $form->to_wh->setMultiOptions($result2set);
            $form->to_wh->setValue($to_wh);

            $stock_master->form_values = $form->getValues();
            $result3 = $stock_master->getAllWarehouseBatches();

            $this->view->result = $result3;
        }

        $this->view->form = $form;
    }

    /**
     * ajaxGetWarehousesByProduct
     */
    public function ajaxGetWarehousesByProductAction() {
        $this->_helper->layout->disableLayout();
        $product_id = $this->_request->getParam('product');

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getWarehousesByProduct($product_id);

        $this->view->result = $result;
    }

    /**
     * ajaxGetToWarehousesByProduct
     */
    public function ajaxGetToWarehousesByProductAction() {
        $this->_helper->layout->disableLayout();
        $product_id = $this->_request->getParam('product');
        $from_wh = $this->_request->getParam('from_wh');

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getToWarehousesByProduct($from_wh, $product_id);

        $this->view->result = $result;
    }

    /**
     * ajaxGetBatchData
     */
    public function ajaxGetBatchDataAction() {
        $this->_helper->layout->disableLayout();

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $issue_result = $stock_master->getBatchData();
        $batch = $this->_em->getRepository("StockBatch")->find($this->_request->getParam('batch_id'));
        $stock_master->form_values['batch_number'] = $batch->getNumber();
        $receive_result = $stock_master->getReceiveBatchData();

        $this->view->receive_result = $receive_result;
        $this->view->issue_result = $issue_result;
    }

    /**
     * ajaxLinkReceiveWithIssue
     */
    public function ajaxLinkReceiveWithIssueAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $stock_master->linkReceiveWithIssue();
    }

    /**
     * Batch Shelf Life Graph
     */
    public function batchShelfLifeGraphAction() {
        $form = new Form_BatchShelfLife();

        $this->view->form = $form;
        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Vaccine Shelf Life';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;


        if ($this->_request->isPost()) {
            $this->view->combos = $this->_request->getPost();
            $form_values = $this->_request->getPost();
            $this->view->office = $form_values['office'];
            $this->view->warehouse_id = $form_values['warehouse'];
            $this->view->warehouse_hidden = $form_values['warehouse'];

            $this->view->combo1_hidden = $form_values['combo1'];
            $this->view->combo2_hidden = $form_values['combo2'];
            $this->view->time_period = $form_values['time_period'];
            $form->time_period->setValue($form_values['time_period']);
        } else {
            $this->view->office = 1;
            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = 159;
            $this->view->time_period = date("d/m/Y");
            $form->time_period->setValue(date("d/m/Y"));
        }
        $this->view->role_id = $this->_identity->getRoleId();

        $base_url = Zend_Registry::get('baseurl');
        $this->view->menu_type = 1;
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * Batch Shelf Life
     */
    public function batchShelfLifeAction() {
        $form = new Form_BatchShelfLife();

        $this->view->form = $form;
        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Vaccine Shelf Life';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;


        if ($this->_request->isPost()) {
            $this->view->combos = $this->_request->getPost();
            $form_values = $this->_request->getPost();

            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = $this->_identity->getWarehouseId();

            $this->view->time_period = $form_values['time_period'];
            $form->time_period->setValue($form_values['time_period']);
        } else {
            $this->view->office = 1;
            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = 159;
            $this->view->time_period = date("d/m/Y");
            $form->time_period->setValue(date("d/m/Y"));
        }
        $this->view->role_id = $this->_identity->getRoleId();

        $base_url = Zend_Registry::get('baseurl');
        $this->view->menu_type = 1;
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    public function batchShelfLifeAllAction() {
        $form = new Form_BatchShelfLife();

        $this->view->form = $form;
        $this->view->report_id = 'REPORTEDPROVINCES';
        $this->view->report_title = 'Vaccine Shelf Life';
        $this->view->actionpage = 'reported-provinces';
        $this->view->parameters = 'T';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        $location_name = $location->getProvincesName();
        $this->view->location_name = $location_name;


        if ($this->_request->isPost()) {
            $this->view->combos = $this->_request->getPost();
            $form_values = $this->_request->getPost();

            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = $this->_identity->getWarehouseId();

            $this->view->time_period = $form_values['time_period'];
            $form->time_period->setValue($form_values['time_period']);
        } else {
            $this->view->office = 1;
            $this->view->warehouse_id = $this->_identity->getWarehouseId();
            $this->view->warehouse_hidden = 159;
            $this->view->time_period = date("d/m/Y");
            $form->time_period->setValue(date("d/m/Y"));
        }
        $this->view->role_id = $this->_identity->getRoleId();

        $base_url = Zend_Registry::get('baseurl');
        $this->view->menu_type = 1;
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * Stock Analysis Summary Report
     */
    public function stockAnalysisSummaryReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'DISTRICTWAREHOUSE';
        $this->view->report_title = 'Stock Analysis Summary Report';
        $this->view->actionpage = 'product-wise-districts-yearly-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }


        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        $locations = new Model_Locations();
        if (!empty($this->_request->prov_sel)) {
            $prov_sel = $this->_request->prov_sel;
            $locations->form_values['pk_id'] = $this->_request->prov_sel;
            $this->view->location_name = $locations->getLocationName();
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $this->_request->prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        } else {
            $prov_sel = 1;
            $this->view->location_name = "Punjab";
            $locations->form_values['geo_level_id'] = '4';
            $locations->form_values['province_id'] = $prov_sel;
            $district = $locations->getLocationsByLevelByProvince();
            $this->view->district = $district;
        }
        $this->view->prov_sel = $prov_sel;


        $this->view->dist_sel = $this->_request->dist_id;
        if ($this->_request->dist_id) {
            $this->view->in_dist = $this->_request->dist_id;
            $this->view->sel_dist = $this->_request->dist_id;
        } else {
            $this->view->in_dist = $this->_request->prov_sel;
            $this->view->sel_dist = 0;
        }
        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Sufficient Report
     */
    public function sufficientReportAction() {

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->role_id = $this->_identity->getRoleId();

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getSufficientProductReport();

        $today_date = new DateTime(date("Y-m-d"));
        $last_year = $today_date->modify("-1 year");
        $this->view->from_date = $from_date = $last_year->format('d F, Y');
        $this->view->to_date = $to_date = date("d F, Y");

        $this->view->result = $result;
        $this->view->headTitle("Stock Sufficiency Report, $from_date - $to_date");

        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Stock Sufficiency Graph'
         exportFileName='District wise percentage of UCs having wastage > 50% " . date('Y-m-d H:i:s') . "' yAxisName='MOS (Avg. Issuance)' showValues='1' formatNumberScale='0' theme='fint' numberSuffix=''>";
        foreach ($result as $data) {
            // echo $data['avg_issue'];
            $month6 = ROUND($data['soh'] / $data['amc'], 1);
            $month = ($month6 > 0) ? $month6 . " months" : '';

            $xmlstore .= "<set label='$data[item_name]' value='" . $month . "' />";
        }
        $xmlstore .= "</chart>";

        $this->view->xml_store = $xmlstore;
    }

    /**
     * Pipeline Report
     */
    public function pipelineReportAction() {

        $this->view->role_id = $this->_identity->getRoleId();

        $stock_master = new Model_StockMaster();
        $result = $stock_master->getPipelineProductReport();

        $today_date = new DateTime(date("Y-m-d"));
        $last_year = $today_date->modify("-1 year");
        $this->view->from_date = $from_date = $last_year->format('d F, Y');
        $this->view->to_date = $to_date = date("d F, Y");

        $this->view->result = $result;
        $this->view->headTitle("Pipeline Report, $from_date - $to_date");
    }

    /**
     * Status Report
     */
    public function statusReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SHIPMENTREPORT';
        $this->view->report_id1 = 'STATUSREPORT';
        $this->view->report_title = 'IM Status Report';
        $this->view->actionpage = 'status-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '95%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Consumption\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        } else if ($sel_indicator == 4) {
            $str_indicator = "\'Issued\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-3 month", strtotime($end_date1)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Stock Issuance
     */
    public function stockIssuanceAction() {
        $stock_master = new Model_StockMaster();
        $warehouse = $this->_identity->getWarehouseName();
        $role_id = $this->_identity->getRoleId();

        if ($this->_request->isPost()) {
            $stock_master->form_values = $this->_request->getParams();
            $this->view->month = $this->_request->getParam('month');
            $this->view->year = $this->_request->getParam('year');
            $wh = new Model_Warehouses();
            $warehouse = $wh->getWarehouseNameByWarehouseId($this->_request->getParam('warehouse'));
        } else {
            $stock_master->form_values = array(
                'month' => date("m"),
                'year' => date("Y"),
                'warehouse' => 159
            );
        }

        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->warehouse = $this->_request->getParam('warehouse', 159);
        $this->view->params = $this->_request->getParams();
        $this->view->result = $stock_master->getStockIssuanceByDate();
        $this->view->warehousename = $warehouse;
        $this->view->role_id = $role_id;

        $this->view->menu_type = 1;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * Print Stock Issuance
     */
    public function printStockIssuanceAction() {
        $this->_helper->layout->setLayout('print');
        $wh = new Model_Warehouses();

        $stock_master = new Model_StockMaster();
        $this->view->headTitle("Stock Issuance Report");
        $stock_master->form_values = $this->_request->getParams();
        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->result = $stock_master->getStockIssuanceByDate();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $wh->getWarehouseNameByWarehouseId($this->_request->getParam('warehouse'));
    }

    /**
     * Stock Report
     */
    public function stockReportAction() {
        $stock_master = new Model_StockMaster();
        $warehouse = $this->_identity->getWarehouseName();
        $role_id = $this->_identity->getRoleId();
        $wh_id = $this->_request->getParam('warehouse', 159);

        if ($this->_request->isPost()) {
            $stock_master->form_values = $this->_request->getParams();
            if ($role_id == 23 || $role_id == 3) {
                $wh = new Model_Warehouses();
                $warehouse = $wh->getWarehouseNameByWarehouseId($this->_request->getParam('warehouse'));
            }
        } else {
            $stock_master->form_values = array(
                'month' => date("m"),
                'year' => date("Y")
            );
            $warehouse = $this->_identity->getWarehouseName();
        }

        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->warehouse = $wh_id;
        $this->view->params = $this->_request->getParams();
        $this->view->result = $stock_master->getStockReportByDate();
        $this->view->warehousename = $warehouse;
        $this->view->lastdate = $stock_master->getStockLastDate($wh_id);
        $this->view->role_id = $role_id;

        $this->view->menu_type = 1;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->headScript()->appendFile($base_url . '/js/all_level_combos.js');
    }

    /**
     * Print Stock Report
     */
    public function printStockReportAction() {
        $this->_helper->layout->setLayout('print');
        $wh = new Model_Warehouses();

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = $this->_request->getParams();
        $this->view->headTitle("Stock Balance Report");
        $this->view->print_title = "Stock Balance Report";
        $this->view->month = $this->_request->getParam('month', date("m"));
        $this->view->year = $this->_request->getParam('year', date("Y"));
        $this->view->result = $stock_master->getStockReportByDate();
        $this->view->username = $this->_identity->getUserName();
        $this->view->warehousename = $wh->getWarehouseNameByWarehouseId($this->_request->getParam('warehouse'));
    }

    /**
     * BCG Coverage Detail
     */
    public function bcgCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'BCG Vaccine Coverage Detail Report';
        $this->view->actionpage = 'bcg-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR BCG ITEM
        $item_pack_sizes->form_values = 6;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
//        if ($this->_request->wh_type == 4) {
//            $locations->form_values['pk_id'] = $this->_request->district;
//            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
//        } elseif ($this->_request->wh_type == 5) {
//            $locations->form_values['pk_id'] = $this->_request->tehsil;
//            $this->view->loc_name = "Tehsil:" . ' ' . $locations->getLocationName();
//        } else {
//            $this->view->loc_name = "District:" . ' ' . 'Badin';
//        }

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $distrctName = $locations->getLocationForReport();
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;


        //FOR Excel File Name
        $reportingDate = $year . '-' . $month . '-01';
        $fileName = 'BCG-Vaccine-Coverage' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
        $this->view->file_name = $fileName;
    }

    /**
     * Vaccines Coverage
     */
    public function vaccinesCoverageAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'VCOVERAGE';
        $this->view->actionpage = 'vaccines-coverage';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Annualized Vaccines Coverage Report';
        $locations = new Model_Locations();
        //filters
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = $province = 1;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district1 = $this->_request->district;
            $locations->form_values['pk_id'] = $this->_request->district;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->district1 = 33;
            $this->view->loc_name = "District:" . ' ' . 'Bahawalpur';
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

        if (!empty($this->_request->year_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = 12;
        } else {
            $year = date("Y");

            $this->view->year_sel = $year;
            $this->view->month_sel = 12;
        }

        $month = 12;
        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

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
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Vaccine Status Wastage
     */
    public function vaccineStatusWastageAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'VSTATUSWASTAGE';
        $this->view->actionpage = 'vaccine-status-wastage';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Vaccine Stock Status Uc Wise';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();
        $item = $item_pack_sizes->VaccineProductsReport();
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
            $this->view->district1 = 33;
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
        $end_date1 = $yy1 . '-' . ($month) . '-01';

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
    }

    /**
     * Vaccine Dropout Rate
     */
    public function vaccineDropoutRateAction() {

        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'VDROPOUTRATE';
        $this->view->actionpage = 'vaccine-dropout-rate';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $this->view->report_title = 'Vaccine Dropout Rate (0-11 months)';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        //filters
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->province = $this->_request->province;
        } else {
            $this->view->province = $province = 2;
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
    }

    /**
     * Stock Movement Report
     */
    public function stockMovementReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'STOCKMOVEMENT';
        $this->view->report_title = 'Stock Movement Report';
        $this->view->actionpage = 'stock-movement-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 159;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }
        //to warehouse
        if (isset($this->_request->to_wh_type) && !empty($this->_request->to_wh_type)) {
            $this->view->to_wh_type = $wh_type = $this->_request->to_wh_type;
        } else {
            $this->view->to_wh_type = $wh_type = 2;
        }
        if (isset($this->_request->to_warehouse_id) && !empty($this->_request->to_warehouse_id)) {
            $this->view->to_warehouse_id = $warehouse_id = $this->_request->to_warehouse_id;
        } else {
            $this->view->to_warehouse_id = $warehouse_id = 162;
        }

        if (isset($this->_request->to_wh_prov_sel) && !empty($this->_request->to_wh_prov_sel)) {
            $this->view->to_wh_prov_sel = $wh_prov_sel = $this->_request->to_wh_prov_sel;
        } else {
            $this->view->to_wh_prov_sel = $wh_prov_sel = '';
        }

        //to warehouse end
        if ($sel_indicator == 1) {
            $str_indicator = "\'Issue\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * To Wh Type
     */
    public function toWhTypeAction() {
        $this->_helper->layout->disableLayout();

        $this->view->wh_type = $this->_request->getParam('SkOfcLvl');
    }

    /**
     * TT Coverage Detail
     */
    public function ttCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'TT Vaccine Coverage Detail Report';
        $this->view->actionpage = 'tt-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR BCG ITEM
        $item_pack_sizes->form_values = 6;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $prov_sel = $this->_request->province;
        } else {
            $this->view->prov_sel = $prov_sel = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $prov_sel;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $prov_sel;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function pentavalentCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'Pentavalent Vaccine Coverage Detail Report';
        $this->view->actionpage = 'pentavalent-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Measles Coverage Detail
     */
    public function measlesCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'Measles Vaccine Coverage Detail Report';
        $this->view->actionpage = 'measles-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 9;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }


        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $prov_sel = $this->_request->province;
        } else {
            $this->view->prov_sel = $prov_sel = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $prov_sel;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $prov_sel;


        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

    /**
     * PCV Coverage Detail
     */
    public function pcvCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'PCV Vaccine Coverage Detail Report';
        $this->view->actionpage = 'pcv-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pcv ITEM
        $item_pack_sizes->form_values = 8;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }
        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }


        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->wh_type = $wh_type = 2;
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

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;


        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

    /**
     * topv Coverage Detail
     */
    public function topvCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'tOPV Vaccine Coverage Detail Report';
        $this->view->actionpage = 'topv-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pcv ITEM
        $item_pack_sizes->form_values = 26;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $prov_sel = $this->_request->province;
        } else {
            $this->view->prov_sel = $prov_sel = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $prov_sel;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $prov_sel;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

    /**
     * bopv(Routine) Coverage Detail
     */
    public function bopvCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'bOPV Vaccine Coverage Detail Report';
        $this->view->actionpage = 'bopv-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pcv ITEM
        $item_pack_sizes->form_values = 26;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $prov_sel = $this->_request->province;
        } else {
            $this->view->prov_sel = $prov_sel = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $prov_sel;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $prov_sel;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

//    public function bopvCoverageDetailAction() {
//        $this->_helper->layout->setLayout('reports');
//        $this->view->report_id = 'SNONREPDIST';
//        $this->view->report_title = 'bOPV Vaccine Coverage Detail Report';
//        $this->view->actionpage = 'bopv-coverage-detail';
//        $this->view->parameters = 'TS01IP';
//        $this->view->parameter_width = '100%';
//        $item_pack_sizes = new Model_ItemPackSizes();
//        $locations = new Model_Locations();
//
//        //FOR Pcv ITEM
//        $item_pack_sizes->form_values = 43;
//        $items = $item_pack_sizes->getItemForConsumptionReport();
//        $this->view->items = $items;
//
//        //For locations Combo
//        $lct = $locations->conusmptionReportLocations();
//        $this->view->location = $lct;
//
//        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
//            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
//            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
//        } else {
//            $from_date = date('m/Y', strtotime('-4 months'));
//            list($from_month, $from_year) = explode('/', $from_date);
//
//            $this->view->from_year_sel = $from_year;
//            $this->view->from_month_sel = $from_month;
//        }
//
//        if (!empty($this->_request->month_sel)) {
//            $this->view->year_sel = $year = $this->_request->year_sel;
//            $this->view->month_sel = $month = $this->_request->month_sel;
//        } else {
//            $date = date('m/Y', strtotime('-4 months'));
//            list($month, $year) = explode('/', $date);
//
//            $this->view->year_sel = $year;
//            $this->view->month_sel = $month;
//        }
//
//        if (isset($this->_request->province) && !empty($this->_request->province)) {
//            $this->view->prov_sel = $prov_sel = $this->_request->province;
//        } else {
//            $this->view->prov_sel = $prov_sel = 2;
//        }
//        if (isset($this->_request->district) && !empty($this->_request->district)) {
//            $this->view->district_id = $district_id = $this->_request->district;
//        } else {
//            $this->view->district_id = $district_id = 30;
//        }
//        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
//            $this->view->tehsil = $this->_request->tehsil;
//        } else {
//            $this->view->tehsil = 1;
//        }
//
//        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
//            $this->view->wh_type = $wh_type = $this->_request->wh_type;
//        } else {
//            $this->view->wh_type = $wh_type = 4;
//        }
//        if ($this->_request->wh_type == 2) {
//            $locations->form_values['pk_id'] = $this->_request->province;
//            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
//        } else if ($this->_request->wh_type == 4) {
//            $locations->form_values['pk_id'] = $this->_request->district;
//            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
//        } else {
//            $locations->form_values['pk_id'] = 2;
//            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
//        }
//        $locations->form_values['geo_level_id'] = 4;
//        $locations->form_values['province_id'] = $prov_sel;
//        $district = $locations->getLocationsByLevelByProvinceConsumption();
//        $this->view->district = $district;
//        $this->view->prov_sel = $prov_sel;
//
//        $locations->form_values['dist_id'] = $district_id;
//        $locations->form_values['year'] = $year;
//        $res = $locations->getLocationsForConsumptionReport();
//        $this->view->result = $res;
//    }

    /**
     * cba Coverage Detail
     */
    public function cbaCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'CBA Vaccine Coverage Detail Report';
        $this->view->actionpage = 'cba-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pcv ITEM
        $item_pack_sizes->form_values = 26;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;


        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $prov_sel = $this->_request->province;
        } else {
            $this->view->prov_sel = $prov_sel = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $prov_sel;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $prov_sel;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

    /**
     * ajaxGetToMonths
     */
    public function ajaxGetToMonthsAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->from_month_id) && !empty($this->_request->from_month_id)) {
            $this->view->to_year_id = $this->_request->to_year_id;
            $this->view->from_year_id = $this->_request->from_year_id;
            $this->view->from_month_id = $this->_request->from_month_id;
        }
    }

    /**
     * ajaxGetToMonths
     */
    public function ajaxGetMonthsAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->year_id)) {
            $this->view->year_id = $this->_request->year_id;
        }
    }

    /**
     * ajaxGetToYear
     */
    public function ajaxGetToYearAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->from_year_id) && !empty($this->_request->from_year_id)) {

            $this->view->from_year_id = $this->_request->from_year_id;
        }
    }

    /**
     * Inventory Management Reporting
     */
    public function inventoryManagementReportingAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'Inventory Management Reporting Report';
        $this->view->actionpage = 'inventory-management-reporting';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR BCG ITEM
        $item_pack_sizes->form_values = 6;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_year = date("Y");

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month = date("m", strtotime("-4 month"));
        }


        if (!empty($this->_request->year_sel) && !empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $year = date("Y");
            $month = date("m", strtotime("-2 month"));

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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

        if ($this->_request->wh_type == 4) {
            $locations->form_values['pk_id'] = $this->_request->district;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } elseif ($this->_request->wh_type == 5) {
            $locations->form_values['pk_id'] = $this->_request->tehsil;
            $this->view->loc_name = "Tehsil:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->loc_name = "District:" . ' ' . 'Badin';
        }

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $distrctName = $locations->getLocationForReport();
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        //FOR Excel File Name
        $reportingDate = $year . '-' . $month . '-01';
        $fileName = 'BCG-Vaccine-Coverage' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
        $this->view->file_name = $fileName;
    }

    /**
     * Pipeline MOS Report
     */
    public function pipelineMosReportAction() {
        $form = new Form_Product();
        $this->view->role_id = $this->_identity->getRoleId();
        $this->view->wh_id = $this->_identity->getWarehouseId();

        if ($this->_request->isPost()) {
            $product = $this->_request->getPost('product');
            $today_date = new DateTime(date("Y-m-d"));
            $last_year = $today_date->modify("+2 year");
            $this->view->to_year = $last_year->format("Y");
            $this->view->product = $product;
            $this->view->headTitle("Pipeline MOS Report");
            $this->view->is_post = true;
            $form->product->setValue($product);
        }

        $this->view->form = $form;
    }

    /**
     * IPV Coverage Detail
     */
    public function ipvCoverageDetailAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'IPV Vaccine Coverage Detail Report';
        $this->view->actionpage = 'ipv-coverage-detail';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR BCG ITEM
        $item_pack_sizes->form_values = 40;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {

            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
        }

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $distrctName = $locations->getLocationForReport();
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;


        //FOR Excel File Name
        $reportingDate = $year . '-' . $month . '-01';
        $fileName = 'BCG-Vaccine-Coverage' . $distrctName . '_for_' . date('M-Y', strtotime($reportingDate));
        $this->view->file_name = $fileName;
    }

    /**
     * Reverse Logistics Report
     */
    public function reverseLogisticsReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REVERSELOGISTIC';
        $this->view->report_title = 'Reverse Logistics Report';
        $this->view->actionpage = 'reverse-logistics-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();

        //FOR BCG ITEM
        $item_pack_sizes->form_values = 40;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
            $locations->form_values['location_id'] = $this->_request->province;
            $warehouse_id = $locations->getWarehouseIdByLocationId();
            $this->view->wh_type_1 = $wh_type = $warehouse_id[0]['pk_id'];
        } else {
            $this->view->prov_sel = $province = 2;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district_id = $district_id = $this->_request->district;
            $locations->form_values['location_id'] = $this->_request->district;
            $warehouse_id = $locations->getWarehouseIdByLocationId();
            $this->view->wh_type_1 = $wh_type = $warehouse_id[0]['pk_id'];
        } else {
            $this->view->district_id = $district_id = 30;
            $this->view->wh_type_1 = 9;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
            $locations->form_values['location_id'] = $this->_request->tehsil;
            $warehouse_id = $locations->getWarehouseIdByLocationId();
            $this->view->wh_type_1 = $wh_type = $warehouse_id[0]['pk_id'];
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 4;
        }

        if ($this->_request->wh_type == 4) {
            $locations->form_values['pk_id'] = $this->_request->district;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } elseif ($this->_request->wh_type == 5) {
            $locations->form_values['pk_id'] = $this->_request->tehsil;
            $this->view->loc_name = "Tehsil:" . ' ' . $locations->getLocationName();
        } else {
            $this->view->loc_name = "District:" . ' ' . 'Badin';
        }

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;
        $distrctName = $locations->getLocationForReport();
        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;
    }

    /**
     *
     */
    public function expiryRateReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'EXPIRYRATE';
        $this->view->report_title = 'Expiry Rate Report';
        $this->view->actionpage = 'expiry-rate-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        //From Month:
        if (isset($this->_request->from_month_sel) && !empty($this->_request->from_month_sel)) {
            $from_month = $this->_request->from_month_sel;
            $this->view->from_month_sel = $from_month;
        } else {
            $from_month = $current_month = date('m');
            $this->view->from_month_sel = $from_month;
        }
        //From Year:
        if (isset($this->_request->from_year_sel) && !empty($this->_request->from_year_sel)) {
            $from_year = $this->_request->from_year_sel;
            $this->view->from_year_sel = $from_year;
        } else {
            $current_year = date('Y');
            $previous_year = $current_year - 1;
            $from_year = $previous_year;
            $this->view->from_year_sel = $from_year;
        }

        //To Month:
        if (isset($this->_request->month_sel) && !empty($this->_request->month_sel)) {
            $to_month = $this->_request->month_sel;
            $this->view->month_sel = $to_month;
        } else {
            $current_month = date('m');
            $to_month = $current_month;
            $this->view->month_sel = $to_month;
        }
        //To Year:
        if (isset($this->_request->year_sel) && !empty($this->_request->year_sel)) {
            $to_year = $this->_request->year_sel;
            $this->view->year_sel = $to_year;
        } else {
            $current_year = date('Y');
            $to_year = $current_year;
            $this->view->year_sel = $to_year;
        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 159;
        }

        $from_date = $from_year . '-' . $from_month;
        $to_date = $to_year . '-' . $to_month;

        $this->view->from_date = $from_date;
        $this->view->to_date = $to_date;

        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Demand vs Issuance report
     */
    public function demandIssuanceReportAction() {
        $form = new Form_DemandIssuance();
        $this->view->form = $form;
        $item_pack_sizes = new Model_ItemPackSizes();

        $wh_id = 161;
        $year = 2016;
        if ($this->_request->isPost()) {
            $res = $this->_request->getPost();
            $item_pack_sizes->form_values = $res;
            $wh_id = $res['from_warehouse_id'];
            $form->from_warehouse_id->setValue($res['from_warehouse_id']);
            $year = $res['status'];
            $form->status->setValue($res['status']);
        }

        $warehouses = new Model_Warehouses();
        $province = $warehouses->getWarehouseProvinceByWarehouseId($wh_id);

        $this->view->province = $province[0]['province_name'];
        $this->view->year = $res['status'];


        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'CENTRALWAREHOUSE';
        $this->view->report_title = 'Demand vs Issuance Report';
        $this->view->actionpage = 'demand-issuance-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';

        $location_population = new Model_LocationPopulation();
        $province_population = $location_population->getProvincePopulation($province[0]['province_id'], $year);
        //var_dump($province_population);exit;
        $this->view->province_population = $province_population[0]['population'];


        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;

        $routine_item = $item_pack_sizes->productsReport1();
        $this->view->routine_item = $routine_item;

        $total_allocation = $item_pack_sizes->getTotalAllocation();
        $this->view->total_allocation = $total_allocation;


        $issed_qty = $item_pack_sizes->getQtrWiseIssuance($wh_id, $year);

        $qtr1 = Array();
        $qtr2 = Array();
        $qtr3 = Array();
        $qtr4 = Array();

        if (!empty($issed_qty)) {
            foreach ($issed_qty as $r) {
                $qtr1[$r['pk_id']] = $r['qtr1'];
                $qtr2[$r['pk_id']] = $r['qtr2'];
                $qtr3[$r['pk_id']] = $r['qtr3'];
                $qtr4[$r['pk_id']] = $r['qtr4'];
            }
        }

        $this->view->qtr1 = $qtr1;
        $this->view->qtr2 = $qtr2;
        $this->view->qtr3 = $qtr3;
        $this->view->qtr4 = $qtr4;

        $demand_master = $item_pack_sizes->getDemandMasters($wh_id, $year);

        $this->view->demand_dates = $demand_master;


        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 159;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Issue\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Vaccine Arrival report
     */
    public function vaccineArrivalReportAction() {
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            // $em = Zend_Registry::get('doctrine');
            // $em->getConnection()->beginTransaction();
            //  try {
            if ($this->_request->isPost()) {
                if (isset($this->_request->search) && !empty($this->_request->search)) {
                    if (isset($this->_request->receive_no) && !empty($this->_request->receive_no)) {
                        $receive_no = $this->_request->receive_no;
                        $stock_master = new Model_StockMaster();

                        $stock_master->transaction_number = $receive_no;
                        $this->view->receive_num = $receive_no;
                        $stock_master->to_warehouse_id = $this->_identity->getWarehouseId();
                        $stockReceive = $stock_master->getShipmentsByReceiveNo();
                        $this->view->receive_no = $stockReceive;
                        $this->view->rec_no = "";
                        $this->view->pk_id = $stockReceive[0]['pk_id'];

                        $shipments = new Model_Shipments();
                        $shipments->shipment_id = $stockReceive[0]['pk_id'];
                        $var_data = $shipments->getVarData();
                        $this->view->var_data = $var_data;

                        $shipments->shipment_id = $stockReceive[0]['pk_id'];
                        $var_electronic_data = $shipments->getVarElectronicData();
                        $this->view->var_electronic_data = $var_electronic_data;

                        $shipments->shipment_id = $stockReceive[0]['pk_id'];
                        $shipments->transaction_number = $receive_no;

                        $shipments->to_warehouse_id = $this->_identity->getWarehouseId();

                        $shipment_vaccines = $shipments->getShipmentsVaccine();
                        $shipment_non_vaccines = $shipments->getShipmentsNonVaccine();
                        $this->view->shipment_id = $stockReceive[0]['pk_id'];
                        $this->view->shipment_vaccines = $shipment_vaccines;
                        $this->view->shipment_non_vaccines = $shipment_non_vaccines;
                    }
                } else {

                    $stock_master = new Model_StockMaster();
                    $shipment = new Model_Shipments();
                    $data = $this->_request->getPost();
                    $shipment->form_values = $data;
                    $shipment->addVarReport();
                    $this->view->msg = '';
                    $receive_no = $data['hdn_rec_no'];
                    $stock_master->transaction_number = $receive_no;
                    $this->view->receive_num = $receive_no;
                    $stock_master->to_warehouse_id = $this->_identity->getWarehouseId();
                    $stockReceive = $stock_master->getShipmentsByReceiveNo();
                    $this->view->receive_no = $stockReceive;
                    $this->view->rec_no = "";
                    $this->view->pk_id = $stockReceive[0]['pk_id'];

                    $shipments = new Model_Shipments();
                    $shipments->shipment_id = $stockReceive[0]['pk_id'];
                    $var_data = $shipments->getVarData();
                    $this->view->var_data = $var_data;

                    $shipments->shipment_id = $stockReceive[0]['pk_id'];
                    $var_electronic_data = $shipments->getVarElectronicData();
                    $this->view->var_electronic_data = $var_electronic_data;

                    $shipments->shipment_id = $stockReceive[0]['pk_id'];
                    $shipments->transaction_number = $receive_no;

                    $shipments->to_warehouse_id = $this->_identity->getWarehouseId();

                    $shipment_vaccines = $shipments->getShipmentsVaccine();
                    $shipment_non_vaccines = $shipments->getShipmentsNonVaccine();
                    $this->view->shipment_id = $stockReceive[0]['pk_id'];
                    $this->view->shipment_vaccines = $shipment_vaccines;
                    $this->view->shipment_non_vaccines = $shipment_non_vaccines;
                }
            }

            //    $em->getConnection()->commit();
            //  } catch (Exception $e) {
            //      $em->getConnection()->rollback();
            //      $em->close();
            //     App_FileLogger::info($e);
            //  }
        }
    }

    /**
     * Vaccine Arrival report
     */
    public function electronicAlarmDeviceReportAction() {
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

    public function ajaxProductDoseAction() {
        $this->_helper->layout->disableLayout();
        $item_id = $this->_request->getParam("item_id");
        $product = new Model_ItemPackSizes();
        $prod_data = $product->getProductDose($item_id);
        $this->view->prod_data = $prod_data;
        $this->view->selId = $this->_request->getParam("dose", 'all');
    }

    public function printVarDetailAction() {
        $this->_helper->layout->setLayout('var');
        $this->view->headTitle("Vaccine Arrival Report");
        $shipments = new Model_Shipments();
        $shipments->shipment_id = $this->_request->id;
        $var_data = $shipments->getVarData();
        $this->view->var_data = $var_data;

        $shipments->shipment_id = $this->_request->id;
        $var_electronic_data = $shipments->getVarElectronicData();
        $this->view->var_electronic_data = $var_electronic_data;

        $shipments->shipment_id = $this->_request->id;
        $sm = $this->_em->getRepository("StockMaster")->find($this->_request->id);

        $shipments->transaction_number = $sm->getTransactionNumber();
        $shipments->to_warehouse_id = $this->_identity->getWarehouseId();

        $shipment_vaccines = $shipments->getShipmentsVaccine();
        $shipment_non_vaccines = $shipments->getShipmentsNonVaccine();
        $this->view->shipment_vaccines = $shipment_vaccines;
        $this->view->shipment_non_vaccines = $shipment_non_vaccines;
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function monthlyCategoryWiseReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'Monthly Category-Wise Antigne Report';
        $this->view->actionpage = 'monthly-category-wise-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        //  $items = $item_pack_sizes->getItemForConsumptionReport();
        $items = $districts = Array(
            0 => Array('pk_id' => 62, 'district_name' => "Pentavalent |||"),
            1 => Array('pk_id' => 63, 'district_name' => "BCG"),
            2 => Array('pk_id' => 109, 'district_name' => "IPV"),
            3 => Array('pk_id' => 4279, 'district_name' => "Measles |"),
            4 => Array('pk_id' => 4278, 'district_name' => "Measles ||")
        );
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

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
        //$date_from = date('01/m/Y');
        //  $date_to = date('d/m/Y');

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->report_type = $report_type = 2;
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
        if (isset($this->_request->prod_sel) && !empty($this->_request->prod_sel)) {
            $this->view->sel_item = $sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_item = $sel_item = 7;
        }
        $this->view->sel_item = $sel_item;
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $year = $yy;
        $locations->form_values['year'] = $year;


        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        // $item = $item_pack_sizes->VaccineProductsReport();
        $item = $districts = Array(
            0 => Array('pkId' => 7, 'itemName' => "Pentavalent 1"),
            1 => Array('pkId' => 8, 'itemName' => "Pentavalent 3"),
            2 => Array('pkId' => 6, 'itemName' => "BCG"),
            3 => Array('pkId' => 40, 'itemName' => "IPV"),
            4 => Array('pkId' => 9, 'itemName' => "Measles 1"),
            5 => Array('pkId' => 10, 'itemName' => "Measles 2")
        );
        $this->view->item_id = $item;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function utilizationReportAction() {
        //    $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST2';
        $this->view->report_title = 'UC-Wise Access & Utilization of Pentavalent';
        $this->view->actionpage = 'utilization-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;
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

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->report_type = $report_type = 2;
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
        if (isset($this->_request->prod_sel) && !empty($this->_request->prod_sel)) {
            $this->view->sel_item = $sel_item = $this->_request->prod_sel;
        } else {
            $this->view->sel_item = $sel_item = 6;
        }
        $this->view->sel_item = $sel_item;
        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = $province;
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = $province;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $yy1;


        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        $item = $item_pack_sizes->VaccineProductsReport();
        $this->view->item_id = $item;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     *
     */
    public function vaccineDistributionReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'EXPIRYRATE';
        $this->view->report_id1 = 'VACCDIS';
        $this->view->report_title = 'Vaccine Distribution Report';
        $this->view->actionpage = 'vaccine-distribution-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->vaccineDistributionProductsReport();
        $this->view->item_id = $item;

        if (!empty($this->_request->date_from) && !empty($this->_request->date_from)) {
           
            $this->view->date_from = $from_month = $this->_request->date_from;
        } else {
            $from_date = date('m/Y');
            list($from_month, $from_year) = explode('/', $from_date);
            $date_from = date('m/'.'01/' . 'Y');
           
            $this->view->date_from = $date_from;
        }

        if (!empty($this->_request->date_to)) {
         
            $this->view->date_to = $month = $this->_request->date_to;
        } else {
        
           
            $this->view->date_to = $month = date('m/d/Y');
        }

//        //To Month:
//        if (isset($this->_request->month_sel) && !empty($this->_request->month_sel)) {
//            $to_month = $this->_request->month_sel;
//            $this->view->month_sel = $to_month;
//        } else {
//            $current_month = date('m') - 8;
//            $to_month = $current_month;
//            $this->view->month_sel = $to_month;
//        }
//        //To Year:
//        if (isset($this->_request->year_sel) && !empty($this->_request->year_sel)) {
//            $to_year = $this->_request->year_sel;
//            $this->view->year_sel = $to_year;
//        } else {
//            $current_year = date('Y') - 1;
//            $to_year = $current_year;
//            $this->view->year_sel = $to_year;
//        }

        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = $this->_identity->getWarehouseId();
        }

        if (isset($this->_request->report_type) && !empty($this->_request->report_type)) {
            $this->view->report_type = $report_type = $this->_request->report_type;
        } else {
            $this->view->report_type = $report_type = 1;
        }

//        $from_date1 = $from_year . '-' . $from_month;
//        $from_date = date('Y-m', strtotime($from_date1));
//        $to_date1 = $to_year . '-' . $to_month;
//        $to_date = date('Y-m', strtotime($to_date1));
//        $this->view->from_date = $from_date;
//        $this->view->to_date = $to_date;

        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;

        $locations = new Model_Locations();
        $locations->form_values['wh_type'] = $wh_type;
        $locations->form_values['warehouse_id'] = $warehouse_id;
        $lct = $locations->locationsVaccineDistributionReport();
        $this->view->location1 = $lct;
        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function coverageReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';

        $this->view->actionpage = 'coverage-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->wh_type = $wh_type = 2;
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
            $this->view->sel_item = $sel_item = 6;
        }
        $item_name = $item_pack_sizes->itemName($sel_item);
        $this->view->report_title = $item_name[0]['itemName'] . ' Coverage ';
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
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function expiredStockReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'EXPIREDSTOCK';

        $this->view->actionpage = 'expired-stock-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values['prov_id'] = 1;
        $stocks = $item_pack_sizes->getExpiredStockReport();
        $this->view->stocks = $stocks;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Central Provincial Warehouse
     */
    public function priorityDistributionReportAction() {

        $this->view->report_id = 'CENTRALWAREHOUSE';
        $this->view->report_title = 'Priority Distribution Report';
        $this->view->actionpage = 'priority-distribution-report';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->getAllItemsNonDil();
        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (!empty($this->_request->rep_indicators)) {
            $this->view->sel_indicator = $sel_indicator = $this->_request->rep_indicators;
        } else {
            $this->view->sel_indicator = $sel_indicator = 1;
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 1;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 159;
        }
        $wh = new Model_Warehouses();
        $warehouse_name = $wh->getWarehouseNameByWarehouseId($warehouse_id);
        $warehouses = $wh->getWarehouseByLevel($wh_type);
        $this->view->warehouse_name = $warehouse_name;
        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Issue\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;
    }

    /**
     * Central Provincial Warehouse
     */
    public function priorityDistributionAction() {

        $this->view->report_id = 'CENTRALWAREHOUSE';
        $this->view->report_title = 'Priority Distribution';
        $this->view->actionpage = 'priority-distribution';
        $this->view->parameters = 'TS01I';
        $this->view->parameter_width = '100%';
        $ips = new Model_ItemPackSizes();
        $this->view->products = $ips->itemsExpiryReport();
        $this->view->item_id = $ips->itemsExpiryReport();
        if (!empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $year = date("Y");
            if (date('d') > 10) {
                $month = date("m", strtotime("-1"));
            } else {
                $month = date("m", strtotime("-2"));
            }
            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (!empty($this->_request->purpose)) {
            $this->view->purpose = $purpose = $this->_request->purpose;
        } else {
            $this->view->purpose = $purpose = '1';
        }
        if (!empty($this->_request->status)) {
            $this->view->status = $status = $this->_request->status;
        } else {
            $this->view->status = $status = '1';
        }

        if (!empty($this->_request->prod_sel)) {
            $this->view->prod_sel = $prod_sel = $this->_request->prod_sel;
        } else {
            $this->view->prod_sel = $prod_sel = 'all';
        }

        if (!empty($this->_request->prod_sel)) {
            $this->view->prod_sel = $prod_sel = $this->_request->prod_sel;
        } else {
            $this->view->prod_sel = $prod_sel = 'all';
        }
        $this->view->prov_sel = $this->_request->prov_sel;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        $this->view->wh_type = $wh_type = 2;

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->wh_prov_sel;
        } else {
            $this->view->warehouse_id = $warehouse_id = 1;
        }
        $wh = new Model_Warehouses();
        //$warehouse_name = $wh->getWarehouseNameByWarehouseId($warehouse_id);
        if ($warehouse_id != "all") {
            $warehouse_province = $wh->getWarehouseByLevel($warehouse_id);
            $this->view->warehouse_name = $warehouse_province[0]['location_name'];
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '1';
        }

        if ($sel_indicator == 1) {
            $str_indicator = "\'Issue\'";
        } else if ($sel_indicator == 2) {
            $str_indicator = "\'Stock on Hand\'";
        } else if ($sel_indicator == 3) {
            $str_indicator = "\'Received\'";
        }
        $this->view->str_indicator = $str_indicator;

        $end_date = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date))));
        $start_date = date('Y-m-d', strtotime("-364 days", strtotime($end_date)));

        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = $this->_request->prod_sel;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->nationalReport();
        $this->view->location = $lct;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * ajaxGetUcsAction
     */
    public function ajaxGetProductsAction() {
        $this->_helper->layout->disableLayout();
        if (isset($this->_request->purpose) && !empty($this->_request->purpose)) {

            $item_pack_size = new Model_ItemPackSizes();
            $item_pack_size->form_values['purpose'] = $this->_request->purpose;

            $array = $item_pack_size->itemsExpiryReport();



            $this->view->result = $array;
        }
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function dataPerformanceReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'REPORTEDBYUC';
        $this->view->report_title = 'Data Performance Report';
        $this->view->actionpage = 'data-performance-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();

        $item = $item_pack_sizes->productsReport();
        $this->view->item_id = $item;
        $location = new Model_Locations();
        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $province = $this->_request->province;
            $this->view->province = $province;
        } else {
            $this->view->province = $province = 5;
        }
        if (isset($this->_request->district) && !empty($this->_request->district)) {
            $this->view->district = $this->_request->district;
        } else {
            $this->view->district = 1;
        }
        if (isset($this->_request->tehsil) && !empty($this->_request->tehsil)) {
            $this->view->tehsil = $this->_request->tehsil;
        } else {
            $this->view->tehsil = 1;
        }
//        $location->form_values['province_id'] = $province;
//        $location_name = $location->districtLocations();
//       
//        $this->view->location_name = $location_name;

        if (!empty($this->_request->year_sel) && !empty($this->_request->ending_month)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->ending_month;
        } else {
            $date = date('m/Y', strtotime('-2 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }
        if (isset($this->_request->wh_type) && !empty($this->_request->wh_type)) {
            $this->view->wh_type = $wh_type = $this->_request->wh_type;
        } else {
            $this->view->wh_type = $wh_type = 2;
        }
        if (isset($this->_request->warehouse_id) && !empty($this->_request->warehouse_id)) {
            $this->view->warehouse_id = $warehouse_id = $this->_request->warehouse_id;
        } else {
            $this->view->warehouse_id = $warehouse_id = 162;
        }

        if (isset($this->_request->wh_prov_sel) && !empty($this->_request->wh_prov_sel)) {
            $this->view->wh_prov_sel = $wh_prov_sel = $this->_request->wh_prov_sel;
        } else {
            $this->view->wh_prov_sel = $wh_prov_sel = '';
        }


        $this->view->prov_sel = $province;

        if (isset($this->_request->stk_sel) && !empty($this->_request->stk_sel)) {
            $this->view->stk_sel = $sel_stk = $this->_request->stk_sel;
        } else {
            $this->view->stk_sel = $sel_stk = 1;
        }

        if (isset($this->_request->report_type) && !empty($this->_request->report_type)) {
            $this->view->report_type = $report_type = $this->_request->report_type;
        } else {
            $this->view->report_type = $report_type = 1;
        }

        if (isset($this->_request->report_by) && !empty($this->_request->report_by)) {
            $this->view->report_by = $report_by = $this->_request->report_by;
        } else {
            $this->view->report_by = $report_by = 1;
        }


        $end_date1 = $year . '-' . ($month) . '-01';
        $end_date = date('Y-m-d', strtotime("-1 days", strtotime("+1 month", strtotime($end_date1))));
        $start_date = date('Y-m-d', strtotime("-2 month", strtotime($end_date1)));
        $this->view->start_date = $start_date;
        $this->view->end_date = $end_date;
        // Start date and End date
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $end);
        $this->view->period = $period;
        $this->view->sel_item = 6;
        $stakeholder = new Model_Stakeholders();
        $stk = $stakeholder->nationReport();
        $this->view->stk = $stk;
        $locations = new Model_Locations();
        $lct = $locations->devisionalReport();
        $this->view->location = $lct;
    }

    /**
     * Inventory Report
     */
    public function inventoryReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';

        $this->view->actionpage = 'inventory-report';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
        $this->view->location = $lct;

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_month_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y', strtotime('-4 months'));
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }

        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->province) && !empty($this->_request->province)) {
            $this->view->prov_sel = $province = $this->_request->province;
        } else {
            $this->view->prov_sel = $province = 2;
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
            $this->view->wh_type = $wh_type = 'all';
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
            $this->view->sel_item = $sel_item = 6;
        }
        $item_name = $item_pack_sizes->itemName($sel_item);
        $this->view->report_title = 'Inventory/Stock Report ';
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
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    /**
     * Antigen Vaccine Coverage
     */
    public function antigenVaccinesCoverageAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';

        $this->view->actionpage = 'antigen-vaccines-coverage';
        $this->view->parameters = 'TS01IP';
        $this->view->parameter_width = '100%';
        $item_pack_sizes = new Model_ItemPackSizes();
        $locations = new Model_Locations();


        //FOR Pentavalent ITEM
        $item_pack_sizes->form_values = 7;
        $items = $item_pack_sizes->getItemForConsumptionReport();
        $this->view->items = $items;

        //For locations Combo
        $lct = $locations->conusmptionReportLocations();
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
            $this->view->wh_type = $wh_type = 2;
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
     * Sufficient Report
     */
    public function warehouseTypesAction() {

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_combos.js');
        $this->view->role_id = $this->_identity->getRoleId();

        $warehouses = new Model_Warehouses();
        $result = $warehouses->getWarehouseTypes();

       

        $this->view->result = $result;
       
    }

}
