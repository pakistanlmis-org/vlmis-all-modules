<?php

/**
 * Form_AdjustmentSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Adjustmment Search
 */
class Form_AdjustmentSearch extends Form_Base {

    /**
     * Form Fields
     * @adjustment_date: Adjustment date
     * @ref_no: Reference number
     * @adjustment_no: Adjustment number
     * @adjustment_type: Adjustment type
     * @date_from: Date from
     * @date_to: Date to
     * @batch_no:Batch number
     * @product:Product
     * @expiry_date: Expiry date
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "adjustment_date" => "Adjustment Date",
        "ref_no" => "Reference number",
        "adjustment_no" => "adjustment_no",
        "adjustment_type" => "Adjustment type",
        "date_from" => "Date From",
        "date_to" => "Date To",
        "batch_no" => "batch_no",
        "product" => "Product",
        "expiry_date" => "Expiry Date"
    );

    /**
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "pk_id" => "ID",
        "hdn_batch_no" => "hdn_batch_no"
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'adjustment_type' => array(),
        'product' => array(),
        'batch_no' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $transaction_types = new Model_TransactionTypes();
        $result = $transaction_types->getSearchAdjusmentTypes();
        foreach ($result as $trans) {
            $this->_list["adjustment_type"][''] = 'Select';
            $this->_list["adjustment_type"][$trans['pkId']] = $trans['transactionTypeName'];
        }

        // Get adjusted products names
        $stock_master = new Model_StockMaster();
        $result2 = $stock_master->getAdjustedProducts();
        $this->_list["product"][''] = "All";
        foreach ($result2 as $item) {
            $this->_list["product"][$item['pkId']] = $item['itemName'];
        }

        $date_from = date('01/m/Y');
        $date_to = date('d/m/Y');

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "adjustment_no":
                case "ref_no":
                case "transaction_reference":
                case "comments":
                    parent::createText($col);
                    break;
                case "adjustment_date":
                case "expiry_date":
                    parent::createReadOnlyText($col);
                    break;
                case "date_from":
                    parent::createReadOnlyTextWithValue($col, $date_from);
                    break;
                case "date_to":
                    parent::createReadOnlyTextWithValue($col, $date_to);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
        foreach ($this->_hidden as $col => $name) {
            if ($col == "hdn_batch_no") {
                parent::createHidden($col);
            }
        }
    }

}
