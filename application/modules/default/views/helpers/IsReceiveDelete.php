<?php

/**
 * Zend_View_Helper_IsReceiveDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Is Receive Delete
 * 
 */
class Zend_View_Helper_IsReceiveDelete extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Is Receive Delete
     * This determines whether the receive voucher
     * is deleteable or not because there are
     * many use case in which receive voucher can not be deleted
     * and in some use cases receive voucher can be deleted.
     * @param type $detail_id
     * @return boolean|string
     */
    public function isReceiveDelete($detail_id) {
        /**
         * Get batch_id and transaction_date by detail_id
         */
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.pkId as stock_batch_id,sm.transactionDate, sd.quantity, sd.createdDate")
                ->from('StockDetail', 'sd')
                ->join('sd.stockBatchWarehouse', 'sb')
                ->join('sd.stockMaster', 'sm')
                ->andWhere("sd.pkId = $detail_id");

        // Execute query and get result.
        $row = $str_sql->getQuery()->getResult();

        /**
         * Check if detail_id is available in placements table
         */
        $sql_plc = $this->_em_read->createQueryBuilder()
                ->select("SUM(p.quantity) as qty")
                ->from('Placements', 'p')
                ->where("p.stockDetail = $detail_id")
                ->having("qty = '" . $row[0]['quantity'] . "'");

        // Execute query and get result.
        $sql_plc->getQuery()->getResult();

        /**
         * Check if detail_id is available in placements table
         */
        $sql_plc2 = $this->_em_read->createQueryBuilder()
                ->select("p")
                ->from('Placements', 'p')
                ->where("p.stockBatchWarehouse = '" . $row[0]['stock_batch_id'] . "'")
                ->andWhere("p.createdDate > '" . $row[0]['createdDate'] . "'");

        // Execute query and get result.
        $row_plc2 = $sql_plc2->getQuery()->getResult();

        // Check the result set.
        if (count($row_plc2) == 0) {
            if ($this->checkCurrentQty($row[0]['stock_batch_id'], $row[0]['quantity']) && ($this->checkDelDateQty($row[0]['transactionDate'], $row[0]['stock_batch_id'], $row[0]['quantity']))) {
                return 'DIRECT_DELETE';
            }
        } else {
            if ($this->checkCurrentQty($row[0]['stock_batch_id'], $row[0]['quantity']) && ($this->checkDelDateQty($row[0]['transactionDate'], $row[0]['stock_batch_id'], $row[0]['quantity']))) {
                return 'CUSTOME_DELETE';
            }
        }

        return false;
    }

    /**
     * Check if current available batch quantity is equal or greater than delete quantity
     */
    public function checkCurrentQty($batch, $quantity) {

        $sql_current = $this->_em_read->createQueryBuilder()
                ->select("SUM(sd.quantity) as qty")
                ->from('StockDetail', 'sd')
                ->where("sd.stockBatchWarehouse = '" . $batch . "'")
                ->having("qty >= '" . $quantity . "'");

        // Execute query and get result.
        $row_current = $sql_current->getQuery()->getResult();
        return count($row_current) > 0;
    }

    /**
     * Check if delete date available batch quantity is equal or greater than delete quantity
     */
    public function checkDelDateQty($date, $batch, $quantity) {

        $sql_del_qty = $this->_em_read->createQueryBuilder()
                ->select("SUM(sd.quantity) as qty")
                ->from('StockDetail', 'sd')
                ->join('sd.stockMaster', 'sm')
                ->where("sm.transactionDate <= '" . $date . "'")
                ->andWhere("sd.stockBatchWarehouse = '" . $batch . "'")
                ->having("qty >= '" . $quantity . "'");

        // Execute query and get result.
        $row_del_qty = $sql_del_qty->getQuery()->getResult();

        return count($row_del_qty) > 0;
    }

}
