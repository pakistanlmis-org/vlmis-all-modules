<?php

/**
 * Form_AddBatch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 * Function:
 *  Form for Add Batch
 */
class Form_AddBatch extends Form_Base {

    /**
     * Private Variable
     * 
     * Form Fields
     * @product_id: Product Primary key
     * @batch: Stock Batch Id
     * @production_date: Batch Production Date
     * @expiry_date: Batch Expiry Date
     * @unit_price: Batch Unit Price
     * @manufacturer: Batch Manufacturer key
     * @vvm_type_id: VVM Type id
     * 
     * @var type
     *  
     */
    private $_fields = array(
        "product_id" => "Product",
        "batch" => "Batch Id",
        "production_date" => "Production Date",
        "expiry_date" => "Expiry Date",
        "unit_price" => "Unit Price",
        "manufacturer" => "Manufacturer",
        "vvm_type_id" => "VVM Type"
    );

    /**
     * Private Variable
     * 
     * $_list
     * @var type 
     * @product_id: Product Primary key
     * @manufacturer: Batch Manufacturer key
     * @vvm_type_id: VVM Type id
     * 
     */
    private $_list = array(
        "product_id" => array(),
        "manufacturer" => array(),
        "vvm_type_id" => array(),
    );

    /**
     * Initializes Form fields
     */
    public function init() {

        /**
         * Generate VVM Type Combo
         */
        $vvmtypes = new Model_VvmTypes();
        $result3 = $vvmtypes->getAll();
        $this->_list["vvm_type_id"][''] = 'Select';
        foreach ($result3 as $vvmtype) {
            $this->_list["vvm_type_id"][$vvmtype['pk_id']] = $vvmtype['vvm_type_name'];
        }
        /**
         * Generate Product Combo       
         */
        $item_pack_sizes = new Model_ItemPackSizes();
        $result1 = $item_pack_sizes->getAllManageItems();
        $this->_list["product_id"][''] = 'Select';
        if ($result1 && count($result1) > 0) {
            foreach ($result1 as $whs) {
                $this->_list["product_id"][$whs['pkId']] = $whs['itemName'];
            }
        }
        /**
         * Generate Text Fields     
         */
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "batch":
                case "production_date":
                case "expiry_date":
                case "unit_price":
                    parent::createText($col);                    
                    break;
                default:
                    break;
            }

            /**
             * Generate Select Boxes     
             */
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHidden("id");
    }
}