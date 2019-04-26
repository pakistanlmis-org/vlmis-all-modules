<?php

/**
 * Form_AddRefrigerator
 *
 * 
 *
 * Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Refrigerator
 */
class Form_AddRefrigerator extends Form_Base {

    /**
     * 
     * $_fields
     * 
     * @ catalogue_id: "Catalogue ID"
     * 
     * @ ccm_make_id: "Make"
     * 
     * @ ccm_model_id: "Model"
     * 
     * @ ccm_asset_type_id: "Asset Sub Type"
     * 
     * @ cfc_free: "CFC Free Sticker"
     * 
     * @ is_pis_pqs: "Is PIS/PQS"
     * 
     * @ asset_dimension_length: "Dimensions"
     * 
     * @ asset_dimension_width: "Dimensions"
     * 
     * @ asset_dimension_height: "Dimensions"
     * 
     * @ gross_capacity_4: "Capacity"
     * 
     * @ gross_capacity_20: "Capacity"
     * 
     * @ net_capacity_4: "Capacity"
     * 
     * @ net_capacity_20: "Capacity"
     * 
     * @ serial_number: "Serial Number"
     * 
     * @ working_since: "Working Since Year"
     * 
     * @ catalogue_id_popup: "Catalogue id"
     * 
     * @ ccm_make_popup: "Make"
     * 
     * @ ccm_model_popup: "Model"
     * 
     * @ ccm_asset_type_id_popup: "Asset Sub Type"
     * 
     * @ asset_dimension_length_popup: "Dimensions"
     * 
     * @ asset_dimension_width_popup: "Dimensions"
     * 
     * @ asset_dimension_height_popup: "Dimensions"
     * 
     * @ gross_capacity_4_popup: "Capacity"
     * 
     * @ gross_capacity_20_popup: "Capacity"
     * 
     * @ net_capacity_4_popup: "Capacity"
     * 
     * @ net_capacity_20_popup: "Capacity"
     * 
     * @ temperature_monitor: "temperature_monitor"
     * 
     * @ refrigerator_gas_type: "refrigerator_gas_type"
     * 
     * @ power_source: "power_source"
     * 
     * @ product_price: "product_price"
     * 
     * @var type 
     * 
     */
    private $_fields = array(
        "catalogue_id" => "Catalogue ID",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "ccm_asset_type_id" => "Asset Sub Type",
        "cfc_free" => "CFC Free Sticker",
        "is_pis_pqs" => "Is PIS/PQS",
        "has_voltage" => "Has Voltage regulator",
        "asset_dimension_length" => "Dimensions",
        "asset_dimension_width" => "Dimensions",
        "asset_dimension_height" => "Dimensions",
        "gross_capacity_4" => "Capacity",
        "gross_capacity_20" => "Capacity",
        "net_capacity_4" => "Capacity",
        "net_capacity_20" => "Capacity",
        "serial_number" => "Serial Number",
        "working_since" => "Working Since Year",
        "catalogue_id_popup" => "Catalogue id",
        "ccm_make_popup" => "Make",
        "ccm_model_popup" => "Model",
        "ccm_asset_type_id_popup" => "Asset Sub Type",
        "asset_dimension_length_popup" => "Dimensions",
        "asset_dimension_width_popup" => "Dimensions",
        "asset_dimension_height_popup" => "Dimensions",
        "gross_capacity_4_popup" => "Capacity",
        "gross_capacity_20_popup" => "Capacity",
        "net_capacity_4_popup" => "Capacity",
        "net_capacity_20_popup" => "Capacity",
        "temperature_monitor" => "temperature_monitor",
        "refrigerator_gas_type" => "refrigerator_gas_type",
        "power_source" => "power_source",
        "product_price" => "product_price"
    );

    /**
     * $_list
     * 
     * @ccm_make_id: array()
     * 
     * @ccm_model_id: array()
     * 
     * @ccm_asset_type_id: array()
     * 
     * @temperature_monitor: array()
     * 
     * @refrigerator_gas_type: array()
     * 
     * @power_source: array()
     * 
     * @var type 
     */
    private $_list = array(
        'ccm_make_id' => array(),
        'ccm_model_id' => array(),
        'ccm_asset_type_id' => array(),
        'temperature_monitor' => array(),
        'refrigerator_gas_type' => array(),
        'power_source' => array()
    );

    /**
     * $_radio
     * 
     * @cfc_free: array()
     * 
     * @is_pis_pqs: array()
     * 
     * @var type 
     */
    private $_radio = array(
        'cfc_free' => array(
            "2" => "NA",
            "1" => "Yes",
            "0" => "No",
        ),
        'is_pis_pqs' => array(
            "1" => "Yes",
            "0" => "No",
        ),
        'has_voltage' => array(
            "1" => "Yes",
            "0" => "No",
        )
    );

    /**
     * $_hidden
     * 
     * @ccm_id: "pkId"
     * 
     * @ccm_make_id_hidden: ""
     * 
     * @ccm_model_id_hidden: ""
     * 
     * 
     * @var type 
     */
    private $_hidden = array(
        "ccm_id" => "pkId",
        "ccm_make_id_hidden" => "",
        "ccm_model_id_hidden" => ""
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate Asset Id Equipment Code Combo
        $models = new Model_CcmModels();

        $models->form_values['asset_type'] = Model_CcmAssetTypes::REFRIGERATOR;
        $result0 = $models->getAllAssetsByType();
        $this->_list["catalogue_id"][''] = "Select";
        foreach ($result0 as $row) {
            $this->_list["catalogue_id"][$row['pkId']] = $row['catalogueId'] . ' - ' . $row['ccmMakeName'] . ' - ' . $row['ccmModelName'];
        }

        //Generate Makes Combo
        $make = new Model_CcmMakes();
        $make->form_values = array('type_id' => Model_CcmAssetTypes::REFRIGERATOR);
        $result1 = $make->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select Makes";
        foreach ($result1 as $make) {
            $this->_list["ccm_make_id"][$make['pkId']] = $make['ccmMakeName'];
        }


        //Generate Temperature Monitor  Combo
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::TEMPERATURE_MONITOR);
        $result5 = $list_master->getListDetailByType();
        $this->_list["temperature_monitor"][''] = "Select";
        foreach ($result5 as $rs) {
            $this->_list["temperature_monitor"][$rs['pkId']] = $rs['listValue'];
        }

        //Generate Temperature Monitor  Combo
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::REFRIGERANT_GAS_TYPE);
        $result5 = $list_master->getListDetailByType();
        $this->_list["refrigerator_gas_type"][''] = "Select";
        foreach ($result5 as $rs) {
            $this->_list["refrigerator_gas_type"][$rs['pkId']] = $rs['listValue'];
        }

        //Generate Temperature Monitor  Combo
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::POWER_SOURCE);
        $result5 = $list_master->getListDetailByType();
        $this->_list["power_source"][''] = "Select";
        foreach ($result5 as $rs) {
            $this->_list["power_source"][$rs['pkId']] = $rs['listValue'];
        }


        //Generate Models Combo
        $this->_list["ccm_model_id"][''] = "Select Make First";

        //Generate Asset Sub Type Combo
        $asset_types = new Model_CcmAssetTypes();
        $asset_types->form_values = array('parent_id' => 1);
        $result3 = $asset_types->getAssetSubTypes();
        $this->_list["ccm_asset_type_id"][''] = "Select";
        $this->_list["ccm_asset_type_id_popup"][''] = "Select";
        foreach ($result3 as $assetsubtype) {
            $this->_list["ccm_asset_type_id"][$assetsubtype['pkId']] = $assetsubtype['assetTypeName'];
            $this->_list["ccm_asset_type_id_popup"][$assetsubtype['pkId']] = $assetsubtype['assetTypeName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * gross_capacity_4
                 * 
                 * gross_capacity_4_popup
                 * 
                 */
                case "gross_capacity_4":
                case "gross_capacity_4_popup":
                    parent::createTextWithPlaceholder($col, "Gross Cap 4");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * gross_capacity_20
                 * 
                 * gross_capacity_20_popup
                 * 
                 */
                case "gross_capacity_20_popup":
                case "gross_capacity_20":
                    parent::createTextWithPlaceholder($col, "Gross Cap -20");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * net_capacity_4
                 * 
                 * net_capacity_4_popup
                 * 
                 */
                case "net_capacity_4_popup":
                case "net_capacity_4":
                    parent::createTextWithPlaceholder($col, "Net Cap 4");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * net_capacity_20
                 * 
                 * net_capacity_20_popup
                 * 
                 */
                case "net_capacity_20":
                case "net_capacity_20_popup":
                    parent::createTextWithPlaceholder($col, "Net Cap -20");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * working_since
                 * 
                 */
                case "working_since":
                    parent::createReadOnlyText($col);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * serial_number
                 * 
                 * product_price
                 * 
                 * ccm_make_popup
                 * 
                 * ccm_model_popup
                 * 
                 * catalogue_id_popup
                 * 
                 */
                case "serial_number":
                case "product_price":
                case "ccm_make_popup":
                case "ccm_model_popup":
                case "catalogue_id_popup":
                    parent::createText($col);
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * asset_dimension_length
                 * 
                 * asset_dimension_length_popup
                 * 
                 */
                case "asset_dimension_length":
                case "asset_dimension_length_popup":
                    parent::createTextWithPlaceholder($col, "Length");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * asset_dimension_width
                 * 
                 * asset_dimension_width_popup
                 * 
                 */
                case "asset_dimension_width":
                case "asset_dimension_width_popup":
                    parent::createTextWithPlaceholder($col, "Width");
                    break;
                /**
                 * 
                 * Form Text Fields for 
                 * 
                 * asset_dimension_height
                 * 
                 * asset_dimension_height_popup
                 * 
                 */
                case "asset_dimension_height":
                case "asset_dimension_height_popup":
                    parent::createTextWithPlaceholder($col, "Height");
                    break;
                /**
                 * 
                 * Default Switch Statement
                 * 
                 */
                default:
                    break;
            }

            /**
             * 
             * Form Radio Fields population
             * 
             */
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }

            /**
             * 
             * Catalog_id check for field css classes
             * 
             */
            if ($col == "catalogue_id") {
                $attribute_class = "col-md-2 form-control input-small form-group";
            } else {
                $attribute_class = "form-control form-group";
            }

            /**
             * 
             * Populate form select fields
             * 
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        /**
         * 
         * Populate hidden fields
         * 
         */
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "ccm_id":
                case "ccm_make_id_hidden":
                case "ccm_model_id_hidden":
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
