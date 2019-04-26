<?php

/**
 * Form_TransferStock
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Transfer Stock
*/

class Form_TransferStock extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @item_pack_size_id: Product
     * @stock_batch_id: Batch No
     * @totqty: Available Quantity
     * @non_ccm_location_id: Location
     * @quantity: Quantity
     * 
     * @var type 
     */
    private $_fields = array(
        "item_pack_size_id" => "Product",
        "stock_batch_id" => "Batch No",
        "totqty" => "Available Quantity",
        "non_ccm_location_id" => "Location",
        "quantity" => "Quantity"
    );
    
    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'non_ccm_location_id' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $non_ccm_loc = new Model_NonCcmLocations();
        $result3 = $non_ccm_loc->getLocationsName();

        $this->_list["non_ccm_location_id"][''] = "Select Location";
        if ($result3) {
            foreach ($result3 as $row3) {
                $this->_list["non_ccm_location_id"][$row3['pkId']] = $row3['locationName'];
            }
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "item_pack_size_id":
                case "stock_batch_id":
                case "totqty";
                case "quantity":
                    parent::createText($col);
                    break;
                default:
                    break;
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
        $this->getElement('totqty')->setAttrib("readonly", "true");
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
