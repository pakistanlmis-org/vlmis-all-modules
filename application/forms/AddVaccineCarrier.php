<?php

/**
 * Form_AddVaccineCarrier
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Vaccine Carriers
 */
class Form_AddVaccineCarrier extends Form_Base {

    /**
     * Form Fields
     * 
     * @placed_at: Placed At
     * @catalogue_id: Catalogue Id
     * @ccm_make_id: Make
     * @ccm_model_id: Model
     * @asset_dimension_length: Asset Dimension Length
     * @asset_dimension_width: Asset Dimension Width
     * @asset_dimension_height: Asset Dimension Height
     * @quantity: Quantity
     * @catalogue_id_popup: Catalogue id
     * @ccm_make_popup: Make
     * @ccm_model_popup: Model
     * @asset_dimension_length_popup: Dimensions
     * @asset_dimension_width_popup: Dimensions
     * @asset_dimension_height_popup: Dimensions
     * @internal_dimension_length_popup: Dimensions
     * @internal_dimension_width_popup: Dimensions
     * @internal_dimension_height_popup: Dimensions
     * @storage_dimension_length_popup: Dimensions
     * @storage_dimension_width_popup: Dimensions
     * @storage_dimension_height_popup: Dimensions 
     * @ccm_asset_type_id_popup: Ccm Asset Type 
     * @net_capacity_4: Net Capacity4
     * @cold_life: Cold life
     * @product_price: Product Price
     * 
     * @var type
     *  
     */
    private $_fields = array(
        "placed_at" => "Placed At",
        "catalogue_id" => "Catalogue Id",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "asset_dimension_length" => "Asset Dimension Length",
        "asset_dimension_width" => "Asset Dimension Width",
        "asset_dimension_height" => "Asset Dimension Height",
        "quantity" => "Quantity",
        "catalogue_id_popup" => "Catalogue id",
        "ccm_make_popup" => "Make",
        "ccm_model_popup" => "Model",
        "asset_dimension_length_popup" => "Dimensions",
        "asset_dimension_width_popup" => "Dimensions",
        "asset_dimension_height_popup" => "Dimensions",
        "internal_dimension_length_popup" => "Dimensions",
        "internal_dimension_width_popup" => "Dimensions",
        "internal_dimension_height_popup" => "Dimensions",
        "storage_dimension_length_popup" => "Dimensions",
        "storage_dimension_width_popup" => "Dimensions",
        "storage_dimension_height_popup" => "Dimensions",
        "ccm_asset_type_id_popup" => "ccm_asset_type_id_popup",
        "net_capacity_4" => "net_capacity_4",
        "cold_life" => "cold_life",
        "product_price" => "product_price"
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
    );

    /**
     * $_radio
     * @var type 
     * placed_at
     */
    private $_radio = array(
        'placed_at' => array(
            "0" => "Unallocated",
            "1" => "Select Store",
        )
    );

    /**
     * $_hidden 
     * @var type 
     * ccm_make_id_hidden
     * ccm_model_id_hidden
     */
    private $_hidden = array(
        "ccm_make_id_hidden" => "",
        "ccm_model_id_hidden" => "",
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        /**
         * Generate Asset Id Equipment Code Combo
         */
        $models = new Model_CcmModels();
        $models->form_values['asset_type'] = Model_CcmAssetTypes::VACCINECARRIER;
        $result0 = $models->getAllAssetsByType();
        $this->_list["catalogue_id"][''] = "Select";
        foreach ($result0 as $row) {
            $this->_list["catalogue_id"][$row['pkId']] = $row['catalogueId'] . ' - ' . $row['ccmMakeName'] . ' - ' . $row['ccmModelName'];
        }

        /**
         * Generate Makes Combo
         */
        $make = new Model_CcmMakes();
        $make->form_values = array('type_id' => Model_CcmAssetTypes::VACCINECARRIER);
        $result1 = $make->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select Makes";
        foreach ($result1 as $make) {
            $this->_list["ccm_make_id"][$make['pkId']] = $make['ccmMakeName'];
        }
        /**
         * Generate Asset Sub Type Combo
         */
        $asset_types = new Model_CcmAssetTypes();
        $asset_types->form_values = array('parent_id' => 2);
        $result3 = $asset_types->getAssetSubTypes();

        $this->_list["ccm_asset_type_id_popup"][''] = "Select";
        foreach ($result3 as $assetsubtype) {

            $this->_list["ccm_asset_type_id_popup"][$assetsubtype['pkId']] = $assetsubtype['assetTypeName'];
        }


        /**
         * Generate Models Combo
         */
        $this->_list["ccm_model_id"][''] = "Select Make First";
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                /** Hidden Fields
                 * ccm_make_id_hidden
                 * ccm_model_id_hidden
                 */
                case "ccm_make_id_hidden":
                case "ccm_model_id_hidden":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /** Generate Text Fields
                 * ccm_make_popup
                 * ccm_model_popup
                 * catalogue_id_popup
                 * net_capacity_4
                 * cold_life
                 * product_price
                 */
                case "ccm_make_popup":
                case "ccm_model_popup":
                case "catalogue_id_popup":
                case "net_capacity_4":
                case "cold_life":
                case "product_price":
                    parent::createText($col);
                    break;
                /**
                 * quantity
                 */
                case "quantity":
                    parent::createTextWithAdditionalClass($col, "input-small");
                    parent::createText($col);
                    break;
                /**
                 * asset_dimension_length
                 * asset_dimension_length_popup
                 * internal_dimension_length_popup
                 * storage_dimension_length_popup
                 */
                case "asset_dimension_length":
                case "asset_dimension_length_popup":
                case "internal_dimension_length_popup":
                case "storage_dimension_length_popup":
                    parent::createTextWithPlaceholder($col, "Length");
                    break;
                /**
                 * asset_dimension_width
                 * asset_dimension_width_popup
                 * internal_dimension_width_popup
                 * storage_dimension_width_popup
                 */
                case "asset_dimension_width":
                case "asset_dimension_width_popup":
                case "internal_dimension_width_popup":
                case "storage_dimension_width_popup":
                    parent::createTextWithPlaceholder($col, "Width");
                    break;
                /**
                 * asset_dimension_height
                 * asset_dimension_height_popup
                 * internal_dimension_height_popup
                 * storage_dimension_height_popup
                 */
                case "asset_dimension_height":
                case "asset_dimension_height_popup":
                case "internal_dimension_height_popup":
                case "storage_dimension_height_popup":
                    parent::createTextWithPlaceholder($col, "Height");
                    break;
                default:
                    break;
            }
            /**
             * Generate Radio Buttons
             */
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }

            if ($col == "catalogue_id") {
                $attribute_class = "col-md-2 form-control input-small form-group";
            } else {
                $attribute_class = "form-control";
            }
            /**
             * Generate Select Boxes
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
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
