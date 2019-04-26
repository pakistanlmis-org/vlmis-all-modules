<?php

/**
 * Form_Cadmin_ModelsSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Models Search
 */
class Form_Cadmin_ModelsSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @ccm_model_name: Model Name
     * @catalogue_id: Catalogue Id
     * @ccm_asset_type_id: Asset Type
     * @ccm_make_id: Make
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "ccm_model_name" => "Model Name",
        "catalogue_id" => "catalogue_id",
        'ccm_asset_type_id' => 'Asset Type',
        'ccm_make_id' => 'Make',
        'status' => 'Status'
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_list = array(
        "ccm_asset_type_id" => array(),
        'ccm_make_id' => array('' => 'Select Asset Type First'),
        'status' => array(
            "all" => "All",
            "0" => "In Active",
            "1" => "Active",
            "2" => "Draft"
        )
    );

    /**
     * $_radio
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_radio = array(
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Asset Types Combo
        $asset_type = new Model_CcmAssetTypes();
        $result4 = $asset_type->getAssetSubTypes();
        $this->_list["ccm_asset_type_id"][''] = "Select";
        foreach ($result4 as $rs) {
            $this->_list["ccm_asset_type_id"][$rs['pkId']] = $rs['assetTypeName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "ccm_model_name":
                case "catalogue_id":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

}
