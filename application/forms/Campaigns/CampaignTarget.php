<?php

/**
 * Form_Campaigns_CampaignTarget
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Campaign Target
 */
class Form_Campaigns_CampaignTarget extends Form_Base {

    /**
     * Fields for Form_Campaigns_CampaignTarget
     * 
     * 
     * campaign_id
     * campaign_import_id
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
        "campaign_import_id" => "Campaigns",
        "province_id" => "Province",
        "district_id" => "District",
        "item_id" => "Product",
        "day" => "day"
    );

    /**
     * Hidden fields for Form_Campaigns_CampaignTarget
     * 
     * 
     * province_id_hidden
     * district_id_hidden
     * item_id_hidden
     * province_import_hidden
     * campaign_import_hidden
     * district_import_hidden
     * item_import_hidden
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "province_id_hidden" => "",
        "district_id_hidden" => "",
        "item_id_hidden" => "",
        "province_import_hidden" => "",
        "campaign_import_hidden" => "",
        "district_import_hidden" => "",
        "item_import_hidden" => ""
    );

    /**
     * Combo boxes for Form_Campaigns_CampaignTarget
     * 
     * 
     * campaign_id
     * campaign_import_id
     * province_id
     * district_id
     * item_id
     * day
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'campaign_id' => array(),
        'campaign_import_id' => array(),
        "province_id" => array('0' => 'Select Campaign'),
        "district_id" => array('0' => 'Select Province'),
        "item_id" => array('0' => 'Select Campaign'),
        "day" => array('0' => 'Select Campaign'),
    );

    /**
     * Initializes Form Fields
     * for Form_Campaigns_CampaignTarget
     */
    public function init() {
        $auth = App_Auth::getInstance();
        if ($auth->getStakeholderId() != 10) {
            $warehouse_id = $auth->getWarehouseId();
        } else {
            $warehouse_id = "";
        }

        // Generate combo boxes
        // for Form_Campaigns_CampaignTarget
        $this->_list["campaign_import_id"][''] = 'Select';
        $campaign = new Model_Campaigns();
        $result1 = $campaign->allCampaigns();
        $this->_list["campaign_id"][''] = 'Select';

        foreach ($result1 as $row) {
            $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
        }

        //Generate fields for 
        // for Form_Campaigns_CampaignTarget
        foreach ($this->_fields as $col => $name) {

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }

        //Generate hidden fields for 
        // for Form_Campaigns_CampaignTarget
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "province_id_hidden":
                case "district_id_hidden":
                case "item_id_hidden":
                case "province_import_hidden":
                case "district_import_hidden":
                case "campaign_import_hidden":
                case "item_import_hidden";
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Campaigns_CampaignTarget
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
