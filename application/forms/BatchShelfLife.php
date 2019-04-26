<?php

/**
 * Form_BatchShelfLife
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Batch Shelf Life
 */
class Form_BatchShelfLife extends Form_Base {

    /**
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "time_period" => "Time Period"
    );

    /**
     * Initializes Form Fields
     * with css attributes and classes. 
     */
    public function init() {

        // Set all forms elements css classes and attributes.
        foreach ($this->_fields as $col => $name) {
            if ($col == "time_period") {
                parent::createReadOnlyText($col);
            }
        }
    }

}
