<?php

/**
 * Form_Campaigns_CampaignReadiness
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Campaign Readiness
 */
class Form_Campaigns_CampaignReadiness extends Form_Base {

    /**
     * Form Fields
     * 
     * Private Variable
     * 
     * @campaign_id: Campaigns
     * @campaign_add_id: Campaigns
     * @num_tally_sheets: num_tally_sheets
     * @num_finger_markers: num finger markers
     * @arrival_date_mobiliztion_material: arrival date mobiliztion material
     * @dpec_meeting_date: dpec meeting date
     * @remarks: remarks
     * @dco_attended_meeting: dco attended meeting
     * @edo_attended_meeting: edo attended meeting
     * @all_members_attended_meeting: all members attended meeting
     * @province_id: Province
     * @district_id: District
     * @province_edit_id: Province
     * @district_edit_id: District
     * @campaign_edit_id: Campaigns
     * 
     * @var type
     *  
     */
    private $_fields = array(
        "campaign_id" => "Campaigns",
        "campaign_add_id" => "Campaigns",
        "num_tally_sheets" => "num_tally_sheets",
        "num_finger_markers" => "num_finger_markers",
        "arrival_date_mobiliztion_material" => "arrival_date_mobiliztion_material",
        "dpec_meeting_date" => "dpec_meeting_date",
        "remarks" => "remarks",
        "dco_attended_meeting" => "dco_attended_meeting",
        "edo_attended_meeting" => "edo_attended_meeting",
        "all_members_attended_meeting" => "all_members_attended_meeting",
        "province_id" => "Province",
        "district_id" => "District",
        "province_edit_id" => "Province",
        "district_edit_id" => "District",
        "campaign_edit_id" => "Campaigns"
    );

    /**
     * $_hidden
     * @var type 
     * readiness_id
     * province_id_hidden
     * district_id_hidden
     */
    private $_hidden = array(
        "readiness_id" => "",
        "province_id_hidden" => "",
        "district_id_hidden" => ""
    );

    /**
     * $_list
     * @var type 
     * campaign_id
     * campaign_add_id
     * province_id
     * district_id
     * province_edit_id
     * district_edit_id
     * item_id
     * day
     */
    private $_list = array(
        'campaign_id' => array(),
        'campaign_add_id' => array(),
        "province_id" => array('0' => 'Select Campaign'),
        "district_id" => array('0' => 'Select Province'),
        "province_edit_id" => array('0' => 'Select Campaign'),
        "district_edit_id" => array('0' => 'Select Province'),
        "item_id" => array('0' => 'Select Campaign'),
        "day" => array('0' => 'Select Campaign')
    );

    /**
     * $_checkbox
     * @var type 
     * dco_attended_meeting
     * edo_attended_meeting
     * all_members_attended_meeting
     */
    private $_checkbox = array(
        'dco_attended_meeting' => array(
            "dco_attended_meeting" => "Dco Attended Meeting"
        ),
        'edo_attended_meeting' => array(
            "edo_attended_meeting" => "Edo Attended Meeting"
        ),
        'all_members_attended_meeting' => array(
            "all_members_attended_meeting" => "All Members Attended Meeting"
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $auth = App_Auth::getInstance();
        $role_id = $auth->getRoleId();
        if ($auth->getStakeholderId() != 10) {
            $warehouse_id = $auth->getWarehouseId();
        } else {
            $warehouse_id = "";
        }
        $district_id = $auth->getDistrictId($auth->getIdentity());

        $campaign = new Model_Campaigns();
        if ($role_id == Model_Roles::CAMPAIGN && empty($warehouse_id)) {
            /**
             * All Campaigns
             * @param null
             * @return All Campaigns
             */
            $result1 = $campaign->allCampaigns();
            $this->_list["campaign_id"][''] = 'Select';
            $this->_list["campaign_add_id"][''] = 'Select';
            $this->_list["campaign_edit_id"][''] = 'Select';

            foreach ($result1 as $row) {
                $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
                $this->_list["campaign_add_id"][$row['pkId']] = $row['campaignName'];
                $this->_list["campaign_edit_id"][$row['pkId']] = $row['campaignName'];
            }
        } else {
            $campaign->form_values['district_id'] = $district_id;
            /**
             * District Campaigns
             * @param null
             * @return District Campaigns
             */
            $result1 = $campaign->districtCampaigns();
            $this->_list["campaign_id"][''] = 'Select';
            $this->_list["campaign_add_id"][''] = 'Select';
            $this->_list["campaign_edit_id"][''] = 'Select';


            foreach ($result1 as $row) {
                $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
                $this->_list["campaign_add_id"][$row['pkId']] = $row['campaignName'];
                $this->_list["campaign_edit_id"][$row['pkId']] = $row['campaignName'];
            }
        }
        /**
         * Initializes Form Fields
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * num_tally_sheets
                 * num_finger_markers
                 * remarks
                 */
                case "num_tally_sheets":
                case "num_finger_markers":
                case "remarks" :
                    parent::createText($col);
                    break;
                /**
                 * arrival_date_mobiliztion_material
                 * dpec_meeting_date
                 */
                case "arrival_date_mobiliztion_material":
                case "dpec_meeting_date":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            /**
             * Initializes Select Boxes
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            /**
             * Initializes CheckBoxes
             */
            if (in_array($col, array_keys($this->_checkbox))) {
                parent::createMultiCheckbox($col, $this->_checkbox[$col]);
            }
        }
        /**
         * Initializes Hidden Fields
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                /**
                 * readiness_id
                 * province_id_hidden
                 * district_id_hidden
                 */
                case "readiness_id":
                case "province_id_hidden":
                case "district_id_hidden":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
