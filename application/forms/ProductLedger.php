<?php

/**
 * Form_ProductLedger
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Product Ledger
 * 
 * Inherits: Zend Form
 */
class Form_ProductLedger extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @Product: Product
     * @from_date: From Date
     * @to_date: To Date
     * @detail_summary: Detail Summary
     * 
     * @var type 
     */
    private $_fields = array(
        "product" => "Product",
        "from_date" => "From Date",
        "to_date" => "To Date",
        "detail_summary" => "detail_summary"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * @product
     * 
     * @var type 
     */
    private $_list = array(
        'product' => array()
    );

    /**
     * $_radio
     * @var type 
     */
    private $_radio = array(
        'detail_summary' => array(
            "1" => "Summary",
            "2" => "Detail",
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $item_pack_sizes = new Model_ItemPackSizes();
        $result1 = $item_pack_sizes->getAllManageItems();
        $this->_list["product"][''] = 'Select';
        if ($result1 && count($result1) > 0) {
            foreach ($result1 as $whs) {
                $this->_list["product"][$whs['pkId']] = $whs['itemName'];
            }
        }

        foreach ($this->_fields as $col => $name) {

            switch ($col) {
                case "from_date":
                case "to_date":
                    parent::createReadOnlyText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
            if (in_array($col, array_keys($this->_radio))) {
                parent::createRadio($col, $this->_radio[$col]);
            }
        }
    }

}
