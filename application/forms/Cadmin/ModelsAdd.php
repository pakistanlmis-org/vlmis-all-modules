<?php

/**
 * Form_Cadmin_ModelsAdd
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Models Add
 */
class Form_Cadmin_ModelsAdd extends Form_Base {

    /**
     * $_fields
     * 
     * @ccm_model_name: 'model name'
     *
     * @asset_dimension_length: 'asset dimension length'
     *
     * @asset_dimension_width: 'asset dimension width'
     *
     * @asset_dimension_height: 'asset dimension height'
     *
     * @gross_capacity_20: 'gross capacity 20'
     *
     * @gross_capacity_4: 'gross capacity 4'
     *
     * @net_capacity_20: 'net capacity 20'
     *
     * @net_capacity_4: 'net capacity 4'
     *
     * @cfc_free: 'cfc free'
     *
     * @no_of_phases: 'no of phases'
     *
     * @status: 'status'
     *
     * @reasons: 'reasons'
     *
     * @utilizations: 'utilizations'
     *
     * @temperature_type: 'temperature type'
     *
     * @catalogue_id: 'catalogue id'
     *
     * @gas_type: 'gas type'
     *
     * @ccm_make_id: 'make'
     *
     * @ccm_asset_type_id_popup: 'asset type'
     *
     * @ccm_asset_type_id_update: 'asset type'
     *
     * @ccm_asset_sub_type: 'asset sub type'
     *
     * @ccm_asset_sub_type_update: 'asset sub type'
     *
     * 
     * @var type 
     */
    private $_fields = array(
        'ccm_model_name' => 'model name',
        'asset_dimension_length' => 'asset dimension length',
        'asset_dimension_width' => 'asset dimension width',
        'asset_dimension_height' => 'asset dimension height',
        'gross_capacity_20' => 'gross capacity 20',
        'gross_capacity_4' => 'gross capacity 4',
        'net_capacity_20' => 'net capacity 20',
        'net_capacity_4' => 'net capacity 4',
        'cfc_free' => 'cfc free',
        'no_of_phases' => 'no of phases',
        'status' => 'status',
        'reasons' => 'reasons',
        'utilizations' => 'utilizations',
        'temperature_type' => 'temperature type',
        'catalogue_id' => 'catalogue id',
        'gas_type' => 'gas type',
        'ccm_make_id' => 'make',
        'ccm_asset_type_id_popup' => 'asset type',
        'ccm_asset_type_id_update' => 'asset type',
        'ccm_asset_sub_type' => 'asset sub type',
        'ccm_asset_sub_type_update' => 'asset sub type'
    );

    /**
     * $_list
     * 
     * @status: array()
     *
     * @ccm_make_id: array()
     *
     * @reason: array()
     *
     * @utilization: array()
     *
     * @temperature_type: array()
     *
     * @gas_type: array()
     *
     * @ccm_asset_type_id_popup: array()
     *
     * @ccm_asset_type_id_update: array()
     *
     * @ccm_asset_sub_type: array('' => 'Select Asset Type First')
     *
     * @ccm_asset_sub_type_update: array('' => 'Select Asset Type First')
     *
     * 
     * @var type 
     */
    private $_list = array(
        'status' => array(
            "0" => "In Active",
            "1" => "Active",
            "2" => "Draft"
        ),
        'ccm_make_id' => array(),
        'reason' => array(),
        'utilization' => array(),
        'temperature_type' => array(),
        'gas_type' => array(),
        "ccm_asset_type_id_popup" => array(),
        "ccm_asset_type_id_update" => array(),
        "ccm_asset_sub_type" => array('' => 'Select Asset Type First'),
        "ccm_asset_sub_type_update" => array('' => 'Select Asset Type First')
    );

    /**
     * $_radio
     * 
     * cfc_free: array()
     * 
     * temperature_type: array()
     * 
     * no_of_phases: array()
     * 
     * @var type 
     */
    private $_radio = array(
        'cfc_free' => array(
            "2" => "Not Applicable",
            "1" => "Yes",
            "0" => "No",
        ),
        'temperature_type' => array(
            "0" => "Positive",
            "1" => "Negative",
            "2" => "Both",
        ),
        'no_of_phases' => array(
            "1" => "One",
            "3" => "Three",
        ),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        // Makes Combo
        $ccm_makes = new Model_CcmMakes();
        $result1 = $ccm_makes->getAllMakesForAddForm();
        $this->_list["ccm_make_id"][''] = "Select";

        if ($result1) {
            foreach ($result1 as $rs) {
                $this->_list["ccm_make_id"][$rs['pkId']] = $rs['ccmMakeName'];
            }
        }

        //Generate Asset Types Combo
        $asset_type = new Model_CcmAssetTypes();
        $result3 = $asset_type->getAssetSubTypes();

        $this->_list["ccm_asset_type_id_popup"][''] = "Select";
        $this->_list["ccm_asset_type_id_update"][''] = "Select";
        foreach ($result3 as $rs) {
            $this->_list["ccm_asset_type_id_popup"][$rs['pkId']] = $rs['assetTypeName'];
            $this->_list["ccm_asset_type_id_update"][$rs['pkId']] = $rs['assetTypeName'];
        }

        //Generate Asset Sub Types Combo
        $asset_type2 = new Model_CcmAssetTypes();
        $asset_type2->form_values['parent_id'] = 'childs';
        $result4 = $asset_type2->getAssetSubTypes();
        $this->_list["ccm_asset_sub_type"][''] = "Select";
        foreach ($result4 as $rs) {
            $this->_list["ccm_asset_sub_type"][$rs['pkId']] = $rs['assetTypeName'];
            $this->_list["ccm_asset_sub_type_update"][$rs['pkId']] = $rs['assetTypeName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * catalogue_id
                 * 
                 */
                case "ccm_model_name":
                case "catalogue_id":
                    parent::createText($col);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * asset_dimension_width
                 * 
                 */
                case "asset_dimension_width":
                    parent::createTextWithPlaceholder($col, "Width");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * asset_dimension_height
                 * 
                 */
                case "asset_dimension_height":
                    parent::createTextWithPlaceholder($col, "Height");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * gross_capacity_4
                 * 
                 */
                case "gross_capacity_4":
                    parent::createTextWithPlaceholder($col, "Gross Cap 4");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * gross_capacity_20
                 * 
                 */
                case "gross_capacity_20":
                    parent::createTextWithPlaceholder($col, "Gross Cap 20");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * net_capacity_4
                 * 
                 */
                case "net_capacity_4":
                    parent::createTextWithPlaceholder($col, "Net Cap 4");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * net_capacity_20
                 * 
                 */
                case "net_capacity_20":
                    parent::createTextWithPlaceholder($col, "Net Cap 20");
                    break;
                default:
                    break;
            }
            /**
             * 
             * Form Select Fields for models add
             * 
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            /**
             * 
             * Form Radio Buttons for 
             * 
             * models add
             * 
             */
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
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
