<?php

/**
 * Model_PipelineConsignments
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Pipeline Consignments
 */
class Model_PipelineConsignments extends Model_Base {

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
        $this->_table = $this->_em->getRepository('PipelineConsignments');
    }

    /**
     * Get By Search
     * @return type
     */
    public function getBySearch() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("fa")
                ->from("PipelineConsignments", "fa");
        if (!empty($this->form_values['pkId'])) {
            $str_sql->andWhere("fa.pkId = '" . $this->form_values['pkId'] . "' ");
        }
        if (!empty($this->form_values['voucher_number'])) {
            $str_sql->andWhere("fa.voucherNumber = '" . $this->form_values['voucher_number'] . "'");
        }
        if (!empty($this->form_values['source'])) {
            $str_sql->andWhere("fa.fromWarehouse = '" . $this->form_values['source'] . "'");
        }
        if (!empty($this->form_values['to_wh'])) {
            $str_sql->andWhere("fa.toWarehouse = '" . $this->form_values['to_wh'] . "'");
        }
        if (!empty($this->form_values['type'])) {
            $str_sql->andWhere("fa.transactionType = '" . $this->form_values['type'] . "'");
        }
        $str_sql->orderBy("fa.pkId", "ASC");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Pipeline To Warehouses
     * @return type
     */
    public function getPipelineToWarehouses() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT tw.pkId,tw.warehouseName')
                ->from("PipelineConsignments", "pc")
                ->join("pc.toWarehouse", "tw")
                ->andWhere("pc.fromWarehouse =" . $this->_identity->getWarehouseId())
                ->orderBy("tw.warehouseName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Update Pipeline Consignments Received Qty
     * @return type
     */
    public function updatePipelineConsignmentsReceivedQty() {
        $params = $this->form_values;
        $rec_id = $params['rec_id'];
        $batch_no = $params['batch_no'];
        $qty = str_replace(",", "", $params['qty']);
        $vvmstage = $params['vvmstage'];
        $is_update = $params['update'];
        $auth = $params['auth'];       
        $loctation_id=$params['location_id'];       
        $pipeline_consignments = $this->_em->getRepository("PipelineConsignments")->find($rec_id);
        if (count($pipeline_consignments) == 0) {         
            $result_msg = array("message" => "Selected record id $rec_id does not exist in pipeline consignments. Please update your data.");
            App_FileLogger::info($result_msg['message']);
            return $result_msg;
        }
        if ($is_update) {
            $consignments_placements = $this->_em->getRepository("PipelineConsignmentsPlacements")->findBy(array("pipelineConsignment" => $rec_id));
            if (count($consignments_placements) > 0) {
                foreach ($consignments_placements as $row) {
                    $this->_em->remove($row);
                }
                $this->_em->flush();
            }
        }
        $plac_loc_id = $this->_em->getRepository("PlacementLocations")->find($loctation_id);
        // Add entry in Placement table
        $placements = new PipelineConsignmentsPlacements();
        $placements->setBatchNumber($batch_no);
        $placements->setPipelineConsignment($pipeline_consignments);
        $placements->setPlacementLocation($plac_loc_id);
        $placements->setQuantity($qty);
        $vvms = $this->_em->getRepository("VvmStages")->find($vvmstage);
        $placements->setVvmStage($vvms);
        $created_by = $this->_em->getRepository("Users")->findOneBy((array('auth' => $auth)));
        $placements->setCreatedBy($created_by);
        $placements->setCreatedDate(App_Tools_Time::now());
        $placements->setModifiedBy($created_by);
        $placements->setModifiedDate(App_Tools_Time::now());
        
        $this->_em->persist($placements);
        $this->_em->flush();
        // Update Received Qty in Future Arrivals table
        $update_qty = $pipeline_consignments->getReceivedQuantity() + $qty;
        $pipeline_consignments->setReceivedQuantity($update_qty);
        $pipeline_consignments->setModifiedBy($created_by);
        $pipeline_consignments->setModifiedDate(App_Tools_Time::now());
       
        $this->_em->persist($pipeline_consignments);
        $this->_em->flush();
        return array("message" => "success", "master_id" => $pipeline_consignments->getPkId());
    }

    /**
     * Upload Pipeline Consignments
     * @return type
     */
    public function uploadPipelineConsignments() {
        $trans_type = $this->form_values['trans_type'];
        if ($trans_type == Model_TransactionTypes::TRANSACTION_RECIEVE) {
            $voucher = $this->uploadReceiveConsignments();
        }
        if ($trans_type == Model_TransactionTypes::TRANSACTION_ISSUE) {
            $voucher = $this->uploadIssueConsignments();
        }
        return $voucher;
    }

    /**
     * Upload Issue Consignments
     * @return type
     */
    public function uploadIssueConsignments() {
        $params = $this->form_values;
        $to_wh_id = $params['wh_id'];
        $from_wh_id = $params['from_wh_id'];
        $rec_id = $params['rec_id'];
        $qty = str_replace(",", "", $params['qty']);
        $voucher = $params['voucher'];
        $vvmstage = $params['vvmstage'];
        $consignments_location = $this->_em->getRepository("PipelineConsignmentsPlacements")->findBy(array("pipelineConsignment" => $rec_id));
        $str_qry = $this->_em_read->createQueryBuilder()
                ->select("pc.masterId")
                ->from("PipelineConsignments", "pc")
                ->where("pc.voucherNumber = '" . $voucher . "'")
                ->andWhere("pc.masterId > 0")
                ->andWhere("pc.fromWarehouse = " . $from_wh_id)
                ->andWhere("pc.toWarehouse = " . $to_wh_id);
        $stock_master_id = $str_qry->getQuery()->getResult();
        if (count($stock_master_id) > 0) {
            $master_id = $stock_master_id[0]['masterId'];
        } else {
            $master_id = 0;
        }
        $pipeline_consignments = $this->_em->getRepository("PipelineConsignments")->find($rec_id);
        if (count($pipeline_consignments) == 0) {         
            $result_msg = array("message" => "Selected record id $rec_id does not exist in planned issue list. Please update your data.");
            App_FileLogger::info($result_msg['message']);
            return $result_msg;
        }
        $created_by = $this->_em->find('Users', $this->_user_id);
        // Check if we need to create new voucher or add detail entry in existing voucher
        // If 0 then New Voucher         // If greater then 0 then Add in Existing voucher 
        if ($master_id == 0) {
            $tr_date = date("d/m/Y");
            $obj_master = new Model_StockMaster();
            $trans = $obj_master->getTransactionNumber(2, $tr_date, $from_wh_id);
            $stock_master = new StockMaster();
            $stock_master->setTransactionDate(new DateTime());
            $stock_master->setTransactionNumber($trans['trans_no']);
            $stock_master->setTransactionCounter($trans['id']);
            $stock_master->setTransactionReference($pipeline_consignments->getReferenceNumber());
            $stock_master->setDraft(0);
            $stock_master->setComments($pipeline_consignments->getDescription());
            $type = $this->_em->getRepository("TransactionTypes")->find(2);
            $stock_master->setTransactionType($type);
            $stock_master->setFromWarehouse($pipeline_consignments->getFromWarehouse());
            $stock_master->setToWarehouse($pipeline_consignments->getToWarehouse());
            $stock_master->setParentId(0);
            $stock_master->setStakeholderActivity($pipeline_consignments->getStakeholderActivity());
            $stock_master->setCreatedBy($pipeline_consignments->getCreatedBy());
            $stock_master->setCreatedDate(new DateTime(date("Y-m-d H:i:s")));
            $stock_master->setModifiedBy($created_by);
            $stock_master->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock_master);
            $this->_em->flush();
            $master_id = $stock_master->getPkId();
        }
        $stock_batch = $pipeline_consignments->getStockBatchWarehouse();
        if (count($consignments_location) > 0) {
            foreach ($consignments_location as $temp_plac) {
                // Add Entry in stock detail against selected master voucher
                $stock_detail = new StockDetail();
                $stock_detail->setQuantity($temp_plac->getQuantity());
                $stock_detail->setTemporary(0);
                $stock_detail->setVvmStage($temp_plac->getVvmStage());
                $stock_detail->setIsReceived(0);
                $stock_detail->setAdjustmentType(2);
                $stock_master = $this->_em->getRepository("StockMaster")->find($master_id);
                $stock_detail->setStockMaster($stock_master);
                $stock_detail->setStockBatchWarehouse($stock_batch);
                $stock_detail->setItemUnit($pipeline_consignments->getItemPackSize()->getItemUnit());
                $stock_detail->setCreatedBy($created_by);
                $stock_detail->setCreatedDate(App_Tools_Time::now());
                $stock_detail->setModifiedBy($created_by);
                $stock_detail->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($stock_detail);
                $this->_em->flush();
                $plac_loc_id = $temp_plac->getPlacementLocation();
                // Add entry in Placement table
                $placements = new Placements();
                $placements->setQuantity($temp_plac->getQuantity());
                $placements->setVvmStage($temp_plac->getVvmStage());
                $placements->setIsPlaced(1);
                $placements->setPlacementLocation($plac_loc_id);
                $placements->setStockBatchWarehouse($stock_batch);
                $trans_type = $this->_em->getRepository("ListDetail")->find(Model_ListDetail::STOCK_PLACEMENT);
                $placements->setPlacementTransactionType($trans_type);
                $placements->setCreatedBy($pipeline_consignments->getCreatedBy());
                $placements->setCreatedDate(new DateTime(date("Y-m-d H:i:s")));
                $placements->setModifiedBy($created_by);
                $placements->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($placements);
                $this->_em->flush();
            }
        } else {
            // Add Entry in stock detail against selected master voucher
            $stock_detail = new StockDetail();
            $stock_detail->setQuantity("-" . $qty);
            $stock_detail->setTemporary(0);
            $vvms = $this->_em->getRepository("VvmStages")->find($vvmstage);
            $stock_detail->setVvmStage($vvms);
            $stock_detail->setIsReceived(0);
            $stock_detail->setAdjustmentType(2);
            $stock_master = $this->_em->getRepository("StockMaster")->find($master_id);
            $stock_detail->setStockMaster($stock_master);
            $stock_detail->setStockBatchWarehouse($stock_batch);
            $stock_detail->setItemUnit($pipeline_consignments->getItemPackSize()->getItemUnit());
            $stock_detail->setCreatedBy($created_by);
            $stock_detail->setCreatedDate(App_Tools_Time::now());
            $stock_detail->setModifiedBy($created_by);
            $stock_detail->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock_detail);
            $this->_em->flush();
        }
        // Update Received Qty in Future Arrivals table
        $pipeline_consignments->setReceivedQuantity("-" . $qty);
        $pipeline_consignments->setMasterId($master_id);
        $pipeline_consignments->setModifiedBy($created_by);
        $pipeline_consignments->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($pipeline_consignments);
        $this->_em->flush();
        // Adjust Batch Quantity By Warehouse
        $this->adjustQuantityByWarehouse($stock_batch->getPkId(), $pipeline_consignments->getFromWarehouse()->getPkId());
        // Adjust Warehouse data for selected month and item
        $warehouse_data = new Model_HfDataMaster();
        $warehouse_data->form_values = array(
            'report_month' => date("m"),
            'report_year' => date("Y"),
            'item_id' => $pipeline_consignments->getItemPackSize()->getPkId(),
            'warehouse_id' => $pipeline_consignments->getFromWarehouse()->getPkId(),
            'created_by' => $pipeline_consignments->getCreatedBy()->getPkId()
        );
        $warehouse_data->adjustStockReport();
        $stock_master = $this->_em->getRepository("StockMaster")->find($master_id);
        return "V" . base64_encode($stock_master->getTransactionNumber() . "|" . $stock_master->getPkId());
    }

    /**
     * Upload Receive Consignments
     * @return type
     */
    public function uploadReceiveConsignments() {
        $params = $this->form_values;
        $wh_id = $params['wh_id'];
        $rec_id = $params['rec_id'];
        $batch_no = $params['batch_no'];
        $qty = str_replace(",", "", $params['qty']);
        $voucher = $params['voucher'];
        $consignments_location = $this->_em->getRepository("PipelineConsignmentsPlacements")->findBy(array("pipelineConsignment" => $rec_id));
        if (count($consignments_location) > 0) {
            foreach ($consignments_location as $cl) {
                $location_id[] = $cl->getPlacementLocation()->getPkId();
            }
        }
        $str_qry = $this->_em_read->createQueryBuilder()
                ->select("pc.masterId")
                ->from("PipelineConsignments", "pc")
                ->where("pc.voucherNumber = '" . $voucher . "'")
                ->andWhere("pc.masterId > 0")
                ->andWhere("pc.toWarehouse = " . $wh_id);
        $created_by = $this->_em->find('Users', $this->_user_id);
        $stock_master_id = $str_qry->getQuery()->getResult();
        if (count($stock_master_id) > 0) {
            $master_id = $stock_master_id[0]['masterId'];
        } else {
            $master_id = 0;
        }
        $pipeline_consignments = $this->_em->getRepository("PipelineConsignments")->find($rec_id);
        // Record must exists
        if (count($pipeline_consignments) == 0) {
            $result_msg = array("message" => "Selected record id $rec_id does not exist in pipeline consignments. Please update your data.");
            App_FileLogger::info($result_msg['message']);
            return $result_msg;
        }
        // Check if we need to create new voucher or add detail entry in existing voucher
        // If 0 then New Voucher     // If greater then 0 then Add in Existing voucher 
        if ($master_id == 0) {
            $tr_date = date("d/m/Y");
            $obj_master = new Model_StockMaster();
            $trans = $obj_master->getTransactionNumber(1, $tr_date, $wh_id);
            $stock_master = new StockMaster();
            $stock_master->setTransactionDate(new DateTime());
            $stock_master->setTransactionNumber($trans['trans_no']);
            $stock_master->setTransactionCounter($trans['id']);
            $stock_master->setTransactionReference($pipeline_consignments->getReferenceNumber());
            $stock_master->setDraft(0);
            $stock_master->setComments($pipeline_consignments->getDescription());
            $type = $this->_em->getRepository("TransactionTypes")->find(1);
            $stock_master->setTransactionType($type);
            $stock_master->setFromWarehouse($pipeline_consignments->getFromWarehouse());
            $stock_master->setToWarehouse($pipeline_consignments->getToWarehouse());
            $stock_master->setParentId(0);
            $stock_master->setStakeholderActivity($pipeline_consignments->getStakeholderActivity());
            $stock_master->setCreatedBy($pipeline_consignments->getCreatedBy());
            $stock_master->setCreatedDate(new DateTime(date("Y-m-d H:i:s")));
            $stock_master->setModifiedBy($pipeline_consignments->getCreatedBy());
            $stock_master->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock_master);
            $this->_em->flush();
            $master_id = $stock_master->getPkId();
        }
        $array = array("item_id" => $pipeline_consignments->getItemPackSize()->getPkId(), "number" => $batch_no, "wh_id" => $pipeline_consignments->getToWarehouse()->getPkId());
        $batch_id = $this->checkBatch($array);
        // Check if Batch exists or not
        // If 0 then create new batch        // If greater then 0 then add quantity in existing batch
        if ($batch_id === 0) {
            $stock_batch = new StockBatch();
            $stock_batch->setNumber(strtoupper($batch_no));
            $stock_batch->setExpiryDate($pipeline_consignments->getExpiryDate());
            $stock_batch->setUnitPrice(0);
            $stock_batch->setProductionDate($pipeline_consignments->getProductionDate());
            $vvm_type = $this->_em->getRepository("VvmTypes")->find(2);
            $stock_batch->setVvmType($vvm_type);
            $stock_batch->setPackInfo($pipeline_consignments->getManufacturer());
            $stock_batch->setCreatedBy($pipeline_consignments->getCreatedBy());
            $stock_batch->setCreatedDate(App_Tools_Time::now());
            $stock_batch->setModifiedBy($pipeline_consignments->getCreatedBy());
            $stock_batch->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock_batch);
            $this->_em->flush();
            $stock_batch_warehouses = new StockBatchWarehouses();
            $stock_batch_warehouses->setQuantity($qty);
            $stock_batch_warehouses->setStatus('Stacked');
            $stock_batch_warehouses->setStockBatch($stock_batch);
            $stock_batch_warehouses->setWarehouse($pipeline_consignments->getToWarehouse());
            $this->_em->persist($stock_batch_warehouses);
            $this->_em->flush();
            $batch_id = $stock_batch_warehouses->getPkId();
        }
        // Add Entry in stock detail against selected master voucher
        $stock_detail = new StockDetail();
        $stock_detail->setQuantity($qty);
        $stock_detail->setTemporary(0);
        $vvms = $this->_em->getRepository("VvmStages")->find(1);
        $stock_detail->setVvmStage($vvms);
        $stock_detail->setIsReceived(1);
        $stock_detail->setAdjustmentType(1);
        $stock_master = $this->_em->getRepository("StockMaster")->find($master_id);
        $stock_detail->setStockMaster($stock_master);
        $stock_batch = $this->_em->getRepository("StockBatchWarehouses")->find($batch_id);
        $stock_detail->setStockBatchWarehouse($stock_batch);
        $stock_detail->setItemUnit($pipeline_consignments->getItemPackSize()->getItemUnit());
        $stock_detail->setCreatedBy($pipeline_consignments->getCreatedBy());
        $stock_detail->setCreatedDate(App_Tools_Time::now());
        $stock_detail->setModifiedBy($pipeline_consignments->getCreatedBy());
        $stock_detail->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock_detail);
        $this->_em->flush();
        if (count($location_id) > 0) {
            foreach ($location_id as $plac_id) {
                $plac_loc_id = $this->_em->getRepository("PlacementLocations")->find($plac_id);
                // Add entry in Placement table
                $placements = new Placements();
                $placements->setQuantity($qty);
                $vvms = $this->_em->getRepository("VvmStages")->find(1);
                $placements->setVvmStage($vvms);
                $placements->setIsPlaced(1);
                $placements->setPlacementLocation($plac_loc_id);
                $placements->setStockBatchWarehouse($stock_batch);
                $trans_type = $this->_em->getRepository("ListDetail")->find(Model_ListDetail::STOCK_PLACEMENT);
                $placements->setPlacementTransactionType($trans_type);
                $placements->setCreatedBy($pipeline_consignments->getCreatedBy());
                $placements->setModifiedBy($pipeline_consignments->getCreatedBy());
                $placements->setCreatedDate(new DateTime(date("Y-m-d H:i:s")));
                $placements->setModifiedDate(new DateTime(date("Y-m-d H:i:s")));
                $this->_em->persist($placements);
                $this->_em->flush();
            }
        }
        // Update Received Qty in Future Arrivals table
        $pipeline_consignments->setReceivedQuantity($qty);
        $pipeline_consignments->setMasterId($master_id);
        $pipeline_consignments->setCreatedBy($created_by);
        $pipeline_consignments->setCreatedDate(App_Tools_Time::now());
        $pipeline_consignments->setModifiedBy($created_by);
        $pipeline_consignments->setModifiedDate(App_Tools_Time::now());
        $pipeline_consignments->setStatus('Receiving');
        $this->_em->persist($pipeline_consignments);
        $this->_em->flush();
        // Adjust Batch Quantity By Warehouse
        $this->adjustQuantityByWarehouse($batch_id, $pipeline_consignments->getToWarehouse()->getPkId());
        // Adjust Warehouse data for selected month and item
        $warehouse_data = new Model_HfDataMaster();
        $warehouse_data->form_values = array(
            'report_month' => date("m"),
            'report_year' => date("Y"),
            'item_id' => $pipeline_consignments->getItemPackSize()->getPkId(),
            'warehouse_id' => $pipeline_consignments->getToWarehouse()->getPkId(),
            'created_by' => $pipeline_consignments->getCreatedBy()->getPkId()
        );
        $warehouse_data->adjustStockReport();
        $stock_master = $this->_em->getRepository("StockMaster")->find($master_id);
        return "V" . base64_encode($stock_master->getTransactionNumber() . "|" . $stock_master->getPkId());
    }

    /**
     * Adjust Quantity By Warehouse
     * @param type $batch_id
     * @param type $wh_id
     */
    public function adjustQuantityByWarehouse($batch_id, $wh_id) {
        $row = $this->_em->getConnection()->prepare("SELECT AdjustQty($batch_id,$wh_id) from DUAL");
        $row->execute();
    }

    /**
     * Check Batch
     * @param type $array
     * @return int
     */
    public function checkBatch($array) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sbw.pkId")
                ->from("StockBatchWarehouses", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->where("sb.number = '" . $array['number'] . "'")
                ->andWhere("sip.itemPackSize = " . $array['item_id'])
                ->andWhere("sbw.warehouse = " . $array['wh_id']);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return 0;
        }
    }

    /**
     * Get Pipeline Consignments Draft
     * @return boolean
     */
    public function getPipelineConsignmentsDraft() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("fad")
                ->from("PipelineConsignmentsDraft", "fad")
                ->where("fad.toWarehouse = " . $this->_identity->getWarehouseId());
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Issue Voucher Items List
     * @param type $voucher
     * @param type $wh_id
     * @param type $limit
     * @return type
     */
    public function getIssueVoucherItemsList($voucher, $wh_id, $limit = 0) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("fa")
                ->from("PipelineConsignments", "fa")
                ->where("fa.fromWarehouse = " . $wh_id)
                ->andWhere("fa.voucherNumber = '$voucher'");
        $rows = $str_sql->getQuery()->getResult();
        if (count($rows) > 0) {
            $arr_data = array();
            foreach ($rows as $row) {
                $arr_data[] = array(
                    'record_id' => $row->getPkId(),
                    'trans_no' => $row->getVoucherNumber(),
                    'trans_counter' => $row->getTransactionCounter(),
                    'arrival_date' => $row->getExpectedArrivalDate()->format("Y-m-d"),
                    'reference_no' => $row->getReferenceNumber(),
                    'description' => $row->getDescription(),
                    'quantity_per_pack' => $row->getManufacturer()->getQuantityPerPack(),
                    'item_pack_size_id' => $row->getItemPackSize()->getPkId(),
                    'item_category' => $row->getItemPackSize()->getItemCategory()->getPkId(),
                    'item_name' => $row->getItemPackSize()->getItemName(),
                    'batch_no' => $row->getBatchNumber(),
                    'batch_id' => $row->getStockBatchWarehouse()->getPkId(),
                    'production_date' => ($row->getProductionDate() != null) ? $row->getProductionDate()->format("Y-m-d") : '',
                    'expiry_date' => $row->getExpiryDate()->format("Y-m-d"),
                    'manufacturer' => $row->getManufacturer()->getStakeholder()->getStakeholderName(),
                    'manufacturer_id' => $row->getManufacturer()->getStakeholder()->getPkId(),
                    'stakeholder_item_pack_size_id' => $row->getManufacturer()->getPkId(),
                    'quantity' => $row->getQuantity(),
                    'received_quantity' => $row->getReceivedQuantity(),
                    'from_wh_name' => $row->getFromWarehouse()->getWarehouseName(),
                    'from_wh_id' => $row->getFromWarehouse()->getPkId(),
                    'to_wh_id' => $row->getToWarehouse()->getPkId(),
                    'to_wh_name' => $row->getToWarehouse()->getWarehouseName()
                );
            }
            return $arr_data;
        } else {
            return array("message" => "No record found");
        }
    }

    /**
     * Get Pipeline Consignments By Wh
     * @param type $wh_id
     * @return type
     */
    public function getPipelineConsignmentsByWh($wh_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("fa")
                ->from("PipelineConsignments", "fa")
                ->where("fa.toWarehouse = " . $wh_id)
                ->andWhere("fa.transactionType = 1")
                ->andWhere("fa.status != 'Received'")
                ->andWhere("fa.itemPackSize IN (" . Zend_Registry::get('barcode_products') . ")");
        $rows = $str_sql->getQuery()->getResult();
        if (count($rows) > 0) {
            $arr_data = array();
            foreach ($rows as $row) {
                $arr_data[] = array(
                    'record_id' => $row->getPkId(),
                    'trans_no' => $row->getVoucherNumber(),
                    'arrival_date' => $row->getExpectedArrivalDate()->format("Y-m-d"),
                    'reference_no' => $row->getReferenceNumber(),
                    'description' => $row->getDescription(),
                    'quantity_per_pack' => $row->getManufacturer()->getQuantityPerPack(),
                    'item_pack_size_id' => $row->getItemPackSize()->getPkId(),
                    'item_category' => $row->getItemPackSize()->getItemCategory()->getPkId(),
                    'item_name' => $row->getItemPackSize()->getItemName(),
                    'batch_no' => $row->getBatchNumber(),
                    'batch_id' => ($row->getStockBatchWarehouse() != null) ? $row->getStockBatch()->getPkId() : 0,
                    'production_date' => ($row->getProductionDate() != null) ? $row->getProductionDate()->format("Y-m-d") : '',
                    'expiry_date' => $row->getExpiryDate()->format("Y-m-d"),
                    'manufacturer' => $row->getManufacturer()->getStakeholderItemPackSize()->getStakeholder()->getStakeholderName(),
                    'manufacturer_id' => $row->getManufacturer()->getPkId(),
                    'stakeholder_item_pack_size_id' => $row->getManufacturer()->getPkId(),
                    'quantity' => $row->getQuantity(),
                    'received_quantity' => ABS($row->getReceivedQuantity()),
                    'from_wh_name' => $row->getFromWarehouse()->getWarehouseName(),
                    'from_wh_id' => $row->getFromWarehouse()->getPkId(),
                    'to_wh_id' => $row->getToWarehouse()->getPkId(),
                    'to_wh_name' => $row->getToWarehouse()->getWarehouseName()
                );
            }
            return $arr_data;
        } else {
            return array(array("message" => "No record found"));
        }
    }

    /**
     * Get Planned Issue By Wh
     * @param type $wh_id
     * @return type
     */
    public function getPlannedIssueByWh($wh_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("fa")
                ->from("PipelineConsignments", "fa")
                ->where("fa.fromWarehouse = " . $wh_id)
                ->andWhere("fa.status != 'Received'")
                ->andWhere("fa.transactionType = " . Model_TransactionTypes::TRANSACTION_ISSUE . "")
                ->andWhere("fa.itemPackSize IN (" . Zend_Registry::get('barcode_products') . ")");
        $rows = $str_sql->getQuery()->getResult();
        if (count($rows) > 0) {
            $arr_data = array();
            foreach ($rows as $row) {
                $arr_data[] = array(
                    'record_id' => $row->getPkId(),
                    'trans_no' => $row->getVoucherNumber(),
                    'arrival_date' => $row->getExpectedArrivalDate()->format("Y-m-d"),
                    'reference_no' => $row->getReferenceNumber(),
                    'description' => $row->getDescription(),
                    'quantity_per_pack' => $row->getManufacturer()->getQuantityPerPack(),
                    'item_pack_size_id' => $row->getItemPackSize()->getPkId(),
                    'item_category' => $row->getItemPackSize()->getItemCategory()->getPkId(),
                    'item_name' => $row->getItemPackSize()->getItemName(),
                    'batch_no' => $row->getBatchNumber(),
                    'batch_id' => ($row->getStockBatchWarehouse() != null) ? $row->getStockBatchWarehouse()->getPkId() : 0,
                    'production_date' => ($row->getProductionDate() != null) ? $row->getProductionDate()->format("Y-m-d") : '',
                    'expiry_date' => $row->getExpiryDate()->format("Y-m-d"),
                    'manufacturer' => $row->getManufacturer()->getStakeholderItemPackSize()->getStakeholder()->getStakeholderName(),
                    'manufacturer_id' => $row->getManufacturer()->getStakeholderItemPackSize()->getStakeholder()->getPkId(),
                    'stakeholder_item_pack_size_id' => $row->getManufacturer()->getPkId(),
                    'quantity' => $row->getQuantity(),
                    'received_quantity' => ABS($row->getReceivedQuantity()),
                    'from_wh_name' => $row->getFromWarehouse()->getWarehouseName(),
                    'from_wh_id' => $row->getFromWarehouse()->getPkId(),
                    'to_wh_id' => $row->getToWarehouse()->getPkId(),
                    'to_wh_name' => $row->getToWarehouse()->getWarehouseName()
                );
            }
            return $arr_data;
        } else {
            return array(array("message" => "No record found"));
        }
    }

    /**
     * Get Temprary Number
     * @param type $tr_date
     * @param type $type
     * @return type
     */
    public function getTempraryNumber($tr_date, $type) {
        $date = explode(" ", $tr_date);
        //list($dd, $current_month, $current_year)
        $current_date = explode("/", $date[0]);
        $current_year = $current_date[2];
        $current_month = $current_date[1];
        $from_date = $current_year . "-" . $current_month . "-01";
        $to_date = $current_year . "-" . $current_month . "-31";
        $last_id = $this->getLastIDPipelineConsignments($from_date, $to_date, $type);
        if ($last_id == NULL) {
            $last_id = 0;
        }
        $last_id += 1;
        if ($type == 1) {
            return array("id" => $last_id, "temp_no" => "TR" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT));
        } else {
            return array("id" => $last_id, "temp_no" => "TI" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT));
        }
    }

    /**
     * Get Last ID Pipeline Consignments
     * @param type $from
     * @param type $to
     * @param type $type
     * @return boolean
     */
    public function getLastIDPipelineConsignments($from, $to, $type) {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MAX(fa.transactionCounter) as Maxtr')
                ->from("PipelineConsignments", "fa")
                ->where("fa.expectedArrivalDate between '" . $from . "' AND '" . $to . "'");
        if ($type == 1) {
            $str_sql->andWhere("fa.toWarehouse =  $wh_id ");
        } else {
            $str_sql->andWhere("fa.fromWarehouse =  $wh_id ");
        }
        $str_sql->andWhere("fa.transactionType =  $type ");
        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
    }

    /**
     * Insert Pipeline Consignments
     * @return type
     */
    public function insertPipelineConsignments() {
        $params = $this->form_values;
        $wh_id = $params['wh_id'];
        $batch_no = $params['batch_no'];
        $expiry_date = $params['expiry_date'];
        $qty = str_replace(",", "", $params['qty']);
        $location_id = $params['location_id'];
        $voucher = $params['voucher'];
        $farr = $this->_em->getRepository("PipelineConsignments")->findOneBy(array("voucherNumber" => $voucher, "batchNumber" => $batch_no));
        $created_by = $this->_em->find('Users', $this->_user_id);
        if (count($farr) == 0) {
            $pipeline_consignments = $this->_em->getRepository("PipelineConsignments")->findOneBy(array("voucherNumber" => $voucher, "toWarehouse" => $wh_id));
            if (count($pipeline_consignments) > 0) {
                $farr = new PipelineConsignments();
                $farr->setVoucherNumber($pipeline_consignments->getVoucherNumber());
                $farr->setTransactionCounter($pipeline_consignments->getTransactionCounter());
                $farr->setExpectedArrivalDate($pipeline_consignments->getExpectedArrivalDate());
                $farr->setReferenceNumber($pipeline_consignments->getReferenceNumber());
                $farr->setStakeholderActivity($pipeline_consignments->getStakeholderActivity());
                $farr->setDescription($pipeline_consignments->getDescription());
                $farr->setItemPackSize($pipeline_consignments->getItemPackSize());
                $farr->setBatchNumber($batch_no);
                $farr->setExpiryDate(new DateTime($expiry_date));
                $farr->setManufacturer($pipeline_consignments->getManufacturer());
                $vvm_type = $this->_em->getRepository("VvmTypes")->find(1);
                $farr->setVvmType($vvm_type);
                $farr->setUnitPrice($pipeline_consignments->getUnitPrice());
                $farr->setQuantity($qty);
                $farr->setReceivedQuantity($qty);
                $farr->setFromWarehouse($pipeline_consignments->getFromWarehouse());
                $farr->setToWarehouse($pipeline_consignments->getToWarehouse());
                $farr->setModifiedBy($pipeline_consignments->getCreatedBy());
                $farr->setCreatedBy($pipeline_consignments->getCreatedBy());
                $farr->setCreatedDate($pipeline_consignments->getCreatedDate());
                $farr->setMasterId(0);
                $farr->setStatus('Planned');
                $this->_em->persist($farr);
                $this->_em->flush();
            }
        }
        $plac_loc_id = $this->_em->getRepository("PlacementLocations")->find($location_id);
        // Add entry in Placement table
        $placements = new PipelineConsignmentsPlacements();
        $placements->setBatchNumber($batch_no);
        $placements->setPipelineConsignment($farr);
        $placements->setPlacementLocation($plac_loc_id);
        $placements->setQuantity($qty);
        $placements->setCreatedBy($created_by);
        $placements->setCreatedDate(App_Tools_Time::now());
        $placements->setModifiedBy($created_by);
        $placements->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($placements);
        $this->_em->flush();
        return array("message" => "success", "master_id" => $farr->getPkId());
    }

    /**
     * Add Planned Issue
     * @return boolean
     */
    public function addPlannedIssue() {
        $form_values = $this->form_values;
        $end = $form_values['counter'];
        $furute_arrival = new Model_PipelineConsignments();
        $vouchers = $furute_arrival->getTempraryNumber($form_values['transaction_date'], 2);
        if (!empty($form_values['voucher'])) {
            $vouchers['temp_no'] = $form_values['voucher'];
            $vouchers['id'] = $form_values['trans_id'];
        }
        $counter = 0;
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            if (!empty($row['item_id']) && !empty($row['number']) && !empty($row['quantity'])) {
                $farr = new PipelineConsignments();
                $farr->setVoucherNumber($vouchers['temp_no']);
                $farr->setTransactionCounter($vouchers['id']);
                $farr->setExpectedArrivalDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['transaction_date'])));
                $farr->setReferenceNumber($form_values['reference_number']);
                $activity = $this->_em->getRepository("StakeholderActivities")->find($form_values['stakeholder_activity_id']);
                $farr->setStakeholderActivity($activity);
                $farr->setDescription($form_values['description']);
                $item_pack_size = $this->_em->getRepository("ItemPackSizes")->find($row['item_id']);
                $farr->setItemPackSize($item_pack_size);
                $stock_batch = $this->_em->getRepository("StockBatchWarehouses")->find($row['number']);
                $farr->setStockBatchWarehouse($stock_batch);
                $farr->setBatchNumber($stock_batch->getStockBatch()->getNumber());
                $farr->setProductionDate($stock_batch->getStockBatch()->getProductionDate());
                $farr->setExpiryDate($stock_batch->getStockBatch()->getExpiryDate());
                $farr->setManufacturer($stock_batch->getStockBatch()->getPackInfo());
                $farr->setVvmType($stock_batch->getStockBatch()->getVvmType());
                $farr->setUnitPrice($stock_batch->getStockBatch()->getUnitPrice());
                $quantity = str_replace(",", "", $row['quantity']);
                $farr->setQuantity($quantity);
                $farr->setReceivedQuantity(0);
                $from_wh = $this->_em->getRepository("Warehouses")->find($this->_identity->getWarehouseId());
                $farr->setFromWarehouse($from_wh);
                $to_wh = $this->_em->getRepository("Warehouses")->find($form_values['warehouse']);
                $farr->setToWarehouse($to_wh);
                $user = $this->_em->getRepository("Users")->find($this->_user_id);
                $farr->setMasterId(0);
                $farr->setStatus('Planned');
                $tr_type = $this->_em->getRepository("TransactionTypes")->find(2);
                $farr->setTransactionType($tr_type);
                $farr->setCreatedBy($user);
                $farr->setCreatedDate(App_Tools_Time::now());
                $farr->setModifiedBy($user);
                $farr->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($farr);
                $counter++;
            }
        }
        $this->_em->flush();
        if ($counter >= 1) {
            // Delete Draft entries
            $rs = $this->_em->getRepository('PipelineConsignmentsDraft')->findBy(array('fromWarehouse' => $this->_identity->getWarehouseId(), "transactionType" => 2));
            foreach ($rs as $ro) {
                $this->_em->remove($ro);
            }
            $this->_em->flush();
            return $farr->getVoucherNumber();
        }
        return false;
    }

    /**
     * Add Pipeline Consignments
     * @return boolean
     */
    public function addPipelineConsignments() {
        $form_values = $this->form_values;
        $end = $form_values['counter'];
        $furute_arrival = new Model_PipelineConsignments();
        $vouchers = $furute_arrival->getTempraryNumber($form_values['expected_arrival_date'], 1);
        $counter = 0;
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            if (!empty($row['item_pack_size_id']) && !empty($row['batch_number']) && !empty($row['expiry_date']) && !empty($row['quantity'])) {
                $farr = new PipelineConsignments();
                $farr->setVoucherNumber($vouchers['temp_no']);
                $farr->setTransactionCounter($vouchers['id']);
                $farr->setExpectedArrivalDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['expected_arrival_date'])));
                $farr->setReferenceNumber($form_values['reference_number']);
                $activity = $this->_em->getRepository("StakeholderActivities")->find($form_values['stakeholder_activity_id']);
                $farr->setStakeholderActivity($activity);
                $farr->setDescription($form_values['description']);
                $item_pack_size = $this->_em->getRepository("ItemPackSizes")->find($row['item_pack_size_id']);
                $farr->setItemPackSize($item_pack_size);
                $farr->setBatchNumber($row['batch_number']);
                $farr->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($row['production_date'])));
                $farr->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($row['expiry_date'])));
                $manufacturer = $this->_em->getRepository("PackInfo")->findOneBy((array('stakeholderItemPackSize' => $row['manufacturer_id'])));
                $farr->setManufacturer($manufacturer);
                $vvm_type = $this->_em->getRepository("VvmTypes")->find($row['vvm_type_id']);
                $farr->setVvmType($vvm_type);
                $farr->setUnitPrice($row['unit_price']);
                $quantity = str_replace(",", "", $row['quantity']);
                $farr->setQuantity($quantity);
                $farr->setReceivedQuantity(0);
                $from_wh = $this->_em->getRepository("Warehouses")->find($form_values['from_warehouse_id']);
                $farr->setFromWarehouse($from_wh);
                $to_wh = $this->_em->getRepository("Warehouses")->find($this->_identity->getWarehouseId());
                $farr->setToWarehouse($to_wh);
                $user = $this->_em->getRepository("Users")->find($this->_user_id);
                $farr->setMasterId(0);
                $farr->setStatus('Planned');
                $tr_type = $this->_em->getRepository("TransactionTypes")->find(1);
                $farr->setTransactionType($tr_type);
                $farr->setCreatedBy($user);
                $farr->setCreatedDate(App_Tools_Time::now());
                $farr->setModifiedBy($user);
                $farr->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($farr);
                $counter++;
            }
        }
        $this->_em->flush();
        if ($counter >= 1) {
            // Delete Draft entries
            $rs = $this->_em->getRepository('PipelineConsignmentsDraft')->findBy(array('toWarehouse' => $this->_identity->getWarehouseId()));
            foreach ($rs as $ro) {
                $this->_em->remove($ro);
            }
            $this->_em->flush();
            return true;
        }
        return false;
    }

    /**
     * Add Pipeline Consignments Draft
     * @return boolean
     */
    public function addPipelineConsignmentsDraft() {
        $form_values = $this->form_values;
        $rs = $this->_em->getRepository('PipelineConsignmentsDraft')->findBy(array('toWarehouse' => $this->_identity->getWarehouseId()));
        foreach ($rs as $ro) {
            $this->_em->remove($ro);
        }
        $this->_em->flush();
        $end = $form_values['counter'];
        $counter = 0;
        for ($i = 0; $i < $end; $i++) {
            $row = $form_values["rows" . $i];
            if (!empty($row['item_pack_size_id']) && !empty($row['batch_number']) && !empty($row['expiry_date']) && !empty($row['quantity'])) {
                $farr = new PipelineConsignmentsDraft();
                $farr->setExpectedArrivalDate(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['expected_arrival_date'])));
                $farr->setReferenceNumber($form_values['reference_number']);
                $activity = $this->_em->getRepository("StakeholderActivities")->find($form_values['stakeholder_activity_id']);
                $farr->setStakeholderActivity($activity);
                $farr->setDescription($form_values['description']);
                $item_pack_size = $this->_em->getRepository("ItemPackSizes")->find($row['item_pack_size_id']);
                $farr->setItemPackSize($item_pack_size);
                $farr->setBatchNumber($row['batch_number']);
                $farr->setProductionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($row['production_date'])));
                $farr->setExpiryDate(new \DateTime(App_Controller_Functions::dateToDbFormat($row['expiry_date'])));
                $manufacturer = $this->_em->getRepository("StakeholderItemPackSizes")->find($row['manufacturer_id']);
                $farr->setManufacturer($manufacturer);
                $vvm_type = $this->_em->getRepository("VvmTypes")->find($row['vvm_type_id']);
                $farr->setVvmType($vvm_type);
                $farr->setUnitPrice($row['unit_price']);
                $quantity = str_replace(",", "", $row['quantity']);
                $farr->setQuantity($quantity);
                $from_wh = $this->_em->getRepository("Warehouses")->find($form_values['from_warehouse_id']);
                $farr->setFromWarehouse($from_wh);
                $to_wh = $this->_em->getRepository("Warehouses")->find($this->_identity->getWarehouseId());
                $farr->setToWarehouse($to_wh);
                $user = $this->_em->getRepository("Users")->find($this->_user_id);
                $farr->setCreatedBy($user);
                $farr->setCreatedDate(App_Tools_Time::now());
                $farr->setModifiedBy($user);
                $farr->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($farr);
                $counter++;
            }
        }
        $this->_em->flush();
        if ($counter >= 1) {
            return true;
        }
        return false;
    }

    /**
     * Get Distinct By Voucher Number
     * @return type
     */
    public function getDistinctByVoucherNumber() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT fa.voucherNumber, fa.pkId, warehouse.warehouseName,fa.referenceNumber, "
                        . "fa.expectedArrivalDate, fa.quantity, SUM(fa.receivedQuantity) as receivedQuantity, fa.status")
                ->from("PipelineConsignments", "fa")
                ->join("fa.fromWarehouse", 'warehouse')
                ->andWhere("fa.toWarehouse = " . $this->_identity->getWarehouseId());
        if (!empty($this->form_values['from_date']) && !empty($this->form_values['to_date'])) {
            $str_sql->andWhere("DATE_FORMAT(fa.expectedArrivalDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['to_date']) . "'");
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $str_sql->andWhere("DATE_FORMAT(fa.expectedArrivalDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
        }
        if (!empty($this->form_values['item_pack_size_id'])) {
            $str_sql->andWhere("fa.itemPackSize = '" . $this->form_values['item_pack_size_id'] . "'");
        }
        if (!empty($this->form_values['from_warehouse_id'])) {
            $str_sql->andWhere("fa.fromWarehouse = '" . $this->form_values['from_warehouse_id'] . "'");
        }
        if (!empty($this->form_values['status'])) {
            $str_sql->andWhere("fa.status = '" . $this->form_values['status'] . "'");
        } else {
            $str_sql->andWhere("fa.status = 'Planned' ");
        }
        $str_sql->groupBy("fa.voucherNumber");
        $str_sql->orderBy("fa.pkId", "DESC");
       // echo $str_sql->getQuery()->getSQL();exit;
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Distinct Issue By Voucher Number
     * @return type
     */
    public function getDistinctIssueByVoucherNumber() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT fa.voucherNumber, fa.pkId, warehouse.warehouseName,fa.referenceNumber, "
                        . "fa.expectedArrivalDate, fa.quantity, SUM(fa.receivedQuantity) as receivedQuantity, fa.status")
                ->from("PipelineConsignments", "fa")
                ->join("fa.toWarehouse", 'warehouse')
                ->andWhere("fa.fromWarehouse = " . $this->_identity->getWarehouseId());
        if (!empty($this->form_values['from_date']) && !empty($this->form_values['to_date'])) {
            $str_sql->andWhere("DATE_FORMAT(fa.expectedArrivalDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['to_date']) . "'");
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $str_sql->andWhere("DATE_FORMAT(fa.expectedArrivalDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
        }
        if (!empty($this->form_values['item_pack_size_id'])) {
            $str_sql->andWhere("fa.itemPackSize = '" . $this->form_values['item_pack_size_id'] . "'");
        }
        if (!empty($this->form_values['to_warehouse_id'])) {
            $str_sql->andWhere("fa.toWarehouse = '" . $this->form_values['to_warehouse_id'] . "'");
        }
        if (!empty($this->form_values['status'])) {
            $str_sql->andWhere("fa.status = '" . $this->form_values['status'] . "'");
        } else {
            $str_sql->andWhere("fa.status = 'Planned' ");
        }
        $str_sql->groupBy("fa.voucherNumber");
        $str_sql->orderBy("fa.pkId", "DESC");
        return $str_sql->getQuery()->getResult();
    }

}
