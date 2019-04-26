<?php

/**
 * Model_PlacementQuantity
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Placement Quantity
 */

class Model_PlacementQuantity extends Model_Base {

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
        $this->_table = $this->_em->getRepository('Placements');
    }

    /**
     * Find by asset batch
     * 
     * @param type $cc_id
     * @param type $batch_id
     * @return type
     */
    public function find_by_asset_batch($cc_id = 0, $batch_id = 0) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("pq.*")
                ->from("Model_PlacementQuantity pq")
                ->where("pq.ccm_id =2")
                ->andWhere("pq.stock_batch_warehouse_id=92");

        return $str_sql->fetchArray();
    }

    /**
     * Add
     * 
     * @param type $data
     * @return type
     */
    public function add($data) {
        $placement_qty = new Model_PlacementQuantity();
        $placement_qty->ccm_id = $data['coldchain'];
        $placement_qty->quantity = $data['quantity'];
        $placement_qty->stock_batch_warehouse_id = $data['batchID'];
        $placement_qty->save();
        return $placement_qty->getLast();
    }

    /**
     * Update
     * 
     * @param type $data
     * @return type
     */
    public function update($data) {
        $quantity = $data['quantity'];
        $pk_id = $data['pk_id'];

        $str_sql = $this->_em->createQueryBuilder()
                ->update('Model_PlacementQuantity')
                ->set('quantity', '?', $quantity)
                ->where('pk_id = ?', $pk_id);

        $str_sql->execute();
        return ( $str_sql->getCountSqlQuery() == 1);
    }

}
