<?php

/**
 * Zend_View_Helper_GetPhysicalBatchQuantity
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Get Physical Batch Quantity
 */

class Zend_View_Helper_GetPhysicalBatchQuantity extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Physical Batch Quantity
     * @param type $batch_id
     * @return type
     */
    public function getPhysicalBatchQuantity($batch_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('SUM(pst.quantity) as qty')
                ->from("PhysicalStockTakingDetail", "pst")
                ->where("pst.stockBatch = " . $batch_id)
                ->andWhere("pst.physicalStockTaking = " . Model_PhysicalStockTakingDetail::STOCKID )
                ->groupBy("pst.batchNumber");

        $row = $str_sql->getQuery()->getResult();
        return $row[0]['qty'];
    }
}

