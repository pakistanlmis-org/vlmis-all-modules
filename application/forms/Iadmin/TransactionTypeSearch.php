<?php

/**
 * Form_Iadmin_TransactionTypeSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Transaction Type Search
 */
class Form_Iadmin_TransactionTypeSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @transaction_type_name: Transaction Type Name
     * @nature: Nature
     * 
     * @var type 
     */
    private $_fields = array(
        "transaction_type_name" => "Transaction Type Name",
        "nature" => "Nature"
    );

    /**
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'nature' => array(
            "+" => "Positive",
            "-" => "Negative"
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        foreach ($this->_fields as $col => $name) {
            if ($col == "transaction_type_name") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

}
