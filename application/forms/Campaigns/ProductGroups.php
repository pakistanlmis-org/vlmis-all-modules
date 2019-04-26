<?php

/**
 * Form_Campaigns_ProductGroups
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Campaigns Product Groups
 */
class Form_Campaigns_ProductGroups extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @item_id: Product
     * @item_ad_add: Product
     * @item_id_edit: Product
     * @age_group1_min: Age Group1 Min
     * @age_group1_max: Age Group1 Max
     * @age_group2_min: Age Group2 Min
     * @age_group2_max: Age Group2 Max
     * 
     * @var type 
     */
    private $_fields = array(
        "item_id" => "Product",
        "item_id_add" => "Product",
        "item_id_edit" => "Product",
        "age_group1_min" => "Age Group1 Min",
        "age_group1_max" => "Age Group1 Max",
        "age_group2_min" => "Age Group2 Min",
        "age_group2_max" => "Age Group2 Max"
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_hidden = array(
        "campaign_item_groups_id" => "campaign_item_groups_id"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_list = array(
        "item_id" => array(),
        "item_id_add" => array(),
        "item_id_edit" => array(),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $campaign = new Model_Campaigns();
        $result2 = $campaign->campaignVaccines();
        $this->_list["item_id"][''] = 'Select';
        $this->_list["item_id_add"][''] = 'Select';
        $this->_list["item_id_edit"][''] = 'Select';
        foreach ($result2 as $row) {
            $this->_list["item_id"][$row['pkId']] = $row['itemName'];
            $this->_list["item_id_add"][$row['pkId']] = $row['itemName'];
            $this->_list["item_id_edit"][$row['pkId']] = $row['itemName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {

                case "age_group1_min":
                case "age_group1_max":
                case "age_group2_min":
                case "age_group2_max":
                    parent::createTextWithAdditionalClass($col, "input-xsmall");
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        foreach ($this->_hidden as $col => $name) {
            if ($col == "campaign_item_groups_id") {
                parent::createHidden($col);
            }
        }
    }

}
