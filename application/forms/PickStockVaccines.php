<?php

/**
 * Form_PickStockVaccines
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Pick Stock Vaccines
 */
class Form_PickStockVaccines extends Form_Base {

    /**
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "stock_master_id" => "Issue No",
    );

    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'stock_master_id' => array(),
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        $identity = new App_Auth();
        $stock_master = new Model_StockMaster();
        $wh_id = $identity->getWarehouseId();
        $result1 = $stock_master->getUnpickedIssueNo($wh_id);
        $this->_list["stock_master_id"][''] = "Select Issue No";
        if ($result1) {
            foreach ($result1 as $row) {
                $this->_list["stock_master_id"][$row['stc_master_pkid']] = $row['transaction_number'];
            }
        }
        foreach ($this->_fields as $col => $name) {
            if ($col == "stock_master_id") {
                parent::createText($col);
            }


            if (in_array("stock_master_id", array_keys($this->_list))) {
                parent::createSelectWithValidator($col, $name, $this->_list["stock_master_id"]);
            }
        }
    }

}
