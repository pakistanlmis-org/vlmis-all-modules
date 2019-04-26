<?php

/**
 * Form_Iadmin_LocationTypeSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Location Type Search
 */
class Form_Iadmin_LocationTypeSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @location_type_name: Location Type Name
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "location_type_name" => "Location Type Name",
        "status" => "Status"
    );

    /**
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'status' => array(
            "3" => "All",
            "1" => "Active",
            "2" => "Inactive"
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        // set css attributes and classes
        foreach ($this->_fields as $col => $name) {
            if ($col == "location_type_name") {
                parent::createText($col);
            }

            // Set Attributes to forms elements.
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

}
