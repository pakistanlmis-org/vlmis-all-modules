<?php
/**
 * 
 * Form_AddColdRoom
 *
 * Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 * 
 */

/**
 * 
 * Form for Add Cold Room
 * 
 * Function: To Add More Cold Rooms
 * 
 */
class Form_AddColdRoom extends Form_Base {

    /**
     * Form Fields
     * 
     * Private Variable
     * 
     * @serial_number: Serial Number
     * @working_since: Working Since Year
     * @ccm_model_id: Model
     * @stakeholder_id: Stakeholder
     * @warehouse_id: Warehouse
     * @cooling_system: Cooling System
     * @has_voltage: Has Voltage Regulator
     * @ccm_asset_sub_type_id: Asset Sub Type Id
     * @temperature_recording_system: Temperature Recording System
     * @type_recording_system: Type Recording System
     * @refrigerator_gas_type: Refrigerator Gas Type
     * @backup_generator: Backup Generator
     * @ccm_model_name: Ccm Model Name
     * @asset_dimension_length: Asset Dimension Length
     * @asset_dimension_width: Asset Dimension Width
     * @asset_dimension_height: Asset Dimension Height
     * @gross_capacity: Gross Capacity
     * @net_capacity: Net Capacity
     * @placed_at: Placed at
     * @no_of_phases: No of Phases
     * @n: Number
     * @make: Make
     * 
     * @var type
     *  
     */
    private $_fields = array(
        "serial_number" => "Serial Number",
        "working_since" => "Working Since Year",
        "ccm_model_id" => "Model",
        "stakeholder_id" => "Stakeholder",
        "warehouse_id" => "Warehouse",
        "cooling_system" => "cooling_system",
        "has_voltage" => "Has Voltage regulator",
        "ccm_asset_sub_type_id" => "asset sub type id",
        "temperature_recording_system" => "temperature recording system",
        "type_recording_system" => "type recording system",
        "refrigerator_gas_type" => "refrigerator gas type",
        "backup_generator" => "backup generator",
        "ccm_model_name" => "ccm_model_name",
        "asset_dimension_length" => "Dimensions",
        "asset_dimension_width" => "Dimensions",
        "asset_dimension_height" => "Dimensions",
        "gross_capacity" => "Capacity",
        "net_capacity" => "Capacity",
        "placed_at" => "placed at",
        "no_of_phases" => "No Of Phases",
        "n" => "No",
        "make" => "make"
    );

    /**
     * 
     * $_list
     * @var type 
     * @ccm_model_id: Model
     * @ccm_asset_sub_type_id: Asset Sub Type Id
     * @vvm_type_id: VVM Type id
     * @type_recording_system: Type Recording System
     * @refrigerator_gas_type: Refrigerator Gas Type
     * @backup_generator: Backup Generator
     * @make: Make
     * @type_recording_system: Type Recording System
     */
    private $_list = array(
        'ccm_model_id' => array('' => 'Select Make First'),
        'stakeholder_id' => array(),
        'ccm_asset_sub_type_id' => array(),
        'type_recording_system' => array(),
        'refrigerator_gas_type' => array(),
        'backup_generator' => array(),
        'make' => array(),
        'temperature_recording_system' => array()
    );

    /**
     * Private Variable
     * 
     * Array
     * 
     * $_radio
     * @var type 
     * 
     * 
     */
    private $_radio = array(
        'placed_at' => array(
            "1" => "Unallocated",
            "2" => "Select Store",
        ),
        'no_of_phases' => array(
            "1" => "One",
            "3" => "Three",
        ),
        'has_voltage' => array(
            "1" => "Yes",
            "0" => "No",
        )
    );

    /**
     * Private Variable
     * 
     * Array
     * 
     * 
     * $_checkbox
     * @var type 
     */
    private $_checkbox = array();

    /**
     * Private Variable
     * 
     * Array
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "ccm_id" => "pkId",
        "model_hidden" => "pkId"
    );

    /**
     * Initializes Form fields
     */
    public function init() {


        /**
         * Generate Makes Combo
         */
        $makes = new Model_CcmMakes();
        $makes->form_values = array('type_id' => Model_CcmAssetTypes::COLDROOM);
        $result3 = $makes->getAllMakesByAssetType();
        $this->_list["make"][''] = "Select";
        foreach ($result3 as $rs) {
            $this->_list["make"][$rs['pkId']] = $rs['ccmMakeName'];
        }
        /**
         * Generate Asset Sub Type Combo
         */
        $asset_type = new Model_CcmAssetTypes();
        $asset_type->form_values = array('parent_id' => Model_CcmAssetTypes::COLDROOM);
        $result4 = $asset_type->getAssetSubTypes();
        $this->_list["ccm_asset_sub_type_id"][''] = "Select";
        foreach ($result4 as $rs) {
            $this->_list["ccm_asset_sub_type_id"][$rs['pkId']] = $rs['assetTypeName'];
        }

        /**
         * Generate Type Rrecording System Combo
         */
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::TYPE_OF_RECORDING_SYSTEM);
        $result5 = $list_master->getListDetailByType();
        $this->_list["type_recording_system"][''] = "Select";
        foreach ($result5 as $rs) {
            $this->_list["type_recording_system"][$rs['pkId']] = $rs['listValue'];
        }

        /**
         * Generate Refrigerator Gas Type Combo
         */
        $list_master->form_values = array('pk_id' => Model_ListMaster::REFRRIGERATOR_GAS_TYPE);
        $result6 = $list_master->getListDetailByType();
        $this->_list["refrigerator_gas_type"][''] = "Select";
        foreach ($result6 as $rs) {
            $this->_list["refrigerator_gas_type"][$rs['pkId']] = $rs['listValue'];
        }

        /**
         * Generate Backup Generator Combo
         */
        $list_master->form_values = array('pk_id' => Model_ListMaster::HAS_WORKING_BACKUP_GENERATOR);
        $result7 = $list_master->getListDetailByType();
        $this->_list["backup_generator"][''] = "Select";
        foreach ($result7 as $rs) {
            $this->_list["backup_generator"][$rs['pkId']] = $rs['listValue'];
        }

        /**
         * Generate Temperature Recording System Combo
         */
        $list_master->form_values = array('pk_id' => Model_ListMaster::TEMPERATURE_RECORDING_SYSTEM);
        $result8 = $list_master->getListDetailByType();
        $this->_list["temperature_recording_system"][''] = "Select";
        foreach ($result8 as $rs) {
            $this->_list["temperature_recording_system"][$rs['pkId']] = $rs['listValue'];
        }
        /**
         * Generate Text Fields
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * Cooling System Text Field
                 */
                case "cooling_system":
                    parent::createText($col);
                    break;
                /**
                 * Working Since Text Field
                 */
                case "working_since":
                    parent::createReadOnlyText($col);
                    break;
                /**
                 * Gross Capacity Text Field
                 */
                case "gross_capacity":
                    parent::createTextWithPlaceholder($col, "Gross Capacity");
                    break;
                /**
                 * Net Capacity Text Field
                 */
                case "net_capacity":
                    parent::createTextWithPlaceholder($col, "Net Capacity");
                    break;
                /**
                 * Asset Dimension Length Text Field
                 */
                case "asset_dimension_length":
                    parent::createTextWithPlaceholder($col, "Length");
                    break;
                /**
                 * Asset Dimension Width Text Field
                 */
                case "asset_dimension_width":
                    parent::createTextWithPlaceholder($col, "Width");
                    break;
                /**
                 * Asset Dimension Height Text Field
                 */
                case "asset_dimension_height":
                    parent::createTextWithPlaceholder($col, "Height");
                    break;
                default:
                    break;
            }
            /**
             * Generate Select Boxes
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            /**
             * Generate Radio Buttons
             */
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }

            /**
             * Generate CheckBoxes
             */
            if (in_array($col, array_keys($this->_checkbox))) {
                parent::createMultiCheckbox($col, $this->_checkbox[$col]);
            }
        }
        //foreach loop
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
     *  Add Hidden Fields
     * 
     * Function: 
     * 
     * To Add hidden fields
     * 
     */
    public function addHidden() {
        parent::createHidden("id");
    }
}