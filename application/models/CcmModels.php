<?php

/**
 * Model_CcmModels
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for CCM Models
 */
class Model_CcmModels extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CcmModels');
    }

    /**
     * Get Models By Generic Make
     * @return type
     */
    public function getModelsByGenericMake() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('c.pkId,c.ccmModelName')
                ->from("CcmModels", "c")
                ->where("c.ccmAssetType =" . Model_CcmAssetTypes::ICEPACKS)
                ->andWhere("c.pkId IN (2103,2104,2106)");
        
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Quantity By Generic Make
     * @return type
     */
    public function getQuantityByGenericMake() {
        if ($this->form_values['unallocated'] == 1) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("c.pkId,c.ccmModelName,cc.quantity,cc.pkId as coldChainId")
                    ->from("ColdChain", "cc")
                    ->join("cc.ccmModel", "c")
                    ->where("c.ccmAssetType = " . Model_CcmAssetTypes::ICEPACKS)
                    ->andWhere("cc.createdBy=$this->_user_id")
                    ->andWhere("cc.warehouse IS NULL ");
            return $str_sql->getQuery()->getResult();
        } else {
            $wh_id = $this->form_values['warehouse'];
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select("c.pkId,c.ccmModelName,cc.quantity,cc.pkId as coldChainId")
                    ->from("ColdChain", "cc")
                    ->join("cc.ccmModel", "c")
                    ->where("c.ccmAssetType =" . Model_CcmAssetTypes::ICEPACKS)
                    ->andWhere("cc.createdBy=$this->_user_id")
                    ->andWhere("cc.warehouse =" . $wh_id);
            return $str_sql->getQuery()->getResult();
        }
    }

    /**
     * Get Models By Make Id
     * @return type
     */
    public function getModelsByMakeId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('c.pkId', 'c.ccmModelName')
                ->from("CcmModels", "c")
                ->where("c.ccmMake = " . $this->form_values['make_id']);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Add Vaccine Carrier
     */
    public function addVaccineCarrier() {
        $form_values = $this->form_values;
        $ccm_model = $this->_em->getRepository('CcmModels')->find($form_values['catalogue_id']);
        $asset_id_m = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::VACCINECARRIER);
        $ccm_model->setCcmAssetType($asset_id_m);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $ccm_model->setCreatedBy($created_by);
        $ccm_model->setModifiedBy($created_by);
        $ccm_model->setCreatedDate(App_Tools_Time::now());
        $ccm_model->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_model);
        $this->_em->flush();
        $model_id = $ccm_model->getPkId();
        $cold_chain = new ColdChain();
        $cold_chain->setAutoAssetId(App_Controller_Functions::generateCcemUniqueAssetId(Model_CcmAssetTypes::VACCINECARRIER));
        $cold_chain->setQuantity($form_values['quantity']);
        $asset_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::VACCINECARRIER);
        $cold_chain->setCcmAssetType($asset_id);
        $m_id = $this->_em->getRepository('CcmModels')->find($model_id);
        $cold_chain->setCcmModel($m_id);
        if (!empty($this->form_values['warehouse'])) {
            $w_id = $this->form_values['warehouse'];
            $warehouse = $this->_em->getRepository('Warehouses')->find($w_id);
            $cold_chain->setWarehouse($warehouse);
        }
        $cold_chain->setCreatedBy($created_by);
        $cold_chain->setCreatedDate(App_Tools_Time::now());
        $cold_chain->setModifiedBy($created_by);
        $cold_chain->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($cold_chain);
        $this->_em->flush();
        $cold_chain_id = $cold_chain->getPkId();
        $ccm_status_history = new CcmStatusHistory();
        $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
        $cold_chian_id = $this->_em->getRepository('ColdChain')->find($cold_chain_id);
        $ccm_status_history->setCcm($cold_chian_id);
        $asset1_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::VACCINECARRIER);
        $ccm_status_history->setCcmAssetType($asset1_id);
        $ccm_status_history->setWorkingQuantity($form_values['quantity']);
        $ccm_status_history->setCreatedBy($created_by);
        $ccm_status_history->setCreatedDate(App_Tools_Time::now());
        $ccm_status_history->setModifiedBy($created_by);
        $ccm_status_history->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_status_history);
        $this->_em->flush();
        $cold_chain_model = new Model_ColdChain();
        $ccm_history_id = $ccm_status_history->getPkId();
        $cold_chain_model->updateCcmStatusHistory($cold_chain_id, $ccm_history_id);
        if (!empty($form_values['warehouse'])) {
            $ccm_history = new CcmHistory();
            $w_id = $this->form_values['warehouse'];
            $warehouse_id = $this->_em->getRepository('Warehouses')->find($w_id);
            $ccm_history->setWarehouse($warehouse_id);
            $ccm_id = $this->_em->getRepository('ColdChain')->find($cold_chain_id);
            $ccm_history->setCcm($ccm_id);
            $action_id = $this->_em->getRepository('ListDetail')->find('10');
            $ccm_history->setAction($action_id);
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            $ccm_history->setCreatedBy($created_by);
            $ccm_history->setCreatedDate(new \DateTime(date("Y-m-d")));
            $ccm_history->setModifiedBy($created_by);
            $ccm_history->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($ccm_history);
            $this->_em->flush();
        }
    }

    /**
     * Get Vaccine Carriers
     * @return type
     */
    public function getVaccineCarriers() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("c.assetDimensionLength,c.assetDimensionWidth," . "c.assetDimensionHeight,c.catalogueId,cc.quantity")
                ->from("ColdChain", "cc")
                ->join("cc.ccmModel", "c")
                ->join("c.ccmAssetType", "at")
                ->where("at.pkId =" . Model_CcmAssetTypes::VACCINECARRIER)
                ->andWhere("cc.createdBy =" . $this->_user_id);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Add Ice Pack
     */
    public function addIcePack() {
        $ccm_model_id = $this->form_values['ccm_model_id'];
        foreach ($ccm_model_id as $index => $model_id) {
            $form_values = $this->form_values;
            $quantity = $this->form_values['quantity'];
            $cold_chain = new ColdChain();
            $cold_chain->setQuantity($quantity[$index]);
            $asset_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::ICEPACKS);
            $cold_chain->setCcmAssetType($asset_id);
            $m_id = $this->_em->getRepository('CcmModels')->find($model_id);
            $cold_chain->setCcmModel($m_id);
            $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
            if (!empty($this->form_values['warehouse'])) {
                $w_id = $this->form_values['warehouse'];
                $warehouse = $this->_em->getRepository('Warehouses')->find($w_id);
                $cold_chain->setWarehouse($warehouse);
            }
            $cold_chain->setCreatedBy($created_by);
            $cold_chain->setCreatedDate(new \DateTime(date("Y-m-d")));
            $cold_chain->setModifiedBy($created_by);
            $cold_chain->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($cold_chain);
            $this->_em->flush();
            $cold_chain_id = $cold_chain->getPkId();
            $ccm_status_history = new CcmStatusHistory();
            $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
            $cold_chian_id = $this->_em->getRepository('ColdChain')->find($cold_chain_id);
            $ccm_status_history->setCcm($cold_chian_id);
            $asset1_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::ICEPACKS);
            $ccm_status_history->setCcmAssetType($asset1_id);
            $ccm_status_history->setWorkingQuantity($quantity[$index]);
            $ccm_status_history->setCreatedBy($created_by);
            $ccm_status_history->setCreatedDate(App_Tools_Time::now());
            $ccm_status_history->setModifiedBy($created_by);
            $ccm_status_history->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($ccm_status_history);
            $this->_em->flush();
            $cold_chain_model = new Model_ColdChain();
            $ccm_history_id = $ccm_status_history->getPkId();
            $cold_chain_model->updateCcmStatusHistory($cold_chain_id, $ccm_history_id);
        }
    }

    /**
     * Get Voltage Regulators
     * @return type
     */
    public function getVoltageRegulators() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('cmo.catalogueId,cma.ccmMakeName,cmo.ccmModelName,cc.quantity')
                ->from("ColdChain", "cc")
                ->join("cc.ccmModel", "cmo")
                ->join("cmo.ccmMake", "cma")
                ->where("cc.ccmAssetType = " . Model_CcmAssetTypes::VOLTAGEREGULATOR . " ");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Assets By Type
     * @return type
     */
    public function getAllAssetsByType() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('cmo.pkId, cmo.catalogueId,cma.ccmMakeName,cmo.ccmModelName')
                ->from("CcmModels", "cmo")
                ->join("cmo.ccmMake", "cma");
        if ($this->form_values['asset_type'] <= 7 && $this->form_values['asset_type'] <> 2) {
            $ccm_asset_types = new Model_CcmAssetTypes();
            $ccm_asset_types->form_values['parent_id'] = $this->form_values['asset_type'];
            $arr_result = $ccm_asset_types->getAssetSubTypes();
            $str_asset_ids = "";
            if ($arr_result && count($arr_result) > 0) {
                foreach ($arr_result as $id) {
                    $str_asset_ids .= $id['pkId'] . ",";
                }
                $str_asset_ids = rtrim($str_asset_ids, ",");
            } else {
                $str_asset_ids = $this->form_values['asset_type'];
            }
            $str_sql->where("cmo.ccmAssetType IN ( " . $str_asset_ids . ")");
        } else {
            $str_sql->where("cmo.ccmAssetType = " . $this->form_values['asset_type']);
        }

        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Asset Details By Id
     * @return type
     */
    public function getAssetDetailsById() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('cmo.pkId, cma.ccmMakeName, cmo.ccmModelName, cmo.cfcFree, cmo.netCapacity4, cmo.netCapacity20,
                        cmo.grossCapacity4, cmo.grossCapacity20, cmo.assetDimensionHeight,cmo.assetDimensionWidth, cmo.assetDimensionLength,
                        ca.pkId AS ccm_asset_type, cmo.pkId AS ccm_model, cma.pkId AS ccm_make')
                ->from("CcmModels", "cmo")
                ->join("cmo.ccmMake", "cma")
                ->join("cmo.ccmAssetType", "ca")
                ->where("cmo.pkId = '" . $this->form_values['catalogue_id'] . "'")
                ->setMaxResults(1);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get Models By Search
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getModelsBySearch($order = null, $sort = null) {
        $where = array();
        $form_values = $this->form_values;
        if (!empty($form_values['ccm_model_name'])) {
            $where[] = "c.ccmModelName LIKE '%" . $form_values['ccm_model_name'] . "%'";
        }
        if (!empty($form_values['catalogue_id'])) {
            $where[] = "c.catalogueId LIKE '%" . $form_values['catalogue_id'] . "%'";
        }
        if (!empty($form_values['status']) && $form_values['status'] != 'all') {
            $where[] = "c.status = '" . $form_values['status'] . "'";
        }
        if (!empty($form_values['ccm_asset_type_id'])) {
            $where[] = "cat.pkId = '" . $form_values['ccm_asset_type_id'] . "'";
        }
        if (!empty($form_values['ccm_make_id'])) {
            $where[] = "cmake.pkId = '" . $form_values['ccm_make_id'] . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('c.pkId', 'c.ccmModelName, cmake.ccmMakeName, cat.assetTypeName, c.catalogueId, cu.userName, c.status')
                ->from("CcmModels", "c")
                ->join('c.ccmMake', 'cmake')
                ->join('c.createdBy', 'cu')
                ->join('c.ccmAssetType', 'cat');
        if (!empty($where_s)) {
            $str_sql->where($where_s);
        }
        if ($order == 'model_name') {
            $str_sql->orderBy("c.ccmModelName", $sort);
        }
        if ($order == 'make_id') {
            $str_sql->orderBy("cmake.pkId", $sort);
        }
        if ($order == 'asset_type') {
            $str_sql->orderBy("cat.pkId", $sort);
        }
        if ($order == 'catalogue_id') {
            $str_sql->orderBy("cat.catalogueId", $sort);
        }
        if ($order == 'created_by') {
            $str_sql->orderBy("c.createdBy", $sort);
        }
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Refrigerator Models By Working Status Report
     * @return type
     */
    public function refrigeratorModelsByWorkingStatusReport() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1']) == 2) {
            $where[] = "Province.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "District.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = "SELECT
                    ccm_models.ccm_model_name, Count(cold_chain.ccm_model_id) AS Total,
                    Sum(IF(ccm_status_history.ccm_status_list_id = 1,1,0)) AS Working,
                    ROUND((SUM(IF(ccm_status_history.ccm_status_list_id = 1,1,0)) / COUNT(cold_chain.warehouse_id)) * 100,1) AS WorkingPer,
                    Sum(IF(ccm_status_history.ccm_status_list_id = 2,1,0)) AS NeedsService,
                    ROUND((SUM(IF(ccm_status_history.ccm_status_list_id = 2,1,0)) / COUNT(cold_chain.warehouse_id)) * 100,1) AS NeedsServicePer,
                    Sum(IF(ccm_status_history.ccm_status_list_id = 3,1,0)) AS NotWorking,
                    ROUND((SUM(IF(ccm_status_history.ccm_status_list_id = 3,1,0)) / COUNT(cold_chain.warehouse_id)) * 100,1) AS NotWorkingPer,
                    warehouse_types.warehouse_type_name, District.location_name, Province.location_name
                    FROM
                     ccm_models
                    INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                    INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                    INNER JOIN locations AS Province ON District.province_id = Province.pk_id
                    INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                    WHERE
                    (Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . " OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . ")
                     and warehouses.status = 1 " . $str_where .
                " GROUP BY ccm_models.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Refrigerator Models By Working Status Graph
     * @return type
     */
    public function refrigeratorModelsByWorkingStatusGraph() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "Province.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "District.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = "SELECT Model, 
                        sum(b.Working) as Working,
                        sum(b.NeedsService) as NeedsService,
                        sum(b.NotWorking) as NotWorking
                        from ( SELECT
                                ccm_models.ccm_model_name AS Model,
                                Sum(IF(ccm_status_history.ccm_status_list_id = 1,1,0)) AS Working,
                                Sum(IF(ccm_status_history.ccm_status_list_id = 2,1,0)) AS NeedsService,
                                Sum(IF(ccm_status_history.ccm_status_list_id = 3,1,0)) AS NotWorking,
                                warehouse_types.warehouse_type_name
                            FROM ccm_models
                            INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
                            INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                            INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                            INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                            INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                            INNER JOIN locations AS Province ON District.province_id = Province.pk_id
                            INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                            WHERE
                            ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                                OR Asset_Type.parent_id =  " . Model_CcmAssetTypes::REFRIGERATOR . " )
                            AND warehouses.status = 1 " . $str_where . "
                        GROUP BY ccm_models.pk_id
                   ) b group by Model ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Refrigerator Models By Age Group Report
     * @return type
     */
    public function getRefrigeratorModelsByAgeGroupReport() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouses.warehouse_type_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = "SELECT
                        ccm_models.ccm_model_name,
                        COUNT(ccm_models.pk_id) AS total,
                        SUM(IF(ADDDATE(CURDATE(), INTERVAL -2 YEAR) < DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0))AS `0-2 Years`,
                        ROUND(SUM(IF(ADDDATE(CURDATE(), INTERVAL -2 YEAR) < DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) / COUNT(ccm_models.pk_id) * 100, 2) AS `0-2 Years Per`,
                        SUM(IF(ADDDATE(CURDATE(), INTERVAL -2 YEAR) > DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND ADDDATE(CURDATE(), INTERVAL -5 YEAR) < DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) AS `3-5 Years`,
                        ROUND(SUM(IF(ADDDATE(CURDATE(), INTERVAL -2 YEAR) > DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND ADDDATE(CURDATE(), INTERVAL -5 YEAR) < DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) / COUNT(ccm_models.pk_id) * 100, 2) AS `3-5 Years Per`,
                        SUM(IF(ADDDATE(CURDATE(), INTERVAL -5 YEAR) > DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND ADDDATE(CURDATE(), INTERVAL -10 YEAR) < DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) AS `6-10 Years`,
                        ROUND(SUM(IF(ADDDATE(CURDATE(), INTERVAL -5 YEAR) > DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND ADDDATE(CURDATE(), INTERVAL -10 YEAR) < DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) / COUNT(ccm_models.pk_id) * 100, 2) AS `6-10 Years Per`,
                        SUM(IF(ADDDATE(CURDATE(), INTERVAL -10 YEAR) > DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) AS `>10 Years`,
                        ROUND(SUM(IF(ADDDATE(CURDATE(), INTERVAL -10 YEAR) > DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') AND DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') != '0000-00-00', 1, 0)) / COUNT(ccm_models.pk_id) * 100, 2) AS `>10 Years Per`,
                        SUM(IF(DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') = '0000-00-00' || cold_chain.working_since IS NULL, 1, 0)) AS `Unknown`,
                        ROUND(SUM(IF(DATE_FORMAT(cold_chain.working_since, '%Y-%m-%d') = '0000-00-00' || cold_chain.working_since IS NULL, 1, 0)) / COUNT(ccm_models.pk_id) * 100, 2) AS `Unknown Per`
                    FROM
                        ccm_models
                    INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                    WHERE ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . " OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . " )
                    AND warehouses.status = 1 " . $str_where . "
                     GROUP BY ccm_models.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Refrigerator Freezers Utilization Report
     * @return type
     */
    public function refrigeratorFreezersUtilizationReport() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = "
                SELECT ccm_models.ccm_model_name,
                COUNT(cold_chain.warehouse_id) AS Total,
                Sum(IF(ccm_status_history.ccm_status_list_id=8, 1, 0)) AS inUse,
                ROUND((SUM(IF(ccm_status_history.ccm_status_list_id=8, 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS inUsePer,
                Sum(IF(ccm_status_history.ccm_status_list_id=14, 1, 0)) AS inStore,
                ROUND((SUM(IF(ccm_status_history.ccm_status_list_id=14, 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS inStorePer,
                Sum(IF(ccm_status_history.ccm_status_list_id IN(9, 10), 1, 0)) AS notUsed,
                ROUND((SUM(IF(ccm_status_history.ccm_status_list_id IN(9, 10), 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS notUsedPer,
                Sum(IF(ccm_status_history.ccm_status_list_id=19, 1, 0)) AS unknown,
                ROUND((SUM(IF(ccm_status_history.ccm_status_list_id=19, 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS unknownPer,
                   warehouse_types.warehouse_type_name
                FROM
                   ccm_models
                    INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                    INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                    INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                WHERE
                    ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . " OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . " )
                    AND warehouses.status = 1  " . $str_where . "
                GROUP BY ccm_models.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Refrigerator Freezers Utilization Graph
     * @return type
     */
    public function refrigeratorFreezersUtilizationGraph() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = "
            SELECT ccm_model_name, 
           sum(b.inUse) as inUse,
           sum(b.inStore) as inStore,
           sum(b.notUsed) as notUsed,
           sum(b.unknown) as unknown
           from ( SELECT
                    ccm_models.ccm_model_name,
                    Sum(IF(ccm_status_history.utilization_id=8, 1, 0)) AS inUse,
                    Sum(IF(ccm_status_history.utilization_id=14, 1, 0)) AS inStore,
                    Sum(IF(ccm_status_history.utilization_id IN(9, 10), 1, 0)) AS notUsed,
                    Sum(IF(ccm_status_history.utilization_id=19, 1, 0)) AS unknown,
                        warehouse_types.warehouse_type_name
                FROM ccm_models
                    INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                    INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                WHERE
                    ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                   OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . " )
                    and warehouses.status = 1 " . $str_where . "
                GROUP BY ccm_models.pk_id
             ) b group by ccm_model_name ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Distribution CFC Free Equipment By FType
     */
    public function distributionCFCFreeEquipmentByFType() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                    warehouse_types.warehouse_type_name, COUNT(cold_chain.ccm_model_id) AS Total,
                    SUM(IF(ccm_models.cfc_free=1, 1, 0)) AS CFCFree,
                    ROUND((SUM(IF(ccm_models.cfc_free=1, 1, 0))/COUNT(cold_chain.ccm_model_id)) * 100, 2) AS CFCFreePer,
                    Sum(IF(ccm_models.cfc_free=0, 1, 0)) AS notCFCFree,
                    ROUND((SUM(IF(ccm_models.cfc_free=0, 1, 0))/COUNT(cold_chain.ccm_model_id)) * 100, 2) AS NeedsServicePer,
                    Sum(IF(ccm_models.cfc_free=3 || ccm_models.cfc_free IS NULL, 1, 0)) AS unknown,
                    ROUND((SUM(IF(ccm_models.cfc_free=3 || ccm_models.cfc_free IS NULL, 1, 0))/COUNT(cold_chain.ccm_model_id)) * 100, 2) AS unknownPer
                FROM warehouses
                    INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                    INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                WHERE
                    ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                        OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . " )
                AND warehouses.status = 1 " . $str_where . "
                GROUP BY warehouse_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Add New Make Model
     */
    public function addNewMakeModel() {
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $ccm_make = new CcmMakes();
        $ccm_make->setCcmMakeName($this->form_values['ccm_make_popup']);
        $ccm_make->setCreatedBy($created_by);
        $ccm_make->setModifiedBy($created_by);
        $ccm_make->setCreatedDate(App_Tools_Time::now());
        $ccm_make->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_make);
        $this->_em->flush();
        $ccm_make_i = $ccm_make->getPkId();
        $ccm_model = new CcmModels();
        $ccm_voltage_regulator = new CcmVoltageRegulators();
        $ccm_make_id = $this->_em->getRepository('CcmMakes')->find($ccm_make_i);
        $ccm_model->setCcmMake($ccm_make_id);
        $ccm_model->setCcmModelName($this->form_values['ccm_model_popup']);
        $ccm_model->setCatalogueId($this->form_values['catalogue_id_popup']);
        if (!empty($this->form_values['asset_dimension_height_popup'])) {
            $ccm_model->setAssetDimensionHeight($this->form_values['asset_dimension_height_popup']);
        }
        if (!empty($this->form_values['asset_dimension_length_popup'])) {
            $ccm_model->setAssetDimensionLength($this->form_values['asset_dimension_length_popup']);
        }
        if (!empty($this->form_values['asset_dimension_width_popup'])) {
            $ccm_model->setAssetDimensionWidth($this->form_values['asset_dimension_width_popup']);
        }
        if (!empty($this->form_values['internal_dimension_width_popup'])) {
            $ccm_model->setInternalDimensionWidth($this->form_values['internal_dimension_width_popup']);
        }
        if (!empty($this->form_values['internal_dimension_width_popup'])) {
            $ccm_model->setInternalDimensionWidth($this->form_values['internal_dimension_width_popup']);
        }
        if (!empty($this->form_values['internal_dimension_width_popup'])) {
            $ccm_model->setInternalDimensionWidth($this->form_values['internal_dimension_width_popup']);
        }
        if (!empty($this->form_values['storage_dimension_width_popup'])) {
            $ccm_model->setStorageDimensionWidth($this->form_values['storage_dimension_width_popup']);
        }
        if (!empty($this->form_values['storage_dimension_width_popup'])) {
            $ccm_model->setStorageDimensionWidth($this->form_values['storage_dimension_width_popup']);
        }
        if (!empty($this->form_values['storage_dimension_width_popup'])) {
            $ccm_model->setStorageDimensionWidth($this->form_values['storage_dimension_width_popup']);
        }
        if (!empty($this->form_values['net_capacity_4'])) {
            $ccm_model->setNetCapacity4($this->form_values['net_capacity_4']);
        }
        if (!empty($this->form_values['cold_life'])) {
            $ccm_model->setColdLife($this->form_values['cold_life']);
        }
        if (!empty($this->form_values['ccm_asset_type_id_popup'])) {
            $asset_id_m = $this->_em->getRepository('CcmAssetTypes')->find($this->form_values['ccm_asset_type_id_popup']);
            $ccm_model->setCcmAssetType($asset_id_m);
        }
        if (!empty($this->form_values['cfc_free'])) {
            $ccm_model->setCfcFree($this->form_values['cfc_free']);
        }
        if (!empty($this->form_values['is_pis_pqs'])) {
            $ccm_model->setIsPqs($this->form_values['is_pis_pqs']);
        }
        if (!empty($this->form_values['no_of_phases'])) {
            $ccm_model->setNoOfPhases($this->form_values['no_of_phases']);
        }
        if (!empty($this->form_values['refrigerator_gas_type'])) {
            $refrigerator_gas_type = $this->_em->getRepository('ListDetail')->find($this->form_values['refrigerator_gas_type']);
            $ccm_model->setGasType($refrigerator_gas_type);
        }
        if (!empty($this->form_values['product_price'])) {
            $ccm_model->setProductPrice($this->form_values['product_price']);
        }
       
        if (!empty($this->form_values['power_source'])) {
            $power_source = $this->_em->getRepository('ListDetail')->find($this->form_values['power_source']);
            $ccm_model->setPowerSource($power_source);
        }
        if (!empty($this->form_values['gross_capacity_4_popup'])) {
            $ccm_model->setGrossCapacity20($this->form_values['gross_capacity_4_popup']);
        }
        if (!empty($this->form_values['gross_capacity_4_popup'])) {
            $ccm_model->setGrossCapacity4($this->form_values['gross_capacity_4_popup']);
        }
        if (!empty($this->form_values['net_capacity_20_popup'])) {
            $ccm_model->setNetCapacity20($this->form_values['net_capacity_20_popup']);
        }
        if (!empty($this->form_values['net_capacity_4_popup'])) {
            $ccm_model->setNetCapacity4($this->form_values['net_capacity_4_popup']);
        }
        if (!empty($this->form_values['nominal_voltage'])) {
            $ccm_voltage_regulator->setNominalVoltage($this->form_values['nominal_voltage']);
        }
        if (!empty($this->form_values['continous_power'])) {
            $ccm_voltage_regulator->setContinousPower($this->form_values['continous_power']);
        }
        if (!empty($this->form_values['frequency'])) {
            $ccm_voltage_regulator->setFrequency($this->form_values['frequency']);
        }
        if (!empty($this->form_values['input_voltage_range'])) {
            $ccm_voltage_regulator->setInputVoltageRange($this->form_values['input_voltage_range']);
        }
        if (!empty($this->form_values['output_voltage_range'])) {
            $ccm_voltage_regulator->setOutputVoltageRange($this->form_values['output_voltage_range']);
        }
        
        $ccm_model->setStatus(1);
        $ccm_model->setCreatedBy($created_by);
        $ccm_model->setModifiedBy($created_by);
        $ccm_model->setCreatedDate(App_Tools_Time::now());
        $ccm_model->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_model);
        $this->_em->flush();
    }

    /**
     * Cold Rooms 4 to 20 By Model And Working Status
     */
    public function coldRooms4to20ByModelAndWorkingStatus() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "Province.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "locations.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                    Province.location_name AS Province,
                    locations.location_name AS District,
                        warehouse_types.warehouse_type_name AS FacilityType,
                        ccm_models.ccm_model_name AS Model,
                        ccm_makes.ccm_make_name AS Manufacturer,
                        ccm_asset_types.asset_type_name AS EquipmentType,
                        COUNT(ccm_asset_types.asset_type_name) AS Total,
                        SUM(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) AS Working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS WorkingPer,
                        SUM(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) AS NeedsService,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS NeedsServicePer,
                        SUM(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) AS NotWorking,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS NotWorkingPer
                    FROM warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
                    WHERE
                      (ccm_asset_types.pk_id = " . Model_CcmAssetTypes::COLDROOM . " OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::COLDROOM . ")
                      and warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id, ccm_models.pk_id, ccm_makes.pk_id, ccm_asset_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Listing Of Cold Room Facilities And Working Status
     */
    public function listingOfColdRoomFacilitiesAndWorkingStatus() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                        warehouse_types.warehouse_type_name,
                        ccm_models.ccm_model_name,
                        ccm_makes.ccm_make_name,
                        ccm_asset_types.asset_type_name,
                        Count(ccm_asset_types.asset_type_name) AS total,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) AS working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS working_per,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) AS needs_service,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS needs_service_per,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) AS not_working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS not_working_per,
                        cold_chain.serial_number,'No' AS has_generator
                    FROM warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                    WHERE
                            (ccm_asset_types.pk_id = " . Model_CcmAssetTypes::COLDROOM . " OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::COLDROOM . ")
                            and warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id, ccm_models.pk_id, ccm_makes.pk_id, ccm_asset_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Coldbox And Vaccine Carriers By Working Status Report
     */
    public function coldboxAndVaccineCarriersByWorkingStatusReport() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                     warehouse_types.warehouse_type_name,
                     ccm_models.ccm_model_name,
                     ccm_makes.ccm_make_name,
                     ccm_asset_types.asset_type_name,
                     SUM(cold_chain.quantity) AS workingQuantity,
                     (SELECT ccm_history.quantity
                      FROM ccm_history
                      WHERE ccm_history.warehouse_id = warehouses.pk_id
                      ORDER BY ccm_history.created_date DESC
                      LIMIT 1
                     ) AS notWorkingQuantity                     
                    FROM warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                    WHERE
                        ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::VACCINECARRIER . " )
                        and warehouses.status = 1  " . $str_where . "
                    GROUP BY warehouse_types.pk_id, ccm_models.pk_id, ccm_makes.pk_id, ccm_asset_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Coldbox And Vaccine Carriers By Working Status Graph
     */
    public function coldboxAndVaccineCarriersByWorkingStatusGraph() {
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
        $str_qry = "SELECT
                        b.FacilityType, b.pk_id,
                        sum(b.workingQuantity) AS workingQuantity,
                        sum(b.notWorkingQuantity) AS notWorkingQuantity
                    FROM
                        ( SELECT warehouse_types.pk_id,
                                warehouse_types.warehouse_type_name AS FacilityType, ccm_models.ccm_model_name,
                                ccm_makes.ccm_make_name, ccm_asset_types.asset_type_name,
                                SUM(cold_chain.quantity) AS workingQuantity,
                                ( SELECT ccm_history.quantity
                                    FROM ccm_history
                                    WHERE ccm_history.warehouse_id = warehouses.pk_id
                                    ORDER BY ccm_history.created_date DESC
                                    LIMIT 1
                                ) AS notWorkingQuantity
                        FROM warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        WHERE
                        ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::VACCINECARRIER . " )
                        AND warehouses. STATUS = 1 " . $str_where . "
                        GROUP BY warehouse_types.pk_id, ccm_models.pk_id, ccm_makes.pk_id, ccm_asset_types.pk_id
                        ) b
                    GROUP BY FacilityType
                    ORDER BY b.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Quantity Of Cold Boxes Carriers Report
     */
    public function quantityOfColdBoxesCarriersReport() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                        warehouse_types.warehouse_type_name, MIN(cold_chain.quantity) AS min,
                        MAX(cold_chain.quantity) AS max, ROUND(AVG(cold_chain.quantity)) AS avg,
                        cold_chain.ccm_asset_type_id
                    FROM warehouses
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON warehouses.pk_id = cold_chain.warehouse_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                    WHERE
                        ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::VACCINECARRIER . " )
                        AND warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Quantity Of Cold Boxes Carriers Graph
     */
    public function quantityOfColdBoxesCarriersGraph() {
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
        $str_qry = " SELECT warehouse_types.warehouse_type_name, 
                        MIN(cold_chain.quantity) AS min, MAX(cold_chain.quantity) AS max, 
                        ROUND(AVG(cold_chain.quantity)) AS avg, cold_chain.ccm_asset_type_id
                    FROM warehouses
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON warehouses.pk_id = cold_chain.warehouse_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                    WHERE
                        ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::VACCINECARRIER . " )
                        and warehouses.status = 1 " . $str_where . "  
                            GROUP BY warehouse_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Standby Generators By Facility Type And Working Status Report
     */
    public function standbyGeneratorsByFacilityTypeAndWorkingStatusReport() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT Province.location_name AS Province,
                    locations.location_name AS District, warehouse_types.warehouse_type_name,
                        ccm_models.ccm_model_name, ccm_makes.ccm_make_name,
                        COUNT(cold_chain.ccm_asset_type_id) as Total,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) AS working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) / COUNT(cold_chain.ccm_asset_type_id) * 100, 2) AS working_per,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) AS needs_service,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) / COUNT(cold_chain.ccm_asset_type_id) * 100, 2) AS needs_service_per,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) AS not_working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) / COUNT(cold_chain.ccm_asset_type_id) * 100, 2) AS not_working_per
                    FROM
                        warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
                    WHERE
                        ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::GENERATOR . "
                            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::GENERATOR . " )
                    AND warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Standby Generators By Facility Type And Working Status Graph
     */
    public function standbyGeneratorsByFacilityTypeAndWorkingStatusGraph() {
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
        $str_qry = " SELECT  warehouse_type_name,
                     sum(b.working) as working, sum(b.needs_service) as needs_service,
           sum(b.not_working) as not_working
           from (  SELECT
                        warehouse_types.pk_id,
                        warehouse_types.warehouse_type_name,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) AS working,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) AS needs_service,
                        Sum(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) AS not_working
                    FROM
                        warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
                    WHERE
                        (ccm_asset_types.pk_id = " . Model_CcmAssetTypes::GENERATOR . "
                            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::GENERATOR . ")
                        and warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id
               ) b group by warehouse_type_name ORDER BY b.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Voltage Stabilizers And Regulators Working Status
     */
    public function voltageStabilizersAndRegulatorsWorkingStatus() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT Province.location_name AS Province,
                        locations.location_name AS District, warehouse_types.warehouse_type_name,
                        ccm_models.ccm_model_name, ccm_makes.ccm_make_name,
                        ccm_asset_types.asset_type_name, COUNT(ccm_asset_types.asset_type_name) AS total,
                        SUM(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) AS working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 1, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS working_per,
                        SUM(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) AS needs_service,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 2, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS needs_service_per,
                        SUM(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) AS not_working,
                        ROUND(SUM(IF(ccm_status_history.ccm_status_list_id = 3, 1, 0)) / COUNT(ccm_asset_types.asset_type_name) * 100, 2) AS not_working_per
                    FROM warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                    INNER JOIN locations ON warehouses.district_id = locations.pk_id
                    INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
                    WHERE
                       ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::VOLTAGEREGULATOR . " OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::VOLTAGEREGULATOR . " )
                    and warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id, ccm_models.pk_id, ccm_makes.pk_id, ccm_asset_types.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Line List Of Equipment With Working Status
     */
    public function lineListOfEquipmentWithWorkingStatus() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "Province.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "District.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                        warehouses.ccem_id AS FacilityCode,
                        Province.location_name AS Province,
                        District.location_name AS Distirct,                       
                        warehouses.warehouse_name AS FacilityName,
                        warehouse_types.warehouse_type_name AS FacilityType,
                        ccm_models.catalogue_id AS LibraryID,
                        ccm_models.ccm_model_name AS Model,
                        ccm_makes.ccm_make_name AS Make,
                        cold_chain.serial_number AS SerialNumber,
                        round(ccm_models.net_capacity_4,1) AS NetVol4,
                        round(ccm_models.net_capacity_20,1) AS NetVol20,
                        YEAR (cold_chain.working_since) AS WorkingSince,
                        ccm_status_list.ccm_status_list_name AS WorkingStatus,
                        Utilization.ccm_status_list_name AS Utilization
                    FROM
                        locations AS Province
                        INNER JOIN locations AS District ON Province.pk_id = District.province_id
                        INNER JOIN warehouses ON District.pk_id = warehouses.location_id
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                        LEFT JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
                        LEFT JOIN ccm_status_list AS Utilization ON ccm_status_history.utilization_id = Utilization.pk_id
                        INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id                                         
                        WHERE
                        ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                            OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . " )
                        and warehouses.status = 1 and ccm_status_list.pk_id > 1  " . $str_where .
                " ORDER BY Province.pk_id, District.location_name";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Line List Of Equipment With Equipment Utilization
     */
    public function lineListOfEquipmentWithEquipmentUtilization() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "Province.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "District.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = "SELECT
                        warehouses.ccem_id AS FacilityCode,
                        Province.location_name AS Province,
                        District.location_name AS Distirct,
                        warehouses.warehouse_name AS FacilityName,
                        warehouse_types.warehouse_type_name AS FacilityType,
                        ccm_models.catalogue_id AS LibraryID,
                        ccm_models.ccm_model_name AS Model,
                        ccm_makes.ccm_make_name AS Make,
                        cold_chain.serial_number AS SerialNumber,
                        round(ccm_models.net_capacity_4,1) AS NetVol4,
                        round(ccm_models.net_capacity_20,1) AS NetVol20,
                        YEAR (cold_chain.working_since) AS WorkingSince,
                        ccm_status_list.ccm_status_list_name AS WorkingStatus
                    FROM
                        locations AS Province
                        INNER JOIN locations AS District ON Province.pk_id = District.province_id
                        INNER JOIN warehouses ON District.pk_id = warehouses.district_id
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                        LEFT JOIN ccm_status_list ON ccm_status_history.utilization_id = ccm_status_list.pk_id
                        INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id                  
                        WHERE
                       ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                       OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                        )
                       and warehouses.status = 1
                       and ccm_status_list.pk_id in (9,10,14)
                    " . $str_where . " ORDER BY Province.pk_id, District.location_name, FacilityName ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Icepack Freezing Capacity Against Routine And SIA
     */
    public function icepackFreezingCapacityAgainstRoutineAndSIA() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                        Province.location_name AS Province,
                        District.location_name AS Distirct,
                        Tehsil.location_name AS Tehsil,
                        UC.location_name AS UC,
                        warehouses.warehouse_name AS FacilityName,
                        warehouse_types.warehouse_type_name AS FacilityType,
                        warehouses.ccem_id AS FacilityCode,
                        warehouse_population.requirments_4degree AS Required,
                        warehouse_population.capacity_4degree AS Capacity,
                        warehouse_population.capacity_4degree - warehouse_population.requirments_4degree AS Balance
                   FROM
                        locations AS Province
                        INNER JOIN locations AS District ON Province.pk_id = District.province_id
                        INNER JOIN locations AS Tehsil ON District.pk_id = Tehsil.district_id
                        INNER JOIN locations AS UC ON Tehsil.pk_id = UC.parent_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN warehouse_population ON warehouses.pk_id = warehouse_population.warehouse_id
                   WHERE
                        cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                        and warehouses.status = 1 " . $str_where . "
                   GROUP BY warehouses.pk_id ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Coldbox And Vaccine Carrier Capacity By Facility
     */
    public function coldboxAndVaccineCarrierCapacityByFacility() {
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['facility_type'])) {
            $where[] = "warehouse_types.pk_id = " . $this->form_values['facility_type'];
        }
        if (!empty($this->form_values['combo1'])) {
            $where[] = "Province.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "District.district_id = " . $this->form_values['combo2'];
        }
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $str_qry = " SELECT
                        Province.location_name AS Province,
                        District.location_name AS Distirct,
                        warehouses.warehouse_name AS FacilityName,
                        warehouse_types.warehouse_type_name AS FacilityType,
                        SUM(ccm_status_history.working_quantity) AS QuantityPresent,
                        round(SUM(ccm_models.net_capacity_4),1) AS NetStorage ,
                        (SELECT
                            ccm_history.quantity
                         FROM
                            ccm_history
                         WHERE
                            ccm_history.ccm_id = cold_chain.pk_id
                         ORDER BY
                            ccm_history.pk_id DESC
                        LIMIT 1) AS QuantityNotWorking,
                        warehouses.ccem_id AS FacilityCode
                    FROM
                    locations AS Province
                    INNER JOIN locations AS District ON Province.pk_id = District.province_id
                    INNER JOIN warehouses ON District.pk_id = warehouses.district_id
                    INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                    INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                    INNER JOIN ccm_asset_types AS Asset_Type ON Asset_Type.pk_id = ccm_models.ccm_asset_type_id
                    INNER JOIN ccm_asset_types AS AssetMainType ON cold_chain.ccm_asset_type_id = AssetMainType.pk_id               
                    WHERE
                    ( AssetMainType.pk_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                        OR AssetMainType.parent_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                    )  and warehouses.status = 1 
                        " . $str_where . "
                    GROUP BY warehouses.pk_id
                    ORDER BY Province.pk_id, District.location_name ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Stabilizers By Working Status Graph
     */
    public function stabilizersByWorkingStatusGraph() {
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
        $str_qry = "
            SELECT FacilityType, 
           sum(b.workingQuantity) as workingQuantity,
           sum(b.notWorkingQuantity) as notWorkingQuantity
           from (
           SELECT
                     warehouse_types.warehouse_type_name AS FacilityType,
                     ccm_models.ccm_model_name,
                     ccm_makes.ccm_make_name,
                     ccm_asset_types.asset_type_name,
                     SUM(cold_chain.quantity) AS workingQuantity,
                     (SELECT
                       ccm_history.quantity
                      FROM
                       ccm_history
                      WHERE
                       ccm_history.warehouse_id = warehouses.pk_id
                      ORDER BY
                       ccm_history.created_date DESC
                      LIMIT 1
                     ) AS notWorkingQuantity                     
                    FROM
                     warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                    WHERE
                        ccm_asset_types.pk_id = " . Model_CcmAssetTypes::VOLTAGEREGULATOR . "
                        and warehouses.status = 1 " . $str_where . "
                    GROUP BY warehouse_types.pk_id, ccm_models.pk_id, ccm_makes.pk_id, ccm_asset_types.pk_id
                 ) b group by FacilityType
                    ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Assets Capacity By Search
     * @return type
     */
    public function getAssetsCapacityBySearch() {
        $type = $this->form_values['ccm_asset_type_id'];
        $warehouse_id = $this->form_values['warehouse3'];
        $where = " AND ( ( cold_chain.ccm_asset_type_id = $type OR AssetMainType.pk_id = $type ) )";
        $str_sql = "SELECT DISTINCT
        cold_chain.pk_id as pkId,     
        cold_chain.asset_id,
        ccm_models.gross_capacity_20 + ccm_models.gross_capacity_4 AS gross,
        ccm_models.net_capacity_20 + ccm_models.net_capacity_4 AS net_usable,
        ROUND(
                SUM( ( placements.quantity * pack_info.volum_per_vial ) / 1000  )
        ) AS being_used, AssetMainType.asset_type_name, AssetSubtype.asset_type_name as asset_sub_type_name
        FROM
        cold_chain
        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
        LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
        INNER JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
        INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
        LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        WHERE                cold_chain.warehouse_id = $warehouse_id
        $where
        AND placement_locations.location_type = " . Model_PlacementLocations::LOCATIONTYPE_CCM . "
        GROUP BY cold_chain.auto_asset_id
        ORDER BY cold_chain.asset_id, cold_chain.ccm_asset_type_id DESC";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

}
