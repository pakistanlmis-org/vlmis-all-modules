<?php

/**
 * Form_Campaigns_DataEntrySearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Data Entry Search
 */
class Form_Campaigns_DataEntrySearch extends Form_Base {

    /**
     * Fields for Form_Campaigns_DataEntrySearch
     * 
     * 
     * campaign_id
     * province_id
     * district_id
     * item_id
     * day
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "campaign_id" => "Campaigns",
        "province_id" => "Province",
        "district_id" => "District",
        "item_id" => "Product",
        "day" => "day"
    );

    /**
     * Combo boxes for Form_Campaigns_DataEntrySearch
     * 
     * 
     * campaign_id
     * province_id
     * district_id
     * item_id
     * day
     * 
     * 
     *  $_list
     * @var type 
     */
    private $_list = array(
        'campaign_id' => array(),
        "province_id" => array('0' => 'Select Campaign'),
        "district_id" => array('0' => 'Select Province'),
        "item_id" => array('0' => 'Select Campaign'),
        "day" => array('0' => 'Select Campaign'),
    );

    /**
     * Initializes Form Fields
     * for Form_Campaigns_DataEntrySearch
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
            $result1 = $campaign->allCampaigns();

            $this->_list["campaign_id"][''] = 'Select';
            foreach ($result1 as $row) {
                $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
            }
        } else {
            $campaign->form_values['district_id'] = $district_id;
            $result1 = $campaign->districtCampaigns();
            $this->_list["campaign_id"][''] = 'Select';
            foreach ($result1 as $row) {
                $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
            }
        }

        //Generate fields for 
        // form Form_Campaigns_DataEntrySearch
        foreach ($this->_fields as $col => $name) {

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Campaigns_DataEntrySearch
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
