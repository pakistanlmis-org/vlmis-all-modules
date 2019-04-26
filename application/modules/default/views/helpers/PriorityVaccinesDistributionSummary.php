<?php

/**
 * Zend_View_Helper_PriorityVaccinesDistributionSummary
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Priority Vaccines Distribution Summary
 */

class Zend_View_Helper_PriorityVaccinesDistributionSummary extends Zend_View_Helper_Abstract {

    /**
     * Priority Vaccines Distribution Summary
     * @param type $product_id
     * @param type $case
     * @return boolean
     */
    public function priorityVaccinesDistributionSummary($product_id, $case) {

        $stock_master = new Model_StockMaster();
        $stock_master->form_values = array(
            'product_id' => $product_id,
            'case' => $case
        );
        $result = $stock_master->priorityVaccinesDistributionSummary();

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

}
