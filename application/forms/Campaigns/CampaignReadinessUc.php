<?php

/**
 * Form_Campaigns_CampaignReadinessUc
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Campaign Readiness UC
 */
class Form_Campaigns_CampaignReadinessUc extends Form_Base {

    /**
     * Form Fields
     *
     * Private Variable
     *
     * @campaign_id: Campaigns
     * @campaign_add_id: Campaigns
     * @campaign_edit_id: Campaigns
     * @uc_id: uc
     * @uc_add_id: uc
     * @uc_edit_id: uc
     * @target: target
     * @inaccessible_children: inaccessible children
     * @no_of_mobile_teams: no of mobile teams
     * @inaccessible_area: inaccessible area
     * @no_of_fixed_teams: no of fixed teams
     * @area_in_charge: area in charge
     * @no_of_transist_points: no of transist points
     * @aics_trained: aics trained
     * @no_of_teams_trained: no of teams trained
     * @area_mobile_population: area mobile population
     * @date_upec_meeting: date upec meeting
     * @target: target
     *
     * @var type
     *
     */
    private $_fields = array(
        "campaign_id" => "Campaigns",
        "campaign_add_id" => "Campaigns",
        "campaign_edit_id" => "Campaigns",
        "uc_id" => "uc",
        "uc_add_id" => "uc",
        "uc_edit_id" => "uc",
        "target" => "target",
        "inaccessible_children" => "inaccessible_children",
        "no_of_mobile_teams" => "no_of_mobile_teams",
        "inaccessible_area" => "inaccessible_area",
        "no_of_fixed_teams" => "no_of_fixed_teams",
        "area_in_charge" => "area_in_charge",
        "no_of_transist_points" => "no_of_transist_points",
        "aics_trained" => "aics_trained",
        "no_of_teams_trained" => "no_of_teams_trained",
        "area_mobile_population" => "area_mobile_population",
        "date_upec_meeting" => "date_upec_meeting",
        "target" => "target"
    );

    /**
     * $_hidden
     * @var type
     * uc_id_hidden
     * readiness_uc_id
     * uc_edit_id_hidden
     * campaign_add_id_hidden
     * warehouse_add_id_hidden
     */
    private $_hidden = array(
        "uc_id_hidden" => "",
        "readiness_uc_id" => "",
        "uc_edit_id_hidden" => "",
        "campaign_add_id_hidden" => "",
        "warehouse_add_id_hidden" => ""
    );

    /**
     * $_list
     * @var type
     * campaign_id
     * campaign_edit_id
     * uc_id
     * uc_edit_id
     */
    private $_list = array(
        'campaign_id' => array(),
        'campaign_edit_id' => array(),
        'uc_id' => array(),
        'uc_edit_id' => array()
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
        /**
         * Get District Id
         * return District By Id
         */
        $district_id = $auth->getDistrictId($auth->getIdentity());

        $locations = new Model_Locations();

        $locations->form_values['district_id'] = $district_id;

        $this->_list["uc_id"][''] = 'All';
        /**
         * Get All Campaigns
         * return All Campaigns
         */
        $campaign = new Model_Campaigns();
        if ($role_id == Model_Roles::CAMPAIGN && empty($warehouse_id)) {
            $result1 = $campaign->allCampaigns();
            $this->_list["campaign_id"][''] = 'Select';
            $this->_list["campaign_edit_id"][''] = 'Select';
            foreach ($result1 as $row) {
                $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
                $this->_list["campaign_edit_id"][$row['pkId']] = $row['campaignName'];
            }
        } else {
            $campaign->form_values['district_id'] = $district_id;
            /**
             * Get District Campaigns
             * return All District Campaigns
             */
            $result1 = $campaign->districtCampaigns();

            $this->_list["campaign_id"][''] = 'Select';
            $this->_list["campaign_edit_id"][''] = 'Select';
            foreach ($result1 as $row) {
                $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
                $this->_list["campaign_edit_id"][$row['pkId']] = $row['campaignName'];
            }
        }
        /**
         * Generate Text Fields
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * inaccessible_children
                 * no_of_mobile_teams
                 * inaccessible_area
                 * no_of_fixed_teams
                 * area_in_charge
                 * no_of_transist_points
                 * aics_trained
                 * no_of_teams_trained
                 * area_mobile_population
                 * uc_add_id
                 * campaign_add_id
                 * target
                 */
                case "inaccessible_children":
                case "no_of_mobile_teams":
                case "inaccessible_area" :
                case "no_of_fixed_teams" :
                case "area_in_charge" :
                case "no_of_transist_points" :
                case "aics_trained" :
                case "no_of_teams_trained" :
                case "area_mobile_population" :
                case "uc_add_id":
                case "campaign_add_id":
                case "target":
                    parent::createText($col);
                    break;
                /**
                 * date_upec_meeting
                 */
                case "date_upec_meeting":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        /**
         * Generate Hidden Fields
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                /**
                 * readiness_uc_id
                 * uc_edit_id_hidden
                 * campaign_add_id_hidden
                 * warehouse_add_id_hidden
                 * uc_id_hidden
                 */
                case "readiness_uc_id":
                case "uc_edit_id_hidden":
                case "campaign_add_id_hidden":
                case "warehouse_add_id_hidden":
                case "uc_id_hidden":
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
