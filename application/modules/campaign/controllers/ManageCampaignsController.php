<?php

/**
 * Campaign_ManageCampaignsController
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaign
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Controller manages Campaigns
 */
class Campaign_ManageCampaignsController extends App_Controller_Base {

    /**
     * This method searches Campaigns
     */
    public function indexAction() {
        $form = new Form_Campaigns_CampaignSearch();
        $campaigns = new Model_Campaigns();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $campaigns->form_values = $form->getValues();
                $campaign_name = $form->getValue('campaign_name');
                $campaign_type_id = $form->getValue('campaign_type_id');
                $item_id = $form->getValue('item_id');
            }
            $form->campaign_name->setValue($campaign_name);
            $form->campaign_type_id->setValue($campaign_type_id);
            $form->item_id->setValue($item_id);

            $campaigns->form_values['campaign_name'] = $campaign_name;
            $campaigns->form_values['campaign_type_id'] = $campaign_type_id;
            $campaigns->form_values['item_id'] = $item_id;
        }

        $sort = $this->_getParam("sort", "DESC");
        $order = $this->_getParam("order", "pk_id");

        $result = $campaigns->getAllCampaigns($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;
        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
    }

    /**
     * This method adds Campaigns
     */
    public function addCampaignAction() {
        $disticts = array();
        $form = new Form_Campaigns_AddCampaign();
        $campaigns = new Model_Campaigns();
        $all_districts = $campaigns->allDistricts();
        $action = 'add-campaign';
        $btn_txt = 'Add New Campaign';
        $page_heading = 'Add New Campaign';
        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $district_ids = $this->_request->getParam('district_id', '');
            $campaigns->form_values = $this->_request->getPost();
            $campaigns->form_values['district_ids'] = $district_ids;
            $last_id = $campaigns->addCampaign();
            $doEdit = App_Controller_Functions::encrypt('edit|' . $last_id);
            $this->redirect("/campaign/manage-campaigns/add-campaign?id=$doEdit");
        }

        $id = $this->_request->getParam('id', '');

        if (!empty($id)) {
            $arr = explode('|', App_Controller_Functions::decrypt($id));
            $action = $arr[0];
            $id = $arr[1];

            $campaign = $this->_em->getRepository("Campaigns")->find($id);
            $form->campaign_type_id->setValue($campaign->getCampaignType()->getPkId());
            $form->campaign_name->setValue($campaign->getCampaignName());

            $form->date_from->setValue($campaign->getDateFrom()->format('d/m/Y'));
            $form->date_to->setValue($campaign->getDateTo()->format('d/m/Y'));
            $form->catch_up_days->setValue($campaign->getCatchUpDays());
            $form->campaign_id->setValue($campaign->getPkId());
            $campaign_ips = $this->_em->getRepository("CampaignItemPackSizes")->findBy(array('campaign' => $id));
            foreach ($campaign_ips as $cam_ips) {
                $arr_ips[] = $cam_ips->getItemPackSize()->getPkId();
            }

            $form->item_id->setValue($arr_ips);
            $campaign_districts = $this->_em->getRepository("CampaignDistricts")->findBy(array('campaign' => $id));
            foreach ($campaign_districts as $cam_dis) {
                $disticts[] = $cam_dis->getDistrict()->getPkId();
            }

            $this->view->disticts = (count($disticts) > 0) ? implode(',', $disticts) : "";
            $form->campaign_update_id->setValue($id);
            $action = 'update-campaign';
            $btn_txt = 'Update Campaign';
            $page_heading = 'Update Campaign';
            $campaigns = new Model_Campaigns();
            $campaigns->form_values['campaign_id'] = $id;
            $district_data = $campaigns->allDistrictsData();
            $this->view->district_data = $district_data;
        }
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/iadmin/manage-stakeholders/ajax-get-items.js');

        $this->view->inlineScript()->appendFile($base_url . '/js/jquery.multi-select.min.js');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/css/select.css');
        $this->view->headLink()->appendStylesheet($base_url . '/common/theme/css/multiselect.css');
        $this->view->form = $form;

        $this->view->action = $action;
        $this->view->btn_txt = $btn_txt;
        $this->view->page_heading = $page_heading;
        $this->view->all_districts = $all_districts;
    }

    /**
     * ajaxEdit
     */
    public function ajaxEditAction() {
        $this->_helper->layout->disableLayout();
        $id = $this->_request->getParam('id', '');
        $campaigns = $this->_em->find('Campaigns', $id);
        $form = new Form_Campaigns_AddCampaign();

        $form->campaign_name->setValue($campaigns->getCampaignName());
        $form->campaign_type_id->setValue($campaigns->getCampaignType());
        $this->view->form = $form;
    }

    /**
     * This method updates Campaigns
     */
    public function updateCampaignAction() {
        if ($this->_request->getPost()) {

            $form_values = $this->_request->getPost();
            $id = $form_values['campaign_update_id'];
            $campaign = $this->_em->getRepository("Campaigns")->find($id);
            $campaign->setCampaignName($form_values['campaign_name']);
            $campaign->setDateFrom(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_from'])));
            $campaign->setDateTo(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_to'])));
            $campaign->setCatchUpDays($form_values['catch_up_days']);
            $campaign_type_id = $this->_em->find('CampaignTypes', $form_values['campaign_type_id']);
            $campaign->setCampaignType($campaign_type_id);
            $created_by = $this->_em->find('Users', $this->_userid);

            $campaign->setCreatedBy($created_by);
            $campaign->setCreatedDate(App_Tools_Time::now());
            $campaign->setModifiedBy($created_by);
            $campaign->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($campaign);
            $this->_em->flush();
            $last_id = $campaign->getPkId();
            $campaign_id = $this->_em->find('Campaigns', $last_id);
            $campaign_ip = $this->_em->getRepository("CampaignItemPackSizes")->findBy(array('campaign' => $id));

            foreach ($form_values['item_id'] as $item_id):

                $campaign_ips = $this->_em->getRepository("CampaignItemPackSizes")->find($campaign_ip['0']->getPkId());
                $campaign_ips->setCampaign($campaign_id);
                $item_id_ips = $this->_em->find('ItemPackSizes', $item_id);
                $campaign_ips->setItemPackSize($item_id_ips);

                $campaign_ips->setModifiedBy($created_by);
                $campaign_ips->setModifiedDate(App_Tools_Time::now());

                $this->_em->persist($campaign_ips);
                $this->_em->flush();
            endforeach;

            $campaign_di = $this->_em->getRepository("CampaignDistricts")->findBy(array('campaign' => $id));

            foreach ($campaign_di as $cam_dis) {

                $campaign_dis = $this->_em->getRepository("CampaignDistricts")->find($cam_dis->getPkId());

                $this->_em->remove($campaign_dis);
                $this->_em->flush();
            }

            foreach ($form_values['districts'] as $district_id):
                $campaign_d = new CampaignDistricts();
                $campaign_d->setCampaign($campaign_id);
                $district_location_id = $this->_em->find('Locations', $district_id);
                $campaign_d->setDistrict($district_location_id);

                $campaign_d->setCreatedBy($created_by);
                $campaign_d->setCreatedDate(App_Tools_Time::now());
                $campaign_d->setModifiedBy($created_by);
                $campaign_d->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($campaign_d);
                $this->_em->flush();
            endforeach;

            $this->redirect("/campaign/manage-campaigns/index?success=1");
        }
    }

    /**
     * ajaxForCampaign
     */
    public function ajaxForCampaignAction() {
        $this->_helper->layout->disableLayout();
        $campaign_type_id = $this->_request->getParam('campaign_type_id', '');
        $item_id = $this->_request->getParam('item_id', $this->_request->getPost('item_id'));
        if ($item_id && is_array($item_id)) {
            $item_id = implode(',', $item_id);
        }

        $date_from = $this->_request->getParam('date_from', '');
        $date_to = $this->_request->getParam('date_to', '');
        $province_id = $this->_request->getParam('province_id', '');
        $dist_id = $this->_request->getParam('dist_id', '');
        $campaign_id = $this->_request->getParam('campaign_id', '');
        $day = $this->_request->getParam('day', '');
        $wh_id = $this->_request->getParam('wh_id', '');
        $provinces = $this->_request->getParam('provinces', '');
        $district_id = $this->_request->getParam('district_id', '');

        $condition = $this->_request->getParam('condition', '');
        $show_all = $this->_request->getParam('show_all', '');

        $campaign = new Model_Campaigns();

        // Create Campaign Name
        if (!empty($campaign_type_id)) {
            $campaign->form_values['campaign_type_id'] = $campaign_type_id;
            $campaign->form_values['date_from'] = $date_from;
            $campaign->form_values['item_IDs'] = $item_id;

            //Get Product Names
            $pro_names = $campaign->getCampaignVccNames();
            //Get campaign type name
            $camp_type_name = $campaign->getCompaignTypeName();
            $campaign_name = $camp_type_name[0]['camapignTypeName'] . ' ' . $pro_names[0]['itemNames'] . ' ' . date('d M Y', strtotime(App_Controller_Functions::dateToDbFormat($date_from))) . '- ' . date('d M Y', strtotime(App_Controller_Functions::dateToDbFormat($date_to)));

            $this->view->condition = "001";
            $this->view->campaign_name = $campaign_name;
        }

        // Get districts of the selected province
        if ($condition == "002") {
            $campaign->form_values['province_id'] = $province_id;
            $campaign->form_values['campaign_id'] = $campaign_id;
            $districts = $campaign->getCampaignDistricts();

            $this->view->condition = "002";
            $this->view->dist_id = $dist_id;
            $this->view->districts = $districts;
        }

        // Get Warehouses for the selected campaigns
        if ($condition == "003") {
            $campaign->form_values['campaign_id'] = $campaign_id;
            $campaign->form_values['district_id'] = $district_id;
            $campaign->form_values['campaign_day'] = $day;
            $all_warehouses = $campaign->getCampaignUCsForDataEntry();

            $this->view->condition = "003";
            $this->view->wh_id = $wh_id;
            $this->view->all_warehouses = $all_warehouses;
        }

        // Show campaings for the selected District
        if (!empty($campaign_id) && !empty($district_id) && empty($day)) {
            $campaign->form_values['campaign_id'] = $campaign_id;
            $campaign->form_values['district_id'] = $district_id;
            $all_campaigns = $campaign->getAllCampaigns();

            $this->view->condition = "004";
            $this->view->campaign_id = $campaign_id;
            $this->view->all_campaigns = $all_campaigns;
        }

        // Show campaign items for the selected Campaign
        if ($condition == "005") {
            $campaign->form_values['campaign_id'] = $campaign_id;
            $campaign->form_values['item_id'] = $item_id;
            $all_campaigns = $campaign->campaignItems();

            $this->view->condition = "005";
            $this->view->item_id = $item_id;
            $this->view->campaign_id = $campaign_id;
            $this->view->all_campaigns = $all_campaigns;
        }

        // Get dates for the selected campaigns
        if ($condition == "006") {
            $campaign->form_values['campaign_id'] = $campaign_id;
            $campaign->form_values['campaign_day'] = $day;

            $campaign_days = $campaign->getCampaignDays();
            $catch_up_days = $campaign_days[0]['catchUpDays'] + 1;


            $end_date = date('Y-m-d', strtotime("$catch_up_days days", strtotime($campaign_days[0]['dateTo'])));

            $start_date = $campaign_days[0]['dateFrom'];

            $begin = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            $this->view->condition = "006";
            $this->view->day = $day;
            $this->view->show_all = $show_all;
            $this->view->period = $period;
        }

        // Get Provinces of the selected campaign
        if ($condition == "007") {
            $campaign->form_values['campaign_id'] = $campaign_id;
            $campaign->form_values['province_id'] = $province_id;
            $provinces = $campaign->getCampaignProvinces();
            $this->view->condition = "007";
            $this->view->province_id = $province_id;
            $this->view->provinces = $provinces;
        }
    }

    /**
     * ajaxForCampaignUc
     */
    public function ajaxForCampaignUcAction() {
        $this->_helper->layout->disableLayout();
        $campaign_type_id = $this->_request->getParam('campaign_type_id', '');
        $item_id = $this->_request->getParam('item_id', $this->_request->getPost('item_id'));
        if ($item_id && is_array($item_id)) {
            $item_id = implode(',', $item_id);
        }
        $date_from = $this->_request->getParam('date_from', '');
        $province_id = $this->_request->getParam('province_id', '');
        $province_id = (!empty($province_id)) ? $province_id : $this->_identity->getProvinceId();
        $campaign_id = $this->_request->getParam('campaign_id', '');
        $day = $this->_request->getParam('day', '');
        $wh_id = $this->_request->getParam('wh_id', '');
        $district_id = $this->_request->getParam('district_id', '');

        $condition = $this->_request->getParam('condition', '');

        $campaign = new Model_Campaigns();

        // Create Campaign Name
        if (!empty($campaign_type_id)) {
            $campaign->form_values['campaign_type_id'] = $campaign_type_id;
            $campaign->form_values['date_from'] = $date_from;
            $campaign->form_values['item_IDs'] = $item_id;

            //Get Product Names
            $pro_names = $campaign->getCampaignVccNames();
            //Get campaign type name
            $camp_type_name = $campaign->getCompaignTypeName();
            $campaign_name = $camp_type_name[0]['camapignTypeName'] . ' ' . $pro_names[0]['itemNames'] . ' ' . date('d M Y', strtotime(App_Controller_Functions::dateToDbFormat($date_from)));

            $this->view->condition = "001";
            $this->view->campaign_name = $campaign_name;
        }


        // Get Warehouses for the selected campaigns
        if ($condition == "003") {
            $campaign->form_values['campaign_id'] = $campaign_id;
            $campaign->form_values['district_id'] = $district_id;
            $campaign->form_values['campaign_day'] = $day;
            $all_warehouses = $campaign->getCampaignUCsForReadiness();

            $this->view->condition = "003";
            $this->view->wh_id = $wh_id;
            $this->view->all_warehouses = $all_warehouses;
        }
    }

    /**
     * Data Entry History
     */
    public function dataEntryHistoryAction() {
        $form = new Form_Campaigns_DataEntrySearch();
        $campaigns = new Model_Campaigns();
        $stakeholder_id = $this->_identity->getStakeholderId();
        $geoLevelId = $this->_identity->getGeoLevelId($stakeholder_id);

        $campaigns->form_values['province_id'] = ($this->_identity->getProvinceId() != 10) ? $this->_identity->getProvinceId() : '';
        $dist_id = $this->_identity->getDistrictId($this->_identity->getIdentity());

        $district_id = $this->_request->getParam('district_id', '');
        $district_id = (!empty($district_id)) ? $district_id : $dist_id;

        $day = $this->_request->getParam('day');
        $campaign_id = $this->_request->getParam('campaign_id');
        $province_id = $this->_request->getParam('province_id');
        $item_id = $this->_request->getParam('item_id');
        $id = $this->_request->getParam('id', '');
        $type = $this->_request->getParam('type', '');

        if (!empty($district_id) && !empty($day) && !empty($campaign_id)) {
            $campaigns->form_values['province_id'] = $province_id;
            $campaigns->form_values['district_id'] = $district_id;
            $campaigns->form_values['campaign_day'] = $day;
            $campaigns->form_values['campaign_id'] = $campaign_id;
            $campaigns->form_values['item_id'] = $item_id;
            $campaign_data = $campaigns->getCampaignEnteredData();

            $sort = $this->_getParam("sort", "asc");
            $order = $this->_getParam("order", "campaign_name");

            $this->view->paginator = $campaign_data;
            $this->view->sort = $sort;
            $this->view->order = $order;
        }

        if (isset($id) && !empty($id)) {
            $arr = explode('|', App_Controller_Functions::decrypt($id));

            $action = $arr[0];
            $id = $arr[1];
            $campaign_day = $arr[2];
            $campaign_id = $arr[3];
            $district_id = $arr[4];
            $item_id = $arr[5];

            $campaigns->form_values['campaign_id'] = $id;
            if ($action == 'del') {
                $campaigns->deleteCampaignData();
                $url = 'campaign/manage-campaigns/data-entry-history?district_id=' . $district_id . '&campaign_id=' . $campaign_id . '&item_id=' . $item_id . '&day=' . $campaign_day;
                $this->_redirect($url);
                return;
            }
        }

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $campaigns->form_values = $form->getValues();

            $campaign_id = $form->getValue('campaign_id');
            $province_id = $form->getValue('province_id');
            $district_id = $form->getValue('district_id');
            $item_id = $form->getValue('item_id');
            $day = $form->getValue('day');
        }

        $form->campaign_id->setValue($campaign_id);
        $form->province_id->setValue($province_id);
        $form->district_id->setValue($district_id);
        $form->item_id->setValue($item_id);
        $form->day->setValue($day);

        $this->view->campaign_user_type = Model_Roles::CAMPAIGN;
        if ($this->_identity->getStakeholderId() != 40) {
            $this->view->warehouse_id = $this->_identity->getWarehouseId();
        } else {
            $this->view->warehouse_id = "";
        }
        $this->view->user_level = $this->_identity->getUserLevel($this->_userid);
        $this->view->role_id = $this->_identity->getRoleId();
        $this->view->district_id = $district_id;
        $this->view->day = $day;
        $this->view->campaign_id = $campaign_id;
        $this->view->province_id = $province_id;
        $this->view->item_id = $item_id;
        $this->view->page = $geoLevelId;
        $this->view->form = $form;
        $this->view->action = $type;
    }

    /**
     * New Data Entry
     */
    public function newDataEntryAction() {
        $notify_text = "";
        $order = "";
        $sort = "";
        $campaign_data = array();
        $form = new Form_Campaigns_NewDataEntry();
        $campaigns = new Model_Campaigns();
        $campaign_user_type = Model_Roles::CAMPAIGN;
        $role_id = $this->_identity->getRoleId();

        $warehouse_id = $this->_identity->getWarehouseId();
        $wh_id = $this->_request->getParam('wh_id', '');
        $wh_id = (!empty($wh_id)) ? $wh_id : $warehouse_id;
        $dist_id = $this->_identity->getDistrictId($this->_identity->getIdentity());
        $district_id = $this->_request->getParam('district_id', '');
        $district_id = (!empty($district_id)) ? $district_id : $dist_id;
        $campaigns->form_values['district_id'] = $district_id;
        $day = $this->_request->getParam('day', '');
        $campaign_id = $this->_request->getParam('campaign_id', '');
        $province_id = $this->_request->getParam('province_id', '');
        $itm = $this->_request->getParam('itm', '');
        $itm_id = $this->_request->getParam('item_id', '');
        $id = $this->_request->getParam('id');
        $form_values = $this->_request->getPost();
        $campaign_day = $form_values['campaign_day'];


        $action = 'add';
        $btn_txt = 'Save';
        $page_heading = 'Data Entry Control Room';

        // Edit Campaign
        if (isset($id) && !empty($id)) {
            $arr = explode('|', App_Controller_Functions::decrypt($id));
            $action = $arr[0];
            $id = $arr[1];

            $campaigns->form_values['campaign_id'] = $id;

            if ($action == 'view' || $action == 'edit') {
                if ($role_id == $campaign_user_type && empty($wh_id)) {
                    $page_heading = 'Data Entry Control Room';
                } elseif ($action == 'view') {
                    $page_heading = 'View Details Data Entry Control Room';
                } elseif ($action == 'edit') {
                    $page_heading = 'Update Data Entry Control Room';
                }
                $btn_txt = 'Update';

                if (isset($day)) {
                    $campaigns->form_values['campaign_id'] = $campaign_id;
                    $campaigns->form_values['campaign_day'] = $day;
                    $campaigns->form_values['district_id'] = $district_id;
                    $campaigns->form_values['wh_id'] = $wh_id;
                }
                $campaignData = $campaigns->getCampaignDataEntry();

                $form->campaign_id->setValue($campaignData[0]['campaign_id']);
                $form->getElement('campaign_id')->setAttrib('disabled', 'disabled');
                $form->item_id->setValue($campaignData[0]['item_pack_size_id']);
                $form->getElement('item_id')->setAttrib('disabled', 'disabled');
                $form->wh_id->setValue($campaignData[0]['warehouse_id']);
                $form->getElement('wh_id')->setAttrib('readonly', 'readonly');
                $form->wh_name->setValue($campaignData[0]['warehouse_name']);
                $form->getElement('wh_name')->setAttrib('readonly', 'readonly');
                $form->campaign_day->setValue($campaignData[0]['campaign_day']);
                $form->getElement('campaign_day')->setAttrib('disabled', 'disabled');
                $form->daily_target->setValue($campaignData[0]['daily_target']);
                $form->household_visited->setValue($campaignData[0]['household_visited']);
                $form->multiple_family_household->setValue($campaignData[0]['multiple_family_household']);
                $form->target_age_six_months->setValue($campaignData[0]['target_age_six_months']);
                $form->target_age_sixty_months->setValue($campaignData[0]['target_age_sixty_months']);
                $form->total_coverage->setValue($campaignData[0]['total_coverage']);
                $form->refusal_covered->setValue($campaignData[0]['refusal_covered']);
                $form->coverage_mobile_children->setValue($campaignData[0]['coverage_mobile_children']);
                $form->coverage_not_accessible->setValue($campaignData[0]['coverage_not_accessible']);
                $form->record_not_accessible->setValue($campaignData[0]['record_not_accessible']);
                $form->record_refusal->setValue($campaignData[0]['record_refusal']);
                $form->reported_with_weakness->setValue($campaignData[0]['reported_with_weakness']);
                $form->zero_dose->setValue($campaignData[0]['zero_doses']);
                $form->teams_reported->setValue($campaignData[0]['teams_reported']);
                $form->inaccessible_coverage->setValue($campaignData[0]['inaccessible_coverage']);
                $form->vials_given->setValue($campaignData[0]['vials_given']);
                $form->vials_used->setValue($campaignData[0]['vials_used']);
                $form->vials_expired->setValue($campaignData[0]['vials_expired']);
                $form->vials_returned->setValue($campaignData[0]['vials_returned']);
                $form->recon_syr_wasted->setValue($campaignData[0]['recon_syr_wasted']);
                $form->ad_syr_wasted->setValue($campaignData[0]['ad_syr_wasted']);

                if ($action == 'view') {
                    $form->getElement('campaign_id')->setAttrib('disabled', 'disabled');
                    $form->getElement('item_id')->setAttrib('disabled', 'disabled');
                    $form->getElement('wh_id')->setAttrib('readonly', 'readonly');
                    $form->getElement('wh_name')->setAttrib('readonly', 'readonly');
                    $form->getElement('campaign_day')->setAttrib('disabled', 'disabled');
                    $form->getElement('daily_target')->setAttrib('readonly', 'readonly');
                    $form->getElement('household_visited')->setAttrib('readonly', 'readonly');
                    $form->getElement('multiple_family_household')->setAttrib('readonly', 'readonly');
                    $form->getElement('target_age_six_months')->setAttrib('readonly', 'readonly');
                    $form->getElement('target_age_sixty_months')->setAttrib('readonly', 'readonly');
                    $form->getElement('total_coverage')->setAttrib('readonly', 'readonly');
                    $form->getElement('refusal_covered')->setAttrib('readonly', 'readonly');
                    $form->getElement('coverage_mobile_children')->setAttrib('readonly', 'readonly');
                    $form->getElement('coverage_not_accessible')->setAttrib('readonly', 'readonly');
                    $form->getElement('record_not_accessible')->setAttrib('readonly', 'readonly');
                    $form->getElement('record_refusal')->setAttrib('readonly', 'readonly');
                    $form->getElement('reported_with_weakness')->setAttrib('readonly', 'readonly');
                    $form->getElement('zero_dose')->setAttrib('readonly', 'readonly');
                    $form->getElement('teams_reported')->setAttrib('readonly', 'readonly');
                    $form->getElement('inaccessible_coverage')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_given')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_used')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_expired')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_returned')->setAttrib('readonly', 'readonly');
                    $form->getElement('recon_syr_wasted')->setAttrib('readonly', 'readonly');
                    $form->getElement('ad_syr_wasted')->setAttrib('readonly', 'readonly');
                }
            } else if ($action == 'del') {
                $campaigns->deleteCampaignData();

                $cm_id = $arr[2];
                $cm_day = $arr[3];

                $notify = 'delete_success';
                $this->_redirect("/campaign/manage-campaigns/new-data-entry?notify=$notify&campaign_id=$cm_id&day=$cm_day");
                return;
            }
        }

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $campaigns->form_values = $form->getValues();

            $date = App_Controller_Functions::dateToDbFormat($form->getValue('hdn_campaign_date'));
            $date = date_create($date);
            // If new campaign
            if ($action == 'add') {
                $campaigns->form_values['district_id'] = $district_id;
                $campaigns->addCampaignData();
                $notify = 'add_success';
            }
            // If campaign update
            else if ($action == 'edit') {
                if (!empty($id)) {
                    $campaigns->form_values['campaign_pk_id'] = $id;
                }

                $campaigns->form_values['district_id'] = $district_id;
                $campaigns->form_values['campaign_pk_id'] = $id;
                $campaigns->form_values['modified_by'] = $this->_identity->getIdentity();
                $campaigns->form_values['campaign_id'] = $campaign_id;
                $campaigns->form_values['campaign_day'] = $day;
                $campaigns->form_values['item_id'] = $itm_id;
                $campaigns->form_values['modified_date'] = date('Y-m-d');
                $campaigns->updateCampaignData();

                $notify = 'update_success';
            }
            $this->_redirect("/campaign/manage-campaigns/new-data-entry?notify=$notify&campaign_id=$campaign_id&day=$campaign_day");
            return;
        }
        // Get Data for Listing Grid
        if (isset($day) && $day != 'all' && !empty($day) && isset($campaign_id)) {
            $campaigns->form_values['district_id'] = (isset($district_id)) ? $district_id : $dist_id;
            $campaigns->form_values['campaign_id'] = $campaign_id;
            $campaigns->form_values['campaign_day'] = $day;
            $campaign_data = $campaigns->getCampaignEnteredData();
        }

        // Paginate the contest results
        $paginator = Zend_Paginator::factory($campaign_data);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->notify_text = $notify_text;
        $this->view->action = $action;
        $this->view->btn_txt = $btn_txt;
        $this->view->page_heading = $page_heading;

        $this->view->campaign_user_type = $campaign_user_type;
        $this->view->role_id = $role_id;
        $this->view->district_id = $district_id;
        $this->view->day = $day;
        $this->view->campaign_id = $campaign_id;
        $this->view->province_id = $province_id;
        $this->view->itm = $itm;
        $this->view->wh_id = $wh_id;
        $this->view->id = $id;

        $this->view->form = $form;
        $this->view->paginator = $campaign_data;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
    }

    /**
     * This method Edits Data Entry
     */
    public function editDataEntryAction() {

        $notify_text = "";
        $order = "";
        $sort = "";
        $campaign_data = array();

        $form = new Form_Campaigns_NewDataEntry();

        $campaigns = new Model_Campaigns();


        $campaign_user_type = Model_Roles::CAMPAIGN;
        $role_id = $this->_identity->getRoleId();

        $warehouse_id = $this->_identity->getWarehouseId();
        $wh_id = $this->_request->getParam('wh', '');
        $wh_id = (!empty($wh_id)) ? $wh_id : $warehouse_id;
        $dist_id = $this->_identity->getDistrictId($this->_identity->getIdentity());
        $district_id = $this->_request->getParam('district_id', '');
        $district_id = (!empty($district_id)) ? $district_id : $dist_id;
        $campaigns->form_values['district_id'] = $district_id;
        $day = $this->_request->getParam('day', '');
        $campaign_id = $this->_request->getParam('campaign_id', '');
        $province_id = $this->_request->getParam('province_id', '');
        $itm = $this->_request->getParam('itm', '');
        $itm_id = $this->_request->getParam('item_id', '');
        $id = $this->_request->getParam('id');
        $type = $this->_request->getParam('type');

        $campaign_day = $this->_request->getParam('day');
        $action = 'add';
        $btn_txt = 'Save';
        $page_heading = 'Data Entry Control Room';

        // Edit Campaign
        if (isset($id) && !empty($id)) {
            $arr = explode('|', App_Controller_Functions::decrypt($id));
            $action = $arr[0];
            $id = $arr[1];


            $campaigns->form_values['campaign_id'] = $id;

            if ($action == 'view' || $action == 'edit') {
                if ($role_id == $campaign_user_type && empty($wh_id)) {
                    $page_heading = 'Data Entry Control Room';
                } elseif ($action == 'view') {
                    $page_heading = 'View Details Data Entry Control Room';
                } elseif ($action == 'edit') {
                    $page_heading = 'Update Data Entry Control Room';
                }
                $btn_txt = 'Update';

                if (isset($day)) {
                    $campaigns->form_values['campaign_id'] = $campaign_id;
                    $campaigns->form_values['campaign_day'] = $day;
                    $campaigns->form_values['district_id'] = $district_id;
                    $campaigns->form_values['wh_id'] = $wh_id;
                }
                $campaignData = $campaigns->getCampaignDataEntry();


                $form->campaign_id->setValue($campaignData[0]['campaign_id']);
                $form->getElement('campaign_id')->setAttrib('disabled', 'disabled');
                $form->item_id->setValue($campaignData[0]['item_pack_size_id']);
                $form->getElement('item_id')->setAttrib('disabled', 'disabled');
                $form->wh_id->setValue($campaignData[0]['warehouse_id']);
                $form->getElement('wh_id')->setAttrib('readonly', 'readonly');
                $form->wh_name->setValue($campaignData[0]['warehouse_name']);
                $form->getElement('wh_name')->setAttrib('readonly', 'readonly');
                $form->campaign_day->setValue($campaignData[0]['campaign_day']);
                $form->getElement('campaign_day')->setAttrib('disabled', 'disabled');
                $form->daily_target->setValue($campaignData[0]['daily_target']);
                $form->household_visited->setValue($campaignData[0]['household_visited']);
                $form->multiple_family_household->setValue($campaignData[0]['multiple_family_household']);
                $form->target_age_six_months->setValue($campaignData[0]['target_age_six_months']);
                $form->target_age_sixty_months->setValue($campaignData[0]['target_age_sixty_months']);
                $form->total_coverage->setValue($campaignData[0]['total_coverage']);
                $form->refusal_covered->setValue($campaignData[0]['refusal_covered']);
                $form->coverage_mobile_children->setValue($campaignData[0]['coverage_mobile_children']);
                $form->coverage_not_accessible->setValue($campaignData[0]['coverage_not_accessible']);
                $form->record_not_accessible->setValue($campaignData[0]['record_not_accessible']);
                $form->record_refusal->setValue($campaignData[0]['record_refusal']);
                $form->reported_with_weakness->setValue($campaignData[0]['reported_with_weakness']);
                $form->zero_dose->setValue($campaignData[0]['zero_doses']);
                $form->teams_reported->setValue($campaignData[0]['teams_reported']);
                $form->inaccessible_coverage->setValue($campaignData[0]['inaccessible_coverage']);
                $form->vials_given->setValue($campaignData[0]['vials_given']);
                $form->vials_used->setValue($campaignData[0]['vials_used']);
                $form->vials_expired->setValue($campaignData[0]['vials_expired']);
                $form->vials_returned->setValue($campaignData[0]['vials_returned']);
                $form->recon_syr_wasted->setValue($campaignData[0]['recon_syr_wasted']);
                $form->ad_syr_wasted->setValue($campaignData[0]['ad_syr_wasted']);

                if ($action == 'view') {
                    $form->getElement('campaign_id')->setAttrib('disabled', 'disabled');
                    $form->getElement('item_id')->setAttrib('disabled', 'disabled');
                    $form->getElement('wh_id')->setAttrib('readonly', 'readonly');
                    $form->getElement('wh_name')->setAttrib('readonly', 'readonly');
                    $form->getElement('campaign_day')->setAttrib('disabled', 'disabled');
                    $form->getElement('daily_target')->setAttrib('readonly', 'readonly');
                    $form->getElement('household_visited')->setAttrib('readonly', 'readonly');
                    $form->getElement('multiple_family_household')->setAttrib('readonly', 'readonly');
                    $form->getElement('target_age_six_months')->setAttrib('readonly', 'readonly');
                    $form->getElement('target_age_sixty_months')->setAttrib('readonly', 'readonly');
                    $form->getElement('total_coverage')->setAttrib('readonly', 'readonly');
                    $form->getElement('refusal_covered')->setAttrib('readonly', 'readonly');
                    $form->getElement('coverage_mobile_children')->setAttrib('readonly', 'readonly');
                    $form->getElement('coverage_not_accessible')->setAttrib('readonly', 'readonly');
                    $form->getElement('record_not_accessible')->setAttrib('readonly', 'readonly');
                    $form->getElement('record_refusal')->setAttrib('readonly', 'readonly');
                    $form->getElement('reported_with_weakness')->setAttrib('readonly', 'readonly');
                    $form->getElement('zero_dose')->setAttrib('readonly', 'readonly');
                    $form->getElement('teams_reported')->setAttrib('readonly', 'readonly');
                    $form->getElement('inaccessible_coverage')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_given')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_used')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_expired')->setAttrib('readonly', 'readonly');
                    $form->getElement('vials_returned')->setAttrib('readonly', 'readonly');
                    $form->getElement('recon_syr_wasted')->setAttrib('readonly', 'readonly');
                    $form->getElement('ad_syr_wasted')->setAttrib('readonly', 'readonly');
                }
            } else if ($action == 'del') {
                $campaigns->deleteCampaignData();
                $notify = 'delete_success';
            }
        }

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $campaigns->form_values = $form->getValues();

            $date = App_Controller_Functions::dateToDbFormat($form->getValue('hdn_campaign_date'));
            $date = date_create($date);
            // If new campaign
            if ($action == 'add') {
                $campaigns->form_values['district_id'] = $district_id;
                $campaigns->addCampaignData();

                $notify = 'add_success';
            }
            // If campaign update 
            else if ($action == 'edit') {
                if (!empty($id)) {

                    $campaigns->form_values['campaign_pk_id'] = $id;
                }
                $campaigns->form_values['district_id'] = $district_id;
                $campaigns->form_values['campaign_pk_id'] = $id;
                $campaigns->form_values['modified_by'] = $this->_identity->getIdentity();
                $campaigns->form_values['campaign_id'] = $campaign_id;
                $campaigns->form_values['campaign_day'] = $day;
                $campaigns->form_values['item_id'] = $itm_id;
                $campaigns->form_values['modified_date'] = date('Y-m-d');
                $campaigns->updateCampaignData();


                $notify = 'update_success';
            }
            $this->_redirect("/campaign/manage-campaigns/new-data-entry?notify=$notify&campaign_id=$campaign_id&day=$campaign_day");

            return;
        }
        //Get Data for Listing Grid
        if (isset($day) && $day != 'all' && !empty($day) && isset($campaign_id)) {
            $campaigns->form_values['district_id'] = (isset($district_id)) ? $district_id : $dist_id;
            $campaigns->form_values['campaign_id'] = $campaign_id;
            $campaigns->form_values['campaign_day'] = $day;
            $campaign_data = $campaigns->getCampaignEnteredData();
        }

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($campaign_data);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->notify_text = $notify_text;
        $this->view->action = $action;
        $this->view->btn_txt = $btn_txt;
        $this->view->page_heading = $page_heading;

        $this->view->type = $type;

        $this->view->campaign_user_type = $campaign_user_type;
        $this->view->role_id = $role_id;
        $this->view->district_id = $district_id;
        $this->view->day = $day;
        $this->view->campaign_id = $campaign_id;
        $this->view->province_id = $province_id;
        $this->view->itm = $itm;
        $this->view->wh_id = $wh_id;
        $this->view->id = $id;

        $this->view->form = $form;
        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
    }

    /**
     * This method gets All Campaigns Targets
     */
    public function campaignsTargetAction() {
        $form = new Form_Campaigns_CampaignTarget();
        $campaigns_targets = new Model_CampaignTargets();
        $locations = new Model_Locations();
        $base_url = Zend_Registry::get('baseurl');

        $show_add_edit = 0;

        $this->view->headScript()->appendFile($base_url . '/js/campaign/manage-campaigns/excellentexport.js');
        if ($this->_request->isPost()) {
            $show_add_edit = 1;

            $form_values = $this->_request->getPost();

            $form->campaign_id->setValue($form_values['campaign_id']);
            $form->province_id_hidden->setValue($form_values['province_id']);
            $form->district_id_hidden->setValue($form_values['district_id']);
            $form->item_id_hidden->setValue($form_values['item_id']);
            $form->campaign_import_hidden->setValue($form_values['campaign_id']);
            $form->province_import_hidden->setValue($form_values['province_id']);
            $form->district_import_hidden->setValue($form_values['district_id']);
            $form->item_import_hidden->setValue($form_values['item_id']);
            $campaigns_targets->form_values = $form_values;
            $result = $campaigns_targets->getAllCampaignTargets();
            $campaign_closed = $campaigns_targets->getAllCampaignClosed();
        }


        $campaign_id = $this->_request->getParam('campaign_id', '');
        $district_id = $this->_request->getParam('district_id', '');
        $province_id = $this->_request->getParam('province_id', '');
        $item_pack_size_id = $this->_request->getParam('item_id', '');
        if (!empty($campaign_id)) {
            $show_add_edit = 1;

            $campaigns_targets->form_values['campaign_id'] = $campaign_id;
            $campaigns_targets->form_values['district_id'] = $district_id;
            $campaigns_targets->form_values['province_id'] = $province_id;
            $campaigns_targets->form_values['item_id'] = $item_pack_size_id;
            $form->campaign_import_hidden->setValue($campaign_id);
            $form->province_import_hidden->setValue($province_id);
            $form->district_import_hidden->setValue($district_id);
            $form->item_import_hidden->setValue($item_pack_size_id);
            $form->campaign_id->setValue($campaign_id);
            $form->province_id_hidden->setValue($province_id);
            $form->district_id_hidden->setValue($district_id);
            $form->item_id_hidden->setValue($item_pack_size_id);
            $locations->form_values['district_id'] = $district_id;
            $export = $campaigns_targets->getAllCampaignTargetUcs();
            $district_name = $locations->getDistrictName();
            $result = $campaigns_targets->getAllCampaignTargets();
            $campaign_closed = $campaigns_targets->getAllCampaignClosed();
        }

        $this->view->form = $form;
        $this->view->paginator = $result;
        $this->view->export = $export;
        $this->view->district_name = $district_name[0]['locationName'];

        $this->view->cam_closed = 0;
        $this->view->show_add_edit = $show_add_edit;
    }

    /**
     * ajaxCampaignsTarge
     */
    public function ajaxCampaignsTargetAction() {
        $this->_helper->layout->setLayout("ajax");

        $form_values = $this->_request->getPost();

        $campaign_targets = new Model_CampaignTargets();
        $campaign_targets->form_values = $form_values;
        $result = $campaign_targets->getAllCampaignTargetUcs();

        $this->view->campaign_id = $form_values['campaign_id'];
        $this->view->province_id = $form_values['province_id'];
        $this->view->district_id = $form_values['district_id'];
        $this->view->item_id = $form_values['item_id'];
        $this->view->result = $result;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/jquery.price_format.1.8.min.js');
    }

    /**
     * ajaxCampaignsName
     */
    public function ajaxCampaignsNameAction() {
        $this->_helper->layout->setLayout("ajax");

        $form_values = $this->_request->getPost();
        $campaign_targets = new Model_CampaignTargets();
        $campaign_targets->form_values = $form_values;
        $result = $campaign_targets->getCampaignNameForLabel();


        $this->view->result = $result;
    }

    /**
     * This method Adds Campaign Target
     */
    public function addCampaignTargetAction() {
        $form_values = $this->_request->getPost();

        foreach ($form_values['warehoue'] as $index => $warehouse_id) {

            $campaign_targets = $this->_em->getRepository("CampaignTargets")->findBy(array('warehouse' => $warehouse_id, 'campaign' => $form_values['campaign_id']));
            if (!empty($campaign_targets)) {

                $cam_target = $this->_em->find('CampaignTargets', $campaign_targets['0']->getPkId());

                $daily_target = $form_values['daily_target'];
                $d_target = str_replace(',', '', $daily_target);
                $cam_target->setDailyTarget($d_target[$index]);

                $item_id_ips = $this->_em->find('ItemPackSizes', $form_values['item_id']);
                $cam_target->setItemPackSize($item_id_ips);
                $created_by = $this->_em->find('Users', $this->_userid);
                $cam_target->setCreatedBy($created_by);
                $cam_target->setModifiedBy($created_by);
                $cam_target->setCreatedDate(App_Tools_Time::now());
                $cam_target->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($cam_target);
                $this->_em->flush();

                if ($daily_target[$index] == 0) {

                    $camp_target = $this->_em->find('CampaignTargets', $campaign_targets['0']->getPkId());
                    $this->_em->remove($camp_target);
                    $this->_em->flush();
                }
            } else {
                $campaign_target = new CampaignTargets();
                $daily_target = $form_values['daily_target'];
                $d_target = str_replace(',', '', $daily_target);
                if ($d_target[$index] != 0) {

                    $campaign_target->setDailyTarget($d_target[$index]);
                    $campaign_id = $this->_em->find('Campaigns', $form_values['campaign_id']);
                    $campaign_target->setCampaign($campaign_id);
                    $warehouse_id_a = $this->_em->find('Warehouses', $warehouse_id);
                    $campaign_target->setWarehouse($warehouse_id_a);
                    $item_id_ips = $this->_em->find('ItemPackSizes', $form_values['item_id']);
                    $campaign_target->setItemPackSize($item_id_ips);

                    $created_by = $this->_em->find('Users', $this->_userid);
                    $campaign_target->setCreatedBy($created_by);
                    $campaign_target->setModifiedBy($created_by);
                    $campaign_target->setCreatedDate(App_Tools_Time::now());
                    $campaign_target->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($campaign_target);
                    $this->_em->flush();
                }
            }
        }

        $this->_redirect("/campaign/manage-campaigns/campaigns-target?campaign_id=$form_values[campaign_id]&district_id=$form_values[district_id]&province_id=$form_values[province_id]&item_id=$form_values[item_id]");
    }

    /**
     * This method Closes Campaigns Target
     */
    public function closeCampaignsTargetAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $id = $this->_request->getParam('id');
        $campaign = $this->_em->find('Campaigns', $id);
        $campaign->setIsClosed('1');
        $created_by = $this->_em->find('Users', $this->_user_id);
        $campaign->setModifiedBy($created_by);
        $campaign->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($campaign);
        $this->_em->flush();
        return;
    }

    /**
     * This method Opens Campaigns Target
     */
    public function openCampaignsTargetAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $id = $this->_request->getParam('id');
        $campaign = $this->_em->find('Campaigns', $id);
        $campaign->setIsClosed('0');
        $created_by = $this->_em->find('Users', $this->_user_id);
        $campaign->setModifiedBy($created_by);
        $campaign->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($campaign);
        $this->_em->flush();
        return;
    }

    /**
     * This method Imports Campaign Target 
     */
    public function campaignTargetImportAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $import_type = $this->_request->getPost('import_option');

        if ($this->_request->isPost() && $this->_request->getPost()) {

            if ($import_type == 'campaign') {
                $form_values = $this->_request->getPost();

                $campaign_id = $form_values['campaign_import_hidden'];

                $campaign_target = new Model_CampaignTargets();
                $campaign_target->form_values = $form_values;
                $data = $campaign_target->getCampaignTargets();

                if (!empty($data)) {
                    $campaign_target->form_values = $form_values;
                    $campaign_target->deleteCampaignData();
                }

                if (!empty($data)) {

                    foreach ($data as $data1) {
                        $campaign_target = new CampaignTargets();
                        $campaign_target->setDailyTarget($data1['dailyTarget']);
                        $campaign_id_a = $this->_em->find('Campaigns', $campaign_id);
                        $campaign_target->setCampaign($campaign_id_a);
                        $warehouse_id_a = $this->_em->find('Warehouses', $data1['warehouseId']);
                        $campaign_target->setWarehouse($warehouse_id_a);
                        $item_id = $this->_em->find('ItemPackSizes', $data1['itemId']);
                        $campaign_target->setItemPackSize($item_id);

                        $created_by = $this->_em->find('Users', $this->_userid);
                        $campaign_target->setCreatedBy($created_by);
                        $campaign_target->setModifiedBy($created_by);
                        $campaign_target->setCreatedDate(App_Tools_Time::now());
                        $campaign_target->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($campaign_target);
                        $this->_em->flush();
                    }
                }
            } else if ($import_type == 'csv') {
                $form_values = $this->_request->getPost();

                $campaign_target = new Model_CampaignTargets();
                $campaign_target->form_values = $form_values;
                $tempFileName = $_FILES['cvs_import']['tmp_name'];
                $i = 1;
                $error = "";
                if (($handle = fopen($tempFileName, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $warehouse_name = $data[0];
                        $target1 = (int) $data[1];
                        $target = abs($target1);
                        $campaign_target->form_values['warehouse_name'] = $warehouse_name;
                        $res = $campaign_target->getOldCampaignTarget();

                        if (!is_numeric($data[1])) {
                            $error .= "Import with Errors";
                        }


                        if ($target != 0) {
                            foreach ($res as $result) {
                                // If data already exists then update
                                if (!empty($result['pk_id'])) {
                                    $cam_target = $this->_em->getRepository("CampaignTargets")->find($result['pk_id']);

                                    $cam_target->setDailyTarget($target);
                                    $created_by = $this->_em->find('Users', $this->_userid);
                                    $cam_target->setModifiedBy($created_by);
                                    $cam_target->setModifiedDate(App_Tools_Time::now());

                                    $this->_em->persist($cam_target);
                                    $this->_em->flush();
                                }
                                // If data not exists then Insert
                                else {
                                    $camp_target = new CampaignTargets();
                                    $campaign = $this->_em->find('Campaigns', $form_values['campaign_import_hidden']);
                                    $camp_target->setCampaign($campaign);
                                    $item = $this->_em->find('ItemPackSizes', $form_values['item_import_hidden']);
                                    $camp_target->setItemPackSize($item);
                                    $warehouse = $this->_em->find('Warehouses', $result['warehouse_id']);
                                    $camp_target->setWarehouse($warehouse);
                                    $camp_target->setDailyTarget($target);
                                    $created_by = $this->_em->find('Users', $this->_userid);
                                    $camp_target->setModifiedBy($created_by);
                                    $camp_target->setModifiedDate(App_Tools_Time::now());
                                    $camp_target->setCreatedBy($created_by);
                                    $camp_target->setCreatedDate(App_Tools_Time::now());

                                    $this->_em->persist($camp_target);
                                    $this->_em->flush();
                                }
                            }
                            $i++;
                        }
                    }
                }
            }
        }

        if (!empty($error)) {
            $success = 0;
        } else {
            $success = 1;
        }
        $this->_redirect("/campaign/manage-campaigns/campaigns-target?campaign_id=$form_values[campaign_import_hidden]&district_id=$form_values[district_import_hidden]&province_id=$form_values[province_import_hidden]&item_id=$form_values[item_import_hidden]&success=$success");
    }

    /**
     * This method gets Campaign Readiness
     */
    public function campaignReadinessAction() {

        $form = new Form_Campaigns_CampaignReadiness();

        $params = array();

        $campaign_readiness = new Model_CampaignReadiness();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $campaignId = $form->getValue('campaign_id');


                if (!empty($campaignId)) {
                    $params['campaignId'] = $campaignId;
                }
            }
        } else {
            $campaignId = $this->_getParam('campaignId');


            if (!empty($campaignId)) {
                $params['campaignId'] = $campaignId;
                $form->$campaign_id->setValue($campaignId);
            }
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "login_id");
        $campaign_readiness->form_values = $params;
        $result = $campaign_readiness->getCampaignReadiness($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->pagination_params = $params;
    }

    /**
     * This method Adds Campaign Readiness
     */
    public function addCampaignReadinessAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $campaign_readiness = new CampaignReadiness();
        $campaign_readiness->setNumTallySheets($form_values['num_tally_sheets']);
        $campaign_readiness->setNumFingerMarkers($form_values['num_finger_markers']);
        $campaign_readiness->setArrivalDateMobilizationMaterial(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['arrival_date_mobiliztion_material'])));
        $campaign_readiness->setDpecMeetingDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['dpec_meeting_date'])));

        if (!empty($form_values['dco_attended_meeting']) && $form_values['dco_attended_meeting'] == 'on') {
            $doc_meeting = 1;
        } else {
            $doc_meeting = 0;
        }
        $campaign_readiness->setDcoAttendedMeeting($doc_meeting);
        if (!empty($form_values['edo_attended_meeting']) && $form_values['edo_attended_meeting'] == 'on') {
            $edo_meeting = 1;
        } else {
            $edo_meeting = 0;
        }

        $campaign_readiness->setEdoAttendedMeeting($edo_meeting);
        if (!empty($form_values['all_members_attended_meeting']) && $form_values['all_members_attended_meeting'] == 'on') {
            $all_meeting = 1;
        } else {
            $all_meeting = 0;
        }
        $campaign_readiness->setAllMembersAttendedMeeting($all_meeting);
        $campaign_readiness->setRemarks($form_values['remarks']);
        $campaign = $this->_em->find('Campaigns', $form_values['campaign_add_id']);
        $campaign_readiness->setCampaign($campaign);

        $district_id = $this->_identity->getDistrictId($this->_identity->getIdentity());

        $district = $this->_em->find('Locations', $district_id);
        $campaign_readiness->setDistrict($district);

        $created_by = $this->_em->find('Users', $this->_user_id);
        $campaign_readiness->setCreatedBy($created_by);
        $campaign_readiness->setModifiedBy($created_by);
        $campaign_readiness->setCreatedDate(App_Tools_Time::now());
        $campaign_readiness->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($campaign_readiness);
        $this->_em->flush();
        $this->_redirect("campaign/manage-campaigns/campaign-readiness");
    }

    /**
     * ajaxEditReadiness
     */
    public function ajaxEditReadinessAction() {
        $this->_helper->layout->setLayout("ajax");

        $campaign_readiness = $this->_em->find('CampaignReadiness', $this->_request->getParam('item_id'));
        $form = new Form_Campaigns_CampaignReadiness();


        $form->campaign_edit_id->setValue($campaign_readiness->getCampaign()->getPkId());
        $province = $this->_em->getRepository("Locations")->findBy(array('district' => $campaign_readiness->getDistrict()->getPkId()));


        $form->province_id_hidden->setValue($province[0]->getProvince()->getPkId());
        $form->district_id_hidden->setValue($campaign_readiness->getDistrict()->getPkId());

        $form->num_tally_sheets->setValue($campaign_readiness->getNumTallySheets());
        $form->num_finger_markers->setValue($campaign_readiness->getNumFingerMarkers());


        $form->arrival_date_mobiliztion_material->setValue($campaign_readiness->getArrivalDateMobilizationMaterial()->format('Y-m-d'));
        $form->dpec_meeting_date->setValue($campaign_readiness->getDpecMeetingDate()->format('Y-m-d'));

        $form->remarks->setValue($campaign_readiness->getRemarks());

        $form->readiness_id->setValue($campaign_readiness->getPkId());

        $this->view->dco_attended_meeting = $campaign_readiness->getDcoAttendedMeeting();
        $this->view->edo_attended_meeting = $campaign_readiness->getEdoAttendedMeeting();
        $this->view->all_members_attended_meeting = $campaign_readiness->getAllMembersAttendedMeeting();

        $this->view->form = $form;
    }

    /**
     * This method Updates Campaign Readiness
     */
    public function updateCampaignReadinessAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $campaign_readiness = $this->_em->find('CampaignReadiness', $form_values['readiness_id']);
        $campaign_readiness->setNumTallySheets($form_values['num_tally_sheets']);
        $campaign_readiness->setNumFingerMarkers($form_values['num_finger_markers']);
        $campaign_readiness->setArrivalDateMobilizationMaterial(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['arrival_date_mobiliztion_material'])));
        $campaign_readiness->setDpecMeetingDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['dpec_meeting_date'])));

        if (!empty($form_values['dco_attended_meeting']) && $form_values['dco_attended_meeting'] == 'on') {
            $dco_meeting = 1;
        } else {
            $dco_meeting = 0;
        }

        $campaign_readiness->setDcoAttendedMeeting($dco_meeting);
        if (!empty($form_values['edo_attended_meeting']) && $form_values['edo_attended_meeting'] == 'on') {
            $edo_meeting = 1;
        } else {
            $edo_meeting = 0;
        }
        $campaign_readiness->setEdoAttendedMeeting($edo_meeting);
        $campaign_readiness->setRemarks($form_values['remarks']);
        if (!empty($form_values['all_members_attended_meeting']) && $form_values['all_members_attended_meeting'] == 'on') {
            $all_meeting = 1;
        } else {
            $all_meeting = 0;
        }


        $campaign_readiness->setAllMembersAttendedMeeting($all_meeting);

        $campaign = $this->_em->find('Campaigns', $form_values['campaign_edit_id']);

        $campaign_readiness->setCampaign($campaign);
        $created_by = $this->_em->find('Users', $this->_userid);
        $campaign_readiness->setModifiedBy($created_by);
        $campaign_readiness->setCreatedDate(App_Tools_Time::now());

        $this->_em->persist($campaign_readiness);
        $this->_em->flush();
        $this->_redirect("campaign/manage-campaigns/campaign-readiness");
    }

    /**
     * This method gets Lqas Data Entry
     */
    public function lqasDataEntryAction() {
        $form = new Form_Campaigns_LqasDataEntry();
        $campaigns_lqas_data = new Model_Campaigns();

        $params = array();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $campaignId = $form->getValue('campaign_search_id');


                if (!empty($campaignId)) {
                    $params['campaignId'] = $campaignId;
                }
            }
        } else {
            $campaignId = $this->_getParam('campaignId');


            if (!empty($campaignId)) {
                $params['campaignId'] = $campaignId;
                $form->campaign_search_id->setValue($campaignId);
            }
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "login_id");
        $campaigns_lqas_data->form_values = $params;
        $result = $campaigns_lqas_data->getAllCampaignsLqasData($order, $sort);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->pagination_params = $params;
    }

    /**
     * ajaxGetUcs
     */
    public function ajaxGetUcsAction() {

        $this->_helper->layout->disableLayout();

        $form_values = $this->_request->getPost();
        $location = new Model_Locations();
        $location->form_values = $form_values;
        $result = $location->getAllUcsByCampaignId();
        $this->view->data = $result;
    }

    /**
     * This method Adds Campaign Lqas
     */
    public function addCampaignLqasAction() {


        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $campaign_lqas = new CampaignLqasData();
        $campaign_lqas->setSurveyor($form_values['surveyor']);
        $campaign_lqas->setChecked($form_values['checked']);
        $campaign_lqas->setUnvaccinated($form_values['unvaccinted']);

        $campaign_lqas->setRemarks($form_values['remarks']);

        $campaign = $this->_em->find('Campaigns', $form_values['campaign_id']);
        $campaign_lqas->setCampaign($campaign);

        $district = $this->_em->find('Locations', $form_values['district_id']);
        $campaign_lqas->setDistrict($district);

        $uc = $this->_em->find('Warehouses', $form_values['uc_id']);

        $campaign_lqas->setUnionCouncil($uc);

        $created_by = $this->_em->find('Users', $this->_userid);
        $campaign_lqas->setCreatedBy($created_by);
        $campaign_lqas->setCreatedDate(App_Tools_Time::now());
        $campaign_lqas->setModifiedBy($created_by);
        $campaign_lqas->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($campaign_lqas);
        $this->_em->flush();
        $this->_redirect("campaign/manage-campaigns/lqas-data-entry");
    }

    /**
     * This method Updates Campaign Lqas
     */
    public function updateCampaignLqasAction() {


        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $campaign_lqas = $this->_em->find('CampaignLqasData', $this->_request->getParam('lqas_id'));
        $campaign_lqas->setSurveyor($form_values['surveyor']);
        $campaign_lqas->setChecked($form_values['checked']);
        $campaign_lqas->setUnvaccinated($form_values['unvaccinted']);

        $campaign_lqas->setRemarks($form_values['remarks']);

        $campaign = $this->_em->find('Campaigns', $form_values['campaign_edit_id']);
        $campaign_lqas->setCampaign($campaign);

        $district = $this->_em->find('Locations', $form_values['district_edit_id']);
        $campaign_lqas->setDistrict($district);

        $uc = $this->_em->find('Warehouses', $form_values['uc_edit_id']);

        $campaign_lqas->setUnionCouncil($uc);

        $created_by = $this->_em->find('Users', $this->_userid);
        $campaign_lqas->setModifiedBy(App_Tools_Time::now());
        $campaign_lqas->setModifiedBy($created_by);

        $this->_em->persist($campaign_lqas);
        $this->_em->flush();
        $this->_redirect("campaign/manage-campaigns/lqas-data-entry");
    }

    /**
     * ajaxEditLqas
     */
    public function ajaxEditLqasAction() {
        $this->_helper->layout->setLayout("ajax");
        $campaign_lqas = $this->_em->find('CampaignLqasData', $this->_request->getParam('lqas_id'));
        $form = new Form_Campaigns_LqasDataEntry();

        $form->campaign_edit_id->setValue($campaign_lqas->getCampaign()->getPkId());

        $province = $this->_em->getRepository("Locations")->findBy(array('district' => $campaign_lqas->getDistrict()->getPkId()));

        $form->province_id_hidden->setValue($province[0]->getProvince()->getPkId());
        $form->district_id_hidden->setValue($campaign_lqas->getDistrict()->getPkId());
        $form->uc_id_hidden->setValue($campaign_lqas->getUnionCouncil()->getPkId());

        $form->surveyor->setValue($campaign_lqas->getSurveyor());
        $form->checked->setValue($campaign_lqas->getChecked());
        $form->unvaccinted->setValue($campaign_lqas->getUnvaccinated());

        $form->remarks->setValue($campaign_lqas->getRemarks());

        $form->lqas_id->setValue($campaign_lqas->getPkId());

        $this->view->form = $form;

        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/campaign/manage-campaigns/ajax-lqas.js');
    }

    /**
     * This method gets Campaign Readiness Uc
     */
    public function campaignReadinessUcAction() {

        $form = new Form_Campaigns_CampaignReadinessUc();

        $params = array();

        $campaign_readiness = new Model_CampaignReadiness();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $form->uc_id_hidden->setValue($form->getValue('uc_id'));
                $campaignId = $form->getValue('campaign_id');
                $ucId = $form->getValue('uc_id');
                if (!empty($campaignId)) {
                    $params['campaignId'] = $campaignId;
                }
                if (!empty($ucId)) {
                    $params['ucId'] = $ucId;
                }
            }
        } else {
            $campaignId = $this->_getParam('campaignId');
            $ucId = $this->_getParam('ucId');
            $campaignId = $campaign_readiness->getLatestCampaignByDistrict();

            if (!empty($campaignId)) {
                $params['campaignId'] = $campaignId;
                $form->campaign_id->setValue($campaignId);
            }
            if (!empty($ucId)) {
                $params['ucId'] = $ucId;
                $form->uc_id->setValue($ucId);
            }
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "login_id");
        $campaign_readiness->form_values = $params;
        $result = $campaign_readiness->getCampaignReadinessUc($order, $sort);

        $this->view->form = $form;

        $this->view->paginator = $result;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->pagination_params = $params;
    }

    /**
     * This method Adds Campaign Readiness Uc
     */
    public function addCampaignReadinessUcAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $campaign_readiness = new CampaignReadinessUnionCouncil();
        $campaign_readiness->setInaccessibleChildren($form_values['inaccessible_children']);
        $campaign_readiness->setNumberMobileTeams($form_values['no_of_mobile_teams']);

        $campaign_readiness->setUpecMeetingDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_upec_meeting'])));
        $campaign_readiness->setInaccessibleArea($form_values['inaccessible_area']);
        $campaign_readiness->setNumberFixedTeams($form_values['no_of_fixed_teams']);
        $campaign_readiness->setNumberTransitPoints($form_values['no_of_transist_points']);
        $campaign_readiness->setAicTrained($form_values['aics_trained']);
        $campaign_readiness->setNumberTeamsTrained($form_values['no_of_teams_trained']);
        $campaign_readiness->setMobilePopulationAreas($form_values['area_mobile_population']);


        $uc = $this->_em->find('Warehouses', $form_values['warehouse_add_id_hidden']);
        $campaign_readiness->setUnionCouncil($uc);
        $campaign = $this->_em->find('Campaigns', $form_values['campaign_add_id_hidden']);
        $campaign_readiness->setCampaign($campaign);

        $created_by = $this->_em->find('Users', $this->_userid);
        $campaign_readiness->setCreatedBy($created_by);
        $campaign_readiness->setCreatedDate(App_Tools_Time::now());
        $campaign_readiness->setModifiedBy($created_by);
        $campaign_readiness->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($campaign_readiness);
        $this->_em->flush();
        $this->_redirect("campaign/manage-campaigns/campaign-readiness-uc");
    }

    /**
     * ajaxEditReadinessUc
     */
    public function ajaxEditReadinessUcAction() {
        $this->_helper->layout->disableLayout();
        $campaign_readiness = $this->_em->find('CampaignReadinessUnionCouncil', $this->_request->getParam('item_id'));
        $form = new Form_Campaigns_CampaignReadinessUc();
        $form->campaign_edit_id->setValue($campaign_readiness->getCampaign()->getPkId());
        $form->campaign_edit_id->setAttrib('disabled', 'disabled');
        $form->uc_edit_id_hidden->setValue($campaign_readiness->getUnionCouncil()->getPkId());
        $form->uc_edit_id->setAttrib('disabled', 'disabled');
        $form->inaccessible_children->setValue($campaign_readiness->getInaccessibleChildren());
        $form->no_of_mobile_teams->setValue($campaign_readiness->getNumberMobileTeams());
        $form->inaccessible_area->setValue($campaign_readiness->getInaccessibleArea());
        $form->no_of_fixed_teams->setValue($campaign_readiness->getNumberFixedTeams());
        $form->area_in_charge->setValue($campaign_readiness->getNumberFixedTeams());
        $form->no_of_transist_points->setValue($campaign_readiness->getNumberTransitPoints());
        $form->aics_trained->setValue($campaign_readiness->getAicTrained());

        $form->no_of_teams_trained->setValue($campaign_readiness->getNumberTeamsTrained());

        $form->area_mobile_population->setValue($campaign_readiness->getMobilePopulationAreas());
        $form->date_upec_meeting->setValue($campaign_readiness->getUpecMeetingDate()->format('d/m/Y'));

        $form->readiness_uc_id->setValue($campaign_readiness->getPkId());
        $this->view->form = $form;
        $base_url = Zend_Registry::get('baseurl');
        $this->view->inlineScript()->appendFile($base_url . '/js/campaign/manage-campaigns/ajax-campaign-readiness-uc.js');
    }

    /**
     * This method Updates Campaign Readiness Uc
     */
    public function updateCampaignReadinessUcAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $form_values = $this->_request->getPost();
        $campaign_readiness = $this->_em->find('CampaignReadinessUnionCouncil', $form_values['readiness_uc_id']);
        $campaign_readiness->setInaccessibleChildren($form_values['inaccessible_children']);
        $campaign_readiness->setNumberMobileTeams($form_values['no_of_mobile_teams']);

        $campaign_readiness->setUpecMeetingDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['date_upec_meeting'])));
        $campaign_readiness->setInaccessibleArea($form_values['inaccessible_area']);
        $campaign_readiness->setNumberFixedTeams($form_values['no_of_fixed_teams']);

        $campaign_readiness->setNumberTransitPoints($form_values['no_of_transist_points']);
        $campaign_readiness->setAicTrained($form_values['aics_trained']);
        $campaign_readiness->setNumberTeamsTrained($form_values['no_of_teams_trained']);
        $campaign_readiness->setMobilePopulationAreas($form_values['area_mobile_population']);

        $created_by = $this->_em->find('Users', $this->_user_id);
        $campaign_readiness->setCreatedBy($created_by);
        $campaign_readiness->setCreatedDate(App_Tools_Time::now());
        $campaign_readiness->setModifiedBy($created_by);
        $campaign_readiness->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($campaign_readiness);
        $this->_em->flush();
        $this->_redirect("campaign/manage-campaigns/campaign-readiness-uc");
    }

    /**
     * This method gets Reported Districts
     */
    public function reportedDistrictsAction() {
        $form = new Form_Campaigns_LqasDataEntry();
        $campaigns_lqas_data = new Model_Campaigns();

        $params = array();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $campaignId = $form->getValue('campaign_search_id');


                if (!empty($campaignId)) {
                    $params['campaignId'] = $campaignId;
                }
            }
        } else {
            $campaignId = $this->_getParam('campaignId');


            if (!empty($campaignId)) {
                $params['campaignId'] = $campaignId;
                $form->campaign_search_id->setValue($campaignId);
            }
        }
        if (empty($campaignId)) {
            $params['campaignId'] = 1;
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "login_id");
        $campaigns_lqas_data->form_values = $params;
        $result = $campaigns_lqas_data->getAllReportedDistricts($order, $sort);

        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
        $this->view->pagination_params = $params;
    }

    /**
     * This method gets Reported Ucs
     */
    public function reportedUcsAction() {
        $form = new Form_Campaigns_LqasDataEntry();
        $campaigns_lqas_data = new Model_Campaigns();

        $params = array();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {
            $campaignId = $form->getValue('campaign_search_id');

            if (!empty($campaignId)) {
                $params['campaignId'] = $campaignId;
            }
            $campaigns_lqas_data->form_values = $params;
            $auth = App_Auth::getInstance();
            $district_id = $auth->getDistrictId($auth->getIdentity());
            $campaigns_lqas_data->form_values['district_id'] = $district_id;
            $campaigns_lqas_data->form_values['campaign_id'] = $campaignId;
            $all_ucs = $campaigns_lqas_data->getAllDistrictUcs();
            $reported_uc = $campaigns_lqas_data->getAllReportedUcs();
            $this->view->reported_uc = $reported_uc;
            $this->view->all_uc = $all_ucs;
        }

        $this->view->form = $form;
    }

    /**
     * ajaxCampaignTargetUc
     */
    public function ajaxCampaignTargetUcAction() {
        $this->_helper->layout->disableLayout();

        $form_values = $this->_request->getPost();
        $campaign_target = new Model_CampaignTargets();
        $campaign_target->form_values = $form_values;
        $result = $campaign_target->getTargetByCampaignIdUcId();
        $this->view->data = $result;
    }

    /**
     * ajaxGetCampaignVaccince
     */
    public function ajaxGetCampaignVaccinceAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $campaign = new Model_Campaigns();
        $campaign->form_values = $form_values;
        $result = $campaign->getVaccinceByCampaignId();
        $this->view->data = $result;
    }

    /**
     * ajaxGetCampaignVialsRequired
     */
    public function ajaxGetCampaignVialsRequiredAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $campaign = new Model_Campaigns();
        $campaign->form_values = $form_values;
        $result = $campaign->getVialsRequireByCampaignId();
        $this->view->data = $result;
    }

    /**
     * ajaxGetCampaignVialsAvailable
     */
    public function ajaxGetCampaignVialsAvailableAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $campaign = new Model_Campaigns();
        $campaign->form_values = $form_values;
        $result = $campaign->getVialsAvailableByCampaignId();
        $this->view->data = $result;
    }

    /**
     * ajaxGetUcTarget
     */
    public function ajaxGetUcTargetAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $campaign = new Model_Campaigns();
        $campaign->form_values = $form_values;

        $campaign_days = $campaign->getCampaignDays();

        $end_date = $campaign_days[0]['dateTo'];

        $start_date = $campaign_days[0]['dateFrom'];
        $begin = new DateTime($start_date);
        $end = new DateTime($end_date);
        $difference = $begin->diff($end);
        if ($start_date == $end_date) {
            $day = 1;
        } else {
            $day = $difference->d;
        }

        $result = $campaign->getUcTarget();
        $this->view->data = $result;
        $this->view->days = $day;
    }

    /**
     * This method gets Campaign Types
     */
    public function campaignTypesAction() {
        $form = new Form_Campaigns_CampaignTypes();

        $campaignType = new Model_CampaignTypes();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $campaign_type_name = $form->getValue('campaign_type_name');

                if (!empty($campaign_type_name)) {
                    $campaignType->form_values['campaign_type_name'] = $campaign_type_name;
                }
            }
            $form->campaign_type_name->setValue($campaign_type_name);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "campaignType");

        $result = $campaignType->getCampaignTypes();
        //Paginate the contest results
        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
    }

    /**
     * This method Adds Campaign Type
     */
    public function addCampaignTypeAction() {
        $form = new Form_Campaigns_CampaignTypes();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $campaign_types = new CampaignTypes();
            $campaign_types->setCamapignTypeName($form->campaign_type_name->getValue());
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $campaign_types->setCreatedBy($createdBy);
            $campaign_types->setModifiedBy($createdBy);
            $campaign_types->setCreatedDate(App_Tools_Time::now());
            $campaign_types->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($campaign_types);
            $this->_em->flush();
        }
        $this->_redirect("/campaign/manage-campaigns/campaign-types?success=1");
    }

    /**
     * This method Adds Product Groups
     */
    public function addProductGroupsAction() {
        $form = new Form_Campaigns_ProductGroups();

        if ($this->_request->isPost() && $form->isValid($this->_request->getPost())) {

            $campaign_item_groups = new CampaignItemGroups;

            $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($form->item_id_add->getValue());
            $campaign_item_groups->setItemPackSize($item_pack_size_id);

            $campaign_item_groups->setAgeGroup1Min($form->age_group1_min->getValue());
            $campaign_item_groups->setAgeGroup1Max($form->age_group1_max->getValue());
            $campaign_item_groups->setAgeGroup2Min($form->age_group2_min->getValue());
            $campaign_item_groups->setAgeGroup2Max($form->age_group2_max->getValue());

            $created_by = $this->_em->find('Users', $this->_user_id);
            $campaign_item_groups->setCreatedBy($created_by);
            $campaign_item_groups->setCreatedDate(App_Tools_Time::now());
            $campaign_item_groups->setModifiedBy($created_by);
            $campaign_item_groups->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($campaign_item_groups);
            $this->_em->flush();
        }
        $this->_redirect("/campaign/manage-campaigns/product-groups?success=1");
    }

    /**
     * This method Updates Product Groups
     */
    public function updateProductGroupsAction() {


        if ($this->_request->isPost()) {

            $form_values = $this->_request->getPost();

            $campaign_item_groups = $this->_em->getRepository("CampaignItemGroups")->find($form_values['campaign_item_groups_id']);
            $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($form_values['item_id_add']);
            $campaign_item_groups->setItemPackSize($item_pack_size_id);

            $campaign_item_groups->setAgeGroup1Min($form_values['age_group1_min']);
            $campaign_item_groups->setAgeGroup1Max($form_values['age_group1_max']);
            $campaign_item_groups->setAgeGroup2Min($form_values['age_group2_min']);
            $campaign_item_groups->setAgeGroup2Max($form_values['age_group2_max']);

            $created_by = $this->_em->find('Users', $this->_user_id);
            $campaign_item_groups->setModifiedBy($created_by);
            $campaign_item_groups->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($campaign_item_groups);
            $this->_em->flush();
        }
        $this->_redirect("/campaign/manage-campaigns/product-groups?success=1");
    }

    /**
     * This method Checks Campaign Type
     */
    public function checkCampaignTypeAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->campaign_type_name;

        $campaign_type = new Model_CampaignTypes();
        $campaign_type->form_values = $form_values;
        $result = $campaign_type->checkCampaignType();
        $this->view->result = $result;
    }

    /**
     * ajaxCampaignTypeEdit
     */
    public function ajaxCampaignTypeEditAction() {
        $this->_helper->layout->disableLayout();
        $campaign_type = $this->_em->find('CampaignTypes', $this->_request->getParam('campaign_type_id'));
        $form = new Form_Campaigns_CampaignTypes();
        $form->campaign_type_name->setValue($campaign_type->getCamapignTypeName());

        $form->campaign_type_id->setValue($campaign_type->getPkId());
        $this->view->form = $form;
    }

    /**
     * This method Updates Campaign Type
     */
    public function updateCampaignTypeAction() {
        if ($this->_request->getPost()) {
            $form_values = $this->_request->getPost();
            $campaign_type = $this->_em->getRepository("CampaignTypes")->find($form_values['campaign_type_id']);

            $campaign_type->setCamapignTypeName($form_values['campaign_type_name']);
            $createdBy = $this->_em->getRepository('Users')->find($this->_userid);
            $campaign_type->setModifiedBy($createdBy);
            $campaign_type->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($campaign_type);
            $this->_em->flush();
        }
        $this->_redirect("/campaign/manage-campaigns/campaign-types?success=2");
    }

    /**
     * ajaxGetNumberDoses
     */
    public function ajaxGetNumberDosesAction() {
        $this->_helper->layout->disableLayout();
        $form_values = $this->_request->getPost();
        $campaign = new Model_Campaigns();
        $campaign->form_values = $form_values;
        $result = $campaign->getNumberOfDoses();
        $this->view->data = $result;
    }

    /**
     * ajaxPreviousCampaignsName
     */
    public function ajaxPreviousCampaignsNameAction() {
        $this->_helper->layout->setLayout("ajax");

        $form_values = $this->_request->getPost();
        $campaign_targets = new Model_CampaignTargets();
        $campaign_targets->form_values = $form_values;
        $result = $campaign_targets->getAllPreviousCampaigns();

        $this->view->data = $result;
    }

    /**
     * ajaxAddReadinessUc
     */
    public function ajaxAddReadinessUcAction() {

        $this->_helper->layout->disableLayout();
        $warehouse_name = $this->_em->find('Warehouses', $this->_request->getParam('warehouse_id'));
        $campaign_name = $this->_em->find('Campaigns', $this->_request->getParam('campaign_id'));
        $campaign_target = new Model_CampaignTargets();
        $campaign_target->form_values['uc_id'] = $this->_request->getParam('warehouse_id');
        $campaign_target->form_values['campaign_id'] = $this->_request->getParam('campaign_id');
        $result = $campaign_target->getTargetByCampaignIdUcId();

        $form = new Form_Campaigns_CampaignReadinessUc();
        $form->campaign_add_id->setValue($campaign_name->getCampaignName());
        $form->campaign_add_id_hidden->setValue($this->_request->getParam('campaign_id'));
        $form->warehouse_add_id_hidden->setValue($this->_request->getParam('warehouse_id'));
        $form->target->setValue($result['0']['dailyTarget']);
        $form->uc_add_id->setValue($warehouse_name->getWarehouseName());
        $form->uc_add_id->setAttrib('disabled', 'disabled');
        $form->campaign_add_id->setAttrib('disabled', 'disabled');
        $form->target->setAttrib('disabled', 'disabled');
        $this->view->form = $form;
    }

    /**
     * This method gets Product Groups
     */
    public function productGroupsAction() {
        $form = new Form_Campaigns_ProductGroups();

        $campaignItemGroups = new Model_CampaignItemGroups();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $item_id = $form->getValue('item_id');

                if (!empty($item_id)) {
                    $campaignItemGroups->form_values['item_id'] = $item_id;
                }
            }
            $form->item_id->setValue($item_id);
        }

        $sort = $this->_getParam("sort", "asc");
        $order = $this->_getParam("order", "campaignType");

        $result = $campaignItemGroups->getCampaignItemGroups();

        $paginator = Zend_Paginator::factory($result);
        $page = $this->_getParam("page", 1);
        $counter = $this->_getParam("counter", 10);
        $paginator->setCurrentPageNumber((int) $page);
        $paginator->setItemCountPerPage((int) $counter);

        $this->view->form = $form;

        $this->view->paginator = $paginator;
        $this->view->sort = $sort;
        $this->view->order = $order;
        $this->view->counter = $counter;
    }

    /**
     * ajaxCampaignItemGroupsEdit
     */
    public function ajaxCampaignItemGroupsEditAction() {
        $this->_helper->layout->disableLayout();
        $campaign_item_groups = $this->_em->find('CampaignItemGroups', $this->_request->getParam('campaign_item_group_id'));
        $form = new Form_Campaigns_ProductGroups();
        $form->item_id_edit->setValue($campaign_item_groups->getItemPackSize()->getPkId());

        $form->age_group1_min->setValue($campaign_item_groups->getAgeGroup1Min());
        $form->age_group1_max->setValue($campaign_item_groups->getAgeGroup1Max());
        $form->age_group2_min->setValue($campaign_item_groups->getAgeGroup2Min());
        $form->age_group2_max->setValue($campaign_item_groups->getAgeGroup2Max());

        $form->campaign_item_groups_id->setValue($campaign_item_groups->getPkId());
        $this->view->form = $form;
    }

}
