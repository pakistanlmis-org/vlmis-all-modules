<?php

/**
 * Form_VvmManagement
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for VVM Management
*/

class Form_VvmManagement extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @product: Product
     * @batch: Batch
     * 
     * @var type 
     */
    private $_fields = array(
        "product" => "Product",
        "batch" => "Batch"
    );
    
    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'product' => array(),
        'batch' => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        //Generate Products(items) Combo
        $item_pack_sizes = new Model_ItemPackSizes();
        $result2 = $item_pack_sizes->getAllVaccines();
        $this->_list["product"][''] = "Select";
        foreach ($result2 as $item) {
            $this->_list["product"][$item['pkId']] = $item['itemName'];
        }
        $this->_list["batch"][''] = "Select";

        foreach ($this->_fields as $col => $name) {
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
