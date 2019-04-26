<?php

/**
 * Form_Iadmin_VvmTypeAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin VVM Type Add
 */
class Form_Iadmin_VvmTypeAdd extends Form_Base {

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
     * 
     * Private Variable
     * 
     * @status: Active,
     *          Inactive
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
     * Hidden
     * @vvm_type_id: Pk Id
     * @vvm_type_name_hidden: vvm_type_name_hidden
     * 
     * @var type 
     */
    private $_hidden = array(
        "vvm_type_id" => "pkId",
        "vvm_type_name_hidden" => "vvm_type_name_hidden"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        foreach ($this->_hidden as $col => $name) {
            if ($col == "vvm_type_id" || $col == "vvm_type_name_hidden") {
                parent::createHidden($col);
            }
        }

        foreach ($this->_fields as $col => $name) {
            if ($col == "vvm_type_name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
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
