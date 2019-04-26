<?php

/**
 * Form_Campaigns_LqasDataEntry
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns LQAS Data Entry
 */
class Form_Campaigns_LqasDataEntry extends Form_Base {

    /**
     * Fields 
     * for Form_Campaigns_LqasDataEntry
     * 
     * 
     * campaign_id
     * campaign_search_id
     * campaign_edit_id
     * province_id
     * district_id
     * province_edit_id
     * district_edit_id
     * uc_edit_id
     * item_id
     * uc_id
     * surveyor
     * checked
     * unvaccinted
     * remarks
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "campaign_id" => "Campaigns",
        "campaign_search_id" => "Campaigns",
        "campaign_edit_id" => "Campaigns",
        "province_id" => "Province",
        "district_id" => "District",
        "uc_id" => "Uc",
        "province_edit_id" => "Province",
        "district_edit_id" => "District",
        "uc_edit_id" => "Uc",
        "item_id" => "Product",
        "surveyor" => "surveyor",
        "checked" => "surveryor",
        "unvaccinted" => "unvaccinted",
        "remarks" => "remarks"
    );

    /**
     * Hidden Fields 
     * for Form_Campaigns_LqasDataEntry
     * 
     * 
     * province_id_hidden
     * district_id_hidden
     * uc_id_hidden
     * lqas_id
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "province_id_hidden" => "",
        "district_id_hidden" => "",
        "uc_id_hidden" => "",
        "lqas_id" => ""
    );

    /**
     * Combo boxes
     * for Form_Campaigns_LqasDataEntry
     * 
     * 
     * campaign_id
     * campaign_search_id
     * campaign_edit_id
     * province_id
     * district_id
     * uc_id
     * province_edit_id
     * district_edit_id
     * uc_edit_id
     * item_id
     * day
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'campaign_id' => array(),
        'campaign_search_id' => array(),
        'campaign_edit_id' => array(),
        "province_id" => array('0' => 'Select Campaign'),
        "district_id" => array('0' => 'Select Province'),
        "uc_id" => array(),
        "province_edit_id" => array('0' => 'Select Campaign'),
        "district_edit_id" => array('0' => 'Select Province'),
        "uc_edit_id" => array(),
        "item_id" => array('0' => 'Select Campaign'),
        "day" => array('0' => 'Select Campaign'),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $auth = App_Auth::getInstance();
        if ($auth->getStakeholderId() != 10) {
            $warehouse_id = $auth->getWarehouseId();
        } else {
            $warehouse_id = "";
        }
        $campaign = new Model_Campaigns();
        $result1 = $campaign->allCampaigns();
        $this->_list["campaign_id"][''] = 'Select';
        $this->_list["campaign_search_id"][''] = 'Select';
        $this->_list["campaign_edit_id"][''] = 'Select';
        foreach ($result1 as $row) {
            $this->_list["campaign_id"][$row['pkId']] = $row['campaignName'];
            $this->_list["campaign_search_id"][$row['pkId']] = $row['campaignName'];
            $this->_list["campaign_edit_id"][$row['pkId']] = $row['campaignName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {

                case "surveyor":
                case "checked":
                case "unvaccinted" :
                case "remarks" :
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {

                case "province_id_hidden":
                case "district_id_hidden":
                case "uc_id_hidden":
                case "lqas_id":
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
