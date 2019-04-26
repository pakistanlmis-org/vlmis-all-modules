<?php

/**
 * Form_ColdChainQtyAdd
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cold Chain Qty Add
 */
class Form_ColdChainQtyAdd extends Form_Base {

    /**
     * Fields for Form_ColdChainQtyAdd
     * 
     * 
     * coldchain
     * stockDetailId
     * qty.
     * product
     * rem_quantity
     * batch_id
     * quantity
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        'coldchain' => '',
        'stockDetailId' => '',
        'qty' => '',
        'batch_id' => '',
        'product' => '',
        'rem_quantity' => '',
        'quantity' => ''
    );

    /**
     * Combo boxes for Form_ColdChainQtyAdd
     * 
     * 
     * coldchain
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'coldchain' => array()
    );

    /**
     * Initializes Form Fields
     * for Form_ColdChainQtyAdd
     */
    public function init() {

        $cold_chain = new Model_ColdChain();
        $result = $cold_chain->getListing();
        foreach ($result as $coldchain) {
            $this->_list["coldchain"][$coldchain['pk_id']] = $coldchain['asset_id'] . "-" . $coldchain['ccm_asset_type_id'];
        }
        // Generate fields for 
        // Form_ColdChainQtyAdd
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "qty":
                case "product":
                case "quantity":
                case "batch_id":
                case "rem_quantity":
                case "stockDetailId":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            // Generate combo 
            // for Form_ColdChainQtyAdd
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

}
