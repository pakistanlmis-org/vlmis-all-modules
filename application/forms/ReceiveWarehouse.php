<?php

/**
 * Form_ReceiveWarehouse
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Receive Warehouse
 */
class Form_ReceiveWarehouse extends Form_Base {

    /**
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "issue_no" => "Issue No",
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        foreach ($this->_fields as $col => $name) {
            if ($col == "issue_no") {
                parent::createText($col);
                }
        }
    }

}
