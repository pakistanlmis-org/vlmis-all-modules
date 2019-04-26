<?php

/**
 * Zend_View_Helper_GetLocation
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helpe Get Location
 */
class Zend_View_Helper_GetLocationByBatch extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Location
     * @param type $detail_id
     * @param type $cat_id
     * @return string
     */
    public function getLocationByBatch($batch_id, $detail_id) {


        // Check category.
        $batch = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);

        if ($batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId() == Model_Base::VACCINECATEGORY || $batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId() == Model_Base::INACTIVEVACCINE) {
            $qry = "SELECT
            cold_chain.asset_id,
            placements.quantity,

            IF (
                    item_pack_sizes.vvm_group_id = 1,
                    vvm_stages.pk_id,
                    vvm_stages.vvm_stage_value
            ) AS vvm_stage,
            placement_locations.pk_id AS place_loc_id,
            vvm_stages.pk_id AS vvm_stage_id,
            stock_batch_warehouses.pk_id AS batch_id,
            stock_batch.number
            FROM
                    placements
            INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
            INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
            INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
            INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            where placements.stock_detail_id = " . $detail_id;
        } else {
            $qry = "SELECT
                placements.quantity,
                non_ccm_locations.location_name AS asset_id,
                'NA' AS vvm_stage,
                placement_locations.pk_id AS place_loc_id,
                placements.stock_batch_warehouse_id AS batch_id,
                stock_batch.number
                FROM
                placements
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
                WHERE
                        placements.stock_detail_id = " . $detail_id;
        }


        $row = $this->_em_read->getConnection()->prepare($qry);

        // Get result.
        $row->execute();
        $data = $row->fetchAll();

        if (count($data) > 0) {
            return $data[0]['asset_id'];
        } else {
            return '';
        }
    }

}

?>