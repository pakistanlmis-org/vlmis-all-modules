<?php

/**
 * Model_CcmAssetTypes
 *
 * 
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Placement Locations
 */
class Model_PlacementLocations extends Model_Base {

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
        $this->_table = $this->_em->getRepository('PlacementLocations');
    }

    /**
     * Get Placement Locations
     * 
     * @return boolean
     */
    public function getPlacementLocations() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("pl.pkId,pl.locationType")
                ->from('PlacementLocations', 'pl');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Placement Location Id
     * 
     * @param type $nonccm
     * @return boolean
     */
    public function getPlacementLocationId($nonccm) {


        $str_sql = "SELECT
            non_ccm_locations.location_name,
            non_ccm_locations.pk_id,
            placement_locations.pk_id as plcid
            FROM
            non_ccm_locations
            INNER JOIN placement_locations ON non_ccm_locations.pk_id = placement_locations.location_id
            WHERE
            placement_locations.location_id = $nonccm";
        $res = $this->_em_read->getConnection()->prepare($str_sql);
        $res->execute();
        $result = $res->fetchAll();
        if (count($result) > 0) {
            return $result[0]['plcid'];
        } else {
            return false;
        }
    }

    /**
     * Get Placements Summary
     * 
     * @param type $wh_id
     * @param type $type
     * @return type
     */
    public function getPlacementsSummary($wh_id, $type) {


        if ($type == Model_PlacementLocations::LOCATIONTYPE_CCM) {
            $str_sql = "SELECT
                    Sum(placements.quantity) AS qty,
                    placements.placement_location_id,
                    placements.stock_batch_warehouse_id AS batch_id,
                    stock_batch.number AS batch_no,
                    item_pack_sizes.item_name,
                    item_pack_sizes.pk_id AS item_id,
                    cold_chain.asset_id AS location_name,
                    ROUND(
                                    Sum(placements.quantity) / pack_info.quantity_per_pack
                            ) AS cartons,
                    placements.vvm_stage
                    FROM
                            placements
                    INNER JOIN placement_locations ON placement_locations.pk_id = placements.placement_location_id
                    INNER JOIN cold_chain ON cold_chain.pk_id = placement_locations.location_id
                    INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id 
                    WHERE
                            cold_chain.warehouse_id = $wh_id
                    AND placement_locations.location_type = $type
                    GROUP BY
                    placements.placement_location_id,
                    placements.stock_batch_warehouse_id,
                    stock_batch.number,
                    item_pack_sizes.item_name,
                    item_pack_sizes.pk_id,
                    placements.vvm_stage
                    HAVING
                            qty > 0";
        }

        if ($type == Model_PlacementLocations::LOCATIONTYPE_NONCCM) {
            $str_sql = "SELECT
                                Sum(placements.quantity) AS qty,
                                placements.placement_location_id,
                                placements.stock_batch_warehouse_id AS batch_id,
                                stock_batch.number AS batch_no,
                                item_pack_sizes.item_name,
                                item_pack_sizes.pk_id AS item_id,
                                placement_locations.location_barcode AS location_name,
                                ROUND(Sum(placements.quantity) / pack_info.quantity_per_pack) as cartons
                        FROM
                                placements
                        INNER JOIN placement_locations ON placement_locations.pk_id = placements.placement_location_id
                        INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id 
                        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                      
                        WHERE
                                placement_locations.location_type = $type
                        AND non_ccm_locations.warehouse_id = $wh_id
                        GROUP BY
                                placements.placement_location_id,
                                placements.stock_batch_warehouse_id,
                                stock_batch.number,
                                item_pack_sizes.item_name,
                                item_pack_sizes.pk_id
                        HAVING
                                qty > 0";
        }

        $res = $this->_em_read->getConnection()->prepare($str_sql);
        $res->execute();
        $result = $res->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return array("message" => "No record found");
        }
    }

    /**
     * Get Placement Loc Pk Id
     * 
     * @param type $id
     * @return type
     */
    public function getPlacementLocPkId($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("pl.pkId")
                ->from('PlacementLocations', 'pl')
                ->where("pl.locationId =" . $id);

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Location Status Vaccines
     * 
     * @return boolean
     */
    public function locationStatusVaccines() {

        $warehouse_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT DISTINCT
                cold_chain.pk_id,
                cold_chain.asset_id,
                cold_chain.auto_asset_id,
                AssetSubtype.asset_type_name,
                placement_locations.pk_id AS plc_pk_id,
                ccm_models.ccm_model_name,
                ccm_status_history.ccm_status_list_id,
                round(
                 (
                        SUM(
                                (
                                        placements.quantity * pack_info.volum_per_vial
                                ) / 1000
                        )
                ) / (
                                                ccm_models.net_capacity_20 + ccm_models.net_capacity_4
                ) * 100
                ) AS used_percentage,
                ccm_status_history.ccm_status_list_id
                FROM
                cold_chain
                INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
                INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
                LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id                
                LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id=stock_batch.pk_id
                LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
                LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
                WHERE
                 cold_chain.warehouse_id = $warehouse_id
                AND (
                        (
                                cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::COLDROOM . "
                                OR AssetMainType.pk_id = " . Model_CcmAssetTypes::COLDROOM . "
                        )
                        OR (
                                cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                                OR AssetMainType.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                        )
                )
                AND placement_locations.location_type = " . parent::LOCATIONTYPE_CCM . "
                GROUP BY
                cold_chain.auto_asset_id ORDER BY ccm_status_history.ccm_status_list_id,cold_chain.asset_id,cold_chain.ccm_asset_type_id DESC";
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
