<?php

/**
 * Model_StockDetail
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stock Detail
 */
class Model_StockDetail extends Model_Base {

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
        $this->_table = $this->_em->getRepository('StockDetail');
    }

    /**
     * Add Stock Detail
     * @param type $array
     * @return type
     * @throws Exception
     */
    public function addStockDetail($array) {

        $action = Zend_Registry::get("action");
        if ($array['rcvedit'] == "Yes") {
            $stock_detail = $this->_em->getRepository("StockDetail")->find($array['stock_detail_id']);
        } else {
            $stock_detail = new StockDetail();
        }
        $type = $array['transaction_type_id'];

        if (!empty($array['hdn_stock_id'])) {
            $stock_id = $array['hdn_stock_id'];
        } else {
            $stock_id = $array['stock_master_id'];
        }

        $stock_master_id = $this->_em->getRepository('StockMaster')->find($stock_id);
        $stock_detail->setStockMaster($stock_master_id);
        if (!empty($array['item_unit_id'])) {
            $item_unit_id = $this->_em->getRepository('ItemUnits')->find($array['item_unit_id']);
            $stock_detail->setItemUnit($item_unit_id);
        }
        if ($array['type'] != 's') {
            $stock_detail->setTemporary(1);
        }
        $quantity = str_replace(",", "", $array['quantity']);

        if ($action == 'issue' || $action == 'requisition-issue') {
            list($location, $vvm, $placd_qty) = explode("|", $array['vvm_stage']);
            if ($vvm == 0) {
                $array['vvm_stage'] = 0;
            } else if (!empty($vvm)) {
                $array['vvm_stage'] = $vvm;
            } else {
                $array['vvm_stage'] = $location;
            }
            if ($quantity > $placd_qty) {
                throw new RangeException('PLCD_QTY_GREATER_THAN_ISSUE_QTY');
            }
        }
        //   if (!empty($array['vvm_stage'])) {
        $vvms = $this->_em->getRepository("VvmStages")->find($array['vvm_stage']);

        $stock_detail->setVvmStage($vvms);
        //  }

        if ($type == Model_TransactionTypes::TRANSACTION_ISSUE) {
            $quantity = "-" . $quantity;
        }
        $stock_detail->setQuantity($quantity);
        $stock_detail->setAdjustmentType("$type");
        if ($type == 1) {
            $str_sql = $this->_em->createQueryBuilder()
                    ->select("sbw.pkId")
                    ->from('StockBatchWarehouses', 'sbw')
                    ->join('sbw.stockBatch', 'sb')
                    ->join('sb.packInfo', 'pi')
                    ->join('pi.stakeholderItemPackSize', 'sip')
                    ->where("sip.itemPackSize = '" . $array['item_id'] . "' ")
                    ->andWhere("pi.stakeholderItemPackSize = '" . $array['manufacturer_id'] . "'  ")
                    ->andWhere("sb.number = '" . $array['number'] . "'  ")
                    ->andWhere("sbw.warehouse =  '" . $this->_identity->getWarehouseId() . "' ");
            //this IF is for stock receive
            $row = $str_sql->getQuery()->getResult();
            $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($row['0']['pkId']);
            $stock_detail->setStockBatchWarehouse($stock_batch_id);

            $stock_detail->setIsReceived(1);
        } else if ($type == 2) {
            //this ELSE IF is for stock issue
            $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($array['stock_batch_id']);
            $stock_detail->setStockBatchWarehouse($stock_batch_id);
            $stock_detail->setIsReceived(0);
        }
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_detail->setModifiedBy($created_by);
        $stock_detail->setModifiedDate(App_Tools_Time::now());
        $stock_detail->setCreatedBy($created_by);
        $stock_detail->setCreatedDate(App_Tools_Time::now());
        $this->_em->persist($stock_detail);
        $this->_em->flush();
        if ($action == 'issue' && !empty($location)) {
            $placements = new Model_Placements();
            $placements->form_values['placement_loc_id'] = $location;
            $placements->form_values['batch_id'] = $array['number'];
            $placements->form_values['vvmstage'] = $vvm;
            $placements->form_values['is_placed'] = 1;
            $placements->form_values['quantity'] = $quantity;
            $placements->form_values['placement_loc_type_id'] = 115;
            $placements->form_values['detail_id'] = $stock_detail->getPkId();
            $placements->form_values['user_id'] = $this->_user_id;
            $placements->form_values['created_date'] = date("Y-m-d");
            $placements->add();
        }
        return $stock_detail->getPkId();
    }

    /**
     * Update Stock Detail Temp
     *
     * @param type $id
     */
    public function updateStockDetailTemp($id) {
        $row = $this->_em->getConnection()->prepare("UPDATE stock_detail SET `temporary` = 0 WHERE stock_master_id = $id");
        $row->execute();
    }

    public function deleteOrphonRecord() {
        $wh_id = $this->_identity->getWarehouseId();

        $sql = "DELETE FROM stock_master WHERE
	stock_master.pk_id NOT IN (
		SELECT DISTINCT
			stock_detail.stock_master_id
		FROM
			stock_detail
	) AND stock_master.draft = 1 AND
        stock_master.from_warehouse_id = $wh_id";
        $row = $this->_em->getConnection()->prepare($sql);
        $row->execute();
    }

    /**
     * Get Quantity By Id
     *
     */
    public function getQuantityById($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sd.pkId as stockDetail,sd.quantity,sbw.pkId as stockBatchId,sb.number,s.pkId as stockMasterId,ips.pkId as itemPackSize,sb.expiryDate,vt.pkId as vvmTypeId,sips.pkId as stakeholderItemPackSizeId,sb.unitPrice')
                ->from('StockDetail', 'sd')
                ->join('sd.stockMaster', 's')
                ->join('sd.stockBatchWarehouse', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->leftjoin('pi.stakeholderItemPackSize', 'sips')
                ->join('sips.itemPackSize', 'ips')
                ->leftjoin('sb.vvmType', 'vt')
                ->where('sd.pkId =' . $id);
        $row = $str_sql->getQuery()->getResult();
        return array(
            'number' => $row[0]['number'],
            'quantity' => $row[0]['quantity'],
            'stock_batch_id' => $row[0]['stockBatchId'],
            'stock_master_id' => $row[0]['stockMasterId'],
            'item_pack_size_id' => $row[0]['itemPackSize'],
            'stock_detail' => $row[0]['stockDetail'],
            'expiryDate' => $row[0]['expiryDate'],
            'vvmTypeId' => $row[0]['vvmTypeId'],
            'stakeholderItemPackSizeId' => $row[0]['stakeholderItemPackSizeId'],
            'unitPrice' => $row[0]['unitPrice']
        );
    }

    /**
     * Delete Stock Detail
     */
    public function deleteStockDetail($id) {
        $stockdetail = $this->_table->find($id);
        $this->_em->remove($stockdetail);
        $this->_em->flush();
        return true;
    }

    /**
     * Delete Stock Detail Picking
     */
    public function deleteStockDetailPicking($detail_id) {
        $stockdetail = $this->_em->getRepository("Placements")->findBy(array("stockDetail" => $detail_id));
        if (count($stockdetail) > 0) {
            foreach ($stockdetail as $sd) {
                $this->_em->remove($sd);
            }
            $this->_em->flush();
        }
        return true;
    }

    /**
     * Delete Issue
     */
    public function deleteIssue($id) {
        $stock_batch = new Model_StockBatch();
        $result = $this->getQuantityById($id);
        $batch_id = $result['stock_batch_id'];
        $stock_batch->form_values['pk_id'] = $batch_id;
        $stock_batch->form_values['status'] = Model_StockBatch::RUNNING;
        $stock_batch->changeStatus();
        $del = $this->deleteStockDetail($id);
        $del = $this->deleteStockDetailPicking($id);
        $stock_master = new Model_StockMaster();
        $stock_master->deleteStockMaster($result['stock_master_id']);
        $stock_batch->adjustQuantityByWarehouse($batch_id);
        return $del;
    }

    /**
     * Edit Quantity
     */
    public function editQuantity() {
        $stock_batch = new Model_StockBatch;
        $entered_quantity = $this->form_values['quantity'];
        $id = $this->form_values['pk_id'];
        $result = $this->getQuantityById($id);
        $batch_id = $result['stock_batch_id'];
        $this->adjustQuantity($id, $entered_quantity);
        $stock_batch->adjustQuantityByWarehouse($batch_id, $this->_identity->getWarehouseId());
    }

    /**
     * Adjust Quantity
     */
    public function adjustQuantity($id, $qty) {
        $stock = $this->_table->find($id);
        $stock->setQuantity($qty);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock->setModifiedBy($created_by);
        $stock->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock);
        return $this->_em->flush();
    }

    /**
     * Find By Stock Id
     */
    public function findByStockId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('d.pkId,sbw.pkId as batchId,'
                        . 'iu.pkId as itemUnit,d.quantity,d.temporary,vvm.pkId as vvmStage,vvm.vvmStageValue,'
                        . 'd.isReceived,d.adjustmentType,w.pkId as toWarehouse,fw.pkId as fromWarehouse,sa.pkId as stakeholderActivity')
                ->from("StockDetail", "d")
                ->join("d.vvmStage", "vvm")
                ->join("d.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", 'ips')
                ->join("ips.itemUnit", "iu")
                ->join("d.stockMaster", "m")
                ->leftJoin("m.stakeholderActivity", "sa")
                ->join("m.toWarehouse", "w")
                ->join("m.fromWarehouse", "fw")
                ->where('m.pkId =' . $this->pkId)
                ->andWhere('d.isReceived = 0');
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Find By Detail Id
     */
    public function findByDetailId($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('d.pkId,sbw.pkId as stockBatch,'
                        . 'iu.pkId as itemUnit,d.quantity,d.temporary,vvm.pkId as vvmStage,vvm.vvmStageValue,'
                        . 'd.isReceived,d.adjustmentType,w.pkId as toWarehouse')
                ->from('StockDetail', 'd')
                ->join("d.vvmStage", "vvm")
                ->join("d.stockBatchWarehouse", 'sbw')
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->join("d.itemUnit", "iu")
                ->join("d.stockMaster", "m")
                ->join("m.toWarehouse", "w")
                ->where('d.pkId =' . $id)
                ->andWhere('d.isReceived = 0');

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Stock Received
     */
    public function stockReceived($id) {
        $stock_detail = $this->_table->find($id);
        $stock_detail->setIsReceived(1);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_detail->setModifiedBy($created_by);
        $stock_detail->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock_detail);
        return $this->_em->flush($stock_detail);
    }

    /**
     * Get Batch Detail
     */
    public function getBatchDetail($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sb.pkId as batchMasterId, sb.number,sb.expiryDate,ips.pkId as itemPackSize,
                        sb.unitPrice,sb.productionDate,vt.pkId as vvmType ,sd.quantity, sips.pkId as stakeholderItemPackSize, pi.pkId as packInfoId')
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sips")
                ->join("sips.itemPackSize", "ips")
                ->leftJoin("sb.vvmType", "vt")
                ->where("sd.pkId =  $id ");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Un Total Quantity By Batch
     */
    public function getUnTotalQuantityByBatch($id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('Sum(sd.quantity) AS total,
                        pi.quantityPerPack')
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->where("sbw.pkId ='  " . $this->form_values['batchId'] . "'")
                ->andwhere("sd.pkId =" . $id);
        $result = $str_sql->getQuery()->getResult();
        $sql = "SELECT
                        sum(placements.quantity) as sum
                        FROM
                        placements
                        INNER JOIN stock_detail ON placements.stock_detail_id = stock_detail.pk_id
                        WHERE
                        placements.stock_batch_warehouse_id ='" . $this->form_values['batchId'] . "' AND
                        placements.stock_detail_id = " . $id;

        $row = $this->_em_read->getConnection()->prepare($sql);
        $row->execute();
        $res = $row->fetchAll();
        $result2 = $res[0]['sum'];
        // 50-10 = 40pq
        $total = $result[0]['total'] - $result2;
        // 40/10=4 unaloc
        $return['unallocated_qty'] = $total / $result[0]['quantityPerPack'];
        // 40
        $return['product_qty'] = $total;
        return $return;
    }

    /**
     * Get Total Quantity By Batch
     */
    public function getTotalQuantityByBatch($id = NULL) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('Sum(sd.quantity) AS total,
                        ips.quantityPerPack')
                ->from("StockDetail", "sd")
                ->join("sd.stockBatchWarehouse", "sb")
                ->join("sb.itemPackSize", "ips")
                ->where("sb.pkId ='" . $this->form_values['batchId'] . "'")
                ->andwhere("sd.pkId =" . $id);
        $result = $str_sql->getQuery()->getResult();
        $str_qry = "SELECT
                    sum(placements.quantity) as sum
                FROM
                    placements
                INNER JOIN stock_detail ON placements.stock_detail_id = stock_detail.pk_id
                WHERE
                    placements.stock_batch_warehouse_id ='" . $this->form_values['batchId'] . "' AND
                    placements.stock_detail_id = " . $id;

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        $res = $row->fetchAll();
        $result2 = $res[0]['sum'];
        //50-40 = 10pq
        $total = $result[0]['total'] - $result2;
        //10/10=1 unaloc
        $return['unallocated_qty'] = $total / $result[0]['quantityPerPack'];
        //10
        $return['product_qty'] = $total;
        return $return;
    }

    /**
     * Get Total Quantity
     */
    public function getTotalQuantity() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sum(sd.quantity) quantity')
                ->from("StockDetail", "sd")
                ->where("sd.stockBatchWarehouse =" . $this->form_values['stock_batch_id']);
        $result = $str_sql->getQuery()->getResult();
        return $result[0]['quantity'];
    }

    /**
     * Quantity Data Issueno
     */
    public function quantityDataIssueno($stoc_mas) {
        $wh_id = $this->_identity->getWarehouseId();
        $qr = ' ';
        end($stoc_mas);
        foreach ($stoc_mas as $value):
            $qr .= $value . '  ';
            if ($value != end($stoc_mas)) {
                $qr .= ' , ';
            }
        endforeach;
        $str_qry = "SELECT * FROM (SELECT
                                stock_master.pk_id AS masterId,
                                stock_batch_warehouses.pk_id,
                                stock_detail.pk_id as pkId,
                                stock_batch.number,
                                item_pack_sizes.item_name,
                                SUM(ABS(stock_detail.quantity)) - ( COALESCE((SELECT SUM(gatepass_detail.quantity)
                                        FROM gatepass_detail
                                        WHERE gatepass_detail.stock_detail_id = stock_detail.pk_id ), NULL, 0) ) AS Qty
                        FROM
                                stock_master
                        INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
                        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                       WHERE
                                stock_master.from_warehouse_id = $wh_id
                        AND stock_master.transaction_type_id =" . Model_TransactionTypes:: TRANSACTION_ISSUE . "
                        AND stock_master.pk_id IN ($qr)
                        GROUP BY stock_batch_warehouses.pk_id) A
                        WHERE A.Qty > 0";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        $return = $row->fetchAll();
        $str_qry1 = " SELECT
                    Sum(gatepass_detail.quantity) AS gp_qty,
                    gatepass_detail.stock_detail_id
                    FROM
                            gatepass_detail
                    WHERE gatepass_detail.stock_detail_id = 12
                    GROUP BY gatepass_detail.stock_detail_id";

        $row = $this->_em_read->getConnection()->prepare($str_qry1);
        $row->execute();
        return $return;
    }

    /**
     * Quantity Data By Stc Master
     * @param type $stm_id
     * @return type
     */
    public function quantityDataByStcMaster($stm_id) {
        $str_qry = "SELECT
                            stock_detail.pk_id as pkId,stock_batch_warehouses.pk_id as stckbatch_id,
                stock_batch.number, item_pack_sizes.item_name, stock_detail.quantity as qty
                FROM
                stock_detail
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                WHERE stock_detail.stock_master_id = $stm_id";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Quantity Data By Stc Batch
     * @param type $stb_id
     * @param type $stm_id
     * @return boolean
     */
    public function quantityDataByStcBatch($stb_id, $stm_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sd.pkId,sd.quantity')
                ->from("StockDetail", "sd")
                ->where("sd.stockBatchWarehouse =" . $stb_id)
                ->andwhere("sd.stockMaster =" . $stm_id);
        $result = $str_sql->getQuery()->getResult();
        if (!empty($result) && count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Closest
     * @param type $array
     * @param type $search
     * @return type
     */
    public function getClosest($array, $search) {
        $closest = null;
        foreach ($array as $key => $val) {
            if ($closest == null || abs($search - $closest) > abs($val - $search)) {
                $closest = $val;
                $closestKey = $key;
                $arr = array($closestKey, $closest);
            }
        }
        return $arr;
    }

    /**
     * Update Detail
     * @param type $id
     * @param type $batch_id
     * @return type
     */
    public function updateDetail($id, $batch_id) {
        $stock_detail = $this->_table->find($id);
        $b_id = $this->_em->getRepository('StockBatch')->find($batch_id);
        $stock_detail->setStockBatch($b_id);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_detail->setModifiedBy($created_by);
        $stock_detail->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock_detail);
        return $this->_em->flush();
    }

    /**
     * Purpose Transfer History
     * @param type $batch_id
     * @return boolean
     */
    public function purposeTransferHistory($batch_id) {
        $str_sql = "";
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
     * Purpose Transfer Management
     * @param type $batch_id
     * @return boolean
     */
    public function purposeTransferManagement($batch_id, $transfer_date) {
        if (!empty($transfer_date)) {
            $transfer_date = App_Controller_Functions::dateToDbFormat($transfer_date);
        } else {
            $transfer_date = date("Y-m-d");
        }
        $str_sql = "SELECT
	stakeholder_activities.pk_id AS activity,
        item_pack_sizes.pk_id as item_pack_size_id,
	stakeholder_activities.activity AS activityname,
	placement_summary.batch_number,
	placement_summary.stock_batch_warehouse_id,
	placement_summary.vvm_stage,
	IFNULL(
		(
			SELECT
			
				SUM(placements.quantity) quantity
				
			FROM
				placements
			INNER JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
			INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
			INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
			INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
			INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
			INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
			INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
			INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
			INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
			INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
			WHERE
				stock_batch_warehouses.pk_id = $batch_id
			AND Date_format(placements.created_date,'%Y-%m-%d') <= '$transfer_date'
		),
		0
	) AS quantity,
	placement_summary.placement_location_id,
	placement_locations.location_type,
	placement_locations.location_id,
	vvm_stages.vvm_stage_value,
	item_pack_sizes.vvm_group_id,
	item_activities.stakeholder_activity_id
        FROM
                placement_summary
            INNER JOIN stock_batch_warehouses ON placement_summary.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
            INNER JOIN placement_locations ON placement_summary.placement_location_id = placement_locations.pk_id
            INNER JOIN vvm_stages ON placement_summary.vvm_stage = vvm_stages.pk_id
            WHERE
	stock_batch_warehouses.pk_id = $batch_id";


        // echo $str_sql;
        //exit;
        $rec = $this->_em->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Add Stock Detail1 Validation
     * @param type $array
     * @return type
     */
    public function addStockDetail1Validation($array) {
        $form_values = $array;
        $end = $form_values['counter'];
        $number = array();
        $error_array = array();
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            if ($row['quantity'] > 0) {
                if (!in_array($row['number'], $number)) {
                    $number[] = $row['number'];
                } else {

                    $error_array[] = $row['item_pack_size_id'];
                }
            }
        }
        if (empty($error_array)) {

            $this->addStockDetail1($array);
        } else {
            return $error_array;
        }
    }

    /**
     * Add Stock Detail1
     * @param type $array
     * @return type
     */
    public function addStockDetail1($array) {

        $action = Zend_Registry::get("action");
        $form_values = $array;
        $end = $form_values['counter'];
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            if ($row['quantity'] > 0) {
                $stock_detail = new StockDetail();
                $type = $array['transaction_type_id'];
                $quantity = str_replace(",", "", $row['quantity']);
                if ($type == Model_TransactionTypes::TRANSACTION_ISSUE) {
                    $quantity = "-" . $quantity;
                }
                $stock_id = $array['stock_master_id'];
                $stock_master_id = $this->_em->getRepository('StockMaster')->find($stock_id);
                $stock_detail->setStockMaster($stock_master_id);
                $row_item = $this->_em->getRepository('ItemPackSizes')->find($row['item_pack_size_id']);
                $item_unit = $row_item->getItemUnit()->getPkId();
                if (!empty($item_unit)) {
                    $item_unit_id = $this->_em->getRepository('ItemUnits')->find($item_unit);
                    $stock_detail->setItemUnit($item_unit_id);
                }
                $stock_detail->setQuantity($quantity);
                $stock_detail->setTemporary(0);
                if ($action == 'add-stock-issue') {
                    list($location, $vvm) = explode("|", trim($row['hdn_vvm_stage']));
                    if ($vvm == 0) {
                        $row['hdn_vvm_stage'] = 0;
                    } else if (!empty($vvm)) {
                        $row['hdn_vvm_stage'] = $vvm;
                    } else {
                        $row['hdn_vvm_stage'] = $location;
                    }
                }
                $vvms = $this->_em->getRepository("VvmStages")->find($row['hdn_vvm_stage']);
                $stock_detail->setVvmStage($vvms);
                $stock_detail->setAdjustmentType("$type");
                if ($type == 1) {
                    $str_sql = $this->_em_read->createQueryBuilder()
                            ->select("sbw.pkId")
                            ->from('StockBatchWarehouses', 'sbw')
                            ->join('sbw.stockBatch', 'sb')
                            ->join('sb.packInfo', 'pi')
                            ->join('pi.stakeholderItemPackSize', 'sip')
                            ->where("sip.itemPackSize = '" . $row['item_id'] . "' ")
                            ->andWhere("sb.number = '" . $row['number'] . "'  ")
                            ->andWhere("sbw.warehouse =  '" . $this->_identity->getWarehouseId() . "' ");
                    //this IF is for stock receive
                    $row_q = $str_sql->getQuery()->getResult();
                    $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($row_q['0']['pkId']);
                    $stock_detail->setStockBatchWarehouse($stock_batch_id);
                    $stock_detail->setIsReceived(1);
                } else if ($type == 2) {
                    //this ELSE IF is for stock issue
                    $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($row['number']);
                    $stock_detail->setStockBatchWarehouse($stock_batch_id);
                    $stock_detail->setIsReceived(0);
                }
                $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                $stock_detail->setModifiedBy($created_by);
                $stock_detail->setModifiedDate(App_Tools_Time::now());
                $stock_detail->setCreatedBy($created_by);
                $stock_detail->setCreatedDate(App_Tools_Time::now());
                $this->_em->persist($stock_detail);
                $this->_em->flush();
                $query = "SELECT AdjustQty(" . $stock_batch_id->getPkId() . "," . $this->_identity->getWarehouseId() . ") FROM DUAL";
                $str_sql = $this->_em->getConnection()->prepare($query);
                $str_sql->execute();
                if ($action == 'add-stock-issue' && !empty($location)) {
                    $placements = new Model_Placements();
                    $placements->form_values['placement_loc_id'] = $location;
                    $placements->form_values['batch_id'] = $row['number'];
                    $placements->form_values['vvmstage'] = $vvm;
                    $placements->form_values['is_placed'] = 1;
                    $placements->form_values['quantity'] = $quantity;
                    $placements->form_values['placement_loc_type_id'] = 115;
                    $placements->form_values['detail_id'] = $stock_detail->getPkId();
                    $placements->form_values['user_id'] = $this->_user_id;
                    $placements->form_values['created_date'] = date("Y-m-d");
                    $placements->addIssuePlacement();
                }
            }
        }


        return $stock_detail->getPkId();
    }

    /**
     * Add Stock Detail Temp Validation
     * @param type $array
     * @return type
     * @throws Exception
     */
    public function addStockDetailTempValidation($array) {
        $form_values = $array;
        $end = $form_values['counter'];
        $number = array();
        $error_array_batch = array();
        $error_array_quantity = array();
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            $quantity = str_replace(",", "", $row['quantity']);
            if ($quantity > 0) {
                $quantity_check[] = $quantity;
                if (!in_array($row['number'], $number)) {
                    $number[] = $row['number'];
                } else {
                    $error_array_batch[] = $row['item_pack_size_id'];
                }
                if ($quantity > $row['hdn_available_quantity']) {
                    $error_array_quantity[] = $row['item_pack_size_id'];
                }
            }
        }

        if (empty($error_array_batch) && empty($error_array_quantity) && !empty($quantity_check)) {
            return $this->addStockDetailTemp($array);
        } else {
            throw new RangeException('FALSE');
        }
    }

    /**
     * Add Stock Detail Temp
     * @param type $array
     * @return type
     */
    public function addStockDetailTemp($array) {

        $action = Zend_Registry::get("action");
        $form_values = $array;
        $end = $form_values['counter'];
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            $quantity = str_replace(",", "", $row['quantity']);
            if ($quantity > 0) {
                $stock_detail = new StockDetail();
                $type = $array['transaction_type_id'];
                $quantity = str_replace(",", "", $row['quantity']);
                if ($type == Model_TransactionTypes::TRANSACTION_ISSUE) {
                    $quantity = "-" . $quantity;
                }
                $stock_id = $array['stock_master_id'];
                $stock_master_id = $this->_em->getRepository('StockMaster')->find($stock_id);
                $stock_detail->setStockMaster($stock_master_id);
                $row_item = $this->_em->getRepository('ItemPackSizes')->find($row['item_pack_size_id']);
                $item_unit = $row_item->getItemUnit()->getPkId();
                if (!empty($item_unit)) {
                    $item_unit_id = $this->_em->getRepository('ItemUnits')->find($item_unit);
                    $stock_detail->setItemUnit($item_unit_id);
                }
                $stock_detail->setQuantity($quantity);
                if ($array['type'] != 's') {
                    $stock_detail->setTemporary(1);
                }
                if ($action == 'ajax-stock-issue-temp') {
                    list($location, $vvm) = explode("|", trim($row['hdn_vvm_stage']));
                    if ($vvm == 0) {
                        $row['hdn_vvm_stage'] = 0;
                    } else if (!empty($vvm)) {
                        $row['hdn_vvm_stage'] = $vvm;
                    } else {
                        $row['hdn_vvm_stage'] = $location;
                    }
                }
                $vvms = $this->_em->getRepository("VvmStages")->find($row['hdn_vvm_stage']);
                $stock_detail->setVvmStage($vvms);
                $stock_detail->setAdjustmentType("$type");
                if ($type == 1) {
                    $str_sql = $this->_em_read->createQueryBuilder()
                            ->select("sbw.pkId")
                            ->from('StockBatchWarehouses', 'sbw')
                            ->join('sbw.stockBatch', 'sb')
                            ->join('sb.packInfo', 'pi')
                            ->join('pi.stakeholderItemPackSize', 'sip')
                            ->where("sip.itemPackSize = '" . $row['item_id'] . "' ")
                            ->andWhere("sb.number = '" . $row['number'] . "'  ")
                            ->andWhere("sbw.warehouse =  '" . $this->_identity->getWarehouseId() . "' ");
                    //this IF is for stock receive
                    $row_q = $str_sql->getQuery()->getResult();
                    $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($row_q['0']['pkId']);
                    $stock_detail->setStockBatchWarehouse($stock_batch_id);
                    $stock_detail->setIsReceived(1);
                } else if ($type == 2) {
                    //this ELSE IF is for stock issue
                    $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($row['number']);
                    $stock_detail->setStockBatchWarehouse($stock_batch_id);
                    $stock_detail->setIsReceived(0);
                }
                $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                $stock_detail->setModifiedBy($created_by);
                $stock_detail->setModifiedDate(App_Tools_Time::now());
                $stock_detail->setCreatedBy($created_by);
                $stock_detail->setCreatedDate(App_Tools_Time::now());
                $this->_em->persist($stock_detail);
                $this->_em->flush();
                $query = "SELECT AdjustQty(" . $stock_batch_id->getPkId() . "," . $this->_identity->getWarehouseId() . ") FROM DUAL";
                $str_sql = $this->_em_read->getConnection()->prepare($query);
                $str_sql->execute();
            }
        }
        return $stock_detail->getPkId();
    }

    /**
     * Get Purpose Transfer History
     * @param type $product_id
     * @return boolean
     */
    public function getPurposeTransferHistory($product_id) {
        //  $transfer_date = App_Controller_Functions::dateToDbFormat($transfer_date);
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                            `from`.activity AS from_activity,
                            `to`.activity AS to_activity,
                            from_item.item_name AS from_item,
                            from_item.pk_id AS from_item_id,
                            to_item.item_name AS to_item,
                            to_item.pk_id AS to_item_id,
                            purpose_transfer_history.quantity,
                            DATE_FORMAT(
                                    purpose_transfer_history.created_date,
                                    '%d/%m/%Y'
                            ) AS date,
                            to_stock_batch.number
                    FROM
                            purpose_transfer_history
                    INNER JOIN stakeholder_activities AS `from` ON purpose_transfer_history.from_activity_id = `from`.pk_id
                    INNER JOIN stakeholder_activities AS `to` ON purpose_transfer_history.to_activity_id = `to`.pk_id
                    INNER JOIN stock_batch_warehouses AS from_batch ON purpose_transfer_history.from_stock_batch_warehouse_id = from_batch.pk_id
                    INNER JOIN stock_batch AS from_stock_batch ON from_stock_batch.pk_id = from_batch.stock_batch_id
                    INNER JOIN pack_info AS from_pack_info ON from_stock_batch.pack_info_id = from_pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes AS from_stakeholder ON from_pack_info.stakeholder_item_pack_size_id = from_stakeholder.pk_id
                    INNER JOIN item_pack_sizes AS from_item ON from_stakeholder.item_pack_size_id = from_item.pk_id
                    INNER JOIN stock_batch_warehouses AS to_batch ON purpose_transfer_history.to_stock_batch_warehouse_id = to_batch.pk_id
                    INNER JOIN stock_batch AS to_stock_batch ON to_stock_batch.pk_id = to_batch.stock_batch_id
                    INNER JOIN pack_info AS to_pack_info ON to_stock_batch.pack_info_id = to_pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes AS to_stakeholder ON to_pack_info.stakeholder_item_pack_size_id = to_stakeholder.pk_id
                    INNER JOIN item_pack_sizes AS to_item ON to_stakeholder.item_pack_size_id = to_item.pk_id
                    INNER JOIN transaction_types ON purpose_transfer_history.transaction_type_id = transaction_types.pk_id
                    WHERE
                            (from_stakeholder.item_pack_size_id = $product_id
                    OR to_stakeholder.item_pack_size_id = $product_id) AND
                        from_batch.warehouse_id = $wh_id AND
                        to_batch.warehouse_id = $wh_id

                    ORDER BY
                            purpose_transfer_history.created_date";
        $rec = $this->_em->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Opening Balance Purpose
     * @param type $product_id
     * @param type $batch_id
     * @return boolean
     */
    public function getOpeningBalancePurpose($product_id, $batch_id) {
        $str_sql_date = "SELECT
                        purpose_transfer_history.created_date
                FROM
                        purpose_transfer_history
                WHERE
                        purpose_transfer_history.from_stock_batch_warehouse_id = '$batch_id'
                ORDER BY purpose_transfer_history.pk_id LIMIT 1";
        $rec_date = $this->_em->getConnection()->prepare($str_sql_date);
        $rec_date->execute();
        $result_date = $rec_date->fetchAll();
        if (count($result_date) > 0) {
            $date_purpose = $result_date[0]['created_date'];
        } else {
            $date_purpose = date("Y-m-d");
        }
        $str_sql = "SELECT
        sum(stock_detail.quantity) as opening_balance
        FROM
        stock_detail
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id= stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN stock_master ON stock_master.pk_id = stock_detail.stock_master_id
        WHERE
                stakeholder_item_pack_sizes.item_pack_size_id = '$product_id'
        AND DATE_FORMAT( stock_master.created_date, '%Y-%m-%d' ) < '$date_purpose'
        AND stock_batch_warehouses.pk_id = '$batch_id'";
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
     * Get Closing Balance Purpose
     * @param type $product_id
     * @param type $batch_id
     * @return boolean
     */
    public function getClosingBalancePurpose($product_id, $batch_id) {
        $str_sql = "SELECT
            SUM(if(purpose_transfer_history.from_stock_batch_warehouse_id = '$batch_id',purpose_transfer_history.quantity,0)) as minus_quantity,
            SUM(if(purpose_transfer_history.to_stock_batch_warehouse_id = '$batch_id',purpose_transfer_history.quantity,0)) as sum_quantity
            FROM
            purpose_transfer_history
            WHERE
            (purpose_transfer_history.from_stock_batch_warehouse_id = $batch_id OR
            purpose_transfer_history.to_stock_batch_warehouse_id = $batch_id)";
        $rec = $this->_em->getConnection()->prepare($str_sql);
        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Get Vvm Transfer History
     * @param type $product_id
     * @return boolean
     */
    public function getVvmTransferHistory($product_id) {
        $str_sql = "SELECT
                        item_pack_sizes.item_name,
                        stock_batch.number,
                        vvm_transfer_history.from_vvm_stage_id,
                        vvm_transfer_history.to_vvm_stage_id,
                        vvm_transfer_history.quantity,
                        DATE_FORMAT(vvm_transfer_history.created_date,'%d/%m/%Y') created_date
                    FROM
                        vvm_transfer_history
                        INNER JOIN stock_batch_warehouses ON vvm_transfer_history.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN vvm_stages AS from_vvm ON vvm_transfer_history.from_vvm_stage_id = from_vvm.pk_id
                        INNER JOIN vvm_stages AS to_vvm ON vvm_transfer_history.to_vvm_stage_id = to_vvm.pk_id
                    WHERE
                        stakeholder_item_pack_sizes.item_pack_size_id = '$product_id'";
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
     * Add Stock Detail
     * @param type $array
     * @return type
     * @throws Exception
     */
    public function addStockDetailShipmentsReceive($array) {

        $action = Zend_Registry::get("action");
        if ($array['rcvedit'] == "Yes") {
            $stock_detail = $this->_em->getRepository("StockDetail")->find($array['stock_detail_id']);
        } else {
            $stock_detail = new StockDetail();
        }
        $type = $array['transaction_type_id'];

        if (!empty($array['hdn_stock_id'])) {
            $stock_id = $array['hdn_stock_id'];
        } else {
            $stock_id = $array['stock_master_id'];
        }

        $stock_master_id = $this->_em->getRepository('StockMaster')->find($stock_id);
        $stock_detail->setStockMaster($stock_master_id);
        if (!empty($array['item_unit_id'])) {
            $item_unit_id = $this->_em->getRepository('ItemUnits')->find($array['item_unit_id']);
            $stock_detail->setItemUnit($item_unit_id);
        }
        if ($array['type'] != 's') {
            $stock_detail->setTemporary(1);
        }
        $quantity = str_replace(",", "", $array['quantity']);

        if ($action == 'issue' || $action == 'requisition-issue') {
            list($location, $vvm, $placd_qty) = explode("|", $array['vvm_stage']);
            if ($vvm == 0) {
                $array['vvm_stage'] = 0;
            } else if (!empty($vvm)) {
                $array['vvm_stage'] = $vvm;
            } else {
                $array['vvm_stage'] = $location;
            }
            if ($quantity > $placd_qty) {
                throw new RangeException('PLCD_QTY_GREATER_THAN_ISSUE_QTY');
            }
        }

        if (empty($array['vvm_stage'])) {
            $array['vvm_stage'] = 0;
        }

        //   if (!empty($array['vvm_stage'])) {
        $vvms = $this->_em->getRepository("VvmStages")->find($array['vvm_stage']);

        $stock_detail->setVvmStage($vvms);
        //  }

        if ($type == Model_TransactionTypes::TRANSACTION_ISSUE) {
            $quantity = "-" . $quantity;
        }
        $stock_detail->setQuantity($quantity);
        $stock_detail->setAdjustmentType("$type");
        if ($type == 1) {
            $str_sql = $this->_em->createQueryBuilder()
                    ->select("sbw.pkId")
                    ->from('StockBatchWarehouses', 'sbw')
                    ->join('sbw.stockBatch', 'sb')
                    ->join('sb.packInfo', 'pi')
                    ->join('pi.stakeholderItemPackSize', 'sip')
                    ->where("sip.itemPackSize = '" . $array['item_id'] . "' ")
                    ->andWhere("sb.number = '" . $array['number'] . "'  ")
                    ->andWhere("sbw.warehouse =  '" . $this->_identity->getWarehouseId() . "' ");
            //this IF is for stock receive
            $row = $str_sql->getQuery()->getResult();
            $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($row['0']['pkId']);
            $stock_detail->setStockBatchWarehouse($stock_batch_id);

            $stock_detail->setIsReceived(1);
        } else if ($type == 2) {
            //this ELSE IF is for stock issue
            $stock_batch_id = $this->_em->getRepository('StockBatchWarehouses')->find($array['stock_batch_id']);
            $stock_detail->setStockBatchWarehouse($stock_batch_id);
            $stock_detail->setIsReceived(0);
        }
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_detail->setModifiedBy($created_by);
        $stock_detail->setModifiedDate(App_Tools_Time::now());
        $stock_detail->setCreatedBy($created_by);
        $stock_detail->setCreatedDate(App_Tools_Time::now());
        $this->_em->persist($stock_detail);
        $this->_em->flush();
        if ($action == 'issue' && !empty($location)) {
            $placements = new Model_Placements();
            $placements->form_values['placement_loc_id'] = $location;
            $placements->form_values['batch_id'] = $array['number'];
            $placements->form_values['vvmstage'] = $vvm;
            $placements->form_values['is_placed'] = 1;
            $placements->form_values['quantity'] = $quantity;
            $placements->form_values['placement_loc_type_id'] = 115;
            $placements->form_values['detail_id'] = $stock_detail->getPkId();
            $placements->form_values['user_id'] = $this->_user_id;
            $placements->form_values['created_date'] = date("Y-m-d");
            $placements->add();
        }
        return $stock_detail->getPkId();
    }

}
