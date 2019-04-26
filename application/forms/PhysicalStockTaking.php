<?php

/**
 * Form_PhysicalStockTaking
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Physical Stock Taking
 * 
 * Inherits: Zend Form
 */
class Form_PhysicalStockTaking extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @product: Product
     * @from_date: From Date
     * @to_date: To Date
     * @description: Description
     * 
     * @var type 
     */
    private $_fields = array(
        "product" => "Product",
        "from_date" => "From Date",
        "to_date" => "To Date",
        "description" => "Description"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * 
     * @var type 
     */
    private $_list = array(
        'product' => array(),
        'description' => array()
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

        $physical_stock_taking = new Model_PhysicalStockTaking();
        $result2 = $physical_stock_taking->getAllDescripiton();
        $this->_list["description"][''] = 'Select';
        if ($result2 && count($result2) > 0) {
            foreach ($result2 as $whs) {
                $this->_list["description"][$whs->getPkId()] = $whs->getDescription() . " - " . $whs->getFromDate()->format("d M Y") . " - " . $whs->getToDate()->format("d M Y");
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
        }
    }

}
