<?php

/**
 * Zend_View_Helper_GetBatchMasterId
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Get Batch Master Id
 */

class Zend_View_Helper_GetBatchMasterId extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Batch Master Id
     * @param type $number
     * @param type $item_id
     * @param type $current_level
     * @return boolean
     */
    public function getBatchMasterId($number, $item_id, $current_level) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sbw')
                ->from("StockBatchWarehouses","sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo","pi")
                ->join("pi.stakeholderItemPackSize","sips")
                ->join("sbw.warehouse", "w")
                ->join("w.stakeholderOffice", "so")
                ->join("so.geoLevel", "gl")
                ->where("sb.number = '$number'")
                ->andWhere("sips.itemPackSize = $item_id")
                ->andWhere("so.geoLevel <= $current_level")
                ->orderBy("gl.pkId", "ASC");
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0];
        } else {
            return false;
        }
    }

}

?>