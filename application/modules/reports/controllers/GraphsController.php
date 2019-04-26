<?php

/**
 * Reports_GraphsController
 *     Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */
/**
 * Graphs
 */
require_once 'FusionCharts/Code/PHP/Includes/FusionCharts.php';

/**
 *  Controller for Graphs
 */
class Reports_GraphsController extends App_Controller_Base {

    /**
     * Reports_GraphsController index
     */
    public function indexAction() {
        // action body
    }

    /**
     * Fetch Products
     */
    public function fetchProductsAction() {
        $this->_helper->layout->disableLayout();
    }

    /**
     * Fetch Province
     */
    public function fetchProvAction() {
        $this->_helper->layout->disableLayout();
    }

    /**
     * Fetch Districts
     */
    public function fetchDistrictsAction() {
        $this->_helper->layout->disableLayout();
    }

    /*
     * Following 4 Graphs are Under the Refrigerator/Freezer Sub-menu
     * Vaccine Storage Capacity At 2 to 8
     */

    public function vaccineStorageCapacityAt2to8Action() {
        $search_form = new Form_ReportsSearch();
        $main_heading = "Vaccine storage capacity at +2C to +8C";
        $str_sub_heading = "";
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxVaccineStorageCapacityAt2to8
     */
    public function ajaxVaccineStorageCapacityAt2to8Action() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_warehouse->form_values = $form_values;
        $data_arr = $ccm_warehouse->vaccineStorageCapacityAt2to8Graph();
        $main_heading = "Vaccine storage capacity at +2C to +8C";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" showplotborder="1" plotfillalpha="80" showborder="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" color = "#A80000">';
        $dataset_6 = '<dataset seriesname="Data Not Available" color = "#F5D133">';
        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_3 .= '<set value="' . ($sub_arr['match10'] + $sub_arr['surplus1030'] + $sub_arr['shortage1030']) . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
            $dataset_6 .= '<set value="' . $sub_arr['Unknown'] . '" />';
        }
        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_5 .= '</dataset>';
        $dataset_6 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_5;
        $xmlstore .= $dataset_6;
        $xmlstore .="</chart>";
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = "StackedBar2D";
        $this->view->xmlstore = $xmlstore;
        $this->view->width = '100%';
        $this->view->height = '400';
        $this->view->data = $data_arr;
    }

    /**
     * Vaccine Storage Capacity At 20
     */
    public function vaccineStorageCapacityAt20Action() {
        $search_form = new Form_ReportsSearch();
        $main_heading = "Vaccine storage capacity at -20c";
        $this->view->main_heading = $main_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxVaccineStorageCapacityAt20
     */
    public function ajaxVaccineStorageCapacityAt20Action() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_warehouse->form_values = $form_values;
        $data_arr = $ccm_warehouse->vaccineStorageCapacityAt20Graph();
        $main_heading = "Vaccine storage capacity at -20c";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" showplotborder="1" plotfillalpha="80" showborder="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_2 = '<dataset seriesname="Surplus 10-30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 10%" >';
        $dataset_4 = '<dataset seriesname="Shortage 10-30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" >';
        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['surplus1030'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['match10'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['shortage1030'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
        }
        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /**
     * Icepack Freezing Capacity Against Routine Requirements
     */
    public function icepackFreezingCapacityAgainstRoutineRequirementsAction() {
        $search_form = new Form_ReportsSearch();
        $main_heading = "Icepack freezing capacity ";
        $this->view->main_heading = $main_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxIcepackFreezingCapacityAgainstRoutineRequirements
     */
    public function ajaxIcepackFreezingCapacityAgainstRoutineRequirementsAction() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_warehouse->form_values = $form_values;
        $data_arr = $ccm_warehouse->icepackFreezingCapacityAgainstSIARequirementsGraph();
        $main_heading = "Icepack freezing capacity";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" showborder="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_2 = '<dataset seriesname="Surplus 10-30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 30%" >';
        $dataset_4 = '<dataset seriesname="Shortage 10-30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" >';
        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['surplus1030'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['match10'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['shortage1030'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
        }
        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /**
     * Icepack Freezing Capacity Against Sia Requirements
     */
    public function icepackFreezingCapacityAgainstSiaRequirementsAction() {
        $search_form = new Form_ReportsSearch();
        $main_heading = "Icepack freezing capacity";
        $this->view->main_heading = $main_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxIcepackFreezingCapacityAgainstSiaRequirements
     */
    public function ajaxIcepackFreezingCapacityAgainstSiaRequirementsAction() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->icepackFreezingCapacityAgainstSIARequirementsGraph();
        $main_heading = "Icepack freezing capacity against SIA requirements";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0"exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Surplus > 30%" >';
        $dataset_2 = '<dataset seriesname="Surplus 10-30%" >';
        $dataset_3 = '<dataset seriesname="Match +/- 30%" >';
        $dataset_4 = '<dataset seriesname="Shortage 10-30%" >';
        $dataset_5 = '<dataset seriesname="Shortage > 30%" >';
        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['surplus30'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['surplus1030'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['match10'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['shortage1030'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['shortage30'] . '" />';
        }
        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /*
     * Following 3 Graphs are Under the Refrigerator/Freezer Sub-menu
     * Refrigerators By Working Status
     */

    public function refrigeratorsByWorkingStatusAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getRefrigeratorsByWorkingStatus();
        $search_form = new Form_ReportsSearch();
        $main_heading = "Working status by refrigerators/freezers model";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxRefrigeratorsByWorkingStatus
     */
    public function ajaxRefrigeratorsByWorkingStatusAction() {
        $this->_helper->layout->disableLayout();
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getRefrigeratorsByWorkingStatus();
        $main_heading = "Working status by refrigerators/freezers model";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '500';
    }

    /**
     * Refrigerators Freezers Models By Age Group
     */
    public function refrigeratorsFreezersModelsByAgeGroupAction() {
        $search_form = new Form_ReportsSearch();
        $main_heading = "Refrigerators/freezers models by age group";
        $str_sub_heading = "";
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxRefrigeratorsFreezersModelsByAgeGroup
     */
    public function ajaxRefrigeratorsFreezersModelsByAgeGroupAction() {
        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;
        $data_arr = $ccm_models->getRefrigeratorModelsByAgeGroupReport();
        $main_heading = "Refrigerators/Freezers models by age group";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="0-2 Years" >';
        $dataset_2 = '<dataset seriesname="3-5 Years" >';
        $dataset_3 = '<dataset seriesname="6-10 Years" >';
        $dataset_4 = '<dataset seriesname=">10 Years" >';
        $dataset_5 = '<dataset seriesname="Unknown" >';
        $counter = 0;
        foreach ($data_arr as $sub_arr) {
            if ($counter >= 10) {
                break;
            }
            $categories .='<category label="' . $sub_arr['ccm_model_name'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['0-2 Years'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['3-5 Years'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['6-10 Years'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['>10 Years'] . '" />';
            $dataset_5 .= '<set value="' . $sub_arr['Unknown'] . '" />';
            $counter++;
        }
        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $dataset_5 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .= $dataset_5;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /**
     * Refrigerators Freezers Utilization
     */
    public function refrigeratorsFreezersUtilizationAction() {
        $search_form = new Form_ReportsSearch();
        $main_heading = "Refrigerators/freezers utilization";
        $str_sub_heading = "";
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxRefrigeratorsFreezersUtilization
     */
    public function ajaxRefrigeratorsFreezersUtilizationAction() {
        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['facility_type'] = $this->_request->getParam('facility_type', '');
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;
        $data_arr = $ccm_models->refrigeratorFreezersUtilizationGraph();
        $main_heading = "Refrigerators/freezers utilization";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="In Use" >';
        $dataset_2 = '<dataset seriesname="In Store" >';
        $dataset_3 = '<dataset seriesname="Not Used" >';
        $dataset_4 = '<dataset seriesname="Unknown" >';
        $counter = 0;
        foreach ($data_arr as $sub_arr) {
            if ($counter >= 10) {
                break;
            }
            $categories .='<category label="' . $sub_arr['ccm_model_name'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['inUse'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['inStore'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['notUsed'] . '" />';
            $dataset_4 .= '<set value="' . $sub_arr['Unknown'] . '" />';
            $counter++;
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';
        $dataset_4 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;
        $xmlstore .= $dataset_4;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /*
     * Following 2 Graphs are Under the Cold Boxes Sub-menu
     */

    /**
     * Coldbox And Vaccine Carriers By Working Status
     */
    public function coldboxAndVaccineCarriersByWorkingStatusAction() {
        //ccem proposed list 1.13a (37)
        $search_form = new Form_ReportsSearch();
        $main_heading = "Coldbox And Vaccine Carriers By Working Status";
        $str_sub_heading = "";
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxColdboxAndVaccineCarriersByWorkingStatus
     */
    public function ajaxColdboxAndVaccineCarriersByWorkingStatusAction() {
        //ccem proposed list 1.13a (37)
        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;
        $data_arr = $ccm_models->coldboxAndVaccineCarriersByWorkingStatusGraph();
        $main_heading = "Coldbox And Vaccine Carriers By Working Status";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Working" >';
        $dataset_2 = '<dataset seriesname="Not Working" >';
        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['workingQuantity'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['notWorkingQuantity'] . '" />';
        }
        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '500';
    }

    /**
     * Quantity Of Cold Boxes Carriers
     */
    public function quantityOfColdBoxesCarriersAction() {
        //ccem proposed list 1.14b (39)
        $search_form = new Form_ReportsSearch();
        $main_heading = "Quantity Of Cold Boxes Carriers";
        $this->view->main_heading = $main_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxQuantityOfColdBoxesCarriers
     */
    public function ajaxQuantityOfColdBoxesCarriersAction() {
        //ccem proposed list 1.14b (39)
        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;
        $data_arr = $ccm_models->quantityOfColdBoxesCarriersGraph();
        $main_heading = "Quantity Of Cold Boxes Carriers";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';
        $categories = '<categories>';
        $dataset_min = '<dataset seriesname="Min" >';
        $dataset_max = '<dataset seriesname="Max" >';
        $dataset_avg = '<dataset seriesname="Avg" >';
        $counter = 0;
        foreach ($data_arr as $sub_arr) {
            if ($counter >= 10) {
                break;
            }
            $categories .='<category label="' . $sub_arr['warehouse_type_name'] . '" />';
            $dataset_min .= '<set value="' . $sub_arr['min'] . '" />';
            $dataset_max .= '<set value="' . $sub_arr['max'] . '" />';
            $dataset_avg .= '<set value="' . $sub_arr['avg'] . '" />';
            $counter++;
        }
        $categories .='</categories>';
        $dataset_min .= '</dataset>';
        $dataset_max .= '</dataset>';
        $dataset_avg .= '</dataset>';
        $xmlstore .= $categories;
        $xmlstore .= $dataset_min;
        $xmlstore .= $dataset_max;
        $xmlstore .= $dataset_avg;
        $xmlstore .="</chart>";
        $this->view->xmlstore = $xmlstore;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /*
     * Following 2 Graphs are Under the Generators and Stabilizers Sub-menu
     * Electric Refrigerators EquippedWith Voltage Stabilizers
     */

    public function electricRefrigeratorsEquippedWithVoltageStabilizersAction() {
        //ccem proposed list 1.19 (45)
        $search_form = new Form_ReportsSearch();

        $data_arr = array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );

        $main_heading = "Electric Refrigerators Equipped With Voltage Stabilizers";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxElectricRefrigeratorsEquippedWithVoltageStabilizers
     */
    public function ajaxElectricRefrigeratorsEquippedWithVoltageStabilizersAction() {
        //ccem proposed list 1.19 (45)

        $this->_helper->layout->disableLayout();

        $data_arr = array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );

        $main_heading = "Electric Refrigerators Equipped With Voltage Stabilizers";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
    }

    /**
     * Working Status Of Standby Generator By Model
     */
    public function workingStatusOfStandbyGeneratorByModelAction() {
        //ccem proposed list 1.16 (42)
        $search_form = new Form_ReportsSearch();

        $main_heading = "Working Status Of Standby Generator By Model";
        $str_sub_heading = "";

        $this->view->search_form = $search_form;
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxWorkingStatusOfStandbyGeneratorByModel
     */
    public function ajaxWorkingStatusOfStandbyGeneratorByModelAction() {
        //ccem proposed list 1.16 (42)

        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;
        $data_arr = $ccm_models->standbyGeneratorsByFacilityTypeAndWorkingStatusGraph();

        $main_heading = "Working Status Of Standby Generator By Model";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" exportEnabled="1" rotateValues="1" theme="fint">';

        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Working Well" >';
        $dataset_2 = '<dataset seriesname="Working Needs Service" >';
        $dataset_3 = '<dataset seriesname="Not Working" >';

        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['warehouse_type_name'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['working'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['needs_service'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['not_working'] . '" />';
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';

        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;

        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '500';
    }

    /**
     * Electricity Availability By Fcility Type
     */
    public function electricityAvailabilityByFTypeAction() {
        //ccem graph 4.5.1
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getElectricityAvailabilityByFType();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Electricity availability by facility type Federal Level";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Less than 8hrs/24hrs" value="' . $data_arr['<8hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="8 to 16hrs/24hrs" value="' . $data_arr['8to16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="More than 16hrs/24hrs" value="' . $data_arr['>16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="None" value="' . $data_arr['none'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Energy Availability At Facilities
     */
    public function energyAvailabilityAtFacilitiesAction() {
        //ccem graph 4.5.2
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getElectricityAvailabilityByFType();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Energy availability at facilities";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Less than 8hrs/24hrs" value="' . $data_arr['<8hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="8 to 16hrs/24hrs" value="' . $data_arr['8to16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="More than 16hrs/24hrs" value="' . $data_arr['>16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="None" value="' . $data_arr['none'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Refrigerator By Type
     */
    public function refrigeratorByTypeAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getElectricityAvailabilityByFType();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Refrigerators/freezers by type";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Less than 8hrs/24hrs" value="' . $data_arr['<8hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="8 to 16hrs/24hrs" value="' . $data_arr['8to16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="More than 16hrs/24hrs" value="' . $data_arr['>16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="None" value="' . $data_arr['none'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Working Status By Refrigerators Model
     */
    public function workingStatusByRefrigeratorsModelAction() {
        $search_form = new Form_ReportsSearch();

        $main_heading = "Working status by refrigerators/freezers model ";
        $str_sub_heading = "";

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxWorkingStatusByRefrigeratorsModel
     */
    public function ajaxWorkingStatusByRefrigeratorsModelAction() {
        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['facility_type'] = $this->_request->getParam('facility_type', '');
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;
        $data_arr = $ccm_models->refrigeratorModelsByWorkingStatusGraph();

        $search_form = new Form_ReportsSearch();

        $main_heading = "Working status by refrigerators/freezers model";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" showborder="0" exportEnabled="1" rotateValues="1" theme="fint">';

        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Working Well" >';
        $dataset_2 = '<dataset seriesname="Working Needs Service" >';
        $dataset_3 = '<dataset seriesname="Not Working" >';

        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['Model'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['Working'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['NeedsService'] . '" />';
            $dataset_3 .= '<set value="' . $sub_arr['NotWorking'] . '" />';
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';
        $dataset_3 .= '</dataset>';

        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;
        $xmlstore .= $dataset_3;

        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = "StackedBar2D";
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Refrigerator Models By Age Group
     */
    public function refrigeratorModelsByAgeGroupAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getElectricityAvailabilityByFType();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Refrigerator/freezer models by age group";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Less than 8hrs/24hrs" value="' . $data_arr['<8hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="8 to 16hrs/24hrs" value="' . $data_arr['8to16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="More than 16hrs/24hrs" value="' . $data_arr['>16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="None" value="' . $data_arr['none'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Refrigerator Utilization Pie
     */
    public function refrigeratorUtilizationPieAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getElectricityAvailabilityByFType();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Refrigerator/freezer utilization";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Less than 8hrs/24hrs" value="' . $data_arr['<8hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="8 to 16hrs/24hrs" value="' . $data_arr['8to16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="More than 16hrs/24hrs" value="' . $data_arr['>16hrs/24hrs'] . '"/>';
        $xmlstore .='<set label="None" value="' . $data_arr['none'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Refrigerators Freezers By Type
     */
    public function refrigeratorsFreezersByTypeAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getRefrigeratorsFreezersByType();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Refrigerators/freezers by type";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Chest Refrigerator AC" value="' . $data_arr['ChestRefAC'] . '"/>';
        $xmlstore .='<set label="Chest Refrigerator Electricity and Gas" value="' . $data_arr['ChestRefEleGas'] . '"/>';
        $xmlstore .='<set label="Chest Refrigerator Electricity and Kerosene" value="' . $data_arr['ChestRefEleKerosene'] . '"/>';
        $xmlstore .='<set label="Icelined Refrigerator" value="' . $data_arr['IcelinedRef'] . '"/>';
        $xmlstore .='<set label="Icepack Freezer AC" value="' . $data_arr['IcePackFreezerAC'] . '"/>';
        $xmlstore .='<set label="Icepack Freezer Electricity and Gas" value="' . $data_arr['IcePackFreezerEleGas'] . '"/>';
        $xmlstore .='<set label="Solar Photvoltaic Refrigerator" value="' . $data_arr['SolarPhotvoltaicRef'] . '"/>';
        $xmlstore .='<set label="Upright Refrigerator AC Electricity" value="' . $data_arr['UprightRefACEle'] . '"/>';
        $xmlstore .='<set label="Upright Refrigerator AC Electricity and Gas" value="' . $data_arr['UprightRefACEleGas'] . '"/>';
        $xmlstore .='<set label="Upright Refrigerator AC Electricity and Kerosene" value="' . $data_arr['UprightRefACEleKerosene'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Refrigerators Freezers Models By Age Group Pie
     */
    public function refrigeratorsFreezersModelsByAgeGroupPieAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getRefrigeratorsFreezersModelsByAgeGroupPie();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Refrigerators/freezers models by age group";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="0-5 Years" value="' . $data_arr['05Years'] . '"/>';
        $xmlstore .='<set label="6-10 Years" value="' . $data_arr['610Years'] . '"/>';
        $xmlstore .='<set label="More than 10 Years" value="' . $data_arr['>10Years'] . '"/>';
        $xmlstore .='<set label="Unknown Age" value="' . $data_arr['UnknownAge'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Refrigerators Freezers Utilization Pie
     */
    public function refrigeratorsFreezersUtilizationPieAction() {
        $ccm_warehouse = new Model_CcmWarehouses();
        $data_arr = $ccm_warehouse->getRefrigeratorsFreezersUtilizationPie();
        $search_form = new Form_ReportsSearch();

        $main_heading = "Refrigerators/freezers utilization";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="In Use" value="' . $data_arr['<InUse'] . '"/>';
        $xmlstore .='<set label="In Store" value="' . $data_arr['InStore'] . '"/>';
        $xmlstore .='<set label="Not Used" value="' . $data_arr['NotUsed'] . '"/>';
        $xmlstore .='<set label="Unknown" value="' . $data_arr['Unknown'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Facility Function By Facility Type
     */
    public function facilityFunctionByFacilityTypeAction() {
        //ccem proposed list 1.1 (1)
        $search_form = new Form_ReportsSearch();

        $data_arr = array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );

        $main_heading = "Facility Function By Facility Type";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Mode Of Vaccine Supply By Facility Type
     */
    public function modeOfVaccineSupplyByFacilityTypeAction() {
        //ccem proposed list 1.1 (1)
        $search_form = new Form_ReportsSearch();

        $data_arr = array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );

        $main_heading = "Mode Of Vaccine Supply By Facility Type";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Percentage And Number Of Facilities W Vaccinator
     */
    public function percentageAndNumberOfFacilitiesWVaccinatorAction() {
        //ccem proposed list 1.21 (7)
        $search_form = new Form_ReportsSearch();

        $data_arr = array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );

        $main_heading = "Percentage And Number Of Facilities W/ Vaccinator";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Standby Generator Models By Age Group And Working Status
     */
    public function standbyGeneratorModelsByAgeGroupAndWorkingStatusAction() {
        //ccem proposed list 1.17 (43)
        $search_form = new Form_ReportsSearch();

        $data_arr = array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );

        $main_heading = "Standby Generator Models By Age Group And Working Status";
        $str_sub_heading = "";
        $number_prefix = "";
        $number_suffix = "%";
        $s_number_prefix = "";

        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .= '<chart caption="' . $main_heading . '" subCaption="' . $str_sub_heading . '" numberPrefix="' . $number_prefix . '" numberSuffix="' . $number_suffix . '" sformatNumberScale="1" sNumberPrefix="' . $s_number_prefix . '" syncAxisLimits="1" rotateValues="1" showSum="0" theme="fint">';
        $xmlstore .='<set label="Working Well" value="' . $data_arr['WorkingWell'] . '"/>';
        $xmlstore .='<set label="Working Needs Service" value="' . $data_arr['WorkingNeedsService'] . '"/>';
        $xmlstore .='<set label="Not Working" value="' . $data_arr['NotWorking'] . '"/>';
        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'Pie3D';
        $this->view->width = '80%';
        $this->view->height = '400';
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * Standby Generator Models By Age Group And Working Status
     */
    public function stabilizersByWorkingStatusAction() {
        //ccem proposed list 1.13a (37)
        $search_form = new Form_ReportsSearch();
        $main_heading = "Stabilizers By Working Status";
        $str_sub_heading = "";
        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->search_form = $search_form;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo.js');
    }

    /**
     * ajaxStabilizersByWorkingStatus
     */
    public function ajaxStabilizersByWorkingStatusAction() {
        //ccem proposed list 1.13a (37)
        $this->_helper->layout->disableLayout();
        $ccm_models = new Model_CcmModels();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $ccm_models->form_values = $form_values;

        $data_arr = $ccm_models->stabilizersByWorkingStatusGraph();

        $main_heading = "Stabilizers By Working Status";
        $str_sub_heading = "";
        $number_prefix = "";
        $xmlstore = "<?xml version=\"1.0\"?>";
        $xmlstore .='<chart caption="' . $main_heading . '" numberprefix="' . $number_prefix . '" showvalues="0" showborder="0" exportEnabled="1" rotateValues="1" theme="fint">';

        $categories = '<categories>';
        $dataset_1 = '<dataset seriesname="Working" >';
        $dataset_2 = '<dataset seriesname="Not Working" >';

        foreach ($data_arr as $sub_arr) {
            $categories .='<category label="' . $sub_arr['FacilityType'] . '" />';
            $dataset_1 .= '<set value="' . $sub_arr['workingQuantity'] . '" />';
            $dataset_2 .= '<set value="' . $sub_arr['notWorkingQuantity'] . '" />';
        }

        $categories .='</categories>';
        $dataset_1 .= '</dataset>';
        $dataset_2 .= '</dataset>';

        $xmlstore .= $categories;
        $xmlstore .= $dataset_1;
        $xmlstore .= $dataset_2;

        $xmlstore .="</chart>";

        $this->view->xmlstore = $xmlstore;

        $this->view->main_heading = $main_heading;
        $this->view->str_sub_heading = $str_sub_heading;
        $this->view->chart_type = 'StackedBar2D';
        $this->view->width = '80%';
        $this->view->height = '500';
    }

    /**
     * Comparison Graphs
     */
    public function comparisonGraphsAction() {
        if ($this->_request->isPost()) {
            $post = $this->_request->getPost();
            $graphs = new Model_Graphs();
            $graphs->form_values = $post;
            $optvals = $post['optvals'];
            if (in_array($optvals, array(1, 2, 3))) {
                $xmlstore = $graphs->compGraphOptionYearNational();
            } else if (in_array($optvals, array(7))) {
                $xmlstore = $graphs->compGraphOptionGeoProvincial();
            } else if (in_array($optvals, array(8))) {
                $xmlstore = $graphs->compGraphOptionGeoDistrict();
            }
            $this->view->xmlstore = $xmlstore;
            $this->view->chart_type = $post['ctype'];
            $this->view->sel_indicator = $post['indicators'];
            $this->view->sel_product = $post['products'];          
            $this->view->sel_optvals = $post['optvals'];
            $this->view->sel_period = $post['period'];
            $this->view->sel_year = $post['yearcomp'];
            $this->view->sel_province = $post['all_provinces'];
            $this->view->sel_district = $post['all_districts'];

            if (in_array($optvals, array(2, 3, 7, 8))) {
                $locations = new Model_Locations();
                $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);
                $this->view->combo_provinces = $locations->getLocationsByLevel();
            }

            if (in_array($optvals, array(3, 8))) {
                $location = new Model_Locations();
                $location->form_values = array('province_id' => $post['all_provinces'], 'geo_level_id' => 4);
                $this->view->combo_districts = $location->getLocationsByLevelByProvince();
            }
        } else {
            $this->view->sel_optvals = 1;
        }

        $reports = new Model_Reports();
        $indicators = $reports->getIndicators();
        $this->view->indicators = $indicators;

        $products = new Model_ItemPackSizes();
        $product = $products->getAllItemsNonDil();
        $this->view->product = $product;

        $compare_option = array(
            "Years" => array(
                "1" => "Year - National",
                "2" => "Year - Provincial",
                "3" => "Year - District"
            ),
            "Geographical" => array(
                "7" => "Geographical - Provinical",
                "8" => "Geographical - District"
            )
        );
        $this->view->compare_option = $compare_option;

        $period = new Model_Period();
        $time_intervals = $period->getTimeIntervals();

        $this->view->time_intervals = $time_intervals;
        $this->view->quarter = Model_Period::QUARTER;
        $this->view->halfyear = Model_Period::HALFYEAR;
        $this->view->annual = Model_Period::ANNUAL;

        $years = array();
        for ($i = 2013; $i <= date("Y"); $i++) {
            $years[] = $i;
        }
        $this->view->years = $years;

        $chart_type = array(
            'Line' => "Line",
            'Column2D' => "Bar"
        );
        $this->view->chart_types = $chart_type;
        $this->view->main_heading = "Graph Matching Selected Criteria";
    }

    /**
     * Simple Graphs
     */
    public function simpleGraphsAction() {

        if ($this->_request->isPost()) {
            $post = $this->_request->getPost();
            $graphs = new Model_Graphs();
            $graphs->form_values = $post;
            $optvals = $post['optvals'];
            if (in_array($optvals, array(9, 10, 11))) {
                $xmlstore = $graphs->simpleGraphOptionYearNational();
            }
            $this->view->xmlstore = $xmlstore;
            $this->view->chart_type = $post['ctype'];
            $this->view->sel_indicator = $post['indicators'];
            $this->view->sel_product = $post['products'];
            $this->view->sel_optvals = $post['optvals'];
            $this->view->sel_period = $post['period'];
            $this->view->sel_year = $post['yearcomp'];
            $this->view->sel_province = $post['all_provinces'];
            $this->view->sel_district = $post['all_districts'];

            if (in_array($optvals, array(10, 11))) {
                $locations = new Model_Locations();
                $locations->form_values = array('parent_id' => 10, 'geo_level_id' => 2);
                $this->view->combo_provinces = $locations->getLocationsByLevel();
            }

            if ($optvals == 11) {
                $location = new Model_Locations();
                $location->form_values = array('province_id' => $post['all_provinces'], 'geo_level_id' => 4);
                $this->view->combo_districts = $location->getLocationsByLevelByProvince();
            }
        } else {
            $this->view->sel_optvals = 9;
        }

        $reports = new Model_Reports();
        $indicators = $reports->getIndicators();
        $this->view->indicators = $indicators;

        $products = new Model_ItemPackSizes();
        $product = $products->getAllItemsNonDil();
        $this->view->product = $product;

        $compare_option = array(
            "Geographical" => array(
                "9" => "National",
                "10" => "Provincial",
                "11" => "District"
            )
        );
        $this->view->compare_option = $compare_option;

        $period = new Model_Period();
        $time_intervals = $period->getTimeIntervals();

        $this->view->time_intervals = $time_intervals;
        $this->view->quarter = Model_Period::QUARTER;
        $this->view->halfyear = Model_Period::HALFYEAR;
        $this->view->annual = Model_Period::ANNUAL;

        $years = array();
        for ($i = 2013; $i <= date("Y"); $i++) {
            $years[] = $i;
        }
        $this->view->years = $years;

        $chart_type = array(
            'Line' => "Line",
            'Column2D' => "Bar"
        );
        $this->view->chart_types = $chart_type;
        $this->view->main_heading = "Graph Matching Selected Criteria";
    }

    /**
     * Reported Districts
     */
    public function reportedDistrictsAction() {
        $this->_helper->layout->setLayout("graphs");
        $param = explode('|', base64_decode($this->_request->getParam('param', '')));
        $this->view->param = $param;
    }

}
