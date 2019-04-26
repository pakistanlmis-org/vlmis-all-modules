<?php

/**
 * Form_Cadmin_MakeAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Make Add
 */
class Form_Cadmin_MakeAdd extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @ccm_make_name: Make Name
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "ccm_make_name" => "Make Name",
        "status" => "Status"
    );

    /**
     * $_radio
     * 
     * Private Variable
     * 
     * @status: Active,Inactive
     * 
     * @var type 
     */
    private $_radio = array(
        'status' => array(
            "1" => "Active",
            "0" => "In Active"
        )
    );

    /**
     * $_hidden
     * 
     * Private Variable
     * 
     * @make_id: pkId
     * 
     * @var type 
     */
    private $_hidden = array(
        "make_id" => "pkId"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        foreach ($this->_fields as $col => $name) {
            if ($col == "ccm_make_name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            if ($col == "make_id") {
                parent::createHidden($col);
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
