<?php

/**
 * Zend_View_Helper_GetDataEntryStatus
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Get Data Entry Status
 */
class Zend_View_Helper_GetDataEntryStatus extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Data Entry Status
     * @param type $warehouse_id
     * @param type $transaction_type
     * @return type
     */
    public function getDataEntryStatus($warehouse_id, $transaction_type) {
        // Check transaction type.
        if ($transaction_type == 1) {
            $join = "INNER JOIN warehouses ON  stock_master.from_warehouse_id = warehouses.pk_id";
        } else {
            $join = "INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id";
        }

        // Create query.
        $querypro = "SELECT
                        stock_master.pk_id,
                        stock_master.transaction_date AS transactionDate,
                        warehouses.warehouse_name,
                        stock_master.to_warehouse_id AS wh_id,
                        stock_master.from_warehouse_id AS wh_from_id,
                        stock_master.transaction_type_id
                FROM
                        stock_detail
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                $join
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id  
                WHERE
                        stock_detail.temporary = 0
                AND stock_batch_warehouses.warehouse_id = $warehouse_id
                AND warehouses.`status` = 1
                AND stock_master.transaction_type_id = $transaction_type
                ORDER BY
                        transactionDate DESC
                LIMIT 1";

        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($querypro);

        // Execute query and get result.
        $row->execute();
        return $row->fetchAll();
    }

}

?>