<?php

/**
 * Form_AddMain
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Add Main
 */
class Form_AddMain extends Form_Base {

    /**
     * Fields for form Form_AddMain
     * 
     * 
     * asset_id
     * ccm_status_list_id
     * source_id
     * reason
     * utilization
     * placed_at
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "asset_id" => "Asset Id",
        "ccm_status_list_id" => "Working Status",
        "source_id" => "Source of supply",
        "reason" => "Reason",
        "utilization" => "Utilization",
        "placed_at" => "Placed At",
    );

    /**
     * Combo for Form_AddMain
     * 
     * 
     * ccm_status_list_id
     * source_id
     * reason
     * utilization
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'ccm_status_list_id' => array(),
        'source_id' => array(),
        'reason' => array(),
        'utilization' => array()
    );

    /**
     * Radios for form
     * 
     * 
     * placed_at
     * 
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'placed_at' => array(
            "0" => "Unallocated",
            "1" => "Select Store",
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        /**
         * Generate working status Combo
         */
        $ccm_status_list = new Model_CcmStatusList();
        $result1 = $ccm_status_list->getStatusLists();
        $this->_list["ccm_status_list_id"][''] = "Select working status";
        foreach ($result1 as $row) {
            $this->_list["ccm_status_list_id"][$row['pkId']] = $row['ccmStatusListName'];
        }
        /**
         * Generate source of supply Combo
         */
        $stakeholder = new Model_Stakeholders();
        $stakeholder->form_values['type'] = 1;
        $result2 = $stakeholder->getAllStakeholders();
        $this->_list["source_id"][''] = "Select Source Of Supply";
        foreach ($result2 as $row2) {
            $this->_list["source_id"][$row2['pkId']] = $row2['stakeholderName'];
        }
        /**
         * Generate Utilizations Combo
         */
        $ccm_status_list1 = new Model_CcmStatusList();
        $data = $ccm_status_list1->getAllUtilizations();
        $this->_list["utilization"][''] = "Select ";
        foreach ($data as $utilization) {
            $this->_list["utilization"][$utilization['pkId']] = $utilization['ccmStatusListName'];
        }
        /**
         * Generate Text Fields
         */
        foreach ($this->_fields as $col => $name) {
            if ($col == "asset_id") {
                parent::createText($col);
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
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHidden("id");
    }

}
