<?php

/**
 * Form_SearchColdRoom
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Search Cold Room
 */
class Form_SearchColdRoom extends Form_Base {

    /**
     * Fields 
     * for Form_SearchColdRoom
     * 
     * 
     * asset_id
     * serial_number
     * working_since_from
     * working_since_to
     * ccm_model_id
     * stakeholder_id
     * warehouse_id
     * ccm_status_list_id
     * source_id
     * cooling_system
     * has_voltage
     * ccm_asset_sub_type_id
     * temperature_recording_system
     * type_recording_system
     * refrigerator_gas_type
     * backup_generator
     * ccm_model_name
     * asset_dimension_length
     * asset_dimension_width
     * asset_dimension_height
     * gross_capacity
     * capacity_from
     * net_capacity
     * capacity_to
     * placed_at
     * no_of_phases
     * n
     * make
     * year_supply_from
     * year_supply_to
     * report_type
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "asset_id" => "Asset Id",
        "serial_number" => "Serial Number",
        "working_since_from" => "Working Since Year from",
        "working_since_to" => "Working Since Year to",
        "ccm_model_id" => "Model",
        "stakeholder_id" => "Stakeholder",
        "warehouse_id" => "Warehouse",
        "ccm_status_list_id" => "Working Status",
        "source_id" => "Source of supply",
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
        "capacity_from" => "Capacity from",
        "net_capacity" => "Capacity",
        "capacity_to" => "Capacity to",
        "placed_at" => "placed at",
        "no_of_phases" => "No Of Phases",
        "n" => "No",
        "make" => "make",
        "year_supply_from" => "Year of Supply from",
        "year_supply_to" => "Year of Supply to",
        "report_type" => "Report Type"
    );

    /**
     * Combo boxes
     * for Form_SearchColdRoom
     * 
     * ccm_model_id
     * ccm_status_list_id
     * source_id
     * stakeholder_id
     * ccm_asset_sub_type_id
     * type_recording_system
     * type_recording_system
     * refrigerator_gas_type
     * backup_generator
     * backup_generator
     * make
     * temperature_recording_system
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'ccm_model_id' => array('' => 'Select Make First'),
        'ccm_status_list_id' => array(),
        'source_id' => array(),
        'stakeholder_id' => array(),
        'ccm_asset_sub_type_id' => array(),
        'type_recording_system' => array(),
        'refrigerator_gas_type' => array(),
        'backup_generator' => array(),
        'make' => array(),
        'temperature_recording_system' => array()
    );

    /**
     * Hidden fields
     * for Form_SearchColdRoom
     * 
     * 
     * office_id
     * combo1_id
     * warehouse_id
     * model_id
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "office_id" => "",
        "combo1_id" => "",
        "warehouse_id" => "",
        "model_id" => ""
    );

    /**
     * Radio buttons
     * for Form_SearchColdRoom
     * 
     * 
     * model_id
     * no_of_phases
     * has_voltage
     * report_type
     * 
     * 
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'placed_at' => array(
            "1" => "Select Store",
            "0" => "Unallocated",
        ),
        'no_of_phases' => array(
            "1" => "One",
            "3" => "Three",
        ),
        'has_voltage' => array(
            "1" => "Yes",
            "0" => "No",
        ),
        'report_type' => array(
            "0" => "Detail View",
            "1" => "Summary View",
        )
    );

    /**
     * $_checkbox
     * @var type 
     */
    private $_checkbox = array();

    /**
     * Initializes Form Fields
     * for Form_SearchColdRoom
     */
    public function init() {
        //Generate working status Combo
        $ccm_status_list = new Model_CcmStatusList();
        $result1 = $ccm_status_list->getStatusLists();
        $this->_list["ccm_status_list_id"][''] = "Select working status";
        foreach ($result1 as $row) {
            $this->_list["ccm_status_list_id"][$row['pkId']] = $row['ccmStatusListName'];
        }
        //Generate source of supply Combo
        $stakeholder = new Model_Stakeholders();
        $stakeholder->form_values['type'] = 1;
        $result2 = $stakeholder->getAllStakeholders();
        $this->_list["source_id"][''] = "Select Source Of Supply";
        foreach ($result2 as $row2) {
            $this->_list["source_id"][$row2['pkId']] = $row2['stakeholderName'];
        }

        //Generate Make Combo
        $makes = new Model_CcmMakes();
        $makes->form_values = array('type_id' => Model_CcmAssetTypes::COLDROOM);
        $result3 = $makes->getAllMakesByAssetType();
        $this->_list["make"][''] = "Select";
        foreach ($result3 as $rs) {
            $this->_list["make"][$rs['pkId']] = $rs['ccmMakeName'];
        }

        //Generate Asset Sub Type Combo
        $asset_type = new Model_CcmAssetTypes();
        $asset_type->form_values = array('parent_id' => Model_CcmAssetTypes::COLDROOM);
        $result4 = $asset_type->getAssetSubTypes();
        $this->_list["ccm_asset_sub_type_id"][''] = "Select";
        foreach ($result4 as $rs) {
            $this->_list["ccm_asset_sub_type_id"][$rs['pkId']] = $rs['assetTypeName'];
        }
        //Generating Hidden fields
        foreach ($this->_hidden as $col => $name) {
            switch ($col) {
                case "office_id":
                case "combo1_id":
                case "warehouse_id":
                case "model_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
        // Generating fields
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "year_supply_from":
                case "year_supply_to":
                case "cooling_system":
                case "asset_id":
                    parent::createText($col);
                    break;
                case "capacity_from":
                case "capacity_to":
                case "gross_capacity":
                    parent::createTextWithPlaceholder($col, "Gross Capacity");
                    break;
                case "working_since_from":
                case "working_since_to":
                    parent::createReadOnlyText($col);
                    break;
                case "net_capacity":
                    parent::createTextWithPlaceholder($col, "Net Capacity");
                    break;
                case "asset_dimension_length":
                    parent::createTextWithPlaceholder($col, "Length");
                    break;
                case "asset_dimension_width":
                    parent::createTextWithPlaceholder($col, "Width");
                    break;
                case "asset_dimension_height":
                    parent::createTextWithPlaceholder($col, "Height");
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }

            if (in_array($col, array_keys($this->_checkbox))) {
                parent::createMultiCheckbox($col, $this->_checkbox[$col]);
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
