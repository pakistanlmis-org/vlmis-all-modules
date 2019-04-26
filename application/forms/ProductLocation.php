<?php

/**
 * Form_ProductLocation
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Product Location
 */
class Form_ProductLocation extends Form_Base {

    /**
     * Fields
     * for Form_ProductLocation
     * 
     * 
     * item_pack_size_id
     * stock_batch_id
     * unallocated_quantity
     * total_quantity
     * quantity
     * estimated_pallet_filled
     * quantity1
     * location
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "item_pack_size_id" => "Product",
        "stock_batch_id" => "Batch No",
        "unallocated_quantity" => "Unallocated Quantity",
        "total_quantity" => "Product Quantity",
        "quantity" => "Carton Quantity",
        "estimated_pallet_filled" => "Estimated Pallet Filled",
        "quantity1" => "Quantity",
        "location" => "Location",
    );

    /**
     * Combo boxes
     * for Form_ProductLocation
     * 
     * 
     * location
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'location' => array()
    );

    /**
     * Hidden fields
     * for Form_ProductLocation
     * 
     * 
     * placement_location_id
     * itemId
     * batchId
     * 
     * 
     * $_hidden
     * @var type 
     */
    private $_hidden = array(
        "placement_location_id" => "",
        "itemId" => "",
        "batchId" => "",
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Item Combo
        $item_pack_size = new Model_ItemPackSizes();
        $result = $item_pack_size->getItemsByCategory();
        $this->_list["item_pack_size_id"][''] = "Select Product";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["item_pack_size_id"][$row->getPkId()] = $row->getItemName();
            }
        }

        //Generate Batch Number Combo  
        $stock_batch = new Model_StockBatch();
        $result = $stock_batch->getBatches();
        $this->_list["stock_batch_id"][''] = "Select Stock Batch";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["stock_batch_id"][$row['pkId']] = $row['number'];
            }
        }

        //Generate Location Combo 
        $non_ccm_loc = new Model_NonCcmLocations();
        $result = $non_ccm_loc->getLocationsName();
        $this->_list["location"][''] = "Select Location";
        if ($result) {
            foreach ($result as $row) {
                $this->_list["location"][$row['pkId']] = $row['locationName'];
            }
        }

        //Generating fields
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "item_pack_size_id":
                case "stock_batch_id":
                case "number":
                case "unallocated_quantity":
                case "total_quantity":
                case "quantity":
                case "estimated_pallet_filled":
                case "location":
                case "quantity1":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
            //Generating Hidden fields
            foreach ($this->_hidden as $col => $name) {
                switch ($col) {

                    case "placement_location_id":
                    case "itemId":
                    case "batchId" :
                        parent::createHidden($col);
                        break;
                    default:
                        break;
                }
            }
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list[$col]);
            }
        }
    }

    /**
     * Read Fields
     */
    public function readFields() {
        $this->getElement('item_pack_size_id')->setAttrib("readonly", "true");
        $this->getElement('stock_batch_id')->setAttrib("readonly", "true");
        $this->getElement('unallocated_quantity')->setAttrib("disabled", "true");
        $this->getElement('total_quantity')->setAttrib("disabled", "true");
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHidden($col);
    }

}
