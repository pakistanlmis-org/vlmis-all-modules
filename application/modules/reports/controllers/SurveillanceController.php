<?php

/**
 * Reports_SurveillanceController
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Controller for Reports Surveillance
 */
class Reports_SurveillanceController extends App_Controller_Base {

    /**
     * Pentavalent Coverage Detail
     */
    public function vpdAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST';
        $this->view->report_title = 'VPD Linelist Report';
        $this->view->actionpage = 'vpd';
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

        if (!empty($this->_request->date_from) && !empty($this->_request->date_from)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->date_from;
        } else {
            $from_date = date('m/Y');
            list($from_month, $from_year) = explode('/', $from_date);
            $date_from = date('Y-m' . '-01');
            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $date_from;
        }

        if (!empty($this->_request->date_to)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->date_to;
        } else {
            $date = date('m/Y');
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month = date('Y-m-d');
            ;
        }

        if (isset($this->_request->dist_id) && !empty($this->_request->dist_id)) {
            $this->view->dist_id = $district_id = $this->_request->dist_id;
            $this->view->in_dist = $this->_request->dist_id;

            $this->view->sel_dist = $this->_request->dist_id;
        } else {
            $this->view->dist_id = $district_id = '';
        }
        if (isset($this->_request->teh_id) && !empty($this->_request->teh_id)) {
            $this->view->tehsil = $this->_request->teh_id;
            $this->view->sel_teh = $this->_request->teh_id;
            $locations->form_values['pk_id'] = $this->_request->teh_id;
            $location_name = $locations->getLocationName();
            $this->view->tehsil_name = $location_name;
            $locations->form_values['geo_level_id'] = 5;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $tehsil = $locations->getLocationsByLevelByDistrict();
            $this->view->combo_teh = $tehsil;
        } else {
            $this->view->tehsil = '';
        }

        if (isset($this->_request->uc_id) && !empty($this->_request->uc_id)) {
            $this->view->uc_id = $this->_request->uc_id;
            $this->view->sel_uc = $this->_request->uc_id;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['parent_id'] = $this->_request->teh_id;
            $uc = $locations->getLocationsByLevelByTehsil();
            $this->view->combo_uc = $uc;
        } else {
            $this->view->uc_id = '';
        }


        if (isset($this->_request->report_type) && !empty($this->_request->report_type)) {
            $this->view->report_type = $report_type = $this->_request->report_type;
        } else {
            $this->view->report_type = $report_type = 246;
        }

        if ($this->_request->wh_type == 2) {
            $locations->form_values['pk_id'] = $this->_request->province;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        } else if ($this->_request->wh_type == 4) {
            $locations->form_values['pk_id'] = $this->_request->dist_id;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $locations->form_values['pk_id'] = 2;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        }

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = '2';
        $district = $locations->getLocationsByLevelByProvinceConsumption();
        $this->view->district = $district;
        $this->view->prov_sel = '2';

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;


        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::TYPE_CASE);
        $result_case = $list_master->getListDetailByType();
        $this->view->type_case = $result_case;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

    public function aefiAction() {
        
    }

    /**
     * ajaxGetToMonths
     */
    public function ajaxGetToWeeksAction() {
        $this->_helper->layout->disableLayout();

        if (isset($this->_request->from_month_id) && !empty($this->_request->from_month_id)) {
            $this->view->to_year_id = $this->_request->to_year_id;
            $this->view->from_year_id = $this->_request->from_year_id;
            $this->view->from_month_id = $this->_request->from_month_id;
        }
    }

    /**
     * Pentavalent Coverage Detail
     */
    public function vpdWeeklyReportAction() {
        $this->_helper->layout->setLayout('reports');
        $this->view->report_id = 'SNONREPDIST2';
        $this->view->report_title = 'VPD Weekly Report';
        $this->view->actionpage = 'vpd-weekly-report';
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

        if (!empty($this->_request->from_year_sel) && !empty($this->_request->from_year_sel)) {
            $this->view->from_year_sel = $from_year = $this->_request->from_year_sel;
            $this->view->from_month_sel = $from_month = $this->_request->from_month_sel;
        } else {
            $from_date = date('m/Y');
            list($from_month, $from_year) = explode('/', $from_date);

            $this->view->from_year_sel = $from_year;
            $this->view->from_month_sel = $from_month;
        }
        $surveillance = new Model_Surveillance();
        $weeks = $surveillance->getVPdWeeks($from_year);
        $this->view->weeks = $weeks;
        if (!empty($this->_request->month_sel)) {
            $this->view->year_sel = $year = $this->_request->year_sel;
            $this->view->month_sel = $month = $this->_request->month_sel;
        } else {
            $date = date('m/Y', strtotime('-4 months'));
            list($month, $year) = explode('/', $date);

            $this->view->year_sel = $year;
            $this->view->month_sel = $month;
        }

        if (isset($this->_request->dist_id) && !empty($this->_request->dist_id)) {
            $this->view->dist_id = $district_id = $this->_request->dist_id;
            $this->view->in_dist = $this->_request->dist_id;

            $this->view->sel_dist = $this->_request->dist_id;
        } else {
            $this->view->dist_id = $district_id = 30;
        }
        if (isset($this->_request->teh_id) && !empty($this->_request->teh_id)) {
            $this->view->tehsil = $this->_request->teh_id;
            $this->view->sel_teh = $this->_request->teh_id;
            $locations->form_values['pk_id'] = $this->_request->teh_id;
            $location_name = $locations->getLocationName();
            $this->view->tehsil_name = $location_name;
            $locations->form_values['geo_level_id'] = 5;
            $locations->form_values['district_id'] = $this->_request->dist_id;
            $tehsil = $locations->getLocationsByLevelByDistrict();
            $this->view->combo_teh = $tehsil;
        } else {
            $this->view->tehsil = 1;
        }

        if (isset($this->_request->uc_id) && !empty($this->_request->uc_id)) {
            $this->view->uc_id = $this->_request->uc_id;
            $this->view->sel_uc = $this->_request->uc_id;
            $locations->form_values['geo_level_id'] = 6;
            $locations->form_values['parent_id'] = $this->_request->teh_id;
            $uc = $locations->getLocationsByLevelByTehsil();
            $this->view->combo_uc = $uc;
        } else {
            $this->view->uc_id = 1;
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
            $locations->form_values['pk_id'] = $this->_request->dist_id;
            $this->view->loc_name = "District:" . ' ' . $locations->getLocationName();
        } else {
            $locations->form_values['pk_id'] = 2;
            $this->view->loc_name = "Province:" . ' ' . $locations->getLocationName();
        }

        $locations->form_values['geo_level_id'] = '4';
        $locations->form_values['province_id'] = '2';
        $district = $surveillance->getLocationsByLevelByProvinceConsumption1();
        $this->view->district = $district;
        $this->view->prov_sel = '2';

        $current_week = $surveillance->current_week();
        $this->view->current_week = $current_week;

        $locations->form_values['dist_id'] = $district_id;
        $locations->form_values['year'] = $year;


        $res = $locations->getLocationsForConsumptionReport();
        $this->view->result = $res;

        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::TYPE_CASE);
        $result_case = $list_master->getListDetailByType();
        $this->view->type_case = $result_case;

        $base_url = Zend_Registry::get("baseurl");
        $this->view->inlineScript()->appendFile($base_url . '/js/tableToExcel.js');
    }

}
