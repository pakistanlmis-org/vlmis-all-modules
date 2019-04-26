<?php

/**
 * Form_Campaigns_CampaignTypes
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Campaign Types
 */
class Form_Campaigns_CampaignTypes extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @campaign_type_name: Campaign Type
     * 
     * @var type 
     */
    private $_fields = array(
        "campaign_type_name" => "Campaign Type"
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_hidden = array(
        "campaign_type_id" => "campaign_type_id"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {


        foreach ($this->_fields as $col => $name) {
            if ($col == "campaign_type_name") {
                parent::createText($col);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            if ($col == "campaign_type_id") {
                $this->addElement("hidden", $col);
                $this->getElement($col)->removeDecorator("Label")->removeDecorator("HtmlTag");
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
