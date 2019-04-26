<?php

/**
 * Zend_View_Helper_WarehouseDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Warehouse Delete
 */
class Zend_View_Helper_GetSubResources extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Warehouse Delete
     * @param type $warehouse_id
     * @return type
     */
    public function getSubResources($resource_id) {
        $qry = $this->_em_read->createQueryBuilder()
                ->select("r")
                ->from("Resources", "r")
                ->where("r.parentId = $resource_id");

        $qry->orderBy("r.resourceName", "ASC");
        $qry->addOrderBy("r.description", "ASC");

       // echo $qry->getQuery()->getSql();
        return $qry->getQuery()->getResult();
    }

}

?>