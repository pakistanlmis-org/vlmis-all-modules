<?php

/**
 * Form_SearchIcePack
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Search Ice Pack
 */
class Form_SearchIcePack extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @placed_at: Placed At
     * @ccm_make_id: Make
     * @ccm_model_id: Model
     * @report_type: Summary
     * 
     * @var type 
     */
    private $_fields = array(
        "placed_at" => "Placed At",
        "ccm_make_id" => "Make",
        "ccm_model_id" => "Model",
        "report_type" => "Summary"
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
    );

    /**
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
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'report_type' => array(
            "0" => "Detail View",
            "1" => "Summary View",
        ),
        'placed_at' => array(
            "1" => "Select Store",
            "0" => "Unallocated",
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Asset Id Equipment Code Combo
        $models = new Model_CcmModels();
        $models->form_values['asset_type'] = Model_CcmAssetTypes::ICEPACKS;
        $result0 = $models->getAllAssetsByType();
        $this->_list["catalogue_id"][''] = "Select asset";
        foreach ($result0 as $row) {
            $this->_list["catalogue_id"][$row['catalogueId']] = $row['catalogueId'] . ' - ' . $row['ccmMakeName'] . ' - ' . $row['ccmModelName'];
        }

        //Generate Makes Combo
        $make = new Model_CcmMakes();
        $make->form_values = array('type_id' => Model_CcmAssetTypes::ICEPACKS);
        $result1 = $make->getAllMakesByAssetType();
        $this->_list["ccm_make_id"][''] = "Select Makes";
        foreach ($result1 as $make) {
            $this->_list["ccm_make_id"][$make['pkId']] = $make['ccmMakeName'];
        }

        //Generate Models Combo
        $this->_list["ccm_model_id"][''] = "Select Make First";
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
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
