<?php

/**
 * Model_StockMaster
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Stock Master
 */
class Model_StockMaster extends Model_Base {

    /**
     * $_table
     * @var type
     */
    private $_table;

    const PURPOSE_POSITIVE = 16;
    const PURPOSE_NEGATIVE = 17;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('StockMaster');
    }

    /**
     * Add Stock Master
     *
     * @param type $array
     * @return type
     */
    public function addStockMaster($array) {
        if ($array['rcvedit'] == "Yes") {
            $stock_master = $this->_em->getRepository("StockMaster")->find($array['stock_master_id']);
        } else {
            $stock_master = new StockMaster();
        }

        $type = $array['transaction_type_id'];

        $time_arr = explode(' ', $array['transaction_date']);
        $time = date('H:i:s', strtotime($time_arr[1] . $time_arr[2]));

        $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($time_arr[0]) . '' . $time));
        $tran_type = $this->_em->getRepository('TransactionTypes')->find($type);
        $stock_master->setTransactionType($tran_type);
        $stock_master->setTransactionReference($array['transaction_reference']);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_master->setCreatedBy($created_by);
        $stock_master->setModifiedBy($created_by);
        $stock_master->setParentId(0);
        $stock_master->setCreatedDate(App_Tools_Time::now());
        $stock_master->setModifiedDate(App_Tools_Time::now());
        $activity_id = $this->_em->getRepository('StakeholderActivities')->find($array['activity_id']);
        $stock_master->setStakeholderActivity($activity_id);
        if (!empty($array['campaign_id'])) {
            $stock_master->setCampaignId($array['campaign_id']);
        }
        if (!empty($array['dispatch_by'])) {
            $stock_master->setDispatchBy($array['dispatch_by']);
        }
        if (!empty($array['shipment_id'])) {
            $shipment_id = $this->_em->getRepository('Shipments')->find($array['shipment_id']);
            $stock_master->setShipment($shipment_id);
        }

        if ($type == 1) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($array['from_warehouse_id']);
            $stock_master->setFromWarehouse($from_warehouse_id);
            $to_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $stock_master->setToWarehouse($to_warehouse_id);
        } else if ($type == 2) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $stock_master->setFromWarehouse($from_warehouse_id);
            $to_warehouse_id = $this->_em->getRepository('Warehouses')->find($array['warehouse']);
            $stock_master->setToWarehouse($to_warehouse_id);
        }

        $stock_master->setComments($array['comments']);
        $stock_master->setTransactionNumber('TEMP');
        $stock_master->setDraft(1);

        $this->_em->persist($stock_master);
        $this->_em->flush();

        // Code to link demand master, in case issuance against requisition
        if (isset($array['demand_id'])) {
            $demandMaster = $this->_em->getRepository('DemandMaster')->find($array['demand_id']);
            $demandMaster->setStockMaster($stock_master);
            $demandMaster->setDraft('3'); // Issued.

            $this->_em->persist($demandMaster);
            $this->_em->flush();
        }

        // end
        return $stock_master->getPkId();
    }

    /**
     * Get Last ID
     *
     * @param type $from
     * @param type $to
     * @param type $tr_type
     * @param type $wh_id
     * @return boolean
     */
    public function getLastID($from, $to, $tr_type, $wh_id = null) {

        if ($wh_id == null) {
            $wh_id = $this->_identity->getWarehouseId();
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MAX(sm.transactionCounter) as Maxtr')
                ->from("StockMaster", "sm")
                ->where("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') between '" . $from . "' and '" . $to . "'");

        if ($tr_type > 2) {
            $str_sql->andWhere("sm.transactionType > 2 ");
        } else {
            $str_sql->andWhere("sm.transactionType =  $tr_type ");
        }

        if ($tr_type == 1) {
            $str_sql->andWhere("sm.toWarehouse =  " . $wh_id);
        } else {
            $str_sql->andWhere("sm.fromWarehouse = " . $wh_id);
        }

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
    }

    /**
     * Get Stock Last Date
     *
     * @param type $wh_id
     * @return type
     */
    public function getStockLastDate($wh_id) {

        $form_values = $this->form_values;
        $date = $form_values['year'] . "-" . str_pad($form_values['month'], 2, '0', STR_PAD_LEFT);
        $str_sql = "SELECT
                        DATE_FORMAT(MAX(
                                stock_master.transaction_date
                        ),'%d, %M %Y') AS trans_date
                FROM
                        stock_detail
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                AND DATE_FORMAT(
                        stock_master.transaction_date,
                        '%Y-%m'
                ) <= '$date'";


        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        return $data[0]['trans_date'];
    }

    /**
     * Get Last ID Except Me
     *
     * @param type $from
     * @param type $to
     * @param type $tr_type
     * @param type $trans_id
     * @param type $wh_id
     * @return boolean
     */
    public function getLastIDExceptMe($from, $to, $tr_type, $trans_id, $wh_id = null) {

        if ($wh_id == null) {
            $wh_id = $this->_identity->getWarehouseId();
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MAX(sm.transactionCounter) as Maxtr')
                ->from("StockMaster", "sm")
                ->where("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') between '" . $from . "' and '" . $to . "'")
                ->andWhere("sm.transactionType =  $tr_type ")
                ->andWhere("sm.pkId !=  $trans_id ");

        if ($tr_type == 1) {
            $str_sql->andWhere("sm.toWarehouse =  " . $wh_id);
        } else {
            $str_sql->andWhere("sm.fromWarehouse = " . $wh_id);
        }


        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
    }

    /**
     * Get Transaction Number
     *
     * @param type $tr_type
     * @param type $tr_date
     * @param type $wh_id
     * @param type $trans_id
     * @return type
     */
    public function getTransactionNumber($tr_type, $tr_date, $wh_id = null, $trans_id = null) {

        $time_arr = explode(' ', $tr_date);

        $current_date = explode("/", $time_arr['0']);

        $current_month = $current_date[1];
        $current_year = $current_date[2];

        $from_date = $current_year . "-" . $current_month . "-01";
        $to_date = $current_year . "-" . $current_month . "-31";

        if ($trans_id > 0) {
            $last_id = $this->getLastIDExceptMe($from_date, $to_date, $tr_type, $trans_id, $wh_id);
        } else {
            $last_id = $this->getLastID($from_date, $to_date, $tr_type, $wh_id);
        }

        if ($last_id == NULL) {
            $last_id = 0;
        }

        $last_id += 1;

        if ($tr_type == 1) {
            return array(
                "id" => $last_id,
                "trans_no" => "R" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
            );
        }
        if ($tr_type == 2) {
            return array(
                "id" => $last_id,
                "trans_no" => "I" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
            );
        }
        if ($tr_type > 2) {
            return array(
                "id" => $last_id,
                "trans_no" => "A" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
            );
        }
        if ($tr_type == 'PS') {
            return array(
                "id" => $last_id,
                "trans_no" => "PS" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
            );
        }
    }

    /**
     * Get Unplaced Receive Voucher List
     *
     * @uses API Get Unplaced Receive Voucher List
     * @return type
     */
    public function getUnplacedReceiveVoucherList($wh_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT sm.pkId as transID, sm.transactionDate,
                        sm.transactionNumber,
                        ws.pkId as fromWHID,
                        ws.warehouseName as fromWHName,
                        tws.pkId as toWHID,
                        tws.warehouseName as toWHName')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sm.fromWarehouse", "ws")
                ->join("sm.toWarehouse", "tws")
                ->join("sm.transactionType", "tt")
                ->where("sm.transactionType = 1")
                ->andWhere("ws.status = 1");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Warehouses By Product
     *
     * @param type $product_id
     * @return type
     */
    public function getWarehousesByProduct($product_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT fw.pkId,fw.warehouseName')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sb")
                ->join("sm.fromWarehouse", "fw")
                ->join("fw.stakeholderOffice", "so")
                ->where("sm.transactionType = 2")
                ->andWhere("sb.itemPackSize = $product_id")
                ->andWhere("fw.status = 1")
                ->orderBy("so.pkId,fw.warehouseName");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Link Receive With Issue
     *
     * @return boolean
     */
    public function linkReceiveWithIssue() {
        $form_values = $this->form_values;

        $stock_detail_receive = $this->_em->getRepository("StockDetail")->find($form_values['receive_detail_id']);
        $stock_detail_receive->setIsReceived($form_values['issue_detail_id']);
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_detail_receive->setModifiedBy($user);
        $stock_detail_receive->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($stock_detail_receive);
        $this->_em->flush();

        $stock_detail_issue = $this->_em->getRepository("StockDetail")->find($form_values['issue_detail_id']);

        $datetime1 = date_create($stock_detail_issue->getStockMaster()->getTransactionDate()->format("Y-m-d"));
        $datetime2 = date_create($stock_detail_receive->getStockMaster()->getTransactionDate()->format("Y-m-d"));
        $interval = date_diff($datetime1, $datetime2);
        $days = $interval->format('%R%a');

//If Receive date greater then Issue date
        if ($days < 0) {
            $stock_master = $stock_detail_receive->getStockMaster();
            if ($stock_detail_issue->getStockMaster()->getTransactionDate()->format("Y-m") != $stock_detail_receive->getStockMaster()->getTransactionDate()->format("Y-m")) {
                $result = $this->getTransactionNumber(1, $stock_detail_issue->getStockMaster()->getTransactionDate()->format("d/m/Y h:i:s"), $stock_detail_receive->getStockMaster()->getToWarehouse()->getPkId());
                $stock_master->setTransactionNumber($result['trans_no']);
                $stock_master->setTransactionCounter($result['id']);
            }

            $stock_master->setTransactionDate($stock_detail_issue->getStockMaster()->getTransactionDate());
            $stock_master->setModifiedBy($user);
            $stock_master->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock_master);
            $this->_em->flush();
        }

        return true;
    }

    /**
     * Get All Warehouse Batches
     *
     * @return type
     */
    public function getAllWarehouseBatches() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT sb.pkId,sb.number')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sb")
                ->where("sm.fromWarehouse = " . $form_values['from_wh'])
                ->andWhere("sm.toWarehouse = " . $form_values['to_wh'])
                ->andWhere("sb.itemPackSize = " . $form_values['product'])
                ->andWhere("sm.transactionType = 2");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get To Warehouses By Product
     *
     * @param type $from_wh
     * @param type $product_id
     * @return type
     */
    public function getToWarehousesByProduct($from_wh, $product_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT fw.pkId,fw.warehouseName')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sb")
                ->join("sm.toWarehouse", "fw")
                ->join("fw.stakeholderOffice", "so")
                ->where("sm.transactionType = 2")
                ->andWhere("sb.itemPackSize = $product_id")
                ->andWhere("sm.fromWarehouse = $from_wh")
                ->andWhere("fw.status = 1")
                ->andWhere("so.pkId < 6")
                ->orderBy("so.pkId,fw.warehouseName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Unplaced Receive Voucher Items
     *
     * @uses API Get Unplaced Receive Voucher Items
     * @return type
     */
    public function getUnplacedReceiveVoucherItems($voucher, $wh_id) {
        $array_ids = array();
        $str_sql2 = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT sm.pkId')
                ->from("Placements", "p")
                ->join("p.stockDetail", "sd")
                ->join("sd.stockMaster", "sm");
        $array = $str_sql2->getQuery()->getResult();
        foreach ($array as $arr) {
            $array_ids[] = $arr['pkId'];
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT ips.pkId as itemId,
                        ips.itemName,
                        sb.pkId as batchId,
                        sd.pkId as detailId,
                        sb.number as batchNo,
                        sb.quantity')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sb")
                ->join("sb.itemPackSize", "ips")
                ->where("sm.transactionType = 1")
                ->andWhere("sm.transactionNumber = '$voucher'");
        if (!empty($wh_id)) {
            $str_sql->andWhere("sm.toWarehouse = :wh_id")->setParameter("wh_id", $wh_id);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Issue Voucher List
     *
     * @uses API Get Unplaced Receive Voucher List
     * @return type
     */
    public function getIssueVoucherList($wh_id) {
        $array_ids = array();
        $str_sql2 = $this->_em_read->createQueryBuilder()
                ->select('sm.pkId')
                ->from("Placements", "p")
                ->join("p.stockDetail", "sd")
                ->join("sd.stockMaster", "sm");
        $array = $str_sql2->getQuery()->getResult();
        foreach ($array as $arr) {
            $array_ids[] = $arr['pkId'];
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT sm.pkId as transID, sm.transactionDate,
                        sm.transactionNumber,
                        ws.pkId as fromWHID,
                        ws.warehouseName as fromWHName,
                        tws.pkId as toWHID,
                        tws.warehouseName as toWHName')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sm.fromWarehouse", "ws")
                ->join("sm.toWarehouse", "tws")
                ->join("sm.transactionType", "tt")
                ->where("sm.transactionType = 2")
                ->andWhere("ws.status = 1")
                ->andWhere("sm.pkId Not IN (:array_ids)")
                ->setParameter("array_ids", $array_ids);
        if (!empty($wh_id)) {
            $str_sql->andWhere("sm.fromWarehouse = :wh_id")->setParameter("wh_id", $wh_id);
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Issue Voucher Items
     *
     * @uses API Get Unplaced Receive Voucher Items
     * @return type
     */
    public function getIssueVoucherItems($voucher, $wh_id) {
        $array_ids = array();
        $str_sql2 = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT sm.pkId')
                ->from("Placements", "p")
                ->join("p.stockDetail", "sd")
                ->join("sd.stockMaster", "sm");
        $array = $str_sql2->getQuery()->getResult();
        foreach ($array as $arr) {
            $array_ids[] = $arr['pkId'];
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT ips.pkId as itemId,
                        ips.itemName,
                        sb.pkId as batchId,
                        sd.pkId as detailId,
                        sb.number as batchNo,
                        sb.quantity')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sb")
                ->join("sb.itemPackSize", "ips")
                ->where("sm.transactionType = 2")
                ->andWhere("sm.transactionNumber = '$voucher'");

        if (!empty($wh_id)) {
            $str_sql->andWhere("sm.fromWarehouse = :wh_id")->setParameter("wh_id", $wh_id);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Temp Stock
     *
     * @return boolean
     */
    public function getTempStock() {
        $str_sql = $this->_em->createQueryBuilder()
                ->select('sm.transactionNumber AS transaction_number,
                        sm.transactionReference AS transaction_reference,
                        sm.transactionDate AS transaction_date,
                        sm.pkId AS pk_id,
                        ws.pkId AS from_warehouse_id,
                        ws.warehouseName AS warehouse_name
                        ')
                ->from("StockMaster", "sm")
                ->join("sm.fromWarehouse", "ws")
                ->where("sm.createdBy = " . $this->_user_id)
                ->andWhere("ws.status = 1");


        // In case Issuance from Requisition.
        // Set stock master pk id condition so that only
        // those temp record loaded which are against the requisition issue.
        if (isset($this->form_values['demand_master_id'])) {
            $stock_master_id = 0; // set default id.
            $demandMaster = $this->_em->find('DemandMaster', $this->form_values['demand_master_id']);
            if ($demandMaster && $demandMaster->getStockMaster()) {
                $stock_master_id = $demandMaster->getStockMaster()->getPkId();
            }
            $str_sql->andWhere("sm.pkId =  " . $stock_master_id);
        } else {
            // Don't load temp requisition issue voucher in case of normal voucher issuance.
            $str_query = $this->_em->createQueryBuilder()
                    ->select('sm.pkId')
                    ->from("DemandMaster", "dm")
                    ->join("dm.stockMaster", "sm");
            $result = $str_query->getQuery()->getResult();

            if (count($result) > 0) {
                foreach ($result as $r) {
                    $string_array[] = $r['pkId'];
                }
                $string = implode(",", $string_array);
                $str_sql->andWhere("sm.pkId NOT IN($string)");
            }
        }

        if ($this->form_values['transaction_type_id'] == 1) {
            $str_sql->andWhere("sm.toWarehouse =  " . $this->_identity->getWarehouseId());
        } else {
            $str_sql->andWhere("sm.fromWarehouse = " . $this->_identity->getWarehouseId());
        }

        $str_sql->andWhere("sm.transactionType = " . $this->form_values['transaction_type_id'])
                ->andWhere("sm.draft = 1 ");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            $warehouses = new Model_Warehouses();
            $arr_data['warehouse_name'] = $warehouses->getWarehouseNameByWarehouseId($row[0]['from_warehouse_id']);
            $arr_data['transaction_date'] = App_Controller_Functions::dateToUserFormat($row[0]['transaction_date']);
            $arr_data['transaction_number'] = $row[0]['transaction_number'];
            $arr_data['transaction_reference'] = $row[0]['transaction_reference'];
            $arr_data['stock_id'] = $row[0]['pk_id'];
            return $arr_data;
        } else {
            return false;
        }
    }

    /**
     * Get Temp Stocks List
     *
     * @return boolean
     */
    public function getTempStocksList() {
        $str_sql = $this->_em->createQueryBuilder()
                ->select('
                        sm.transactionDate AS transaction_date,
                        sm.campaignId,
                        sd.pkId AS stock_detail_id,
                        sd.quantity,
                        vvm.pkId AS vvm_stage,
                        sbw.pkId AS stock_batch_id,
                        sb.number,
                        sbw.quantity as no_ofvials,
                        sb.unitPrice AS unit_price,
                        sb.productionDate AS production_date,
                        sb.expiryDate AS expiry_date,
                        ips.itemName AS item_name,
                        fw.warehouseName as from_warehouse,
                        tw.warehouseName as to_warehouse,
                        fw.pkId as from_warehouse_id,
                        tw.pkId as to_warehouse_id,
                        iu.itemUnitName AS item_unit_name,
                        sm.pkId AS stock_master_id,
                        sm.transactionNumber AS transaction_number,
                        sm.transactionReference AS transaction_reference,
                        a.pkId AS activity_id,
                        ips.numberOfDoses as description,
                        vt.vvmTypeName AS vvm_type_name,
                        vt.pkId AS vvm_type_id,
                        ic.pkId item_category,
                        s.stakeholderName as manufacturer
                        ')
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "sm")
                ->join("sm.fromWarehouse", "fw")
                ->join("sm.toWarehouse", "tw")
                ->join("sm.stakeholderActivity", "a")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->join("sip.stakeholder", "s")
                ->leftJoin("sb.vvmType", "vt")
                ->join("ips.itemUnit", "iu")
                ->join("ips.itemCategory", "ic")
                ->where("sd.temporary =  1")
                ->andWhere("sm.draft =  1")
                ->andWhere("fw.status =  1");


        // In case Issuance from Requisition.
        // Set stock master pk id condition so that only
        // those temp record loaded which are against the requisition issue.
        if (isset($this->form_values['demand_master_id'])) {
            $stock_master_id = 0; // set default id.
            $demandMaster = $this->_em->find('DemandMaster', $this->form_values['demand_master_id']);
            if ($demandMaster && $demandMaster->getStockMaster()) {
                $stock_master_id = $demandMaster->getStockMaster()->getPkId();
            }
            $str_sql->andWhere("sm.pkId =  " . $stock_master_id);
        } else {
            // Don't load temp requisition issue voucher in case of normal voucher issuance.
            $str_query = $this->_em->createQueryBuilder()
                    ->select('sm.pkId')
                    ->from("DemandMaster", "dm")
                    ->join("dm.stockMaster", "sm");
            $result = $str_query->getQuery()->getResult();

            if (count($result) > 0) {
                foreach ($result as $r) {
                    $string_array[] = $r['pkId'];
                }
                $string = implode(",", $string_array);
                $str_sql->andWhere("sm.pkId NOT IN($string)");
            }
        }

        if ($this->form_values['transaction_type_id'] == 1) {
            $str_sql->andWhere("sm.toWarehouse =  " . $this->_identity->getWarehouseId());
        } else {
            $str_sql->andWhere("sm.fromWarehouse = " . $this->_identity->getWarehouseId());
        }

        $str_sql->andWhere("sm.createdBy = " . $this->_user_id)
                ->andWhere("sm.transactionType = " . $this->form_values['transaction_type_id']);


        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Update Stock Master Temp
     *
     * @param type $id
     * @param type $comments
     * @return type
     */
    public function updateStockMasterTemp($id, $comments = null) {
        $stock = $this->_table->find($id);
        $trans = $this->getTransactionNumber($stock->getTransactionType()->getPkId(), $stock->getTransactionDate()->format("d/m/Y"), $this->_identity->getWarehouseId(), $stock->getPkId());
        $stock->setDraft(0);
        $stock->setTransactionCounter($trans['id']);
        $stock->setTransactionNumber($trans['trans_no']);
        $stock->setComments($comments);

        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock->setModifiedBy($user);
        $stock->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($stock);
        $this->_em->flush();
        return $trans['trans_no'];
    }

    /**
     * Update Stock Period
     *
     * @param type $id
     * @param type $array
     * @return boolean
     */
    public function updateStockPeriod($id, $array) {
        $stock = $this->_table->find($id);

        if ($array['issue_period'] != 'custom') {
            list($issue_from, $issue_to) = explode("-", $array['issue_period']);
            $array['issue_from'] = $issue_from;
            $array['issue_to'] = $issue_to;
        }

        if (!empty($array['issue_from']) && !empty($array['issue_to'])) {
            $stock->setIssueFrom(new \DateTime(App_Controller_Functions::dateToDbFormat($array['issue_from'])));
            $stock->setIssueTo(new \DateTime(App_Controller_Functions::dateToDbFormat($array['issue_to'])));
            $user = $this->_em->getRepository('Users')->find($this->_user_id);
            $stock->setModifiedBy($user);
            $stock->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($stock);
            $this->_em->flush();
        }

        return true;
    }

    /**
     * Delete Receive
     *
     * @param type $id
     * @return boolean
     */
    public function deleteReceive($id) {
        $stock_detail = $this->_em->getRepository("StockDetail")->find($id);
        /**
         * Delete relevent Adjustments if any
         */
        $stock_detail_adjustments = $this->_em->getRepository("StockDetail")->findBy(array("isReceived" => $id));
        if (count($stock_detail_adjustments) > 0) {
            foreach ($stock_detail_adjustments as $stk_dtl_adj) {
                $this->deleteAdjustment($stk_dtl_adj->getStockMaster()->getPkId());
            }
        }

        if (count($stock_detail) > 0) {
            $month = $stock_detail->getStockMaster()->getTransactionDate()->format("m");
            $year = $stock_detail->getStockMaster()->getTransactionDate()->format("Y");
            $item = $stock_detail->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId();
            $warehouse = $stock_detail->getStockBatchWarehouse()->getWarehouse()->getPkId();
            $user = $stock_detail->getStockMaster()->getCreatedBy()->getPkId();
            //$batch_id = $stock_detail->getStockBatchWarehouse()->getPkId();
            $master_id = $stock_detail->getStockMaster()->getPkId();
            $issue_detail_id = $stock_detail->getIsReceived();

            $this->_em->remove($stock_detail);
            $this->_em->flush();

            $issue_stock_detail = $this->_em->getRepository("StockDetail")->find($issue_detail_id);
            if (count($issue_stock_detail) > 0) {

                $issue_stock_detail->setIsReceived(0);
                $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                $issue_stock_detail->setModifiedBy($created_by);
                $issue_stock_detail->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($issue_stock_detail);
                $this->_em->flush();
            }

            $placements = $this->_em->getRepository("Placements")->findBy(array("stockDetail" => $id));
            if (count($placements) > 0) {
                foreach ($placements as $remplacements) {
                    $this->_em->remove($remplacements);
                }
                $this->_em->flush();
            }

            //$stock_batch = new Model_StockBatch;
            //$stock_batch->adjustQuantityByWarehouse($batch_id, $this->_identity->getWarehouseId());

            $this->deleteStockMaster($master_id);

            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->form_values['report_month'] = $month;
            $warehouse_data->form_values['report_year'] = $year;
            $warehouse_data->form_values['item_id'] = $item;
            $warehouse_data->form_values['warehouse_id'] = $warehouse;
            $warehouse_data->form_values['created_by'] = $user;
            $warehouse_data->adjustStockReport();
            return true;
        }

        return false;
    }

    /**
     * Get Item Detail From Stock
     *
     * @return boolean
     */
    public function getItemDetailFromStock() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sm.transactionDate as transaction_date,
                        ips.pkId as item_pack_size_id,
                        sb.expiryDate as expiry_date,
                        sd.quantity,
                        ips.numberOfDoses AS doses_per_unit
                        ')
                ->from("StockDetail", "sd")
                ->innerJoin("sd.stockMaster", "sm")
                ->innerJoin("sd.stockBatchWarehouse", "sbw")
                ->innerJoin("sbw.stockBatch", "sb")
                ->innerJoin("sb.packInfo", "pi")
                ->innerJoin("pi.stakeholderItemPackSize", "sip")
                ->innerJoin("sip.itemPackSize", "ips")
                ->where("sm.pkId = ?1 ");

        $str_sql->setParameter(1, $this->form_values['pk_id']);
        if ($this->form_values['from'] == 'wh') {
            $str_sql->andWhere("sd.isReceived = 1 ");
        }

        $rs = $str_sql->getQuery()->getResult();
        if (!empty($rs) && count($rs) > 0) {
            foreach ($rs as $row) {
                $array[] = array(
                    'transaction_date' => $row['transaction_date'],
                    'item_id' => $row['item_pack_size_id'],
                    'quantity' => $row['quantity'] * $row['doses_per_unit'],
                    'expiry_date' => $row['expiry_date']
                );
            }
            return $array;
        } else {
            return FALSE;
        }
    }

    /**
     * Get All Item Stock
     *
     * @return boolean
     */
    public function getAllItemStock() {
        $wh_id = $this->_identity->getWarehouseId();
        $where = array();
        $sa_select = '';
        if (!empty($this->form_values['number'])) {
            switch ($this->form_values['searchby']) {
                case 1:
                    $where[] = "s.transactionNumber = '" . $this->form_values['number'] . "'";
                    break;
                case 2:
                    $where[] = "s.transactionReference = '" . $this->form_values['number'] . "'";
                    break;
                case 3:
                    $where[] = "b.number = '" . $this->form_values['number'] . "'";
                    break;
                default :
                    break;
            }
        }

        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.fromWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        $sa_join = false;
        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_select = "a.pkId as activity_id,";
            $sa_join = true;
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        $where[] = "w.status=1";
        $where[] = "s.transactionType=1";
        $where[] = "sbw.warehouse = $wh_id";
        $where[] = "sd.temporary=0";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId as detailId,s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "w.warehouseName,b.number,sbw.pkId as batchId,w.pkId as fromWarehouseId,"
                        . "b.expiryDate,sd.quantity,"
                        . "i.itemUnitName,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName,"
                        . "st.stakeholderName,"
                        . $sa_select
                        . "p.numberOfDoses as description, DATE_FORMAT(s.createdDate,'%d/%m/%Y') createdDate")
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.stakeholder", "st")
                ->join("sip.itemPackSize", "p")
                ->join("s.fromWarehouse", "w");

        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }
        $str_sql->join("p.itemUnit", "i")
                ->where($where_s)
                ->orderBy("s.transactionNumber", "ASC");

        // echo $str_sql->getQuery()->getSql();
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Item
     *
     * @return boolean
     */
    public function getAllItem() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = "SELECT
        stock_master.transaction_number,
        stock_master.transaction_date,
        stock_detail.quantity AS quantity,
        stock_detail.pk_id AS detail_id,
        stock_detail.stock_batch_warehouse_id AS batch_id,
        stock_batch.number,
        GetPlaced(stock_detail.pk_id) AS plc_qty,
        item_pack_sizes.item_name,
        pack_info.quantity_per_pack AS quantity_per_pack
        FROM
        stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
        stock_master.transaction_type_id =" . Model_TransactionTypes::TRANSACTION_RECIEVE . " AND
        stock_master.to_warehouse_id =" . $wh_id . " AND item_pack_sizes.item_category_id IN (" . Model_ItemCategories::NONVACCINES . "," . Model_ItemCategories::DILUENT . ")"
                . " ORDER BY stock_master.transaction_date DESC ";

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
     * Get All Item Vaccines
     *
     * @return boolean
     */
    public function getAllItemVaccines() {
        $from_date = Zend_Registry::get('api_from_date');
        $to_date = date("Y-m");

        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                            stock_master.transaction_number,
                            stock_master.transaction_date,
                            stock_master.transaction_type_id,
                            stock_detail.quantity AS quantity,
                            stock_detail.pk_id AS detail_id,
                            stock_master.pk_id AS master_id,
                            stock_detail.stock_batch_warehouse_id AS batch_id,
                            stock_batch.number,
                            GetPlaced(stock_detail.pk_id) AS plc_qty,
                            item_pack_sizes.item_name,
                            pack_info.quantity_per_pack AS quantity_per_pack
                    FROM
                    stock_master
                    INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id

                    WHERE
                    stock_master.transaction_type_id IN (" . Model_TransactionTypes::TRANSACTION_RECIEVE . "," . Model_TransactionTypes::LOST_RECOVERED . "," . Model_TransactionTypes::PHYSICALLY_FOUND . ") AND
                    stock_master.to_warehouse_id =" . $wh_id . " AND item_pack_sizes.item_category_id IN (" . Model_ItemCategories::VACCINES . ")";
        $str_sql .= " AND DATE_FORMAT(stock_master.transaction_date,'%Y-%m') BETWEEN '$from_date' AND '$to_date'";

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
     * Get Temp Stock Issue
     *
     * @return boolean
     */
    public function getTempStockIssue() {
        $wh_id = $this->_identity->getWarehouseId();
        if ($this->form_values['searchby'] == 1) {
            $tranNo = 1;
        } else if ($this->form_values['searchby'] == 2) {
            $tranRef = 1;
        } else if ($this->form_values['searchby'] == 3) {
            $batchNo = 1;
        }

        if (!empty($tranNo)) {
            $where[] = "s.transactionNumber like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($batchNo)) {
            $where[] = "b.number like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($tranRef)) {
            $where[] = "s.transactionReference like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.toWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d')  BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $where[] = "s.transactionType=2";
        $where[] = "sbw.warehouse = $wh_id";
        $where[] = "sd.temporary=0";
        $where[] = "w.status=1";

        $sa_join = false;

        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_join = true;
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "tw.warehouseName,b.number,"
                        . "b.expiryDate,abs(sd.quantity) as quantity,"
                        . "i.itemUnitName,"
                        . "sd.pkId as detailId,"
                        . "p.pkId as packSizeId,"
                        . "p.itemName, "
                        . "p.numberOfDoses as description, IF(p.vvmGroup=1,vvm.pkId,vvm.vvmStageValue) vvmStage,stk.stakeholderName manufacturer")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.stakeholder", "stk")
                ->join("sbw.warehouse", "w")
                ->join("s.toWarehouse", "tw")
                ->join("sip.itemPackSize", "p")
                ->join("p.itemUnit", "i");
        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }
        $str_sql->where("$where_s");
        $str_sql->orderBy("p.listRank,s.transactionDate,tw.warehouseName");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Temp Stock Receive
     *
     * @return boolean
     */
    public function getTempStockReceive() {
        $wh_id = $this->_identity->getWarehouseId();
        if ($this->form_values['searchby'] == 1) {
            $tranNo = 1;
        } else if ($this->form_values['searchby'] == 2) {
            $tranRef = 1;
        } else if ($this->form_values['searchby'] == 3) {
            $batchNo = 1;
        }

        if (!empty($tranNo)) {
            $where[] = "s.transactionNumber like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($batchNo)) {
            $where[] = "b.number like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($tranRef)) {
            $where[] = "s.transactionReference like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.fromWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d')  BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $where[] = "s.transactionType=1";
        $where[] = "sbw.warehouse = $wh_id";
        $where[] = "sd.temporary=0";
        $where[] = "w.status=1";

        $sa_join = false;

        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_join = true;
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,s.pkId,s.transactionNumber,"
                        . "s.transactionReference,tw.warehouseName,b.number,"
                        . "b.expiryDate,ABS(sd.quantity) as quantity,"
                        . "i.itemUnitName,sd.pkId as detailId,"
                        . "p.pkId as packSizeId,p.itemName,"
                        . "p.numberOfDoses as description,IF(p.vvmGroup=1,vvm.pkId,vvm.vvmStageValue) vvmStage,stk.stakeholderName as manufacturer")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.warehouse", "w")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.stakeholder", "stk")
                ->join("s.fromWarehouse", "tw")
                ->join("sip.itemPackSize", "p")
                ->join("p.itemUnit", "i");

        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }
        $str_sql->where("$where_s");
        $str_sql->orderBy("p.listRank,s.transactionDate,tw.warehouseName");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Temp Stock Issue Summary
     *
     * @param type $type
     * @return boolean
     */
    public function getTempStockIssueSummary($type) {
        $wh_id = $this->_identity->getWarehouseId();

        if ($type == 'loc') {
            $groupBy = 'tw.pkId, p.itemName, s.transactionDate';
        } else if ($type == 'prod') {
            $groupBy = 'p.itemName, tw.pkId, s.transactionDate';
        }

        if ($this->form_values['searchby'] == 1) {
            $tranNo = 1;
        } else if ($this->form_values['searchby'] == 2) {
            $tranRef = 1;
        } else if ($this->form_values['searchby'] == 3) {
            $batchNo = 1;
        } else {
            $tranNo = "";
            $batchNo = "";
            $tranRef = "";
        }

        if (!empty($tranNo)) {
            $where[] = "s.transactionNumber like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($batchNo)) {
            $where[] = "b.number like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($tranRef)) {
            $where[] = "s.transactionReference like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.toWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $where[] = "s.transactionType=2";
        $where[] = "sbw.warehouse = $wh_id";
        $where[] = "sd.temporary=0";
        $where[] = "w.status=1";

        $sa_join = false;

        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_join = true;
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "tw.warehouseName,b.number,"
                        . "b.expiryDate,SUM(ABS(sd.quantity)) as quantity,"
                        . "i.itemUnitName,"
                        . "sd.pkId as detailId,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName,"
                        . "p.numberOfDoses as description,"
                        . "v.vvmTypeName, vvm.pkId as vvmStage, vvm.vvmStageValue")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sbw.warehouse", "w")
                ->join("s.toWarehouse", "tw")
                ->join("sip.itemPackSize", "p")
                ->leftjoin("p.itemUnit", "i")
                ->leftjoin("b.vvmType", "v");
        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }
        $str_sql->where("$where_s");
        $str_sql->groupBy("$groupBy");
        $str_sql->orderBy("s.transactionDate,tw.warehouseName");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Temp Stock Receive Summary
     *
     * @param type $type
     * @return boolean
     */
    public function getTempStockReceiveSummary($type) {
        $wh_id = $this->_identity->getWarehouseId();

        if ($type == 'loc') {
            $groupBy = 'w.warehouseName, p.itemName, s.transactionDate';
        } else if ($type == 'prod') {
            $groupBy = 'p.itemName, w.warehouseName, s.transactionDate';
        }

        if ($this->form_values['searchby'] == 1) {
            $tranNo = 1;
        } else if ($this->form_values['searchby'] == 2) {
            $tranRef = 1;
        } else if ($this->form_values['searchby'] == 3) {
            $batchNo = 1;
        } else {
            $tranNo = "";
            $batchNo = "";
            $tranRef = "";
        }

        if (!empty($tranNo)) {
            $where[] = "s.transactionNumber like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($batchNo)) {
            $where[] = "b.number like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($tranRef)) {
            $where[] = "s.transactionReference like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.fromWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $sa_join = false;
        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_join = true;
        }

        $where[] = "s.transactionType=1";
        $where[] = "sbw.warehouse = $wh_id";
        $where[] = "sd.temporary=0";
        $where[] = "w.status=1";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "tw.warehouseName,b.number,"
                        . "b.expiryDate,ABS(sd.quantity) as quantity,"
                        . "i.itemUnitName,"
                        . "sd.pkId as detailId,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName,"
                        . "p.numberOfDoses as description,"
                        . "v.vvmTypeName, vvm.pkId as vvmStage, vvm.vvmStageValue")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "p")
                ->leftjoin("p.itemUnit", "i")
                ->leftjoin("b.vvmType", "v")
                ->join("sbw.warehouse", "w")
                ->join("s.fromWarehouse", "tw");
        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }
        $str_sql->where("$where_s");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Stock Issue Search
     *
     * @return boolean
     */
    public function stockIssueSearch() {

        $wh_id = $this->_identity->getWarehouseId();
        $sa_select = '';

        if ($this->form_values['searchby'] == 1) {
            $tranNo = 1;
        } else if ($this->form_values['searchby'] == 2) {
            $tranRef = 1;
        } else if ($this->form_values['searchby'] == 3) {
            $batchNo = 1;
        } else {
            $tranNo = "";
            $batchNo = "";
            $tranRef = "";
        }
        if (!empty($tranNo)) {
            $where[] = "s.transactionNumber like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($batchNo)) {
            $where[] = "b.number like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($tranRef)) {
            $where[] = "s.transactionReference like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.toWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        $sa_join = false;
        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_select = "a.pkId as activity_id,";
            $sa_join = true;
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $where[] = "s.transactionType=2";
        $where[] = "sbw.warehouse = $wh_id";
        $where[] = "sd.temporary=0";
        $where[] = "w.status=1";

        if ($this->form_values['voucher_type'] == 2) {
            $where[] = "s.actionType = 2";
            $where[] = "sd.actionType = 3";
            $master = "s.masterId as pkId";
            $detail = "sd.detailId";
        } else {
            $master = "s.pkId";
            $detail = "sd.pkId";
        }

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                . " $master, s.transactionNumber,"
                . "s.transactionReference,"
                . "w.warehouseName,b.number,"
                . "b.expiryDate,ABS(sd.quantity) as quantity,sbw.pkId as batchId,"
                . "i.itemUnitName,"
                . "st.stakeholderName,"
                . $sa_select
                . "$detail as detailId,"
                . "p.pkId as itemPackSizeId,"
                . "p.itemName,DATE_FORMAT(s.createdDate,'%d/%m/%Y') createdDate");

        if ($this->form_values['voucher_type'] == 2) {
            $str_sql->from("StockDetailHistory", "sd")
                    ->join("sd.stockMaster", "s")
                    ->join("sd.stockBatchWarehouse", "sbw")
                    ->join("sbw.stockBatch", "b");
        } else {
            $str_sql->from("StockDetail", "sd")
                    ->join("sd.stockMaster", "s")
                    ->join("sd.stockBatchWarehouse", "sbw")
                    ->join("sbw.stockBatch", "b");
        }

        $str_sql->join("s.toWarehouse", "w")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.stakeholder", "st")
                ->join("sip.itemPackSize", "p");

        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }
        $str_sql->join("p.itemUnit", "i")
                ->where($where_s)
                ->orderBy("s.transactionNumber");


        $row = $str_sql->getQuery()->getResult();



        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Target Issuance Search
     *
     * @return type
     */
    public function targetIssuanceSearch() {

        if (!empty($this->form_values['month'])) {
            $m = $this->form_values['month'];
        } else {
            $m = 0;
        }
        if (!empty($this->form_values['year'])) {
            $y = $this->form_values['year'];
        } else {
            $y = 0;
        }
        if (!empty($this->form_values['warehouse_id'])) {
            $warehouse_id = $this->form_values['warehouse_id'];
        } else {
            $warehouse_id = 0;
        }

        $str_qry = "SELECT
                        i5_.item_name AS product,
                        epi_amc.amc AS 1_month_requirement,
                        epi_amc.amc * $m AS n_month_requirement,
                        i5_.number_of_doses,
                        ((Sum(ABS(s3_.quantity))) * i5_.number_of_doses) AS issuance,
                        ((epi_amc.amc * $m) - (Sum(ABS(s3_.quantity)))  * i5_.number_of_doses) AS n_month_balance
                    FROM
                        stock_detail AS s3_
                        INNER JOIN stock_master AS s0_ ON s3_.stock_master_id = s0_.pk_id
                        INNER JOIN stock_batch_warehouses ON s3_.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes AS i5_ ON stakeholder_item_pack_sizes.item_pack_size_id = i5_.pk_id
                        INNER JOIN warehouses AS w1_ ON s0_.to_warehouse_id = w1_.pk_id
                        INNER JOIN items ON i5_.item_id = items.pk_id
                        INNER JOIN item_units AS i4_ ON i5_.item_unit_id = i4_.pk_id
                        INNER JOIN epi_amc ON i5_.pk_id = epi_amc.item_id
                        AND w1_.pk_id = epi_amc.warehouse_id
                    WHERE
                        DATE_FORMAT(s0_.transaction_date, '%Y-%m') BETWEEN  '$y-01' AND '$y-$m'
                        AND epi_amc.amc_year = DATE_FORMAT(s0_.transaction_date, '%Y')
                        AND s0_.transaction_type_id = 2
                        AND w1_.pk_id = $warehouse_id
                        AND s3_.`temporary` = 0
                        AND w1_.`status` = 1
                        AND i5_.item_category_id = 1
                        AND s0_.stakeholder_activity_id = 1
                    GROUP BY
                        i5_.item_name";


        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Stock Adjustment
     *
     * @return boolean
     */
    public function stockAdjustment() {
        $wh_id = $this->_identity->getWarehouseId();

        if (!empty($this->form_values['adjustment_no'])) {
            $where[] = "s.transactionNumber = '" . $this->form_values['adjustment_no'] . "'";
        }
        if (!empty($this->form_values['adjustment_type'])) {
            $where[] = "s.transactionType = '" . $this->form_values['adjustment_type'] . "'";
        }
        if (!empty($wh_id)) {
            $where[] = "s.fromWarehouse  = '" . $wh_id . "'";
        }
        if (!empty($wh_id)) {
            $where[] = "s.toWarehouse   = '" . $wh_id . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['batch_no']) && $this->form_values['batch_no'] != 'null') {
            $where[] = "sbw.pkId = '" . $this->form_values['batch_no'] . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "s.transactionDate BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        }
        $where[] = "s.transactionType > 2";
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "s.comments,"
                        . "b.number,"
                        . "b.expiryDate,"
                        . "sbw.pkId as batch_id,"
                        . "sd.quantity,"
                        . "sd.pkId as detailId,"
                        . "sd.quantity,"
                        . "p.itemName,"
                        . "p.numberOfDoses,"
                        . "t.transactionTypeName")
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "p")
                ->join("s.transactionType", "t")
                ->where($where_s);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Adjusted Products
     *
     * @return boolean
     */
    public function getAdjustedProducts() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = "
            SELECT DISTINCT
                i4_.item_name AS itemName,
                i4_.pk_id AS pkId

            FROM
                stock_detail s2_
                    INNER JOIN stock_master s0_ ON s2_.stock_master_id = s0_.pk_id
                    INNER JOIN stock_batch_warehouses ON s2_.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes i4_  ON stakeholder_item_pack_sizes.item_pack_size_id = i4_ .pk_id

                    INNER JOIN item_units i3_ ON i4_.item_unit_id = i3_.pk_id
                    INNER JOIN transaction_types t5_ ON s0_.transaction_type_id = t5_.pk_id
            WHERE
                    s0_.from_warehouse_id = '$wh_id'
                    AND s0_.to_warehouse_id = '$wh_id'
                    AND i4_.pk_id NOT IN (25,26,31,42)
                    AND s0_.transaction_type_id > 2
            ORDER BY i4_.list_rank";

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
     * Stock Adjustment Search
     *
     * @return boolean
     */
    public function stockAdjustmentSearch() {
        $wh_id = $this->_identity->getWarehouseId();
        if (!empty($this->form_values['adjustment_no'])) {
            $where[] = "s.transactionNumber = '" . $this->form_values['adjustment_no'] . "'";
        }
        if (!empty($this->form_values['adjustment_type'])) {
            $where[] = "s.transactionType = '" . $this->form_values['adjustment_type'] . "'";
        }
        if (!empty($wh_id)) {
            $where[] = "s.fromWarehouse  = '" . $wh_id . "'";
        }
        if (!empty($wh_id)) {
            $where[] = "s.toWarehouse   = '" . $wh_id . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['batch_no'])) {
            $where[] = "sbw.pkId = '" . $this->form_values['batch_no'] . "'";
        }
        if (!empty($this->form_values['expiry_date'])) {
            $where[] = "DATE_FORMAT(b.expiryDate,'%Y-%m-%d') = '" . App_Controller_Functions::dateToDbFormat($this->form_values['expiry_date']) . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        $where[] = "s.transactionType NOT IN(1,2,16,17)";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "s.comments,"
                        . "b.number,"
                        . "b.expiryDate,"
                        . "sd.quantity,"
                        . "i.itemUnitName,"
                        . "sd.pkId as detailId,"
                        . "sd.quantity,"
                        . "p.pkId as packSizeId,"
                        . "p.itemName,"
                        . "t.transactionTypeName")
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "p")
                ->join("p.itemUnit", "i")
                ->join("s.transactionType", "t")
                ->where($where_s)
                ->orderBy("s.transactionNumber", "ASC");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Stock stockTransferSearch
     *
     * @return boolean
     */
    public function stockTransferSearch() {
        $wh_id = $this->_identity->getWarehouseId();
        if (!empty($this->form_values['adjustment_no'])) {
            $where[] = "s.transactionNumber = '" . $this->form_values['adjustment_no'] . "'";
        }
        $where[] = "s.transactionType IN (16,17)";

        if (!empty($wh_id)) {
            $where[] = "s.fromWarehouse  = '" . $wh_id . "'";
        }
        if (!empty($wh_id)) {
            $where[] = "s.toWarehouse   = '" . $wh_id . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sip.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        if (!empty($this->form_values['batch_no'])) {
            $where[] = "sbw.pkId = '" . $this->form_values['batch_no'] . "'";
        }
        if (!empty($this->form_values['expiry_date'])) {
            $where[] = "DATE_FORMAT(b.expiryDate,'%Y-%m-%d') = '" . App_Controller_Functions::dateToDbFormat($this->form_values['expiry_date']) . "'";
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.transactionDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "s.comments,"
                        . "b.number,"
                        . "b.expiryDate,"
                        . "sd.quantity,"
                        . "i.itemUnitName,"
                        . "sd.pkId as detailId,"
                        . "sd.quantity,"
                        . "p.pkId as packSizeId,"
                        . "p.itemName,"
                        . "sd.isReceived,"
                        . "t.pkId trTypeId,"
                        . "t.transactionTypeName")
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "p")
                ->join("p.itemUnit", "i")
                ->join("s.transactionType", "t")
                ->where($where_s)
                ->orderBy("s.transactionNumber", "ASC");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Expired Stock Report
     *
     * @return boolean
     */
    public function expiredStockReport() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = "SELECT
                i4_.item_name,
                ABS(SUM(s2_.quantity)) AS vails,
                DATE_FORMAT(s1_.expiry_date,'%d/%m/%Y') expiry_date,
                transaction_types.transaction_type_name as reason,
                s1_.number,
                ABS(SUM(s2_.quantity)) * i4_.number_of_doses AS doses
        FROM
                stock_detail s2_
        INNER JOIN stock_master s0_ ON s2_.stock_master_id = s0_.pk_id
        INNER JOIN stock_batch_warehouses ON s2_.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch s1_  ON s1_ .pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON s1_.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes i4_ ON stakeholder_item_pack_sizes.item_pack_size_id = i4_.pk_id
        INNER JOIN transaction_types ON s0_.transaction_type_id = transaction_types.pk_id
        WHERE
        stock_batch_warehouses.warehouse_id = $wh_id AND
        s0_.transaction_type_id IN (" . $this->form_values['adjustment_type'] . ") AND
        DATE_FORMAT(s0_.transaction_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'
        GROUP BY
                stakeholder_item_pack_sizes.item_pack_size_id,
                stock_batch_warehouses.pk_id,
                s1_.expiry_date
        ORDER BY
                i4_.list_rank ASC";

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
     * Get Stocks Receive List
     *
     * @param int $type
     * @return boolean
     */
    public function getStocksReceiveList($type = 1) {
        if (empty($type)) {
            $type = 1;
        }

        $str_sql = "SELECT
            s0_.pk_id AS detailId,
            s1_.transaction_date AS transactionDate,
            s1_.transaction_number AS transactionNumber,
            s1_.transaction_reference AS transactionReference,
            s1_.pk_id AS masterId,
            s1_.comments,
            w2_.warehouse_name AS warehouseName,
            sb.number AS number,
            s3_.pk_id AS batch_id,
            sb.expiry_date AS expiryDate,
            sb.production_date AS productionDate,
            s0_.quantity AS quantity,
            i4_.item_unit_name AS itemUnitName,
            i5_.pk_id AS itemPackSizeId,
            i5_.item_name AS itemName,
            i5_.number_of_doses AS description,
            v6_.vvm_type_name AS vvmTypeName,
            stock_master.parent_id AS stockMasterParentId,
            stakeholder_activities.activity,
            IF(i5_.vvm_group_id = 1, vvm_stages.pk_id, vvm_stages.vvm_stage_value) as vvmStage,
            stakeholders.stakeholder_name as manufacturer,
            `from`.pk_id fromWhId,
            sb.unit_price,
            (s0_.quantity*sb.unit_price) unitCost
        FROM
        stock_detail AS s0_
            INNER JOIN stock_master AS s1_ ON s0_.stock_master_id = s1_.pk_id
            INNER JOIN warehouses AS `from` ON s1_.from_warehouse_id = `from`.pk_id
            INNER JOIN stock_batch_warehouses AS s3_ ON s0_.stock_batch_warehouse_id = s3_.pk_id
            INNER JOIN warehouses AS w2_ ON s3_.warehouse_id = w2_.pk_id
            INNER JOIN stock_batch AS sb ON s3_.stock_batch_id = sb.pk_id
            INNER JOIN pack_info AS pi ON sb.pack_info_id= pi.pk_id
            INNER JOIN stakeholder_item_pack_sizes AS sip ON pi.stakeholder_item_pack_size_id= sip.pk_id
            INNER JOIN stakeholders ON stakeholders.pk_id = sip.stakeholder_id
            INNER JOIN item_pack_sizes AS i5_ ON sip.item_pack_size_id = i5_.pk_id
            INNER JOIN item_units AS i4_ ON i5_.item_unit_id = i4_.pk_id
            LEFT JOIN vvm_types AS v6_ ON sb.vvm_type_id = v6_.pk_id
            LEFT JOIN stock_master ON s1_.parent_id = stock_master.pk_id
            LEFT JOIN stakeholder_activities ON s1_.stakeholder_activity_id = stakeholder_activities.pk_id
            LEFT JOIN vvm_stages ON s0_.vvm_stage = vvm_stages.pk_id
        WHERE
            s1_.transaction_type_id = $type
            AND w2_.status = 1
            AND s1_.pk_id = " . $this->form_values['pk_id'] . "
        ORDER BY
            s1_.transaction_number ASC";

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
     * Get Stocks Receive List Shipment
     *
     * @return boolean
     */
    public function getStocksReceiveListShipment() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId as detailId,"
                        . "s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,"
                        . "w.warehouseName,"
                        . "fw.warehouseName as warehouse_name,"
                        . "b.number,"
                        . "sbw.pkId as batch_id,"
                        . "b.expiryDate,"
                        . "b.productionDate,"
                        . "sd.quantity,"
                        . "i.itemUnitName,"
                        . "p.pkId AS itemPackSizeId,"
                        . "p.itemName,"
                        . "p.numberOfDoses AS description,"
                        . "v.vvmTypeName, sa.activity,IF(p.vvmGroup = 1, vvm.pkId, vvm.vvmStageValue) as vvmStage")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->leftJoin("s.stakeholderActivity", "sa")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("s.fromWarehouse", 'fw')
                ->join("sbw.warehouse", "w")
                ->join("sip.itemPackSize", "p")
                ->join("p.itemUnit", "i")
                ->leftJoin("b.vvmType", "v")
                ->where("s.transactionType = 1")
                ->andWhere("w.status=1")
                ->andWhere("s.toWarehouse = " . $this->form_values['warehouse_id'])
                ->andWhere("s.pkId=" . $this->form_values['pk_id'])
                ->orderBy("s.transactionNumber", "ASC");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }


        // if (empty($type)) {
        //      $type = 1;
        //   }
//        $str_sql = "SELECT
//            s0_.pk_id AS detailId,
//            s1_.transaction_date AS transactionDate,
//            s1_.transaction_number AS transactionNumber,
//            s1_.transaction_reference AS transactionReference,
//            s1_.pk_id AS masterId,
//            s1_.comments,
//            w2_.warehouse_name AS warehouseName,
//            sb.number AS number,
//            s3_.pk_id AS batch_id,
//            sb.expiry_date AS expiryDate,
//            sb.production_date AS productionDate,
//            s0_.quantity AS quantity,
//            i4_.item_unit_name AS itemUnitName,
//            i5_.pk_id AS itemPackSizeId,
//            i5_.item_name AS itemName,
//            i5_.number_of_doses AS description,
//            v6_.vvm_type_name AS vvmTypeName,
//            stock_master.parent_id AS stockMasterParentId,
//            stakeholder_activities.activity,
//            IF(i5_.vvm_group_id = 1, vvm_stages.pk_id, vvm_stages.vvm_stage_value) as vvmStage
//        FROM
//        stock_detail AS s0_
//            INNER JOIN stock_master AS s1_ ON s0_.stock_master_id = s1_.pk_id
//            INNER JOIN stock_batch_warehouses AS s3_ ON s0_.stock_batch_warehouse_id = s3_.pk_id
//            INNER JOIN warehouses AS w2_ ON s3_.warehouse_id = w2_.pk_id
//            INNER JOIN stock_batch AS sb ON s3_.stock_batch_id = sb.pk_id
//            INNER JOIN pack_info AS pi ON sb.pack_info_id= pi.pk_id
//            INNER JOIN stakeholder_item_pack_sizes AS sip ON pi.stakeholder_item_pack_size_id= sip.pk_id
//            INNER JOIN item_pack_sizes AS i5_ ON sip.item_pack_size_id = i5_.pk_id
//            INNER JOIN item_units AS i4_ ON i5_.item_unit_id = i4_.pk_id
//            LEFT JOIN vvm_types AS v6_ ON sb.vvm_type_id = v6_.pk_id
//            LEFT JOIN stock_master ON s1_.parent_id = stock_master.pk_id
//            LEFT JOIN stakeholder_activities ON s1_.stakeholder_activity_id = stakeholder_activities.pk_id
//            LEFT JOIN vvm_stages ON s0_.vvm_stage = vvm_stages.pk_id
//        WHERE
//            s1_.transaction_type_id = $type
//            AND s1_.to_warehouse_id = " . $this->form_values['warehouse_id']. "    
//            AND w2_.status = 1
//            AND s1_.pk_id = " . $this->form_values['pk_id'] . "
//        ORDER BY
//            s1_.transaction_number ASC";
//echo $str_sql;
//exit;
//        $rec = $this->_em_read->getConnection()->prepare($str_sql);
//
//        $rec->execute();
//        $result = $rec->fetchAll();
//        if (count($result) > 0) {
//            return $result;
//        } else {
//            return false;
//        }
    }

    /**
     * Get Stocks Issue List
     *
     * @return boolean
     */
    public function getStocksIssueList() {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("sd.pkId as detailId,"
                        . "s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,s.comments,"
                        . "tw.warehouseName,tw.pkId as toWhId,b.number,sbw.pkId as batch_id,"
                        . "b.expiryDate,"
                        . "b.productionDate,"
                        . "p.numberOfDoses,"
                        . "ABS(sd.quantity) as quantity,"
                        . "i.itemUnitName,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName, "
                        . "p.numberOfDoses AS description,"
                        . "v.vvmTypeName,(b.unitPrice*ABS(sd.quantity)) as unitCost,"
                        . "s.dispatchBy,IF(p.vvmGroup=1,vvm.pkId,vvm.vvmStageValue) vvmStage,"
                        . "stk.stakeholderName, sa.activity, DATE_FORMAT(s.issueFrom,'%d/%m/%Y') as issue_from, DATE_FORMAT(s.issueTo,'%d/%m/%Y') as issue_to")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("s.stakeholderActivity", "sa")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sips")
                ->join("sips.stakeholder", "stk")
                ->join("s.toWarehouse", "tw")
                ->join("sbw.warehouse", "w")
                ->join("sips.itemPackSize", "p")
                ->join("p.itemCategory", "ic")
                ->join("p.itemUnit", "i")
                ->leftJoin("b.vvmType", "v")
                ->where("s.transactionType = 2 ")
                ->andWhere("w.status=1")
                ->andWhere("s.pkId=" . $this->form_values['pk_id'])
                ->orderBy("s.transactionNumber,p.listRank, ic.pkId", "ASC");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Stocks Issue List Shipment
     *
     * @return boolean
     */
    public function getStocksIssueListShipment() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId as detailId,"
                        . "s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,s.comments,"
                        . "w.warehouseName as warehouse_name,"
                        . "tw.warehouseName,b.number,sbw.pkId as batch_id,"
                        . "b.expiryDate,"
                        . "b.productionDate,"
                        . "p.numberOfDoses,"
                        . "ABS(sd.quantity) as quantity,"
                        . "i.itemUnitName,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName, "
                        . "p.numberOfDoses AS description,"
                        . "v.vvmTypeName,"
                        . "s.dispatchBy,IF(p.vvmGroup=1,vvm.pkId,vvm.vvmStageValue) vvmStage,"
                        . "stk.stakeholderName, sa.activity, DATE_FORMAT(s.issueFrom,'%d/%m/%Y') as issue_from, DATE_FORMAT(s.issueTo,'%d/%m/%Y') as issue_to")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("s.stakeholderActivity", "sa")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "b")
                ->join("b.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sips")
                ->join("sips.stakeholder", "stk")
                ->join("s.toWarehouse", "tw")
                ->join("sbw.warehouse", "w")
                ->join("sips.itemPackSize", "p")
                ->join("p.itemCategory", "ic")
                ->join("p.itemUnit", "i")
                ->leftJoin("b.vvmType", "v")
                ->where("s.transactionType = 2 ")
                ->andWhere("w.status=1")
                ->andWhere("s.fromWarehouse= " . $this->form_values['warehouse_id'])
                ->andWhere("s.pkId=" . $this->form_values['pk_id'])
                ->orderBy("s.transactionNumber,p.listRank, ic.pkId", "ASC");




        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Stocks Issue Cancel List
     *
     * @return boolean
     */
    public function getStocksIssueCancelList() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId as detailId,"
                        . "vvm.pkId as vvmStage,vvm.vvmStageValue,"
                        . "s.createdDate as transactionDate,"
                        . "s.masterId as pkId,s.transactionNumber,"
                        . "s.transactionReference,s.comments,"
                        . "tw.warehouseName,sb.number,"
                        . "sb.expiryDate,"
                        . "sb.productionDate,"
                        . "p.numberOfDoses,"
                        . "ABS(sd.quantity) as quantity,"
                        . "i.itemUnitName,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName, "
                        . "p.numberOfDoses AS description,"
                        . "v.vvmTypeName,"
                        . "stk.stakeholderName, sa.activity")
                ->from("StockDetailHistory", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("s.stakeholderActivity", "sa")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sips")
                ->join("sips.itemPackSize", "p")
                ->join("sbw.warehouse", "w")
                ->join("sips.stakeholder", "stk")
                ->join("s.toWarehouse", "tw")
                ->join("p.itemCategory", "ic")
                ->join("p.itemUnit", "i")
                ->leftJoin("sb.vvmType", "v")
                ->where("s.transactionType = 2 ")
                ->andWhere("w.status=1")
                ->andWhere("s.actionType=2")
                ->andWhere("sd.actionType=3")
                ->andWhere("s.draft=0")
                ->andWhere("s.masterId=" . $this->form_values['pk_id'])
                ->orderBy("s.transactionNumber,p.listRank, ic.pkId", "ASC");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Stocks Pending List Shipment
     *
     * @return boolean
     */
    public function getStocksPendingListShipment() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd.pkId as detailId,"
                        . "vvm.pkId as vvmStage,vvm.vvmStageValue,"
                        . "s.transactionDate,"
                        . " s.pkId,s.transactionNumber,"
                        . "s.transactionReference,s.comments,w.warehouseName as warehouse_name,"
                        . "tw.warehouseName,sb.number,"
                        . "sb.expiryDate,"
                        . "sb.productionDate,"
                        . "p.numberOfDoses,"
                        . "ABS(sd.quantity) as quantity,"
                        . "i.itemUnitName,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName, "
                        . "p.numberOfDoses AS description,"
                        . "v.vvmTypeName,"
                        . "s.dispatchBy,"
                        . "stk.stakeholderName")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sips")
                ->join("sips.stakeholder", "stk")
                ->join("s.toWarehouse", "tw")
                ->join("sbw.warehouse", "w")
                ->join("sips.itemPackSize", "p")
                ->join("p.itemUnit", "i")
                ->leftJoin("sb.vvmType", "v")
                ->where("s.transactionType = 2 ")
                ->andWhere("w.status=1")
                ->andWhere("s.toWarehouse= " . $this->form_values['warehouse_id'])
                ->andWhere("s.pkId=" . $this->form_values['pk_id'])
                ->orderBy("s.transactionNumber", "ASC");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse Level By StockId
     *
     * @param type $stock_master_id
     * @return boolean
     */
    public function getWarehouseLevelByStockId($stock_master_id) {
        $str_record = $this->_em->getRepository("StockMaster")->find($stock_master_id);

        if (count($str_record) > 0) {
            return $str_record->getToWarehouse()->getStakeholderOffice()->getGeoLevel()->getPkId();
        } else {
            return false;
        }
    }

    /**
     * Auto Receive Data
     *
     * @param type $stock_master_id
     * @return boolean
     */
    public function autoReceiveData($stock_master_id) {
        if ($this->getWarehouseLevelByStockId($stock_master_id) == 6) {

            $master_result = $this->_em->getRepository("StockMaster")->find($stock_master_id);
            $trans_date = $master_result->getTransactionDate();
            $to_wh_id = $master_result->getToWarehouse()->getPkId();

            $new_date = $trans_date->format("d/m/Y H:i:s");
            $trans_no = $this->getTransactionNumber(1, $new_date);

            $new_master = new StockMaster();
            $new_master->setTransactionDate($trans_date);
            $new_master->setTransactionNumber($trans_no['trans_no']);
            $new_master->setTransactionCounter($trans_no['id']);
            $new_master->setTransactionReference($master_result->getTransactionReference());
            $new_master->setDraft(0);
            $new_master->setComments($master_result->getComments());
            $new_master->setParentId($stock_master_id);
            $new_master->setCampaignId($master_result->getCampaignId());
            $new_master->setCreatedDate($master_result->getCreatedDate());
            $new_master->setModifiedDate(App_Tools_Time::now());
            $new_master->setStakeholderActivity($master_result->getStakeholderActivity());
            $new_user_id = $this->getUserIdByWarehouse($to_wh_id);
            $new_master->setCreatedBy($new_user_id);
            $modified_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $new_master->setModifiedBy($modified_by);
            $new_master->setFromWarehouse($master_result->getFromWarehouse());
            $new_master->setToWarehouse($master_result->getToWarehouse());
            $trans_type = $this->_em->getRepository("TransactionTypes")->find(1);
            $new_master->setTransactionType($trans_type);
            $this->_em->persist($new_master);
            $this->_em->flush();

            $detail_result = $this->_em->getRepository("StockDetail")->findBy(array("stockMaster" => $stock_master_id));
            if (count($detail_result) > 0) {

                foreach ($detail_result as $detail_row) {
                    $detail_id = $detail_row->getPkId();
                    $item_id = $detail_row->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId();
                    $batch_number = $detail_row->getStockBatch()->getNumber();
                    $from_batch_id = $detail_row->getStockBatch()->getPkId();

                    $batch_result = $this->_em->getRepository("StockBatch")->findOneBy(array("itemPackSize" => $item_id, "warehouse" => $to_wh_id, "number" => $batch_number));
                    if (count($batch_result) > 0) {
                        $new_batch_id = $batch_result;
                    } else {
                        $from_batch_result = $this->_em->getRepository("StockBatch")->find($from_batch_id);
                        $obj_batch = new StockBatch();
                        $obj_batch->setNumber(strtoupper($from_batch_result->getNumber()));
                        $obj_batch->setBatchMasterId($from_batch_result->getBatchMasterId());
                        $obj_batch->setExpiryDate($from_batch_result->getExpiryDate());
                        $obj_batch->setUnitPrice($from_batch_result->getUnitPrice());
                        $obj_batch->setQuantity(0);
                        $obj_batch->setStatus("Running");
                        $obj_batch->setProductionDate($from_batch_result->getProductionDate());
                        $obj_batch->setLastUpdate($from_batch_result->getLastUpdate());
                        if ($from_batch_result->getStakeholderItemPackSize() != null) {
                            $obj_batch->setStakeholderItemPackSize($from_batch_result->getStakeholderItemPackSize());
                        }
                        $obj_batch->setItemPackSize($from_batch_result->getItemPackSize());
                        if ($from_batch_result->getVvmType() != null) {
                            $obj_batch->setVvmType($from_batch_result->getVvmType());
                        }
                        $to_wh = $this->_em->getRepository("Warehouses")->find($to_wh_id);
                        $obj_batch->setWarehouse($to_wh);
                        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                        $obj_batch->setModifiedBy($created_by);
                        $obj_batch->setModifiedDate(App_Tools_Time::now());
                        $obj_batch->setCreatedBy($created_by);
                        $obj_batch->setCreatedDate(App_Tools_Time::now());
                        $this->_em->persist($obj_batch);
                        $this->_em->flush();

                        $new_batch_id = $obj_batch;
                    }

                    $obj_detail = new StockDetail();
                    $obj_detail->setQuantity(ABS($detail_row->getQuantity()));
                    $obj_detail->setTemporary(0);
                    $obj_detail->setVvmStage($detail_row->getVvmStage());
                    $obj_detail->setIsReceived($detail_id);
                    $obj_detail->setAdjustmentType(1);
                    $obj_detail->setItemUnit($detail_row->getItemUnit());
                    $obj_detail->setStockBatch($new_batch_id);
                    $obj_detail->setStockMaster($new_master);
                    $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
                    $obj_detail->setModifiedBy($created_by);
                    $obj_detail->setModifiedDate(App_Tools_Time::now());
                    $obj_detail->setCreatedBy($created_by);
                    $obj_detail->setCreatedDate(App_Tools_Time::now());
                    $this->_em->persist($obj_detail);
                    $this->_em->flush();

                    $this->adjustQuantityByWarehouse($new_batch_id->getPkId(), $to_wh_id);
                }
            }
        }
        return true;
    }

    /**
     * Adjust Quantity By Warehouse
     *
     * @param type $batch_id
     * @param type $wh_id
     */
    public function adjustQuantityByWarehouse($batch_id, $wh_id) {
        $row = $this->_em->getConnection()->prepare("SELECT AdjustQty($batch_id,$wh_id) from DUAL");
        $row->execute();
    }

    /**
     * Get User Id By Warehouse
     *
     * @param type $wh_id
     * @return boolean
     */
    public function getUserIdByWarehouse($wh_id) {
        $wh = $this->_em_read->getRepository('WarehouseUsers')->findOneBy(array("warehouse" => $wh_id));
        if (count($wh) > 0) {
            return $wh->getUser();
        } else {
            return false;
        }
    }

    /**
     * Delete Stock Master
     *
     * @param type $id
     * @return boolean
     */
    public function deleteStockMaster($id) {
        if (!$this->stockExists($id)) {
            // Check if detail master is being used in demand master, then reset it and set requisition status as submitted.
            $sql = "UPDATE demand_master SET stock_master_id = NULL, draft='0' WHERE stock_master_id = " . $id;
            $row = $this->_em->getConnection()->prepare($sql);
            $row->execute();

            $stock = $this->_table->find($id);
            $this->_em->remove($stock);
            $this->_em->flush();
            return true;
        }
    }

    /**
     * Get Adj Last ID
     *
     * @param type $from
     * @param type $to
     * @return boolean
     */
    public function getAdjLastID($from, $to) {
        if (!empty($tr_type) && $tr_type == 1) {
            $str = "s.toWarehouse='" . $this->_identity->getWarehouseId() . "'";
        } else {
            $str = "s.fromWarehouse ='" . $this->_identity->getWarehouseId() . "'";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("MAX(s.transactionCounter) as Maxtr")
                ->from("StockMaster", "s")
                ->where("s.transactionDate between '" . $from . "' and '" . $to . "'")
                ->andWhere("s.transactionType !=1 ")
                ->andWhere("s.transactionType !=2 ")
                ->andWhere($str);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Add Adjustment
     *
     * @return type
     * @throws Exception
     */
    public function addAdjustment() {

        $wh_id = $this->_identity->getWarehouseId();
        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        }

        $ref_no = '';
        $quantity = '';
        $comments = '';

        if (isset($this->form_values['adjustment_date']) && !empty($this->form_values['adjustment_date'])) {
            $adjustment_date = $this->form_values['adjustment_date'];
            list($dd, $mm, $yy) = explode("/", $adjustment_date);
        } else {
            $adjustment_date = date('d/m/Y');
            list($dd, $mm, $yy) = explode("/", $adjustment_date);
        }
        if (isset($this->form_values['ref_no']) && !empty($this->form_values['ref_no'])) {
            $ref_no = $this->form_values['ref_no'];
        }

        if (isset($this->form_values['batch_no']) && !empty($this->form_values['batch_no'])) {
            $batch_id = $this->form_values['batch_no'];
        }

        if (isset($this->form_values['adjustment_type']) && !empty($this->form_values['adjustment_type'])) {
            $type = $this->form_values['adjustment_type'];
        }
        if (isset($this->form_values['quantity']) && !empty($this->form_values['quantity'])) {
            $quantity = str_replace(',', '', $this->form_values['quantity']);
        }
        if (isset($this->form_values['comments']) && !empty($this->form_values['comments'])) {
            $comments = $this->form_values['comments'];
        }
        if (isset($this->form_values['item_unit_id']) && !empty($this->form_values['item_unit_id'])) {
            $unit = $this->form_values['item_unit_id'];
        }

        if (isset($this->form_values['purpose']) && !empty($this->form_values['purpose'])) {
            $purpose = $this->form_values['purpose'];
        }
        $is_received = 0;
        if (isset($this->form_values['is_received']) && !empty($this->form_values['is_received'])) {
            $is_received = $this->form_values['is_received'];
        }
        if (isset($this->form_values['transfer_date']) && !empty($this->form_values['transfer_date'])) {
            $transfer_date = $this->form_values['transfer_date'];
        }

        $stock_batch_warehouse = $this->_em->getRepository('StockBatchWarehouses')->find($batch_id);
        $tranaction_type = new Model_TransactionTypes();
        $type_nature = $tranaction_type->findById($type);
        $trans_nature = $type_nature[0]['nature'];

        if ($stock_batch_warehouse->getQuantity() == 0 && $trans_nature == '-') {
            throw new RangeException('NEGATIVE_OR_ZERO_QTY');
        }

        list($location, $old_vvm, $placed_qty) = explode("|", $this->form_values['location_id']);
        if ($trans_nature == '+') {
            $old_vvm = $this->form_values['vvm_stage'];
        }

        if ($quantity > $stock_batch_warehouse->getQuantity() && $trans_nature == '-') {
            throw new RangeException('ADJ_QTY_GREATER_BATCH_QTY');
        }

        if (!empty($placed_qty)) {
            if ($placed_qty < 0) {
                throw new RangeException('PLCD_QTY_LESS_EQUAL_ZERO');
            }

            if ($placed_qty < $quantity && $trans_nature == '-') {
                throw new RangeException('ADJ_QTY_LESS_EQUAL_PLCD_QTY');
            }
        }

        $stock_master = new StockMaster();
        $stock_master->setTransactionDate(new \DateTime($yy . "-" . $mm . "-" . $dd));
        $tran_type = $this->_em->getRepository('TransactionTypes')->find($type);
        $stock_master->setTransactionType($tran_type);
        $stock_master->setTransactionReference($ref_no);
        $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
        if ($type == parent::REVLOGISTICS) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->form_values['from_wh_id']);
            $stock_master->setFromWarehouse($from_warehouse_id);
        } else {
            $stock_master->setFromWarehouse($warehouse_id);
        }
        $stock_master->setToWarehouse($warehouse_id);
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_master->setCreatedBy($user_id);
        $stock_master->setModifiedBy($user_id);
        $stock_master->setCreatedDate(App_Tools_Time::now());
        $stock_master->setModifiedDate(App_Tools_Time::now());
        $stock_master->setComments($comments);

        $trans_no = $this->getTransactionNumber($type, $adjustment_date, $wh_id);
        $stock_master->setTransactionNumber($trans_no['trans_no']);

        if (!empty($purpose)) {
            $stakeholder_activity = $this->_em->getRepository('StakeholderActivities')->find($purpose);
            $stock_master->setStakeholderActivity($stakeholder_activity);
        }

        $stock_master->setDraft(0);
        $stock_master->setTransactionCounter($trans_no['id']);
        $stock_master->setParentId(0);
        $this->_em->persist($stock_master);
        $this->_em->flush();

        $stock_ID = $stock_master->getPkId();

        $stock_detail = new StockDetail();
        $s_id = $this->_em->getRepository('StockMaster')->find($stock_ID);
        $stock_detail->setStockMaster($s_id);
        $b_id = $this->_em->getRepository('StockBatchWarehouses')->find($batch_id);
        $stock_detail->setStockBatchWarehouse($b_id);
        $stock_detail->setQuantity($type_nature[0]['nature'] . $quantity);
        $stock_detail->setAdjustmentType($type);
        $stock_detail->setTemporary(0);
        if (empty($old_vvm) || $old_vvm == null || $old_vvm == '') {
            $old_vvm = 0;
        }

        $vvms = $this->_em->getRepository("VvmStages")->find($old_vvm);
        $stock_detail->setVvmStage($vvms);
        $u_id = $this->_em->getRepository('ItemUnits')->find($unit);
        $stock_detail->setItemUnit($u_id);
        $stock_detail->setIsReceived($is_received);
        $stock_detail->setCreatedBy($user_id);
        $stock_detail->setModifiedBy($user_id);
        $stock_detail->setCreatedDate(App_Tools_Time::now());
        $stock_detail->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($stock_detail);
        $this->_em->flush();

        $stock_detail_id = $stock_detail->getPkId();
        $stock_batch = new Model_StockBatch();
        $warehouse_data = new Model_HfDataMaster();
        //$stock_batch->adjustQuantityBywarehouse($batch_id);
        $warehouse_data->form_values['wh_id'] = $wh_id;
        $warehouse_data->addReport($stock_ID, $type);

        if (!empty($location)) {
            $placements = new Model_Placements();
            $placements->form_values = array(
                'batch_id' => $batch_id,
                'placement_loc_id' => $location,
                'detail_id' => $stock_detail_id,
                'user_id' => $this->_user_id,
                'created_date' => date("Y-m-d")
            );


            if ($type_nature[0]['nature'] == '-') {
                // Check if batch exists in cold room
                $str_sql = $this->_em_read->createQueryBuilder()
                        ->select("SUM(ps.quantity) as quantity")
                        ->from("Placements", "ps")
                        ->where("ps.placementLocation = " . $location)
                        ->andWhere("ps.stockBatchWarehouse = " . $batch_id);
                $row = $str_sql->getQuery()->getResult();
                if (count($row) > 0) {
                    $qty = $row[0]['quantity'];
                    if ($qty < $quantity) {
                        // If pick quantity is greater than placed quantity
                        throw new RangeException('PICK_ERROR');
                    }
                }

                $loc_type = 115;
                $is_placed = 0;
            } else {
                $loc_type = 114;
                $is_placed = 1;
            }

            $placements->form_values['vvmstage'] = $old_vvm;
            $placements->form_values['is_placed'] = $is_placed;
            $placements->form_values['quantity'] = $type_nature[0]['nature'] . $quantity;
            $placements->form_values['placement_loc_type_id'] = $loc_type;
            $placements->add();
        }

        return $stock_master->getTransactionNumber();
    }

    /**
     * Delete Adjustment
     *
     * @param type $id
     * @return boolean
     */
    public function deleteAdjustment($id) {
        $stock_detail = $this->_em->getRepository("StockDetail")->findBy(array("stockMaster" => $id));

        if (count($stock_detail) > 0) {
            //start - delete related adjustments - change purpose
            if ($stock_detail[0]->getIsReceived() != 0) {
                $relate_adj = $this->_em->getRepository("StockDetail")->findBy(array("isReceived" => $stock_detail[0]->getIsReceived()));
                if (count($relate_adj) > 0 && $relate_adj[0]->getAdjustmentType() == Model_StockMaster::PURPOSE_NEGATIVE) {
                    $this->_em->remove($relate_adj[0]->getStockMaster());
                    if (count($relate_adj) > 0) {
                        foreach ($relate_adj as $sd2) {
                            $placement2 = $this->_em->getRepository("Placements")->findBy(array("stockDetail" => $sd2->getPkId()));
                            if (count($placement2) > 0) {
                                foreach ($placement2 as $plc2) {
                                    $this->_em->remove($plc2);
                                }
                            }
                            $this->_em->remove($sd2);
                        }
                    }
                }
            }
            //end - delete related adjustments - change purpose

            $this->_em->remove($stock_detail[0]->getStockMaster());
            if (count($stock_detail) > 0) {
                foreach ($stock_detail as $sd) {
                    $placement = $this->_em->getRepository("Placements")->findBy(array("stockDetail" => $sd->getPkId()));
                    if (count($placement) > 0) {
                        foreach ($placement as $plc) {
                            $this->_em->remove($plc);
                        }
                    }

                    $this->_em->remove($sd);
                }
            }
        }

        $this->_em->flush();

        return true;
    }

    /**
     * Get warehouse Stock By Issue No
     *
     * @return boolean
     */
     public function getwarehouseStockByIssueNo() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.pkId as masterId,w.warehouseName,"
                        . "ABS(sd.quantity) as quantity,(ABS(sd.quantity)*p.numberOfDoses) as doses,p.numberOfDoses,"
                        . "vvm.pkId as vvmStage,vvm.vvmStageValue,"
                        . "sd.pkId as detailId,"
                        . "sb.number,"
                        . "p.itemName,DATE_FORMAT(s.transactionDate,'%d/%m/%Y') as transactionDate, ic.pkId as itemCategory,s.transactionDate as receiveDate")
                ->from("StockDetail", "sd")
                ->join("sd.vvmStage", "vvm")
                ->join("sd.stockMaster", "s")
                ->join("s.fromWarehouse","w")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "p")
                ->join("p.itemCategory", "ic")
                ->where("s.transactionType= 2")
                ->andWhere("s.transactionNumber = '" . $this->transaction_number . "'")
                ->andWhere("s.toWarehouse  =  $this->to_warehouse_id")
                ->andWhere("sd.isReceived IS NULL OR sd.isReceived=0")
                ->andWhere("s.draft=0");
       
               
                
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }


    /**
     * Add Stock Warehouse Via Scanner
     *
     * @return boolean
     */
    public function addStockWarehouseViaScanner() {

        $data = $this->form_values;
        $stock_id = $data['stockid'];
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);

        $stocks = $this->_em->getRepository("StockDetail")->find($stock_id[0]);

        $stock_master = new StockMaster();
        $time_arr = explode(' ', $data['rec_date']);
        $time = date('H:i:s', strtotime($time_arr[1] . $time_arr[2]));
        $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($time_arr[0]) . '' . $time));
        $tran_type = $this->_em->getRepository('TransactionTypes')->find(Model_TransactionTypes::TRANSACTION_RECIEVE);
        $stock_master->setTransactionType($tran_type);
        $stock_master->setTransactionReference($data['rec_ref']);
        $stock_master->setFromWarehouse($stocks->getStockMaster()->getFromWarehouse());
        $stock_master->setToWarehouse($stocks->getStockMaster()->getToWarehouse());
        $stock_master->setCreatedBy($created_by);
        $stock_master->setModifiedBy($created_by);
        $stock_master->setCreatedDate(App_Tools_Time::now());
        $stock_master->setModifiedDate(App_Tools_Time::now());
        $stock_master->setComments($data['remarks']);

        $trans_no = $this->getTransactionNumber(1, $data['rec_date']);
        $stock_master->setTransactionNumber($trans_no['trans_no']);
        $stock_master->setDraft(0);
        $stock_master->setTransactionCounter($trans_no['id']);
        $stock_master->setParentId(0);
        $this->_em->persist($stock_master);
        $this->_em->flush();

        foreach ($stock_id as $index => $detail_id) {
            $stock_detail = $this->_em->getRepository("StockDetail")->find($detail_id);
            $received_location = $this->_em->getRepository("StockReceiveFromScanner")->findBy(array("stockDetail" => $detail_id));

            $obj_stock_batch = new Model_StockBatch();
            $array = array('number' => $stock_detail->getStockBatch()->getNumber(), 'item_id' => $stock_detail->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
            $batch_id = $obj_stock_batch->checkBatch($array);

            if ($batch_id === 0) {
                $stock_batch = new StockBatch();
                $stock_batch->setNumber(strtoupper($stock_detail->getStockBatch()->getNumber()));
                $stock_batch->setBatchMasterId($stock_detail->getStockBatch()->getBatchMasterId());
                $stock_batch->setExpiryDate($stock_detail->getStockBatch()->getExpiryDate());
                $stock_batch->setQuantity(ABS($stock_detail->getQuantity()));
                $stock_batch->setItemPackSize($stock_detail->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize());
                $stock_batch->setStatus('Stacked');
                $stock_batch->setUnitPrice($stock_detail->getStockBatch()->getUnitPrice());
                $stock_batch->setProductionDate($stock_detail->getStockBatch()->getProductionDate());
                $stock_batch->setVvmType($stock_detail->getStockBatch()->getVvmType());
                $stock_batch->setWarehouse($stock_detail->getStockMaster()->getToWarehouse());

                if ($stock_detail->getStockBatch()->getStakeholderItemPackSize() != null) {
                    $stock_batch->setStakeholderItemPackSize($stock_detail->getStockBatch()->getStakeholderItemPackSize());
                }
                $stock_batch->setCreatedBy($created_by);
                $stock_batch->setModifiedBy($created_by);
                $stock_batch->setCreatedDate(App_Tools_Time::now());
                $stock_batch->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($stock_batch);
                $this->_em->flush();
                $batch_id = $stock_batch->getPkId();
            }

            if (count($received_location) > 0) {
                foreach ($received_location as $placement) {
                    $stk_detail = new StockDetail();
                    $stk_detail->setStockMaster($stock_master);
                    $sb_id = $this->_em->getRepository('StockBatch')->find($batch_id);
                    $stk_detail->setStockBatch($sb_id);
                    $stk_detail->setItemUnit($stock_detail->getItemUnit());
                    $stk_detail->setQuantity(ABS($placement->getQuantity()));
                    $stk_detail->setTemporary(0);
                    $stk_detail->setVvmStage($placement->getVvmStage());
                    $stk_detail->setIsReceived($stock_detail->getPkId());
                    $stk_detail->setAdjustmentType(1);
                    $stk_detail->setCreatedBy($created_by);
                    $stk_detail->setModifiedBy($created_by);
                    $stk_detail->setCreatedDate(App_Tools_Time::now());
                    $stk_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($stk_detail);
                    $this->_em->flush();

                    /*
                     * Add entry in Placement table
                     */
                    $placements = new Placements();
                    $placements->setQuantity($placement->getQuantity());
                    $placements->setVvmStage($placement->getVvmStage());
                    $placements->setIsPlaced(1);
                    $placements->setPlacementLocation($placement->getPlacementLocation());
                    $placements->setStockBatch($sb_id);
                    $trans_type = $this->_em->getRepository("ListDetail")->find(Model_ListDetail::STOCK_PLACEMENT);
                    $placements->setPlacementTransactionType($trans_type);
                    $placements->setCreatedBy($stk_detail->getStockMaster()->getCreatedBy());
                    $placements->setCreatedDate(App_Tools_Time::now());
                    $stock_master->setModifiedBy($created_by);
                    $stock_master->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($placements);
                    $this->_em->flush();
                }
            } else {
                $stk_detail = new StockDetail();
                $stk_detail->setStockMaster($stock_master);
                $sb_id = $this->_em->getRepository('StockBatch')->find($batch_id);
                $stk_detail->setStockBatch($sb_id);
                $stk_detail->setItemUnit($stock_detail->getItemUnit());
                $stk_detail->setQuantity(ABS($stock_detail->getQuantity()));
                $stk_detail->setTemporary(0);
                $stk_detail->setVvmStage($stock_detail->getVvmStage());
                $stk_detail->setIsReceived($stock_detail->getPkId());
                $stk_detail->setAdjustmentType(1);
                $stk_detail->setModifiedBy($created_by);
                $stk_detail->setModifiedDate(App_Tools_Time::now());
                $stk_detail->setCreatedBy($created_by);
                $stk_detail->setCreatedDate(App_Tools_Time::now());

                $this->_em->persist($stk_detail);
                $this->_em->flush();
            }

            $details[$index] = $stk_detail;

            $stock_detail->setModifiedBy($created_by);
            $stock_detail->setModifiedDate(App_Tools_Time::now());
            $stock_detail->setIsReceived(1);
            $this->_em->persist($stock_detail);
            $this->_em->flush();
        }

        $missing = $this->form_values['missing'];
        $type = $this->form_values['types'];

        $tranaction_type = new Model_TransactionTypes();
        $arr_types = $tranaction_type->getAll();
        $array_types = array();
        foreach ($arr_types as $arrtype) {
            $array_types[$arrtype['pkId']] = $arrtype['nature'];
        }

        $count = 1;
        foreach ($missing as $index => $adjustment) {
            if ($adjustment > 0) {
                if ($count == 1) {
                    $stock_master = new StockMaster();
                    $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['rec_date'])));
                    $tran_type1 = $this->_em->getRepository('TransactionTypes')->find($type[$index]);
                    $stock_master->setTransactionType($tran_type1);
                    $stock_master->setTransactionReference($data['rec_ref']);
                    $stock_master->setFromWarehouse($stock_detail->getStockMaster()->getToWarehouse());
                    $stock_master->setToWarehouse($stock_detail->getStockMaster()->getToWarehouse());
                    $stock_master->setCreatedBy($created_by);
                    $stock_master->setModifiedBy($created_by);
                    $stock_master->setCreatedDate(App_Tools_Time::now());
                    $stock_master->setModifiedDate(App_Tools_Time::now());
                    $stock_master->setComments($data['remarks']);

                    $trans_no = $this->getTransactionNumber($type[$index], $data['rec_date']);
                    $stock_master->setTransactionNumber($trans_no['trans_no']);
                    $stock_master->setDraft(0);
                    $stock_master->setTransactionCounter($trans_no['id']);
                    $stock_master->setParentId($stock_detail->getStockMaster()->getPkId());

                    $this->_em->persist($stock_master);
                    $this->_em->flush();
                }

                $stock_detail_a = new StockDetail();
                $stock_detail_a->setStockMaster($stock_master);
                $stock_detail_a->setStockBatch($details[$index]->getStockBatch());
                $stock_detail_a->setItemUnit($stock_detail->getItemUnit());
                $stock_detail_a->setQuantity($array_types[$type[$index]] . $missing[$index]);
                $stock_detail_a->setAdjustmentType($type[$index]);
                $stock_detail_a->setTemporary(0);
                $stock_detail_a->setIsReceived($details[$index]->getPkId());
                $vvms = $this->_em->getRepository("VvmStages")->find(1);
                $stock_detail_a->setVvmStage($vvms);
                $stock_detail_a->setCreatedBy($created_by);
                $stock_detail_a->setModifiedBy($created_by);
                $stock_detail_a->setCreatedDate(App_Tools_Time::now());
                $stock_detail_a->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($stock_detail_a);
                $this->_em->flush();

                $count++;
            }
        }

        foreach ($details as $obj_stock_detail) {
            $obj_stock_batch->adjustQuantityByWarehouse($obj_stock_detail->getStockBatch()->getPkId());
            $obj_stock_batch->autoRunningLEFOBatch($obj_stock_detail->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId());
        }

        $warehouse_data = new Model_HfDataMaster();
        $warehouse_data->addReport($details[0]->getStockMaster()->getPkId(), 1, 'wh');

        return true;
    }

    /**
     * Add Stock Warehouse By Issue
     *
     * @return boolean
     */
    public function addStockWarehouseByIssue() {
        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        } else {
            $wh_id = $this->_identity->getWarehouseId();
        }

        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $tranaction_type = new Model_TransactionTypes();
        $arr_types = $tranaction_type->getAll();
        $array_types = array();

        foreach ($arr_types as $arrtype) {
            $array_types[$arrtype['pkId']] = $arrtype['nature'];
        }

        if (isset($this->form_values['stock_id']) && !empty($this->form_values['stock_id'])) {
            $stock_id = $this->form_values['stock_id'];

            $stock_detail = new Model_StockDetail();
            $stock_detail->pkId = $stock_id;
            $stock_detail_result = $stock_detail->findbyStockId();

            $type_id = 1;
            if (isset($this->form_values['remarks']) && !empty($this->form_values['remarks'])) {
                $remarks = $this->form_values['remarks'];
            }

            if (isset($this->form_values['rec_date']) && !empty($this->form_values['rec_date'])) {
                $rec_date = $this->form_values['rec_date'];
            }
            if (isset($this->form_values['rec_ref']) && !empty($this->form_values['rec_ref'])) {
                $rec_ref = $this->form_values['rec_ref'];
            }
            if (isset($this->form_values['vvmstage']) && !empty($this->form_values['vvmstage'])) {
                $vvmstage = $this->form_values['vvmstage'];
            }
            if (isset($this->form_values['locations']) && !empty($this->form_values['locations'])) {
                $locations = $this->form_values['locations'];
            }

            if ($stock_detail_result != false) {
                $stock_master = new StockMaster();
                $time_arr = explode(' ', $rec_date);
                $time = date('H:i:s', strtotime($time_arr[1] . $time_arr[2]));
                $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($time_arr[0]) . ' ' . $time));
                $tran_type = $this->_em->getRepository('TransactionTypes')->find($type_id);

                $stock_master->setTransactionType($tran_type);
                $stock_master->setTransactionReference($rec_ref);
                $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($stock_detail_result[0]['fromWarehouse']);
                $stock_master->setFromWarehouse($from_warehouse_id);
                $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
                $stock_master->setToWarehouse($warehouse_id);
                $stock_master->setCreatedBy($created_by);
                $stock_master->setModifiedBy($created_by);
                $stock_master->setCreatedDate(App_Tools_Time::now());
                $stock_master->setModifiedDate(App_Tools_Time::now());
                $stock_master->setComments($remarks);

                $trans_no = $this->getTransactionNumber(1, $rec_date);
                $stock_master->setTransactionNumber($trans_no['trans_no']);

                if (!empty($stock_detail_result[0]['stakeholderActivity'])) {
                    $stakeholder_activity = $this->_em->getRepository('StakeholderActivities')->find($stock_detail_result[0]['stakeholderActivity']);
                    $stock_master->setStakeholderActivity($stakeholder_activity);
                }

                $stock_master->setDraft(0);
                $stock_master->setTransactionCounter($trans_no['id']);
                $stock_master->setParentId(0);
                $this->_em->persist($stock_master);
                $this->_em->flush();

                $fk_stock_ID = $stock_master->getPkId();
            }
        }

        if (isset($this->form_values['stockid']) && !empty($this->form_values['stockid'])) {

            $stock_ids = $this->form_values['stockid'];

            foreach ($stock_ids as $index => $detail_id) {

                $stock_detail = new Model_StockDetail();
                $stock_detail->stockReceived($detail_id);

                $stockBatch = $stock_detail->GetBatchDetail($detail_id);

                $stockDetail = $stock_detail->findByDetailId($detail_id);
                if ($stockDetail != false) {
                    $array_missing = $this->form_values['missing'];

                    $quantity = ABS($stockBatch['0']['quantity']);

                    $obj_stock_batch = new Model_StockBatch();
                    $product_id = $stockBatch[0]['itemPackSize'];
                    $batch_id1 = $obj_stock_batch->checkAndAddBatch($stockBatch[0]);

                    $stock_detail = new StockDetail();
                    $sm_id = $this->_em->getRepository('StockMaster')->find($fk_stock_ID);
                    $stock_detail->setStockMaster($sm_id);
                    $sb_id = $this->_em->getRepository('StockBatchWarehouses')->find($batch_id1);
                    $stock_detail->setStockBatchWarehouse($sb_id);
                    $iu_id = $this->_em->getRepository('ItemUnits')->find($stockDetail['0']['itemUnit']);
                    $stock_detail->setItemUnit($iu_id);
                    $stock_detail->setQuantity($array_types[$type_id] . $quantity);
                    $stock_detail->setTemporary(0);
                    $vvms = $this->_em->getRepository("VvmStages")->find($vvmstage[$index]);
                    $stock_detail->setVvmStage($vvms);
                    $stock_detail->setIsReceived($detail_id);
                    $stock_detail->setAdjustmentType($type_id);
                    $stock_detail->setModifiedBy($created_by);
                    $stock_detail->setModifiedDate(App_Tools_Time::now());
                    $stock_detail->setCreatedBy($created_by);
                    $stock_detail->setCreatedDate(App_Tools_Time::now());
                    $this->_em->persist($stock_detail);
                    $this->_em->flush();
                    $adj_stock_detail_id = $stock_detail->getPkId();

                    if (!empty($locations[$index])) {
                        $placement = new Model_Placements();
                        $placement->form_values = array(
                            'vvmstage' => $vvmstage[$index],
                            'is_placed' => 1,
                            'quantity' => $array_types[$type_id] . $quantity,
                            'placement_loc_type_id' => 114,
                            'placement_loc_id' => $locations[$index],
                            'batch_id' => $batch_id1,
                            'detail_id' => $stock_detail->getPkId(),
                            'user_id' => $this->_user_id,
                            'created_date' => date("Y-m-d")
                        );
                        $placement->add();
                    }
                }

                if (isset($array_missing[$index]) && !empty($array_missing[$index])) {
                    $type = $this->form_values['types'];
                    $stock_detail = new Model_StockDetail();
                    $stockDetail = $stock_detail->findByDetailId($detail_id);

                    if ($stockDetail != false) {
                        $stock_master = new StockMaster();
                        $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($rec_date)));
                        $tran_type1 = $this->_em->getRepository('TransactionTypes')->find($type[$index]);
                        $stock_master->setTransactionType($tran_type1);
                        $stock_master->setTransactionReference($rec_ref);
                        $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
                        $stock_master->setFromWarehouse($warehouse_id);
                        $stock_master->setToWarehouse($warehouse_id);
                        $stock_master->setCreatedBy($created_by);
                        $stock_master->setModifiedBy($created_by);
                        $stock_master->setCreatedDate(App_Tools_Time::now());
                        $stock_master->setModifiedDate(App_Tools_Time::now());
                        $stock_master->setComments($remarks);
                        $trans_no = $this->getTransactionNumber($type[$index], $rec_date);
                        $stock_master->setTransactionNumber($trans_no['trans_no']);
                        $stock_master->setDraft(0);
                        $stock_master->setTransactionCounter($trans_no['id']);
                        $stock_master->setParentId($fk_stock_ID);

                        $this->_em->persist($stock_master);
                        $this->_em->flush();

                        $stock_id = $stock_master->getPkId();

                        $stock_detail_a = new StockDetail();
                        $s_id = $this->_em->getRepository('StockMaster')->find($stock_id);
                        $stock_detail_a->setStockMaster($s_id);
                        $sb_id = $this->_em->getRepository('StockBatchWarehouses')->find($batch_id1);
                        $stock_detail_a->setStockBatchWarehouse($sb_id);
                        $iu_id = $this->_em->getRepository('ItemUnits')->find($stockDetail['0']['itemUnit']);
                        $stock_detail_a->setItemUnit($iu_id);
                        $stock_detail_a->setQuantity($array_types[$type[$index]] . $array_missing[$index]);
                        $stock_detail_a->setAdjustmentType($type[$index]);
                        $stock_detail_a->setTemporary(0);
                        $stock_detail_a->setIsReceived($adj_stock_detail_id);
                        $vvms = $this->_em->getRepository("VvmStages")->find($vvmstage[$index]);
                        $stock_detail_a->setVvmStage($vvms);
                        $stock_detail_a->setCreatedBy($created_by);
                        $stock_detail_a->setModifiedBy($created_by);
                        $stock_detail_a->setCreatedDate(App_Tools_Time::now());
                        $stock_detail_a->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($stock_detail_a);
                        $this->_em->flush();

                        if (!empty($locations[$index])) {
                            $placement2 = new Model_Placements();
                            $placement2->form_values = array(
                                'vvmstage' => $vvmstage[$index],
                                'is_placed' => 0,
                                'quantity' => $array_types[$type[$index]] . $array_missing[$index],
                                'placement_loc_type_id' => 115,
                                'placement_loc_id' => $locations[$index],
                                'batch_id' => $batch_id1,
                                'detail_id' => $stock_detail_a->getPkId(),
                                'user_id' => $this->_user_id,
                                'created_date' => date("Y-m-d")
                            );
                            $placement2->add();
                        }
                    }
                }

                //$obj_stock_batch->adjustQuantityByWarehouse($batch_id1);
                //$obj_stock_batch->autoRunningLEFOBatch($product_id);
            }
        }

        if (!empty($stock_id)) {
            $warehouse_data = new Model_HfDataMaster();
            $warehouse_data->addReport($stock_id, 1, 'wh');
        }

        return $fk_stock_ID;
    }

    /**
     * Update Master Issue Date
     *
     * @return boolean
     */
    public function updateMasterIssueDate() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->update('StockMaster')
                ->set("transactionDate", '?', $this->transaction_date)
                ->where('pk_id = ?', $this->pk_id);
        $row = $str_sql->execute();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Stock Exists
     *
     * @param type $id
     * @return boolean
     */
    public function stockExists($id) {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("sd.pkId")
                ->from("StockDetail", "sd")
                ->where('sd.stockMaster = ' . $id);
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
     * @param type $date_from
     * @param type $dateto
     * @return boolean
     */
    public function stockGatepassSearch($date_from, $dateto) {

        $wh_id = $this->_identity->getWarehouseId();
        if (!empty($date_from) && !empty($dateto)) {
            $where[] = "DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($date_from) . "' AND '" . App_Controller_Functions::dateToDbFormat($dateto) . "'";
        }

        if (!empty($wh_id)) {
            $where[] = "sm.fromWarehouse = '" . $wh_id . "'";
        }

        $where[] = "sm.transactionType = '" . Model_TransactionTypes::TRANSACTION_ISSUE . "'";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sm.pkId,sm.transactionNumber,sm.transactionDate")
                ->from("StockMaster", "sm")
                ->where($where_s);
       
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }


    /**
     * Get Issue Voucher List Not Received
     *
     * @param integer $wh_id
     * @return array $arr_data
     * @uses Barcode API
     */
    public function getIssueVoucherListNotReceived($wh_id) {
        $from_date = Zend_Registry::get('api_from_date');
        $to_date = date("Y-m");

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sd")
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stocBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.staekholderItemPackSize", "sip")
                ->where("sm.toWarehouse = " . $wh_id)
                ->andWhere("sip.itemPackSize IN (" . Zend_Registry::get('barcode_products') . ")")
                ->andWhere("sm.transactionType = 2")
                ->andWhere("sd.isReceived = 0")
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m') BETWEEN '$from_date' AND '$to_date'");
        $rows = $str_sql->getQuery()->getResult();
        if (count($rows) > 0) {
            $arr_data = array();
            foreach ($rows as $row) {
                if ($row->getStockBatch()->getProductionDate() !== null) {
                    $prod_date = $row->getStockBatch()->getProductionDate()->format("Y-m-d");
                } else {
                    $prod_date = '';
                }

                $received_qty = 0;
                $rec_qty = $this->_em->getRepository("StockDetail")->findBy(array("isReceived" => $row->getPkId()));
                if (count($rec_qty) > 0) {
                    foreach ($rec_qty as $rec_row) {
                        $received_qty = $received_qty + $rec_row->getQuantity();
                    }
                }

                $arr_data[] = array(
                    'record_id' => $row->getPkId(),
                    'trans_no' => $row->getStockMaster()->getTransactionNumber(),
                    'arrival_date' => $row->getStockMaster()->getTransactionDate()->format("Y-m-d"),
                    'reference_no' => $row->getStockMaster()->getTransactionReference(),
                    'description' => $row->getStockMaster()->getComments(),
                    'gtin' => $row->getStockBatch()->getStakeholderItemPackSize()->getGtin(),
                    'quantity_per_pack' => $row->getStockBatch()->getStakeholderItemPackSize()->getQuantityPerPack(),
                    'item_pack_size_id' => $row->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId(),
                    'item_category' => $row->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId(),
                    'item_name' => $row->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemName(),
                    'batch_no' => $row->getStockBatch()->getNumber(),
                    'production_date' => $prod_date,
                    'expiry_date' => $row->getStockBatch()->getExpiryDate()->format("Y-m-d"),
                    'manufacturer' => $row->getStockBatch()->getStakeholderItemPackSize()->getStakeholder()->getStakeholderName(),
                    'manufacturer_id' => $row->getStockBatch()->getStakeholderItemPackSize()->getStakeholder()->getPkId(),
                    'stakeholder_item_pack_size_id' => $row->getStockBatch()->getStakeholderItemPackSize()->getPkId(),
                    'quantity' => ABS($row->getQuantity()),
                    'from_wh_name' => $row->getStockMaster()->getFromWarehouse()->getWarehouseName(),
                    'from_wh_id' => $row->getStockMaster()->getFromWarehouse()->getPkId(),
                    'to_wh_id' => $row->getStockMaster()->getToWarehouse()->getPkId(),
                    'to_wh_name' => $row->getStockMaster()->getToWarehouse()->getWarehouseName(),
                    'received_qty' => $received_qty
                );
            }
            return $arr_data;
        } else {
            return array("message" => "No record found");
        }
    }

    /**
     * Upload Received Quantity Via Scanner
     *
     * @param integer $wh_id
     * @return array $arr_data
     * @uses Barcode API
     */
    public function uploadReceivedQuantityViaScanner() {
        $params = $this->form_values;

        $rec_id = $params['rec_id'];
        $qty = $params['qty'];
        $location_id = $params['location_id'];
        $is_update = $params['update'];
        $vvmstage = $params['vvmstage'];
        $auth = $params['auth'];

        $stock_detail = $this->_em->getRepository("StockDetail")->find($rec_id);
        /**
         * Record must exists
         */
        if (count($stock_detail) == 0) {
            $result_msg = array("message" => "Selected record id $rec_id does not exist in pipeline consignments. Please update your data.");
            App_FileLogger::info($result_msg['message']);
            return $result_msg;
        }

        if ($is_update) {
            $detail_placements = $this->_em->getRepository("StockReceiveFromScanner")->findBy(array("stockDetail" => $rec_id));
            if (count($detail_placements) > 0) {
                foreach ($detail_placements as $row) {
                    $this->_em->remove($row);
                }
                $this->_em->flush();
            }
        }

        $plac_loc_id = $this->_em->getRepository("PlacementLocations")->find($location_id);
        /*
         * Add entry in Placement table
         */
        $stock_receive = new StockReceiveFromScanner();
        $stock_receive->setPlacementLocation($plac_loc_id);
        $stock_receive->setQuantity($qty);
        $vvms = $this->_em->getRepository("VvmStages")->find($vvmstage);
        $stock_receive->setVvmStage($vvms);



        $created_by = $this->_em->getRepository("Users")->findOneBy((array('auth' => $auth)));


        $stock_receive->setModifiedBy($created_by);
        $stock_receive->setModifiedDate(App_Tools_Time::now());
        $stock_receive->setCreatedBy($created_by);
        $stock_receive->setCreatedDate(App_Tools_Time::now());
        $this->_em->persist($stock_receive);
        $this->_em->flush();

        return array("message" => "success", "detail_id" => $stock_detail->getPkId());
    }

    /**
     * Get Unpicked Issue No
     *
     * @param type $wh_id
     * @return boolean
     */
    public function getUnpickedIssueNo($wh_id) {

        $from_date = Zend_Registry::get('api_from_date');
        $to_date = date("Y-m");
        $str_sql = "SELECT DISTINCT
        A.transaction_number,
        A.stc_master_pkid,
        A.transaction_date,
        A.warehouse_name,
        A.to_warehouse_id,
        A.from_warehouse_id
        FROM
        (
                SELECT
        stock_master.transaction_number,
        abs(Sum(stock_detail.quantity)) AS quantity,
        stock_detail.pk_id,
        stock_master.transaction_date,
        stock_master.to_warehouse_id,
        stock_master.from_warehouse_id,
        warehouses.warehouse_name,
        stock_master.pk_id AS stc_master_pkid,
        abs(GetPicked(stock_detail.pk_id)) AS place_quantity
        FROM
                stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id

        WHERE
        stock_master.transaction_type_id IN (" . Model_TransactionTypes::TRANSACTION_ISSUE . ")
        AND stock_master.from_warehouse_id = " . $wh_id . "
        AND warehouses.status = 1 AND
        DATE_FORMAT(stock_master.transaction_date,'%Y-%m') BETWEEN '$from_date' AND '$to_date' AND
        item_pack_sizes.item_category_id = 1 AND
        stock_detail.`temporary` = 0 AND stock_master.draft = 0
        GROUP BY
        stock_detail.stock_batch_warehouse_id,
        stock_detail.stock_master_id,
        stock_detail.pk_id
        having quantity > place_quantity
        ORDER BY
        stock_master.transaction_date DESC
        ) A";

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
     * Get Unplaced Voucher No
     *
     * @param type $wh_id
     * @return boolean
     */
    public function getUnplacedVoucherNo($wh_id) {  //updated
        $from_date = Zend_Registry::get('api_from_date');
        $to_date = date("Y-m");

        $str_sql = "SELECT DISTINCT
                        A.transaction_number,
                        A.stc_master_pkid,
                        A.transaction_date,
                        A.warehouse_name,
                        A.to_warehouse_id,
                        A.from_warehouse_id
                        FROM
                        (
                        SELECT
                                stock_master.transaction_number,
                                Sum(stock_detail.quantity) AS quantity,
                                stock_detail.pk_id,
                                stock_master.transaction_date,
                                stock_master.to_warehouse_id,
                                stock_master.from_warehouse_id,
                                warehouses.warehouse_name,
                                stock_master.pk_id AS stc_master_pkid,
                                GetPlaced(stock_detail.pk_id) AS place_quantity
                        FROM
                                stock_master
                        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
                        WHERE
                        stock_master.transaction_type_id = " . Model_TransactionTypes :: TRANSACTION_RECIEVE . "
                        AND stock_master.to_warehouse_id = " . $wh_id . "
                        AND warehouses.status=1 AND
                        DATE_FORMAT(stock_master.transaction_date,'%Y-%m') BETWEEN '$from_date' AND '$to_date'
                            AND stock_batch.item_pack_size_id IN (" . Zend_Registry::get('barcode_products') . ")
                        GROUP BY
                                stock_detail.stock_batch_warehouse_id,
                                stock_detail.stock_master_id
                        HAVING
                                quantity > place_quantity
                        ORDER BY
                                transaction_date DESC
        ) A";

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
     * Detail Data Issueno
     *
     * @param type $stockmasterId
     * @return boolean
     */
    public function detailDataIssueno($stockmasterId) { //updated
// Improved Query
        $str_sql = "SELECT
        stock_master.transaction_number,
        abs(Sum(stock_detail.quantity)) AS quantity,
        stock_detail.pk_id,
        stock_master.pk_id AS stc_master_pkid,
        stock_batch.number,
        stock_batch.pk_id as batch_id,
        stock_batch.expiry_date,
        item_pack_sizes.item_name,
        stakeholder_item_pack_sizes.item_pack_size_id as ItemID,
        pack_info.quantity_per_pack,
        item_pack_sizes.item_category_id,
        abs(GetPicked(stock_detail.pk_id)) as place_quantity
        FROM
        stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
         stock_master.transaction_type_id NOT IN (" . Model_TransactionTypes::TRANSACTION_RECIEVE . "," . Model_TransactionTypes::LOST_RECOVERED . "," . Model_TransactionTypes::PHYSICALLY_FOUND . ")
         AND stock_detail.stock_master_id =" . $stockmasterId . " AND
        item_pack_sizes.item_category_id = 1
        GROUP BY
            stock_detail.stock_batch_warehouse_id,
            stock_detail.stock_master_id,
            stock_detail.pk_id";

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
     * Detail Data Issue no Vaccines
     *
     * @param type $stockmasterId
     * @return boolean
     */
    public function detailDataIssuenoVaccines($stockmasterId) {

        $str_sql = "SELECT
        stock_master.transaction_number,
        abs(Sum(stock_detail.quantity)) AS quantity,
        stock_detail.pk_id,
        stock_master.pk_id AS stc_master_pkid,
        stock_batch.number,
        stock_batch_warehouses.pk_id as batch_id,
        stock_batch.expiry_date,
        item_pack_sizes.item_name,
        item_pack_sizes.pk_id as ItemID,
        stakeholder_item_pack_sizes.quantity_per_pack,
        item_pack_sizes.item_category_id,
        abs(GetPicked(stock_detail.pk_id)) as place_quantity
        FROM
        stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON stock_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
        stock_master.transaction_type_id = " . Model_TransactionTypes :: TRANSACTION_ISSUE . "
        AND stock_detail.stock_master_id =" . $stockmasterId . "
        GROUP BY
                                stock_detail.stock_batch_warehouse_id,
                                stock_detail.stock_master_id";


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
     * Detail Data Receive no
     *
     */
    public function detailDataReceiveno($stockmasterId) {

        $str_sql = "SELECT
        stock_master.transaction_number,
        abs(Sum(stock_detail.quantity)) AS quantity,
        stock_detail.pk_id,
        stock_master.pk_id AS stc_master_pkid,
        stock_batch.number,
        stock_batch_warehouses.pk_id as batch_id,
        stock_batch.expiry_date,
        item_pack_sizes.item_name,
        item_pack_sizes.item_category_id,
        item_pack_sizes.pk_id as ItemID,
        stakeholder_item_pack_sizes.quantity_per_pack,
        stakeholder_item_pack_sizes.pk_id as stakeholder_item_pack_size_id,
        GetPlaced(stock_detail.pk_id) as place_quantity
        FROM
        stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON stock_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
        stock_master.transaction_type_id = " . Model_TransactionTypes :: TRANSACTION_RECIEVE . "
        AND stock_detail.stock_master_id =" . $stockmasterId . "
            AND item_pack_sizes.pk_id IN (" . Zend_Registry::get('barcode_products') . ")
        GROUP BY
                                stock_detail.stock_batch_warehouse_id,
                                stock_detail.stock_master_id
                                having quantity > place_quantity";

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
     * Get Issue Voucher Items List
     *
     */
    public function getIssueVoucherItemsList($stockmasterId) {

        $str_sql = "SELECT
            stock_master.transaction_number,
            abs(Sum(stock_detail.quantity)) AS quantity,
            stock_detail.pk_id,
            stock_master.pk_id AS stc_master_pkid,
            stock_batch.number,
            stock_batch_warehouses.pk_id as batch_id,
            stock_batch.expiry_date,
            item_pack_sizes.item_name,
            item_pack_sizes.item_category_id,
            item_pack_sizes.pk_id as ItemID,
            pack_info.quantity_per_pack,
            abs(GetPicked(stock_detail.pk_id)) as place_quantity,
            stakeholder_item_pack_sizes.pk_id as stakeholder_item_pack_size_id
        FROM
        stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON stock_batch.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
             stock_master.transaction_type_id = " . Model_TransactionTypes :: TRANSACTION_ISSUE . "
             AND stock_detail.stock_master_id =" . $stockmasterId . "
             AND item_pack_sizes.pk_id IN (" . Zend_Registry::get('barcode_products') . ")
        GROUP BY
                                stock_detail.stock_batch_warehouse_id,
                                stock_detail.stock_master_id
                                having quantity > place_quantity";

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
     * Get Pending Receive
     *
     */
    public function getPendingReceive() {
        $wh_id = $this->_identity->getWarehouseId();
        $province_id = $this->_identity->getProvinceId();
        $district_id = $this->_identity->getDistrictId();
        if ($wh_id == 128) {
            $date_in = '2015-08-31';
        } else if ($wh_id == 9188) {
            $date_in = '2016-01-31';
        } else if ($wh_id == 123) {
            $date_in = '2015-12-31';
        } else if ($wh_id == 3918) {
            $date_in = '2014-12-31';
        } else if ($wh_id == 9689 || $wh_id == 95) {
            $date_in = '2015-12-31';
        } else if ($wh_id == 3919) {
            $date_in = '2015-12-31';
        } else if ($wh_id == 163) {
            $date_in = '2015-12-31';
        } else if ($wh_id == 126) {
            $date_in = '2015-10-31';
        }  else if ($district_id == 28 || $district_id == 32 || $district_id == 33 || $district_id == 44 || $district_id == 46 || $district_id == 50 || $district_id == 53 || $district_id == 65 || $district_id == 83 || $district_id == 84 || $district_id == 97 || $district_id == 107 || $district_id == 111 || $district_id == 112 || $district_id == 123 || $district_id == 127 || $district_id == 130 || $district_id == 151 || $district_id == 152 || $district_id == 153 || $district_id == 154 || $district_id == 156 || $district_id == 179 ) {
            $date_in = '2017-12-31';
        } else if ($district_id == 39 || $district_id == 66 || $district_id == 69 || $district_id == 94 || $district_id == 99 || $district_id == 118 || $district_id == 131 || $district_id == 132 || $district_id == 140 || $district_id == 142 || $district_id == 158 || $district_id == 161 || $district_id == 174  ) {
            $date_in = '2018-03-31';
        } else if ($province_id == 6 ) {
            $date_in = '2017-12-31';
        } else if ($province_id == 4 ) {
            $date_in = '2017-12-31';
        }else if ($province_id == 7 ) {
            $date_in = '2017-12-31';
        }else {
            $date_in = '2014-07-31';
        }

        $str_sql = "SELECT DISTINCT
                        stock_master.transaction_number
                FROM
                        stock_master
                INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id

                WHERE
                        stock_master.to_warehouse_id = $wh_id
                AND (
                        stock_detail.is_received IS NULL
                        OR stock_detail.is_received = 0
                )
                AND stock_master.transaction_type_id = 2
                AND stock_master.transaction_date > '$date_in' AND
                stock_master.draft = 0 AND
                stock_detail.`temporary` = 0";

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
     * Get Data Entry Status Federal
     *
     */
    public function getDataEntryStatusFederal() {

        $roleIds = array(3, 4, 6, 7, 17);
        if ($this->form_values['wh_type'] == 1 || in_array($this->form_values['role_id'], $roleIds)) {
            $str_qry1 = "SELECT
                    *
                    FROM
                    (
                            SELECT
                        stock_master.pk_id,
                        stock_master.transaction_date AS transactionDate,
                        warehouses.warehouse_name,
                        warehouses.pk_id AS wh_id,
                        stock_master.from_warehouse_id AS wh_from_id,
                        stock_master.transaction_type_id
                FROM
                        stock_detail
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN warehouses ON (
                        stock_master.to_warehouse_id = warehouses.pk_id
                        OR stock_master.from_warehouse_id = warehouses.pk_id
                )
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                WHERE
                        stakeholders.pk_id = 1
                AND stakeholders.geo_level_id = 1
                AND stock_master.draft = 0
                AND stakeholders.stakeholder_type_id = 1
                AND warehouses.`status` = 1
                AND stock_master.transaction_type_id = 2
                ORDER BY
                        transactionDate DESC
                LIMIT 1
                ) A
        UNION
                (
                        SELECT
                        stock_master.pk_id,
                        stock_master.transaction_date AS transactionDate,
                        warehouses.warehouse_name,
                        warehouses.pk_id AS wh_id,
                        stock_master.from_warehouse_id AS wh_from_id,
                        stock_master.transaction_type_id
                FROM
                        stock_detail
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN warehouses ON (
                        stock_master.to_warehouse_id = warehouses.pk_id
                        OR stock_master.from_warehouse_id = warehouses.pk_id
                )
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                WHERE
                        stakeholders.pk_id = 1
                AND stakeholders.geo_level_id = 1
                AND stock_master.draft = 0
                AND stakeholders.stakeholder_type_id = 1
                AND warehouses.`status` = 1
                AND stock_master.transaction_type_id = 1
                ORDER BY
                        transactionDate DESC
                LIMIT 1
        )";

            $row1 = $this->_em_read->getConnection()->prepare($str_qry1);
            $row1->execute();
            return $row1->fetchAll();
        }
    }

    /**
     * Get Data Entry Status Provincial
     *
     */
    public function getDataEntryStatusProvincial() {
        $roleIds = array(3, 4, 6, 7, 17);
        if ($this->form_values['wh_type'] == 2 || in_array($this->form_values['role_id'], $roleIds)) {
            $sel_prov = $this->form_values['province'];
            if ($this->form_values['role_id'] != 3) {
                $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
            } else {
                $provFilter = "";
            }

            $str_qry2 = "SELECT
                *
               FROM
                (
                 SELECT
                  stock_master.transaction_date as transactionDate,
                  stock_master.pk_id,
                  warehouses.warehouse_name,
                  stock_master.transaction_type_id,
                  warehouses.pk_id AS wh_id,
                  stock_master.from_warehouse_id AS wh_from_id
                 FROM
                  stock_detail
                 INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                 INNER JOIN warehouses ON (
                  stock_master.to_warehouse_id = warehouses.pk_id
                  OR stock_master.from_warehouse_id = warehouses.pk_id
                 )
                 INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                 WHERE
                  stakeholders.geo_level_id = 2
                 AND warehouses. STATUS = 1
                 $provFilter
                 ORDER BY
                  warehouses.warehouse_name,
                  stock_master.transaction_date DESC
                ) A
               GROUP BY
                warehouse_name";

            $row2 = $this->_em_read->getConnection()->prepare($str_qry2);
            $row2->execute();
            return $row2->fetchAll();
        }
    }

    /**
     * Get Data Entry Status District
     *
     */
    public function getDataEntryStatusDistrict() {

        $roleIds = array(3, 4, 6, 7, 17);
        if (in_array($this->form_values['role_id'], $roleIds)) {

            $sel_prov = $this->form_values['province'];
            if ($sel_prov == "all") {
                $sel_dist = "";
            } else {
                $sel_dist = $this->form_values['district'];
            }

            if ($this->form_values['role_id'] != 3) {
                $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';

                $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
            } else {
                $provFilter = "";
                $distFilter = "";
            }
            $str_qry3 = "SELECT
                *
               FROM
                (SELECT
                stock_master.pk_id,
                stock_master.transaction_date as transactionDate,
                warehouses.warehouse_name,
                stock_master.transaction_type_id,
                warehouses.pk_id as wh_id,
                stock_master.from_warehouse_id as wh_from_id
                FROM
                 stock_detail

                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                  INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id OR stock_master.from_warehouse_id = warehouses.pk_id
                INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                WHERE
                stakeholders.geo_level_id = 4
                AND
                warehouses.status = 1
                $provFilter
                $distFilter
                ORDER BY
                  warehouses.warehouse_name,
                  stock_master.transaction_date DESC
                ) A
               GROUP BY
                warehouse_name";

            $row3 = $this->_em_read->getConnection()->prepare($str_qry3);
            $row3->execute();
            return $row3->fetchAll();
        }
    }

    /**
     * Get Data Entry Status Tehsil
     *
     */
    public function getDataEntryStatusTehsil() {

        if ($this->form_values['role_id'] == 7 || $this->form_values['wh_type'] == 5 || $this->form_values['role_id'] == 3 || $this->form_values['role_id'] == 17) {


            $sel_prov = $this->form_values['province'];
            if ($sel_prov == 'all') {
                $sel_tehsil = "";
                $sel_dist = "";
            } else {
                $sel_tehsil = $this->form_values['tehsil'];
                $sel_dist = $this->form_values['district'];
            }

            if ($this->form_values['role_id'] != 3) {
                $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';

                $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
            } else {
                $provFilter = "";
                $distFilter = "";
            }
            if ($this->form_values['role_id'] != 3) {
                $tehsilFilter = (!empty($sel_tehsil) && $sel_tehsil != 'all') ? " AND warehouses.location_id = $sel_tehsil" : '';
            } else {
                $tehsilFilter = '';
            }
            $str_qry3 = "SELECT
                *
               FROM
                (SELECT
                stock_master.pk_id,
                stock_master.transaction_date as transactionDate,
                warehouses.warehouse_name,
                stock_master.transaction_type_id,
                   warehouses.pk_id as wh_id,
                stock_master.from_warehouse_id as wh_from_id
                FROM
                stock_detail

                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                  INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id OR stock_master.from_warehouse_id = warehouses.pk_id
                INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                WHERE
                stakeholders.geo_level_id = 5
                AND
                warehouses.status = 1
                $provFilter
                $distFilter
                $tehsilFilter
                ORDER BY
                  warehouses.warehouse_name,
                  stock_master.transaction_date DESC
                ) A
               GROUP BY
                warehouse_name ";

            $row3 = $this->_em_read->getConnection()->prepare($str_qry3);
            $row3->execute();
            return $row3->fetchAll();
        }
    }

    /**
     * Get Batch Data
     *
     */
    public function getBatchData() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sd')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sm.fromWarehouse = " . $form_values['from_wh'])
                ->andWhere("sm.toWarehouse = " . $form_values['to_wh'])
                ->andWhere("sip.itemPackSize = " . $form_values['product'])
                ->andWhere("sbw.pkId = " . $form_values['batch_id'])
                ->andWhere("sm.transactionType = 2")
                ->orderBy("sd.quantity", "DESC");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Receive Batch Data
     *
     */
    public function getReceiveBatchData() {

        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sd')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sm.fromWarehouse = " . $form_values['from_wh'])
                ->andWhere("sm.toWarehouse = " . $form_values['to_wh'])
                ->andWhere("sip.itemPackSize = " . $form_values['product'])
                ->andWhere("sbw.number = '" . $form_values['batch_number'] . "'")
                ->andWhere("sm.transactionType = 1")
                ->orderBy("sd.quantity", "ASC");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Product Ledger
     *
     */
    public function getProductLedger() {

        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sd')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sip.itemPackSize = " . $form_values['product'])
                ->andWhere("sbw.warehouse = " . $warehouse)
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($form_values['to_date']) . "'")
                ->orderBy("sm.transactionDate")
                ->addOrderBy("sd.adjustmentType")
                ->addOrderBy("sd.quantity");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Product OB
     *
     */
    public function getProductOB() {
        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('SUM(sd.quantity) as qty')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sip.itemPackSize = " . $form_values['product'])
                ->andWhere("sbw.warehouse = " . $warehouse)
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') < '" . App_Controller_Functions::dateToDbFormat($form_values['from_date']) . "'")
                ->orderBy("sm.transactionDate", "ASC");


        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['qty'];
        } else {
            return false;
        }
    }

    /**
     * Get Product OB
     *
     */
    public function getProductCB() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('SUM(sd.quantity) as qty')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.warehouse", "w")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->where("sip.itemPackSize = " . $form_values['product'])
                ->andWhere("w.location = " . $form_values['location_id'])
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') <= '" . App_Controller_Functions::dateToDbFormat($form_values['date']) . "'")
                ->orderBy("sm.transactionDate", "ASC");

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['qty'];
        } else {
            return false;
        }
    }

    /**
     * Get Batch OB
     *
     */
    public function getBatchOB() {
        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }

        $operator = '<';

        if ($form_values['type'] == 'CB') {
            $operator = '<=';
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('SUM(sd.quantity) as qty, sb.number, sb.pkId, (SUM(sd.quantity)*ips.numberOfDoses) as qty_doses')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "sm")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->where("sip.itemPackSize = " . $form_values['product'])
                ->andWhere("sbw.warehouse = " . $warehouse)
                ->andWhere("DATE_FORMAT(sm.transactionDate,'%Y-%m-%d') $operator '" . App_Controller_Functions::dateToDbFormat($form_values['from_date']) . "'")
                ->groupBy("sbw.pkId")
                ->having("qty <> 0");
        $row = $str_sql->getQuery()->getResult();

        if (count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Product OB Before Adjust
     *
     */
    public function getProductOBBeforeAdjust() {
        $str_sql = "SELECT
                            batch_summary_before_adjust.quantity,
                            batch_summary_before_adjust.doses_per_vial
                    FROM
                            batch_summary_before_adjust
                    WHERE
                            batch_summary_before_adjust.product_id = " . $this->form_values['product'];

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return array(
                'vials' => $result[0]['quantity'],
                'doses' => $result[0]['quantity'] * $result[0]['doses_per_vial']
            );
        } else {
            return array(
                'vials' => 0,
                'doses' => 0
            );
        }
    }

    /**
     *
     */
    function editLedgerTranscationDate() {
        $id = $this->form_values['id'];
        $date = $this->form_values['date'];

        $stockMaster = $this->_em->getRepository("StockMaster")->find($id);
        $previous_month = $stockMaster->getTransactionDate()->format("m");
        $previous_year = $stockMaster->getTransactionDate()->format("Y");

        if (count($stockMaster) > 0) {
            $trans_nature = $stockMaster->getTransactionType()->getNature();
            $trans_type = $stockMaster->getTransactionType()->getPkId();
            if ($trans_nature == '+') {
                $wh_id = $stockMaster->getToWarehouse()->getPkId();
                $time = " 00:00:00";
            } else {
                $wh_id = $stockMaster->getFromWarehouse()->getPkId();
                $time = " 23:59:59";
            }

            $trans = $this->getTransactionNumber($trans_type, $date, $wh_id, $stockMaster->getPkId());
            $stockMaster->setTransactionDate(new DateTime(App_Controller_Functions::dateToDbFormat($date) . $time));
            $stockMaster->setTransactionNumber($trans['trans_no']);
            $stockMaster->setTransactionCounter($trans['id']);

            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $stockMaster->setModifiedBy($created_by);
            $stockMaster->setModifiedDate(App_Tools_Time::now());

            $this->_em->persist($stockMaster);
            $this->_em->flush();

            // REP Update data for updating warehouse_data table
            $wh_data = new Model_HfDataMaster();
            $stock_detail = $this->_em->getRepository("StockDetail")->findBy(array("stockMaster" => $id));

            if (count($stock_detail) > 0) {
                // For Updated Date
                foreach ($stock_detail as $row1) {
                    $wh_data->form_values = array(
                        'report_month' => $row1->getStockMaster()->getTransactionDate()->format("m"),
                        'report_year' => $row1->getStockMaster()->getTransactionDate()->format("Y"),
                        'item_id' => $row1->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId(),
                        'warehouse_id' => $wh_id,
                        'created_by' => $row1->getStockMaster()->getCreatedBy()->getPkId()
                    );
                    $wh_data->adjustStockReport();
                }

// For Previous Date
                foreach ($stock_detail as $row2) {
                    $wh_data->form_values = array(
                        'report_month' => $previous_month,
                        'report_year' => $previous_year,
                        'item_id' => $row2->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getPkId(),
                        'warehouse_id' => $wh_id,
                        'created_by' => $row2->getStockMaster()->getCreatedBy()->getPkId()
                    );
                    $wh_data->adjustStockReport();
                }
            }
        }

        return true;
    }

    /**
     * Get Batch Shelf Life
     *
     */
    public function getBatchShelfLife() {
        $form_values = $this->form_values;

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('sb.number,sb.expiryDate')
                ->from("StockBatch", "sb")
                ->where("sb.number = '" . $form_values['batch_no'] . "' ");

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Product Physical Quantity
     *
     */
    public function getProductPhysicalQuantity() {

        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('pst')
                ->from("PhysicalStockTakingDetail", "pst")
                ->where("pst.itemPackSize = " . $form_values['product'])
                ->andWhere("pst.warehouse =" . $warehouse)
                ->andWhere("pst.physicalStockTaking = " . Model_PhysicalStockTakingDetail::STOCKID)
                ->groupBy("pst.batchNumber")
                ->orderBy("pst.quantity", "DESC");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Physical Batch Quantity
     *
     */
    public function getPhysicalBatchQuantity($batch_id) {

        $str_sql = $this->_em->createQueryBuilder()
                ->select('SUM(pst.quantity) as qty')
                ->from("PhysicalStockTakingDetail", "pst")
                ->where("pst.stockBatch = " . $batch_id)
                ->andWhere("pst.physicalStockTaking = " . Model_PhysicalStockTakingDetail::STOCKID);

        $row = $str_sql->getQuery()->getResult();
        return $row[0]['qty'];
    }

    /**
     * Get Product Physical Stock Taking Quantity
     *
     */
    public function getProductPhysicalStockTakingQuantity() {

        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('pst')
                ->from("PhysicalStockTakingDetail", "pst")
                ->leftJoin("pst.stockBatch", "sb")
                ->innerJoin("pst.physicalStockTaking", "ps")
                ->where("pst.itemPackSize = " . $form_values['product'])
                ->andWhere("pst.warehouse =" . $warehouse)
                ->andWhere("pst.physicalStockTaking = " . Model_PhysicalStockTakingDetail::STOCKID);

        if (!empty($form_values['description'])) {
            $str_sql->andWhere("ps.pkId =" . $form_values['description']);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Pipeline Product Report
     *
     */
    public function getPipelineProductReport() {
        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();

        $today_date = new DateTime();
        $last_year = $today_date->modify("-1 year");
        $from_date = $last_year->format('Y-m-d');
        $to_date = date("Y-m-d");

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }

        $str_qry = "SELECT
        A.item_name,
        A.amc*6 calculated_amc,
        B.soh,
        A.amc,
        IFNULL(C.quantity,1) quantity,
        C.eta
      FROM
        (
                SELECT
                        IFNULL(
                                Sum(stock_detail.quantity),
                                0
                        ) * pack_info.number_of_doses AS issue,
                        stakeholder_item_pack_sizes.item_pack_size_id,
                        item_pack_sizes.item_name,
                        epi_amc.amc
                FROM
                        stock_detail
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN item_pack_sizes ON stock_batch.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN epi_amc ON epi_amc.item_id = item_pack_sizes.pk_id
                WHERE
                        stock_master.transaction_type_id = 2
                AND stock_master.from_warehouse_id = $warehouse
                AND DATE_FORMAT(
                        stock_master.transaction_date,
                        '%Y-%m-%d'
                ) BETWEEN '$from_date'
                AND '$to_date' AND
                item_pack_sizes.multiplier = 1 AND epi_amc.warehouse_id = $warehouse
                GROUP BY
                        stakeholder_item_pack_sizes.item_pack_size_id
                ORDER BY item_pack_sizes.list_rank
        ) A
    INNER JOIN (
        SELECT
                IFNULL(
                        Sum(stock_detail.quantity),
                        0
                ) * pack_info.number_of_doses AS soh,
                stakeholder_item_pack_sizes.item_pack_size_id,
                item_pack_sizes.item_name
        FROM
                stock_detail
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN item_pack_sizes ON stock_batch.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                stock_batch_warehouses.warehouse_id = $warehouse
        GROUP BY
                stakeholder_item_pack_sizes.item_pack_size_id
    ) B ON A.item_pack_size_id = B.item_pack_size_id
    INNER JOIN
    (SELECT
        DATE_FORMAT(
                shipments.shipment_date,
                '%M, %Y'
        ) eta,
        Sum(
                shipments.shipment_quantity
        ) AS quantity,
        shipments.item_pack_size_id,
        item_pack_sizes.item_name,
        stakeholder_activities.activity
    FROM
        shipments
    INNER JOIN item_pack_sizes ON shipments.item_pack_size_id = item_pack_sizes.pk_id
    INNER JOIN stakeholder_activities ON shipments.stakeholder_activity_id = stakeholder_activities.pk_id
    GROUP BY
        shipments.item_pack_size_id,
        DATE_FORMAT(
                shipments.shipment_date,
                '%Y-%m'
        ),
        shipments.stakeholder_activity_id) C
    ON C.item_pack_size_id = B.item_pack_size_id WHERE C.eta > '$to_date'";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }
 

    
   /**
     * Get Sufficient Product Report
     *
     */
    public function getSufficientProductReport() {
        $form_values = $this->form_values;
        $role_id = $this->_identity->getRoleId();
        $warehouse_id = $this->_identity->getWarehouseId();
        $today_date = new DateTime();
        $last_year = $today_date->modify("-1 year");
        $from_date = $last_year->format('Y-m-d');
        $to_date = date("Y-m-d");
        $year_d = date('Y');

        if ($role_id == 2 || $role_id == 22) {
            $warehouse = $form_values['warehouse'];
        } else {
            $warehouse = $this->_identity->getWarehouseId();
        }
        if ($warehouse_id == 159) {
            $ye = "AND epi_amc.amc_year = '$year_d'";
        }

//      $str_qry = "SELECT
//        A.item_name,
//        B.soh,
//        A.amc,
//        A.item_pack_size_id,
//        A.warehouse_id
//    FROM
//        (
//                SELECT
//                        IFNULL(
//                                Sum(stock_detail.quantity),
//                                0
//                        ) * item_pack_sizes.number_of_doses AS issue,
//                        stakeholder_item_pack_sizes.item_pack_size_id,
//                        item_pack_sizes.item_name,
//                        epi_amc.amc,
//                        epi_amc.warehouse_id
//                FROM
//                        stock_detail
//                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
//                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
//                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
//                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
//                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
//                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
//
//                INNER JOIN epi_amc ON epi_amc.item_id = item_pack_sizes.pk_id
//                WHERE
//                        stock_master.transaction_type_id = 2
//                AND stock_master.from_warehouse_id = $warehouse
//                AND DATE_FORMAT(
//                        stock_master.transaction_date,
//                        '%Y-%m-%d'
//                ) BETWEEN '$from_date'
//                AND '$to_date'
//                AND item_pack_sizes.multiplier = 1
//                AND epi_amc.warehouse_id = $warehouse
//                     $ye
//                GROUP BY
//                        stakeholder_item_pack_sizes.item_pack_size_id
//                ORDER BY item_pack_sizes.list_rank
//        ) A
//    INNER JOIN (
//        SELECT
//                IFNULL(
//                        Sum(stock_detail.quantity),
//                        0
//                ) * item_pack_sizes.number_of_doses AS soh,
//                stakeholder_item_pack_sizes.item_pack_size_id,
//                item_pack_sizes.item_name
//        FROM
//                stock_detail
//        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
//        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
//        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
//        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
//        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
//        INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
//
//        WHERE
//                stock_batch_warehouses.warehouse_id = $warehouse
//        GROUP BY
//                stakeholder_item_pack_sizes.item_pack_size_id
//) B ON A.item_pack_size_id = B.item_pack_size_id";

        $str_qry = "SELECT
		A.item_name,
		B.soh,
		A.amc,
		A.item_pack_size_id,
		A.warehouse_id
	FROM
		(
			SELECT
				item_pack_sizes.pk_id item_pack_size_id,
				item_pack_sizes.item_name,
				epi_amc.amc,
				epi_amc.warehouse_id
			FROM
				item_pack_sizes
			INNER JOIN epi_amc ON epi_amc.item_id = item_pack_sizes.pk_id
			WHERE
				epi_amc.warehouse_id = $warehouse
			$ye
		) A
	INNER JOIN (
		SELECT
			IFNULL(
				Sum(stock_detail.quantity),
				0
			) * item_pack_sizes.number_of_doses AS soh,
			stakeholder_item_pack_sizes.item_pack_size_id,
			item_pack_sizes.item_name
		FROM
			stock_detail
		INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
		INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
		WHERE
			stock_batch_warehouses.warehouse_id = $warehouse
		GROUP BY
			stakeholder_item_pack_sizes.item_pack_size_id
	) B ON A.item_pack_size_id = B.item_pack_size_id";
      
      
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }



    /**
     * Priority Vaccines Distribution
     *
     */
    public function priorityVaccinesDistribution() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 2:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 3:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 4:
                $where = " AND
                    (placements.vvm_stage >= 3 OR
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            default :
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%b, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(placements.quantity) AS quantity,
                            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
                            cold_chain.asset_id as coldroom,
                            placement_locations.pk_id as coldroom_id,
                            IF(item_pack_sizes.vvm_group_id=1,IFNULL(vvm_stages.pk_id, 1),vvm_stages.vvm_stage_value) vvm
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    $where
                    GROUP BY
                            placements.vvm_stage,
                            placements.stock_batch_warehouse_id,
                            cold_chain.asset_id
                    HAVING
                            quantity > 0
                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Priority Vaccines Distribution
     *
     */
    public function priorityNonVaccinesDistribution() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND((DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 2:
                $where = " AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 3:
                $where = " AND
                   DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 4:
                $where = " AND(
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            default :
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(stock_batch.expiry_date,'%b, %Y') AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(stock_batch_warehouses.quantity) AS quantity,
                            SUM(stock_batch_warehouses.quantity) * item_pack_sizes.number_of_doses AS doses,
                            '' AS coldroom,
                            '' AS coldroom_id,
                            '' AS vvm,
                            stock_batch_warehouses.pk_id
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id IN (2,3) $where
                    GROUP BY
                            stock_batch_warehouses.pk_id
                    HAVING
                            quantity > 0
                    ORDER BY
                    item_pack_sizes.list_rank,
                    stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Priority Vaccines Distribution Summary
     *
     */
    public function priorityVaccinesDistributionSummary() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 2:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 3:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 4:
                $where = " AND
                    (placements.vvm_stage >= 3 OR
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            default:
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%M, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(placements.quantity) AS quantity,
                            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
                            IFNULL(vvm_stages.pk_id, 1) AS vvm,
                            vvm_stages.vvm_stage_value
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    $where
                    GROUP BY
                            item_pack_sizes.pk_id
                    HAVING
                            quantity > 0
                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Priority Vaccines Distribution Summary
     *
     */
    public function priorityNonVaccinesDistributionSummary() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND((
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 2:
                $where = " AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 3:
                $where = " AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 4:
                $where = " AND
                    (DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            default:
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%M, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(stock_batch_warehouses.quantity) AS quantity,
                            SUM(stock_batch_warehouses.quantity) * item_pack_sizes.number_of_doses AS doses,
                            1 AS vvm,
                            'NA' AS vvm_stage_value
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id IN (2,3)
                    $where
                    GROUP BY
                            item_pack_sizes.pk_id
                    HAVING
                            quantity > 0
                    ORDER BY
                    item_pack_sizes.list_rank,
                    stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Stock Issuance By Date
     *
     */
    public function getStockIssuanceByDate() {
        $form_values = $this->form_values;
        $wh_id = $form_values['warehouse'];
        if (empty($wh_id)) {
            $wh_id = $this->_identity->getWarehouseId();
        }

        $date = $form_values['year'] . "-" . str_pad($form_values['month'], 2, '0', STR_PAD_LEFT);

        $str_qry = "SELECT
                            item_pack_sizes.item_name,
                            ABS(IFNULL(SUM(stock_detail.quantity),0)) AS issue_vials,
                            ABS(IFNULL(SUM(stock_detail.quantity),0)) * item_pack_sizes.number_of_doses as issue_doses,
                            item_pack_sizes.number_of_doses as doses_per_vial
                    FROM
                            stock_detail
                            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                            INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id

                    WHERE
                            stock_master.transaction_type_id = 2 AND
                            stock_batch_warehouses.warehouse_id = $wh_id AND
                            DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date' AND
                            item_pack_sizes.item_category_id = 1
                    GROUP BY
                            stakeholder_item_pack_sizes.item_pack_size_id
                    ORDER BY
                            item_pack_sizes.list_rank ASC";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Stock Report By Date
     *
     */
    public function getStockReportByDate() {
        $form_values = $this->form_values;
        if (!empty($form_values['warehouse'])) {
            $wh_id = $form_values['warehouse'];
        } else {
            $wh_id = $this->_identity->getWarehouseId();
        }

        $date = $form_values['year'] . "-" . str_pad($form_values['month'], 2, '0', STR_PAD_LEFT);

        $str_qry = "SELECT
                            item_pack_sizes.item_name,
                            ABS(IFNULL(SUM(stock_detail.quantity),0)) AS cb_vials,
                            ABS(IFNULL(SUM(stock_detail.quantity),0)) * item_pack_sizes.number_of_doses as cb_doses,
                            item_pack_sizes.number_of_doses as doses_per_vial
                    FROM
                            stock_detail
                            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                            INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id

                    WHERE
                            stock_batch_warehouses.warehouse_id = $wh_id AND
                            DATE_FORMAT(stock_master.transaction_date,'%Y-%m') <= '$date' AND
                            item_pack_sizes.item_category_id = 1
                    GROUP BY
                            stakeholder_item_pack_sizes.item_pack_size_id
                    ORDER BY
                            item_pack_sizes.list_rank ASC";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Stock Issue Voucher List
     *
     */
    public function getStockIssueVoucherList() {
        $form_values = $this->form_values;

        $wh_id = $this->_identity->getWarehouseId();

        $date_in = $form_values['year'] . "-" . $form_values['month'];
        $date_in = date('Y-m', strtotime($date_in));

        $str_qry = "SELECT
            A.pk_id,
            B.warehouse_name,
            A.transaction_number,
            A.transaction_date,
            B.receiver_warehouse_id,
            A.count_voucher
            FROM
            (
            SELECT
            stock_master.pk_id,
            stock_master.transaction_date,
            stock_master.transaction_number,
            stock_master.from_warehouse_id,
            stock_master.to_warehouse_id,
            Count(stock_master.transaction_number) as count_voucher
            FROM
            stock_master
            WHERE
            stock_master.from_warehouse_id = '$wh_id'
            AND Date_format(stock_master.transaction_date,'%Y-%m') = '$date_in'
            AND stock_master.transaction_type_id = 2
              Group By stock_master.from_warehouse_id
            ) A

            RIGHT JOIN
            (SELECT
            distribution_plan.sender_warehouse_id,
            distribution_plan.receiver_warehouse_id,
            warehouses.warehouse_name
            FROM
            distribution_plan
            INNER JOIN warehouses ON distribution_plan.receiver_warehouse_id = warehouses.pk_id
            WHERE
            distribution_plan.sender_warehouse_id = '$wh_id' AND warehouses.status='" . parent::ACTIVE . "') B  ON A.to_warehouse_id = B.receiver_warehouse_id
            ORDER BY warehouse_name";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Add Stock Master 1
     *
     */
    public function addStockMaster1($array) {
        $to_warehouse_id = $array['hdn_receive_warehouse_id'];
        $from_warehouse_id = $this->_identity->getWarehouseId();


        $transaction_date = App_Controller_Functions::dateToDbFormat($array['transaction_date']);
        $str_qry = "SELECT stock_master.pk_id
                            FROM
                            stock_master
                            where
                            stock_master.to_warehouse_id='$to_warehouse_id'
                            and stock_master.from_warehouse_id = '$from_warehouse_id'
                            and stock_master.draft=1 and stock_master.transaction_type_id = 2   AND DATE_FORMAT(
                                stock_master.transaction_date,
                                '%Y-%m-%d'
                        ) = '$transaction_date' ";


        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $result = $row->fetchAll();


        $stock_master_id = $result[0]['pk_id'];

        $detail = $this->_em->getRepository("StockDetail")->findBy(array("stockMaster" => $stock_master_id));
        if (count($detail) > 0) {
            $master = $detail[0]->getStockMaster();
            foreach ($detail as $row) {
                $this->_em->remove($row);
            }
            $this->_em->remove($master);
            $this->_em->flush();
        }



        if (!empty($array['hdn_stock_master_id'])) {
            $stock_master = $this->_em->getRepository("StockMaster")->find($array['hdn_stock_master_id']);
        } else {
            $stock_master = new StockMaster();
        }

        $type = $array['transaction_type_id'];

        $time_arr = explode(' ', $array['transaction_date']);
        $time = date('H:i:s', strtotime($time_arr[1] . $time_arr[2]));
        $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($time_arr[0]) . '' . $time));
        $tran_type = $this->_em->getRepository('TransactionTypes')->find($type);
        $stock_master->setTransactionType($tran_type);
        $stock_master->setTransactionReference($array['transaction_reference']);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_master->setCreatedBy($created_by);
        $stock_master->setModifiedBy($created_by);
        $stock_master->setParentId(0);
        $stock_master->setCreatedDate(App_Tools_Time::now());
        $stock_master->setModifiedDate(App_Tools_Time::now());
        $activity_id = $this->_em->getRepository('StakeholderActivities')->find($array['hdn_activity_id']);
        $stock_master->setStakeholderActivity($activity_id);
        if (!empty($array['campaign_id'])) {
            $stock_master->setCampaignId($array['campaign_id']);
        }
        if (!empty($array['dispatch_by'])) {
            $stock_master->setDispatchBy($array['dispatch_by']);
        }
        if ($type == 1) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($array['from_warehouse_id']);
            $stock_master->setFromWarehouse($from_warehouse_id);
            $to_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $stock_master->setToWarehouse($to_warehouse_id);
        } else if ($type == 2) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $stock_master->setFromWarehouse($from_warehouse_id);
            $to_warehouse_id = $this->_em->getRepository('Warehouses')->find($array['warehouse']);
            $stock_master->setToWarehouse($to_warehouse_id);
        }

        $stock_master->setComments($array['comments']);
        $this->_em->persist($stock_master);
        $this->_em->flush();

        $id = $stock_master->getPkId();

        $stock = $this->_table->find($id);
        $trans = $this->getTransactionNumber($stock->getTransactionType()->getPkId(), $stock->getTransactionDate()->format("d/m/Y"), $this->_identity->getWarehouseId(), $stock->getPkId());

        $stock->setTransactionNumber($trans['trans_no']);
        $stock->setDraft(0);
        $stock->setTransactionCounter($trans['id']);
        $stock->setModifiedBy($created_by);
        $stock->setModifiedDate(App_Tools_Time::now());
        $stock->setCreatedBy($created_by);
        $stock->setCreatedDate(App_Tools_Time::now());
        $this->_em->persist($stock);
        $this->_em->flush();

        return $id;
    }

    /**
     * Add delete Issue Draft
     *
     */
    public function deleteIssueDraft($array) {
        $to_warehouse_id = $array['hdn_receive_warehouse_id'];
        $from_warehouse_id = $this->_identity->getWarehouseId();
        $transaction_date = App_Controller_Functions::dateToDbFormat($array['transaction_date']);
        $str_qry = "SELECT stock_master.pk_id
                            FROM
                            stock_master
                            where
                            stock_master.to_warehouse_id='$to_warehouse_id'
                            and stock_master.from_warehouse_id = '$from_warehouse_id'
                            and stock_master.draft=1 and stock_master.transaction_type_id = 2   AND
                            DATE_FORMAT(
                                stock_master.transaction_date,
                                '%Y-%m-%d'
                        ) = '$transaction_date' ";

        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $result = $row->fetchAll();

        if (count($result) > 0) {
            $stock_master_id = $result[0]['pk_id'];

            $detail = $this->_em->getRepository("StockDetail")->findBy(array("stockMaster" => $stock_master_id));


            if (count($detail) > 0) {
                $master = $detail[0]->getStockMaster();
                foreach ($detail as $row) {
                    $this->_em->remove($row);
                    $this->_em->flush();
                }
                $this->_em->remove($master);
                $this->_em->flush();
            }
        }
        return true;
    }

    /**
     * Add Stock Master Temp
     *
     */
    public function addStockMasterTemp($array) {
        $to_warehouse_id = $array['hdn_receive_warehouse_id'];
        $from_warehouse_id = $this->_identity->getWarehouseId();
        $transaction_date = App_Controller_Functions::dateToDbFormat($array['transaction_date']);
        $str_qry = "SELECT stock_master.pk_id
                            FROM
                            stock_master
                            where
                            stock_master.to_warehouse_id='$to_warehouse_id'
                            and stock_master.from_warehouse_id = '$from_warehouse_id'
                            and stock_master.draft=1 and stock_master.transaction_type_id = 2   AND DATE_FORMAT(
                                stock_master.transaction_date,
                                '%Y-%m-%d'
                        ) = '$transaction_date' ";

        $row = $this->_em->getConnection()->prepare($str_qry);
        $row->execute();
        $result = $row->fetchAll();

        if (count($result) > 0) {
            $stock_master_id = $result[0]['pk_id'];

            $detail = $this->_em->getRepository("StockDetail")->findBy(array("stockMaster" => $stock_master_id));
            if (count($detail) > 0) {
                $master = $detail[0]->getStockMaster();
                foreach ($detail as $row) {
                    $this->_em->remove($row);
                }
                $this->_em->remove($master);
                $this->_em->flush();
            }
        }
        $stock_master = new StockMaster();

        $type = $array['transaction_type_id'];

        $time_arr = explode(' ', $array['transaction_date']);
        $time = date('H:i:s', strtotime($time_arr[1] . $time_arr[2]));
        $stock_master->setTransactionDate(new \DateTime(App_Controller_Functions::dateToDbFormat($time_arr[0]) . '' . $time));
        $tran_type = $this->_em->getRepository('TransactionTypes')->find($type);
        $stock_master->setTransactionType($tran_type);
        $stock_master->setTransactionReference($array['transaction_reference']);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock_master->setCreatedBy($created_by);
        $stock_master->setModifiedBy($created_by);
        $stock_master->setParentId(0);
        $stock_master->setCreatedDate(App_Tools_Time::now());
        $stock_master->setModifiedDate(App_Tools_Time::now());
        $activity_id = $this->_em->getRepository('StakeholderActivities')->find($array['hdn_activity_id']);
        $stock_master->setStakeholderActivity($activity_id);
        if (!empty($array['campaign_id'])) {
            $stock_master->setCampaignId($array['campaign_id']);
        }
        if (!empty($array['dispatch_by'])) {
            $stock_master->setDispatchBy($array['dispatch_by']);
        }
        if ($type == 1) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($array['from_warehouse_id']);
            $stock_master->setFromWarehouse($from_warehouse_id);
            $to_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $stock_master->setToWarehouse($to_warehouse_id);
        } else if ($type == 2) {
            $from_warehouse_id = $this->_em->getRepository('Warehouses')->find($this->_identity->getWarehouseId());
            $stock_master->setFromWarehouse($from_warehouse_id);
            $to_warehouse_id = $this->_em->getRepository('Warehouses')->find($array['hdn_receive_warehouse_id']);
            $stock_master->setToWarehouse($to_warehouse_id);
        }

        $stock_master->setComments($array['comments']);
        $stock_master->setTransactionNumber('TEMP');
        $stock_master->setDraft(1);

        $this->_em->persist($stock_master);
        $this->_em->flush();

        return $stock_master->getPkId();
    }

    /**
     * Get Issue Temp
     *
     */
    public function getIssueTemp() {

        $sender_warehouse_id = $this->form_values['sender_warehouse_id'];
        $receive_warehouse_id = $this->form_values['receive_warehouse_id'];
        $str_sql = "SELECT

            DATE_FORMAT(stock_master.transaction_date,'%d/%m/%Y %h:%i %p') as transaction_date,
            stakeholder_item_pack_sizes.item_pack_size_id,
            stock_detail.stock_batch_warehouse_id as stock_batch_id,
            DATE_FORMAT(stock_batch.expiry_date,'%d %M, %Y') as expiry_date,
            stock_detail.quantity,
            stock_master.transaction_reference,
            stock_master.comments
            FROM
            stock_detail
            INNER JOIN stock_master ON stock_master.pk_id = stock_detail.stock_master_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
            stock_master.from_warehouse_id = '$sender_warehouse_id' and "
                . "stock_master.to_warehouse_id = '$receive_warehouse_id' and "
                . "stock_master.draft = 1 and "
                . "stock_master.transaction_type_id= 2";

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
     * Activity Log Search
     *
     */
    public function activityLogSearch() {

        $wh_id = $this->_identity->getWarehouseId();

        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $date_where = "and DATE_FORMAT(stock_master.created_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $date_where = "and DATE_FORMAT(stock_master.created_date,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $querypro = "SELECT
                DISTINCT
                stock_master.pk_id,
                stock_master.transaction_date,
                stock_master.transaction_number,
                stock_master.transaction_type_id,
                stock_master.transaction_reference,
                ABS(stock_detail.quantity) as quantity,
                item_pack_sizes.item_name,
                stock_batch.number,
                item_units.item_unit_name,
                stock_batch.expiry_date,
                stock_master.created_date,
                from_warehouse.warehouse_name as from_warehouse_name,
                to_warehouse.warehouse_name as to_warehouse_name,
                stock_master.action_type,
                users.user_name
                FROM
                stock_master_history as stock_master
                INNER JOIN stock_detail_history  as stock_detail ON stock_master.master_id = stock_detail.stock_master_id
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                INNER JOIN warehouses AS `from_warehouse` ON stock_master.from_warehouse_id = from_warehouse.pk_id
                INNER JOIN warehouses AS `to_warehouse` ON stock_master.to_warehouse_id = to_warehouse.pk_id
                INNER JOIN users ON stock_master.created_by = users.pk_id
                WHERE
                stock_master.draft = 0 AND
                stock_detail.temporary = 0 AND
                stock_batch_warehouses.warehouse_id = '$wh_id'
                $date_where
                ORDER BY transaction_type_id,transaction_date";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Federal Warehouses
     *
     */
    public function getFederalWarehouses() {

        if ($this->form_values['role_id'] == 3 || $this->form_values['wh_type'] == 1 || $this->form_values['role_id'] == 17) {

            $str_qry1 = "SELECT
                                warehouses.pk_id,
                                warehouses.warehouse_name
                            FROM
                                warehouses
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            WHERE
                                stakeholders.geo_level_id = 1
                                AND stakeholders.pk_id = 1";

            $row1 = $this->_em_read->getConnection()->prepare($str_qry1);
            $row1->execute();
            return $row1->fetchAll();
        }
    }

    /**
     * Get Provincial Warehouses
     *
     */
    public function getProvincialWarehouses() {


        if ($this->form_values['role_id'] == 4 || $this->form_values['wh_type'] == 2 || $this->form_values['role_id'] == 3 || $this->form_values['role_id'] == 17) {
            $sel_prov = $this->form_values['province'];
            if ($this->form_values['role_id'] != 3) {
                $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
            } else {
                $provFilter = "";
            }

            $str_qry1 = "SELECT
                        warehouses.pk_id,
                        warehouses.warehouse_name
                    FROM
                        warehouses
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND stakeholders.pk_id = 2
                        $provFilter
                    ORDER BY
                        warehouses.warehouse_name";

            $row1 = $this->_em_read->getConnection()->prepare($str_qry1);
            $row1->execute();
            return $row1->fetchAll();
        }
    }

    /**
     * Get District Warehouses
     *
     */
    public function getDistrictWarehouses() {

        $roleIds = array(3, 4, 6, 7, 17);
        if (in_array($this->form_values['role_id'], $roleIds)) {

            $sel_prov = $this->form_values['province'];
            if ($sel_prov == "all") {
                $sel_dist = "";
            } else {
                $sel_dist = $this->form_values['district'];
            }

            if ($this->form_values['role_id'] != 3) {
                $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';

                $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
            } else {
                $provFilter = "";
                $distFilter = "";
            }


            $str_qry1 = "SELECT
                        warehouses.pk_id,
                        warehouses.warehouse_name
                    FROM
                        warehouses
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id

                    WHERE
                        stakeholders.geo_level_id = 4
                        AND stakeholders.pk_id = 4
                        AND warehouses.status = 1
                        $provFilter
                        $distFilter
                    ORDER BY
                        warehouses.warehouse_name";

            $row1 = $this->_em_read->getConnection()->prepare($str_qry1);
            $row1->execute();
            return $row1->fetchAll();
        }
    }

    /**
     * Get Tehsil Warehouses
     *
     */
    public function getTehsilWarehouses() {

        if ($this->form_values['role_id'] == 7 || $this->form_values['wh_type'] == 5 || $this->form_values['role_id'] == 3 || $this->form_values['role_id'] == 17) {

            $sel_prov = $this->form_values['province'];
            if ($sel_prov == 'all') {
                $sel_tehsil = "";
                $sel_dist = "";
            } else {
                $sel_tehsil = $this->form_values['tehsil'];
                $sel_dist = $this->form_values['district'];
            }

            if ($this->form_values['role_id'] != 3) {
                $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';

                $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
            } else {
                $provFilter = "";
                $distFilter = "";
            }
            if ($this->form_values['role_id'] != 3) {
                $tehsilFilter = (!empty($sel_tehsil) && $sel_tehsil != 'all') ? " AND warehouses.location_id = $sel_tehsil" : '';
            } else {
                $tehsilFilter = '';
            }

            $str_qry1 = "SELECT
        *
    FROM
        (
                SELECT
                        warehouses.pk_id,
                        warehouses.warehouse_name

                FROM
                        stock_detail
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
                OR stock_master.from_warehouse_id = warehouses.pk_id
                INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                WHERE
                        stakeholders.geo_level_id = 5
                AND warehouses. STATUS = 1
                $provFilter
                $distFilter
                $tehsilFilter
                ORDER BY
                        warehouses.warehouse_name,
                        stock_master.transaction_date DESC
        ) A
    GROUP BY
        warehouse_name";

            $row1 = $this->_em_read->getConnection()->prepare($str_qry1);
            $row1->execute();
            return $row1->fetchAll();
        }
    }

    /**
     * Get Adjustment Data
     *
     */
    public function getAdjustmentData() {

        if (!empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        }

        $str_sql = "SELECT
                        stock_batch_warehouses.warehouse_id,
                        item_pack_sizes.item_name,
                        stock_batch.number AS batch_number,
                        stock_batch_warehouses.quantity AS batch_qty,
                        stock_batch.expiry_date,
                        placement_summary.quantity AS placed_qty,
                        placement_summary.placement_location_id
                    FROM
                        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        LEFT JOIN placement_summary ON stock_batch.pk_id = placement_summary.stock_batch_warehouse_id
                    WHERE
                        stock_batch_warehouses.quantity > 0
                        AND stock_batch_warehouses.warehouse_id = $wh_id";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Issue Detail
     *
     */
    public function getIssueDetail() {
        $detail_id = $this->form_values['$detail_id'];
        $stock_detail = $this->_em->getRepository("StockDetail")->find($detail_id);
        $vvm_stage_id = $stock_detail->getVvmStage()->getPkId();
        $qty_issued = abs($stock_detail->getQuantity());
        $batch_id = $stock_detail->getStockBatchWarehouse()->getPkId();
        $batch_number = $stock_detail->getStockBatchWarehouse()->getStockBatch()->getNumber();
        $expiray_date = $stock_detail->getStockBatchWarehouse()->getStockBatch()->getExpiryDate()->format("d/m/Y");
        $product = $stock_detail->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemName();
        $item_category_id = $stock_detail->getStockBatchWarehouse()->getStockBatch()->getPackInfo()->getStakeholderItemPackSize()->getItemPackSize()->getItemCategory()->getPkId();
        return array(
            "vvm_stage_id" => $vvm_stage_id,
            "qty_issued" => $qty_issued,
            "batch_id" => $batch_id,
            "batch_number" => $batch_number,
            "expiray_date" => $expiray_date,
            "product" => $product,
            "item_category_id" => $item_category_id
        );
    }

    /**
     * Get Temp Vouchers
     *
     */
    public function getTempVouchersReceive() {
        $wh_id = $this->_identity->getWarehouseId();
        $user_id = $this->_user_id;


        $date_in = '2014-07-31';
        $str_sql = "SELECT DISTINCT
                stock_master.transaction_number,
                stock_master.pk_id as stock_id,
                users.login_id
                FROM
                stock_master
                INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN warehouse_users ON stock_master.to_warehouse_id = warehouse_users.warehouse_id
                INNER JOIN users ON stock_master.created_by = users.pk_id

                WHERE
                        stock_master.to_warehouse_id = $wh_id
                AND users.pk_id  <> $user_id
                AND
                    stock_detail.is_received = 1

                AND stock_master.transaction_type_id = 1
                AND stock_master.transaction_date > '$date_in'
                AND stock_master.draft = 1
                AND stock_detail.`temporary` = 1";



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
     * Get Temp Vouchers
     *
     */
    public function getTempVouchersIssue() {
        $wh_id = $this->_identity->getWarehouseId();
        $user_id = $this->_user_id;


        $date_in = '2014-07-31';
        $str_sql = "SELECT DISTINCT
                stock_master.transaction_number,
                stock_master.pk_id as stock_id,
                users.login_id
                FROM
                stock_master
                INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN warehouse_users ON stock_master.to_warehouse_id = warehouse_users.warehouse_id
                INNER JOIN users ON stock_master.created_by = users.pk_id

                WHERE
                        stock_master.from_warehouse_id = $wh_id
                AND users.pk_id  <> $user_id


                AND stock_master.transaction_type_id = 2
                AND stock_master.transaction_date > '$date_in'
                AND stock_master.draft = 1
                AND stock_detail.`temporary` = 1";



        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getStockOuts() {
        $warehouse_id = $this->_identity->getWarehouseId();
        if (empty($warehouse_id)) {
            $warehouse_id = 1;
        }
        if ($warehouse_id == 159) {
            $where = "AND item_pack_sizes.pk_id NOT IN(35,38)";
        } else {
            $where = "";
        }
        $rprt_date = date('Y-m');
        $qry_cb = "SELECT
    SUM(A.total) AS CB,
    A.province_id,
    A.pk_id
        FROM
    (
        SELECT
            ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses AS total,
            warehouses.province_id,
            item_pack_sizes.pk_id
        FROM
            stock_master
        INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
            warehouses.pk_id = $warehouse_id
        AND DATE_FORMAT(
            stock_master.transaction_date,
            '%Y-%m'
        ) <= '$rprt_date'
                AND item_pack_sizes.item_category_id = 1
                AND item_pack_sizes.pk_id IN (6,7,8,9,12,26,40,43,46)
                AND  stock_batch.expiry_Date >= '" . date("Y-m-d") . "'
                $where
        GROUP BY
            item_pack_sizes.pk_id
                        
    ) A
        GROUP BY
    A.pk_id";

        $rec = $this->_em_read->getConnection()->prepare($qry_cb);

        $rec->execute();
        $result = $rec->fetchAll();
        $data_arr = 0;
        foreach ($result as $row) {


            if ($this->_identity->getRoleId() == 4) {
                $amc = $this->getTarget($row['pk_id']);
            } else {
                $amc = $this->getAMC($row['pk_id']);
            }

            $mos = ROUND(@($row['CB'] / $amc), 2);
            $item_id = $row['pk_id'];


            $qry_geo_level = "SELECT
                    locations.geo_level_id
                    FROM
                    warehouses
                    INNER JOIN locations ON warehouses.location_id = locations.pk_id
                    WHERE
                    warehouses.pk_id = $warehouse_id";
            // Get doctrine instance.
            $row_geo = $this->_em_read->getConnection()->prepare($qry_geo_level);
            // Execute and get result.
            $row_geo->execute();
            $rows_geo = $row_geo->fetchAll();
            $geo_level_id = $rows_geo[0]['geo_level_id'];

            $querypro = "SELECT Distinct
                        m.long_term,m.color_code,m.scale_start,m.scale_end
                        FROM
                        mos_scale m

                        WHERE
                        m.item_id = '" . $item_id . "'
                        AND m.geo_level_id= '" . $geo_level_id . "'
                        AND m.stakeholder_id=1
                        AND m.long_term = 'Stock Out'
                        Order BY m.pk_id ASC";


            // Get doctrine instance.
            $row = $this->_em_read->getConnection()->prepare($querypro);
            // Execute and get result.
            $row->execute();
            $rows = $row->fetchAll();

            // echo $rows[0]['scale_start'].'-'.$rows[0]['scale_end'].'-'.$mos."<br>";
            if (count($rows) > 0) {
                if ($mos >= $rows[0]['scale_start'] && $mos <= $rows[0]['scale_end']) {
                    $data_arr += 1;
                } else {
                    $data_arr += 0;
                }
            }
        }
        if (!empty($data_arr)) {
            $data_arr = $data_arr;
        }
        return $data_arr;
    }

    public function getStockOutsAlert($item_pack_size_id) {
        $warehouse_id = $this->_identity->getWarehouseId();
        $rprt_date = date('Y-m');
        $qry_cb = "SELECT
    SUM(A.total) AS CB,
    A.province_id,
    A.pk_id
        FROM
    (
        SELECT
            ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses AS total,
            warehouses.province_id,
            item_pack_sizes.pk_id
        FROM
            stock_master
        INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
            warehouses.pk_id = $warehouse_id
        AND DATE_FORMAT(
            stock_master.transaction_date,
            '%Y-%m'
        ) <= '$rprt_date'
                AND item_pack_sizes.item_category_id = 1
               
        AND item_pack_sizes.pk_id = $item_pack_size_id
    ) A";

        $rec = $this->_em_read->getConnection()->prepare($qry_cb);

        $rec->execute();
        $row = $rec->fetchAll();




        if ($this->_identity->getRoleId() == 4) {
            $amc = $this->getTarget($row[0]['pk_id']);
        } else {
            $amc = $this->getAMC($row[0]['pk_id']);
        }

        $mos = ROUND(@($row[0]['CB'] / $amc), 2);

        return $mos;
    }

    public function getTarget($item_id) {

        $qry1 = "SELECT
item_schedule.number_of_doses,
item_schedule.item_pack_size_id
FROM
item_schedule
WHERE
item_schedule.item_pack_size_id = $item_id";
        $rec1 = $this->_em_read->getConnection()->prepare($qry1);
        $rec1->execute();
        $row1 = $rec1->fetchAll();


        if (!empty($row1) && count($row1) > 0) {
            $doses = $row1[0]['number_of_doses'];
        } else {
            $doses = 1;
        }
        // $warehouse_id = $this->_identity->getWarehouseId();
        $province_id = $this->_identity->getProvinceId();
        $rprt_date = date('Y');
        $qry = "SELECT Round(((B.target * 92.3) / 100)) AS lbt
FROM(
SELECT
                                        ROUND(
                                                COALESCE (
                                                        ROUND(
                                                                (
                                                                        (
                                                                                (
                                                                                        (
                                                                                                SUM(location_populations.population) * 1
                                                                                        ) / 100 * 3.5
                                                                                )
                                                                        ) * $doses
                                                                )
                                                        ) / 12,
                                                        NULL,
                                                        0
                                                )
                                        ) AS target,
                                        district.pk_id AS pk_id
                                FROM
                                            location_populations
                                INNER JOIN locations ON location_populations.location_id = locations.pk_id
                                INNER JOIN locations as district ON locations.district_id = district.pk_id
                                WHERE
                                        locations.geo_level_id = 6
                                AND YEAR (
                                        location_populations.estimation_date
                                ) = '$rprt_date'
                                AND locations.province_id = '$province_id'
                                    
                                GROUP BY
                                        locations.province_id) B";

        $rec = $this->_em_read->getConnection()->prepare($qry);
        $rec->execute();
        $row = $rec->fetchAll();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['lbt'];
        } else {
            return FALSE;
        }
    }

    public function getDryStoreMos($item_pack_size_id) {
        $warehouse_id = $this->_identity->getWarehouseId();
        $rprt_date = date('Y-m');
        $qry_cb = "SELECT
    SUM(A.total) AS CB,
    A.province_id,
        A.item_name,
    A.pk_id
        FROM
    (
        SELECT
            ABS(SUM(stock_detail.quantity)) * item_pack_sizes.number_of_doses AS total,
            warehouses.province_id,
            item_pack_sizes.pk_id,
                        item_pack_sizes.item_name
        FROM
            stock_master
        INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
            warehouses.pk_id = $warehouse_id
        AND DATE_FORMAT(
            stock_master.transaction_date,
            '%Y-%m'
        ) <= '$rprt_date'

        AND item_pack_sizes.pk_id = $item_pack_size_id
    ) A";

        $rec = $this->_em_read->getConnection()->prepare($qry_cb);

        $rec->execute();
        $row = $rec->fetchAll();




        $amc = $this->getAMC($row[0]['pk_id']);

        $mos = ROUND(@($row[0]['CB'] / $amc), 2);

        return $mos;
    }

    public function getAMC($item_id) {
        $warehouse_id = $this->_identity->getWarehouseId();
        $rprt_date = date('Y-m');
        $qry = "SELECT
              AVG(csum) AS AMC,
          item_pack_size_id
        FROM
            (
                SELECT
                    RptDate,
                    csum,
                item_pack_size_id
                FROM
                    (
                        SELECT
                            reporting_start_date AS RptDate,
                            sum(
                                hf_data_master.issue_balance
                            ) AS csum,
                                hf_data_master.item_pack_size_id
                        FROM
                            warehouses
                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                            hf_data_master.item_pack_size_id = $item_id
                        AND warehouses.pk_id = $warehouse_id

                        AND warehouses. STATUS = 1
                        GROUP BY
                            RptDate
                    ) AS A
                WHERE
                    csum > 0
                AND DATE_FORMAT(RptDate, '%Y-%m') <= '$rprt_date'
                ORDER BY
                    RptDate DESC
                LIMIT 3
            ) AS B";
        $rec = $this->_em_read->getConnection()->prepare($qry);
        $rec->execute();
        $row = $rec->fetchAll();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['AMC'];
        } else {
            return FALSE;
        }
    }

    public function getExpiryAlerts() {

        $warehouse_id = $this->_identity->getWarehouseId();
        if (empty($warehouse_id)) {
            $warehouse_id = 1;
        }
        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $qry = "SELECT
    Count(*) total
            FROM
    (
        SELECT
            DATE_FORMAT(
                stock_batch.expiry_date,
                '%b, %Y'
            ) AS expiry_date,
            stock_batch.number,
            item_pack_sizes.item_name,
            SUM(placements.quantity) AS quantity,
            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
            cold_chain.asset_id AS coldroom,
            placement_locations.pk_id AS coldroom_id,

        IF (
            item_pack_sizes.vvm_group_id = 1,
            IFNULL(vvm_stages.pk_id, 1),
            vvm_stages.vvm_stage_value
        ) vvm
        FROM
            stock_batch_warehouses
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        WHERE
            stock_batch_warehouses.warehouse_id = $warehouse_id
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
        GROUP BY
            stock_batch_warehouses.warehouse_id,
            placements.vvm_stage,
            placements.stock_batch_warehouse_id,
            cold_chain.asset_id
        HAVING
            quantity > 0
        ORDER BY
            item_pack_sizes.list_rank,
            stock_batch.expiry_date
    ) A";
        $rec = $this->_em_read->getConnection()->prepare($qry);
        $rec->execute();
        $row = $rec->fetchAll();

        if (!empty($row) && count($row) > 0) {
            return $row['0']['total'];
        } else {
            return FALSE;
        }
    }

    /**
     * Priority Vaccines Distribution Summary
     *
     */
    public function priorityVaccinesDistributionSummaryAlerts() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) ";
                break;
            case 2:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear'  ";
                break;
            case 3:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear'";
                break;
            case 4:
                $where = " AND
                    (placements.vvm_stage >= 3 OR
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                     ";
                break;
            default:
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%M, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(placements.quantity) AS quantity,
                            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
                            IFNULL(vvm_stages.pk_id, 1) AS vvm,
                            vvm_stages.vvm_stage_value
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    $where
                    GROUP BY
                            item_pack_sizes.pk_id,
                            stakeholder_item_pack_sizes.item_pack_size_id
                    HAVING
                            quantity > 0
                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Priority Vaccines Distribution
     *
     */
    public function priorityVaccinesDistributionAlert() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month'))";
                break;
            case 2:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' ";
                break;
            case 3:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear'
                     ";
                break;
            case 4:
                $where = " AND
                    (placements.vvm_stage >= 3 OR
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    ";
                break;
            default :
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%b, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(placements.quantity) AS quantity,
                            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
                            cold_chain.asset_id as coldroom,
                            placement_locations.pk_id as coldroom_id,
                            IF(item_pack_sizes.vvm_group_id=1,IFNULL(vvm_stages.pk_id, 1),vvm_stages.vvm_stage_value) vvm,
                            item_pack_sizes.pk_id as item_id
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    $where
                    GROUP BY
                            placements.vvm_stage,
                            placements.stock_batch_warehouse_id,
                            cold_chain.asset_id,
                            stakeholder_item_pack_sizes.item_pack_size_id
                    HAVING
                            quantity > 0

                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function priorityVaccinesDistributionTotal() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $where = '';
        $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month'))";
                break;
            case 2:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' ";
                break;
            case 3:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear'
                     ";
                break;
            case 4:
                $where = " AND
                    (placements.vvm_stage >= 3 OR
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    ";
                break;
            default :
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%b, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(placements.quantity) AS quantity,
                            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
                            cold_chain.asset_id as coldroom,
                            placement_locations.pk_id as coldroom_id,
                            IF(item_pack_sizes.vvm_group_id=1,IFNULL(vvm_stages.pk_id, 1),vvm_stages.vvm_stage_value) vvm,
                            item_pack_sizes.pk_id as item_id
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $product_id
                    $where
                    GROUP BY
                           stakeholder_item_pack_sizes.item_pack_size_id
                    HAVING
                            quantity > 0

                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Priority Vaccines Distribution
     *
     */
    public function getMosScale($item_id) {
        $warehouse_id = $this->_identity->getWarehouseId();

        $qry_geo_level = "SELECT
                    locations.geo_level_id
                    FROM
                    warehouses
                    INNER JOIN locations ON warehouses.location_id = locations.pk_id
                    WHERE
                    warehouses.pk_id = $warehouse_id";
        // Get doctrine instance.
        $row_geo = $this->_em_read->getConnection()->prepare($qry_geo_level);
        // Execute and get result.
        $row_geo->execute();
        $rows_geo = $row_geo->fetchAll();
        $geo_level_id = $rows_geo[0]['geo_level_id'];

        $querypro = "SELECT Distinct
                        m.long_term,m.color_code,m.scale_start,m.scale_end
                        FROM
                        mos_scale m
                        WHERE
                        m.item_id = '" . $item_id . "'
                        AND m.geo_level_id= '" . $geo_level_id . "'
                        AND m.stakeholder_id=1
                        AND m.long_term = 'Stock Out'
                        Order BY m.pk_id ASC";

        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($querypro);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();

        return $rows;
    }

    /**
     * Priority Vaccines Distribution
     *
     */
    public function getShipmentsByReceiveNo() {


        $querypro = "SELECT
            stock_master.pk_id,
            stock_master.transaction_date,
            stock_master.transaction_number,
            stock_master.transaction_counter,
            stock_master.transaction_reference,
            stock_master.dispatch_by,
            stock_master.draft,
            stock_master.comments,
            stock_master.transaction_type_id,
            stock_master.from_warehouse_id,
            stock_master.to_warehouse_id,
            stock_master.parent_id,
            stock_master.campaign_id,
            stock_master.stakeholder_activity_id,
            stock_master.created_by,
            stock_master.created_date,
            stock_master.issue_from,
            stock_master.issue_to,
            stock_master.modified_by,
            stock_master.modified_date,
            stock_master.shipment_id
            FROM
            stock_master
            WHERE
            stock_master.transaction_number = '" . $this->transaction_number . "' AND
            stock_master.to_warehouse_id = '" . $this->to_warehouse_id . "'";


        // Get doctrine instance.
        $row = $this->_em_read->getConnection()->prepare($querypro);
        // Execute and get result.
        $row->execute();
        $rows = $row->fetchAll();

        return $rows;
    }

    /**
     * Get Stocks Receive List
     *
     * @param int $type
     * @return boolean
     */
    public function getGatePassList() {


        $str_sql = "SELECT
            gatepass_master.transaction_date,
            gatepass_detail.quantity,
            gatepass_master.number,
            item_pack_sizes.item_name,
            gatepass_master.comments
            
            FROM
                    gatepass_master
            INNER JOIN gatepass_detail ON gatepass_detail.gatepass_master_id = gatepass_master.pk_id
            INNER JOIN stock_detail ON gatepass_detail.stock_detail_id = stock_detail.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
            gatepass_master.pk_id ='" . $this->form_values['pk_id'] . "' ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();
        $result = $rec->fetchAll();
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function emailSms($stock_id) {
        $proc_by_arr = array();
        $shipment_stk_arr = array();
        if ($_SERVER['SERVER_ADDR'] == '::1' || $_SERVER['SERVER_ADDR'] == 'localhost' || $_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == 'beta.lmis.gov.pk') {
            //$subject = 'Notification: Stock Issued in beta.lmis.gov.pk/clmisapp';
            $shipment_stk_arr[] = "'all'";
            $proc_by_arr[] = '10';
        } else {

            //$subject = 'Notification: Stock Issued in vLMIS - v.lmis.gov.pk';
            $stk_qry = "SELECT DISTINCT
	
                    warehouses.province_id,
                    warehouses.stakeholder_id
            FROM
                    stock_master
            INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
            WHERE
                    stock_master.pk_id = '$stock_id'";

            $rec = $this->_em_read->getConnection()->prepare($stk_qry);

            $rec->execute();
            $row2 = $rec->fetchAll();
            foreach ($row2 as $row) {
                $shipment_stk_arr[] = "'" . $row['stakeholder_id'] . "'";
                $province = $row['province_id'];
            }
            $shipment_stk_arr[] = "'all'";
            $proc_by_arr[] = $province;
            $proc_by_arr[] = '10';
        }
        $to_list = $cc_list = array();
        $emails_qry = "SELECT
                        email_persons_list.pk_id,
                        email_persons_list.person_name,
                        email_persons_list.email_address,
                        email_persons_list.cell_number,
                        email_persons_list.office_name,
                        email_persons_list.stkid,
                        email_persons_list.prov_id
                    FROM
                        email_persons_list
                        INNER JOIN email_bridge ON email_persons_list.pk_id = email_bridge.person_id
                    WHERE
                        email_bridge.action_id = 3 AND
                      
                        email_persons_list.prov_id IN (" . implode(',', $proc_by_arr) . ")";
        // echo $emails_qry;exit;
        $rec_e = $this->_em_read->getConnection()->prepare($emails_qry);

        $rec_e->execute();
        $row1 = $rec_e->fetchAll();
        foreach ($row1 as $row) {

            $office_name = $row['office_name'];

            if ($office_name == 'Government') {
                $to_list[] = $row['email_address'];
                $to_list_sms[] = $row['cell_number'];
            } else {

                $cc_list[] = $row['email_address'];
                $cc_list_sms[] = $row['cell_number'];
            }
        }

        $to = implode(',', $to_list);
        $cc = implode(',', $cc_list);
        $tt_cc = array('0' => $to, '1' > $cc);
        return $tt_cc;
    }

 /**
     * Priority Vaccines Distribution
     *
     */
    public function priorityVaccinesDistributionReport() {
        $product_id = $this->form_values['product_id'];
        $case = $this->form_values['case'];
        $wh_id = $this->form_values['warehouse_id'];
        $where = '';
     //   $wh_id = $this->_identity->getWarehouseId();

        $current_date = new DateTime(date("Y-m-d"));
        $today = $current_date->format("Y-m-d");
        $month3 = $current_date->modify("+3 months");
        $after3month = $month3->format("Y-m-d");
        $month12 = $current_date->modify("+9 months");
        $afteryear = $month12->format("Y-m-d");

        switch ($case) {
            case 1:
                $where = " AND
                    (placements.vvm_stage = 2 OR
                    (placements.vvm_stage = 1 AND
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d')
                    BETWEEN '$today' AND '$after3month')) AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 2:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$after3month' AND '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 3:
                $where = " AND
                    placements.vvm_stage = 1 AND
                    DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%Y-%m-%d'
                    ) > '$afteryear' AND
                    stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            case 4:
                $where = " AND
                    (placements.vvm_stage >= 3 OR
                    DATE_FORMAT(stock_batch.expiry_date,'%Y-%m-%d') < '$today')
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $product_id ";
                break;
            default :
                break;
        }

        $str_qry = "SELECT
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%b, %Y'
                            ) AS expiry_date,
                            stock_batch.number,
                            item_pack_sizes.item_name,
                            SUM(placements.quantity) AS quantity,
                            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
                            cold_chain.asset_id as coldroom,
                            placement_locations.pk_id as coldroom_id,
                            IF(item_pack_sizes.vvm_group_id=1,IFNULL(vvm_stages.pk_id, 1),vvm_stages.vvm_stage_value) vvm
                    FROM
                            stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                    INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                    WHERE
                    stock_batch_warehouses.warehouse_id = $wh_id AND
                    item_pack_sizes.item_category_id = 1
                    $where
                    GROUP BY
                            placements.vvm_stage,
                            placements.stock_batch_warehouse_id,
                            cold_chain.asset_id
                    HAVING
                            quantity > 0
                    ORDER BY
                            item_pack_sizes.list_rank, stock_batch.expiry_date";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

  /**
     * Priority Vaccines Distribution
     *
     */
    public function priorityVaccinesDistributionRep() {

        $case = $this->form_values['case'];
        $wh_id = $this->form_values['warehouse_id'];
        $wh_type = $this->form_values['wh_type'];
        $item_id = $this->form_values['item_id'];
        $purpose = $this->form_values['purpose'];
        $status = $this->form_values['status'];

        if ($purpose == 1) {
            $pur_where = "AND item_activities.stakeholder_activity_id = 1";
        } else {
            $pur_where = "AND item_activities.stakeholder_activity_id = 2";
        }


        if ($wh_id == 'all') {
            $pr_where = "";
        } else {
            $pr_where = "AND warehouses.province_id = $wh_id";
        }

        if ($item_id == 'all') {
            $item_wh = "";
        } else {
            $item_wh = "AND item_pack_sizes.pk_id = $item_id";
        }
        $current_date = date("Y-m-d");

        if ($status == 1) {
            $wh_status = "DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) < '$current_date'";
        } else {
            $wh_status = "placements.vvm_stage >= 3";
        }

        $where = '';
        //   $wh_id = $this->_identity->getWarehouseId();


        if ($wh_id == 'all') {
            $str_qry = "SELECT
        locations.location_name,
	warehouses.warehouse_name,
	DATE_FORMAT(
		stock_batch.expiry_date,
		'%b, %Y'
	) AS expiry_date,
	stock_batch.number,
	item_pack_sizes.item_name,
	Sum(placements.quantity) AS quantity,
	SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
	cold_chain.asset_id AS coldroom,
	placement_locations.pk_id AS coldroom_id,

        IF (
                item_pack_sizes.vvm_group_id = 1,
                IFNULL(vvm_stages.pk_id, 1),
                vvm_stages.vvm_stage_value
        ) AS vvm,
       warehouses.stakeholder_office_id
        FROM
                stock_batch_warehouses
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
        INNER JOIN locations ON warehouses.province_id = locations.pk_id
        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                item_pack_sizes.item_category_id <> 3
                $pur_where
        AND (
                $wh_status
        )
        $item_wh
        GROUP BY
	placements.vvm_stage,
	placements.stock_batch_warehouse_id,
	cold_chain.asset_id
        HAVING
                quantity > 0
        ORDER BY
        locations.location_name,
	stakeholder_office_id,
	warehouses.warehouse_name,
	item_pack_sizes.list_rank,
	stock_batch.expiry_date";
        } else {



            $str_qry = "SELECT
            warehouses.warehouse_name,
            DATE_FORMAT(
                            stock_batch.expiry_date,
                            '%b, %Y'
                    ) AS expiry_date,
            stock_batch.number,
            item_pack_sizes.item_name,
            Sum(placements.quantity) AS quantity,
            SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
            cold_chain.asset_id AS coldroom,
            placement_locations.pk_id AS coldroom_id,
            IF (
                    item_pack_sizes.vvm_group_id = 1,
                    IFNULL(vvm_stages.pk_id, 1),
                    vvm_stages.vvm_stage_value
            ) AS vvm,
            warehouses.stakeholder_office_id
            FROM
                    stock_batch_warehouses
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
            INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
            INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
            INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
            INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
            WHERE
           
                  item_pack_sizes.item_category_id <> 3
                $pur_where
            AND (
                   $wh_status
            )
            $pr_where
                $item_wh
            GROUP BY

                    placements.vvm_stage,
                    placements.stock_batch_warehouse_id,
                    cold_chain.asset_id
            HAVING
                    quantity > 0
            ORDER BY
              stakeholder_office_id,
              warehouses.warehouse_name,
                    item_pack_sizes.list_rank,
                    stock_batch.expiry_date
                    
            ";
        }

        

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

}
