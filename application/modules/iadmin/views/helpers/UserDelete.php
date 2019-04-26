<?php

/**
 * Zend_View_Helper_UserDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */






/**
 *  Zend View Helper User Delete
 */
class Zend_View_Helper_UserDelete extends Zend_View_Helper_Abstract {
    
    protected $_em_read;
    
    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * User Delete
     * @param type $warehouse_id
     * @return type
     */
    public function userDelete($warehouse_id) {
        
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("wu.pkId")
                ->from('WarehouseUsers', 'wu')
                ->join('wu.warehouse', 'w')
                ->where("w.pkId =" . $warehouse_id);

        return $str_sql->getQuery()->getResult();
    }

}

?>