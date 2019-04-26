<?php

/**
 * Model_Warehouses
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Warehouses
 */
class Model_Warehouses extends Model_Base {

    /**
     * $_table
     * @var type
     */
    protected $_table;

    const FEDERAL_WHID = 159;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Warehouses');
    }

    /**
     * Get Supplier Warehouses
     * @return type
     */
    public function getSupplierWarehouses() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.warehouseName as warehouse_name, w.pkId as pk_id')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "s")
                ->where("s.stakeholderType = 2")
                ->andWhere("w.pkId != 0")
                ->andWhere("w.status = 1");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get User Receive From Warehouse
     * @return boolean
     */
    public function getUserReceiveFromWarehouse() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = $this->_em_read->createQueryBuilder()->select("fw.warehouseName,fw.pkId")
                ->from('StockMaster', 'sm')
                ->join('sm.fromWarehouse', 'fw')
                ->where("sm.toWarehouse = $wh_id")
                ->andWhere("sm.transactionType = 1")
                ->groupBy("fw.warehouseName");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get User Issue To Warehouse
     * @return boolean
     */
    function getUserIssueToWarehouse() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = $this->_em_read->createQueryBuilder()->select('w.warehouseName,w.pkId')
                ->from("StockMaster", "sm")
                ->innerJoin("sm.toWarehouse", "w")
                ->where("sm.fromWarehouse = $wh_id ")
                ->groupBy("w.warehouseName");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse By Asset Type
     * @return type
     */
    public function getWarehouseByAssetType() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $qry = "SELECT DISTINCT warehouses.warehouse_type_id, warehouse_types.warehouse_type_name
                FROM warehouses
                INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                WHERE ( ccm_asset_types.parent_id = 1 OR ccm_asset_types.pk_id = 1 )
                AND warehouses.`status` = 1 ORDER BY warehouse_types.pk_id ASC ";
        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Warehouse Name By Warehouse Id
     * @param type $wh_id
     * @return boolean
     */
    public function getWarehouseNameByWarehouseId($wh_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wh.warehouseName as warehouse_name')
                ->from("Warehouses", "wh")
                ->where("wh.pkId = " . $wh_id)
                ->andWhere("wh.status = 1");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['warehouse_name'];
        } else {
            return FALSE;
        }
    }

    /**
     * get Warehouse Province by WarehouseId.
     * @param type $wh_id
     * @return boolean
     */
    public function getWarehouseProvinceByWarehouseId($wh_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('loc.pkId as province_id, loc.locationName as province_name')
                ->from("Warehouses", "wh")
                ->join("wh.province", "loc")
                ->where("wh.pkId = " . $wh_id)
                ->andWhere("wh.status = 1");
        // echo $str_sql->getQuery()->getSql();exit;
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Get All UC Centers
     * @return boolean
     */
    function getAllUCCenters() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT w.pkId, w.warehouseName")
                ->from("Warehouses", "w")
                ->where("w.location  = '" . $this->form_values['location_id'] . "' ")
                ->AndWhere('w.stakeholder=1')
                ->andWhere('w.status = 1');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return FALSE;
        }
    }

    /**
     * Get Stakeholder Id By Warehouse Id
     * @return boolean
     */
    public function getStakeholderIdByWarehouseId() {
        $warehouse = $this->_table->find($this->form_values['pk_id']);
        if ($warehouse) {
            return $warehouse->getStakeholder()->getPkId();
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse Level By Id
     * @return boolean
     */
    public function getWarehouseLevelById() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('gl.pkId')
                ->from("Warehouses", "wh")
                ->innerJoin("wh.stakeholderOffice", "sh")
                ->innerJoin("sh.geoLevel", "gl")
                ->where("wh.pkId = " . $this->form_values['pk_id'])
                ->andWhere("wh.status = 1");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row[0]['pkId'];
        } else {
            return false;
        }
    }

    /**
     * Get Warehouses By Level
     * @param type $level
     * @return boolean
     */
    public function getWarehousesByLevel($level) {
        if ($level == 4) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('w.pkId,w.warehouseName')
                    ->from("Warehouses", "w")
                    ->innerJoin("w.stakeholderOffice", "st")
                    ->where("w.stakeholder = 1")
                    ->andWhere("st.geoLevel = $level")
                    ->andWhere("w.status = 1")
                    ->andWhere("w.province = 1")
                    ->orderBy("w.warehouseName", "ASC");
        } else {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('w.pkId,w.warehouseName')
                    ->from("Warehouses", "w")
                    ->innerJoin("w.stakeholderOffice", "st")
                    ->where("w.stakeholder = 1")
                    ->andWhere("st.geoLevel = $level")
                    ->andWhere("w.status = 1")
                    ->orderBy("w.warehouseName", "ASC");
        }
        $data = $str_sql->getQuery()->getResult();
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Federal Warehouses
     * @return boolean
     */
    public function getFederalWarehouses() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->where("w.stakeholder = " . $this->form_values['stakeholder_id'])
                ->andWhere("st.geoLevel = 1")
                ->andWhere("w.status = 1")
                ->orderBy("st.pkId", "ASC");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Provincial Warehouses
     * @return boolean
     */
    public function getProvincialWarehouses() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->where("w.stakeholder =  " . $this->form_values['stakeholder_id'])
                ->andWhere("w.province =  " . $this->form_values['province_id'])
                ->andWhere("st.geoLevel = 2")
                ->andWhere("w.status = 1")
                ->orderBy("w.warehouseName");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();

            foreach ($rs as $row) {

                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Divsional Warehouses of Province
     * @return boolean
     */
    public function getDivsionalWarehousesofProvince() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->where("w.stakeholder =  " . $this->form_values['stakeholder_id'])
                ->andWhere("w.province =  " . $this->form_values['province_id'])
                ->andWhere("st.geoLevel = 3")
                ->andWhere("w.status = 1")
                ->orderBy("w.warehouseName");

        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get District Warehouses of Province
     * @return boolean
     */
    public function getDistrictWarehousesofProvince() {
        $str_sql = "SELECT w0_.pk_id AS pkId, w0_.warehouse_name AS warehouseName
                FROM warehouses AS w0_
                INNER JOIN stakeholders AS s1_ ON w0_.stakeholder_office_id = s1_.pk_id
                WHERE w0_.stakeholder_id = " . $this->form_values['stakeholder_id'] . "
                AND w0_.province_id = " . $this->form_values['province_id'] . "
                AND s1_.geo_level_id = 4 ORDER BY w0_.warehouse_name ASC";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $rs = $row->fetchAll();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get IHR Warehouses
     * @param type $user_stk
     * @return boolean
     */
    function getIHRWarehouses($user_stk) {
        $userrole = $this->_identity->getRoleId();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->join("w.stakeholderOffice", "st")
                ->where("st.pkId =  " . $user_stk)
                ->andWhere("w.status = 1")
                ->andWhere("st.geoLevel = 6");

        if ($userrole >= 4) {
            $loc_id = $this->_identity->getDistrictId();
            $str_sql->andWhere("w.district = " . $loc_id);
        }

        $str_sql->orderBy("st.pkId");

        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Tehsil Warehouses of District
     * @return boolean
     */
    public function getTehsilWarehousesofDistrict() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->join("w.stakeholderOffice", "st")
                ->where("w.stakeholder =  " . $this->form_values['stakeholder_id'])
                ->andWhere("w.status=1")
                ->andWhere("w.district =  " . $this->form_values['district_id'])
                ->andWhere("st.geoLevel = 5")
                ->orderBy("w.warehouseName");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get UC Warehouses of District
     * @return boolean
     */
    public function getUCWarehousesofDistricts() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->where("w.district =  " . $this->form_values['district_id'])
                ->andWhere("st.geoLevel = 6")
                ->andWhere("w.status=1")
                ->andWhere("w.stakeholderOffice = 6")
                ->orderBy("w.warehouseName");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get UC Warehouses of District Issue
     * @return boolean
     */
    public function getUCWarehousesofDistrictIssue() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("WarehouseUsers", "wu")
                ->innerJoin("wu.warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->where("w.district =  " . $this->form_values['district_id'])
                ->andWhere("st.geoLevel = 6")
                ->andWhere("w.stakeholderOffice = 6")
                ->andWhere('w.status=1')
                ->orderBy("w.warehouseName");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse Non Reported Districts
     */
    public function getWarehouseNonReportedDistricts() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->join("w.stakeholder", "s")
                ->join("w.location", "l")
                ->where("l.geoLevel = 6")
                ->andWhere('w.status=1')
                ->orderBy("l.locationName", "ASC");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Warehouses
     */
    public function getAllWarehouses($order, $sort) {
        $form_values = $this->form_values;
        if (!empty($form_values['stakeholder'])) {
            $where[] = "so.pk_id = '" . $form_values['stakeholder'] . "'";
        }
        if ($form_values['stakeholder'] != 10 && $form_values['stakeholder'] != 9) {
            if (!empty($form_values['office_type'])) {
                $where[] = "gl.pk_id = '" . $form_values['office_type'] . "'";
            } else {
                $where[] = "gl.pk_id IN (2,3,4,5,6)";
            }
        }
        if (!empty($form_values['combo1'])) {
            $where[] = "p.pk_id = '" . $form_values['combo1'] . "'";
        }
        if (!empty($form_values['combo2'])) {
            $where[] = "d.pk_id  = '" . $form_values['combo2'] . "'";
        }
        if (!empty($form_values['combo3']) && $form_values['office_type'] == 5) {
            $where[] = "l.pk_id = '" . $form_values['combo3'] . "'";
        }
        if (!empty($form_values['combo3']) && $form_values['office_type'] == 6 && empty($form_values['combo4'])) {
            $where[] = "l.parent_id = '" . $form_values['combo3'] . "'";
        }
        if (!empty($form_values['combo4']) && $form_values['office_type'] == 6) {
            $where[] = "l.pk_id = '" . $form_values['combo4'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        if (!empty($form_values['location_name_hdn'])) {
            $location_name = $form_values['location_name_hdn'];
            $str_sql_order = "Order By w.warehouse_name='$location_name' DESC,w.warehouse_name";
        } else {
            $str_sql_order = "Order By w.warehouse_name";
        }
//        $str_sql = $this->_em_read->createQueryBuilder()
//                ->select('w.pkId,w.warehouseName,w.status,so.pkId as stakeholderOfficeId,'
//                        . 's.stakeholderName,p.locationName as provinceName,'
//                        . 'd.locationName as districtName,l.locationName as UC')
//                ->from("Warehouses", "w")
//                ->join("w.stakeholderOffice", "so")
//                ->join("so.geoLevel", "gl")
//                ->join("w.stakeholder", "s")
//                ->join("w.province", "p")
//                ->join("w.district", "d")
//                ->join("w.location", "l")
//                ->where($where_s);
//        echo $str_sql->getQuery()->getSql();
//
//        return $str_sql->getQuery()->getResult();


        $str_sql = "SELECT
	w.pk_id AS pkId,
	w.warehouse_name AS warehouseName,
	w. STATUS AS status,
	so.pk_id AS stakeholderOfficeId,
	s.stakeholder_name AS stakeholderName,
	p.location_name AS provinceName,
	d.location_name AS districtName,
	l.location_name AS UC,
	t.location_name AS tehsilName
        
        FROM
                warehouses w
        INNER JOIN stakeholders so ON w.stakeholder_office_id = so.pk_id
        INNER JOIN geo_levels gl ON so.geo_level_id = gl.pk_id
        INNER JOIN stakeholders s ON w.stakeholder_id = s.pk_id
        INNER JOIN locations p ON w.province_id = p.pk_id
        INNER JOIN locations d ON w.district_id = d.pk_id
        INNER JOIN locations l ON w.location_id = l.pk_id
        INNER JOIN locations t ON l.parent_id = t.pk_id

          Where $where_s  $str_sql_order";



        $rec = $this->_em_read->getConnection()->prepare($str_sql);

        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get All Warehouses Inventory
     */
    public function getAllWarehousesInventory($order, $sort) {
        $form_values = $this->form_values;
        if (!empty($form_values['stakeholder'])) {
            $where[] = "so.pk_id = '" . $form_values['stakeholder'] . "'";
        }
        if (!empty($form_values['office_type'])) {
            $where[] = "so.pk_id = '" . $form_values['office_type'] . "'";
        } else {
            if ($this->_identity->getRoleId() == 38) {
                $where[] = "so.pk_id IN (3,4)";
            } else if ($this->_identity->getRoleId() == 39) {
                $where[] = "so.pk_id IN (5)";
            } else {
                $where[] = "so.pk_id IN (2,3,4,5)";
            }
        }
        if (!empty($form_values['combo1'])) {
            $where[] = "p.pk_id = '" . $form_values['combo1'] . "'";
        }
        if (!empty($form_values['combo2'])) {
            $where[] = "d.pk_id  = '" . $form_values['combo2'] . "'";
        }
        if (!empty($form_values['combo3']) && $form_values['office_type'] == 5) {
            $where[] = "l.pk_id = '" . $form_values['combo3'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
//        $str_sql = $this->_em_read->createQueryBuilder()
//                ->select('w.pkId,w.warehouseName,so.pkId as stakeholderOfficeId,'
//                        . 's.stakeholderName,p.locationName as provinceName,'
//                        . 'd.locationName as districtName,l.locationName as UC')
//                ->from("Warehouses", "w")
//                ->join("w.stakeholderOffice", "so")
//                ->join("w.stakeholder", "s")
//                ->join("w.province", "p")
//                ->join("w.district", "d")
//                ->join("w.location", "l")
//                ->where($where_s)
//                ->andWhere('w.status=1');
//       echo $str_sql->getQuery()->getSql();
//        return $str_sql->getQuery()->getResult();
//

        if (!empty($form_values['store_name_add_hdn'])) {
            $location_name = $form_values['store_name_add_hdn'];
            $str_sql_order = "Order By w.warehouse_name='$location_name' DESC,w.warehouse_name";
        } else {
            $str_sql_order = "Order By w.warehouse_name";
        }

        $str_sql = "SELECT
	w.pk_id AS pkId,
	w.warehouse_name AS warehouseName,
        w.status,
	so.pk_id AS stakeholderOfficeId,
	s.stakeholder_name AS stakeholderName,
	p.location_name AS provinceName,
	d.location_name AS districtName,
	l.location_name AS UC
        FROM
                warehouses w
        INNER JOIN stakeholders so ON w.stakeholder_office_id = so.pk_id
        INNER JOIN stakeholders s ON w.stakeholder_id = s.pk_id
        INNER JOIN locations p ON w.province_id = p.pk_id
        INNER JOIN locations d ON w.district_id = d.pk_id
        INNER JOIN locations l ON w.location_id = l.pk_id
        WHERE $where_s  $su_sql $str_sql_order";



        $rec = $this->_em_read->getConnection()->prepare($str_sql);




        $rec->execute();

        return $rec->fetchAll();
    }

    /**
     * Get Wharehouse Users Id
     */
    public function getWharehouseUsersId($wh_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wu.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->where("u.pkId=" . $wh_id);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Wharehouse Users Id
     */
    public function getWharehouseUsers($wh_id) {
       $str_sql1 = $this->_em_read->createQueryBuilder()
                ->select('p.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("wu.warehouse","w")
               ->join("w.province","p")
                ->where("u.pkId=" . $wh_id);
        $res = $str_sql1->getQuery()->getResult();
      
        if ($res[0]['pkId']==1){
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wu.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->where("u.pkId=" . $wh_id);
        }else {
             $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wu.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->where("u.pkId=" . $wh_id)
                ->andWhere("wu.isDefault <> 1 OR wu.isDefault IS NULL");
        }
        
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Warehouse Id By Uc Id
     * @return type
     */
    public function getWarehouseIdByUcId() {
        $form_values = $this->form_values;
        if ($form_values['office_type_add'] == 1) {
            $where = "so.pkId=  '" . $form_values['office_type_add'] . "'   ";
        }
        if ($form_values['office_type_add'] == 2) {
            $where = "p.pkId= '" . $form_values['combo1_add'] . "' and so.pkId=  '" . $form_values['office_type_add'] . "'   ";
        }
        if ($form_values['office_type_add'] == 3) {
            $where = "p.pkId= '" . $form_values['combo1_add'] . "' and  so.pkId=  '" . $form_values['office_type_add'] . "'  ";
        }
        if ($form_values['office_type_add'] == 4 && $form_values['page'] == 'campaigns') {
            $where = "d.pkId= '" . $form_values['combo2_add'] . "' and so.pkId= '" . Model_Stakeholders::CAMPAIGN_TEAMS . "'  ";
        }
        if ($form_values['office_type_add'] == 4 && $form_values['page'] == 'inventory') {
            $where = "d.pkId= '" . $form_values['combo2_add'] . "' and so.pkId=  '" . $form_values['office_type_add'] . "'  ";
        }
        if ($form_values['office_type_add'] == 5) {
            $where = "l.pkId= '" . $form_values['combo3_add'] . "' and so.pkId=  '" . $form_values['office_type_add'] . "' ";
        }
        if ($form_values['office_type_add'] == 6) {
            $where = "l.pkId= '" . $form_values['combo4_add'] . "' and so.pkId=  '" . $form_values['office_type_add'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId')
                ->from("Warehouses", "w")
                ->join("w.location", "l")
                ->join("w.province", "p")
                ->join("w.district", "d")
                ->join("w.stakeholderOffice", "so")
                ->where($where)
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Warehouse Id By Uc Id Update
     * @return type
     */
    public function getWarehouseIdByUcIdUpdate() {
        $form_values = $this->form_values;
        if ($form_values['office_type_edit'] == 1) {
            $where = "so.pkId=  '" . $form_values['office_type_edit'] . "'   ";
        }
        if ($form_values['office_type_edit'] == 2) {
            $where = "p.pkId= '" . $form_values['combo1_edit'] . "' and so.pkId=  '" . $form_values['office_type_edit'] . "'   ";
        }
        if ($form_values['office_type_edit'] == 3) {
            $where = "p.pkId= '" . $form_values['combo1_edit'] . "' and  so.pkId=  '" . $form_values['office_type_edit'] . "'  ";
        }
        if ($form_values['office_type_edit'] == 4) {
            $where = "d.pkId= '" . $form_values['combo2_edit'] . "' and so.pkId=  '" . $form_values['office_type_edit'] . "'  ";
        }
        if ($form_values['office_type_edit'] == 5) {
            $where = "l.pkId= '" . $form_values['combo3_edit'] . "' and so.pkId=  '" . $form_values['office_type_edit'] . "' ";
        }
        if ($form_values['office_type_edit'] == 6) {
            $where = "l.pkId= '" . $form_values['combo4_edit'] . "' and so.pkId=  '" . $form_values['office_type_edit'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId')
                ->from("Warehouses", "w")
                ->join("w.location", "l")
                ->join("w.province", "p")
                ->join("w.district", "d")
                ->join("w.stakeholderOffice", "so")
                ->where($where)
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Warehouse Id By User Id
     * @return type
     */
    public function getWarehouseIdByUserId() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId')
                ->from("Warehouses", "w")
                ->join("w.location", 'l')
                ->join("w.province", 'p')
                ->join("w.stakeholderOffice", "so")
                ->where("l.pkId=" . $form_values['combo4_add'])
                ->andWhere("p.pkId=" . $form_values['combo1_add'])
                ->andWhere("so.pkId=" . $form_values['office_type_add'])
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Users For Cluster By User
     * @return type
     */
    public function getAllUsersForClusterByUser() {
        $form_values = $this->form_values;
        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId")
                ->from("WarehouseUsers", "wu")
                ->join("wu.user", "u")
                ->join("wu.warehouse", "w")
                ->join("w.stakeholderOffice", "s")
                ->where("u.pkId=" . $form_values['user_id'])
                //->andWhere("s.pkId=6")
                ->andWhere('w.status=1');

        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Users For Cluster By District
     * @return type
     */
    public function getAllUsersForClusterByDistrict() {
        $form_values = $this->form_values;
        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName", "l.locationName")
                ->from("Warehouses", "w")
                ->join("w.location", "l")
                ->join("w.district", "d")
                ->join("w.stakeholderOffice", "s")
                ->where("d.pkId=" . $form_values['district_id'])
                //->andWhere("s.pkId=6")
                ->andWhere('w.status=1');
        return $qry->getQuery()->getResult();
    }

    /**
     * Check Warehouse
     * @return type
     */
    public function checkWarehouse() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.warehouseName")
                ->from('Warehouses', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('w.location', 'l')
                ->where("w.warehouseName= '" . $form_values['store_name_add'] . "' ")
                ->andWhere("so.pkId='" . $form_values['stk_id'] . "' ")
                ->andWhere("l.pkId='" . $form_values['locid_uc'] . "'")
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Warehouse Inventory
     * @return type
     */
    public function checkWarehouseInventory() {
        $form_values = $this->form_values;

        if ($form_values['stk_id'] == 2) {
            $where = "l.pkId= '" . $form_values['province'] . "' and so.pkId=  '" . $form_values['stk_id'] . "'   ";
        }
        if ($form_values['stk_id'] == 3) {
            $where = "l.pkId= '" . $form_values['district'] . "' and  so.pkId=  '" . $form_values['stk_id'] . "'  ";
        }
        if ($form_values['stk_id'] == 4) {
            $where = "l.pkId= '" . $form_values['locid'] . "' and so.pkId=  '" . $form_values['stk_id'] . "'  ";
        }
        if ($form_values['stk_id'] == 5) {
            $where = "l.pkId= '" . $form_values['locid_uc'] . "' and so.pkId=  '" . $form_values['stk_id'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.warehouseName")
                ->from('Warehouses', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('w.location', 'l')
                ->where("w.warehouseName= '" . $form_values['store_name_add'] . "' ")
                ->andWhere($where)
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Warehouse Update
     */
    public function checkWarehouseUpdate() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.warehouseName")
                ->from('Warehouses', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('w.location', 'l')
                ->where("w.warehouseName= '" . $form_values['store_name_update'] . "'  ")
                ->andWhere("so.pkId='" . $form_values['stk_id'] . "'")
                ->andWhere("l.pkId='" . $form_values['locid_uc'] . "'")
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Warehouse Inventory Update
     * @return type
     */
    public function checkWarehouseInventoryUpdate() {
        $form_values = $this->form_values;
        $wh_id = $form_values['wh_id'];
        $where1 = "w.pkId NOT IN ($wh_id)";
        if ($form_values['stk_id'] == 2) {
            $where = "l.pkId= '" . $form_values['province'] . "' and so.pkId=  '" . $form_values['stk_id'] . "'   ";
        }
        if ($form_values['stk_id'] == 3) {
            $where = "l.pkId= '" . $form_values['district'] . "' and  so.pkId=  '" . $form_values['stk_id'] . "'  ";
        }
        if ($form_values['stk_id'] == 4) {
            $where = "l.pkId= '" . $form_values['locid'] . "' and so.pkId=  '" . $form_values['stk_id'] . "'  ";
        }
        if ($form_values['stk_id'] == 5) {
            $where = "l.pkId= '" . $form_values['locid_uc'] . "' and so.pkId=  '" . $form_values['stk_id'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.warehouseName")
                ->from('Warehouses', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('w.location', 'l')
                ->where("w.warehouseName= '" . $form_values['store_name_update'] . "'  ")
                ->andWhere($where)
                ->andWhere('w.status=1')
                ->andWhere($where1);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Warehouse Type
     * @return type
     */
    public function getWarehouseType() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseTypeName")
                ->from('WarehouseTypes', 'w')
                ->where("w.geoLevelId= '" . $form_values . "' ");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Ccm Warehouse Id
     */
    public function checkCcmWarehouseId() {
        $form_values = $this->form_values;
        if ($form_values['locLvl'] == 2) {
            $where = "p.pkId ='" . $form_values['province'] . "' and gl.pkId='2' and w.ccemId='" . $form_values['ccm_warehouse_id'] . "' ";
        }
        if ($form_values['locLvl'] == 3) {
            $where = "p.pkId ='" . $form_values['province'] . "' and gl.pkId='3' and w.ccemId='" . $form_values['ccm_warehouse_id'] . "' ";
        }
        if ($form_values['locLvl'] == 4) {
            $where = "p.pkId='" . $form_values['province'] . "' and gl.pkId='4' and w.ccemId='" . $form_values['ccm_warehouse_id'] . "' ";
        }
        if ($form_values['locLvl'] == 5) {
            $where = "d.pkId='" . $form_values['district'] . "' and gl.pkId='5' and w.ccemId='" . $form_values['ccm_warehouse_id'] . "' ";
        }
        if ($form_values['locLvl'] == 6) {
            $where = "l.pkId='" . $form_values['locid'] . "' and gl.pkId='6' and w.ccemId='" . $form_values['ccm_warehouse_id'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId")
                ->from('Warehouses', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('so.geoLevel', 'gl')
                ->join('w.province', 'p')
                ->join('w.district', 'd')
                ->join('w.location', 'l')
                ->where($where)
                ->andWhere('w.status=1');
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Check Ccm Warehouse Id Update
     */
    public function checkCcmWarehouseIdUpdate() {
        $form_values = $this->form_values;
        $wh_id = $form_values['wh_id'];
        $where1 = "w.pkId NOT IN ($wh_id)";

        if ($form_values['locLvl'] == 2) {
            $where = "p.pkId ='" . $form_values['province'] . "' and gl.pkId='2' and w.ccemId='" . $form_values['ccm_warehouse_id_update'] . "' ";
        }
        if ($form_values['locLvl'] == 3) {
            $where = "p.pkId ='" . $form_values['province'] . "' and gl.pkId='3' and w.ccemId='" . $form_values['ccm_warehouse_id_update'] . "' ";
        }
        if ($form_values['locLvl'] == 4) {
            $where = "p.pkId='" . $form_values['province'] . "' and gl.pkId='4' and w.ccemId='" . $form_values['ccm_warehouse_id_update'] . "' ";
        }
        if ($form_values['locLvl'] == 5) {
            $where = "d.pkId='" . $form_values['district'] . "' and gl.pkId='5' and w.ccemId='" . $form_values['ccm_warehouse_id_update'] . "' ";
        }
        if ($form_values['locLvl'] == 6) {
            $where = "l.pkId='" . $form_values['locid'] . "' and gl.pkId='6' and w.ccemId='" . $form_values['ccm_warehouse_id_update'] . "' ";
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId")
                ->from('Warehouses', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('so.geoLevel', 'gl')
                ->join('w.province', 'p')
                ->join('w.district', 'd')
                ->join('w.location', 'l')
                ->where($where)
                ->andWhere('w.status=1')
                ->andWhere($where1);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Default Warehouse
     * @return type
     */
    public function getDefaultWarehouse() {
        $form_values = $this->form_values;
        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName")
                ->from("Warehouses", "w")
                ->join("w.location", 'l')
                ->join("w.stakeholderOffice", "s")
                ->where("l.pkId=" . $form_values['location_id'])
                ->andWhere("s.pkId=" . $form_values['geo_level_id'])
                ->andWhere('w.status=1');

        return $qry->getQuery()->getResult();
    }

    /**
     * Get Default Warehouse By Level
     * @return type
     */
    public function getDefaultWarehouseByLevel() {
        $form_values = $this->form_values;
        if ($form_values['geo_level_id'] == 1) {
            $where = "s.pkId ='" . $form_values['geo_level_id'] . "' and l.pkId='10' and p.pkId='10' and d.pkId='10'  ";
        }
        if ($form_values['geo_level_id'] == 2) {
            $where = "s.pkId='" . $form_values['geo_level_id'] . "' and l.pkId='" . $form_values['province_id'] . "' and p.pkId='" . $form_values['province_id'] . "' ";
        }
        if ($form_values['geo_level_id'] == 3) {
            $where = "s.pkId='" . $form_values['geo_level_id'] . "' and l.pkId='" . $form_values['district_id'] . "' and p.pkId='" . $form_values['province_id'] . "' and d.pkId='" . $form_values['province_id'] . "' ";
        }
        if ($form_values['geo_level_id'] == 4) {
            $where = "s.pkId='" . $form_values['geo_level_id'] . "' and l.pkId='" . $form_values['district_id'] . "' and p.pkId='" . $form_values['province_id'] . "' and d.pkId='" . $form_values['district_id'] . "'  ";
        }
        if ($form_values['geo_level_id'] == 5) {
            $where = "s.pkId='" . $form_values['geo_level_id'] . "' and l.pkId='" . $form_values['tehsil_id'] . "' and p.pkId='" . $form_values['province_id'] . "' and d.pkId='" . $form_values['district_id'] . "'  ";
        }

        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName")
                ->from("Warehouses", "w")
                ->join("w.location", 'l')
                ->join("w.province", 'p')
                ->join("w.district", 'd')
                ->join("w.stakeholderOffice", "s")
                ->where($where)
                ->andWhere('w.status=1');

        return $qry->getQuery()->getResult();
    }

    /**
     * Get Health Facility Types
     * @return type
     */
    public function getHealthFacilityTypes() {
        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseTypeName")
                ->from("WarehouseTypes", "w")
                ->where("w.geoLevelId=6");
        return $qry->getQuery()->getResult();
    }

    /**
     * Get Services Types
     * @return type
     */
    public function getServicesTypes() {
        $form_values = $this->form_values;
        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,st.listValue")
                ->from("WarehousesServiceTypes", "w")
                ->join("w.warehouse", "wa")
                ->join("w.serviceType", 'st')
                ->where("wa.pkId=" . $form_values)
                ->andWhere('wa.status=1');
        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Health Facility Types
     * @return type
     */
    public function getAllHealthFacilityTypes() {
        $qry = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseTypeName")
                ->from("WarehouseTypes", "w")
                ->orderBy("w.listRank", "ASC");
        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Health Facility Types By Stakeholder
     * @return type
     */
    public function getAllHealthFacilityTypesByStakeholder() {
        $role_id = $this->_identity->getRoleId();

        $qry = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT wt.warehouseTypeName, wt.pkId")
                ->from("Warehouses", "w")
                ->join("w.warehouseType", "wt")
                ->where("w.stakeholder = " . $this->form_values['stakeholder_id'])
                ->andWhere('w.status=1');

        if ($role_id == 13) {
            $qry->andWhere("wt.geoLevelId IN (4,6)");
        } else if ($role_id == 12) {
            $qry->andWhere("wt.geoLevelId IN (2,3,4,6)");
        } else {
            
        }
        $qry->orderBy("wt.listRank", "ASC");
        return $qry->getQuery()->getResult();
    }

    /**
     * Get Warehouse Names
     * @return type
     */
    public function getWarehouseNames() {
        $sub_sql_w = $this->_em_read->createQueryBuilder()
                ->select("w.pkId,w.warehouseName,l.locationName,p.locationName Tehsil")
                ->from('WarehouseUsers', 'wu')
                ->join('wu.warehouse', 'w')
                ->join('w.stakeholderOffice', 'so')
                ->join('wu.user', 'u')
                ->join('w.location', 'l')
                ->join("l.parent", "p")
                ->where('u.pkId=' . $this->_user_id)
                ->andWhere('so.geoLevel=6')
                ->andWhere('w.status=1')
                ->orderBy("p.locationName,l.locationName,w.warehouseName", "ASC");

        return $sub_sql_w->getQuery()->getResult();
    }

    /**
     * Get All Warehouses Report Date
     * @return type
     */
    public function getAllWarehousesReportDate() {
        $province = $this->form_values['province'];
        $district = $this->form_values['district'];
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['user'])) {
            $where[] = "warehouse_users.user_id = " . $this->form_values['user'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        if ($this->form_values['user'] == "") {
            $qry = "SELECT warehouses.warehouse_name,warehouses.pk_id,users.user_name
                FROM warehouses
                INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                INNER JOIN users ON warehouse_users.user_id = users.pk_id
                WHERE warehouses.stakeholder_office_id = 6 and warehouses.status = 1 and
                warehouses.province_id =  $province and warehouses.district_id =  $district " . $str_where . " ";
        }
        $qry = "SELECT warehouses.warehouse_name,warehouses.pk_id,warehouses.from_edit
                FROM warehouses
                INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                WHERE warehouses.stakeholder_office_id = 6 and warehouses.status = 1 and
                warehouses.province_id =  $province  and warehouses.district_id =  $district
                " . $str_where . " ";
        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Uc Warehouses of Tehsil
     * @return boolean
     */
    public function getUcWarehousesofTehsil() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName,l.locationName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->innerJoin("w.location", "l")
                ->where("l.parent =  " . $this->form_values['parent_id'])
                ->andWhere("st.geoLevel = 6")
                ->andWhere("w.status=1")
                ->andWhere("w.stakeholderOffice = 6")
                ->orderBy("w.warehouseName");

        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['locationName'] . '/' . $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Tehsil Locations
     * @return boolean
     */
    public function tehsilLocations() {
        if (!empty($this->form_values['province_id'])) {
            $where[] = "l.province = '" . $this->form_values['province_id'] . "'";
        }
        if (!empty($this->form_values['district_id'])) {
            $where[] = "l.district = '" . $this->form_values['district_id'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('l.pkId as pkId,l.locationName as warehouseName')
                ->from("Locations", "l")
                ->where("l.geoLevel = 5 ")
                ->andWhere($where_s)
                ->orderBy("l.locationName");
        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get Amc Warehouses
     * @return boolean
     */
    public function getAmcWarehouses() {
        if (!empty($this->form_values['stakeholder_id'])) {
            $stk_id = $this->form_values['stakeholder_id'];
        }
        if (!empty($this->form_values['province_id'])) {
            $pr_id = $this->form_values['province_id'];
        }
        $str_sql = "SELECT DISTINCT w0_.pk_id AS pk_id0, w0_.warehouse_name AS warehouse_name1
                    FROM warehouses AS w0_
                        INNER JOIN stakeholders AS s1_ ON w0_.stakeholder_office_id = s1_.pk_id
                        INNER JOIN epi_amc ON epi_amc.warehouse_id = w0_.pk_id
                    WHERE w0_.stakeholder_id = $stk_id AND w0_.province_id = $pr_id AND w0_. STATUS = 1
                    ORDER BY w0_.warehouse_name ASC";

        $rs = $this->_em_read->getConnection()->prepare($str_sql);
        $rs->execute();
        $result = $rs->fetchAll();
        if ($result) {
            $data = array();
            foreach ($result as $row) {
                $data[] = array('key' => $row['pk_id0'], 'value' => $row['warehouse_name1']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get User Receive From Warehouse
     * @return boolean
     */
    public function getAllHfsByTehsil() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT
	warehouses.pk_id,
	warehouses.warehouse_name
        FROM
                warehouses
        INNER JOIN locations ON warehouses.location_id = locations.pk_id
        WHERE
                warehouses.stakeholder_office_id = 6
        AND locations.parent_id IN (
                SELECT
                        warehouses.location_id
                FROM
                        warehouses
                WHERE
                        warehouses.pk_id = $wh_id
        )";

        $rs = $this->_em_read->getConnection()->prepare($str_sql);
        $rs->execute();
        return $result = $rs->fetchAll();
    }

    /**
     * Get Ucs By District
     * @return type
     */
    public function getUcsByDistrict() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId as key,l.locationName as value")
                ->from('Locations', 'l')
                ->Where('l.district =' . $this->form_values['district_id'])
                ->andWhere('l.geoLevel =5')
                ->orderBy("l.locationName");
        return $str_sql->getQuery()->getResult();
    }

    public function getUCWarehousesofUc() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->where("w.location =  " . $this->form_values['uc_id'])
                ->andWhere("st.geoLevel = 6")
                ->andWhere("w.status=1")
                ->andWhere("w.stakeholderOffice = 6")
                ->orderBy("w.warehouseName");

        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    public function getUCWarehousesofDistrict() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId,w.warehouseName')
                ->from("Warehouses", "w")
                ->innerJoin("w.stakeholderOffice", "st")
                ->innerJoin("w.location", "l")
                ->innerJOin("l.district", "d")
                ->where("d.pkId =  " . $this->form_values['district_id'])
                ->andWhere("st.geoLevel = 6")
                ->andWhere("w.status=1")
                ->andWhere("w.stakeholderOffice = 6")
                ->orderBy("w.warehouseName");

        $rs = $str_sql->getQuery()->getResult();
        if ($rs) {
            $data = array();
            foreach ($rs as $row) {
                $data[] = array('key' => $row['pkId'], 'value' => $row['warehouseName']);
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * Get To requisition Warehouses
     * @return type
     */
    public function getToRequisitionWarehouses() {

        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.warehouseName as warehouse_name, w.pkId as pk_id')
                ->from("DemandWarehousesLogic", "dwl")
                ->innerJoin("dwl.toWarehouse", "w")
                ->where("dwl.fromWarehouse = " . $wh_id);


        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get From requisition Warehouses
     * @return type
     */
    public function getFromRequisitionWarehouses() {

        $wh_id = $this->_identity->getWarehouseId();

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.warehouseName as warehouse_name, w.pkId as pk_id')
                ->from("DemandWarehousesLogic", "dwl")
                ->innerJoin("dwl.fromWarehouse", "w")
                ->where("dwl.toWarehouse = " . $wh_id)
                ->orderBy("w.warehouseName");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Wharehouse Users Id
     */
    public function getWharehouseUsersDefault($wh_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('w.pkId')
                ->from("WarehouseUsers", "wu")
                ->join("wu.warehouse", "w")
                ->join("wu.user", "u")
                ->where("u.pkId=" . $wh_id)
                ->andWhere("wu.isDefault = 1");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Ucs By District
     * @return type
     */
    public function getUcsByTehsil() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("l.pkId as key,l.locationName as value")
                ->from('Locations', 'l')
                ->Where('l.parent =' . $this->form_values['uc_id'])
                ->andWhere('l.geoLevel =6')
                ->orderBy("l.locationName");

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Warehouse By Asset Type
     * @return type
     */
    public function getWarehouseByLevel($wh_type) {

        $qry = "SELECT
            locations.location_name,
            locations.pk_id
            FROM
            locations
            WHERE
            locations.pk_id = $wh_type
           ";
        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }
    
    
    /**
     * Get Warehouse By Asset Type
     * @return type
     */
    public function getWarehouseTypes() {

        $qry = "SELECT
        warehouse_type_name,
        count(warehouses.pk_id) as total
        FROM
        warehouses
        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
        where 
        warehouses.status = 1
        GROUP BY
        warehouse_types.warehouse_type_name
        order by warehouse_type_name";
        
        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

}
