<?php

/**
 * Form_Iadmin_VvmTypeSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin VVM Type Search
 */
class Form_Iadmin_VvmTypeSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @vvm_type_name: VVM Type Name
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "vvm_type_name" => "Vvm Type Name",
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

        foreach ($this->_fields as $col => $name) {
            if ($col == "vvm_type_name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

}
