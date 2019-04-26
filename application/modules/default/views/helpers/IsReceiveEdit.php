<?php

/**
 * Zend_View_Helper_IsReceiveEdit
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Is Receive Edit
 */
class Zend_View_Helper_IsReceiveEdit extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Is Receive Edit
     * Determines whether receive voucher is editable or not
     * becuase there are many use cases in which receive voucher
     * can not be edit,e.g. if user has issued some of batches
     * from received voucher,while in some cases receive voucher 
     * is editable.
     * @param type $detail_id
     * @param type $wh_id
     * @return boolean
     */
    public function isReceiveEdit($detail_id, $wh_id) {

        $result = 0;


        // Prepare/Create query
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.pkId as stock_batch_id,sm.transactionDate")
                ->from('StockDetail', 'sd')
                ->join('sd.stockBatchWarehouse', 'sb')
                ->join('sd.stockMaster', 'sm')
                ->andWhere("sd.pkId = $detail_id");

        // Execute query and get result.
        $row = $str_sql->getQuery()->getResult();

        // 
        $str_sql2 = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId")
                ->from('StockDetail', 'sd')
                ->join('sd.stockBatchWarehouse', 'sb')
                ->join('sd.stockMaster', 'sm')
                ->where("sm.transactionDate >= '" . $row[0]['transactionDate'] . "' ")
                ->andWhere("sb.pkId= '" . $row[0]['stock_batch_id'] . "' ")
                ->andWhere("sm.fromWarehouse = '" . $wh_id . "'  ")
                ->andWhere("sm.transactionType=2");

        // Execute query and get result.
        $row2 = $str_sql2->getQuery()->getResult();
        if (!empty($row2) && count($row2) > 0) {
            $result = 0;
        } else {
            $result = 1;
        }

        $str_sql = "SELECT DISTINCT
            stock_detail.pk_id
            FROM
            stock_detail
            INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
            WHERE
            stakeholders.stakeholder_type_id = 1 AND
            stock_detail.pk_id = $detail_id";
        // Execute query and get result.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $is_supplier = $row->fetchAll();

        // Check result set.
        if (count($is_supplier) > 1 && $result == 1) {
            $result = 1;
        } else {
            $result = 0;
        }
        return $result == 1;
    }

}
