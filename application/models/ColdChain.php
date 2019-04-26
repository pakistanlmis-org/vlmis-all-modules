<?php

/**
 * Model_ColdChain
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Cold Chain
 */
class Model_ColdChain extends Model_Base {

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
        $this->_table = $this->_em->getRepository('ColdChain');
    }

    /**
     * Get Listing
     * @return type
     */
    public function getListing() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pk_id,cc.asset_id,cc.estimate_life,"
                        . "cc.working_since,cc.serial_number,cc.ccm_asset_type_id,"
                        . "cc.capacity,cc.ccm_status_list_id,cmod.ccm_model_name,"
                        . "cmk.ccm_make_name,csl.ccm_status_list_name,asst.pk_id as type_asset_id,"
                        . "asst.asset_type_name,cc.warehouse_id,tw.warehouse_name,cc.created_by,cc.created_date")
                ->from("Model_ColdChain cc")
                ->leftJoin("cc.CcmMakes cmk")
                ->leftJoin("cc.CcmModels cmod")
                ->leftJoin("cc.Warehouses tw")
                ->leftJoin("cc.CcmStatusList csl")
                ->leftJoin("cc.CcmAssetTypes asst")
                ->where("cc.warehouse_id=" . $wh_id)
                ->andWhere("tw.status=1");
        return $str_sql->fetchArray();
    }

    /**
     * Get Coldchain By Batch
     * @return type
     */
    public function getColdchainByBatch() {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
            ccm_makes.ccm_make_name,
            ccm_models.ccm_model_name,
            cold_chain.asset_id,
            cold_chain.pk_id
            FROM
            cold_chain
            INNER JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
            INNER JOIN placements ON placements.placement_location_id = placement_locations.pk_id
            INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
            INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
            WHERE
            cold_chain.warehouse_id = 1 AND
            placement_locations.location_type = 'ColdChain' AND
            placements.stock_batch_warehouse_id = '" . $this->form_values['batch'] . "'");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Asset By Location
     * @param type $location_id
     * @return string
     */
    public function getAssetByLocation($location_id) {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                    cold_chain.asset_id
            FROM
                    placement_locations
            INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id            
            WHERE  placement_locations.pk_id = $location_id");
        $str_sql->execute();
        $data = $str_sql->fetchAll();

        if (count($data) > 0) {
            return $data[0]['asset_id'];
        } else {
            return '';
        }
    }

    /**
     * Get Coldchain Models By Facility Type
     * @return type
     */
    public function getColdchainModelsByFacilityType() {
        if (!empty($this->form_values['facility_type'])) {
            $facilitytype = $this->form_values['facility_type'];
        } else {
            $facilitytype = 1;
        }
        $where = array();
        $str_where = "";
        if (!empty($this->form_values['combo1'])) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2'])) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $qry = "SELECT
                    ccm_models.ccm_model_name,
                    ccm_models.pk_id,
                    warehouses.warehouse_type_id,
                    COUNT(ccm_models.pk_id) AS TotalAssets
                FROM
                    cold_chain
                INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                INNER JOIN ccm_asset_types AS Asset_Type ON cold_chain.ccm_asset_type_id = Asset_Type.pk_id
                WHERE
                ( Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                    OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . " )
                AND warehouses. STATUS = 1
                GROUP BY
                    ccm_models.pk_id,
                    Asset_Type.pk_id LIMIT 500";

        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Coldchain By Asset Type
     * @return type
     */
    public function getColdchainByAssetType() {

        if (isset($this->form_values['wh_id']) && !empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        } else {
            $wh_id = $this->_identity->getWarehouseId();
        }

        if (!empty($this->form_values['type_id'])) {
            $type = $this->form_values['type_id'];
        } else {
            $type = "1,3";
        }
        $qry = 'SELECT DISTINCT
                CONCAT(cold_chain.asset_id," (",ccm_models.ccm_model_name,")") AS asset_name,
                cold_chain.pk_id, ccm_makes.ccm_make_name AS make_name, placement_locations.pk_id as plc_loc_id
                FROM
                cold_chain
                INNER JOIN placement_locations ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
                WHERE
                cold_chain.warehouse_id = ' . $wh_id . ' AND
                placement_locations.location_type = ' . Model_PlacementLocations::LOCATIONTYPE_CCM . ' AND
               (ccm_asset_types.parent_id IN (' . $type . ') OR  cold_chain.ccm_asset_type_id IN (' . $type . ')) AND ccm_status_history.ccm_status_list_id <> 3';
        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Coldchain By Asset Type
     * @return type
     */
    public function getColdchainByAssetShipments() {

        if (isset($this->form_values['wh_id']) && !empty($this->form_values['wh_id'])) {
            $wh_id = $this->form_values['wh_id'];
        } else {
            $wh_id = $this->_identity->getWarehouseId();
        }

        if (!empty($this->form_values['type_id'])) {
            $type = $this->form_values['type_id'];
        } else {
            $type = "1,3";
        }
        $qry = 'SELECT DISTINCT
               	cold_chain.pk_id,
                CONCAT(
                        cold_chain.asset_id,
                        " (",
                        ccm_models.ccm_model_name,
                        ")"
                ) AS asset_name
                FROM
                cold_chain
                INNER JOIN placement_locations ON placement_locations.location_id = cold_chain.pk_id
                INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
                LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
                WHERE
                cold_chain.warehouse_id = ' . $wh_id . ' AND
                placement_locations.location_type = ' . Model_PlacementLocations::LOCATIONTYPE_CCM . ' AND
               (ccm_asset_types.parent_id IN (' . $type . ') OR  cold_chain.ccm_asset_type_id IN (' . $type . ')) AND ccm_status_history.ccm_status_list_id <> 3';


        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        $result = json_encode($row->fetchAll());


        return $result;
    }

    /**
     * Get Cold Chain By Warehouse Type
     * @param type $warehouse_type
     * @return type
     */
    public function getColdChainByWarehouseType($warehouse_type) {
        $qry = "SELECT
                    ccm_models.pk_id,
                    COUNT(cold_chain.ccm_model_id) AS TotalAssets
                FROM
                    cold_chain
                INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                WHERE
                    warehouses.warehouse_type_id = " . $warehouse_type . "
                     and warehouses.status = 1
                AND ccm_models.ccm_asset_type_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                GROUP BY
                    cold_chain.ccm_model_id";
        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Location By Wh Id
     * @uses API
     * @param type $wh_id
     */
    public function getLocationByWhId($wh_id, $type = '') {
        $str_sql = "SELECT
        ccm_asset_types.asset_type_name,
        cold_chain.pk_id,
        cold_chain.asset_id,
        cold_chain.serial_number,
        ccm_models.ccm_asset_type_id,
        ROUND( ( Sum(placements.quantity) * pack_info.volum_per_vial ) / ( ( ccm_models.net_capacity_20 + ccm_models.net_capacity_4 ) * 1000 ) * 100, 3 ) AS filled_percent
        FROM
        ccm_asset_types
        INNER JOIN ccm_models ON ccm_models.ccm_asset_type_id = ccm_asset_types.pk_id
        INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
        LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
        LEFT JOIN placements ON placements.placement_location_id = placement_locations.pk_id
        LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.master_id
        LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE ";
        if (!empty($type)) {
            $str_sql .= "ccm_models.ccm_asset_type_id IN ($type) AND ";
        }
        $str_sql .= "cold_chain.warehouse_id = $wh_id
            GROUP BY cold_chain.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Search Batch By Batch No
     * @uses API
     * @param integer $wh_id
     * @param varchar $batch_no
     */
    public function searchBatchByBatchNo($wh_id, $batch_no) {
        $str_sql = "SELECT
            ccm_asset_types.asset_type_name,
            cold_chain.pk_id,
            cold_chain.asset_id,
            cold_chain.serial_number,
            ccm_models.ccm_asset_type_id,
            ROUND((Sum(placements.quantity) * pack_info.volum_per_vial) / ((ccm_models.net_capacity_20+ccm_models.net_capacity_4)*1000) * 100,3) AS filled_percent
            FROM
            ccm_asset_types
            INNER JOIN ccm_models ON ccm_models.ccm_asset_type_id = ccm_asset_types.pk_id
            INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
            LEFT JOIN placements ON cold_chain.pk_id = placements.ccm_id
            LEFT JOIN stock_batch_warehouses ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            LEFT JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.master_id
            LEFT JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            LEFT JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            LEFT JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            WHERE cold_chain.warehouse_id = $wh_id AND
            stock_batch.number = '$batch_no'
            GROUP BY cold_chain.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Search Item By Location Id
     * @uses API
     * @param integer $asset_id
     */
    public function searchItemByLocationId($asset_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ips.pkId as itemId, ips.itemName,
                    w.pkId as warehouseId,w.warehouseName")
                ->from('Placements', 'p')
                ->join('p.ccm', 'cc')
                ->join('p.stockBatchWarehouse', 'sbw')
                ->join('sbw.stockBatch', 'sb')
                ->join('sb.packInfo', 'pi')
                ->join('pi.stakeholderItemPackSize', 'sip')
                ->join('sip.itemPackSize', 'ips')
                ->join('cc.warehouse', 'w')
                ->where("cc.pkId = $asset_id");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse Names Asset Status
     * @return type
     */
    public function getWarehouseNamesAssetStatus() {
        $querypro = "SELECT
                            w.pk_id,
                            w.warehouse_name,
                            MAX(csh.status_date) as status_date
                    FROM
                            warehouses w
                    LEFT JOIN cold_chain c ON c.warehouse_id = w.pk_id
                    LEFT JOIN ccm_status_history csh ON c.ccm_status_history_id = csh.pk_id
                    INNER JOIN warehouse_users wu ON w.pk_id = wu.warehouse_id
                    WHERE
                            wu.user_id = $this->_user_id
                            and w.status = 1
                    GROUP BY w.warehouse_name  ";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Add Refrigerator
     */
    public function addRefrigerator() {
        $form_values = $this->form_values;
        $cold_chain = new ColdChain();
        $cold_chain->setAssetId($form_values['asset_id']);
        $source_id = $this->_em->getRepository('Stakeholders')->find($form_values['source_id']);
        $cold_chain->setSource($source_id);
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['catalogue_id']);
        $cold_chain->setCcmModel($model_id);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find($model_id->getCcmAssetType()->getPkId());
        $cold_chain->setCcmAssetType($asset_type);
        $modified_by = $this->_em->getRepository('Users')->find($this->_user_id);
        $cold_chain->setModifiedBy($modified_by);
        $cold_chain->setCreatedBy($modified_by);

        if (!empty($form_values['temperature_monitor'])) {
            $temperature_monitor = $this->_em->getRepository('ListDetail')->find($form_values['temperature_monitor']);
            $cold_chain->setTemperatureMonitor($temperature_monitor);
        }
        if (!empty($form_values['has_voltage'])) {
            $cold_chain->setHasVoltage($form_values['has_voltage']);
        }
        $cold_chain->setSerialNumber($form_values['serial_number']);
        $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['working_since'])));
        $auto_gen_id = App_Controller_Functions::generateCcemUniqueAssetId(Model_CcmAssetTypes::REFRIGERATOR);
        $cold_chain->setAutoAssetId($auto_gen_id);
        $cold_chain->setCreatedDate(App_Tools_Time::now());
        $cold_chain->setModifiedDate(App_Tools_Time::now());

        if (!empty($form_values['warehouse']) && $form_values['placed_at'] == 1) {
            $wh_id = $this->_em->getRepository('Warehouses')->find($form_values['warehouse']);
            $cold_chain->setWarehouse($wh_id);
        }

        $this->_em->persist($cold_chain);
        $this->_em->flush();


        $ccm_id = $cold_chain->getPkId();
        $ccm_status_history = new CcmStatusHistory();
        $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
        $cold_chian_id = $this->_em->getRepository('ColdChain')->find($ccm_id);
        $ccm_status_history->setCcm($cold_chian_id);
        $ccm_status_list_id = $this->_em->getRepository('CcmStatusList')->find($form_values['ccm_status_list_id']);
        $ccm_status_history->setCcmStatusList($ccm_status_list_id);
        $asset_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::REFRIGERATOR);

        $ccm_status_history->setCcmAssetType($asset_id);
        if (!empty($form_values['reason'])) {
            $reason = $this->_em->getRepository('CcmStatusList')->find($form_values['reason']);
            $ccm_status_history->setReason($reason);
        }
        if (!empty($form_values['utilization'])) {
            $utilization = $this->_em->getRepository('CcmStatusList')->find($form_values['utilization']);
            $ccm_status_history->setUtilization($utilization);
        }
        $created_by = $this->_em->find('Users', $this->_user_id);
        $ccm_status_history->setCreatedBy($created_by);
        $ccm_status_history->setCreatedDate(App_Tools_Time::now());
        $ccm_status_history->setModifiedBy($created_by);
        $ccm_status_history->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_status_history);
        $this->_em->flush();
        $ccm_history_id = $ccm_status_history->getPkId();
        $this->updateCcmStatusHistory($ccm_id, $ccm_history_id);
        $placements_locations = new PlacementLocations();
        $location_type = $this->_em->getRepository('ListDetail')->find(Model_PlacementLocations::LOCATIONTYPE_CCM);
        $placements_locations->setLocationType($location_type);
        $placements_locations->setLocationBarcode($auto_gen_id);
        $placements_locations->setLocationId($ccm_id);
        $placements_locations->setCreatedBy($created_by);
        $placements_locations->setCreatedDate(App_Tools_Time::now());
        $placements_locations->setModifiedBy($created_by);
        $placements_locations->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($placements_locations);
        $this->_em->flush();
        $ware_house = $form_values['warehouse'];
        if (!empty($ware_house)) {
            $date_time = date("Y/m/d");
            $qry = "SELECT REPUpdateCapacity($ware_house,'$date_time') from DUAL";

            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();
            $qry1 = "SELECT REPUpdateRequirement($ware_house,'$date_time') from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
    }

    /**
     * Update Refrigerator
     */
    public function updateRefrigerator() {

        $form_values = $this->form_values;

        //  echo $form_values['ccm_id'];
       // exit;
        $cold_chain = $this->_em->getRepository('ColdChain')->find($form_values['ccm_id']);
        $cold_chain->setAssetId($form_values['asset_id']);
        $source_id = $this->_em->getRepository('Stakeholders')->find($form_values['source_id']);
        $cold_chain->setSource($source_id);
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['catalogue_id']);
        $cold_chain->setCcmModel($model_id);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find($model_id->getCcmAssetType()->getPkId());
        $cold_chain->setCcmAssetType($asset_type);
        $cold_chain->setSerialNumber($form_values['serial_number']);
        $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($form_values['working_since'])));
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);

        $cold_chain->setHasVoltage($form_values['has_voltage']);

        $cold_chain->setModifiedBy($user_id);
        $cold_chain->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($cold_chain);
        $this->_em->flush();
        
        $ware_house = $form_values['warehouse'];
        if (!empty($ware_house)) {
            $date_time = date("Y/m/d");
            $qry = "SELECT REPUpdateCapacity($ware_house,$date_time) from DUAL";
            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();
            $qry1 = "SELECT REPUpdateRequirement($ware_house,$date_time) from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
    }

    /**
     * Add Voltage Regulator
     */
    public function addVoltageRegulator() {
        $form_values = $this->form_values;
        $cold_chain = new ColdChain();
        $model_id = $this->_em->getRepository('CcmModels')->find($form_values['catalogue_id']);
        $cold_chain->setCcmModel($model_id);
        $asset_type = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::VOLTAGEREGULATOR);
        $cold_chain->setCcmAssetType($asset_type);
        $cold_chain->setQuantity($form_values['quantity']);
        if (!empty($form_values['warehouse']) && $form_values['placed_at'] == 1) {
            $wh_id = $this->_em->getRepository('Warehouses')->find($form_values['warehouse']);
            $cold_chain->setWarehouse($wh_id);
        }
        $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
        $cold_chain->setCreatedBy($user_id);
        $cold_chain->setCreatedDate(App_Tools_Time::now());
        $cold_chain->setModifiedBy($user_id);
        $cold_chain->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($cold_chain);
        $this->_em->flush();
        $cold_chain_id = $cold_chain->getPkId();
        $ccm_status_history = new CcmStatusHistory();
        $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
        $cold_chian_id = $this->_em->getRepository('ColdChain')->find($cold_chain_id);
        $ccm_status_history->setCcm($cold_chian_id);
        $asset1_id = $this->_em->getRepository('CcmAssetTypes')->find(Model_CcmAssetTypes::VOLTAGEREGULATOR);
        $ccm_status_history->setCcmAssetType($asset1_id);
        $ccm_status_history->setWorkingQuantity($form_values['quantity']);
        $ccm_status_history->setCreatedBy($user_id);
        $ccm_status_history->setCreatedDate(App_Tools_Time::now());
        $ccm_status_history->setModifiedBy($user_id);
        $ccm_status_history->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($ccm_status_history);
        $this->_em->flush();
        $cold_chain_model = new Model_ColdChain();
        $ccm_history_id = $ccm_status_history->getPkId();
        $cold_chain_model->updateCcmStatusHistory($cold_chain_id, $ccm_history_id);
    }

    /**
     * Get Quantity Cold Chain Asset For Transfer
     * @return boolean
     */
    public function getQuantityColdChainAssetForTransfer() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("DISTINCT cc.pkId,cc.assetId,cc.autoAssetId,at.assetTypeName,cm.ccmModelName,at.pkId as assetTypeId,"
                        . "csh.workingQuantity as quantity ")
                ->from('CcmStatusHistory', 'csh')
                ->join('csh.ccm', 'cc')
                ->join('cc.ccmModel', 'cm')
                ->join('cc.ccmAssetType', 'at')
                ->Where("at.pkId IN (" . Model_CcmAssetTypes::VACCINECARRIER . "," . Model_CcmAssetTypes::ICEPACKS . "," . Model_CcmAssetTypes::VOLTAGEREGULATOR . ") OR at.parent IN (" . Model_CcmAssetTypes::VACCINECARRIER . "," . Model_CcmAssetTypes::ICEPACKS . "," . Model_CcmAssetTypes::VOLTAGEREGULATOR . ")")
                ->andWhere("cc.warehouse = " . $this->form_values['warehouse'])
                ->groupBy('cm.ccmModelName');

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Non Quantity Cold Chain Asset For Transfer
     * @return boolean
     */
    public function getNonQuantityColdChainAssetForTransfer() {
        $warehouse_id = $this->form_values['warehouse'];

        $str_qry = "SELECT DISTINCT
	c0_.pk_id AS pkId,
	c0_.asset_id AS assetId,
	c0_.auto_asset_id AS autoAssetId,
	c1_.asset_type_name AS assetTypeName,
	c2_.ccm_model_name AS ccmModelName,
	c1_.pk_id AS assetTypeId
        FROM
                cold_chain c0_
        INNER JOIN ccm_models c2_ ON c0_.ccm_model_id = c2_.pk_id
        INNER JOIN ccm_asset_types c1_ ON c0_.ccm_asset_type_id = c1_.pk_id
        WHERE
                c0_.warehouse_id = $warehouse_id
        AND (
                c1_.pk_id IN (1, 3, 6, 7)
                OR c1_.parent_id IN (1, 3, 6, 7)
        )
        AND c0_.pk_id NOT IN (
	SELECT DISTINCT
		c0_.pk_id AS pkId
	FROM
		cold_chain AS c0_
	INNER JOIN ccm_models AS c2_ ON c0_.ccm_model_id = c2_.pk_id
	INNER JOIN ccm_asset_types AS c1_ ON c0_.ccm_asset_type_id = c1_.pk_id
	INNER JOIN placement_locations ON c0_.pk_id = placement_locations.location_id
	INNER JOIN placement_summary ON placement_summary.placement_location_id = placement_locations.pk_id
	WHERE
		c0_.warehouse_id = $warehouse_id
	AND (
		c1_.pk_id IN (1, 3, 6, 7)
		OR c1_.parent_id IN (1, 3, 6, 7)
	)
	GROUP BY
		pkId
        )";

        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Transfer Cold Chain Asset
     */
    public function transferColdChainAsset() {
        $form_values = $this->form_values;
        // App_Controller_Functions::pr($form_values);
        $created_by = $this->_em->getRepository('Users')->find($this->_user_id);

        if ($form_values['transfer'] == 1) {
            $cold_chain_update = $this->_table->find($form_values['coldchain_id']);
            $to_warehouse = $this->_em->getRepository('Warehouses')->find($form_values['to_warehouse']);
            $cold_chain_update->setWarehouse($to_warehouse);
            $cold_chain_update->setCreatedBy($created_by);
            $cold_chain_update->setCreatedDate(App_Tools_Time::now());
            $cold_chain_update->setModifiedBy($created_by);
            $cold_chain_update->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($cold_chain_update);
            $this->_em->flush();
        } elseif ($form_values['quantity_issue'] > 0) {
            if ($form_values['quantity_issue'] <= $form_values['quantity_available']) {
                $sub_sql = $this->_em_read->createQueryBuilder()
                        ->select(""
                                . "cc.assetId, cc.quantity, cc.autoAssetId, cc.serialNumber,"
                                . "cc.estimateLife, cc.workingSince, cc.manufactureYear, cc.status,"
                                . "cc.approvedBy, cc.approvedOn, cc.createdDate,"
                                . "csh.pkId AS ccmStatusHistoryId,"
                                . "cat.pkId AS ccmAssetTypeId,"
                                . "cmod.pkId AS ccmModelId,"
                                . "cu.pkId AS createdById,"
                                . "sh.pkId AS sourceId"
                        )
                        ->from("ColdChain", "cc")
                        ->leftJoin('cc.ccmStatusHistory', 'csh')
                        ->leftjoin('cc.ccmAssetType', 'cat')
                        ->leftjoin('cc.ccmModel', 'cmod')
                        ->leftjoin('cc.createdBy', 'cu')
                        ->leftJoin('cc.source', 'sh')
                        ->where("cc.pkId = " . $this->form_values['coldchain_id']);

                $one_row = $sub_sql->getQuery()->getResult();
                $one_row = $one_row[0];
                $cold_chain = new ColdChain();
                $cold_chain->setAssetId($one_row['assetId']);
                $cold_chain->setQuantity($form_values['quantity_issue']);
                $cold_chain->setAutoAssetId($one_row['autoAssetId']);
                $cold_chain->setSerialNumber($one_row['serialNumber']);
                $cold_chain->setEstimateLife($one_row['estimateLife']);
                $cold_chain->setWorkingSince(new \DateTime(App_Controller_Functions::dateToDbFormat($one_row['workingSince'])));
                $cold_chain->setManufactureYear(new \DateTime(App_Controller_Functions::dateToDbFormat($one_row['manufactureYear'])));
                $cold_chain->setStatus($one_row['status']);
                $cold_chain->setApprovedBy($one_row['approvedBy']);
                $cold_chain->setApprovedOn(new \DateTime(App_Controller_Functions::dateToDbFormat($one_row['approvedOn'])));
                $cold_chain->setCreatedDate(App_Tools_Time::now());

                $asset_type = $this->_em->getRepository('CcmAssetTypes')->find($one_row['ccmAssetTypeId']);
                $cold_chain->setCcmAssetType($asset_type);
                $model_id = $this->_em->getRepository('CcmModels')->find($one_row['ccmModelId']);
                $cold_chain->setCcmModel($model_id);
                $created_by_id = $this->_em->getRepository('Users')->find($one_row['createdById']);
                if ($one_row['sourceId']) {
                    $source_id = $this->_em->getRepository('Stakeholders')->find($one_row['sourceId']);
                    $cold_chain->setSource($source_id);
                }
                if ($form_values['to_warehouse']) {
                    $frm_warehouse_id = $this->_em->getRepository('Warehouses')->find($form_values['to_warehouse']);
                    $cold_chain->setWarehouse($frm_warehouse_id);
                }
                $cold_chain->setCreatedBy($created_by_id);
                $cold_chain->setCreatedDate(App_Tools_Time::now());
                $cold_chain->setModifiedBy($created_by_id);
                $cold_chain->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($cold_chain);
                $this->_em->flush();
                $cold_chain_id = $cold_chain->getPkId();
                // Ccm Status History Add
                $ccm_status_history = new CcmStatusHistory();
                $ccm_status_history->setStatusDate(new \DateTime(date("Y-m-d h:i")));
                $cold_chian_id = $this->_em->getRepository('ColdChain')->find($cold_chain->getPkId());
                $ccm_status_history->setCcm($cold_chian_id);
                $asset1_id = $this->_em->getRepository('CcmAssetTypes')->find($one_row['ccmAssetTypeId']);
                $ccm_status_history->setCcmAssetType($asset1_id);
                $ccm_status_history->setWorkingQuantity($form_values['quantity_issue']);
                $ccm_status_history->setCreatedBy($created_by_id);
                $ccm_status_history->setCreatedDate(App_Tools_Time::now());
                $ccm_status_history->setModifiedBy($created_by_id);
                $ccm_status_history->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($ccm_status_history);
                $this->_em->flush();
                // end      
                // update ccm status hisory
                $cold_chain_model = new Model_ColdChain();
                $ccm_history_id = $ccm_status_history->getPkId();
                $cold_chain_model->updateCcmStatusHistory($cold_chain_id, $ccm_history_id);
                // end
                // Adjust Quantity
                $adjusted_quantity = $form_values['quantity_available'] - $form_values['quantity_issue'];
                $cold_chain_adjust_quantity = $this->_table->find($form_values['coldchain_id']);
                $cold_chain_adjust_quantity->setQuantity($adjusted_quantity);
                $cold_chain_adjust_quantity->setCreatedBy($created_by_id);
                $cold_chain_adjust_quantity->setCreatedDate(App_Tools_Time::now());
                $cold_chain_adjust_quantity->setModifiedBy($created_by_id);
                $cold_chain_adjust_quantity->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($cold_chain_adjust_quantity);
                $this->_em->flush();
                $status_history_tbl = $this->_em->getRepository('CcmStatusHistory')->find($cold_chain_adjust_quantity->getCcmStatusHistory()->getPkId());
                $status_history_tbl->setWorkingQuantity($adjusted_quantity);
                $this->_em->persist($status_history_tbl);
                $this->_em->flush();
                // end
            }
        }

        //enter in clod chain history
        if ($form_values['transfer'] == 1 || $form_values['quantity_issue'] > 0) {
            $ccm_transfer = new CcmTransferHistory();
            if ($form_values['quantity_issue']) {
                $ccm_transfer->setQuantity($form_values['quantity_issue']);
            }
            $ccm_transfer->setTransferDate(App_Tools_Time::now());
            $transferBy = $this->_em->getRepository('Users')->find($this->_user_id);
            $ccm_transfer->setTransferBy($transferBy);
            $ccm_id = $this->_em->getRepository('ColdChain')->find($form_values['coldchain_id']);
            $ccm_transfer->setCcm($ccm_id);
            //Temporarily hard code as this filed not found in coldchain table
            $ccm_status_list_id = $this->_em->getRepository('CcmStatusList')->find('1');
            $ccm_transfer->setCcmStatusList($ccm_status_list_id);
            $from_warehouse = $this->_em->getRepository('Warehouses')->find($form_values['from_warehouse']);
            $ccm_transfer->setFromWarehouse($from_warehouse);
            $to_warehouse = $this->_em->getRepository('Warehouses')->find($form_values['to_warehouse']);
            $ccm_transfer->setToWarehouse($to_warehouse);
            $ccm_transfer->setCreatedBy($created_by);
            $ccm_transfer->setCreatedDate(App_Tools_Time::now());
            $ccm_transfer->setModifiedBy($created_by);
            $ccm_transfer->setModifiedDate(App_Tools_Time::now());
            $this->_em->persist($ccm_transfer);
            $this->_em->flush();
        }
        if (!empty($form_values['to_warehouse'])) {
            $date_time = date("Y/m/d");
            $to_warehouse = $form_values['to_warehouse'];
            $qry = "SELECT REPUpdateCapacity($to_warehouse,$date_time) from DUAL";
            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();
            $qry1 = "SELECT REPUpdateRequirement($to_warehouse,$date_time) from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
        if (!empty($form_values['from_warehouse'])) {
            $date_time = date("Y/m/d");
            $from_warehouse = $form_values['from_warehouse'];
            $qry = "SELECT REPUpdateCapacity($from_warehouse,$date_time) from DUAL";
            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();
            $qry1 = "SELECT REPUpdateRequirement($from_warehouse,$date_time) from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
    }

    /**
     * Update Ccm Status History
     * @param type $id
     * @param type $history_id
     * @return type
     */
    public function updateCcmStatusHistory($id, $history_id) {
        $cold_chain = $this->_table->find($id);
        $status_history = $this->_em->getRepository('CcmStatusHistory')->find($history_id);
        $cold_chain->setCcmStatusHistory($status_history);
        $created_by = $this->_em->find('Users', $this->_user_id);
        $cold_chain->setModifiedBy($created_by);
        $cold_chain->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($cold_chain);
        return $this->_em->flush();
    }

    /**
     * Search Vaccine Carriers
     * @return boolean
     */
    public function searchVaccineCarriers() {
        if (!empty($this->form_values['ccm_make_id'])) {
            $where[] = "ccmake.pkId  = '" . $this->form_values['ccm_make_id'] . "'";
        }
        if (!empty($this->form_values['ccm_model_id'])) {
            $where[] = "ccm.pkId  = '" . $this->form_values['ccm_model_id'] . "'";
        }
        if (!empty($this->form_values['catalogue_id'])) {
            $where[] = "ccm.catalogueId  = '" . $this->form_values['catalogue_id'] . "'";
        }
        if ($this->form_values['placed_at'] == 1 && !empty($this->form_values['warehouse'])) {
            $where[] = "w.pkId  = '" . $this->form_values['warehouse'] . "'";
        }
        if ($this->form_values['placed_at'] == 0) {
            $where[] = "w.pkId  IS NULL ";
        }
        $where[] = "cat.pkId = '" . Model_CcmAssetTypes::VACCINECARRIER . "'";
        $where[] = "cc.createdBy = '" . $this->_user_id . "'  ";
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,at.assetTypeName,ccm.ccmModelName,"
                        . "ccm.assetDimensionLength,ccm.assetDimensionWidth,ccm.assetDimensionHeight,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "w.warehouseName,d.locationName")
                ->from('ColdChain', 'cc')
                ->join('cc.ccmAssetType', 'at')
                ->join('cc.ccmModel', 'ccm')
                ->join('ccm.ccmAssetType', 'cat')
                ->join('ccm.ccmMake', 'ccmake');
        if ($this->form_values['placed_at'] == 1) {
            $str_sql->join('cc.warehouse', 'w');
            $str_sql->join('w.district', 'd');
        }
        if ($this->form_values['placed_at'] == 0) {
            $str_sql->leftjoin('cc.warehouse', 'w');
            $str_sql->leftjoin('w.district', 'd');
        }
        $str_sql->where($where_s);
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Search Voltage Regulator
     * @return boolean
     */
    public function searchVoltageRegulator() {
        $form_values = $this->form_values;

        if (!empty($form_values['ccm_make_id'])) {
            $where[] = "ccmake.pkId  = '" . $form_values['ccm_make_id'] . "'";
        }
        if (!empty($form_values['ccm_model_id'])) {
            $where[] = "ccm.pkId  = '" . $form_values['ccm_model_id'] . "'";
        }
        if (!empty($form_values['catalogue_id'])) {
            $where[] = "ccm.catalogueId  = '" . $form_values['catalogue_id'] . "'";
        }
        if (!empty($form_values['ccm_status_list_id'])) {
            $where[] = "cc.ccmStatusHistory  = '" . $form_values['ccm_status_list_id'] . "'";
        }
        if ($form_values['placed_at'] == 1 && !empty($form_values['warehouse'])) {
            $where[] = "w.pkId  = '" . $form_values['warehouse'] . "'";
        }
        if ($form_values['placed_at'] == 0) {
            $where[] = "w.pkId  IS NULL ";
        }


        $where[] = "cat.pkId = '" . Model_CcmAssetTypes::VOLTAGEREGULATOR . "'";
        $where[] = "cc.createdBy = '" . $this->_user_id . "'  ";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,"
                        . "ccmake.ccmMakeName,cc.createdDate,"
                        . "d.locationName as district, w.warehouseName as facility")
                ->from('ColdChain', 'cc')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('ccm.ccmAssetType', 'cat')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl');
        if ($this->form_values['placed_at'] == 1) {
            $str_sql->join('cc.warehouse', 'w');
            $str_sql->join('w.district', 'd');
        }

        if ($this->form_values['placed_at'] == 0) {
            $str_sql->leftjoin('cc.warehouse', 'w');
            $str_sql->leftjoin('w.district', 'd');
        }
        $str_sql->where($where_s);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Search Ice Packs
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function searchIcePacks($order = null, $sort = null) {
        if (!empty($this->form_values['ccm_make_id'])) {
            $where[] = "ccmake.pkId  = '" . $this->form_values['ccm_make_id'] . "'";
        }
        if (!empty($this->form_values['ccm_model_id'])) {
            $where[] = "ccm.pkId  = '" . $this->form_values['ccm_model_id'] . "'";
        }
        if ($this->form_values['placed_at'] == 1 && !empty($this->form_values['warehouse'])) {
            $where[] = "w.pkId  = '" . $this->form_values['warehouse'] . "'";
        }
        if ($this->form_values['placed_at'] == 0) {
            $where[] = "w.pkId  IS NULL ";
        }
        $where[] = "cat.pkId = '" . Model_CcmAssetTypes::ICEPACKS . "'";
        $where[] = "cc.createdBy = '" . $this->_user_id . "'  ";
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,at.assetTypeName,"
                        . "cc.quantity,ccm.ccmModelName,ccmake.ccmMakeName,"
                        . "cc.createdDate,d.locationName,w.warehouseName")
                ->from('ColdChain', 'cc')
                ->join('cc.ccmAssetType', 'at')
                ->join('cc.ccmModel', 'ccm')
                ->join('cc.ccmAssetType', 'cat')
                ->join('ccm.ccmMake', 'ccmake');
        if ($this->form_values['placed_at'] == 1) {
            $str_sql->join('cc.warehouse', 'w');
            $str_sql->join('w.district', 'd');
        }
        if ($this->form_values['placed_at'] == 0) {
            $str_sql->leftjoin('cc.warehouse', 'w');
            $str_sql->leftjoin('w.district', 'd');
        }
        $str_sql->where($where_s);
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Search Refrigerator
     * @return boolean
     */
    public function searchRefrigerator() {
        $form_values = $this->form_values;

        if (!empty($form_values['ccm_asset_sub_type_id'])) {
            $where[] = "cat.pkId  = '" . $form_values['ccm_asset_sub_type_id'] . "'";
        }
        if (!empty($form_values['source_id'])) {
            $where[] = "s.pkId  = '" . $form_values['source_id'] . "'";
        }
        if (!empty($form_values['ccm_status_list_id'])) {
            $where[] = "csl.pkId  = '" . $form_values['ccm_status_list_id'] . "'";
        }
        if (!empty($form_values['asset_id'])) {
            $where[] = "ccm.ccmAssetType  = '" . $form_values['asset_id'] . "'";
        }
        if (!empty($form_values['catalogue_id'])) {
            $where[] = "ccm.catalogueId  = '" . $form_values['catalogue_id'] . "'";
        }
        if (!empty($form_values['ccm_make_id'])) {
            $where[] = "ccmake.pkId  = '" . $form_values['ccm_make_id'] . "'";
        }
        if (!empty($form_values['ccm_model_id'])) {
            $where[] = "ccm.pkId  = '" . $form_values['ccm_model_id'] . "'";
        }
        if (!empty($form_values['serial_number'])) {
            $where[] = "cc.serialNumber  = '" . $form_values['serial_number'] . "'";
        }
        if (!empty($form_values['gross_capacity_from'])) {
            $where[] = "(ccm.grossCapacity20  >= '" . $form_values['gross_capacity_from'] . "' OR ccm.grossCapacity4 >= '" . $form_values['gross_capacity_from'] . "')";
        }
        if (!empty($form_values['gross_capacity_to'])) {
            $where[] = "(ccm.grossCapacity20  <= '" . $form_values['gross_capacity_to'] . "' OR ccm.grossCapacity4 <= '" . $form_values['gross_capacity_to'] . "' )";
        }
        if (!empty($form_values['working_since_from'])) {
            $where[] = "cc.workingSince  >= '" . App_Controller_Functions::dateToDbFormat($form_values['working_since_from']) . "'";
        }
        if (!empty($form_values['working_since_to'])) {
            $where[] = "cc.workingSince  <= '" . App_Controller_Functions::dateToDbFormat($form_values['working_since_to']) . "'";
        }
        if (!empty($form_values['cfc_free'])) {
            $where[] = "ccm.cfcFree  = '" . $form_values['cfc_free'] . "'";
        }
        if ($form_values['placed_at'] == 1 && !empty($form_values['warehouse'])) {
            $where[] = "w.pkId  = '" . $form_values['warehouse'] . "'";
        }

        if ($this->form_values['placed_at'] == 0) {
            $where[] = "w.pkId  IS NULL ";
        }
        $where[] = "(cp.pkId = '" . Model_CcmAssetTypes::REFRIGERATOR . "' OR cc.pkId ='" . Model_CcmAssetTypes::REFRIGERATOR . "') ";
        //  $where[] = "cc.createdBy = '" . $this->_user_id . "'  ";

        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }

        $str_sql = $this->_em->createQueryBuilder()
                ->select("cc.pkId,cc.assetId,cc.quantity,"
                        . "ccm.ccmModelName,csl.ccmStatusListName,"
                        . "ccmake.ccmMakeName,cc.createdDate, DATE_FORMAT(cc.workingSince, '%d/%m/%Y') workingSince ,"
                        . "d.locationName district, w.warehouseName facility,"
                        . "ccm.grossCapacity20, ccm.grossCapacity4,"
                        . "cat.assetTypeName")
                ->from('ColdChain', 'cc')
                ->leftjoin('cc.source', 's')
                ->leftJoin('cc.ccmModel', 'ccm')
                ->leftJoin('cc.ccmAssetType', 'cat')
                ->leftJoin('cat.parent', 'cp')
                ->leftJoin('ccm.ccmMake', 'ccmake')
                ->leftJoin('cc.ccmStatusHistory', 'csh')
                ->leftJoin('csh.ccmStatusList', 'csl');

        if ($this->form_values['placed_at'] == 1) {
            $str_sql->join('cc.warehouse', 'w');
            $str_sql->join('w.district', 'd');
        }

        if ($this->form_values['placed_at'] == 0) {
            $str_sql->leftjoin('cc.warehouse', 'w');
            $str_sql->leftjoin('w.district', 'd');
        }

        $str_sql->where($where_s);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get CCM Locations
     * @uses api Barcode
     * @return type
     */
    public function getCCMLocations($wh_id) {
        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
                cold_chain.pk_id,
                cold_chain.asset_id,
                cold_chain.auto_asset_id,
                cold_chain.serial_number,
                AssetSubtype.asset_type_name
               FROM
                cold_chain
               INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
               LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
               WHERE
               cold_chain.warehouse_id = $wh_id AND
               ((cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::COLDROOM . " OR
               AssetMainType.pk_id = " . Model_CcmAssetTypes::COLDROOM . ") OR (cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::REFRIGERATOR . " OR
               AssetMainType.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "))");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Non CCM Locations
     * @uses api Barcode
     * @return type
     */
    public function getNonCCMLocations($wh_id) {

        $str_sql = $this->_em_read->getConnection()->prepare("SELECT
            non_ccm_locations.pk_id,
            non_ccm_locations.location_name,
            non_ccm_locations.rack_information_id
            FROM
            non_ccm_locations
            where
            warehouse_id= '" . $wh_id . "'");
        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    /**
     * Get Locations Name
     * @return type
     */
    public function getLocationsName() {
        $wh_id = $this->_identity->getWarehouseId();
        $str_sql = "SELECT DISTINCT
                cold_chain.pk_id as pkId,
                cold_chain.asset_id as assetId
                FROM
                        cold_chain
                INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                LEFT JOIN ccm_asset_types AS AssetMainType ON AssetSubtype.parent_id = AssetMainType.pk_id
                LEFT JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
                LEFT JOIN ccm_status_history ON ccm_status_history.pk_id = cold_chain.ccm_status_history_id
                WHERE
                        cold_chain.warehouse_id = $wh_id
                AND ( ( cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::COLDROOM . "
                            OR AssetMainType.pk_id = " . Model_CcmAssetTypes::COLDROOM . " )
                    OR ( cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                            OR AssetMainType.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . " ) )
                AND placement_locations.location_type = 99 AND ccm_status_history.ccm_status_list_id <> 3
                GROUP BY
                        cold_chain.auto_asset_id
                ORDER BY
                        ccm_status_history.ccm_status_list_id,
                        cold_chain.asset_id,                        
                        cold_chain.ccm_asset_type_id DESC";
        $row_ref = $this->_em_read->getConnection()->prepare($str_sql);
        $row_ref->execute();
        return $row_ref->fetchAll();
    }

    /**
     * Get All Non Quantity Ref Asets
     * @return type
     */
    public function getAllNonQuantityRefAsets() {
        if (!empty($this->form_values['warehouse'])) {
            $wh_id = $this->form_values['warehouse'];
            $where = " AND cold_chain.warehouse_id = " . $wh_id;
        }
        if (!empty($this->form_values['combo1'])) {
            $sel_prov = $this->form_values['combo1'];
            $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
        }
        if (!empty($this->form_values['combo2'])) {
            $sel_dist = $this->form_values['combo2'];
            $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
        }

        $str_sql_ref = "SELECT
        ccm_models.catalogue_id AS Cat_ID,
        ccm_models.ccm_model_name AS Model,
        ccm_makes.ccm_make_name AS Manufacturer,
        cold_chain.serial_number AS Serial_No,
        list_detail.list_value AS Refrigerator_Gas_Type,
        round(ccm_models.net_capacity_4,1) net_capacity_4,
        round(ccm_models.net_capacity_20,1) net_capacity_20,
        DATE_FORMAT(
                cold_chain.working_since,
                '%d-%m-%Y'
        ) working_since,
        stakeholders.stakeholder_name,
        ccm_status_list.ccm_status_list_name,
        cold_chain.warehouse_id,
        Asset_Type.asset_type_name AS Asset_Type,
        Power_Source_Name.asset_type_name AS Power_Source,
        warehouses.warehouse_name
        FROM
            ccm_models
        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
        LEFT JOIN list_detail ON ccm_models.gas_type = list_detail.pk_id
        INNER JOIN cold_chain ON cold_chain.ccm_model_id = ccm_models.pk_id
        INNER JOIN stakeholders ON cold_chain.source_id = stakeholders.pk_id
        INNER JOIN ccm_status_history ON cold_chain.pk_id = ccm_status_history.ccm_id
        INNER JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
        INNER JOIN ccm_asset_types AS Asset_Type ON cold_chain.ccm_asset_type_id = Asset_Type.pk_id
        LEFT JOIN ccm_asset_types AS Power_Source_Name ON ccm_models.ccm_asset_type_id = Power_Source_Name.parent_id
        INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
        WHERE (
            Asset_Type.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
            OR Asset_Type.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
            ) $where 
                $provFilter
                $distFilter
        GROUP BY
        cold_chain.pk_id";
        $row_ref = $this->_em_read->getConnection()->prepare($str_sql_ref);
        $row_ref->execute();
        return $row_ref->fetchAll();
    }

    /**
     * Get All Non Quantity Cold Room Asets
     * @return type
     */
    public function getAllNonQuantityColdRoomAsets() {
        if (!empty($this->form_values['warehouse'])) {
            $wh_id = $this->form_values['warehouse'];
            $where = " AND cold_chain.warehouse_id = " . $wh_id;
        }
        if (!empty($this->form_values['combo1'])) {
            $sel_prov = $this->form_values['combo1'];
            $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
        }
        if (!empty($this->form_values['combo2'])) {
            $sel_dist = $this->form_values['combo2'];
            $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
        }

        $str_sql = "SELECT
            DISTINCT
            ccm_models.ccm_model_name,
            ccm_makes.ccm_make_name,
            cold_chain.serial_number,
            list_detail.list_value,
            CoolingSystem.list_value AS cooling_system,
            ccm_models.net_capacity_4,
            ccm_models.net_capacity_20,
            YEAR (cold_chain.working_since) AS Supply_Year,
            stakeholders.stakeholder_name,
            ccm_status_list.ccm_status_list_name,
            warehouses.warehouse_name
           FROM
            cold_chain
           INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
           INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
           INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
           LEFT JOIN list_detail ON ccm_models.gas_type = list_detail.pk_id
           LEFT JOIN ccm_cold_rooms ON cold_chain.pk_id = ccm_cold_rooms.ccm_id
           LEFT JOIN list_detail AS CoolingSystem ON ccm_cold_rooms.cooling_system = CoolingSystem.pk_id
           INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
           INNER JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
           INNER JOIN stakeholders ON cold_chain.source_id = stakeholders.pk_id
           INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
           WHERE
             (ccm_asset_types.pk_id = " . Model_CcmAssetTypes::COLDROOM . " OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::COLDROOM . ") $where "
                . "$provFilter
                   $distFilter";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Non Quantity Cold Box Asets
     * @return type
     */
    public function getAllNonQuantityColdBoxAsets() {
        if (!empty($this->form_values['warehouse'])) {
            $wh_id = $this->form_values['warehouse'];
            $where = " AND c0_.warehouse_id = " . $wh_id;
        }
        if (!empty($this->form_values['combo1'])) {
            $sel_prov = $this->form_values['combo1'];
            $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
        }
        if (!empty($this->form_values['combo2'])) {
            $sel_dist = $this->form_values['combo2'];
            $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
        }

        $str_sql = "SELECT DISTINCT
            c1_.asset_type_name AS asset_type_name3,
            c2_.ccm_model_name,
            ccm_makes.ccm_make_name,
            c0_.serial_number,
            c0_.manufacture_year,
            round(c2_.net_capacity_20, 1) AS net_capacity_20,
            c2_.catalogue_id,
            ccm_status_list.ccm_status_list_name,
            c1_.asset_type_name,
            round(c2_.net_capacity_4, 1) AS net_capacity_4,
            CONCAT(
             c2_.internal_dimension_length,
             'x',
             c2_.internal_dimension_width,
             'x',
             c2_.internal_dimension_height
            ) AS internalDim,
            CONCAT(
             c2_.asset_dimension_length,
             'x',
             c2_.asset_dimension_width,
             'x',
             c2_.asset_dimension_height
            ) AS externalDim,
            CONCAT(
             c2_.storage_dimension_length,
             'x',
             c2_.storage_dimension_width,
             'x',
             c2_.storage_dimension_height
            ) AS storageDim,
            c2_.product_price,
            c2_.cold_life,
            ccm_status_history.working_quantity,
            ccm_history.quantity,
            warehouses.warehouse_name
           FROM
            cold_chain AS c0_
           INNER JOIN ccm_models AS c2_ ON c0_.ccm_model_id = c2_.pk_id
           INNER JOIN ccm_asset_types AS c1_ ON c0_.ccm_asset_type_id = c1_.pk_id
           INNER JOIN ccm_makes ON c2_.ccm_make_id = ccm_makes.pk_id
           INNER JOIN ccm_status_history ON c0_.pk_id = ccm_status_history.ccm_id
           INNER JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
           INNER JOIN ccm_history ON c0_.pk_id = ccm_history.ccm_id
           INNER JOIN warehouses ON c0_.warehouse_id = warehouses.pk_id
           WHERE
            (
            c1_.pk_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
            OR c1_.parent_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
           )
           $where 
           $provFilter
           $distFilter
           ";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Non Quantity Ice Pack Asets
     * @return type
     */
    public function getAllNonQuantityIcePackAsets() {
        if (!empty($this->form_values['warehouse'])) {
            $wh_id = $this->form_values['warehouse'];
            $where = " AND c0_.warehouse_id = " . $wh_id;
        }
        if (!empty($this->form_values['combo1'])) {
            $sel_prov = $this->form_values['combo1'];
            $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
        }
        if (!empty($this->form_values['combo2'])) {
            $sel_dist = $this->form_values['combo2'];
            $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
        }

        $str_sql = "SELECT DISTINCT
            c1_.asset_type_name AS asset_type_name3,
            c2_.ccm_model_name,
            ccm_makes.ccm_make_name,
            c0_.serial_number,
            c0_.manufacture_year,
            round(c2_.net_capacity_20,1) net_capacity_20,
            c2_.catalogue_id,
            ccm_status_list.ccm_status_list_name,
            ccm_asset_types.asset_type_name,
            ccm_cold_rooms.cooling_system,
            ccm_cold_rooms.refrigerator_gas_type,
            round(c2_.net_capacity_4,1) net_capacity_4,
            list_detail.list_value as gas_type_name,
            warehouses.warehouse_name
        FROM
            cold_chain AS c0_
        INNER JOIN ccm_models AS c2_ ON c0_.ccm_model_id = c2_.pk_id
        INNER JOIN ccm_asset_types AS c1_ ON c0_.ccm_asset_type_id = c1_.pk_id
        INNER JOIN ccm_makes ON c2_.ccm_make_id = ccm_makes.pk_id
        INNER JOIN ccm_status_history ON c0_.pk_id = ccm_status_history.ccm_id
        INNER JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
        LEFT JOIN ccm_asset_types ON c1_.parent_id = ccm_asset_types.pk_id
        INNER JOIN ccm_cold_rooms ON c0_.pk_id = ccm_cold_rooms.ccm_id
        INNER JOIN list_detail ON list_detail.pk_id = ccm_cold_rooms.refrigerator_gas_type
        INNER JOIN warehouses ON c0_.warehouse_id = warehouses.pk_id
        WHERE
            ( ccm_asset_types.pk_id = " . Model_CcmAssetTypes::ICEPACKS . "
            OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::ICEPACKS . " ) 
            $where 
            $provFilter
            $distFilter                
            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Non Quantity Voltage Regulator Asets
     * @return type
     */
    public function getAllNonQuantityVoltageRegulatorAsets() {
        if (!empty($this->form_values['warehouse'])) {
            $wh_id = $this->form_values['warehouse'];
            $where = " AND cold_chain.warehouse_id = " . $wh_id;
        }
        if (!empty($this->form_values['combo1'])) {
            $sel_prov = $this->form_values['combo1'];
            $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
        }
        if (!empty($this->form_values['combo2'])) {
            $sel_dist = $this->form_values['combo2'];
            $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
        }

        $str_sql = "SELECT
            warehouses.ccem_id,
            ccm_models.catalogue_id,
            ccm_models.ccm_model_name,
            ccm_makes.ccm_make_name,
            ccm_models.no_of_phases,
            warehouses.warehouse_name
        FROM
            cold_chain
        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
        INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
        INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
        WHERE
            cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::VOLTAGEREGULATOR . "
                and warehouses.status = 1
            $where 
            $provFilter
            $distFilter              
            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get All Non Quantity Generator Asets
     * @return type
     */
    public function getAllNonQuantityGeneratorAsets() {
        if (!empty($this->form_values['warehouse'])) {
            $wh_id = $this->form_values['warehouse'];
            $where = " AND cold_chain.warehouse_id = " . $wh_id;
        }
        if (!empty($this->form_values['combo1'])) {
            $sel_prov = $this->form_values['combo1'];
            $provFilter = (!empty($sel_prov) && $sel_prov != 'all') ? " AND warehouses.province_id = $sel_prov" : '';
        }
        if (!empty($this->form_values['combo2'])) {
            $sel_dist = $this->form_values['combo2'];
            $distFilter = (!empty($sel_dist) && $sel_dist != 'all') ? " AND warehouses.district_id = $sel_dist" : '';
        }

        $str_sql = "SELECT
            ccm_asset_types.asset_type_name,
            ccm_models.ccm_model_name,
            ccm_makes.ccm_make_name,
            cold_chain.serial_number,
            ccm_generators.power_source,
            ccm_generators.power_rating,
            ccm_generators.automatic_start_mechanism,
            ccm_models.no_of_phases,
            DATE_FORMAT(cold_chain.working_since,'%d-%m-%Y') AS Supply_Year,
            ccm_status_list.ccm_status_list_name,
            warehouses.warehouse_name
        FROM
            ccm_models
            INNER JOIN ccm_makes ON ccm_models.ccm_make_id = ccm_makes.pk_id
            INNER JOIN ccm_asset_types ON ccm_models.ccm_asset_type_id = ccm_asset_types.pk_id
            INNER JOIN cold_chain ON cold_chain.ccm_asset_type_id = ccm_models.ccm_asset_type_id
            INNER JOIN ccm_generators ON ccm_generators.ccm_id = cold_chain.pk_id
            INNER JOIN ccm_status_history ON ccm_status_history.ccm_id = cold_chain.pk_id
            INNER JOIN ccm_status_list ON ccm_status_history.ccm_status_list_id = ccm_status_list.pk_id
            INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
            WHERE
            (
                ccm_asset_types.pk_id = " . Model_CcmAssetTypes::GENERATOR . "
                OR ccm_asset_types.parent_id = " . Model_CcmAssetTypes::GENERATOR . "
                )
                $where 
                $provFilter
                $distFilter
            GROUP BY
            cold_chain.pk_id
            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Last Update Date
     * @return date
     */
    public function getLastUpdateDate() {
        $wh_id = $this->_identity->getWarehouseId();
        $id = $this->form_values['id'];
        if ($id == 3) {
            $where = "IN (15,16)";
        } else if ($id == 1) {
            $where = "IN (8,9,10,11,12,13,14,17,18,19,20,21,22,23,24,25,26)";
        } else {
            $where = "= $id ";
        }
        $str_sql = "SELECT
            MAX(DATE_FORMAT(created_date, '%M %e, %Y'))  as l_date
            FROM
            cold_chain
            WHERE
            cold_chain.ccm_asset_type_id $where AND
            cold_chain.warehouse_id = $wh_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

}
