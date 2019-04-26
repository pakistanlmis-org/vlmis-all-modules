<?php

/**
 * Form_Cadmin_StatusListAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Status List Add
 */
class Form_Cadmin_StatusListAdd extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @ccm_status_list_name: CCM Status List Name
     * @type: CCM Status List Type
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "ccm_status_list_name" => "CCM Status List Name",
        "type" => "CCM Status List Type",
        "status" => "Status"
    );

    /**
     * $_radio
     * 
     * Private Variable
     * 
     * @status: Active, Inactive
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
     * @status_list_id: pk Id
     * 
     * @var type 
     */
    private $_hidden = array(
        "status_list_id" => "pkId"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //foreach loop
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "ccm_status_list_name":
                case "type":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }

        foreach ($this->_hidden as $col => $name) {
            if ($col == "status_list_id") {
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
