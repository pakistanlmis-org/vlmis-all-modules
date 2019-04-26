<?php

/**
 * Form_StockBatch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Stock Batch
 */
class Form_StockBatch extends Form_Base {

    /**
     * $_fields
     * 
     * Form Fields
     * @item_pack_size_id: Vaccine
     * @status: Status
     * @searchby: Search By
     * @searchinput: Search Input
     * 
     * @var type 
     */
    private $_fields = array(
        "item_pack_size_id" => "Vaccine",
        "status" => "Status",
        "searchby" => "Search By",
        "searchinput" => "Search Input"
    );

    /**
     * $_list
     * 
     * List
     * @item_pack_size_id
     * @searchby
     * 
     * @var type 
     */
    private $_list = array(
        'item_pack_size_id' => array(),
        'searchby' => array(
            '' => 'Select',
            'number' => 'Batch Number',
            'expired_before' => 'Expired on or before',
            'expired_after' => 'Expired on or after'
        )
    );
    
    private $_radio = array(
        'status' => array(
            "6" => "All Priorities",
            "1" => "Priority 1",
            "2" => "Priority 2",
            "3" => "Priority 3",
            "4" => "Finished",
            "5" => "Expired stock (Date)",
            "7" => "Expired stock (VVM)"
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        //Generate Products(items) Combo
        $items = new Model_ItemPackSizes();
        $result2 = $items->getAllItems();
        foreach ($result2 as $item) {
            $this->_list["item_pack_size_id"][$item['pkId']] = $item['itemName'];
        }

        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "searchinput":
                    parent::createText($col);
                    break;
                case "status":
                    $options = array(
                        'label' => 'Title',
                        'labelAttributes' => array(
                            'class' => 'radio-inline',
                        ),
                    );
                    parent::createRadioWithOptions($col, $this->_radio[$col], $options);
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
