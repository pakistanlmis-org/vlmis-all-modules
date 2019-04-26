<?php

/**
 * Zend_View_Helper_GetManufacturer
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */





/**
 *  Zend View Helpe Get Manufacturer
 */

class Zend_View_Helper_GetManufacturer extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Manufacturer
     * @param type $batch_id
     */
    public function getManufacturer($batch_id) {


        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb")
                ->from('StockBatchWarehouses', 'sb')
                ->where("sb.pkId = $batch_id");

        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            echo $row[0]->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getStakeholder()->getStakeholderName();
        } else {
            echo '';
        }
    }

}
