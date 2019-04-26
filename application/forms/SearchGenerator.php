<?php

/**
 * Form_SearchGenerator
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Search Generator
 */
class Form_SearchGenerator extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @ccm_status_list_id: Working Status
     * @serial_number: Serial Number
     * @source_id: Source of supply
     * @asset_id: Asset ID
     * @ccm_make_id: Make
     * @ccm_model_id: Model
     * @working_since_from: Working Since Year
     * @working_since_to: Working Since Year
     * @report_type: Report Type
     * @placed_at: Placed At
     * 
     * @var type 
     */
    private $_fields = array(
        "ccm_status_list_id" => "Working Status",
        "serial_number" => "Serial Number",
        "source_id" => "Source of supply",
        "asset_id" => "Asset ID",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "working_since_from" => "Working Since Year",
        "working_since_to" => "Working Since Year",
        "report_type" => "Report Type",
        "placed_at" => "Placed At"
    );

    /**
     * $_list
     * 
     * List
     * @ccm_make_id
     * @ccm_model_id
     * 
     * @var type 
     */
    private $_list = array(
        'ccm_make_id' => array(),
        'ccm_model_id' => array('' => 'Select Make First')
    );

    /**
     * $_hidden
     * 
     * Hidden
     * @office_id
     * @combo1_id
     * @warehouse_id
     * @model_id
     * 
     * @var type 
     */
    private $_hidden = array(
        "office_id" => "",
        "combo1_id" => "",
        "warehouse_id" => "",
        "model_id" => ""
    );

    /**
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'placed_at' => array(
            "1" => "Select Store",
            "0" => "Unallocated",
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
     */
    public function init() {
        //Generate working status Combo
        $ccm_status_list = new Model_CcmStatusList();
        $result1 = $ccm_status_list->getStatusLists();
        $this->_list["ccm_status_list_id"][''] = "Select working status";
        foreach ($result1 as $row) {
            $this->_list["ccm_status_list_id"][$row['pkId']] = $row['ccmStatusListName'];
        }

        //Generate Make Combo
        $makes = new Model_CcmMakes();
        $makes->form_values = array('type_id' => Model_CcmAssetTypes::GENERATOR);

        $result3 = $makes->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select";
        if ($result3) {
            foreach ($result3 as $wh) {
                $this->_list["ccm_make_id"][$wh['pkId']] = $wh['ccmMakeName'];
            }
        }

        //Generate source of supply Combo
        $stakeholder = new Model_Stakeholders();
        $stakeholder->form_values['type'] = 1;
        $result2 = $stakeholder->getAllStakeholders();
        $this->_list["source_id"][''] = "Select Source Of Supply";
        foreach ($result2 as $row2) {
            $this->_list["source_id"][$row2['pkId']] = $row2['stakeholderName'];
        }
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
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "serial_number":
                case "asset_id":
                    parent::createText($col);
                    break;
                case "working_since_from":
                case "working_since_to":
                    parent::createReadOnlyText($col);
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
