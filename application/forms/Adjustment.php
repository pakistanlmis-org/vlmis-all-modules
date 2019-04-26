<?php

/**
 * Form_Adjustment
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Adjustment
 */
class Form_Adjustment extends Form_Base {

    /**
     * fileds for Form_Adjustment
     * 
     * 
     * 
     * 
     * adjustment_date
     * ref_no
     * product
     * comments
     * adjustment_type
     * batch_no
     * quantity
     * available
     * vvm_stage
     * old_vvm
     * $_fields
     * location_id
     * 
     * 
     * 
     * 
     * @var type 
     */
    private $_fields = array(
        "adjustment_date" => "Adjustment Date",
        "ref_no" => "Reference number",
        "product" => "Product",
        "comments" => "Comments",
        "adjustment_type" => "Adjustment type",
        "batch_no" => "batch_no",
        "quantity" => "quantity",
        "available" => "available",
        "vvm_stage" => "New Vvm Stage",
        "old_vvm" => "Old VVM Stage",
        "location_id" => "Location"
    );

    /**
     * Hidden fileds for Form_Adjustment
     * 
     * 
     * pk_id
     * item_unit_id
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "pk_id" => "ID",
        "item_unit_id" => "item_unit_id"
    );

    /**
     * Combo boxes for Form_Adjustment
     * 
     * adjustment_type
     * product
     * batch_no
     * vvm_stage
     * old_vvm
     * location_id
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'adjustment_type' => array(),
        'product' => array(),
        'batch_no' => array(),
        'vvm_stage' => array(),
        'old_vvm' => array(),
        'location_id' => array()
    );

    /**
     * Initializes Form Fields
     * for form Form_Adjustment
     */
    public function init() {

        $transaction_types = new Model_TransactionTypes();
        $result = $transaction_types->getAdjusmentTypes();

        foreach ($result as $trans) {
            $this->_list["adjustment_type"][''] = 'Select';
            $this->_list["adjustment_type"][$trans['pkId']] = $trans['transactionTypeName'];
        }

        $item_pack_sizes = new Model_ItemPackSizes();
        $result1 = $item_pack_sizes->getAllManageItems();
        $this->_list["product"][''] = 'Select';
        if ($result1 && count($result1) > 0) {
            foreach ($result1 as $whs) {
                $this->_list["product"][$whs['pkId']] = $whs['itemName'];
            }
        }

        $this->_list["vvm_stage"][""] = "NA";
        $this->_list["old_vvm"][""] = "NA";

        foreach ($this->_hidden as $col => $name) {
            if ($col == "item_unit_id") {

                $this->addElement("hidden", $col);
            }
        }

        foreach ($this->_fields as $col => $name) {

            switch ($col) {
                case "adjustment_date":
                    parent::createReadOnlyText($col);
                    break;
                case "available":
                case "quantity":
                case "ref_no":
                case "transaction_reference":
                case "comments":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
