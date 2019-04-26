<?php

/**
 * Model_AdminTools
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Admin Tools
 */
class Model_AdminTools extends Model_Base {

    /**
     * Get Campaigns By District
     */
    public function getDifferentStockPlacement($wh_id, $item_id) {
        $str_qry = "SELECT
	A.pk_id AS BatchId,
	A.number AS BatchNumber,
	A.item_name AS ItemName,
	IFNULL(A.qty, 0) AS BatchQty,
	IFNULL(B.plcd_qty, 0) AS PlacedQty
FROM
	(
		SELECT
			stock_batch_warehouses.pk_id,
			stock_batch.number,
			item_pack_sizes.item_name,
			SUM(
				stock_batch_warehouses.quantity
			) qty
		FROM
			stock_batch_warehouses
		INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
		INNER JOIN pack_info ON pack_info.pk_id = stock_batch.pack_info_id
		INNER JOIN stakeholder_item_pack_sizes ON stakeholder_item_pack_sizes.pk_id = pack_info.stakeholder_item_pack_size_id
		INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stakeholder_item_pack_sizes.item_pack_size_id
		INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
		WHERE
			item_pack_sizes.pk_id = $item_id
		AND warehouses.pk_id = $wh_id
		GROUP BY
			stock_batch_warehouses.pk_id
	) A
LEFT JOIN (
	SELECT
		stock_batch_warehouses.pk_id,
		item_pack_sizes.item_name,
		SUM(placements.quantity) plcd_qty
	FROM
		placements
	INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
	INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
	INNER JOIN pack_info ON pack_info.pk_id = stock_batch.pack_info_id
	INNER JOIN stakeholder_item_pack_sizes ON stakeholder_item_pack_sizes.pk_id = pack_info.stakeholder_item_pack_size_id
	INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
	WHERE
		item_pack_sizes.pk_id = $item_id
	AND stock_batch_warehouses.warehouse_id = $wh_id
	GROUP BY
		stock_batch_warehouses.pk_id
) B ON A.pk_id = B.pk_id
HAVING
	BatchQty <> PlacedQty
ORDER BY
	A.item_name";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * 
     * Get Different Stock Placement By Product
     */
    public function getDifferentStockPlacementByProduct() {
        $str_qry = "SELECT
	A.item_name AS ItemName,
	A.qty AS BatchQty,
	B.plcd_qty AS PlacedQty,
	A.warehouse_name,
        A.warehouse_id,
        A.item_id
FROM
	(
		SELECT
			stock_batch_warehouses.warehouse_id,
			warehouses.warehouse_name,
			item_pack_sizes.item_name,
			SUM(
				stock_batch_warehouses.quantity
			) qty,
			item_pack_sizes.pk_id AS item_id
		FROM
			stock_batch_warehouses
		INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
		INNER JOIN pack_info ON pack_info.pk_id = stock_batch.pack_info_id
		INNER JOIN stakeholder_item_pack_sizes ON stakeholder_item_pack_sizes.pk_id = pack_info.stakeholder_item_pack_size_id
		INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = stakeholder_item_pack_sizes.item_pack_size_id
		INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
		WHERE
			warehouses.stakeholder_office_id < 6
		GROUP BY
			stock_batch_warehouses.warehouse_id,
			item_pack_sizes.pk_id
	) A
INNER JOIN (
	SELECT
		item_pack_sizes.item_name,
		Sum(placements.quantity) AS plcd_qty,
		warehouses.warehouse_name,
		warehouses.pk_id AS warehouse_id,
		item_pack_sizes.pk_id AS item_id
	FROM
		placements
	INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
	INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
	INNER JOIN pack_info ON pack_info.pk_id = stock_batch.pack_info_id
	INNER JOIN stakeholder_item_pack_sizes ON stakeholder_item_pack_sizes.pk_id = pack_info.stakeholder_item_pack_size_id
	INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
	INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
	GROUP BY
		warehouses.pk_id,
		item_pack_sizes.pk_id
) B ON A.item_id = B.item_id
AND A.warehouse_id = B.warehouse_id
HAVING
	A.qty <> B.plcd_qty
ORDER BY
	A.warehouse_name";
        

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }
/**
 * Get Batch Transactions
 */
    public function getBatchTransactions($batch, $item_cat) {

        if ($item_cat == 1) {
            $str_qry = "SELECT
	stock_master.transaction_date,
	stock_master.transaction_number,
	stock_batch.number,
	stock_batch_warehouses.quantity AS batch_qty,
	stock_detail.quantity AS trans_qty,
	stock_master.transaction_type_id,
	stock_detail.pk_id AS detail_id,
	stock_detail.vvm_stage,
	stock_batch_warehouses.pk_id AS batch_id,
	placements.quantity AS plc_qty,
	placements.placement_location_id,
cold_chain.asset_id,
	placements.pk_id AS plc_id
FROM
	stock_master
INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
RIGHT JOIN placements ON placements.stock_detail_id = stock_detail.pk_id
RIGHT JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
RIGHT JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
WHERE
	placements.stock_batch_warehouse_id IN  (" . implode(",", $batch) . ")
UNION
SELECT
	stock_master.transaction_date,
	stock_master.transaction_number,
	stock_batch.number,
	stock_batch_warehouses.quantity AS batch_qty,
	stock_detail.quantity AS trans_qty,
	stock_master.transaction_type_id,
	stock_detail.pk_id AS detail_id,
	stock_detail.vvm_stage,
	stock_batch_warehouses.pk_id AS batch_id,
	placements.quantity AS plc_qty,
	placements.placement_location_id,
cold_chain.asset_id,
	placements.pk_id AS plc_id
FROM
	stock_master
INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
LEFT JOIN placements ON placements.stock_detail_id = stock_detail.pk_id
LEFT JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
LEFT JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
WHERE
	stock_batch_warehouses.pk_id IN (" . implode(",", $batch) . ")";
        } else {
            $str_qry = "SELECT
	stock_master.transaction_date,
	stock_master.transaction_number,
	stock_batch.number,
	stock_batch_warehouses.quantity AS batch_qty,
	stock_detail.quantity AS trans_qty,
	stock_master.transaction_type_id,
	stock_detail.pk_id AS detail_id,
	stock_detail.vvm_stage,
	stock_batch_warehouses.pk_id AS batch_id,
	placements.quantity AS plc_qty,
	placements.placement_location_id,
	placements.pk_id AS plc_id,
        non_ccm_locations.location_name as asset_id
FROM
	stock_master
INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
RIGHT JOIN placements ON placements.stock_detail_id = stock_detail.pk_id
RIGHT JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
RIGHT JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
WHERE
	placements.stock_batch_warehouse_id IN  (" . implode(",", $batch) . ")
UNION
SELECT
	stock_master.transaction_date,
	stock_master.transaction_number,
	stock_batch.number,
	stock_batch_warehouses.quantity AS batch_qty,
	stock_detail.quantity AS trans_qty,
	stock_master.transaction_type_id,
	stock_detail.pk_id AS detail_id,
	stock_detail.vvm_stage,
	stock_batch_warehouses.pk_id AS batch_id,
	placements.quantity AS plc_qty,
	placements.placement_location_id,
	placements.pk_id AS plc_id,
        non_ccm_locations.location_name as asset_id
FROM
	stock_master
INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
LEFT JOIN placements ON placements.stock_detail_id = stock_detail.pk_id
LEFT JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
LEFT JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
WHERE
	stock_batch_warehouses.pk_id IN (" . implode(",", $batch) . ")";
        }

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

}
