<?php

/**
 * Form_BatchTracking
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Batch Tracking
 */
class Form_BatchTracking extends Form_Base {

    /**
     * 
     * $_fields
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_fields = array(
        "product" => "Product",
        "from_wh" => "Comments",
        "to_wh" => "Adjustment type"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_list = array(
        'from_wh' => array(),
        'to_wh' => array(),
        'product' => array()
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

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
