<?php

/**
 * Zend_View_Helper_GetPreparedBy
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */





/**
 *  Zend View Helper Get Prepared By
 */
class Zend_View_Helper_GetPreparedBy extends Zend_View_Helper_Abstract {

    /**
     * Get Prepared By
     * @param type $stock_id
     * @return boolean
     */
    public function getPreparedBy($stock_id) {

        $em_read = Zend_Registry::get("doctrine_read");
        $str_sql = $em_read->createQueryBuilder()
                ->select('sm')
                ->from("StockMaster", "sm")
                ->where("sm.pkId = $stock_id");
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]->getCreatedBy()->getUsername();
        } else {
            return false;
        }
    }

}

?>