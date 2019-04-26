<?php

/**
 * Form_TransferStockVaccines
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Transfer Stock Vaccines
*/

class Form_TransferStockVaccines extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @item_pack_size_id: Product
     * @stock_batch_id: Batch No
     * @asset_id: Location
     * @quantity: Quantity
     * 
     * @var type 
     */
    private $_fields = array(
        "item_pack_size_id" => "Product",
        "stock_batch_id" => "Batch No",
        "asset_id" => "Location",
        "quantity" => "Quantity"
    );
    
    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'asset_id' => array()
    );
    
    /**
     * Initializes Form Fields
     */
    public function init() {

        $cold_chain = new Model_ColdChain();
        $result3 = $cold_chain->getLocationsName();

        $this->_list["asset_id"][''] = "Select Location";
        if ($result3) {
            foreach ($result3 as $row3) {
                $this->_list["asset_id"][$row3['pkId']] = $row3['assetId'];
            }
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "item_pack_size_id":
                case "stock_batch_id":
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
    }

    /**
     * Add Hidden Fields
     */
    public function addHidden() {
       parent::createHiddenWithValidator("id");
    }

}
