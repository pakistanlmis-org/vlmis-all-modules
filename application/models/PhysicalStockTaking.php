<?php

/**
 * Model_ItemPackSizes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Physical Stock Taking
 */

class Model_PhysicalStockTaking extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('PhysicalStockTaking');
    }

    /**
     * Get All Descripiton
     * 
     * @return boolean
     */
    public function getAllDescripiton() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips")
                ->from("PhysicalStockTaking", "ips")
                ->orderBy("ips.pkId", "ASC");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
