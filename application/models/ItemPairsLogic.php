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
class Model_ItemPairsLogic extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('ItemPairsLogic');
    }

    /**
     * Get Product List
     * 
     * @return type
     */
    public function getProductPairByProduct() {
        $item_id = $this->form_values['item_id'];

        $str_sql = "SELECT
                        item_pair.pk_id AS item_pair_id,
                        item_pair.item_name AS item_pair_name,
                        item.pk_id AS item_id,
                        item.item_name AS item_name
                    FROM
                        item_pairs_logic
                        INNER JOIN item_pack_sizes AS item ON item_pairs_logic.item_id = item.pk_id
                        INNER JOIN item_pack_sizes AS item_pair ON item_pairs_logic.item_pair_id = item_pair.pk_id
                    WHERE
                        item_pairs_logic.item_id = $item_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

}
