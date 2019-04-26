<?php

/**
 * Form_PurposeTransfer
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Purpose Transfer
 */
class Form_PurposeTransfer extends Form_Base {

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
        "batch" => "Batch",
        "transfer_date" => "Transfer Date"
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
        $result2 = $item_pack_sizes->getAllPurposeItems();
        $this->_list["product"][''] = "Select";
        foreach ($result2 as $item) {
            $this->_list["product"][$item['pk_id']] = $item['item_name'];
        }
        $this->_list["batch"][''] = "Select";
        $date_to = date('d/m/Y');
        foreach ($this->_fields as $col => $name) {

            switch ($col) {

                case "transfer_date":
                    parent::createReadOnlyTextWithValue($col, $date_to);
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
