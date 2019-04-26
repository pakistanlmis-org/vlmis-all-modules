<?php

/**
 * Form_Iadmin_TransactionTypeAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Trannsaction Type Add
 */
class Form_Iadmin_TransactionTypeAdd extends Form_Base {

    /**
     * Fields 
     * for Form_Iadmin_TransactionTypeAdd
     * 
     * 
     * 
     * transaction_type_name
     * nature
     * is_adjustment
     * status
     * 
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "transaction_type_name" => "Transaction Type Name",
        "nature" => "Nature",
        "is_adjustment" => "Adjustment",
        "status" => "Status"
    );

    /**
     * Radio buttons 
     * for Form_Iadmin_TransactionTypeAdd
     * 
     * 
     * nature
     * 
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'nature' => array(
            "+" => "Positive",
            "-" => "Negative"
        ),
        'status' => array(
            "1" => "Active",
            "0" => "In Active"
        )
    );

    /**
     * Hidden fields 
     * for Form_Iadmin_TransactionTypeAdd
     * 
     * 
     * transaction_type_id
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "transaction_type_id" => "pkId"
    );

    /**
     * Initializes Form Fields
     * for Form_Iadmin_TransactionTypeAdd
     */
    public function init() {
        foreach ($this->_hidden as $col => $name) {
            if ($col == "transaction_type_id") {
                parent::createHidden($col);
            }
        }

        // Generating fields 
        // for Form_Iadmin_TransactionTypeAdd
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "transaction_type_name":
                    parent::createText($col);
                    break;
                case "nature":
                    parent::createRadioWithoutMultioptions($col);
                    break;
                case "is_adjustment":
                    parent::createCheckbox1($col);
                    break;
                default:
                    break;
            }

            //Generating radio
            // for Form_Iadmin_TransactionTypeAdd
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Iadmin_TransactionTypeAdd
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
