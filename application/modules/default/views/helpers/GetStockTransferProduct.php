<?php

/**
 * Zend_View_Helper_GetStockTransferProduct
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
class Zend_View_Helper_GetStockTransferProduct extends Zend_View_Helper_Abstract {

    /**
     * Get Prepared By
     * @param type $stock_id
     * @return boolean
     */
    public function getStockTransferProduct($tr_id, $type) {

        $em_read = Zend_Registry::get("doctrine_read");
        $str_sql = $em_read->createQueryBuilder()
                ->select('sd')
                ->from("StockDetail", "sd")
                ->where("sd.isReceived = $tr_id")
                ->andWhere("sd.adjustmentType = $type");
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemName();
        } else {
            return false;
        }
    }

}

?>