<?php

/**
 * Zend_View_Helper_PriorityVaccinesDistribution
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Priority Vaccines Distribution
 */
class Zend_View_Helper_PriorityVaccinesDistributionrep extends Zend_View_Helper_Abstract {

    /**
     * Priority Vaccines Distribution
     * @param type $product_id
     * @param type $case
     * @return boolean
     */
    public function priorityVaccinesDistributionRep($wh_type, $warehouse_id,$item_id,$purpose,$status, $case) {

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = array(
            
            'case' => $case,
            'warehouse_id' => $warehouse_id,
            'wh_type' => $wh_type,
            'item_id' => $item_id,
            'purpose' => $purpose,
            'status' => $status
        );
        $result = $stock_master->priorityVaccinesDistributionRep();

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

}
