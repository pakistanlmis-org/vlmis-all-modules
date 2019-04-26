<?php

/**
 * Form_AddVoltageRegulator
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Voltage Regulator
 */
class Form_AddVoltageRegulator extends Form_Base {

    /**
     * @placed_at: Placement location
     * @no_of_phases: No of phases
     * @catalogue_id: Catalog id
     * @quantity: Quantity
     * @ccm_make_id: Cold chain maker id
     * @ccm_model_id: Cold chain model id
     * @catalogue_id_popup: Catalogue popup id
     * @ccm_make_popup: Cold chain make popup id
     * @ccm_model_popup: Cold chain management popup id    
     * @nominal_voltage: Nominal voltage
     * @continous_power: Continous power
     * @frequency: Frequency
     * @product_price: product price
     * @input_voltage_range: Input voltage range
     * @output_voltage_range: Output voltage range
     */
    private $_fields = array(
        "placed_at" => "Placed At",
        "no_of_phases" => "no_of_phases",
        "catalogue_id" => "Catalogue ID",
        "quantity" => "Quantity",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "catalogue_id_popup" => "Catalogue id",
        "ccm_make_popup" => "Make",
        "ccm_model_popup" => "Model",
        "nominal_voltage" => "nominal_voltage",
        "continous_power" => "continous_power",
        "frequency" => "frequency",
        "product_price" => "product_price",
        "input_voltage_range" => "input_voltage_range",
        "output_voltage_range" => "output_voltage_range"
    );

    /**
     * @ccm_make_id: Cold chain maker id
     * @ccm_model_id: Cold chain model id
     * $_list
     * @var type 
     */
    private $_list = array(
        'ccm_make_id' => array(),
        'ccm_model_id' => array()
    );

    /**
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'placed_at' => array(
            "0" => "Unallocated",
            "1" => "Select Store",
        ),
        'no_of_phases' => array(
            "1" => "one",
            "3" => "three"
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        // Generate Asset Id Equipment Code Combo
        $models = new Model_CcmModels();
        $models->form_values['asset_type'] = Model_CcmAssetTypes::VOLTAGEREGULATOR;
        // Get All assets types.
        $result0 = $models->getAllAssetsByType();
        $this->_list["catalogue_id"][''] = "Select asset";
        // Popuplate assets types.
        foreach ($result0 as $row) {
            $this->_list["catalogue_id"][$row['pkId']] = $row['catalogueId'] . ' - ' . $row['ccmMakeName'] . ' - ' . $row['ccmModelName'];
        }

        // Generate Makes Combo
        $make = new Model_CcmMakes();
        $make->form_values = array('type_id' => Model_CcmAssetTypes::VOLTAGEREGULATOR);
        // Get Cold chain manufacturers.
        $result1 = $make->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select Makes";
        foreach ($result1 as $make) {
            $this->_list["ccm_make_id"][$make['pkId']] = $make['ccmMakeName'];
        }

        // Generate Models Combo
        $this->_list["ccm_model_id"][''] = "Select Make First";

        // Apply classes and css attributes ti each element
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "ccm_make_popup":
                case "ccm_model_popup":
                case "catalogue_id_popup":
                case "quantity":
                case "nominal_voltage":
                case "continous_power":
                case "frequency":
                case "product_price":
                case "input_voltage_range":
                case "output_voltage_range":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }

            // Apply css attributes and classes and validations attributes.
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields.
     * Method to apply attributes on hidden fields.
     */
    public function addHidden() {
        // Apply Css attributes and classes to the hidden forms elements.
        parent::createHiddenWithValidator("id");
    }

}
