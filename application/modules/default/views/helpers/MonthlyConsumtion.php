<?php

/**
 * Zend_View_Helper_MonthlyConsumtion
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */


/**
 *  Zend View Helper Monthly Consumtion
 */

class Zend_View_Helper_MonthlyConsumtion extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Monthly Consumtion
     * Used to get monthly consumption data
     * with respect to warehouse/store id, previous month date
     * and id.
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @return type
     */
    public function monthlyConsumtion($wh_id, $prev_month_date, $pk_id) {

        $rows = $this->_em->getRepository('HfDataMasterDraft')->findBy(array('warehouse' => $wh_id, 'reportingStartDate' => $prev_month_date));
        // Check result set.
        if (count($rows) > 0) {
            $querypro = " SELECT w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.nearest_expiry AS nearestExpiry
                       FROM
                               hf_data_master_draft w0_
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND 
           w0_.reporting_start_date = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        } else {
            $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.opening_balance AS openingBalance,
                        w0_.received_balance AS receivedBalance,
                        w0_.issue_balance AS issueBalance,
                        w0_.closing_balance AS closingBalance,
                        w0_.wastages AS wastages,
                        w0_.vials_used AS vialsUsed,
                        w0_.adjustments AS adjustments,
                        w0_.reporting_start_date AS reportingStartDate,
                        w0_.nearest_expiry AS nearestExpiry,
                        w0_.created_date AS createdDate
                       FROM
                               hf_data_master w0_
                       WHERE
                               w0_.warehouse_id = $wh_id
           AND 
           w0_.reporting_start_date = '$prev_month_date'
           AND w0_.item_pack_size_id = $pk_id";
        }

        // Prepare query and get result.
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $result = $row->fetchAll();

        // Return result.
        return $result[0];
    }
}
?>