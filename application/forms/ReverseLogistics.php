<?php

/**
 * Form_ReverseLogistics
 *
 */

/**
 *  Form for Reverse Logistics
 */
class Form_ReverseLogistics extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @healthfacilities: Health Facilities
     * @product: Product
     * @batch: Batch
     * @expiry_date: Expiry Date
     * @quantity: Quantity
     * 
     * @var type 
     */
    private $_fields = array(
        "healthfacilities" => "Health Facilities",
        "product" => "Product",
        "batch" => "Batch",
        "quantity" => "Quantity",
        "expiry_date" => "Expiry Date",
    );

    /**
     * $_list
     * 
     * List
     * @healthfacilities
     * @product
     * @batch
     * 
     * @var type 
     */
    private $_list = array(
        'healthfacilities' => array(),
        'product' => array(
            '' => 'Select',
            26 => 'tOPV'
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Purpose(activity_id) combo 
        $warehouses = new Model_Warehouses();
        $result1 = $warehouses->getAllHfsByTehsil();

        foreach ($result1 as $hfs) {
            $this->_list["healthfacilities"][''] = "Select";
            $this->_list["healthfacilities"][$hfs['pk_id']] = $hfs['warehouse_name'];
        }

        /* $item_pack_sizes = new Model_ItemPackSizes();
          $result1 = $item_pack_sizes->getAllManageItems();
          $this->_list["product"][''] = 'Select';
          if ($result1 && count($result1) > 0) {
          foreach ($result1 as $whs) {
          $this->_list["product"][$whs['pkId']] = $whs['itemName'];
          }
          } */

        foreach ($this->_fields as $col => $name) {

            switch ($col) {
                case "expiry_date":
                    parent::createReadOnlyText($col);
                    break;
                case "batch":
                case "quantity":
                    parent::createText($col);
                    break;
                default :
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
