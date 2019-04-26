<?php

/**
 * Model_StockBatch
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stock Batch
 */
class Model_StockBatch extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('StockBatchWarehouses');
    }

    /**
     * Add Stock Batch
     * 
     * @param type $array
     * @return type
     */
    public function addStockBatch($array) {
        return ($array['rcvedit'] == "Yes") ? $this->updateBatch($array) : $this->createBatch($array);
    }

    /**
     * Auto Running LEFO Batch
     * 
     * @param type $item_id
     * @return boolean
     */
    public function autoRunningLEFOBatch($item_id) {
        $this->expiryDate = $this->isBatchExists($item_id);


        if ($this->expiryDate) {
            $str_sql_stacked = $this->_em->createQueryBuilder()
                    ->select("sbw.pkId")
                    ->from('StockBatchWarehouses', 'sbw')
                    ->join('sbw.stockBatch', 'sb')
                    ->join('sb.packInfo', 'pi')
                    ->join('pi.stakeholderItemPackSize', 'sip')
                    ->where("sip.itemPackSize = '" . $item_id . "' ")
                    ->andWhere("sbw.warehouse = '" . $this->_identity->getWarehouseId() . "' ");

            $row_stacked = $str_sql_stacked->getQuery()->getResult();


            $stock_stacked = $this->_table->find($row_stacked['0']['pkId']);
            $stock_stacked->setStatus(parent::STACKED);
            $this->_em->flush();

            $str_sql_running = $this->_em->createQueryBuilder()
                    ->select("sbw.pkId")
                    ->from('StockBatchWarehouses', 'sbw')
                    ->join('sbw.stockBatch', 'sb')
                    ->join('sb.packInfo', 'pi')
                    ->join('pi.stakeholderItemPackSize', 'sip')
                    ->where("sip.itemPackSize = '" . $item_id . "' ")
                    ->andWhere("sbw.warehouse = '" . $this->_identity->getWarehouseId() . "' ")
                    ->andWhere("sb.expiryDate= '" . $this->expiryDate . "' ");

            $row_running = $str_sql_running->getQuery()->getResult();

            $stock_running = $this->_table->find($row_running['0']['pkId']);
            $stock_running->setStatus(parent::RUNNING);
            $this->_em->flush();
            return true;
        } else {
            return FALSE;
        }
    }

    /**
     * Is Batch Exists
     * 
     * @param type $item_id
     * @return boolean
     */
    public function isBatchExists($item_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("MIN(sb.expiryDate) AS MinDate")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where("sip.itemPackSize = '" . $item_id . "' ")
                ->andWhere("sbw.warehouse = '" . $this->_identity->getWarehouseId() . "' ")
                ->andWhere('sbw.quantity <> 0');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row['0']['MinDate'];
        } else {
            return false;
        }
    }

    /**
     * Get Batch Quantity By Id
     * 
     * @param type $id
     * @return boolean
     */
    public function getBatchQuantityById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sbw.quantity')
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->where('sbw.pkId =' . $id);
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete Stock Batch
     * 
     * @param type $id
     * @return type
     */
    public function deleteStockBatch($id) {
        $stockBatchWarehouse = $this->_table->find($id);
        $this->_em->remove($stockBatchWarehouse);
        $stockBatchId = $stockBatchWarehouse->getStockBatch()->getPkId();
        $stockBatch = $this->_em->getRepository('StockBatch')->find($stockBatchId);
        $this->_em->remove($stockBatch);
        return $this->_em->flush();
    }

    /**
     * Adjust Quantity
     * 
     * @param type $id
     * @param type $qty
     */
    function adjustQuantity($id, $qty) {
        $str_sql = $this->_em->createQueryBuilder()
                ->update('Model_StockBatch')
                ->set("quantity", '?', $qty)
                ->where("pk_id= ? ", $id);
        $str_sql->execute();
    }

    /**
     * Adjust Quantity By Warehouse
     * 
     * @param type $batch_id
     */
    function adjustQuantityByWarehouse($batch_id) {
        $row = $this->_em->getConnection()->prepare("SELECT AdjustQty($batch_id," . $this->_identity->getWarehouseId() . ") from DUAL");
        $row->execute();
    }

    /**
     * Pick Batch
     * 
     * @param type $batch_id
     * @return type
     */
    public function pickBatch($batch_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('number')
                ->from('Model_StockBatch')
                ->where('pk_id =' . $batch_id);
        return $str_sql->fetchArray();
    }

    /**
     * Get Item All Batches
     * 
     * @uses API & Application
     * @return boolean
     */
    public function getItemAllBatches($wh_id) {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                                                        stock_batch_warehouses.pk_id AS 'pkId',
                                                        stock_batch.number,
                                                        stock_batch_warehouses.stock_batch_id,
                                                        stock_batch.expiry_date AS 'expiryDate',
                                                        stock_batch_warehouses.quantity,
                                                        stock_batch_warehouses.`status`,
                                                        stock_batch.unit_price,
                                                        stock_batch.production_date,
                                                        last_update,
                                                        stakeholder_item_pack_sizes.item_pack_size_id AS 'itemPackSize',
                                                        stock_batch.vvm_type_id,
                                                        stock_batch_warehouses.warehouse_id,
                                                        stock_batch.pack_info_id AS 'StakeholderItemPackSizeID'
                                                        FROM
                                                        stock_batch
                                                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                                                        WHERE
                                                        stock_batch_warehouses.`status` <> 'Finished' AND stock_batch_warehouses.warehouse_id= '" . $wh_id . "'");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Batches By Status
     * 
     * @param type $status
     * @return type
     */
    public function getBatchesByStatus($status) {
        $current_date = new DateTime(date("Y-m-d"));
        $date_curr = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $str_sql = $this->_em_read->createQueryBuilder()->select('DISTINCT sbw.pkId,
                sb.number,
                sb.expiryDate,
                sbw.status,
                sbw.quantity AS BatchQty,
                ips.itemName,
                iu.itemUnitName,
                ips.numberOfDoses as description,
                sd.temporary')
                ->from('StockDetail', 'sd')
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where('sbw.warehouse =' . $this->_identity->getWarehouseId())
                ->andWhere('sd.temporary = 0');

        if (!empty($this->form_values['item_pack_size_id'])) {
            $str_sql->andWhere("sip.itemPackSize = " . $this->form_values['item_pack_size_id']);
        }
        if (!empty($this->form_values['number'])) {
            $str_sql->andWhere("sb.number LIKE '%" . $this->form_values['number'] . "%'");
        }
        if (!empty($this->form_values['transaction_refernece'])) {
            $str_sql->andWhere("sm.transactionReference LIKE '%" . $this->form_values['transaction_refernece'] . "%'");
        }
        if (!empty($this->form_values['expired_before'])) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') BETWEEN '" . $date_curr . "' AND '" . $this->form_values['expired_before'] . "'");
        } else if (!empty($this->form_values['expired_after'])) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'");
        }

        if ($status == parent::PRIORITY1) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') BETWEEN '" . $date_curr . "' AND '" . $after3month . "'");
        } else if ($status == parent::PRIORITY2) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') BETWEEN '" . $after3month . "' AND '" . $afteryear . "'");
        } else if ($status == parent::PRIORITY3) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') > '" . $afteryear . "'");
        } else if ($status == parent::TOTAL) {
            $str_sql->andWhere("sbw.status IN ('STACKED','RUNNING')");
        } else if ($status == parent::EXPIRED) {
            $date_curr = date('Y-m-d');
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') < '$date_curr'");
            $str_sql->andWhere("sbw.quantity > 0");
        } else if (!empty($status)) {
            $str_sql->andWhere("sbw.status = '" . $status . "'");
        } else {
            $status = parent::TOTAL;
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Batches By Status
     * 
     * @param type $status
     * @return type
     */
    public function getNonVaccinesBatchesByStatus($status) {
        $current_date = new DateTime(date("Y-m-d"));
        $date_curr = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $str_sql = $this->_em_read->createQueryBuilder()->select('DISTINCT sbw.pkId,
                sb.number,
                sb.expiryDate,
                sbw.status,
                sbw.quantity AS BatchQty,
                ips.itemName,
                iu.itemUnitName,
                ips.numberOfDoses as description,
                sd.temporary')
                ->from('StockDetail', 'sd')
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where('sbw.warehouse =' . $this->_identity->getWarehouseId())
                ->andWhere('sd.temporary = 0');

        if (!empty($this->form_values['item_pack_size_id'])) {
            $str_sql->andWhere("sip.itemPackSize = " . $this->form_values['item_pack_size_id']);
        }
        if (!empty($this->form_values['number'])) {
            $str_sql->andWhere("sb.number LIKE '%" . $this->form_values['number'] . "%'");
        }
        if (!empty($this->form_values['transaction_refernece'])) {
            $str_sql->andWhere("sm.transactionReference LIKE '%" . $this->form_values['transaction_refernece'] . "%'");
        }
        if (!empty($this->form_values['expired_before'])) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') BETWEEN '" . $date_curr . "' AND '" . $this->form_values['expired_before'] . "'");
        } else if (!empty($this->form_values['expired_after'])) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'");
        }

        if ($status == parent::PRIORITY1) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') BETWEEN '" . $date_curr . "' AND '" . $after3month . "'");
        } else if ($status == parent::PRIORITY2) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') BETWEEN '" . $after3month . "' AND '" . $afteryear . "'");
        } else if ($status == parent::PRIORITY3) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') > '" . $afteryear . "'");
        } else if ($status == parent::TOTAL) {
            $str_sql->andWhere("sbw.status IN ('STACKED','RUNNING')");
        } else if ($status == parent::EXPIRED) {
            $date_curr = date('Y-m-d');
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') < '$date_curr'");
            $str_sql->andWhere("sbw.quantity > 0");
        } else if (!empty($status)) {
            $str_sql->andWhere("sbw.status = '" . $status . "'");
        } else {
            $status = parent::TOTAL;
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Search Stock Batch
     *  
     * @return type
     */
    public function searchStockBatch() {

        if (isset($this->form_values['searchby'])) {
            $search_by = $this->form_values['searchby'];
        }
        if (isset($this->form_values['searchinput'])) {
            $search_input = $this->form_values['searchinput'];
        }

        if (!empty($search_by) || !empty($search_input)) {
            switch ($search_by) {
                case 'number':
                    $this->form_values['number'] = $search_input;
                    break;
                case 'transaction_refernece':
                    $this->form_values['transaction_refernece'] = $search_input;
                    break;
                case 'expired_before':
                    $this->form_values['expired_before'] = App_Controller_Functions::dateToDbFormat($search_input);
                    break;
                case 'expired_after':
                    $this->form_values['expired_after'] = App_Controller_Functions::dateToDbFormat($search_input);
                    break;
                default :
                    break;
            }
        }

        $item_id = $this->form_values['item_pack_size_id'];
        $items = $this->_em->getRepository("ItemPackSizes")->find($item_id);
        $item_cat = 0;
        if (count($items) > 0) {
            $item_cat = $items->getItemCategory()->getPkId();
        }

        if ($item_cat == 1) {
            if (!empty($this->form_values['status'])) {
                switch ($this->form_values['status']) {
                    case 1:
                        $status = parent::PRIORITY1;
                        $result = $this->getPriority1Batches();
                        break;
                    case 2:
                        $status = parent::PRIORITY2;
                        $result = $this->getPriority2Batches();
                        break;
                    case 3:
                        $status = parent::PRIORITY3;
                        $result = $this->getPriority3Batches();
                        break;
                    case 4:
                        $result = $this->getBatchesByStatus(parent::FINISHED);
                        break;
                    case 5:
                        $result = $this->getBatchesByStatus(parent::EXPIRED);
                        break;
                    case 6:
                        $result = $this->getTotalPriorityBatches();
                        break;
                    case 7:
                        $result = $this->getUnusableBatches();
                        break;
                    default:

                        break;
                }
            } else {
                $result = $this->getTotalPriorityBatches();
            }
        } else {
            if (!empty($this->form_values['status'])) {
                switch ($this->form_values['status']) {
                    case 1:
                        $status = parent::PRIORITY1;
                        $result = $this->getNonVaccinesPriority1Batches();
                        break;
                    case 2:
                        $status = parent::PRIORITY2;
                        $result = $this->getNonVaccinesPriority2Batches();
                        break;
                    case 3:
                        $status = parent::PRIORITY3;
                        $result = $this->getNonVaccinesPriority3Batches();
                        break;
                    case 4:
                        $result = $this->getNonVaccinesBatchesByStatus(parent::FINISHED);
                        break;
                    case 5:
                        $result = $this->getNonVaccinesBatchesByStatus(parent::EXPIRED);
                        break;
                    case 6:
                        $result = $this->getDryStoreTotalPriorityBatches();
                        break;
                    default:
                        break;
                }
            } else {
                $result = $this->getDryStoreTotalPriorityBatches();
            }
        }

        return $result;
    }

    /**
     * Get Non Vaccine Batch Detail
     * 
     * @return type
     */
    public function getNonVaccineBatchDetail() {
        $item_id = $this->form_values['item_pack_size_id'];
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $str_sql = "SELECT
        COUNT(*) AS cnt,
        A.number,
        A.itemName,
        A.description,
        A.itemUnitName,
        SUM(A.BatchQty) AS BatchQty,
        A.`status`
    FROM
        (
                SELECT
                        stock_batch_warehouses.pk_id,
                        stock_batch.number,
                        item_pack_sizes.item_name AS itemName,
                        item_pack_sizes.number_of_doses AS description,
                        item_units.item_unit_name AS itemUnitName,
                        Sum(placements.quantity) AS BatchQty,
                        'Priority 1' AS `status`,
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)
                AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$today' AND '$after3month'
                AND item_pack_sizes.pk_id = $item_id
                GROUP BY
                placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                UNION
                        SELECT
                                stock_batch_warehouses.pk_id,
                                stock_batch.number,
                                item_pack_sizes.item_name AS itemName,
                                item_pack_sizes.number_of_doses AS description,
                                item_units.item_unit_name AS itemUnitName,
                                Sum(placements.quantity) AS BatchQty,
                                'Priority 2' AS `status`,
                                item_pack_sizes.list_rank,
                                stock_batch.expiry_date
                        FROM
                                stock_batch
                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                        WHERE
                                stock_batch_warehouses.warehouse_id = $wh_id
                        AND item_pack_sizes.item_category_id IN (2,3)                     
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$after3month'
                        AND '$afteryear'
                        AND item_pack_sizes.pk_id = $item_id
                        GROUP BY
                                placements.stock_batch_warehouse_id
                        HAVING
                                BatchQty > 0
                        UNION
                                SELECT
                                        stock_batch_warehouses.pk_id,
                                        stock_batch.number,
                                        item_pack_sizes.item_name AS itemName,
                                        item_pack_sizes.number_of_doses AS description,
                                        item_units.item_unit_name AS itemUnitName,
                                        Sum(placements.quantity) AS BatchQty,
                                        'Priority 3' AS `status`,
                                        item_pack_sizes.list_rank,
                                        stock_batch.expiry_date
                                FROM
                                        stock_batch
                                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                                WHERE
                                        stock_batch_warehouses.warehouse_id = $wh_id
                                AND item_pack_sizes.item_category_id IN (2,3)                             
                                AND DATE_FORMAT(
                                        stock_batch.expiry_date,
                                        '%Y-%m-%d'
                                ) > '$afteryear'
                                AND item_pack_sizes.pk_id = $item_id
                                GROUP BY
                                        placements.stock_batch_warehouse_id
                                HAVING
                                        BatchQty > 0
        ) A
    GROUP BY
        A.`status`
    ORDER BY
        A.`status`";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Batch Detail
     * 
     * @return type
     */
    public function getBatchDetail() {
        $item_id = $this->form_values['item_pack_size_id'];
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $str_sql = "SELECT
        COUNT(*) AS cnt,
        A.number,
        A.itemName,
        A.description,
        A.itemUnitName,
        SUM(A.BatchQty) AS BatchQty,
        A.`status`
    FROM
        (
                SELECT
                        stock_batch_warehouses.pk_id,
                        stock_batch.number,
                        item_pack_sizes.item_name AS itemName,
                        item_pack_sizes.number_of_doses AS description,
                        item_units.item_unit_name AS itemUnitName,
                        Sum(placements.quantity) AS BatchQty,
                        'Priority 1' AS `status`,
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND (
                        placements.vvm_stage = 2
                        OR (
                                placements.vvm_stage = 1
                                AND DATE_FORMAT(
                                        stock_batch.expiry_date,
                                        '%Y-%m-%d'
                                ) BETWEEN '$today'
                                AND '$after3month'
                        )
                ) AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$today'
                AND item_pack_sizes.pk_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                UNION
                        SELECT
                                stock_batch_warehouses.pk_id,
                                stock_batch.number,
                                item_pack_sizes.item_name AS itemName,
                                item_pack_sizes.number_of_doses AS description,
                                item_units.item_unit_name AS itemUnitName,
                                Sum(placements.quantity) AS BatchQty,
                                'Priority 2' AS `status`,
                                item_pack_sizes.list_rank,
                                stock_batch.expiry_date
                        FROM
                                stock_batch
                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                        WHERE
                                stock_batch_warehouses.warehouse_id = $wh_id
                        AND item_pack_sizes.item_category_id = 1
                        AND placements.vvm_stage = 1
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$after3month'
                        AND '$afteryear'
                        AND item_pack_sizes.pk_id = $item_id
                        GROUP BY
                                placements.stock_batch_warehouse_id
                        HAVING
                                BatchQty > 0
                        UNION
                                SELECT
                                        stock_batch_warehouses.pk_id,
                                        stock_batch.number,
                                        item_pack_sizes.item_name AS itemName,
                                        item_pack_sizes.number_of_doses AS description,
                                        item_units.item_unit_name AS itemUnitName,
                                        Sum(placements.quantity) AS BatchQty,
                                        'Priority 3' AS `status`,
                                        item_pack_sizes.list_rank,
                                        stock_batch.expiry_date
                                FROM
                                        stock_batch
                                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                                WHERE
                                        stock_batch_warehouses.warehouse_id = $wh_id
                                AND item_pack_sizes.item_category_id = 1
                                AND placements.vvm_stage = 1
                                AND DATE_FORMAT(
                                        stock_batch.expiry_date,
                                        '%Y-%m-%d'
                                ) > '$afteryear'
                                AND item_pack_sizes.pk_id = $item_id
                                GROUP BY
                                        placements.stock_batch_warehouse_id
                                HAVING
                                        BatchQty > 0
        ) A
    GROUP BY
        A.`status`
    ORDER BY
        A.`status`";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * 
     * @return boolean
     */
    public function changeStatus() {
        $stock = $this->_table->find($this->form_values['pk_id']);
        if (count($stock) >= 1) {
            $stock->setStatus($this->form_values['status']);
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $stock->setModifiedBy($created_by);
            $stock->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock);
            $this->_em->flush();
            return true;
        }
    }

    /**
     * Batch Summary
     * 
     * @return type
     */
    public function batchSummary() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        ips.pkId as item_id,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory = 1")
                
                ->andWhere("sbw.warehouse = " . $wh_id)
                ->andWhere("sb.expiryDate >= '" . date("Y-m-d") . "'");
        
       

        if ($wh_id == 159) {
            $str_sql->andWhere("ips.pkId NOT IN(35,38)");
        }

        $str_sql->groupBy("ips.pkId")
                ->orderBy("ips.listRank");



        return $str_sql->getQuery()->getResult();
    }
    
    /**
     * Batch Summary
     * 
     * @return type
     */
    public function batchSummaryAlert() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        ips.pkId as item_id,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory = 1")
                ->andWhere("ips.pkId IN (6,7,8,9,12,26,40,43,46)")
                ->andWhere("sbw.warehouse = " . $wh_id)
                ->andWhere("sb.expiryDate >= '" . date("Y-m-d") . "'");
        
       

        if ($wh_id == 159) {
            $str_sql->andWhere("ips.pkId NOT IN(35,38)");
        }

        $str_sql->groupBy("ips.pkId")
                ->orderBy("ips.listRank");



        return $str_sql->getQuery()->getResult();
    }

    /**
     * Expired Batch Summary
     * 
     * @return type
     */
    public function expiredBatchSummary() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory = 1")
                ->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId())
                ->andWhere("sb.expiryDate < '" . date("Y-m-d") . "'")
                ->groupBy("ips.pkId")
                ->having("Vials > 0")
                ->orderBy("ips.listRank");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Non vaccine stock batch summary
     * 
     * @return type
     */
    public function nonVaccineBatchSummary() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory in (2,3)")
                ->andWhere("sbw.warehouse = " . $wh_id)
                ->andWhere("sb.expiryDate >= '" . date("Y-m-d") . "'");

        if ($wh_id == 159) {
            $str_sql->andWhere("ips.pkId NOT IN(35,38)");
        }

        $str_sql->groupBy("ips.pkId")
                ->orderBy("ips.listRank");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Non vaccine expired stock batch summary
     * 
     * @return type
     */
    public function nonVaccineExpiredBatchSummary() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory IN (2,3)")
                ->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId())
                ->andWhere("sb.expiryDate < '" . date("Y-m-d") . "'")
                ->groupBy("ips.pkId")
                ->having("Vials > 0")
                ->orderBy("ips.listRank");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Batch Summary 2
     * 
     * @return type
     */
    public function batchSummary2() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("
                         i.description as itemName,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join("ips.item", 'i')
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory = 1")
                ->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId());

        $str_sql->groupBy("ips.pkId")
                ->groupBy("ips.item")
                ->orderBy("ips.listRank");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Batch Summary Before
     * 
     * @return type
     */
    public function batchSummaryBefore() {

        $sql = 'SELECT
                        items.description AS product,
                        Sum(
                                batch_summary_before_adjust.quantity
                        ) AS quantity,
                        Sum(
                                batch_summary_before_adjust.quantity
                        ) * item_pack_sizes.number_of_doses AS quantityD
                FROM
                        batch_summary_before_adjust
                INNER JOIN item_pack_sizes ON batch_summary_before_adjust.product_id = item_pack_sizes.pk_id
                INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
                GROUP BY
                        items.pk_id';

        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        $data = $row->fetchAll();

        foreach ($data as $r) {
            $result[$r['product']] = array(
                'quantity' => $r['quantity'],
                'quantityD' => $r['quantityD']
            );
        }

        return $result;
    }

    /**
     * Stakeholder Product Summary
     * 
     * @return type
     */
    public function stakeholderProductSummary() {
        // Usable produts only
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        s.stakeholderName")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sips')
                ->join('sips.itemPackSize', 'ips')
                ->innerJoin("sips.stakeholder", "s")
                ->where("s.stakeholderType = 3")
                ->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId())
                ->andWhere("sb.expiryDate >= '" . date("Y-m-d") . "'")
                ->groupBy("ips.itemName,s.stakeholderName")
                ->orderBy("ips.listRank", "ASC")
                ->having("Vials > 0");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Stakeholder Expired Product Summary
     * 
     * @return type
     */
    public function stakeholderExpiredProductSummary() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        s.stakeholderName")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sips')
                ->join('sips.itemPackSize', 'ips')
                ->innerJoin("sips.stakeholder", "s")
                ->where("s.stakeholderType = 3")
                ->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId())
                // Expired produts only
                ->andWhere("sb.expiryDate < '" . date("Y-m-d") . "'")
                ->groupBy("ips.itemName,s.stakeholderName")
                ->orderBy("ips.listRank", "ASC")
                ->having("Vials > 0");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Stakeholder Product Total
     * 
     * @return type
     */
    public function stakeholderProductTotal() {

        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        s.stakeholderName")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sips')
                ->join('sips.itemPackSize', 'ips')
                ->innerJoin("sips.stakeholder", "s")
                ->where("s.stakeholderType = 3")
                ->andWhere("sbw.warehouse = " . $wh_id)
                ->groupBy("ips.itemName")
                ->orderBy("ips.listRank", "ASC")
                ->having("Vials > 0");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Priority Product Total
     * 
     * @return type
     */
    public function priorityProductTotal() {
        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        }

        $str_sql = "SELECT
        DATE_FORMAT(
                stock_batch.expiry_date,
                '%M, %Y'
        ) AS expiry_date,
        stock_batch.number,
        item_pack_sizes.item_name,
        SUM(placements.quantity) AS quantity,
        SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
        IFNULL(placements.vvm_stage, 1) AS vvm
    FROM
        stock_batch
    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
    LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
WHERE
        stock_batch_warehouses.warehouse_id = $wh_id
    GROUP BY
        item_pack_sizes.pk_id

    ORDER BY
        item_pack_sizes.list_rank,
        stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Ds Batch Product Total
     * 
     * @return type
     */
    public function dsBatchProductTotal() {

        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        }
        $str_sql = "
            SELECT
                item_pack_sizes.item_name AS 'Product',
                Sum(stock_batch_warehouses.quantity) AS 'Quantity'
            FROM
               stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN item_units i2_ ON item_pack_sizes.item_unit_id = i2_.pk_id
            WHERE
                item_pack_sizes.item_category_id IN (2, 3)
                AND stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.pk_id NOT IN (35,38)
            GROUP BY
                item_pack_sizes.pk_id
            ORDER BY
                item_pack_sizes.list_rank ASC";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Ds Stakeholder Product Total
     * 
     * @return type
     */
    public function dsStakeholderProductTotal() {

        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = "
            SELECT
                item_pack_sizes.item_name AS Product,
                SUM(stock_batch_warehouses.quantity) AS 'Quantity'
            FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
         INNER JOIN stakeholders  ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id

            WHERE
                stakeholders.stakeholder_type_id = 3
                AND stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2, 3)
            GROUP BY
                item_pack_sizes.pk_id
            ORDER BY
                item_pack_sizes.list_rank ASC";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Ds Placement Product Total
     * 
     * @return type
     */
    public function dsPlacementProductTotal() {

        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = "
            SELECT
                item_pack_sizes.item_name as 'Product',
                SUM(placements.quantity) AS 'Quantity'
            FROM
                stock_batch
            INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)
            GROUP BY
                item_pack_sizes.pk_id
            ORDER BY
                item_pack_sizes.list_rank";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Running Batches
     * 
     * @return boolean
     */
    public function getAllRunningBatches() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("Distinct sb.number,
                        sbw.pkId,
                        sb.expiryDate,
                        sbw.quantity")
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sbw.quantity > 0 ")
                ->andWhere("sd.temporary=0")
                ->andWhere("sip.itemPackSize = " . $this->form_values['item_pack_size_id']);

        if (!empty($this->form_values['transaction_date'])) {
            $str_sql->andWhere("sb.expiryDate >= '" . App_Controller_Functions::dateToDbFormat($this->form_values['transaction_date']) . "' ");
        }

        $str_sql->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId())
                ->orderBy("sbw.quantity", "DESC");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Priority 1 Batches
     * 
     * @return type
     */
    public function getPriority1Batches() {

        $wh_id = $this->_identity->getWarehouseId();
        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        } else {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '$today'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 1' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id = 1
        AND (
                placements.vvm_stage = 2
                OR (
                        placements.vvm_stage = 1
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$today'
                        AND '$after3month'
                )
        ) $wr
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                BatchQty > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Priority 1 Batches
     * 
     * @return type
     */
    public function getNonVaccinesPriority1Batches() {

        $wh_id = $this->_identity->getWarehouseId();
        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        } else {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '$today'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 1' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id in (2,3)
        AND DATE_FORMAT(stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$today'
                        AND '$after3month' $wr
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                BatchQty > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Priority 2 Batches
     * 
     * @return type
     */
    public function getPriority2Batches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 2' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Priority 2 Batches
     * 
     * @return type
     */
    public function getNonVaccinesPriority2Batches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 2' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Priority 3 Batches
     * 
     * @return type
     */
    public function getPriority3Batches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month12 = $current_date->modify("+12 months");
        $afteryear = $month12->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 3' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear' $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Priority 3 Batches
     * 
     * @return type
     */
    public function getNonVaccinesPriority3Batches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month12 = $current_date->modify("+12 months");
        $afteryear = $month12->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 3' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)             
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear' $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Unusable Batches
     * 
     * @return type
     */
    public function getUnusableBatches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Unusable' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage IN (3,4)
                $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Total Priority Batches
     * 
     * Uses in Batch Management
     * @return batch list
     */
    public function getTotalPriorityBatches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        } else {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '$today'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "(
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 1' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id = 1
        AND (
                placements.vvm_stage = 2
                OR (
                        placements.vvm_stage = 1
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$today'
                        AND '$after3month'
                )
        ) $wr
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING BatchQty > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 2' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 3' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear' $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Total Priority Batches
     * 
     * Uses in Batch Management
     * @return batch list
     */
    public function getDryStoreTotalPriorityBatches() {

        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $where = array();
        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "item_pack_sizes.pk_id = " . $this->form_values['item_pack_size_id'];
        }
        if (!empty($this->form_values['number'])) {
            $where[] = "stock_batch.number LIKE '" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['expired_before'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '" . $today . "' AND  '" . $this->form_values['expired_before'] . "'";
        } else if (!empty($this->form_values['expired_after'])) {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '" . $this->form_values['expired_after'] . "'";
        } else {
            $where[] = "DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') >= '$today'";
        }

        if (count($where) > 0) {
            $wr = " AND " . implode(" AND ", $where);
        }

        $str_sql = "(
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 1' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id IN (2,3)
        AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$today' AND '$after3month' $wr
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING BatchQty > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 2' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                Sum(placements.quantity) AS BatchQty,
                stock_batch_warehouses.pk_id AS pkId,
                item_pack_sizes.item_name AS itemName,
                'Priority 3' AS `status`,
                item_units.item_unit_name AS itemUnitName,
                item_pack_sizes.number_of_doses AS description
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)                
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear' $wr
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING BatchQty > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Priority Batches
     * 
     * @return type
     */
    public function getAllPriorityBatches() {

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $todaymin1 = $current_date->modify("yesterday");
        $todaymin1date = $todaymin1->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $item_id = $this->form_values['item_pack_size_id'];
        $wh_id = $this->_identity->getWarehouseId();
        $trans_date = App_Controller_Functions::dateToDbFormat($this->form_values['transaction_date']);
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 50){
             $str_sql = "SELECT A.expiryDate,
            A.number,
            A.quantity,
            A.pkId,
            A.priority,
            A.stakeholder_name
            from
            ( (
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'P1' AS priority,
                 stakeholders.stakeholder_name
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
         INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
        stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id = 1
        AND (
                placements.vvm_stage = 2
                OR (
                        placements.vvm_stage = 1
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$today'
                        AND '$after3month'
                )
        )
        AND DATE_FORMAT(
                stock_batch.expiry_date,
                '%Y-%m-%d'
        ) >= '$trans_date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P2' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                 INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) >= '$trans_date'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
      UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P3' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                 INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear'
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) >= '$trans_date'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )  UNION
    (
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'Expired' AS priority,
                 stakeholders.stakeholder_name
        FROM
         stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
         INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id = 1
        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$trans_date'
                        AND '$todaymin1date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    ) ) A 
        HAVING A.quantity > 0 
        ORDER BY A.priority,A.quantity
        LIMIT 1";
       }   else { 
        $str_sql = "(
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'P1' AS priority,
                 stakeholders.stakeholder_name
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
         INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
        stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id = 1
        AND (
                placements.vvm_stage = 2
                OR (
                        placements.vvm_stage = 1
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$today'
                        AND '$after3month'
                )
        )
        AND DATE_FORMAT(
                stock_batch.expiry_date,
                '%Y-%m-%d'
        ) >= '$trans_date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P2' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                 INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) >= '$trans_date'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P3' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                 INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear'
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) >= '$trans_date'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )  UNION
    (
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'Expired' AS priority,
                 stakeholders.stakeholder_name
        FROM
         stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
         INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id = 1
        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$trans_date'
                        AND '$todaymin1date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )";
       }
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Priority Batches
     * 
     * @return type
     */
    public function getDryStoreAllPriorityBatches() {

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $todaymin1 = $current_date->modify("yesterday");
        $todaymin1date = $todaymin1->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $item_id = $this->form_values['item_pack_size_id'];
        $wh_id = $this->_identity->getWarehouseId();
        $trans_date = App_Controller_Functions::dateToDbFormat($this->form_values['transaction_date']);
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 50){
          $str_sql = "SELECT
	A.expiryDate,
	A.number,
	A.quantity,
	A.pkId,
	A.priority,
	A.stakeholder_name
        FROM
	( (
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'P1' AS priority,
                stakeholders.stakeholder_name
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
        stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id IN (2,3)
        AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$today' AND '$after3month'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
        placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P2' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)               
                AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$after3month' AND '$afteryear'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P3' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)               
                AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') > '$afteryear'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )  UNION
    (
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'Expired' AS priority,
                 stakeholders.stakeholder_name
        FROM
         stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id IN (2,3)
        AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$trans_date' AND '$todaymin1date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    ) ) A
HAVING
	A.quantity > 0
ORDER BY
	A.priority,
	A.quantity
LIMIT 1";   
        }   else {  
        $str_sql = "(
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'P1' AS priority,
                stakeholders.stakeholder_name
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
        stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id IN (2,3)
        AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$today' AND '$after3month'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
        placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P2' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)               
                AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$after3month' AND '$afteryear'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P3' AS priority,
                         stakeholders.stakeholder_name
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id IN (2,3)               
                AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') > '$afteryear'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )  UNION
    (
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'Expired' AS priority,
                 stakeholders.stakeholder_name
        FROM
         stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholders.pk_id = stakeholder_item_pack_sizes.stakeholder_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id
        AND item_pack_sizes.item_category_id IN (2,3)
        AND DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') BETWEEN '$trans_date' AND '$todaymin1date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
        )";}

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Batches By Item Id
     * 
     * @uses API & Application
     * @return boolean
     */
    public function getAllBatchesByItemId() {
        $type = $this->form_values['adj_type'];
        $type_sign = '';
        if (!empty($type)) {
            $adj_type = $this->_em->getRepository("TransactionTypes")->find($type);
            $type_sign = $adj_type->getNature();
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sbw.pkId,sb.number,
                        sb.expiryDate,
                        sbw.quantity,s.stakeholderName as stakeholder_name")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.stakeholder', 's')
                ->where('sip.itemPackSize =' . $this->form_values['item_id'])
                ->andWhere('sbw.warehouse =' . $this->_identity->getWarehouseId());

        if ($type == 9) {
            $str_sql->andWhere("DATE_FORMAT(sb.expiryDate,'%Y-%m-%d') < '" . date("Y-m-d") . "'");
        }
        if ($type_sign == '-') {
            $str_sql->having("sbw.quantity > 0");
        }
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Batches By Item Id
     * 
     * @uses API & Application
     * @return boolean
     */
    public function getSimilarBatches() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.number")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where('sip.itemPackSize =' . $this->form_values['item_id'])
                ->andWhere('sbw.warehouse =' . $this->_identity->getWarehouseId())
                ->andWhere("sb.number LIKE '%" . $this->form_values['batch'] . "%'");
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Batches By Item
     * 
     * @return boolean
     */
    public function getBatchesByItem() {
        $batches_arr = array();
        $str_sql = $this->_em->createQueryBuilder()
                ->select("COUNT(sb.number) cnt, sb.number,
                        GROUP_CONCAT(sbw.pkId) batches")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->groupBy('sb.number, sbw.warehouse, pi.pkId')
                ->having("cnt > 1");
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            $batches_arr = array();
            foreach ($row as $batches) {
                $batches_arr[] = $batches['batches'];
            }

            $batch_list = implode(",", $batches_arr);
            $str_sql2 = $this->_em->createQueryBuilder()
                    ->select("sbw")
                    ->from('StockBatchWarehouses', 'sbw')
                    ->join('sbw.stockBatch', 'sb')
                    ->join('sb.packInfo', 'pi')
                    ->join('sbw.warehouse', 'w')
                    ->where("sbw.pkId IN ($batch_list)")
                    ->orderBy("sb.number, w.pkId");
            $row2 = $str_sql2->getQuery()->getResult();

            if (count($row2) > 0) {
                return $row2;
            }
        }

        return false;
    }

    /**
     * Get Batch Expiry
     * 
     * @return boolean
     */
    public function getBatchExpiryByNumber() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DATE_FORMAT(sb.expiryDate,'%d/%m/%Y') expiryDate, sbw.pkId")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize ', 'ips')
                ->where("ips.pkId = " . $this->form_values['item_id'])
                ->andWhere("sb.number = '" . $this->form_values['number'] . "'")
                ->andWhere("sbw.warehouse = '" . $this->_identity->getWarehouseId() . "'");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return array('expiry' => $row[0]['expiryDate'], 'batch_id' => $row[0]['pkId']);
        } else {
            return false;
        }
    }

    /**
     * Get Batch Expiry
     * 
     * @return boolean
     */
    public function getBatchExpiry() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.expiryDate,
                        sbw.quantity,
                        ips.pkId as itemCategory")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize ', 'ips')
                ->join("ips.itemCategory", "ic")
                ->where("sbw.pkId = " . $this->form_values['pk_id']);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return array(
                'date' => $row[0]['expiryDate'],
                'qty' => $row[0]['quantity'],
                'cat' => $row[0]['itemCategory']
            );
        } else {
            return false;
        }
    }

    /**
     * Get Batch Available Balance Expiry
     * 
     * @return boolean
     */
    public function getBatchAvailableBalanceExpiry2() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DATE_FORMAT(sb.expiryDate,'%d/%m/%Y') as expiryDate,
                        SUM(sd.quantity) as quantity,
                        ips.pkId as itemCategory")
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sd.stockMaster", "sm")
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize ', 'ips')
                ->join("ips.itemCategory", "ic")
                ->where("sbw.pkId = " . $this->form_values['pk_id'])
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') <= '" . $this->form_values['trans_date'] . "'");
        $row = $str_sql->getQuery()->getResult();
        $current_qty = $this->getBatchCB();
        $pipeline_qty = $this->getBatchPipelineQty();

        if (!empty($row) && count($row) > 0) {
            if ($row[0]['quantity'] > $current_qty) {
                $quantity = $current_qty;
            } else {
                $quantity = $row[0]['quantity'];
            }

            $quantity = $quantity - $pipeline_qty;

            return array(
                'date' => $row[0]['expiryDate'],
                'qty' => ($quantity > 0) ? number_format($quantity) : 0,
                'cat' => $row[0]['itemCategory']
            );
        } else {
            return false;
        }
    }

    public function getBatchAvailableBalanceExpiry() {
        $sql = "SELECT
	MIN(A.Bal) quantity,
	A.expiry_date expiryDate,
	A.item_category_id itemCategory
FROM
	(
		SELECT DISTINCT
			BatchBalance (
				stock_detail.stock_batch_warehouse_id,
				stock_master.transaction_date
			) AS Bal,
			DATE_FORMAT(
				stock_batch.expiry_date,
				'%d/%m/%Y'
			) AS expiry_date,
			item_pack_sizes.item_category_id
		FROM
			stock_detail
		INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
		INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			DATE_FORMAT(
				stock_master.transaction_date,
				'%Y-%m-%d'
			) >= '" . $this->form_values['trans_date'] . "'
		AND stock_detail.stock_batch_warehouse_id = " . $this->form_values['pk_id'] . "
	) A";
        $str_sql = $this->_em->getConnection()->prepare($sql);
        $str_sql->execute();
        $row = $str_sql->fetchAll();
        $quantity = $this->getBatchCBByDate();
        //$pipeline_qty = $this->getBatchPipelineQty();
        if (!empty($row[0]['quantity'])) {
            if ($quantity > $row[0]['quantity']) {
                $quantity = $row[0]['quantity'];
            }

            return array(
                'date' => $row[0]['expiryDate'],
                'qty' => ($quantity > 0) ? number_format($quantity) : 0,
                'cat' => $row[0]['itemCategory']
            );
        } else {
            return $this->getBatchAvailableBalanceExpiry2();
        }
    }

    /**
     * Get Batch CB
     * 
     * @return int
     */
    public function getBatchCB() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.quantity")
                ->from("StockBatchWarehouses", "sb")
                ->where("sb.pkId = " . $this->form_values['pk_id']);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['quantity'];
        } else {
            return 0;
        }
    }

    /**
     * Get Batch Pipeline Qty
     * 
     * @return int
     */
    public function getBatchPipelineQty() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("SUM(pc.quantity) as qty")
                ->from("PipelineConsignments", "pc")
                ->where("pc.stockBatchWarehouse = " . $this->form_values['pk_id'])
                ->andWhere("pc.transactionType = 2")
                ->andWhere("pc.status != 'Received'");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['qty'];
        } else {
            return 0;
        }
    }

    /**
     * Edit Batch No
     * 
     * @return type
     */
    public function editBatchNo() {
        $stock_detail = new Model_StockDetail();
        $stock_detail->form_values['pk_id'] = $this->form_values['pk_id'];
        $quantity_and_batch = $stock_detail->getQuantityById($this->form_values['pk_id']);

        if ($quantity_and_batch['number'] != $this->form_values['number']) {
            $data = array(
                'number' => $quantity_and_batch['number'],
                'batch_id' => $quantity_and_batch['stock_batch_id'],
                'item_id' => $quantity_and_batch['item_pack_size_id'],
            );
            // check batch is new or old
            $batch_id = $this->checkNewBatch($data);

            if ($batch_id === 0) {
                // if batch is new then  new batch
                $data_pre = array(
                    'number' => $this->form_values['number'],
                    'item_id' => $quantity_and_batch['item_pack_size_id'],
                );
                $batch_id_previous = $this->checkBatch($data_pre);
                if ($batch_id_previous === 0) {
                    $this->form_values['pk_id'] = $quantity_and_batch['stock_batch_id'];
                    return $this->updateName();
                } else {
                    $stock_detail->updateDetail($quantity_and_batch['stock_detail'], $batch_id_previous);
                    $this->adjustQuantityByWarehouse($batch_id_previous);
                    $this->batch_id = $quantity_and_batch['stock_batch_id'];
                    return $this->updateWarehouseId();
                }
            } else {
                $data_array = array(
                    'number' => $this->form_values['number'],
                    'item_id' => $quantity_and_batch['item_pack_size_id'],
                );
                $batch_id_pre = $this->checkBatch($data_array);
                if ($batch_id_pre === 0) {

                    $data_pr = array(
                        'number' => $this->form_values['number'],
                        'item_id' => $quantity_and_batch['item_pack_size_id'],
                        'expiry_date' => $quantity_and_batch['expiryDate'],
                        'quantity' => $quantity_and_batch['quantity'],
                        'vvm_type_id' => $quantity_and_batch['vvmTypeId'],
                        'unit_price' => $quantity_and_batch['unitPrice'],
                        'manufacturer_id' => $quantity_and_batch['stakeholderItemPackSizeId'],
                    );
                    $batch_id_new = $this->createBatch($data_pr);
                    $stock_detail->updateDetail($quantity_and_batch['stock_detail'], $batch_id_new);
                    $this->adjustQuantityByWarehouse($quantity_and_batch['stock_batch_id']);
                } else {
                    $stock_detail->updateDetail($quantity_and_batch['stock_detail'], $batch_id_pre);
                    $this->adjustQuantityByWarehouse($quantity_and_batch['stock_batch_id']);
                    $this->adjustQuantityByWarehouse($batch_id_pre);
                }
            }
        }
    }

    /**
     * Update Name
     * 
     * @return type
     */
    public function updateName() {
        $stock = $this->_table->find($this->form_values['pk_id']);
        $stock->setNumber(strtoupper($this->form_values['number']));
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock->setModifiedBy($created_by);
        $stock->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock);
        return $this->_em->flush();
    }

    /**
     * Update Warehouse Id
     * 
     */
    public function updateWarehouseId() {

        $querypro = "Update stock_batch_warehouses set warehouse_id='0' where stock_batch.pk_id=$this->batch_id";
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
    }

    /**
     * Create Batch
     * 
     * @param type $array
     * @return boolean
     */
    public function createBatch($array) {


        $wh_id = $this->_identity->getWarehouseId();
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $pack_info_id = $this->_em->getRepository('PackInfo')->findOneBy(array("stakeholderItemPackSize" => $array['manufacturer_id']));

        $packInfo = '';
        if (count($pack_info_id) > 0) {
            $packInfo = $pack_info_id->getPkId();
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("sb.pkId")
                ->from('StockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sips')
                ->where("sb.packInfo = '" . $packInfo . "'")
                ->andWhere("sb.number = '" . $array['number'] . "'")
                ->andWhere("sips.itemPackSize = '" . $array['item_id'] . "'");
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            $master_batch_id = $row[0]['pkId'];
          $stock_batch1 = $this->_em->getRepository('StockBatch')->find($row[0]['pkId']);
          $stock_batch1->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['expiry_date'])));
          $this->_em->persist($stock_batch1);
          $this->_em->flush();
        
        } else {
            $stock_batch = new StockBatch();
            $stock_batch->setNumber(strtoupper($array['number']));
            $stock_batch->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['expiry_date'])));
            if (!empty($array['production_date'])) {
                $stock_batch->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['production_date'])));
            }
            if (!empty($array['vvm_type_id'])) {
                $vvm_type_id = $this->_em->getRepository('VvmTypes')->find($array['vvm_type_id']);
                $stock_batch->setVvmType($vvm_type_id);
            }
            $stock_batch->setUnitPrice($array['unit_price']);
            if (!empty($array['manufacturer_id'])) {
                $stakeholder_item_pack_size = $this->_em->getRepository('StakeholderItemPackSizes')->find($array['manufacturer_id']);
                if ($stakeholder_item_pack_size->getItemPackSize()->getPkId() == $array['item_id']) {
                    $pack_info_id = $this->_em->getRepository('PackInfo')->findOneBy(array("stakeholderItemPackSize" => $stakeholder_item_pack_size->getPkId(), "packagingLevel" => '140'));
                    $stock_batch->setPackInfo($pack_info_id);
                } else {
                    $check_sips = $this->_em->getRepository('StakeholderItemPackSizes')->findOneBy(array("stakeholder" => $stakeholder_item_pack_size->getStakeholder()->getPkId(), "itemPackSize" => $array['item_id']));
                    if (count($check_sips) > 0) {
                        $pack_info_id = $this->_em->getRepository('PackInfo')->findOneBy(array("stakeholderItemPackSize" => $check_sips->getPkId(), "packagingLevel" => '140'));
                        $stock_batch->setPackInfo($pack_info_id);
                    } else {
                        $add_sips = new StakeholderItemPackSizes();
                        $add_sips->setStakeholder($stakeholder_item_pack_size->getStakeholder());
                        $item_pack_size_id = $this->_em->getRepository('ItemPackSizes')->find($array['item_id']);
                        $add_sips->setItemPackSize($item_pack_size_id);
                        $add_sips->setModifiedBy($created_by);
                        $add_sips->setModifiedDate(App_Tools_Time::now());
                        $add_sips->setCreatedBy($created_by);
                        $add_sips->setCreatedDate(App_Tools_Time::now());
                        $this->_em->persist($add_sips);
                        $this->_em->flush();
                        $sips_id = $add_sips->getPkId();
                        $pack_info_id_add = $this->_em->getRepository('PackInfo')->findOneBy(array("stakeholderItemPackSize" => $stakeholder_item_pack_size->getPkId(), "packagingLevel" => '140'));

                        $pack_info = new PackInfo();
                        $stakholder_item_pack_id = $this->_em->getRepository('StakeholderItemPackSizes')->find($sips_id);
                        $pack_info->setStakeholderItemPackSize($stakholder_item_pack_id);
                        $pack_info->setQuantityPerPack($pack_info_id_add->getQuantityPerPack());
                        $pack_info->setStatus($pack_info_id_add->getStatus());
                        $pack_info->setListRank($pack_info_id_add->getListRank());
                        $pack_info->setVolumPerVial($pack_info_id_add->getVolumPerVial());
                        $pack_info->setItemGtin($pack_info_id_add->getItemGtin());
                        $pack_info->setPackagingLevel($pack_info_id_add->getPackagingLevel());
                        $pack_info->setModifiedBy($created_by);
                        $pack_info->setModifiedDate(App_Tools_Time::now());
                        $pack_info->setCreatedBy($created_by);
                        $pack_info->setCreatedDate(App_Tools_Time::now());
                        $this->_em->persist($pack_info);
                        $this->_em->flush();
                        $stock_batch->setPackInfo($pack_info);
                    }
                }
            }

            $stock_batch->setModifiedBy($created_by);
            $stock_batch->setModifiedDate(App_Tools_Time::now());
            $stock_batch->setCreatedBy($created_by);
            $stock_batch->setCreatedDate(App_Tools_Time::now());
            $this->_em->persist($stock_batch);
            $this->_em->flush();
            $master_batch_id = $stock_batch->getPkId();
        }

        $batch_id = $this->checkBatchWarehouse($array);

        if ($batch_id === 0) {
            $stock_batch_warehouses = new StockBatchWarehouses;
            $stock_batch_warehouses->setQuantity(str_replace(",", "", $array['quantity']));
            $stock_batch_warehouses->setStatus(parent::STACKED);
            $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
            $stock_batch_warehouses->setWarehouse($warehouse_id);
            $stock_batch_id = $this->_em->getRepository('StockBatch')->find($master_batch_id);
            $stock_batch_warehouses->setStockBatch($stock_batch_id);
            $stock_batch_warehouses->setModifiedBy($created_by);
            $stock_batch_warehouses->setModifiedDate(App_Tools_Time::now());
            $stock_batch_warehouses->setCreatedBy($created_by);
            $stock_batch_warehouses->setCreatedDate(App_Tools_Time::now());
            $this->_em->persist($stock_batch_warehouses);
            $this->_em->flush();
            $batch_id = $stock_batch_warehouses->getPkId();
        }

        return $batch_id;
    }

    /**
     * Update Batch
     * 
     * @param type $array
     * @return type
     */
    public function updateBatch($array) {
        $wh_id = $this->_identity->getWarehouseId();
 
        $stock_batch = $this->_table->find($array['stock_batch_id']);
        $stock_batch->setNumber(strtoupper($array['number']));
        $stock_batch->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['expiry_date'])));
        $item_id = $this->_em->getRepository('ItemPackSizes')->find($array['item_id']);
        $stock_batch->setItemPackSize($item_id);
        $stock_batch->setQuantity(str_replace(",", "", $array['quantity']));
        $stock_batch->setStatus(parent::STACKED);
        $stock_batch->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['production_date'])));

        if (!empty($array['vvm_type_id'])) {
            $vvm_type_id = $this->_em->getRepository('VvmTypes')->find($array['vvm_type_id']);
            $stock_batch->setVvmType($vvm_type_id);
        }

        $stock_batch->setUnitPrice($array['unit_price']);
        $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
        $stock_batch->setWarehouse($warehouse_id);
        $stakeholder_item_pack_size = $this->_em->getRepository('StakeholderItemPackSizes')->find($array['manufacturer_id']);
        $stock_batch->setStakeholderItemPackSize($stakeholder_item_pack_size);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_batch->setModifiedBy($created_by);
        $stock_batch->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock_batch);
        $this->_em->flush();

        if ($wh_id == Model_Warehouses::FEDERAL_WHID) {
            $stock_batch->setBatchMasterId($stock_batch->getPkId());
            $this->_em->persist($stock_batch);
            $this->_em->flush();
        }

        return $stock_batch->getPkId();
    }

    /**
     * Check And Add Batch
     * 
     * @param type $array
     * @return int
     */
    public function checkAndAddBatch($array) {
        $wh_id = $this->_identity->getWarehouseId();
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);

        $str_sql = $this->_em->createQueryBuilder()
                ->select("sb.pkId")
                ->from('StockBatch', 'sb')
                ->where("sb.packInfo = '" . $array['packInfoId'] . "' ")
                ->andWhere("sb.number = '" . $array['number'] . "' ");
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            $master_batch_id = $row[0]['pkId'];
        } else {
            $stock_batch = new StockBatch();
            $stock_batch->setNumber(strtoupper($array['number']));
            $stock_batch->setExpiryDate(new \DateTime($array['expiryDate']));
            $stock_batch->setUnitPrice($array['unitPrice']);
            $stock_batch->setProductionDate(new \DateTime($array['productionDate']));
            if (!empty($array['stakeholderItemPackSize'])) {
                $pack_info_id = $this->_em->getRepository('PackInfo')->findOneBy(array("stakeholderItemPackSize" => $array['stakeholderItemPackSize']));
                $stock_batch->setPackInfo($pack_info_id);
            }

            if (!empty($array['vvmType'])) {
                $vvm_id = $this->_em->getRepository('VvmTypes')->find($array['vvmType']);
                $stock_batch->setVvmType($vvm_id);
            }

            $stock_batch->setModifiedBy($created_by);
            $stock_batch->setModifiedDate(App_Tools_Time::now());
            $stock_batch->setCreatedBy($created_by);
            $stock_batch->setCreatedDate(App_Tools_Time::now());

            $this->_em->persist($stock_batch);
            $this->_em->flush();
            $master_batch_id = $stock_batch->getPkId();
        }

        /* if ($role_id == 3 && !empty($array['expiry_date'])) {
          $stock_batch = $this->_em->getRepository("StockBatch")->find($stock_batch_warehouses->getStockBatch()->getPkId());
          $stock_batch->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['expiry_date'])));
          $stock_batch->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['production_date'])));
          $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
          $stock_batch->setModifiedBy($created_by);
          $stock_batch->setModifiedDate(App_Tools_Time::now());
          $this->_em->persist($stock_batch);
          $this->_em->flush();
          } */

        $array['manufacturer_id'] = $array['stakeholderItemPackSize'];
        $array['item_id'] = $array['itemPackSize'];
        $batch_id = $this->checkBatchWarehouse($array);

        if ($batch_id === 0) {
            $stock_batch_warehouses = new StockBatchWarehouses;
            $stock_batch_warehouses->setQuantity(0);
            $stock_batch_warehouses->setStatus(Model_StockBatch::STACKED);
            $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
            $stock_batch_warehouses->setWarehouse($warehouse_id);
            $stock_batch_id = $this->_em->getRepository('StockBatch')->find($master_batch_id);
            $stock_batch_warehouses->setStockBatch($stock_batch_id);
            $stock_batch_warehouses->setModifiedBy($created_by);
            $stock_batch_warehouses->setModifiedDate(App_Tools_Time::now());
            $stock_batch_warehouses->setCreatedBy($created_by);
            $stock_batch_warehouses->setCreatedDate(App_Tools_Time::now());
            $this->_em->persist($stock_batch_warehouses);
            $this->_em->flush();
            $batch_id = $stock_batch_warehouses->getPkId();
        }

        return $batch_id;
    }

    /**
     * Check Batch
     * 
     * @param type $array
     * @return int
     */
    public function checkBatchWarehouse($array) {
        if (isset($array['wh_id']) && !empty($array['wh_id'])) {
            $wh_id = $array['wh_id'];
        } else {
            $wh_id = $this->_identity->getWarehouseId();
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("sbw.pkId")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where("sip.itemPackSize = '" . $array['item_id'] . "' ")
                ->andWhere("pi.stakeholderItemPackSize = '" . $array['manufacturer_id'] . "' ")
                ->andWhere("sbw.warehouse = '" . $wh_id . "' ")
                ->andWhere("sb.number = '" . $array['number'] . "' ");
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return 0;
        }
    }

    /**
     * Check Batch
     * 
     * @param type $array
     * @return int
     */
    public function checkBatch($array) {
        if (isset($array['wh_id']) && !empty($array['wh_id'])) {
            $wh_id = $array['wh_id'];
        } else {
            $wh_id = $this->_identity->getWarehouseId();
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("sbw.pkId")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where("sip.itemPackSize = '" . $array['item_id'] . "' ")
                ->andWhere("sbw.warehouse = '" . $wh_id . "' ")
                ->andWhere("sb.number = '" . $array['number'] . "' ");

        $row = $str_sql->getQuery()->getResult();

        $stock_batch_warehouses = $this->_em->getRepository("StockBatchWarehouses")->find($row[0]['pkId']);

        if (count($stock_batch_warehouses) > 0) {
            return $stock_batch_warehouses->getPkId();
        } else {
            return 0;
        }
    }

    /**
     * Check New Batch
     * 
     * @param type $array
     * @return int
     */
    public function checkNewBatch($array) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId as detailId,sbw.pkId as batchId")
                ->from("StockDetail", 'sd')
                ->join("sd.stockMaster", "sm")
                ->join("sm.transactionType", "tt")
                ->join("sd.stockBatchWarehouse", 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where('sip.itemPackSize ', 'ips')
                ->where("sbw.pkId = '" . $array['batch_id'] . "'")
                ->andWhere("tt.pkId=1 ")
                ->orWhere("tt.pkId=2 ")
                ->andWhere("sm.draft=0")
                ->andWhere("sip.itemPackSize = " . $array['item_id'])
                ->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId());

        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            $this->adjustQuantityByWarehouse($row[0]['batchId']);
            return $row[0]['batchId'];
        } else {
            return 0;
        }
    }

    /**
     * Get Batch Number By Products
     * 
     * @return type
     */
    public function getBatchNumberByProducts() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT sb.pkId', 'sb.number')
                ->from("StockBatchWarehouses", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->where('sip.itemPackSize =' . $this->form_values['item_pack_size_id']);

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Stock Batch And Detail By Id
     * 
     * @return type
     */
    public function getStockBatchAndDetailById() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ips.itemName, sd.quantity, sd.vvmStage, sb.expiryDate, sb.number')
                ->from("StockDetail", "sd")
                ->leftJoin("sd.stockBatchWarehous", "sbw")
                ->leftJoin("sbw.stockBatch", "sb")
                ->leftJoin('sb.packInfo', 'pi')
                ->leftJoin('pi.stakeholderItemPackSize', 'sip')
                ->leftJoin("sip.itemPackSize", 'ips')
                ->where("sd.pkId = " . $this->form_values['stock_detail']);
        $result = $str_sql->getQuery()->getResult();
        return $result[0];
    }

    /**
     * Show Batch
     * 
     * @return type
     */
    public function showBatch() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('Sum(sd.quantity) AS total,
                        ips.quantityPerPack')
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehous", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join("sip.itemPackSize", 'ips')
                ->where("sbw.pkId =" . $this->form_values['stock_batch_id']);
        $result = $str_sql->getQuery()->getResult();

        $str_sql2 = $this->_em_read->createQueryBuilder()
                ->select('Sum(p.quantity) AS placement_qty')
                ->from("Placements", "p")
                ->where("p.stockBatchWarehouse =" . $this->form_values['stock_batch_id']);
        $result2 = $str_sql2->getQuery()->getResult();

        return $result[0]['total'] - $result2[0]['placement_qty'];
    }

    /**
     * Get Batches
     * 
     * @return boolean
     */
    public function getBatches() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sb.number,
                        sbw.pkId")
                ->from("StockBatchWarehouses", "sbw")
                ->join("sbw.stockBatch", "sb");



        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Item Batches
     * 
     * @return boolean
     */
    public function getItemBatches() {
        $wh_id = $this->form_values['wh_id'];

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sbw.pkId,sb.number,
                        sb.expiryDate,
                        ips.pkId as itemPackSize,
                        sips.pkId as StakeholderItemPackSizeID,
                        sbw.quantity")
                ->from('StockBatchWarehouses', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sips')
                ->join('sips.itemPackSize', 'ips')
                ->where("sb.expiryDate >= " . date("Y-m-d"))
                ->andWhere("sbw.warehouse = " . $wh_id)
                ->andWhere("sbw.status != 'Finished'");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Stock Gatepass Search
     * 
     * @return type
     */
    public function stockGatepassSearch() {
        $where = "";
        if (!empty($this->form_values['vehicle_type_id'])) {
            $where[] = "gpv.vehicleType= '" . $this->form_values['vehicle_type_id'] . "'";
        }

        if (!empty($this->form_values['item_pack_size_id'])) {
            $where[] = "ip.pkId = '" . $this->form_values['item_pack_size_id'] . "'";
        }

        if (!empty($this->form_values['stock_batch_id'])) {
            $where[] = "sb.pkId = '" . $this->form_values['stock_batch_id'] . "'";
        }


        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(gpm.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        }
        $str_sqlee = $this->_em_read->createQueryBuilder()
                ->select(" gpm.pkId,gpm.number as gpmnumber,gpm.transactionDate,"
                        . " gpv.number as gpnumber,"
                        . " sb.number,"
                        . "ip.itemName")
                ->from("GatepassDetail", "gpd")
                ->join("gpd.gatepassMaster", "gpm")
                ->join("gpm.gatepassVehicle", "gpv")
                ->join("gpd.stockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join("sip.itemPackSize", 'ip');
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
            $str_sqlee->where($where_s);
        }

        return $str_sqlee->getQuery()->getResult();
    }

    /**
     * Edit Batch Expiry
     * 
     * @return boolean
     */
    function editBatchExpiry() {
        $id = $this->form_values['id'];
        $date = $this->form_values['date'];

        $batch = $this->_em->getRepository("StockBatch")->find($id);
        if (count($batch) > 0) {
            $batch->setExpiryDate(new DateTime(date(App_Controller_Functions::dateToDbFormat($date))));
        }
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $batch->setModifiedBy($created_by);
        $batch->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($batch);
        $this->_em->flush();

        return true;
    }

    /**
     * Get Placement History
     * 
     * @return type
     */
    function getPlacementHistory() {

        $batch = $this->_em->getRepository("StockBatchWarehouses")->find($this->form_values['batch_id']);

        if ($batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId() == Model_Base::VACCINECATEGORY || $batch->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId() == Model_Base::INACTIVEVACCINE) {
            $sql = "SELECT
                        cold_chain.asset_id,
                        placement_summary.quantity,
                        placement_summary.batch_number,

                    IF (
                        item_pack_sizes.vvm_group_id = 1,
                        vvm_stages.pk_id,
                        vvm_stages.vvm_stage_value
                        ) AS vvm_stage,
                        placement_locations.pk_id AS place_loc_id,
                        vvm_stages.pk_id AS vvm_stage_id,
                        stock_batch_warehouses.pk_id AS batch_id
                    FROM
                        placement_summary
                        INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
                        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                        INNER JOIN vvm_stages ON placement_summary.vvm_stage = vvm_stages.pk_id
                        INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    WHERE
                        placement_summary.stock_batch_warehouse_id = " . $this->form_values['batch_id'];
        } else {
            $sql = "SELECT
                        placement_summary.quantity,
                        placement_summary.batch_number,
                        non_ccm_locations.location_name AS asset_id,
                        'NA' AS vvm_stage,
                        placement_locations.pk_id AS place_loc_id,
                        placement_summary.stock_batch_warehouse_id AS batch_id
                    FROM
                        placement_summary
                        INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
                        INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                    WHERE
                        placement_summary.stock_batch_warehouse_id = " . $this->form_values['batch_id'];
        }

        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Placement Vvm Stage
     * 
     * @return type
     */
    function getPlacementVvmStage() {
        $sql = "SELECT
              SUM(placement_summary.quantity) as quantity,
              IF(item_pack_sizes.vvm_group_id = 1, vvm_stages.pk_id, vvm_stages.vvm_stage_value) vvm_stage
              FROM
              placement_summary
              INNER JOIN non_ccm_locations ON placement_summary.placement_location_id = non_ccm_locations.pk_id
              INNER JOIN vvm_stages ON placement_summary.vvm_stage = vvm_stages.pk_id
              INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
              INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
              INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
              INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
              INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
              where placement_summary.stock_batch_warehouse_id = " . $this->form_values['batch_id'] . "
              AND non_ccm_locations.warehouse_id = " . $this->_identity->getWarehouseId();

        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Issue Receive By Date
     * 
     * @return type
     */
    public function getIssueReceiveByDate() {
        $wh_id = $this->_identity->getWarehouseId();
        $from_date = $this->form_values['from_date'];
        $to_date = $this->form_values['to_date'];

        $sql = "SELECT
                Sum(IF (stock_master.transaction_type_id = 1, stock_detail.quantity, 0)) AS Rcv,
                Sum(IF (stock_master.transaction_type_id = 1, stock_detail.quantity, 0)) * item_pack_sizes.number_of_doses AS RcvD,
                Sum(IF (stock_master.transaction_type_id = 2, stock_detail.quantity, 0)) AS Issue,
                Sum(IF (stock_master.transaction_type_id = 2, stock_detail.quantity, 0)) * item_pack_sizes.number_of_doses AS IssueD,
                Sum(IF (stock_master.transaction_type_id IN (6,9),stock_detail.quantity,0)) AS Expired,
                Sum(IF (stock_master.transaction_type_id IN (6,9),stock_detail.quantity,0)) * item_pack_sizes.number_of_doses AS ExpiredD,
                Sum(IF (stock_master.transaction_type_id IN (1,2,6,9),stock_detail.quantity,0)) AS total,
                Sum(IF (stock_master.transaction_type_id IN (1,2,6,9),stock_detail.quantity,0)) * item_pack_sizes.number_of_doses AS totalD,
                items.description AS item_name
               FROM
                stock_master
               INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
               INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
               INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
               WHERE
                DATE_FORMAT(stock_master.transaction_date,'%Y-%m-%d') BETWEEN '$from_date'
               AND '$to_date'
               AND stock_batch_warehouses.warehouse_id = $wh_id
               GROUP BY
                items.pk_id
               ORDER BY
                item_pack_sizes.list_rank ASC";
        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        $result = $row->fetchAll();

        foreach ($result as $r) {
            $data[$r['item_name']] = array(
                'receive' => $r['Rcv'],
                'receiveD' => $r['RcvD'],
                'issue' => ABS($r['Issue']),
                'issueD' => ABS($r['IssueD']),
                'total' => $r['total'],
                'totalD' => $r['totalD'],
                'expired' => ABS($r['Expired']),
                'expiredD' => ABS($r['ExpiredD'])
            );
        }

        return $data;
    }

    /**
     * Get Batch Locations
     * 
     * @return type
     */
    public function getBatchLocations() {
        $batch_id = $this->form_values['batch_id'];
        $type = $this->form_values['type'];

        $sql = "SELECT
                        placements.placement_location_id,
                        SUM(placements.quantity) AS quantity,
                        cold_chain.asset_id,
                        vvm_stages.pk_id as vvm_stage_id,
                        IF(item_pack_sizes.vvm_group_id = 1, vvm_stages.pk_id, vvm_stages.vvm_stage_value) vvm_stage
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
                GROUP BY
                placements.placement_location_id,
                placements.vvm_stage ORDER BY cold_chain.asset_id";

        if (!empty($type)) {
            $tran_type = $this->_em->getRepository("TransactionTypes")->find($type);
            if ($tran_type->getNature() == '+') {
                $wh_id = $this->_identity->getWarehouseId();
                if (!empty($this->form_values['wh_id'])) {
                    $wh_id = $this->form_values['wh_id'];
                }

                $loc_type = Model_PlacementLocations::LOCATIONTYPE_CCM;

                $sql = "SELECT DISTINCT
                                cold_chain.asset_id,
                                0 vvm_stage,
                                placement_locations.pk_id AS placement_location_id
                        FROM
                                cold_chain
                        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                        LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
                        WHERE
                                cold_chain.warehouse_id = $wh_id
                        AND (
                                (
                                        cold_chain.ccm_asset_type_id = 3
                                        OR AssetMainType.pk_id = 3
                                )
                                OR (
                                        cold_chain.ccm_asset_type_id = 1
                                        OR AssetMainType.pk_id = 1
                                )
                        )
                        AND placement_locations.location_type = $loc_type
                        GROUP BY
                                cold_chain.auto_asset_id
                        ORDER BY
                                cold_chain.asset_id,
                                cold_chain.ccm_asset_type_id DESC";
            }
        }
        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Cold Stores
     * 
     * This Function is using in 'receive from store' screen for vaccines'locations.
     * @return array Cold Stores Locations
     * @author Ajmal Hussain <ajmal@deliver-pk.org>
     *
     */
    public function getAllColdStores() {
        $wh_id = $this->_identity->getWarehouseId();
        $loc_type = 99;

        $sql = "SELECT DISTINCT
                                cold_chain.asset_id,
                                0 vvm_stage,
                                placement_locations.pk_id AS placement_location_id
                        FROM
                                cold_chain
                        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
LEFT JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
                        WHERE
                                cold_chain.warehouse_id = $wh_id
                        AND (
                                (
                                        cold_chain.ccm_asset_type_id = 3
                                        OR AssetMainType.pk_id = 3
                                )
                                OR (
                                        cold_chain.ccm_asset_type_id = 1
                                        OR AssetMainType.pk_id = 1
                                )
                        )
                        AND placement_locations.location_type = $loc_type AND
ccm_status_history.ccm_status_list_id <> 3
                        GROUP BY
                                cold_chain.auto_asset_id
                        ORDER BY
                                cold_chain.asset_id,
                                cold_chain.ccm_asset_type_id DESC";
        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Bactch Location Dry Store
     * 
     * @return type
     */
    public function getBactchLocationDryStore() {
        $batch_id = $this->form_values['batch_id'];
        $type = $this->form_values['type'];
        $loc_type = Model_PlacementLocations::LOCATIONTYPE_NONCCM;

        $sql = "SELECT
                        placements.placement_location_id,
                        SUM(placements.quantity) AS quantity,
                        'NA' vvm_stage,
                        '0' vvm_stage_id,
                        non_ccm_locations.location_name asset_id
                FROM
                        placements
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
                WHERE
                        placements.stock_batch_warehouse_id = $batch_id
                        and placement_locations.location_type = $loc_type
                GROUP BY
                        placements.placement_location_id,
                        placements.vvm_stage ORDER BY non_ccm_locations.location_name";

        if (!empty($type)) {
            $tran_type = $this->_em->getRepository("TransactionTypes")->find($type);
            if ($tran_type->getNature() == '+') {
                $wh_id = $this->_identity->getWarehouseId();

                if (!empty($this->form_values['wh_id'])) {
                    $wh_id = $this->form_values['wh_id'];
                }

                $sql = "SELECT DISTINCT
                                placement_locations.pk_id AS placement_location_id,
                                'NA' vvm_stage,
                                '0' vvm_stage_id,
                                non_ccm_locations.location_name asset_id
                        FROM
                                non_ccm_locations
                        INNER JOIN placement_locations ON non_ccm_locations.pk_id = placement_locations.location_id
                        WHERE
                                non_ccm_locations.warehouse_id = $wh_id
                        AND placement_locations.location_type = $loc_type
                        ORDER BY
                                non_ccm_locations.location_name";
            }
        }

        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Set Priority Batches Status
     * 
     */
    public function setPriorityBatchesStatus() {
        $product_id = $this->form_values['product_id'];
        $wh_id = $this->_identity->getWarehouseId();
        $stacked = Model_StockBatch::STACKED;
        $finised = Model_StockBatch::FINISHED;
        $em = Zend_Registry::get('doctrine');
        $em->getConnection()->beginTransaction();
        try {

            $this->_em = Zend_Registry::get('doctrine');
            $str_upd_fin = "UPDATE stock_batch_warehouses"
                    . "SET `stock_batch_warehouses.status` = '$finised' "
                    . "INNER JOIN pack_info ON stock_batch_warehouses.pack_info_id = pack_info.pk_id"
                    . "INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id "
                    . "WHERE stock_batch_warehouses.quantity = 0 "
                    . "and stakeholder_item_pack_sizes.item_pack_size_id = $product_id "
                    . "and stock_batch_warehouses.warehouse_id=$wh_id";

            $row_finish = $this->_em->getConnection()->prepare($str_upd_fin);
            $row_finish->execute();
            $str_upd_stack = "UPDATE stock_batch_warehouses SET "
                    . "`status` = '$stacked'  "
                    . "INNER JOIN pack_info ON stock_batch_warehouses.pack_info_id = pack_info.pk_id"
                    . "INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id "
                    . "WHERE stock_batch_warehouses.quantity > 0 "
                    . "and stakeholder_item_pack_sizes.item_pack_size_id = $product_id "
                    . "and stock_batch_warehouses.warehouse_id=$wh_id";
            $row_stack = $this->_em->getConnection()->prepare($str_upd_stack);
            $row_stack->execute();


            $current_date = new DateTime(date("Y-m-d"));
            $today = $current_date->format("Y-m-d");
            $month3 = $current_date->modify("+3 months");
            $after3month = $month3->format("Y-m-d");
            $month12 = $current_date->modify("+12 months");
            $afteryear = $month12->format("Y-m-d");


            $str_qry = "SELECT
                           stock_batch_warehouses.pk_id as batch_id,
                           stock_batch_warehouses.`status`,
                           stock_batch_warehouses.quantity
                    FROM
                    stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id
                    GROUP BY
                           stock_batch_warehouses.pk_id
                    HAVING
                            quantity > 0
                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";

            $this->_em = Zend_Registry::get('doctrine');
            $row = $this->_em->getConnection()->prepare($str_qry);
            $row->execute();
            $result = $row->fetchAll();
            if (count($result) > 0) {

                foreach ($result as $res) {
                    $stock_batch = $this->_table->find($res['batch_id']);
                    if (count($stock_batch) >= 1) {
                        $stock_batch->setStatus(Model_StockBatch::RUNNING);
                        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                        $stock_batch->setModifiedBy($created_by);
                        $stock_batch->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($stock_batch);
                        $this->_em->flush();
                    }
                }
            } else {

                $str_qry1 = "SELECT
                           stock_batch_warehouses.pk_id as batch_id,
                           stock_batch_warehouses.`status`,
                           stock_batch_warehouses.quantity
                    FROM
                            stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id
                    GROUP BY
                           stock_batch_warehouses.pk_id
                    HAVING
                           stock_batch_warehouses.quantity > 0
                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";

                $this->_em = Zend_Registry::get('doctrine');
                $row1 = $this->_em->getConnection()->prepare($str_qry1);
                $row1->execute();
                $result2 = $row1->fetchAll();


                foreach ($result2 as $res) {
                    $stock_batch = $this->_table->find($res['batch_id']);
                    if (count($stock_batch) >= 1) {
                        $stock_batch->setStatus(Model_StockBatch::RUNNING);
                        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                        $stock_batch->setModifiedBy($created_by);
                        $stock_batch->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($stock_batch);
                        $this->_em->flush();
                    }
                }
            }

            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }
    }

    /**
     * Get Existing Batches
     * 
     * @param type $product
     * @return type
     */
    public function getExistingBatches($product) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT sb.number")
                ->from("StockBatchWarehouses", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sip.itemPackSize = $product");
        $result = $str_sql->getQuery()->getResult();
        $resultarray = array();
        if (count($result) > 0) {
            foreach ($result as $row) {
                $resultarray[] = $row['number'];
            }
        }
        return Zend_Json::encode($resultarray);
    }

    /**
     * Get Adjusted Batches
     * 
     * @return boolean
     */
    public function getAdjustedBatches() {
        $wh_id = $this->_identity->getWarehouseId();
        $item_id = $this->form_values["item_id"];

        $str_sql = "
                        SELECT DISTINCT
                            stock_batch_warehouses.pk_id,
                            stock_batch.number,
                            stock_batch_warehouses.quantity
                        FROM
                            stock_detail AS s2_
                                INNER JOIN stock_master AS s0_ ON s2_.stock_master_id = s0_.pk_id

                                INNER JOIN stock_batch_warehouses ON s2_.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id

                        WHERE
                                s0_.from_warehouse_id = $wh_id
                                AND s0_.to_warehouse_id = $wh_id
                                AND s0_.transaction_type_id > 2
                                AND stakeholder_item_pack_sizes.item_pack_size_id = '$item_id' ORDER BY stock_batch.number ASC";

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
     * Get Running Batches
     * 
     * @return type
     */
    public function getRunningBatches() {

        $itm = $this->_em->getRepository("ItemPackSizes")->find($this->form_values['item_pack_size_id']);

        if ($itm->getItemCategory()->getPkId() == 1 || $itm->getItemCategory()->getPkId() == 4) {
            $result = $this->getAllIssuePriorityBatches();
        } else {
            $result = $this->getAllIssueRunningBatches();
        }
        return $result;
    }

    /**
     * Get All Issue Priority Batches
     * 
     * @return type
     */
    public function getAllIssuePriorityBatches() {

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        $item_id = $this->form_values['item_pack_size_id'];
        $wh_id = $this->_identity->getWarehouseId();
        $trans_date = App_Controller_Functions::dateToDbFormat($this->form_values['transaction_date']);
        $batch_no = implode(",", $this->form_values['batch_no']);
        if (!empty($batch_no)) {
            $batch_number = "AND stock_batch_warehouses.pk_id NOT IN ($batch_no)";
        }
        $str_sql = "(
        SELECT
                stock_batch.expiry_date AS expiryDate,
                stock_batch.number,
                SUM(placements.quantity) AS quantity,
                stock_batch_warehouses.pk_id AS pkId,
                'P1' AS priority
        FROM
                stock_batch
        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $wh_id

        AND item_pack_sizes.item_category_id = 1
        AND (
                placements.vvm_stage = 2
                OR (
                        placements.vvm_stage = 1
                        AND DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%Y-%m-%d'
                        ) BETWEEN '$today'
                        AND '$after3month'
                )
        )
        AND DATE_FORMAT(
                stock_batch.expiry_date,
                '%Y-%m-%d'
        ) >= '$trans_date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
            $batch_number
        GROUP BY
                placements.stock_batch_warehouse_id
        HAVING
                quantity > 0
        ORDER BY
                item_pack_sizes.list_rank,
                stock_batch.expiry_date
    )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P2' AS priority
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) BETWEEN '$after3month'
                AND '$afteryear'
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) >= '$trans_date'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                    $batch_number
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )
    UNION
        (
                SELECT
                        stock_batch.expiry_date AS expiryDate,
                        stock_batch.number,
                        SUM(placements.quantity) AS quantity,
                        stock_batch_warehouses.pk_id AS pkId,
                        'P3' AS priority
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND item_pack_sizes.item_category_id = 1
                AND placements.vvm_stage = 1
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) > '$afteryear'
                AND DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) >= '$trans_date'
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                    $batch_number
                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                        quantity > 0
                ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
        )";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Issue Running Batches
     * 
     * @return boolean
     */
    public function getAllIssueRunningBatches() {



        $batch_no = implode(",", $this->form_values['batch_no']);


        $str_sql = $this->_em->createQueryBuilder()
                ->select("sb.number,
                        sbw.pkId,
                        sb.expiryDate,
                        sbw.quantity")
                ->from("StockBatchWarehouses", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sbw.quantity > 0 ")
                ->andWhere("sip.itemPackSize = " . $this->form_values['item_pack_size_id']);

        if (!empty($this->form_values['transaction_date'])) {
            $str_sql->andWhere("sb.expiryDate >= '" . App_Controller_Functions::dateToDbFormat($this->form_values['transaction_date']) . "' ");
        }
        if (!empty($batch_no)) {
            $str_sql->andWhere("sbw.pkId NOT IN ($batch_no)");
        }

        $str_sql->andWhere("sbw.warehouse = " . $this->_identity->getWarehouseId())
                ->orderBy("sbw.quantity", "DESC");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Batch Product Total
     * 
     * @return type
     */
    public function batchProductTotal() {

        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.itemName,
                        ips.numberOfDoses as description,
                        SUM(sbw.quantity) AS Vials,
                        SUM(ips.numberOfDoses * sbw.quantity) AS Doses,
                        sb.number")
                ->from("StockBatchWarehouses", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->join("ips.itemUnit", "iu")
                ->where("ips.itemCategory = 1")
                ->andWhere("sbw.warehouse = " . $wh_id);

        if ($wh_id == 159) {
            $str_sql->andWhere("ips.pkId NOT IN(35,38)");
        }

        $str_sql->groupBy("ips.pkId")
                ->orderBy("ips.listRank");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Duplicate Batches
     * @return boolean
     */
    public function getDuplicateBatches() {
        $batches = array();
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("COUNT(sb.number) cnt, sb.number, GROUP_CONCAT(sb.pkId) batch_ids")
                ->from("StockBatch", "sb")
                ->groupBy("sb.number, sb.packInfo")
                ->having("cnt > 1")
                ->orderBy("cnt");
        $data = $str_sql->getQuery()->getResult();

        if (count($data) > 0) {
            $batches = array();
            foreach ($data as $row) {
                $batches[] = $row['batch_ids'];
            }

            $all_dup_bathces = implode(",", $batches);

            $str_sql2 = $this->_em_read->createQueryBuilder()
                    ->select("sb")
                    ->from("StockBatch", "sb")
                    ->where("sb.pkId IN ($all_dup_bathces)")
                    ->orderBy("sb.number,sb.pkId");
            return $str_sql2->getQuery()->getResult();
        }

        return false;
    }

    /**
     * Get All Negative Batches
     * @return type
     */
    public function getAllNegativeBatches() {

        $str_sql = "SELECT
	SUM(stock_detail.quantity) AS qty,
	item_pack_sizes.item_name,
	item_pack_sizes.pk_id AS item_id,
		DATE_FORMAT(MIN(
		stock_master.transaction_date
	),'%d-%m-%Y') AS transaction_date,
	stock_batch_warehouses.pk_id AS batch_id,
        stock_batch.number
        FROM
                stock_detail
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
        GROUP BY
                stock_detail.stock_batch_warehouse_id
        HAVING
	qty < 0";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Placements By Batch
     * @param type $batch_id
     * @return string
     */
    public function getPlacementsByBatch($batch_id) {

        $str_sql = "SELECT
	SUM(placements.quantity) qty
FROM
	placements
WHERE
	placements.stock_batch_warehouse_id = $batch_id";

        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();

        if (count($data) > 0) {
            return $data[0]['qty'];
        } else {
            return '0';
        }
    }

    public function getBatchCBByDate() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("IFNULL(SUM(sd.quantity), 0) as quantity")
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sd.stockMaster", "sm")
                ->where("sbw.pkId = " . $this->form_values['pk_id'])
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') <= '" . $this->form_values['trans_date'] . "'");
        $row = $str_sql->getQuery()->getResult();
        return $row[0]['quantity'];
    }

}
