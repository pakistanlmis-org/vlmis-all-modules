<?php

/**
 * Zend_View_Helper_IsExpiryEdit
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */






/**
 *  Zend_View_Helper_IsExpiryEdit
 */
class Zend_View_Helper_IsExpiryEdit extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Is Expiry Edit
     * @param type $batch_id
     * @return boolean
     */
    public function isExpiryEdit($batch_id) {

        $auth = App_Auth::getInstance();
        $wh_id = $auth->getWarehouseId();
        $role_id = $auth->getRoleId();

        if ($role_id == 3) {

            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("sd")
                    ->from('StockDetail', 'sd')
                    ->join('sd.stockBatchWarehouse', 'sb')
                    ->join('sd.stockMaster', 'sm')
                    ->andWhere("sd.isReceived = 1")
                    ->andWhere("sm.fromWarehouse = $wh_id")
                    ->andWhere("sm.transactionType = 2")
                    ->andWhere("sb.pkId = $batch_id");

            $row = $str_sql->getQuery()->getResult();

            if (count($row) > 0) {
                return false;
            }
            return true;
            
        } else {
            return false;
        }
    }

}
