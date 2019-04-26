<?php

/**
 * Form_Cadmin_AssetSubTypeAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Asset Subtype Add
 */
class Form_Cadmin_AssetSubTypeAdd extends Form_Base {

    /**
     * Fields for Form_Cadmin_AssetSubTypeAdd
     * 
     * 
     * 
     * asset_type
     * asset_sub_type
     * assetSubType
     * 
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "asset_type" => "Asset Type",
        "asset_sub_type" => "Asset Sub Type",
        "assetSubType" => "Asset Sub Type"
    );

    /**
     * Combo boxes fo Form_Cadmin_AssetSubTypeAdd
     * 
     * 
     * asset_type
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'asset_type' => array()
    );

    /**
     * Hidden filds for Form_Cadmin_AssetSubTypeAdd
     * 
     * 
     * asset_id
     * 
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "asset_id" => "pkId"
    );

    /**
     * Initializes Form Fields
     * for Form_Cadmin_AssetSubTypeAdd
     */
    public function init() {

        //Generate Asset Type Combo
        $asset_types = new Model_CcmAssetTypes();

        $result = $asset_types->getAssetSubTypes();

        $this->_list["asset_type"][''] = "Select";
        foreach ($result as $rs) {
            $this->_list["asset_type"][$rs['pkId']] = $rs['assetTypeName'];
        }

        //Generate fields
        //for Form_Cadmin_AssetSubTypeAdd
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "asset_sub_type":
                case "assetSubType":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);                
            }
        }

        //Generate Hidden Fields
        //for Form_Cadmin_AssetSubTypeAdd
        foreach ($this->_hidden as $col => $name) {
            if ($col == "asset_id") {
                parent::createHidden($col);
            }
        }
    }

    /**
     * Add Hidden Fields 
     * for Form_Cadmin_AssetSubTypeAdd
     * Validate
     * Filter
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
