<?php

/**
 * Model_DemandMaster
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Demand Master
 */
class Model_DemandMaster extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('DemandMaster');
    }

    /**
     * Gets requisition number.
     * @param type $from_date
     * @param type $to_date
     * @param type $wh_id
     * @param type $trans_id
     * @return type
     */
    public function getRequisitionNumber($from_date, $to_date, $wh_id = null) {

        $from_date_arr = explode("/", $from_date);

        $from_month = $from_date_arr[1];
        $from_year = $from_date_arr[2];

        $last_id = $this->getLastID($from_date, $to_date, $wh_id);

        if ($last_id == NULL) {
            $last_id = 0;
        }

        $last_id += 1;

        return array(
            "id" => $last_id,
            "req_no" => "RQ" . substr($from_year, -2) . $from_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
        );
    }

    /**
     * 
     * @return boolean
     */
    public function getTempRequisitionList() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                        item.item_name,
                        item.pk_id AS item_id,
                        item_units.item_unit_name,
                        to_warehouse.warehouse_name AS to_warehouse_name,
                        demand_detail.demand_quantity,
                        demand_master.suggested_date,
                        demand_master.from_date,
                        demand_master.to_date,
                        demand_master.from_warehouse_id,
                        demand_master.created_by
                    FROM
                        demand_detail
                        INNER JOIN demand_master ON demand_detail.demand_master_id = demand_master.pk_id
                        INNER JOIN item_pack_sizes AS item ON demand_detail.product_id = item.pk_id
                        INNER JOIN item_units ON item.item_unit_id = item_units.pk_id
                        INNER JOIN warehouses AS to_warehouse ON demand_master.to_warehouse_id = to_warehouse.pk_id
                    WHERE
                        demand_master.draft = '1'
                        AND demand_master.is_deleted='0'
                        AND demand_detail.is_deleted='0'
                        AND demand_master.from_warehouse_id = $wh_id
                        AND demand_master.created_by = $wh_id";

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
     * get demand requisitions list.
     * @param type $master_id
     * @return boolean
     */
    public function getDemandRequisitionDetails($master_id) {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                    demand_master.pk_id,
                    demand_master.from_date,
                    demand_master.to_date,
                    demand_master.created_date,
                    demand_master.suggested_date,
                    demand_master.approved_date,
                    demand_master.requisition_number,
                    demand_master.requisition_counter,
                    demand_master.requisition_reference,
                    demand_master.transaction_type_id,
                    demand_master.draft,
                    demand_master.`status`,
                    demand_master.comments,
                    demand_master.from_warehouse_id,
                    demand_master.to_warehouse_id,
                    demand_master.stock_master_id,
                    demand_master.modified_by,
                    demand_master.modified_date,
                    demand_master.stakeholder_activity_id,
                    demand_detail.pk_id as demand_detail_id,
                    demand_detail.demand_quantity,
                    demand_detail.approved_quantity,
                    demand_detail.demand_master_id,
                    demand_detail.product_id,
                    demand_detail.pair_product_id,
                    demand_detail.draft,
                    item_pack_sizes.item_name,
                    item_units.item_unit_name,
                    warehouses.pk_id as to_warehouse_id,
                    warehouses.warehouse_name AS to_warehouse_name
                    FROM
                    demand_master
                    INNER JOIN demand_detail ON demand_detail.demand_master_id = demand_master.pk_id
                    INNER JOIN item_pack_sizes ON demand_detail.product_id = item_pack_sizes.pk_id
                    INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                    INNER JOIN demand_warehouses_logic ON demand_master.to_warehouse_id = demand_warehouses_logic.to_warehouse_id
                    INNER JOIN warehouses ON demand_warehouses_logic.to_warehouse_id = warehouses.pk_id
                    WHERE
                    demand_master.is_deleted=0
                    AND demand_detail.is_deleted=0
                    AND demand_master.pk_id = $master_id AND demand_warehouses_logic.from_warehouse_id = $wh_id";
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
     * get temp requisition.
     * @return boolean
     */
    public function getTempRequisition() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
                        demand_master.requisition_number,
                        demand_master.requisition_reference,
                        demand_master.created_by,
                        demand_master.from_warehouse_id
                    FROM
                        demand_master
                        INNER JOIN warehouses ON demand_master.from_warehouse_id = warehouses.pk_id
                        INNER JOIN demand_detail ON demand_master.pk_id = demand_detail.demand_master_id
                    WHERE
                        demand_master.draft = 1
                        AND demand_master.is_deleted='0'
                        AND demand_detail.is_deleted='0'
                        AND demand_master.from_warehouse_id = $wh_id";
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
     * 
     * @return boolean
     */
    public function getTempDemandList() {
        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = $this->_em->createQueryBuilder()
                ->select('dm.fromDate AS from_date,
                        dm.toDate AS to_date,
                        dm.pkId,
                        dm.requisitionNumber AS req_no')
                ->from("DemandDetail", "dd")
                ->join("dd.demandMaster", "dm")
                ->where("dm.draft = '1'")
                ->andWhere("dm.isDeleted = '0'")
                ->andWhere("dm.fromWarehouse=" . $wh_id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * adds demand master record.
     * @param type $array
     * @return type
     */
    public function addDemandMaster($array) {
        if ($array['rcvedit'] == "Yes") {
            $demand_master = $this->_em->getRepository("DemandMaster")->find($array['demand_master_id']);
        } else {
            $demand_master = new DemandMaster();
        }
        $type = $array['transaction_type_id'];

        $period_arr = explode('-', $array['period']);
        $from_date = $period_arr['0'];
        $to_date = $period_arr['1'];

        $demand_master->setFromDate(new \DateTime(App_Controller_Functions::dateToDbFormat($from_date)));
        $demand_master->setToDate(new \DateTime(App_Controller_Functions::dateToDbFormat($to_date)));
        $demand_master->setSuggestedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($array['suggested_date'])));
        $trans_type = $this->_em->getRepository("TransactionTypes")->find(parent::TRANSACTION_TYPE_STOCK_REQUISITION);
        $demand_master->setTransactionType($trans_type);
        $demand_master->setRequisitionReference($array['requisition_reference']);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $demand_master->setCreatedBy($created_by);
        $demand_master->setModifiedBy($created_by);
        $demand_master->setCreatedDate(App_Tools_Time::now());
        $demand_master->setModifiedDate(App_Tools_Time::now());
        $activity_id = $this->_em->getRepository('StakeholderActivities')->find($array['activity_id']);
        $demand_master->setStakeholderActivity($activity_id);
        $from_wh = $this->_em->getRepository("Warehouses")->find($this->_identity->getWarehouseId());
        $demand_master->setFromWarehouse($from_wh);
        $to_wh = $this->_em->getRepository("Warehouses")->find($array['warehouse']);
        $demand_master->setToWarehouse($to_wh);

        $demand_master->setComments($array['comments']);

        $requisition_number = $this->getTempTransactionNumber(parent::TRANSACTION_TYPE_STOCK_REQUISITION, App_Tools_Time::now()->format('d/m/Y H:i:s'), $this->_identity->getWarehouseId());

        $demand_master->setDraft('1');
        $demand_master->setIsDeleted('0');
        $demand_master->setRequisitionCounter($requisition_number['id']);
        $demand_master->setRequisitionNumber($requisition_number['trans_no']);

        $this->_em->persist($demand_master);
        $this->_em->flush();

        return $demand_master->getPkId();
    }

    /**
     * 
     * @param type $id
     * @param type $comments
     * @return type
     */
    public function updateDemandMasterTemp($id, $comments = null) {
        $stock = $this->_table->find($id);
        $trans = $this->getTransactionNumber($stock->getTransactionType()->getPkId(), $stock->getCreatedDate()->format("d/m/Y"), $this->_identity->getWarehouseId(), $stock->getPkId());
        $stock->setDraft(0);
        $stock->setRequisitionCounter($trans['id']);
        $stock->setRequisitionNumber($trans['trans_no']);
        $stock->setComments($comments);

        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $stock->setModifiedBy($user);
        $stock->setModifiedDate(App_Tools_Time::now());

        $this->_em->persist($stock);
        $this->_em->flush();
        return $trans['trans_no'];
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


        return array(
            "id" => $last_id,
            "trans_no" => "RQ" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
        );
    }

    /**
     * 
     * @param type $tr_type
     * @param type $tr_date
     * @param type $wh_id
     * @return type
     */
    public function getTempTransactionNumber($tr_type, $tr_date, $wh_id = null) {

        $time_arr = explode(' ', $tr_date);

        $current_date = explode("/", $time_arr['0']);

        $current_month = $current_date[1];
        $current_year = $current_date[2];

        $from_date = $current_year . "-" . $current_month . "-01";
        $to_date = $current_year . "-" . $current_month . "-31";


        $last_id = $this->getTempLastID($from_date, $to_date, $tr_type, $wh_id);

        if ($last_id == NULL) {
            $last_id = 0;
        }

        $last_id += 1;

        return array(
            "id" => $last_id,
            "trans_no" => "RQ" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
        );
    }

    /**
     * 
     * @param type $from
     * @param type $to
     * @param type $tr_type
     * @param type $wh_id
     * @return boolean
     */
    public function getTempLastID($from, $to, $tr_type, $wh_id = null) {

        if ($wh_id == null) {
            $wh_id = $this->_identity->getWarehouseId();
        }
        $str_sql = $this->_em->createQueryBuilder()
                ->select('MAX(sm.requisitionCounter) as Maxtr')
                ->from("DemandMaster", "sm")
                ->where("DATE_FORMAT(sm.createdDate,'%Y-%m-%d') between '" . $from . "' and '" . $to . "'")
                // ->andWhere("sm.draft=1")
                ->andWhere("sm.fromWarehouse = " . $wh_id);

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
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
        $str_sql = $this->_em->createQueryBuilder()
                ->select('MAX(sm.requisitionCounter) as Maxtr')
                ->from("DemandMaster", "sm")
                ->where("DATE_FORMAT(sm.createdDate,'%Y-%m-%d') between '" . $from . "' and '" . $to . "'")
                ->andWhere("sm.transactionType =  $tr_type ")
                ->andWhere("sm.pkId !=  $trans_id ")
                ->andWhere("sm.fromWarehouse = " . $wh_id);

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
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
        $str_sql = $this->_em->createQueryBuilder()
                ->select('MAX(sm.requisitionCounter) as Maxtr')
                ->from("DemandMaster", "sm")
                ->where("DATE_FORMAT(sm.createdDate,'%Y-%m-%d') between '" . $from . "' and '" . $to . "'")
                ->andWhere("sm.fromWarehouse = " . $wh_id);

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['Maxtr'];
        } else {
            return FALSE;
        }
    }

    /**
     * Stock Issue Search
     *
     * @return boolean
     */
    public function stockRequisitionSearch() {

        $wh_id = $this->_identity->getWarehouseId();
        $sa_select = '';

        if ($this->form_values['searchby'] == 1) {
            $tranNo = 1;
        } else if ($this->form_values['searchby'] == 2) {
            $tranRef = 1;
        } else {
            $tranNo = "";
            $tranRef = "";
        }
        if (!empty($tranNo)) {
            $where[] = "s.requisitionNumber like '%" . $this->form_values['number'] . "%'";
        }

        if (!empty($tranRef)) {
            $where[] = "s.requisitionReference like '%" . $this->form_values['number'] . "%'";
        }
        if (!empty($this->form_values['warehouses'])) {
            $where[] = "s.toWarehouse  = '" . $this->form_values['warehouses'] . "'";
        }
        if (!empty($this->form_values['product'])) {
            $where[] = "sd.itemPackSize = '" . $this->form_values['product'] . "'";
        }
        $sa_join = false;
        if (!empty($this->form_values['activity_id'])) {
            $where[] = "s.stakeholderActivity = '" . $this->form_values['activity_id'] . "'";
            $sa_select = "a.pkId as activity_id,";
            $sa_join = true;
        }
        if (!empty($this->form_values['date_from']) && !empty($this->form_values['date_to'])) {
            $where[] = "DATE_FORMAT(s.createdDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_from']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['date_to']) . "'";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(s.createdDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }

        $where[] = "s.transactionType=18";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $master = "s.pkId";
        $detail = "sd.pkId";

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.createdDate,"
                        . " $master, s.requisitionNumber,"
                        . "s.requisitionReference,"
                        . "w.warehouseName,"
                        . "fw.warehouseName as fromWH,"
                        . "sd.demandQuantity as quantity,"
                        . "sd.approvedQuantity,"
                        . "s.draft,"
                        . "s.suggestedDate,"
                        . "s.approvedDate,"
                        . "s.fromDate,"
                        . $sa_select
                        . "$detail as detailId,"
                        . "p.pkId as itemPackSizeId,"
                        . "p.itemName,DATE_FORMAT(s.createdDate,'%d/%m/%Y') createdDate")
                ->from("DemandDetail", "sd")
                ->join("sd.demandMaster", "s")
                ->join("s.toWarehouse", "w")
                ->join("s.fromWarehouse", "fw")
                ->join("sd.product", "p")
                ->where("sd.isDeleted = '0'")
                ->andWhere("s.isDeleted = '0'");

        if ($sa_join) {
            $str_sql->join("s.stakeholderActivity", "a");
        }

        $str_sql->where($where_s . " AND s.fromWarehouse=" . $wh_id)
                ->orderBy("s.requisitionNumber");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /*
     * get all requisitions list submited to logged in user store.
     */

    public function getAllRequisitions() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT "
                        . "dm.pkId, dm.requisitionNumber,"
                        . "dm.requisitionReference,"
                        . "w.warehouseName,"
                        . "dm.suggestedDate,"
                        . "fw.warehouseName as fromWH,"
                        . "dm.draft,"
                        . "dm.suggestedDate,"
                        . "dm.approvedDate,"
                        . "sm.pkId as stockMasterId,"
                        . "sm.transactionNumber,"
                        . "dm.fromDate,"
                        . "DATE_FORMAT(dm.createdDate,'%d/%m/%Y') createdDate,"
                        . "a.activity")
                ->from("DemandMaster", "dm")
                ->join("dm.toWarehouse", "w")
                ->join("dm.fromWarehouse", "fw")
                ->leftJoin("dm.stockMaster", "sm")
                ->join("dm.stakeholderActivity", "a")
                ->where("dm.transactionType=18")
                // 1 is Draft, 0 is submitted, 2 is approved, 3 is issued.
                ->andWhere("dm.draft!='1'")
                ->andWhere("dm.isDeleted ='0'")
                ->andWhere("dm.isDeleted ='0'");


        if (!empty($this->form_values['from_date']) && !empty($this->form_values['to_date'])) {
            $str_sql->andWhere("DATE_FORMAT(dm.createdDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['to_date']) . "'");
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $str_sql->andWhere("DATE_FORMAT(dm.createdDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
        }

        if (!empty($this->form_values['from_warehouse_id'])) {
            $str_sql->andWhere("dm.fromWarehouse = '" . $this->form_values['from_warehouse_id'] . "'");
        }

        if ($this->form_values['status'] != '') {
            $str_sql->andWhere("dm.draft = '" . $this->form_values['status'] . "'");
        }

        $str_sql->orderBy("dm.createdDate");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /*
     * get requisitions of currently logged in user/store/
     */

    public function getStoreRequisitions() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT "
                        . "dm.pkId, dm.requisitionNumber,"
                        . "dm.requisitionReference,"
                        . "w.warehouseName,"
                        . "dm.suggestedDate,"
                        . "fw.warehouseName as fromWH,"
                        . "dm.draft,"
                        . "dm.suggestedDate,"
                        . "dm.approvedDate,"
                        . "sm.pkId as stockMasterId,"
                        . "sm.transactionNumber,"
                        . "dm.fromDate,"
                        . "DATE_FORMAT(dm.createdDate,'%d/%m/%Y') createdDate,"
                        . "a.activity")
                ->from("DemandMaster", "dm")
                ->join("dm.toWarehouse", "w")
                ->join("dm.fromWarehouse", "fw")
                ->leftJoin("dm.stockMaster", "sm")
                ->join("dm.stakeholderActivity", "a")
                ->where("dm.fromWarehouse=" . $wh_id)
                ->andWhere("dm.transactionType=18")
                ->andWhere("dm.isDeleted='0'");

        if (!empty($this->form_values['from_date']) && !empty($this->form_values['to_date'])) {
            $str_sql->andWhere("DATE_FORMAT(dm.createdDate,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['from_date']) . "' AND '" . App_Controller_Functions::dateToDbFormat($this->form_values['to_date']) . "'");
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $str_sql->andWhere("DATE_FORMAT(dm.createdDate,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'");
        }
        if (!empty($this->form_values['item_pack_size_id'])) {
            $str_sql->andWhere("sd.product = '" . $this->form_values['item_pack_size_id'] . "'");
        }
        if (!empty($this->form_values['from_warehouse_id'])) {
            $str_sql->andWhere("dm.toWarehouse = '" . $this->form_values['from_warehouse_id'] . "'");
        }

        if (isset($this->form_values['status'])) {
            $str_sql->andWhere("dm.draft = '" . $this->form_values['status'] . "'");
        }

        $str_sql->groupBy("dm.requisitionNumber");
        $str_sql->orderBy("dm.createdDate");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /*
     * get all requisitions list submited to logged in user store.
     */

    public function getRequisitionDetails() {
        $deman_id = $this->form_values['pk_id'];

        $master = "s.pkId";
        $detail = "sd.pkId";

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.createdDate,"
                        . " $master, s.requisitionNumber,"
                        . "s.requisitionReference,"
                        . "w.warehouseName,"
                        . "sd.comments as detail_comments,"
                        . "fw.warehouseName as fromWH,"
                        . "sd.demandQuantity as quantity,"
                        . "sd.approvedQuantity,"
                        . "s.draft,"
                        . "s.comments,"
                        . "s.suggestedDate,"
                        . "s.approvedDate,"
                        . "$detail as detailId,"
                        . "p.pkId as itemPackSizeId,"
                        . "a.activity,"
                        . "s.fromDate,"
                        . "p.itemName,DATE_FORMAT(s.createdDate,'%d/%m/%Y') createdDate")
                ->from("DemandDetail", "sd")
                ->join("sd.demandMaster", "s")
                ->join("s.toWarehouse", "w")
                ->join("s.fromWarehouse", "fw")
                ->join("sd.product", "p")
                ->leftJoin("s.stockMaster", "sm")
                ->join("s.stakeholderActivity", "a")
                ->where("s.pkId=" . $deman_id)
                ->andWhere("s.transactionType=18")
                ->orderBy("s.modifiedDate");


        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $demandId
     * @return boolean
     */
    public function getRequisitionProducts($demandId) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("s.createdDate,"
                        . "s.requisitionNumber,"
                        . "s.requisitionReference,"
                        . "w.warehouseName,"
                        . "fw.warehouseName as fromWH,"
                        . "sd.demandQuantity as quantity,"
                        . "sd.approvedQuantity,"
                        . "s.draft,"
                        . "s.suggestedDate,"
                        . "s.approvedDate,"
                        . "p.pkId as itemPackSizeId,"
                        . "a.activity,"
                        . "s.fromDate,"
                        . "p.itemName,DATE_FORMAT(s.createdDate,'%d/%m/%Y') createdDate")
                ->from("DemandDetail", "sd")
                ->join("sd.demandMaster", "s")
                ->join("s.toWarehouse", "w")
                ->join("s.fromWarehouse", "fw")
                ->join("sd.product", "p")
                ->leftJoin("s.stockMaster", "sm")
                ->join("s.stakeholderActivity", "a")
                ->where("s.pkId=" . $demandId)
                ->andWhere("s.transactionType=18")
                ->andWhere("sd.approvedQuantity>0")
                ->orderBy("p.listRank");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $stock_master_id
     * @param type $item_pack_size_id
     * @return boolean
     */
    public function getRequisitionProductIssuedQty($stock_master_id, $item_pack_size_id) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("SUM(ABS(IFNULL(sd.quantity,0)))")
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "s")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sips")
                ->join("sips.itemPackSize", "ips")
                ->where("s.pkId=" . $stock_master_id)
                ->andWhere("ips.pkId=" . $item_pack_size_id);


        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * save requisition attachments.
     * @param type $demandId
     * @param type $file_path
     */
    public function saveRequisitionAttachments($demandId, $file_path) {
        $demand = $this->_table->find($demandId);
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $df = $this->_em->getRepository('DemandFiles')->find($demandId);
        if (count($df) == 0) {
            $df = new DemandFiles();
        }

        $df->setDemandMaster($demand);
        $df->setFileName($file_path);
        $df->setCreatedBy($user);
        $df->setCreatedDate(App_Tools_Time::now());
        $df->setModifiedBy($user);
        $df->setIsDeleted(0);
        $df->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($df);
        $this->_em->flush();
    }

    /*
     * 
     * Gets requisition attachments list if any.
     */

    public function getRequisitionAttachments() {
        $deman_id = $this->form_values['pk_id'];

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("df.fileName AS file_path")
                ->from("DemandFiles", "df")
                ->where("df.demandMaster=" . $deman_id);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Delete Demand Master
     * 
     * @param type $id
     * @return boolean
     */
    public function deleteDemandMaster($id) {
        if (!$this->detailsExists($id)) {
            $stock = $this->_table->find($id);
            // Set is deleted flag to true.
            $stock->setIsDeleted('1');
            $this->_em->persist();
            $this->_em->flush();

            $str_sql_files = "UPDATE demand_files SET is_deleted = '1' WHERE demand_master_id = " . $id;
            $files = $this->_em_read->getConnection()->prepare($str_sql_files);
            $files->execute();

            return true;
        }
    }

    /**
     * Check if any record of detail exists.
     * 
     * @param type $id
     * @return boolean
     */
    public function detailsExists($id) {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("dd.pkId")
                ->from("DemandDetail", "dd")
                ->where('dd.demandMaster = ' . $id)
                ->andWhere("dd.isDeleted = '0'");
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Delets master and its details entries.
     * @param type $id
     */
    public function deleteRequisitionMaster($id) {
        $str_sql_detail = "UPDATE demand_detail SET is_deleted = '1' WHERE demand_master_id = " . $id;
        $detail = $this->_em_read->getConnection()->prepare($str_sql_detail);
        $detail->execute();

        $str_sql_master = "UPDATE demand_master SET is_deleted = '1' WHERE pk_id = " . $id;
        $master = $this->_em_read->getConnection()->prepare($str_sql_master);
        $master->execute();

        $str_sql_files = "UPDATE demand_files SET is_deleted = '1' WHERE demand_master_id = " . $id;
        $files = $this->_em_read->getConnection()->prepare($str_sql_files);
        $files->execute();
    }

    /**
     * Get product allocated qty for the quarter.
     * 
     * @return type
     */
    public function getProductAllocatedQty() {
        $item_id = $this->form_values['item_id'];
        $period = $this->form_values['period'];
        $wh_id = $this->_identity->getWarehouseId();
        $qtr_from_date = explode('-', $period);
        $from_date = $qtr_from_date[0];
        $date_elements = explode('/', $from_date);
        $year = $date_elements[2];

        $str_sql = "SELECT
                        epi_amc.amc * 3 as allocation
                    FROM
                        epi_amc                       
                    WHERE
                        epi_amc.item_id = $item_id"
                . " AND epi_amc.amc_year = $year"
                . " AND epi_amc.warehouse_id = $wh_id";

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
     * Gets product remaining qty for the quarter.
     * 
     * @return type
     */
    public function getProductRemainingBalance() {
        $item_id = $this->form_values['item_id'];
        $period = $this->form_values['period'];
        $wh_id = $this->_identity->getWarehouseId();
        $qtr_from_date = explode('-', $period);
        list($fd, $fm, $fy) = explode("/", $qtr_from_date[0]);
        list($td, $tm, $ty) = explode("/", $qtr_from_date[1]);

        $from_date = "$fy-$fm-$fd";
        $to_date = "$ty-$tm-$td";

        $str_sql = "SELECT
                            SUM(
                                    demand_detail.demand_quantity
                            ) AS demanded_qty
                    FROM
                            demand_detail
                    INNER JOIN demand_master ON demand_detail.demand_master_id = demand_master.pk_id
                    WHERE
                    demand_master.from_date = '$from_date'
                    AND demand_master.to_date = '$to_date'
                    AND demand_detail.product_id = $item_id
                    AND demand_master.from_warehouse_id = $wh_id";

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
