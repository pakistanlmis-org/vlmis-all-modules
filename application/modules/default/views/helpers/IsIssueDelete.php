<?php

/**
 * Zend_View_Helper_IsIssueDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Is Issue Delete
 */

class Zend_View_Helper_IsIssueDelete extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Is Issue Delete
     * @param type $detail_id
     * @return string
     */
    public function isIssueDelete($detail_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.quantity")
                ->from('StockDetail', 'sd')
                ->where("sd.isReceived = 0")
                ->andWhere("sd.pkId = $detail_id");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            $sql_plc = $this->_em_read->createQueryBuilder()
                    ->select("SUM(p.quantity) qty")
                    ->from('Placements', 'p')
                    ->where("p.stockDetail = $detail_id")
                    ->groupBy("p.stockDetail")
                    ->having("qty = '" . $row[0]['quantity'] . "'");

            $row_plc = $sql_plc->getQuery()->getResult();

            if (count($row_plc) > 0) {
                return 'DIRECT_DELETE';
            } else {
                return 'CUSTOM_DELETE';
            }
        } else {
            return 'VOUCHER_RECEIVED';
        }
    }

}
