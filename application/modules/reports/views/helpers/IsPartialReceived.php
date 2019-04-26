<?php

/**
 * Zend_View_Helper_IsPartialReceived
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Is Partial Received
 */
class Zend_View_Helper_IsPartialReceived extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Is Partial Received
     * @param type $stock_id
     * @return string
     */
    public function isPartialReceived($stock_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd")
                ->from('StockDetail', 'sd')
                ->where("sd.stockMaster = " . $stock_id)
                ->andWhere("sd.isReceived != 0");
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            return "Partial Received";
        } else {
            return "Pending";
        }
    }

}

?>