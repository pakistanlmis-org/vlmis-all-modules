<?php

/**
 * Form_SearchRefrigerator
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Search Refrigerator
 */
class Form_SearchRefrigerator extends Form_Base {

    /**
     * Fields
     * for Form_SearchRefrigerator
     * 
     * 
     * ccm_asset_sub_type_id
     * source_id
     * ccm_status_list_id
     * asset_id
     * catalogue_id
     * ccm_make_id
     * ccm_model_id
      * serial_number
     * cfc_free
     * gross_capacity_from
     * gross_capacity_to
     * working_since_from
     * working_since_to
     * placed_at
     * report_type
     *  
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "ccm_asset_sub_type_id" => "Transport Type",
        "source_id" => "Source of supply",
        "ccm_status_list_id" => "Working Status",
        "asset_id" => "Asset ID",
        "catalogue_id" => "Catalogue ID",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "serial_number" => "Serial Number",
        "cfc_free" => "CFC Free Sticker",
        "gross_capacity_from" => "Gross Capacity From",
        "gross_capacity_to" => "Gross Capacity From",
        "working_since_from" => "Working Since From",
        "working_since_to" => "Working Since To",
        "placed_at" => "Placed At",
        "report_type" => "Report Type"
    );

    /**
     * Combo boxes
     * for Form_SearchRefrigerator
     * 
     * 
     * ccm_asset_sub_type_id
     * ccm_status_list_id
     * source_id
     * ccm_make_id
     * ccm_model_id
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
        'ccm_model_id' => array()
    );

    /**
     * Hidden fields
     * for Form_SearchRefrigerator
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
     * for Form_SearchRefrigerator
     * 
     * 
     * placed_at
     * report_type
     * cfc_free
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
        ),
        'cfc_free' => array(
            "2" => "Not Applicable",
            "1" => "Yes",
            "0" => "No",
        )
    );

    /**
     * Initializes Form Fields
     * for Form_SearchRefrigerator
     */
    public function init() {
        //Generate Asset Sub Type Combo
        $asset_types = new Model_CcmAssetTypes();
        $asset_types->form_values = array('parent_id' => Model_CcmAssetTypes::REFRIGERATOR);
        $result3 = $asset_types->getAssetSubTypes();
        $this->_list["ccm_asset_sub_type_id"][''] = "Select Asset Sub Types";
        if ($result3) {
            foreach ($result3 as $assetsubtype) {
                $this->_list["ccm_asset_sub_type_id"][$assetsubtype['pkId']] = $assetsubtype['assetTypeName'];
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

        //Generate working status Combo
        $ccm_status_list = new Model_CcmStatusList();
        $result1 = $ccm_status_list->getStatusLists();
        $this->_list["ccm_status_list_id"][''] = "Select working status";
        foreach ($result1 as $row) {
            $this->_list["ccm_status_list_id"][$row['pkId']] = $row['ccmStatusListName'];
        }

        //Generate Makes Combo
        $make = new Model_CcmMakes();
        $make->form_values = array('type_id' => Model_CcmAssetTypes::REFRIGERATOR);
        $result1 = $make->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select Makes";
        foreach ($result1 as $make) {
            $this->_list["ccm_make_id"][$make['pkId']] = $make['ccmMakeName'];
        }

        //Generate Models Combo
        $this->_list["ccm_model_id"][''] = "Select Make First";

        //Generating hidden fields
        //for Form_SearchRefrigerator
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
        //for Form_SearchRefrigerator
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "catalogue_id":
                case "asset_id":
                case "serial_number":
                case "gross_capacity_from":
                case "gross_capacity_to":
                    parent::createText($col);
                    break;
                case "working_since_from":
                case "working_since_to":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_SearchRefrigerator
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
