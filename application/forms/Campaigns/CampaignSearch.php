<?php

/**
 * Form_Campaigns_CampaignSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Campaign Search 
 */
class Form_Campaigns_CampaignSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @campaign_name: Campaign Name
     * @campaign_type_id: Campaign Type
     * @item_id: Product
     * 
     * @var type 
     */
    private $_fields = array(
        "campaign_name" => "Campaign Name",
        "campaign_type_id" => "Campaign Type",
        "item_id" => "Product"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
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

        $_em = Zend_Registry::get('doctrine');
        $result1 = $_em->getRepository('CampaignTypes')->findAll();
        $this->_list["campaign_type_id"][''] = 'Select';
        foreach ($result1 as $row) {
            $this->_list["campaign_type_id"][$row->getPkId()] = $row->getCamapignTypeName();
        }

        $campaign = new Model_Campaigns();
        $result2 = $campaign->campaignVaccines();
        $this->_list["item_id"][''] = 'Select';
        foreach ($result2 as $row) {
            $this->_list["item_id"][$row['pkId']] = $row['itemName'];
        }

        foreach ($this->_fields as $col => $name) {
            if ($col == "campaign_name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
