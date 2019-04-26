<?php

/**
 * Model_Item
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author    Hannan mehmood <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Item
 */

class Model_Item extends Model_Base {

    /**
     * $item_pack_size_id
     * @var type 
     */
    public $item_pack_size_id;

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Items');
    }

    /**
     * Get Product List
     * 
     * @return type
     */
    public function getProductList() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.pkId,  ips.itemName")
                ->from("ItemPackSizes", "ips")
                ->ORDERBY("ips.listRank");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Product By Category
     * 
     * @return type
     */
    public function getProductByCategory() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.pkId,  ips.itemName")
                ->from("ItemPackSizes", "ips")
                ->where("ips.itemCategory IN (1,2)");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Items
     * 
     * @return type
     */
    public function getAllItems() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("i.pkId, i.description")
                ->from("Items", "i");
        return $str_sql->getQuery()->getResult();
    }

}
