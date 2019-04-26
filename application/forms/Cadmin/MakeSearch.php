<?php

/**
 * Form_Cadmin_MakeSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin Make Search
 */
class Form_Cadmin_MakeSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_fields = array(
        "name" => "Make Name",
        "status" => "Status"
    );

    /**
     * $_radio
     * 
     * Private Variable
     * 
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

        foreach ($this->_fields as $col => $name) {
            if ($col == "name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

}
