<?php

/**
 * Reports_CampaignController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Controller for Reports Campaign
 */
class Reports_CampaignController extends App_Controller_Base {

    /**
     * Reports_CampaignController index
     */
    public function indexAction() {
        // action body
    }

    /**
     * Summary Report
     */
    public function summaryAction() {
        //campaigns 4.3.1
        $this->_helper->layout->setLayout('reports');
        $search_form = new Form_ReportsSearch();

        $this->view->main_heading = "Consumption Reports (Campaign)";
        $this->view->report_title = "Campaigns Summary (Search History)";
        $this->view->headers = 'District,Daily Target,No. of Teams Reported in the evening,0-5 Months,5-59 Months,Recorded NA,Recorded Refusal,Covered NA, Covered Refusal,Number of Mobile-Migratory children covered, Total Coverage';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->search_form = $search_form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/reports-search.js');
    }

    /**
     * Coverage Report
     */
    public function coverageReportAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        if ($this->_request->isPost() && $search_form->isValid($this->_request->getPost())) {
            $campaign_data->form_values = $search_form->getValues();
        }

        $data_arr = $campaign_data->getCoverageReport();

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['daily_target']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['teams_reported']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['target_age_six_months']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['target_age_sixty_months']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['record_not_accessible']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['record_refusal']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['coverage_not_accessible']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['refusal_covered']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['coverage_mobile_children']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['total_coverage']) . "</cell>";
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Consumption Reports (Campaign)";
        $this->view->report_title = "Coverage Report – Campaign Summary";
        $this->view->headers = 'District,Daily Target,No. of Teams Reported in the evening,0-5 Months,5-59 Months,Recorded NA,Recorded Refusal,Covered NA, Covered Refusal,Number of Mobile-Migratory children covered, Total Coverage';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,90,90,90,90,90,90,90,90,90,90';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
    }

    /**
     * Campaign Detail Report
     */
    public function campaignDetailAction() {
        //campaigns 4.3.3
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data1 = new Model_CampaignData();
        $campaigns = new Model_Campaigns();
        if ($this->_request->isPost() && $search_form->isValid($this->_request->getPost())) {
            $campaign_data1->form_values = $search_form->getValues();
            $form_values = $this->_request->getPost();

            $search_form->district_id_hidden->setValue($form_values['combo2_add']);
            $campaigns->form_values['district_id'] = $form_values['combo2_add'];
            $campaign = $form_values['campaign'];
            $campaign_data = $campaigns->getCampaignsByDistrictReport();
        }


        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;


        $data_arr = $campaign_data1->getCampaignDetail();

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['Tehsil'] . "]]></cell>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['UC'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['daily_target']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['teams_reported']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['target_age_six_months']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['target_age_sixty_months']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['record_not_accessible']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['record_refusal']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['coverage_not_accessible']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['refusal_covered']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['coverage_mobile_children']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['total_coverage']) . "</cell>";
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Consumption Reports (Campaign)";
        $this->view->report_title = "Coverage Report – Campaign Detail";
        $this->view->headers = 'Tehsil,UC,Daily Target,No. of Teams Reported in the evening,0-5 Months,5-59 Months,Recorded NA,Recorded Refusal,Covered NA, Covered Refusal,Number of Mobile-Migratory children covered, Total Coverage';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,*,*,*,*,*,*,*,*,*,100,*';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/reports-search.js');
    }

    /**
     * Coverage Catch Up Report
     */
    public function coverageCatchUpAction() {
        //campaigns 4.3.4
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data1 = new Model_CampaignData();
        $campaigns = new Model_Campaigns();
        if ($this->_request->isPost() && $search_form->isValid($this->_request->getPost())) {

            $campaign_data1->form_values = $search_form->getValues();
            $form_values = $this->_request->getPost();
            $search_form->district_id_hidden->setValue($form_values['combo2_add']);
            $campaign = $form_values['campaign'];
            $campaign_data = $campaigns->getCampaignsByDistrictReport();
        }

        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;

        $data_arr = $campaign_data1->getCoverageCatchUp();

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $still_missed_after_campaign = $sub_arr['stillNA'] + $sub_arr['stillRefusal'];
            $still_missed_after_catchup = $sub_arr['coverage_not_accessible'] + $sub_arr['refusal_covered'];
            $unrecorded_missed_children_covered = $still_missed_after_campaign + $still_missed_after_catchup;
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['Tehsil'] . "]]></cell>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['UC'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($still_missed_after_campaign) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['stillNA']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['stillRefusal']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($still_missed_after_catchup) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['coverage_not_accessible']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['refusal_covered']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($unrecorded_missed_children_covered) . "</cell>";
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Consumption Reports (Campaign)";
        $this->view->report_title = "Coverage Report – Catch Up";
        $this->view->headers = 'Tehsil,UC,Still Missed After Campaign Activity,Still NA,Still Refusal,Catch-up Missed Children Covered After Campaign Activity,'
                . 'NA Covered,Refusals Covered,Un-recorded Missed Children Covered';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*';
        $this->view->width = '*,90,90,90,90,90,90,90,90';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/reports-search.js');
    }

    /**
     * lqas
     */
    public function lqasAction() {
        //campaigns 4.3.5
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();

        if ($this->_request->isPost()) {
            $level = $this->_request->getPost('office');
            $province = $this->_request->getPost('combo1');
            $campaign = $this->_request->getPost('campaign');
        } else {
            $level = 1;
            $province = '';
            $campaign = '';
        }

        $campaign_data = new Model_CampaignData();
        $campaign_data->form_values = array('level' => $level, 'province_id' => $province, 'campaign_id' => $campaign);
        $data_arr = $campaign_data->getLQASReport();

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $lots_assessed = $sub_arr['lots_assessed'];
            if (empty($lots_assessed)) {
                $lots_assessed = 0;
            }
            $vaccinatedPer = $sub_arr['vaccinatedPer'];
            if (empty($vaccinatedPer)) {
                $vaccinatedPer = 0;
            }
            $rptDataVaccinatedPer = $sub_arr['rptDataVaccinatedPer'];
            if (empty($rptDataVaccinatedPer)) {
                $rptDataVaccinatedPer = 0;
            }
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . $lots_assessed . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['passed'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['intermediate'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['failed'] . "</cell>";
            $xmlstore .= "<cell>" . $vaccinatedPer . "%</cell>";
            $xmlstore .= "<cell>" . $rptDataVaccinatedPer . "%</cell>";
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Consumption Reports (Campaign)";
        $this->view->report_title = "Lot Quality Assurance Sampling (LQAS) Report";
        if ($level == 1) {
            $this->view->headers = 'Province,Lots Assessed,Passed,Intermediate,Failed,% Children found vaccinated during Spot Survey,% Children vaccinated based on reported data';
        } else {
            $this->view->headers = 'District,Lots Assessed,Passed,Intermediate,Failed,% Children found vaccinated during Spot Survey,% Children vaccinated based on reported data';
        }
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,150,150,150,150,150,150';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;

        $campaigns = new Model_Campaigns();
        if ($level == 1) {
            $campaign_data = $campaigns->getAllCampaignsNational();
        } else {
            $campaigns->form_values['province_id'] = $province;
            $campaign_data = $campaigns->getCampaignsByProvince();
        }

        $this->view->district = '';
        $this->view->level = $level;
        $this->view->province = $province;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/all_level_area_combo.js');
    }

    /**
     * Vaccine Utilization Wastage Report
     */
    public function vaccineUtilizationWastageAction() {
        //campaigns 4.3.5
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        if ($this->_request->isPost() && $search_form->isValid($this->_request->getPost())) {
            $campaign_data->form_values = $search_form->getValues();
        }
        $data_arr = $campaign_data->getLQASReport();

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['FacilityType'] . "]]></cell>";
            $xmlstore .= "<cell>" . $sub_arr['NoOfFacilities'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['Minimum'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['Maximum'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['Mean1'] . "</cell>";
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Consumption Reports (Campaign)";
        $this->view->report_title = "Lot Quality Assurance Sampling (LQAS) Report";
        $this->view->headers = 'Area,Facility Type,No. of Facilities,Minimum,Maximum,Mean';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '120,*,150,150,150,150';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/reports-search.js');
    }

    /**
     * Coverage Missed Children
     */
    public function coverageMissedChildrenAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        $form_values['campaign'] = $campaign_id = $this->_request->getParam('campaign', '');
        $form_values['office'] = $office = $this->_request->getParam('office', '');
        $form_values['combo1'] = $province = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $district = $this->_request->getParam('combo2', '');

        if ($this->_request->isPost() && $search_form->isValid($this->_request->getPost())) {
            $form_values = array_merge($form_values, $search_form->getValues());
            $campaign_data->form_values = $form_values;
            $data_arr = $campaign_data->getCoverageMissedChildren();
        }

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['totalTarget']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['totalCoverage']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['coveragePer'] . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['NA']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['NAPer'] . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['refusal']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['refusalPer'] . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['total']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['totalPer'] . "</cell>";
            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $campaigns = new Model_Campaigns();
        if ($office == 6 && !empty($district)) {
            $campaigns->form_values['districts'] = $district;
            $campaign_data = $campaigns->getCampaignsByDistrictReports();
        } elseif ($office == 2 && !empty($province)) {
            $campaigns->form_values['province_id'] = $province;
            $campaign_data = $campaigns->getCampaignsByProvince();
        } elseif ($office == 1) {
            $campaign_data = $campaigns->getAllCampaignsNational();
        }

        $this->view->main_heading = "Reported Coverage and missed Children";
        $this->view->report_title = "Reported Coverage and missed Children";
        $this->view->headers = 'Province / areas,Total target population of reporting districts,Reported Coverage,#cspan,Reported unvaccinated children,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->headers1 = '#rspan,#rspan,Children vaccinated,#cspan,Children still unvaccinated from recoreded unvaccinated,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->headers2 = '#rspan,#rspan,#rspan,Not available,#cspan,Refusal,#cspan,Total,#cspan';
        $this->view->headers3 = '#rspan,#rspan,n,%,n,% of target,n,% of target,n,% of target';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,150,90,90,90,90,90,90,90,90';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign_id;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo_report_graph.js');
    }

    /**
     * Status Nids
     */
    public function statusNidsAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        $office = $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $campaign = $form_values['campaign_id'] = $this->_request->getParam('campaign', '');
        if ($this->_request->isPost()) {
            $province = $this->_request->getParam('combo1', '');
            $campaign_data->form_values = $form_values;
        } else {
            $office = $form_values['office'] = 1;
            $campaign = '';
            $province = '';
        }
        $data_arr = $campaign_data->getStatusNidsReport();
        $campaigns = new Model_Campaigns();
        if ($office == 1) {
            $campaign_data = $campaigns->getAllCampaignsNational();
        } else {
            $campaigns->form_values['province_id'] = $province;
            $campaign_data = $campaigns->getCampaignsByProvince();
        }
        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['totalTarget']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['totalCoverage']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['coveragePer'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['noOfUc'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['nintyPer'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['seventyPer'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['fityPer'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['thiryPer'] . "</cell>";

            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Status of Campaigns";
        $this->view->report_title = "Status of Campaigns";
        $this->view->headers = 'Location,Target Population,Reported Coverage number,Reported Coverage %,No. of UCs,< 90% Coverage, < 70% Coverage,<50% Coverage,< 30% Coverage';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,110,120,90,90,90,90,90,90';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo_report_graph.js');
    }

    /**
     * Vaccine Utilization And Wastage
     */
    public function vaccineUtilizationAndWastageAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        $campaign = $form_values['campaign_id'] = $this->_request->getParam('campaign_id', '');
        if ($this->_request->isPost()) {

            $campaign_data->form_values = $form_values;
            $search_form->campaign_id->setValue($this->_request->getParam('campaign_id', ''));
            $data_arr = $campaign_data->getVaccineUtilizationAndWastage();
        }

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['vials_given']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['vials_used']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['vials_returned']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['vials_expired']) . "</cell>";

            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Vaccine Utilization and Wastage";
        $this->view->report_title = "Vaccine Utilization and Wastage";
        $this->view->headers = 'Province / areas,Doses given,Doses Used,Doses Returned,Vaccine Wastage';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,150,150,150,150';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';

        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo_report_graph.js');
    }

    /**
     * ajaxGetCampaigns
     */
    public function ajaxGetCampaignsAction() {
        $this->_helper->layout->disableLayout();
        $province = $this->_request->province;
        $district = $this->_request->district;
        $level = $this->_request->level;

        $campaigns = new Model_Campaigns();
        if ($level == 1) {

            $campaign_data = $campaigns->getAllCampaignsNational();
        } elseif ($level == 2) {
            $campaigns->form_values['province_id'] = $province;
            $campaign_data = $campaigns->getCampaignsByProvince();
        } elseif ($level == 6) {
            $campaigns->form_values['districts'] = $district;
            $campaign_data = $campaigns->getCampaignsByDistrictReports();
        } else {
            $campaign_data = $campaigns->getAllCampaigns();
        }

        $this->view->campaign_data = $campaign_data;
    }

    /**
     * Under Performing Uc
     */
    public function underPerformingUcsAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data1 = new Model_CampaignData();

        $form_values['combo1'] = $province = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $campaign = $form_values['campaign_id'] = $this->_request->getParam('campaign', '');
        if ($this->_request->isPost()) {


            $campaign_data1->form_values = $form_values;
            $search_form->combo1->setValue($form_values['combo1']);
            $search_form->district_id_hidden->setValue($form_values['combo2']);
            $campaigns = new Model_Campaigns();
            $campaigns->form_values['province_id'] = $province;
            $campaign_data = $campaigns->getCampaignsByProvince();
            $data_arr = $campaign_data1->getUnderPerformingUcs();
        }

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['warehouse_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['totalTarget']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['vaccinated']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['vaccinatedPer'] . "</cell>";

            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Under-performing UCs";
        $this->view->report_title = "Under-performing UCs";
        $this->view->headers = 'Town,UC,Total Target population for the campaaign,No. of Children Vaccinated,% children vaccinated';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan,#cspan';
        $this->view->width = '*,150,150,150,150';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo_report_graph.js');
    }

    /**
     * Under Performing Districts
     */
    public function underPerformingDistrictsAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        $form_values['office'] = $this->_request->getParam('office', '');
        $form_values['combo1'] = $this->_request->getParam('combo1', '');
        $form_values['combo2'] = $this->_request->getParam('combo2', '');
        $campaign = $form_values['campaign_id'] = $this->_request->getParam('campaign', '');
        if ($this->_request->isPost()) {

            $province = $this->_request->getParam('combo1', '');
            $campaign_data->form_values = $form_values;
            $search_form->combo1->setValue($this->_request->getParam('combo1', ''));
            $data_arr = $campaign_data->getUnderPerformingDistricts();
            $campaigns = new Model_Campaigns();
            $campaigns->form_values['province_id'] = $province;
            $campaign_data = $campaigns->getCampaignsByProvince();
        }

        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['totalTarget']) . "</cell>";
            $xmlstore .= "<cell>" . number_format($sub_arr['vaccinated']) . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['vaccinatedPer'] . "</cell>";

            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Under-performing Districts by Province";
        $this->view->report_title = "Under-performing Districts by Province";
        $this->view->headers = 'District/ agency / town,Total Target population for the campaaign,No. of Children Vaccinated,% children vaccinated';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan,#cspan';
        $this->view->width = '*,150,150,150';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo_report_graph.js');
    }

    /**
     * Under Performing Districts Summary
     */
    public function underPerformingDistrictsSummaryAction() {
        //campaigns 4.3.2
        $this->_helper->layout->setLayout('reports');
        $data_arr = array();
        $search_form = new Form_ReportsSearch();
        $campaign_data = new Model_CampaignData();
        $campaign = $form_values['campaign_id'] = $this->_request->getParam('campaign_id', '');
        if ($this->_request->isPost()) {

            $campaign_data->form_values = $form_values;
            $search_form->campaign_id->setValue($this->_request->getParam('campaign_id', ''));
            $data_arr = $campaign_data->getUnderPerformingDistrictsSummary();
        }
        $xmlstore = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlstore .= "<rows>";

        foreach ($data_arr as $sub_arr) {
            $xmlstore .= "<row>";
            $xmlstore .= "<cell><![CDATA[" . $sub_arr['location_name'] . "]]></cell>";
            $xmlstore .= "<cell>" . $sub_arr['reported'] . "</cell>";
            $xmlstore .= "<cell>" . $sub_arr['less90PerCoverage'] . "</cell>";

            $xmlstore .="</row>";
        }
        $xmlstore .="</rows>";

        $this->view->main_heading = "Under-performing Districts by Province(Summary)";
        $this->view->report_title = "Under-performing Districts by Province";
        $this->view->headers = 'Province/Area,No. of district reported,No. of district with <90% reported coverage';
        $this->view->rspan = '';
        $this->view->cspan = '#cspan,#cspan';
        $this->view->width = '*,350,350';
        $this->view->ro = 'ro,ro,ro,ro,ro,ro,ro,ro,ro,ro';
        $this->view->xmlstore = $xmlstore;
        $this->view->data = $data_arr;
        $this->view->search_form = $search_form;
        $this->view->form_values = $form_values;
        $this->view->campaign_data = $campaign_data;
        $this->view->campaign = $campaign;
        $this->view->inlineScript()->appendFile(Zend_Registry::get('baseurl') . '/js/all_level_area_combo_report_graph.js');
    }

    /**
     * ajaxGetCampaignsByDistrict
     */
    public function ajaxGetCampaignsByDistrictAction() {
        $this->_helper->layout->disableLayout();

        $district = $this->_request->district;
        $campaign = new Model_Campaigns();
        $campaign->form_values['district_id'] = $district;
        $campaign_data = $campaign->getCampaignsByDistrictReport();
        $this->view->campaign_data = $campaign_data;
    }

}
