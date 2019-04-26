<?php

/**
 * Form_StockPlacement
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Stock Placement
 * 
 * Inherits: Zend Form
 */
class Form_StockPlacement extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @quantity: Quantity
     * @cold_chain: Cold Chain
     * 
     * @var type 
     */
    private $_fields = array(
        "quantity" => "Quantity",
        "cold_chain" => "Cold Chain"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * 
     * @var type 
     */
    private $_list = array(
        'cold_chain' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate Asset Sub Type Combo
        $asset_types = new Model_CcmAssetTypes();
        $asset_types->form_values = array('parent_id' => 1);
        $result3 = $asset_types->getAssetSubTypes();
        $this->_list["cold_chain"][''] = "Select Cold Chain";
        foreach ($result3 as $assetsubtype) {
            $this->_list["cold_chain"][$assetsubtype['pkId']] = $assetsubtype['assetTypeName'];
        }

        foreach ($this->_fields as $col => $name) {
            if ($col == "quantity") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * 
     * Hidden Fields
     * @id: ID
     * 
     * Class: Hidden
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
