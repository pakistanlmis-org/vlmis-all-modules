<?php

/**
 * Form_AddGenerator
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Generator
 */
class Form_AddGenerator extends Form_Base {

    /**
     * Fields for form Form_AddGenerator
     * make
     * ccm_model_id
     * serial_number
     * working_since
     * no_of_phases
     * power_rating
     * power_source
     * automatic_start
     * use_for
     * equipment_utilizations
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "make" => "Make",
        "ccm_model_id" => "Model",
        "serial_number" => "Serial Number",
        "working_since" => "Working Since Year",
        "no_of_phases" => "No Of Phases",
        "power_rating" => "Power Rating",
        "power_source" => "Power Source",
        "automatic_start" => "Automatic Start Mechanism",
        "use_for" => "Use for",
        "equipment_utilizations" => "Equipment Utilizations",
        "cold_room" => "Cold Room"
    );

    /**
     * Comboboxes for form Form_AddGenerator
     * make
     * ccm_model_id
     * power_source
     * use_for
     * equipment_utilizations
     * $_list
     * @var type 
     */
    private $_list = array(
        'make' => array(),
        'ccm_model_id' => array('' => 'Select Make First'),
        'power_source' => array(),
        'use_for' => array(),
        'equipment_utilizations' => array()
    );

    /**
     * Radio buttons for form Form_AddGenerator
     * no_of_phases
     * automatic_start
     * placed_at
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'no_of_phases' => array(
            "1" => "One",
            "3" => "Three",
        ),
        'automatic_start' => array(
            "1" => "Yes",
            "2" => "No",
        ),
        'placed_at' => array(
            "1" => "Unallocated",
            "2" => "Select Store",
        )
    );

    /**
     * Check boxes for form Form_AddGenerator
     * $_checkbox
     * @var type 
     */
    private $_checkbox = array();

    /**
     * Hidden fields for form Form_AddGenerator
     * ccm_id
     * model_hidden
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
         * Generate Make Combo
         */
        $makes = new Model_CcmMakes();
        $makes->form_values = array('type_id' => Model_CcmAssetTypes::GENERATOR);
        $result3 = $makes->getAllMakesByAssetType();
        $this->_list["make"][''] = "Select";
        if ($result3) {
            foreach ($result3 as $wh) {
                $this->_list["make"][$wh['pkId']] = $wh['ccmMakeName'];
            }
        }


        /**
         * Generate source of supply Combo
         */
        $cold_chain = new Model_ColdChain();
        $cold_chain->form_values = array('type_id' => '1,3');
        $result = $cold_chain->getColdchainByAssetType();
        $this->_list["cold_room"][''] = "Select";
        foreach ($result as $row2) {
            $this->_list["cold_room"][$row2['pk_id']] = $row2['asset_name'];
        }
        /**
         * Generate Power Source Combo
         */
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::FUEL_TYPE);
        $result1 = $list_master->getListDetailByType();
        $this->_list["power_source"][''] = "Select";
        if ($result1) {
            foreach ($result1 as $rs) {
                $this->_list["power_source"][$rs['pkId']] = $rs['listValue'];
            }
        }
        /**
         * Generate Used For Combo
         */
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::USE_FOR);
        $result2 = $list_master->getListDetailByType();
        $this->_list["use_for"][''] = "Select";
        if ($result2) {
            foreach ($result2 as $rs) {
                $this->_list["use_for"][$rs['pkId']] = $rs['listValue'];
            }
        }

        /**
         * Generate Utilizations Combo
         */
        $list_master = new Model_ListMaster();
        $list_master->form_values = array('pk_id' => Model_ListMaster::USE_FOR);
        $result2 = $list_master->getListDetailByType();
        $this->_list["equipment_utilizations"][''] = "Select";
        foreach ($result2 as $rs) {
            $this->_list["equipment_utilizations"][$rs['pkId']] = $rs['listValue'];
        }
        /**
         * Generate Utilizations Combo
         * Adding fileds to the form
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                /**
                 * Power Rating Text Field
                 * Serial Number Text Field
                 */
                case "power_rating":
                case "serial_number":
                    parent::createText($col);
                    break;
                /**
                 * Working Since Text Field
                 */
                case "working_since":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }
            /**
             * Generate Select Boxes
             * Fields
             * make
             * ccm_model_id
             * power_source
             * use_for
             * equipment_utilizations
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
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
        /**
         *  Adding hidden fields
         * foreach loop
         */
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
        parent::createHiddenWithValidator("id");
    }

}
