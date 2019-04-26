<?php

/**
 * Form_Campaigns_AddCampaign
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Add Campaign
 */
class Form_Campaigns_AddCampaign extends Form_Base {

    /**
     * Fields 
     * Form_Campaigns_AddCampaign
     * 
     * 
     * campaign_name
     * date_from
     * date_to
     * campaign_type_id
     * item_id
     * catch_up_days
     * 
     * 
     * $_fields
     * 
     * Form Fields
     * @campaign_name: Campaign Name
     * @date_from: Date From
     * @date_to: Date To
     * @campaign_type_id: Campaign Type
     * @item_id: Product
     * @catch_up_days: Catch up days
     * 
     * @var type 
     */
    private $_fields = array(
        "campaign_name" => "Campaign Name",
        "date_from" => "Date From",
        "date_to" => "Date To",
        "campaign_type_id" => "Campaign Type",
        "item_id" => "Product",
        "catch_up_days" => "Catch up days"
    );

    /**
     * Hidden Fields
     * Form_Campaigns_AddCampaign
     * 
     * 
     * id
     * campaign_id
     * campaign_update_id
     * 
     * 
     * $_hidden
     * 
     * Hidden
     * @id: Pk Id
     * @campaign_id: Pk Id
     * @campaign_update_id: Pk Id
     * 
     * @var type 
     */
    private $_hidden = array(
        "id" => "pkId",
        "campaign_id" => "pkId",
        "campaign_update_id" => "pkId"
    );

    /**
     * Combo boxes
     * Form_Campaigns_AddCampaign
     * 
     * 
     * campaign_type_id
     * item_id
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        "campaign_type_id" => array(),
        "item_id" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $campaign_types = new Model_CampaignTypes();
        $result1 = $campaign_types->getAllCampaignTypes();
        $this->_list["campaign_type_id"][''] = 'Select';
        foreach ($result1 as $row) {
            $this->_list["campaign_type_id"][$row['pkId']] = $row['camapignTypeName'];
        }

        $campaign = new Model_Campaigns();
        $result2 = $campaign->campaignVaccines();

        foreach ($result2 as $row) {
            $this->_list["item_id"][$row['pkId']] = $row['itemName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "campaign_name":
                case "date_from":
                case "date_to":
                    parent::createReadOnlyText($col);
                    break;
                case "catch_up_days":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                if ($col == "item_id") {
                    parent::createMultiSelect($col, $this->_list[$col]);
                } else {
                    parent::createSelect($col, $this->_list[$col]);
                }
            }
        }
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "campaign_id":
                case "campaign_update_id":
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
        parent::createHidden("id");
    }

}
