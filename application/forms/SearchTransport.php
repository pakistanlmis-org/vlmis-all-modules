<?php

/**
 * Form_SearchTransport
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Search Transport
 */
class Form_SearchTransport extends Form_Base {

    /**
     * Fields 
     * for Form_SearchTransport
     * 
     * 
     * ccm_asset_sub_type_id
     * ccm_status_list_id
     * source_id
     * fuel_type_id
     * registration_no
     * ccm_make_id
     * ccm_model_id
     * manufacture_year_from
     * manufacture_year_to
     * placed_at
     * report_type
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "ccm_asset_sub_type_id" => "Transport Type",
        "ccm_status_list_id" => "Working Status",
        "source_id" => "Source of supply",
        "fuel_type_id" => "Fuel Type",
        "registration_no" => "Registration No",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "manufacture_year_from" => "Manufacture Year",
        "manufacture_year_to" => "Manufacture Year",
        "placed_at" => "Placed At",
        "report_type" => "Report Type"
    );

    /**
     * Hidden fields
     * for Form_SearchTransport
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
     * Combo boxes
     * for Form_SearchTransport
     * 
     * 
     * ccm_asset_sub_type_id
     * ccm_status_list_id
     * source_id
     * ccm_make_id
     * fuel_type_id
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'ccm_asset_sub_type_id' => array(),
        'ccm_status_list_id' => array(),
        'source_id' => array(),
        'ccm_make_id' => array(),
        'ccm_model_id' => array(),
        'fuel_type_id' => array()
    );

    /**
     * Radio buttons
     * for Form_SearchTransport
     * 
     * 
     * placed_at
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
        'report_type' => array(
            "0" => "Detail View",
            "1" => "Summary View",
        )
    );

    /**
     * Initializes Form Fields
     * for Form_SearchTransport
     */
    public function init() {
        //Generate working status Combo
        $ccm_status_list = new Model_CcmStatusList();
        $result1 = $ccm_status_list->getStatusLists();
        $this->_list["ccm_status_list_id"][''] = "Select working status";
        foreach ($result1 as $row) {
            $this->_list["ccm_status_list_id"][$row['pkId']] = $row['ccmStatusListName'];
        }

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

        //Generate source of supply Combo
        $stakeholder = new Model_Stakeholders();
        $stakeholder->form_values['type'] = 1;
        $result2 = $stakeholder->getAllStakeholders();
        $this->_list["source_id"][''] = "Select Source Of Supply";
        foreach ($result2 as $row2) {
            $this->_list["source_id"][$row2['pkId']] = $row2['stakeholderName'];
        }

        //Generating hidden fields
        //for Form_SearchTransport
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

        //Generating fields
        //for Form_SearchTransport
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "registration_no":
                    parent::createText($col);
                    break;
                case "manufacture_year_from":
                case "manufacture_year_to":
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
        }
    }

    /**
     * Add Hidden Fields
     * for Form_SearchTransport
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
