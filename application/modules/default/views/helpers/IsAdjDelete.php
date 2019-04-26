<?php

/**
 * Zend_View_Helper_IsAdjDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */


/**
 *  Zend View Helper Is Adj Delete
 */

class Zend_View_Helper_IsAdjDelete extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Is Adj Delete
     * @param type $master_id
     * @return boolean
     */
    public function isAdjDelete($master_id) {

        $identity = App_Auth::getInstance();
        // Get warehouse id.
        $store_wh_id = $identity->getWarehouseId();

        // Create query.
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.pkId as stock_batch_id,DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') transactionDate")
                ->from('StockDetail', 'sd')
                ->join('sd.stockBatchWarehouse', 'sb')
                ->join('sd.stockMaster', 'sm')
                ->andWhere("sm.pkId = $master_id");
        $row = $str_sql->getQuery()->getResult();

        // compose query.
        if (!empty($row) && count($row) > 0) {
            $str_sql2 = $this->_em_read->createQueryBuilder()
                    ->select("sd.pkId")
                    ->from('StockDetail', 'sd')
                    ->join('sd.stockBatchWarehouse', 'sb')
                    ->join('sd.stockMaster', 'sm')
                    ->join('sm.transactionType', 'tt')
                    ->where("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') >= '" . $row[0]['transactionDate'] . "' ")
                    ->andWhere("sb.pkId= '" . $row[0]['stock_batch_id'] . "' ")
                    ->andWhere("sm.fromWarehouse = '" . $store_wh_id . "'  ")
                    ->andWhere("tt.nature = '-'");
            
            // get result.
            $row2 = $str_sql2->getQuery()->getResult();
            if (!empty($row2) && count($row2) > 0) {
                return false;
            }
            return true;
            
        } else {
            return false;
        }
    }

}
