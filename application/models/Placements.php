<?php

/**
 * Model_Placements
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Placements
 */
class Model_Placements extends Model_Base {

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
     * Get Listing
     * @return boolean
     */
    public function getListing() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("p.quantity,"
                        . "c.pkId, c.assetId,"
                        . "s.number,a.assetTypeName")
                ->from("Placements", "p")
                ->leftJoin("p.stockBatchWarehouse", "sbw")
                ->leftJoin("sbw.stockBatch", "s")
                ->leftJoin("p.ccm", "c")
                ->leftJoin("c.ccmAssetType", "a")
                ->where("p.isPlaced = 1")
                ->andWhere("p.stockDetail = " . $this->form_values['stock_detail']);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Add
     * @return type
     */
    public function add() {
        $data = $this->form_values;
        $qty = $data['quantity'];
        $placement = new Placements();
        $placement->setQuantity($qty);
        $placement_loc_id = $this->_em->getRepository("PlacementLocations")->find($data['placement_loc_id']);
        $placement->setPlacementLocation($placement_loc_id);
        $batch_id = $this->_em->getRepository("StockBatchWarehouses")->find($data['batch_id']);
        $placement->setStockBatchWarehouse($batch_id);
        if (!empty($data['detail_id'])) {
            $detail_id = $this->_em->getRepository("StockDetail")->find($data['detail_id']);
            $placement->setStockDetail($detail_id);
        }
        $type_id = $this->_em->getRepository("ListDetail")->find($data['placement_loc_type_id']);
        $placement->setPlacementTransactionType($type_id);
        $created_by = $this->_em->getRepository("Users")->find($data['user_id']);
        $placement->setCreatedBy($created_by);
        $placement->setCreatedDate(App_Tools_Time::now());
        $placement->setModifiedBy($created_by);
        $placement->setModifiedDate(App_Tools_Time::now());
        $vvms = $this->_em->getRepository("VvmStages")->find($data['vvmstage']);
        $placement->setVvmStage($vvms);
        $placement->setIsPlaced($data['is_placed']);
        $this->_em->persist($placement);
        $this->_em->flush();
        return $placement->getPkId();
    }

    /**
     * Add Placement
     * @return boolean
     */
    public function addPlacement() {

        $form_values = $this->form_values;
        if ($form_values['rcvedit'] == "Yes") {
            $plcs = $this->_em->getRepository("Placements")->findOneBy(array("stockDetail" => $form_values['stock_detail_id']));
            if (count($plcs) > 0) {
                $placement = $this->_em->getRepository("Placements")->findOneBy(array("stockDetail" => $form_values['stock_detail_id']));
            } else {
                $placement = new Placements();
            }
        } else {
            $placement = new Placements();
        }
        if (!empty($form_values['batchId'])) {
            $stock_batch_id = $this->_em->find("StockBatchWarehouses", $form_values['batchId']);
            $placement->setStockBatchWarehouse($stock_batch_id);
            $qty = str_replace(",", "", $form_values['quantity']);

            $placement->setQuantity($qty);
        }
        if (!empty($form_values['placement_location_id'])) {
            // Vaccines
            if ($form_values['item_category_id'] == 1) {
                $placement_location_id = $this->_em->getRepository("PlacementLocations")->findOneBy(array("locationId" => $form_values['placement_location_id'], "locationType" => Model_PlacementLocations::LOCATIONTYPE_CCM));
            }
            // Non Vaccines
            else {
                $placement_location_id = $this->_em->getRepository("PlacementLocations")->findOneBy(array("locationId" => $form_values['placement_location_id'], "locationType" => Model_PlacementLocations::LOCATIONTYPE_NONCCM));
            }
            if (count($placement_location_id) > 0) {
                $placement->setPlacementLocation($placement_location_id);
            }
        }
        if (!empty($form_values['stock_detail_id'])) {
            $stock_detail_id = $this->_em->find("StockDetail", $form_values['stock_detail_id']);
            $placement->setStockDetail($stock_detail_id);
        }
        $transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_P);
        $placement->setPlacementTransactionType($transaction_type);
          $vvms = $this->_em->getRepository("VvmStages")->find(0);
        if (!empty($form_values['vvm_stage'])) {
            $vvms = $this->_em->getRepository("VvmStages")->find($form_values['vvm_stage']);
           
        }
        $placement->setVvmStage($vvms);
        $placement->setIsPlaced($form_values['is_placed']);
        $user_id = $this->_em->find("Users", $this->_user_id);
        $placement->setCreatedBy($user_id);
        $placement->setCreatedDate(new DateTime(date("Y-m-d h:i:s")));
        $placement->setModifiedBy($user_id);
        $placement->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($placement);
        $this->_em->flush();

        return true;
    }

    /**
     * Add Transfer Stock
     */
    public function addTransferStock() {
        $form_values = $this->form_values;
        $placement_location_id = $this->_em->getRepository("PlacementLocations")->findOneBy(array("locationId" => $form_values['non_ccm_location_id'], "locationType" => Model_PlacementLocations::LOCATIONTYPE_NONCCM));
        $placement_id = $form_values['id'];
        $plac = $this->_em->find("PlacementSummary", $placement_id);
        $stock_batch = $plac->getStockBatchWarehouse();
        $plc_transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_T);
        $user_id = $this->_em->find("Users", $this->_user_id);
        $placement = new Placements();
        $placement->setPlacementLocation($placement_location_id);
        $placement->setQuantity($form_values['quantity']);
        $placement->setStockBatchWarehouse($stock_batch);
        $placement->setPlacementTransactionType($plc_transaction_type);
        $placement->setCreatedBy($user_id);
        $placement->setCreatedDate(new \DateTime());
        $placement->setModifiedBy($user_id);
        $placement->setModifiedDate(App_Tools_Time::now());
        $vvms = $this->_em->getRepository("VvmStages")->find(0);
        $placement->setVvmStage($vvms);
        $this->_em->persist($placement);
        $placement2 = new Placements();
        $placement2->setQuantity("-" . $form_values['quantity']);
        $placement2->setPlacementLocation($plac->getPlacementLocation());
        $placement2->setStockBatchWarehouse($plac->getStockBatchWarehouse());
        $placement2->setPlacementTransactionType($plc_transaction_type);
        $placement2->setCreatedBy($user_id);
        $placement2->setCreatedDate(new \DateTime());
        $vvms = $this->_em->getRepository("VvmStages")->find(0);
        $placement2->setVvmStage($vvms);
        $placement2->setCreatedBy($user_id);
        $placement2->setCreatedDate(App_Tools_Time::now());
        $placement2->setModifiedBy($user_id);
        $placement2->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($placement2);
        $this->_em->flush();
        return true;
    }

    /**
     * Add Transfer Stock Vaccines
     * @return boolean
     */
    public function addTransferStockVaccines() {
        $form_values = $this->form_values;
        $placement_location_id = $this->_em->getRepository("PlacementLocations")->findOneBy(array("locationId" => $form_values['asset_id'], "locationType" => Model_PlacementLocations::LOCATIONTYPE_CCM));
        $placement_id = $form_values['id'];
        $plac = $this->_em->find("PlacementSummary", $placement_id);
        $plc_transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_T);
        $user_id = $this->_em->find("Users", $this->_user_id);
        $placement = new Placements();
        $placement->setPlacementLocation($placement_location_id);
        $placement->setQuantity($form_values['quantity']);
        $placement->setStockBatchWarehouse($plac->getStockBatchWarehouse());
        $placement->setPlacementTransactionType($plc_transaction_type);
        $placement->setCreatedBy($user_id);
        $placement->setCreatedDate(new \DateTime(date("Y-m-d")));
        $placement->setModifiedBy($user_id);
        $placement->setModifiedDate(App_Tools_Time::now());
        $placement->setVvmStage($plac->getVvmStage());
        $placement->setIsPlaced(1);
        $this->_em->persist($placement);
        $placement2 = new Placements();
        $placement2->setQuantity("-" . $form_values['quantity']);
        $placement2->setPlacementLocation($plac->getPlacementLocation());
        $placement2->setStockBatchWarehouse($plac->getStockBatchWarehouse());
        if ($placement2->getStockDetail() != null) {
            $placement2->setStockDetail($plac->getStockDetail());
        }
        $placement2->setPlacementTransactionType($plc_transaction_type);
        $placement2->setCreatedBy($user_id);
        $placement2->setCreatedDate(new \DateTime(date("Y-m-d")));
        $placement2->setModifiedBy($user_id);
        $placement2->setModifiedDate(App_Tools_Time::now());
        $placement2->setVvmStage($plac->getVvmStage());
        $placement2->setIsPlaced(0);
        $this->_em->persist($placement2);
        $this->_em->flush();
        return true;
    }

    /**
     * Get Product Placements
     * @param type $plc_id
     * @param type $id
     * @return type
     */
    public function getProductPlacements($plc_id, $id) {

        $str_sql = $this->_em->getConnection()->prepare("SELECT
                    placements.pk_id,
                    placements.quantity,
                    stock_batch.number,
                    item_pack_sizes.item_name,
                    placement_locations.pk_id,
                    pack_info.quantity_per_pack
                FROM
                    placements
               
                INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id    
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                WHERE
                    placements.stock_detail_id =" . $id . " AND
                    placements.placement_location_id =" . $plc_id);
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Stock In Bin
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getStockInBin($order, $sort) {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = "SELECT DISTINCT
                            placement_summary.batch_number AS BatchNo,
                            placement_summary.stock_batch_warehouse_id AS BatchID,
                            non_ccm_locations.location_name AS LocationName,
                            non_ccm_locations.pk_id AS LocationID,
                            placement_summary.pk_id AS PlacementID,
                            placement_summary.quantity AS Qty,
                            placement_summary.item_name,
                            stakeholder_item_pack_sizes.pk_id,
                            pack_info.quantity_per_pack as quantity_per_pack
                    FROM
                            placement_summary
                    INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
                    INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                    INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
                    INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    WHERE
                            stock_detail.`temporary` = 0 AND
                            non_ccm_locations.warehouse_id = " . $wh_id . "
                    AND placement_summary.placement_location_id = " . $this->form_values['id'] . "";


        if ($order == 'product') {
            $str_sql .= " ORDER BY placement_summary.item_name $sort";
        }
        if ($order == 'batch_no') {
            $str_sql .= " ORDER BY stock_batch.number $sort";
        }
        if ($order == 'carton_qty') {
            $str_sql .= " ORDER BY placement_summary.quantity $sort";
        }
        if ($order == 'qty') {
            $str_sql .= " ORDER BY placement_summary.quantity $sort";
        }

        $res = $this->_em_read->getConnection()->prepare($str_sql);
        $res->execute();
        return $res->fetchAll();
    }

    /**
     * Get Stock In Bin Vaccines
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getStockInBinVaccines($order, $sort) {
        $warehouse_id = $this->_identity->getWarehouseId();
        $id = $this->form_values['id'];
        /**
         * Ignore temporary batches from the list
         */
        $dql_subquery = $this->_em_read->createQueryBuilder()
                ->select("sb.pkId")
                ->from("StockDetail", "sd")
                ->join('sd.stockBatchWarehouse', 'sb')
                ->where("sd.temporary = 1")
                ->andWhere("sb.warehouse =" . $warehouse_id);
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ps")
                ->from("PlacementSummary", "ps")
                ->join('ps.stockBatchWarehouse', 'sbw')
                ->join('sbw.stockBatch', 'sba')
                ->where("ps.placementLocation = $id")
                ->andWhere("sbw.warehouse =" . $warehouse_id)
                ->andWhere("sbw.pkId NOT IN (" . $dql_subquery->getDql() . ")");

        switch ($order) {
            case 'product':
                $str_sql->orderBy("ps.itemName", $sort);
                break;
            case 'batch_no':
                $str_sql->orderBy("ps.batchNumber", $sort);
                break;
            case 'qty':
                $str_sql->orderBy("ps.quantity", $sort);
                break;
            case 'expiry':
                $str_sql->orderBy("sba.expiryDate", $sort);
                break;

            default:
                $str_sql->orderBy("ps.itemName,sb.number", "ASC");
                break;
        }

        $str_sql->having("ps.quantity > 0");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Product Placements By Stock
     * @param type $sb
     * @return boolean
     */
    public function getProductPlacementsByStock($sb) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ips.quantityPerPack')
                ->from("Placements", "p")
                ->join('p.stockBatchWarehouse', 'sbw')
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->join('sb.itemPackSize', 'ips')
                ->where("p.stockBatchWarehouse =" . $sb);
        $result = $str_sql->getQuery()->getResult();
        if (!empty($result) && count($result) > 0) {
            return $result[0]['quantityPerPack'];
        } else {
            return false;
        }
    }

    /**
     * Get Placement By Batch
     * @param type $sb
     * @return boolean
     */
    public function getPlacementByBatch($sb) {

        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                placements.quantity,
                placements.placement_location_id,
                non_ccm_locations.location_name,
                pack_info.quantity_per_pack
                FROM
                placements
                INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                WHERE
                placements.stock_batch_warehouse_id =" . $sb);


        $str_sql->execute();
        $result = $str_sql->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Search Batch Locations
     * @return boolean
     */
    public function searchBatchLocations() {
        $type = $this->form_values['loc_type'];
        if ($type == Model_PlacementLocations::LOCATIONTYPE_NONCCM) {

            $str_sql = "SELECT
            placement_locations.location_id as LocationID,
            non_ccm_locations.location_name AS LocationName,
            item_pack_sizes.pk_id AS ItemID,
            item_pack_sizes.item_name AS ItemName,
            item_pack_sizes.item_category_id,
            ROUND(
                            abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
                    ) AS pack_quantity,
            abs(Sum(placements.quantity)) AS Qty,
            stock_batch_warehouses.pk_id AS BatchID,
            stock_batch.number AS BatchNo,
            pack_info.quantity_per_pack AS quantity_per_pack,
            placement_locations.pk_id as PlacementID
            FROM
                    placement_locations
            LEFT JOIN placements ON placement_locations.pk_id = placements.placement_location_id
            INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
            LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
                    non_ccm_locations.warehouse_id = " . $this->form_values['wh_id'] . "
            AND stock_batch_warehouses.pk_id =" . $this->form_values['batch_id'] . "
            GROUP BY
                    non_ccm_locations.pk_id  HAVING Qty > 0
            ";
            $result = $this->_em_read->getConnection()->prepare($str_sql);
        } else {

            $str_sql = "SELECT
                placement_locations.location_id AS LocationID,
                cold_chain.asset_id AS LocationName,
                item_pack_sizes.pk_id AS ItemID,
                item_pack_sizes.item_name AS ItemName,
                item_pack_sizes.item_category_id,
                ROUND(
                        abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
                ) AS pack_quantity,
                abs(Sum(placements.quantity)) AS Qty,
                placements.vvm_stage AS VVMStage,
                stock_batch_warehouses.pk_id AS BatchID,
                stock_batch.number AS BatchNo,
                pack_info.quantity_per_pack AS quantity_per_pack,
                placement_locations.pk_id AS PlacementID,
                placements.pk_id as placements_id
        FROM
                placement_locations
        LEFT JOIN placements ON placement_locations.pk_id = placements.placement_location_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                cold_chain.warehouse_id = " . $this->form_values['wh_id'] . "
        AND stock_batch_warehouses.pk_id = " . $this->form_values['batch_id'] . "
        GROUP BY
        cold_chain.pk_id,
        placements.vvm_stage,
        placements.stock_batch_warehouse_id
        HAVING Qty > 0";

            $result = $this->_em_read->getConnection()->prepare($str_sql);
        }
        $result->execute();
        $res = $result->fetchAll();
        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * Search Locations Products
     * @return boolean
     */
    public function searchLocationsProducts() {
        $type = $this->form_values['loc_type'];
        // 100 - Non CCm
        // 99 - CCM
        if ($type == 100) {

            $str_sql = "SELECT
            placement_locations.location_id AS LocationID,
            non_ccm_locations.location_name AS LocationName,
            item_pack_sizes.pk_id AS ItemID,
            item_pack_sizes.item_name AS ItemName,
            ROUND(
                            abs(Sum(placements.quantity)) / pack_info.quantity_per_pack
                    ) AS pack_quantity,
            abs(Sum(placements.quantity)) AS Qty,
            stock_batch_warehouses.pk_id AS BatchID,
            pack_info.quantity_per_pack AS quantity_per_pack,
            placement_locations.pk_id AS placement_locationsid,
            item_pack_sizes.item_category_id,
            stock_batch.expiry_date AS Expiry,
            stock_batch.number as BatchNo,
            placements.stock_detail_id AS DetailID,
            non_ccm_locations.warehouse_id as wh_id
            FROM
                    placement_locations
            LEFT JOIN placements ON placement_locations.pk_id = placements.placement_location_id
            INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
            LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
                    non_ccm_locations.warehouse_id =" . $this->form_values['wh_id'] . " and placement_locations.pk_id = " . $this->form_values['p_loc_id'] . "
            GROUP BY
                    non_ccm_locations.pk_id,
            stock_batch_warehouses.pk_id 
            HAVING Qty > 0
            ";
            $result = $this->_em_read->getConnection()->prepare($str_sql);
        } else {


            $str_sql = "SELECT
            placement_locations.location_id AS LocationID,
            cold_chain.asset_id AS LocationName,
            item_pack_sizes.pk_id AS ItemID,
            item_pack_sizes.item_name AS ItemName,
            ROUND(abs(Sum(placements.quantity)) / pack_info.quantity_per_pack) AS pack_quantity,
            abs(Sum(placements.quantity)) AS Qty,
            stock_batch_warehouses.pk_id AS BatchID,
            pack_info.quantity_per_pack AS quantity_per_pack,
            placement_locations.pk_id AS placement_locationsid,
            item_pack_sizes.item_category_id,
            stock_batch.expiry_date AS Expiry,
            stock_batch.number as BatchNo,
            placements.stock_detail_id AS DetailID,
            cold_chain.warehouse_id as wh_id
            FROM
                    placement_locations
            LEFT JOIN placements ON placement_locations.pk_id = placements.placement_location_id
            INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
            LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
                    cold_chain.warehouse_id =" . $this->form_values['wh_id'] . " and placement_locations.pk_id = " . $this->form_values['p_loc_id'] . "
            GROUP BY
                    cold_chain.pk_id,
            stock_batch_warehouses.pk_id";

            $result = $this->_em_read->getConnection()->prepare($str_sql);
        }
        $result->execute();
        $res = $result->fetchAll();
        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * Add Place Stock
     * @return boolean
     */
    public function addPlaceStock() {
        $form_values = $this->form_values;
        foreach ($form_values['quantity'] as $key => $value) {
            if (!empty($value) && $value > 0) {
                $placement = new Placements();
                list($stcdetail, $stcbatch) = explode('_', $key);
                $placement->setQuantity($value * $form_values['quantity_per_pack']);
                $stock_batch = $this->_em->find("StockBatchWarehouses", $stcbatch);
                $placement->setStockBatchWarehouse($stock_batch);
                $stock_detail = $this->_em->find("StockDetail", $stcdetail);
                $placement->setStockDetail($stock_detail);
                $placement_location = $this->_em->find("PlacementLocations", $form_values['placement_loc_id']);
                $placement->setPlacementLocation($placement_location);
                $plc_transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_P);
                $placement->setPlacementTransactionType($plc_transaction_type);
                $user_id = $this->_em->find("Users", $this->_user_id);
                $placement->setCreatedBy($user_id);
                $placement->setCreatedDate(new \DateTime());
                $this->_em->persist($placement);
            }
        }
        $this->_em->flush();
        return true;
    }

    /**
     * Add Place Stock Vaccines
     * @return boolean
     */
    public function addPlaceStockVaccines() {
        $form_values = $this->form_values;
        foreach ($form_values['quantity'] as $key => $value) {
            if (!empty($value) && $value > 0) {
                $placement = new Placements();
                list($stcdetail, $stcbatch) = explode('_', $key);
                $placement->setQuantity($value);
                $stock_batch_warehouse = $this->_em->find("StockBatchWarehouses", $stcbatch);
                $placement->setStockBatchWarehouse($stock_batch_warehouse);
                $stock_detail = $this->_em->find("StockDetail", $stcdetail);
                $placement->setStockDetail($stock_detail);
                $placement_location = $this->_em->find("PlacementLocations", $form_values['placement_loc_id']);
                $placement->setPlacementLocation($placement_location);
                $placement->setVvmStage($stock_detail->getVvmStage());
                $placement->setIsPlaced(1);
                $plc_transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_P);
                $placement->setPlacementTransactionType($plc_transaction_type);
                $user_id = $this->_em->find("Users", $this->_user_id);
                $placement->setCreatedBy($user_id);
                $placement->setModifiedBy($user_id);
                $placement->setCreatedDate(new \DateTime());
                $this->_em->persist($placement);
            }
        }
        $this->_em->flush();
        return true;
    }

    // 17-07-2014
    /**
     * Update Vvm Stage
     * @return boolean
     */
    public function updateVvmStage() {
        $batch_id = $this->form_values['batch_id'];
        $placement_id = $this->form_values['placement_id'];
        $vvm_stage = $this->form_values['vvm_stage'];
        $qty = $this->form_values['qty'];
        $old_vvm_stage = $this->form_values['old_vvm_stage'];
        $placement = new Model_Placements();
        $placement->form_values = array(
            'quantity' => "-" . $qty,
            'placement_loc_id' => $placement_id,
            'batch_id' => $batch_id,
            'placement_loc_type_id' => 116,
            'user_id' => 159,
            'created_by' => 159,
            'modified_by' => 159,
            'created_date' => date("Y-m-d"),
            'vvmstage' => $old_vvm_stage,
            'is_placed' => 0
        );
        $placement->add();

        $placement->form_values = array(
            'quantity' => $qty,
            'placement_loc_id' => $placement_id,
            'batch_id' => $batch_id,
            'placement_loc_type_id' => 116,
            'user_id' => 159,
            'created_by' => 159,
            'modified_by' => 159,
            'created_date' => date("Y-m-d"),
            'vvmstage' => $vvm_stage,
            'is_placed' => 1
        );
        $placement->add();
        return true;
    }

    /**
     * Stock Pick Detail Vaccines
     * @param type $stcdet
     * @param type $stcbat
     * @param type $wh_id
     * @param type $item_cat
     * @return boolean
     */
    public function stockPickDetailVaccines($stcdet, $stcbat, $wh_id, $item_cat) {
        $str_sql = "SELECT
        stock_batch.expiry_date AS Expiry,
        stock_batch.number AS BatchNo,
        items.pk_id AS ItemID,
        stock_batch_warehouses.pk_id AS BatchID,
        items.description AS ItemName,
        placements.stock_detail_id AS DetailID,
        cold_chain.asset_id as LocationName,
        cold_chain.pk_id as LocationID,
        placements.pk_id AS PlacementID,
        placements.vvm_stage AS VVMStage,
        Sum(placements.quantity) AS Qty,
        pack_info.quantity_per_pack,
        placements.placement_location_id AS plc_loc_id
        FROM
                placements
        INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
        INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        WHERE
                placements.stock_batch_warehouse_id = " . $stcbat . "
        AND cold_chain.warehouse_id = " . $wh_id . "
        GROUP BY
                placements.stock_batch_warehouse_id,
                cold_chain.asset_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Stock Pick Detail
     * @param type $stcdet
     * @param type $stcbat
     * @param type $wh_id
     * @param type $item_cat
     * @return boolean
     */
    public function stockPickDetail($stcdet, $stcbat, $wh_id, $item_cat) {
        $str_sql = "SELECT
        stock_batch.expiry_date AS Expiry,
        stock_batch.number AS BatchNo,
        item_pack_sizes.pk_id AS ItemID,
        stock_batch_warehouses.pk_id AS BatchID,
        item_pack_sizes.item_name AS ItemName,
        placements.stock_detail_id AS DetailID,
        non_ccm_locations.location_name AS LocationName,
        non_ccm_locations.pk_id AS LocationID,
        placements.pk_id AS PlacementID,
        placements.vvm_stage AS VVMStage,
        Sum(placements.quantity) AS Qty,
        pack_info.quantity_per_pack,
        placements.placement_location_id AS plc_loc_id
        FROM
                placements
        INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        WHERE placements.stock_batch_warehouse_id = " . $stcbat . "
        AND non_ccm_locations.warehouse_id = " . $wh_id . "
        GROUP BY
                placements.stock_batch_warehouse_id,
        non_ccm_locations.location_name";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Add Stock Quantity
     * @return boolean
     */
    public function addStockQuantity() {
        $data = $this->form_values;
        $loc_id = $data['loc_id'];
        foreach ($data['quantity'] as $key => $value) {
            if (!empty($value) && $value > 0) {
                $placement = new Placements();
                $placement->setQuantity("-" . $value);
                $placement_loc_id = $this->_em->find("PlacementLocations", $loc_id[$key]);
                $placement->setPlacementLocation($placement_loc_id);
                $batch_id = $this->_em->find("StockBatchWarehouse", $data['BatchID']);
                $placement->setStockBatchWarehouse($batch_id);
                $detail_id = $this->_em->find("StockDetail", $data['DetailID']);
                $placement->setStockDetail($detail_id);
                $placement->setVvmStage($detail_id->getVvmStage());
                $placement->setIsPlaced(0);
                $plc_transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_PICK);
                $placement->setPlacementTransactionType($plc_transaction_type);
                $user_id = $this->_em->find("Users", $this->_user_id);
                $placement->setCreatedBy($user_id);
                $placement->setCreatedDate(new \DateTime($data['created_date']));
                $this->_em->persist($placement);
            }
        }
        $this->_em->flush();
        return true;
    }

    /**
     * Get Batch Placment Detail
     * @param type $id
     * @return boolean
     */
    public function getBatchPlacmentDetail($id) {
        $str_sql = "SELECT
	cold_chain.asset_id,
	placement_summary.quantity,
	placement_summary.batch_number,
IF (
	item_pack_sizes.vvm_group_id = 1,
	vvm_stages.pk_id,
	vvm_stages.vvm_stage_value
) vvm_stage
FROM
	placement_summary
INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
INNER JOIN vvm_stages ON placement_summary.vvm_stage = vvm_stages.pk_id
WHERE
	placement_summary.stock_batch_warehouse_id = $id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Non Vaccine Batch Placment Detail
     * @param type $id
     * @return boolean
     */
    public function getNonVaccineBatchPlacmentDetail($id) {
        $str_sql = "SELECT
        placement_summary.quantity,
        placement_summary.batch_number,
        non_ccm_locations.pk_id AS asset_id,
        non_ccm_locations.location_name
        FROM
                placement_summary
        INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        WHERE placement_summary.stock_batch_warehouse_id = $id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Product Placment Detail
     * @param type $id
     * @return boolean
     */
    public function getProductPlacmentDetail($id) {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                            cold_chain.asset_id,
                            placement_summary.quantity,
                            placement_summary.batch_number,
                            placement_locations.pk_id,
                            IF(item_pack_sizes.vvm_group_id=1,vvm_stages.pk_id,vvm_stages.vvm_stage_value) vvm_stage
                    FROM
                            placement_summary
                    INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
                    INNER JOIN vvm_stages ON placement_summary.vvm_stage = vvm_stages.pk_id
                    WHERE
                            stakeholder_item_pack_sizes.item_pack_size_id = $id AND
                    stock_batch_warehouses.warehouse_id = $wh_id ORDER BY cold_chain.asset_id,placement_summary.batch_number";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Product Placment Summary
     * @param type $id
     * @return boolean
     */
    public function getProductPlacmentSummary($id) {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                            cold_chain.asset_id,
                            SUM(placement_summary.quantity) as quantity,
                            placement_locations.pk_id,
                            IF(item_pack_sizes.vvm_group_id=1, vvm_stages.pk_id, vvm_stages.vvm_stage_value) vvm_stage
                    FROM
                            placement_summary
                    INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id    
                    INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id  
                    INNER JOIN vvm_stages ON placement_summary.vvm_stage = vvm_stages.pk_id
                    WHERE
                            stakeholder_item_pack_sizes.item_pack_size_id = $id AND
                    stock_batch_warehouses.warehouse_id = $wh_id "
                . "GROUP BY cold_chain.asset_id,placement_summary.vvm_stage"
                . "  ORDER BY cold_chain.asset_id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Stock In Bin Vaccines Graph
     * @return string
     */
    public function getStockInBinVaccinesGraph() {
        $warehouse_id = $this->_identity->getWarehouseId();
        $id = $this->form_values['id'];
        $str_sql = "SELECT DISTINCT
                cold_chain.pk_id,
                cold_chain.asset_id,
                cold_chain.auto_asset_id,
                AssetSubtype.asset_type_name,
                placement_locations.pk_id AS plc_pk_id,
                ccm_models.ccm_model_name,
                round( ( SUM( ( placements.quantity * pack_info.volum_per_vial ) / 1000 ) ) / 
                ( ccm_models.net_capacity_20 + ccm_models.net_capacity_4 ) * 100 ) AS used_percentage
                FROM
                cold_chain
                INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
                INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
                LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
                LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id   
                WHERE cold_chain.warehouse_id = $warehouse_id
                AND ( ( cold_chain.ccm_asset_type_id = 3 OR AssetMainType.pk_id = 3 )
                        OR ( cold_chain.ccm_asset_type_id = 1 OR AssetMainType.pk_id = 1 ) )
                AND placement_locations.location_type = " . parent::LOCATIONTYPE_CCM . "
                    AND placement_locations.pk_id = '" . $id . "'
                GROUP BY
                cold_chain.auto_asset_id ORDER BY cold_chain.asset_id,cold_chain.ccm_asset_type_id DESC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        $used_per = $data[0]['used_percentage'];
        $total_per = 100 - $data[0]['used_percentage'];
        if ($used_per > 100) {
            $total_per = 0;
        }

        $asset_id = $data[0]['asset_id'];
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='$asset_id Capacity Utilization Status' subcaption='' exportFileName=' " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' numberSuffix='%'  formatNumberScale='0' >";
        $xmlstore .= "<set label='Remaining' color='#008000' value='$total_per' />";
        $xmlstore .= "<set label='Used' color='#FF0000' value='$used_per' />";
        return $xmlstore . "</chart>";
    }

    /**
     * Location Type
     * @param type $id
     * @return boolean
     */
    public function locationType($id) {
        $str_sql = "SELECT
                placement_locations.location_type
                FROM
                placement_locations
                WHERE
                placement_locations.pk_id = $id";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result[0]['location_type'];
        } else {
            return false;
        }
    }

    /**
     * Get Available Vvm Stages
     * @param type $batch_id
     * @param type $item_cat
     * @return boolean
     */
    public function getAvailableVvmStages($batch_id, $item_cat) {
        $form_values = $this->form_values;
        $where = '';
        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $current_date->modify("yesterday"); // It's required. Don't delete this line
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");
        if (!empty($form_values)) {
            $priority = $form_values['priority'];
            switch (trim($priority)) {
                case 'P1':
                    $where = " AND (
                            placements.vvm_stage = 2
                            OR ( placements.vvm_stage = 1 AND DATE_FORMAT( stock_batch.expiry_date, '%Y-%m-%d' ) BETWEEN '$today' AND '$after3month' ) )";
                    break;
                case 'P2':
                    $where = " AND placements.vvm_stage = 1
                            AND DATE_FORMAT( stock_batch.expiry_date, '%Y-%m-%d' ) BETWEEN '$after3month' AND '$afteryear'";
                    break;
                case 'P3':
                    $where = " AND placements.vvm_stage = 1
                            AND DATE_FORMAT( stock_batch.expiry_date, '%Y-%m-%d' ) >= '$afteryear'";
                    break;
                default :
                    break;
            }
        }
        if ($item_cat == 1) {
            $str_sql = "SELECT DISTINCT
                            Sum(placements.quantity) AS qty,
                            placements.placement_location_id,
                            cold_chain.asset_id,
                            vvm_stages.pk_id as vvm_stage_id,
                            IF(item_pack_sizes.vvm_group_id=1,vvm_stages.pk_id,vvm_stages.vvm_stage_value) vvm_stage
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
                    WHERE
                    placements.stock_batch_warehouse_id = $batch_id
                    AND placement_locations.location_type = 99
                    $where
                    GROUP BY
                    vvm_stages.pk_id,
                    placements.stock_batch_warehouse_id,
                    placements.placement_location_id HAVING qty > 0";
        } else {
            $str_sql = "SELECT DISTINCT
                            Sum(placements.quantity) AS qty,
                            placements.placement_location_id,
                            non_ccm_locations.location_name as asset_id
                    FROM
                            placements
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                    WHERE
                            placements.stock_batch_warehouse_id = $batch_id
                    AND placement_locations.location_type = 100
                    GROUP BY
                            placements.stock_batch_warehouse_id,
                            placements.placement_location_id HAVING qty > 0";
        }
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Add Issue Placement
     * @return type
     */
    public function addIssuePlacement() {
        $data = $this->form_values;
        $qty = $data['quantity'];
        $placement = new Placements();
        $placement->setQuantity($qty);
        $placement_loc_id = $this->_em->getRepository("PlacementLocations")->find($data['placement_loc_id']);
        $placement->setPlacementLocation($placement_loc_id);
        $batch_id = $this->_em->getRepository("StockBatchWarehouses")->find($data['batch_id']);
        $placement->setStockBatchWarehouse($batch_id);
        if ($data['detail_id'] != 0) {
            $detail_id = $this->_em->getRepository("StockDetail")->find($data['detail_id']);
            $placement->setStockDetail($detail_id);
        }
        $type_id = $this->_em->getRepository("ListDetail")->find($data['placement_loc_type_id']);
        $placement->setPlacementTransactionType($type_id);
        $created_by = $this->_em->getRepository("Users")->find($data['user_id']);
        $placement->setCreatedBy($created_by);
        $placement->setCreatedDate(new \DateTime($data['created_date']));
        $vvms = $this->_em->getRepository("VvmStages")->find($data['vvmstage']);
        $placement->setVvmStage($vvms);
        $placement->setIsPlaced($data['is_placed']);
        $placement->setModifiedBy($created_by);
        $placement->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($placement);
        $this->_em->flush();
        return $placement->getPkId();
    }

    /**
     * Get Issue Available Vvm Stages
     * @return boolean
     */
    public function getIssueAvailableVvmStages() {
        $batch_id = $this->form_values['batch_id'];
        $priority = $this->form_values['priority'];
        $batch = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
        $item_cat = $batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();
        $form_values['priority'] = $priority;
        $where = '';
        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");
        if (!empty($form_values)) {
            $priority = $form_values['priority'];
            switch (trim($priority)) {
                case 'P1':
                    $where = " AND ( placements.vvm_stage = 2
                            OR ( placements.vvm_stage = 1 AND DATE_FORMAT( stock_batch.expiry_date, '%Y-%m-%d' ) 
                            BETWEEN '$today' AND '$after3month' )
                    )";
                    break;
                case 'P2':
                    $where = " AND placements.vvm_stage = 1
                            AND DATE_FORMAT( stock_batch.expiry_date, '%Y-%m-%d' ) BETWEEN '$after3month' AND '$afteryear'";
                    break;
                case 'P3':
                    $where = " AND placements.vvm_stage = 1
                            AND DATE_FORMAT( stock_batch.expiry_date, '%Y-%m-%d' ) > '$afteryear'";
                    break;
                default :
                    break;
            }
        }
        if ($item_cat == 1) {
            $str_sql = "SELECT DISTINCT
                            Sum(placements.quantity) AS qty,
                            placements.placement_location_id,
                            cold_chain.asset_id,
                            vvm_stages.pk_id as vvm_stage_id,
                            IF(item_pack_sizes.vvm_group_id=1,vvm_stages.pk_id,vvm_stages.vvm_stage_value) vvm_stage
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
                    WHERE
                            placements.stock_batch_warehouse_id = $batch_id
                    AND placement_locations.location_type = 99
                    $where
                    GROUP BY
                            vvm_stages.pk_id,
                            placements.stock_batch_warehouse_id,
                            placements.placement_location_id HAVING qty > 0";
        } else {
            $str_sql = "SELECT DISTINCT
                            Sum(placements.quantity) AS qty,
                            placements.placement_location_id,
                            non_ccm_locations.location_name as asset_id
                    FROM
                            placements
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                    WHERE
                            placements.stock_batch_warehouse_id = $batch_id
                    AND placement_locations.location_type = 100
                    GROUP BY
                            placements.stock_batch_warehouse_id,
                            placements.placement_location_id HAVING qty > 0";
        }
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Delete Stock Placement
     * @param type $data
     * @return boolean
     */
    public function deleteStockPlacement($data) {
        $placement_info = explode(",", $data);
        // Get each placement batch, location,vvm stage and qty.
        foreach ($placement_info as $info) {
            $ids = explode("|", $info); // batch_id|placement_loc_id|vvm_stage_id|qty
            // get stock batch
            $stock_batch = $this->_em->getRepository("StockBatchWarehouses")->find($ids['0']);
            // get placement location
            $placement_location = $this->_em->getRepository("PlacementLocations")->find($ids['1']);
            // Get transaction type i.e stock picking.
            $plc_transaction_type = $this->_em->find("ListDetail", Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_PICK);
            // Get user id.
            $user_id = $this->_em->find("Users", $this->_user_id);
            $placement = new Placements();
            $placement->setPlacementLocation($placement_location);
            $placement->setStockBatchWarehouse($stock_batch);
            $placement->setPlacementTransactionType($plc_transaction_type);
            $placement->setCreatedBy($user_id);
            $placement->setCreatedDate(App_Tools_Time::now());
            $placement->setModifiedBy($user_id);
            $placement->setModifiedDate(App_Tools_Time::now());
            $vvms = $this->_em->getRepository("VvmStages")->find($ids['2']);
            $placement->setVvmStage($vvms);
            // get qty
            $qty = (-1) * $ids['3'];
            $placement->setQuantity($qty);
            $this->_em->persist($placement);
        }
        $this->_em->flush();
        return true;
    }

    /**
     * Update Stock Placement
     * @param type $data
     * @param type $placement_type
     * @return boolean
     */
    public function updateStockPlacement($data, $placement_type) {
        // batch_id|placement_loc_id|vvm_stage_id|qty, batch_id|placement_loc_id|vvm_stage_id|qty,batch_id|placement_loc_id|vvm_stage_id|qty
        $placement_info = explode(",", $data);
        // Get each placement batch, location,vvm stage and qty.
        // batch_id|placement_loc_id|vvm_stage_id|qty
        foreach ($placement_info as $info) {
            $ids = explode("|", $info);
            // get stock batch
            $stock_batch = $this->_em->getRepository("StockBatchWarehouses")->find($ids['0']);
            // get placement location
            $placement_location = $this->_em->getRepository("PlacementLocations")->find($ids['1']);
            // Get transaction type i.e stock picking.
            $plc_transaction_type = $this->_em->find("ListDetail", $placement_type);
            // Get user id.
            $user_id = $this->_em->find("Users", $this->_user_id);
            $placement = new Placements();
            $placement->setPlacementLocation($placement_location);
            $placement->setStockBatchWarehouse($stock_batch);
            $placement->setPlacementTransactionType($plc_transaction_type);
            $placement->setCreatedBy($user_id);
            $placement->setCreatedDate(App_Tools_Time::now());
            $placement->setModifiedBy($user_id);
            $placement->setModifiedDate(App_Tools_Time::now());
            $vvms = $this->_em->getRepository("VvmStages")->find($ids['2']);
            $placement->setVvmStage($vvms);
            // get qty
            if ($placement_type == Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_P) {
                $qty = $ids['3'];
            } elseif ($placement_type == Model_PlacementLocations::PLACEMENT_TRANSACTION_TYPE_PICK) {
                $qty = (-1) * $ids['3'];
            }
            $placement->setQuantity($qty);
            $this->_em->persist($placement);
        }
        $this->_em->flush();
        return true;
    }

    /**
     * Get Stock Bin Name
     * @param type $plac_loc_id
     * @return type
     */
    public function getStockBinName($plac_loc_id) {

        $str_sql = "SELECT
                non_ccm_locations.location_name
                FROM
                        placement_locations
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                WHERE
                        placement_locations.pk_id = " . $plac_loc_id;
        $res = $this->_em_read->getConnection()->prepare($str_sql);
        $res->execute();
        return $res->fetchAll();
    }

}
