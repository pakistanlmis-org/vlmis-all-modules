<?php

/**
 * Zend_View_Helper_ReceivedQuantity
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */





/**
 *  Zend View Helper Received Quantity
 */
class Zend_View_Helper_ReceivedQuantity extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Received Quantity
     * @param type $detail_id
     * @return type
     */
    public function receivedQuantity($detail_id) {

        $data = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("IFNULL(SUM(srfs.quantity),0) as total, srfs.vvmStage")
                ->from('StockReceiveFromScanner', 'srfs')
                ->where("srfs.stockDetail = $detail_id")
                ->groupBy("srfs.vvmStage");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            $total = 0;
            foreach ($row as $rec) {
                $total = $total + $rec['total'];
                $data[$rec['vvmStage']] = $rec['total'];
            }
            return array('total' => $total, 'vvmstage' => $data);
        } else {
            return array('total' => 0, 'vvmstage' => $data);
        }
    }

}
