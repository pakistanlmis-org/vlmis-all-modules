<?php

/**
 * Form_AddTransport
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Transport
 */
class Form_AddTransport extends Form_Base {

    /**
     * Fields 
     * for Form_AddTransport
     * 
     * 
     * ccm_asset_sub_type_id
     * registration_no
     * ccm_make_id
     * manufacture_year
     * used_for_epi
     * fuel_type_id
     * fuel_type_id
     * comments
     * 
     * 
     * $_fields
     * 
     * Form Fields
     * @ccm_asset_sub_type_id: Transport Type
     * @registration_no: Registration No
     * @ccm_make_id: Make
     * @ccm_model_id: Model
     * @manufacture_year: Manufacture Year
     * @used_for_epi: % Used For EPI
     * @fuel_type_id: Fuel Type
     * @comments: Comments
     * 
     * @var type 
     */
    private $_fields = array(
        "ccm_asset_sub_type_id" => "Transport Type",
        "registration_no" => "Registration No",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "manufacture_year" => "Manufacture Year",
        "used_for_epi" => "% Used For EPI",
        "fuel_type_id" => "Fuel Type",
        "comments" => "Comments",
    );

    /**
     * Combo boxes
     * for Form_AddTransport
     * 
     * 
     * ccm_asset_sub_type_id
     * ccm_make_id
     * ccm_model_id
     * fuel_type_id
     * 
     * 
     * $_list
     * 
     * List
     * @ccm_asset_sub_type_id
     * @ccm_make_id
     * @ccm_model_id
     * @fuel_type_id
     * 
     * @var type $_list
     */
    private $_list = array(
        'ccm_asset_sub_type_id' => array(),
        'ccm_make_id' => array(),
        'ccm_model_id' => array(),
        'fuel_type_id' => array()
    );

    /**
     * Hidden fields 
     * for Form_AddTransport
     * 
     * 
     * ccm_id
     * model_hidden
     * 
     * 
     * $_hidden
     * 
     * Hidden
     * @ccm_id: Pk Id
     * @model_hidden: Pk Id
     * 
     * @var type 
     */
    private $_hidden = array(
        "ccm_id" => "pkId",
        "model_hidden" => "pkId"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Makes Combo
        $makes = new Model_CcmMakes();
        $makes->form_values = array('type_id' => Model_CcmAssetTypes::TRANSPORT);
        $result1 = $makes->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select Makes";
        if ($result1) {
            foreach ($result1 as $row) {
                $this->_list["ccm_make_id"][$row['pkId']] = $row['ccmMakeName'];
            }
        }

        //Generate Models Combo
        $this->_list["ccm_model_id"][''] = "Select Make First";

        //Generate Asset Sub Type Combo
        $asset_types = new Model_CcmAssetTypes();
        $asset_types->form_values = array('parent_id' => Model_CcmAssetTypes::TRANSPORT);
        $result3 = $asset_types->getAssetSubTypes();
        $this->_list["ccm_asset_sub_type_id"][''] = "Select Asset Sub Types";
        if ($result3) {
            foreach ($result3 as $assetsubtype) {
                $this->_list["ccm_asset_sub_type_id"][$assetsubtype['pkId']] = $assetsubtype['assetTypeName'];
            }
        }
        //Generate Fuel Type Combo
        $list = new Model_ListDetail();
        $list->form_values = array('fuel_type_id' => Model_ListMaster::FUEL_TYPE);
        $result4 = $list->getFuelTypes();
        $this->_list["fuel_type_id"][''] = "Select Fuel Types";
        if ($result4) {
            foreach ($result4 as $fueltype) {
                $this->_list["fuel_type_id"][$fueltype['pkId']] = $fueltype['listValue'];
            }
        }


        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "registration_no":
                case "used_for_epi":
                case "comments":
                    parent::createText($col);
                    break;
                case "manufacture_year":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "ccm_id":
                case "model_hidden":
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
