<?php

/**
 * Model_CcmWarehouses
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for CCM Warehouses
 */
class Model_CcmWarehouses extends Model_Base {

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
        $this->_table = $this->_em->getRepository('CcmWarehouses');
    }

    /**
     * Get All Health Facility
     *
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllHealthFacility($order = null, $sort = null) {
        $form_values = $this->form_values;
        $qry = $this->_em_read->createQueryBuilder()
                ->select("cw.pkId,cw.routineImmunizationIcepackRequirments,cw.campaignIcepackRequirments,ea.listValue as electricity,"
                        . "vs.listValue as vaccination")
                ->from("CcmWarehousesVaccinationStaff", "ws")
                ->join("ws.ccmWarehouse", "cw")
                ->join("cw.warehouse", 'w')
                ->join("ws.vaccinationStaff", "vs")
                ->join("cw.electricityAvailability", "ea");
        if (!empty($form_values['warehouse'])) {
            $qry->where("w.pkId=" . $form_values['warehouse']);
        }

        return $qry->getQuery()->getResult();
    }

    /**
     * Get Total Population By Facility Type
     *
     * @return type
     */
    public function getTotalPopulationByFacilityType() {
        $str_qry = "
            SELECT
                warehouse_types.warehouse_type_name AS FacilityType,
                COUNT(warehouses.pk_id) AS NoOfFacilities,
                MIN(warehouse_population.facility_total_pouplation) AS Minimum,
                MAX(warehouse_population.facility_total_pouplation) AS Maximum,
                ROUND(AVG (warehouse_population.facility_total_pouplation)) AS Mean1
            FROM
                warehouses
                INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
                WHERE warehouses.status = 1  ";
        if (!empty($this->form_values['office'])) {
            $str_qry .= "  warehouses.province_id = " . $this->form_values['office'];
        }
        $str_qry .= " GROUP BY warehouse_types.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Refrigerator By Working Status FType Area
     *
     * @return type
     */
    public function refrigeratorByWorkingStatusFTypeArea() {
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

        $str_qry = "SELECT
                        Province.location_name AS Province,
                        locations.location_name AS District,
                        warehouse_types.warehouse_type_name AS FacilityType,
                        AssetSubtype.asset_type_name,
                        Count(AssetSubtype.pk_id) AS Total,
                        Sum(IF(ccm_status_history.ccm_status_list_id=1, 1, 0)) AS Working,
                        ROUND((SUM(IF(ccm_status_history.ccm_status_list_id=1, 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS WorkingPer,
                        Sum(IF(ccm_status_history.ccm_status_list_id=2, 1, 0)) AS NeedsService,
                        ROUND((SUM(IF(ccm_status_history.ccm_status_list_id=2, 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS NeedsServicePer,
                        Sum(IF(ccm_status_history.ccm_status_list_id=3, 1, 0)) AS NotWorking,
                        ROUND((SUM(IF(ccm_status_history.ccm_status_list_id=3, 1, 0))/COUNT(cold_chain.warehouse_id)) * 100, 1) AS NotWorkingPer
                    FROM
                        warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN ccm_asset_types AS AssetSubtype ON cold_chain.ccm_asset_type_id = AssetSubtype.pk_id
                        INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
                    WHERE
                         (
                       AssetSubtype.pk_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                       OR AssetSubtype.parent_id = " . Model_CcmAssetTypes::REFRIGERATOR . "
                          )
                        and warehouses.status = 1
                        " . $str_where . "

                        GROUP BY
                                Province.pk_id,
                                AssetSubtype.pk_id
                        ORDER BY
                                Province.pk_id,
                                locations.location_name
                    ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Electricity Availability By FType
     *
     * @return string
     */
    public function getElectricityAvailabilityByFType() {
        return array(
            '<8hrs/24hrs' => '78',
            '8to16hrs/24hrs' => '35',
            '>16hrs/24hrs' => '9',
            'none' => '14'
        );
    }

    /**
     * Get Refrigerators Freezers Utilization Pie
     *
     */
    public function getRefrigeratorsFreezersUtilizationPie() {
        return array(
            'InUse' => '143',
            'InStore' => '35',
            'NotUsed' => '11',
            'Unknown' => '4'
        );
    }

    /**
     * Get Refrigerators Freezers Models By Age Group Pie
     *
     */
    public function getRefrigeratorsFreezersModelsByAgeGroupPie() {
        return array(
            '05Years' => '1167',
            '610Years' => '135',
            '>10Years' => '80',
            'UnknownAge' => '13'
        );
    }

    /**
     * Get Refrigerators By Working Status
     *
     */
    public function getRefrigeratorsByWorkingStatus() {
        return array(
            'WorkingWell' => '90',
            'WorkingNeedsService' => '7',
            'NotWorking' => '3'
        );
    }

    /**
     * Get Refrigerators Freezers By Type
     *
     */
    public function getRefrigeratorsFreezersByType() {
        return array(
            'ChestRefAC' => '78',
            'ChestRefEleGas' => '44',
            'ChestRefEleKerosene' => '13',
            'IcelinedRef' => '62',
            'IcePackFreezerAC' => '11',
            'IcePackFreezerEleGas' => '30',
            'SolarPhotvoltaicRef' => '9',
            'UprightRefACEle' => '23',
            'UprightRefACEleGas' => '19',
            'UprightRefACEleKerosene' => '14'
        );
    }

    /**
     * Vaccine Storage Capacity At 2 to 8 Report
     *
     * @return type
     */
    public function vaccineStorageCapacityAt2to8Report() {
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
        $where[] = "warehouses.status = 1 ";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }

        $str_qry = "SELECT
            a.FacilityName, a.Province, a.District, a.FacilityType, Required, ActualCap, Difference,
            IF ((Difference / Required) > 0.3, 1, 0) AS surplus30,
            IF ((Difference / Required) <= 0.3 AND (Difference / Required) > 0.1, 1, 0) AS surplus1030,
            IF ((Difference / Required) <= 0.1 AND (Difference / Required) >= -0.1, 1, 0) AS match10,
            IF ((Difference / Required) < -0.1 AND (Difference / Required) >= -0.3, 1, 0) AS shortage1030,
            IF ((Difference / Required) < -0.3 , 1, 0) AS shortage30
           FROM
            (
             SELECT
              Province.pk_id,
              Province.location_name AS Province,
              locations.location_name AS District,
              warehouses.warehouse_name AS FacilityName,
              warehouse_types.warehouse_type_name AS FacilityType,
              IFNULL(warehouse_population.capacity_4degree, 0) AS ActualCap,
              IFNULL(warehouse_population.requirments_4degree, 0) AS Required,
              IFNULL(warehouse_population.capacity_4degree, 0) - IFNULL(warehouse_population.requirments_4degree, 0) AS Difference
             FROM
              warehouses
             INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
             INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
             INNER JOIN locations ON warehouses.district_id = locations.pk_id
             INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
             " . $str_where . "
            ) a
        ORDER BY
            a.pk_id,
            a.District,
            a.FacilityName ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Vaccine Storage Capacity At 2 to 8 Graph
     *
     * @return type
     */
    public function vaccineStorageCapacityAt2to8Graph() {
        $where = array();
        $str_where = "";

        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        $where[] = "warehouses.status = 1";

        if (count($where) > 0) {
            $str_where .= implode(" AND ", $where);
        }

        $str_qry = "SELECT
                    FacilityType,
                    sum(b.surplus30) AS surplus30,
                    sum(b.surplus1030) AS surplus1030,
                    sum(b.match10) AS match10,
                    sum(b.shortage1030) AS shortage1030,
                    Sum(b.shortage30) AS shortage30,
                   Sum(b.UnknownAll) as Unknown
                   FROM
                    (
                     SELECT
                        a.FacilityType,
                        Required,
                        ActualCap,
                        Difference,
                        IF (Required + ActualCap = 0 OR (Required = 0 AND Difference != 0), 1, 0) AS UnknownAll,
                        IF ((Difference / Required) > 0.3, 1, 0) AS surplus30,
                        IF ((Difference / Required) <= 0.3 AND (Difference / Required) > 0.1, 1, 0) AS surplus1030,
                        IF ((Difference / Required) <= 0.1 AND (Difference / Required) >= -0.1, 1, 0) AS match10,
                        IF ((Difference / Required) < -0.1 AND (Difference / Required) >= -0.3, 1, 0) AS shortage1030,
                        IF ((Difference / Required) < -0.3 , 1, 0) AS shortage30
                   FROM
                    (
                     SELECT
                      Province.location_name AS Province,
                      locations.location_name AS District,
                      warehouses.warehouse_name AS FacilityName,
                      warehouse_type_categories.category_name AS FacilityType,
                      IFNULL((warehouse_population.capacity_4degree), 0) AS ActualCap,
                      IFNULL((warehouse_population.requirments_4degree), 0) AS Required,
                      IFNULL(warehouse_population.capacity_4degree, 0) - IFNULL(warehouse_population.requirments_4degree, 0) AS Difference
                     FROM
                      warehouses
                     INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                     INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
                     INNER JOIN locations ON warehouses.district_id = locations.pk_id
                     INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
                   INNER JOIN warehouse_type_categories ON warehouse_types.warehouse_type_category_id = warehouse_type_categories.pk_id
                   WHERE
                      $str_where
                     ORDER BY
                      warehouse_types.geo_level_id ASC
                    ) a
                    ) b
                   GROUP BY
                    FacilityType";


        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Vaccine Storage Capacity At 20 Report
     *
     * @return type
     */
    public function vaccineStorageCapacityAt20Report() {
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
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }

        $str_qry = "SELECT
            a.Province, a.FacilityName, a.District, a.FacilityType, Required, ActualCap, Difference,
            IF (Required + ActualCap = 0 OR (Required = 0 AND Difference != 0), 1, 0) AS UnknownAll,
            IF ((Difference / Required) > 0.3, 1, 0) AS surplus30,
            IF ((Difference / Required) <= 0.3 AND (Difference / Required) > 0.1, 1, 0) AS surplus1030,
            IF ((Difference / Required) <= 0.1 AND (Difference / Required) >= -0.1, 1, 0) AS match10,
            IF ((Difference / Required) < -0.1 AND (Difference / Required) >= -0.3, 1, 0) AS shortage1030,
            IF ((Difference / Required) < -0.3 , 1, 0) AS shortage30
           FROM
            (
             SELECT
             Provinces.pk_id,
            Provinces.location_name AS Province,
            locations.location_name AS District,
              warehouses.warehouse_name AS FacilityName,
              warehouse_types.warehouse_type_name AS FacilityType,
              IFNULL(warehouse_population.capacity_20degree, 0) AS ActualCap,
              IFNULL(warehouse_population.requirments_20degree, 0) AS Required,
              IFNULL(warehouse_population.capacity_20degree, 0) - IFNULL(warehouse_population.requirments_20degree, 0) AS Difference
             FROM
              warehouses
             INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
             INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
             INNER JOIN locations ON warehouses.district_id = locations.pk_id
             INNER JOIN locations AS Provinces ON warehouses.province_id = Provinces.pk_id
             " . $str_where . "
            ) a
            ORDER BY
            a.pk_id,
            a.District";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Vaccine Storage Capacity At 20 Graph
     *
     * @return type
     */
    public function vaccineStorageCapacityAt20Graph() {
        $where = array();
        $str_where = "";

        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }

        $str_qry = "
        SELECT FacilityType,
           sum(b.surplus30) as surplus30,
           sum(b.surplus1030) as surplus1030,
           sum(b.match10) as match10,
           sum(b.shortage1030) as shortage1030,
           Sum(b.shortage30) AS shortage30
           from (
        SELECT
           a.District, a.FacilityType, Required, ActualCap, Difference,
           IF (Required + ActualCap = 0 OR (Required = 0 AND Difference != 0), 1, 0) AS UnknownAll,
            IF ((Difference / Required) > 0.3, 1, 0) AS surplus30,
            IF ((Difference / Required) <= 0.3 AND (Difference / Required) > 0.1, 1, 0) AS surplus1030,
            IF ((Difference / Required) <= 0.1 AND (Difference / Required) >= -0.1, 1, 0) AS match10,
            IF ((Difference / Required) < -0.1 AND (Difference / Required) >= -0.3, 1, 0) AS shortage1030,
            IF ((Difference / Required) < -0.3 , 1, 0) AS shortage30
           FROM
            (
             SELECT
              locations.location_name AS District,
              warehouse_types.warehouse_type_name AS FacilityType,
              IFNULL(warehouse_population.capacity_20degree, 0) AS ActualCap,
              IFNULL(warehouse_population.requirments_20degree, 0) AS Required,
              IFNULL(warehouse_population.capacity_20degree, 0) - IFNULL(warehouse_population.requirments_20degree, 0) AS Difference
             FROM
              warehouses
             INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
             INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
             INNER JOIN locations ON warehouses.district_id = locations.pk_id
             INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
             " . $str_where . "
                 WHERE warehouse_types.pk_id IN(1, 2)
            ) a ) b group by FacilityType
                    ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Icepack Freezing Capacity Against SIA Requirements Graph
     *
     * @return type
     */
    public function icepackFreezingCapacityAgainstSIARequirementsGraph() {
        $where = array();
        $str_where = "";

        if (!empty($this->form_values['combo1']) && $this->form_values['office'] == 2) {
            $where[] = "warehouses.province_id = " . $this->form_values['combo1'];
        }
        if (!empty($this->form_values['combo2']) && $this->form_values['office'] == 6) {
            $where[] = "warehouses.district_id = " . $this->form_values['combo2'];
        }
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
        $where[] = "warehouses.status = 1";

        $str_qry = "
                   SELECT
                    b.FacilityType,
                    sum(b.surplus30) AS surplus30,
                    sum(b.surplus1030) AS surplus1030,
                    sum(b.match10) AS match10,
                    sum(b.shortage1030) AS shortage1030,
                    Sum(b.shortage30) AS shortage30
                   FROM
                    (
                     SELECT
                      a.FacilityType,
                     IF (
                      Difference / Required > 0.3,
                      1,
                      0
                     ) AS surplus30,

                    IF (
                     Difference / Required <= 0.3
                     AND Difference / Required > 0.1,
                     1,
                     0
                    ) AS surplus1030,

                   IF (
                    Difference / Required <= 0.1
                    AND Difference / Required >= - 0.1,
                    1,
                    0
                   ) AS match10,

                   IF (
                    Difference / Required < - 0.1
                    AND Difference / Required >= - 0.3,
                    1,
                    0
                   ) AS shortage1030,

                   IF (
                    Difference / Required < - 0.3,
                    1,
                    0
                   ) AS shortage30
                   FROM
                    (SELECT

                        warehouse_types.warehouse_type_name AS FacilityType,
                        warehouses.ccem_id AS FacilityCode,
                        IFNULL(warehouse_population.requirments_4degree, 0) AS Required,
                        IFNULL(warehouse_population.capacity_4degree, 0) AS Capacity,
                        IFNULL(warehouse_population.capacity_4degree, 0) - IFNULL(warehouse_population.requirments_4degree, 0) AS Difference
                   FROM
                         warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN cold_chain ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN warehouse_population ON warehouses.pk_id = warehouse_population.warehouse_id
                   WHERE
                        cold_chain.ccm_asset_type_id = " . Model_CcmAssetTypes::VACCINECARRIER . "
                        " . $str_where . "
                    )a)b group by FacilityType";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Storage Capacity By Area 4c
     *
     * @return type
     */
    public function getStorageCapacityByArea4c() {
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
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }

        $str_qry = "SELECT
                    a.Province, a.District, a.warehouse_type_name, req4, cap4, diff4,
                   sum(IF (diff4 / req4 > 0.3, 1, 0)) AS surplus30,
                   sum(IF (diff4 / req4 <= 0.3 AND diff4 / req4 > 0.1,1,0)) AS surplus1030,
                   sum(IF (diff4 / req4 <= 0.1 AND diff4 / req4 >= - 0.1,1, 0)) AS match10,
                   sum(IF (diff4 / req4 < - 0.1 AND diff4 / req4 >= - 0.3,1,0)) AS shortage1030,
                   sum(IF (diff4 / req4 < - 0.3, 1, 0)) AS shortage30
                   FROM
                    (
                    SELECT
                     warehouse_types.warehouse_type_name,
                     IFNULL(warehouse_population.requirments_4degree, 0) AS req4,
                     IFNULL(warehouse_population.capacity_4degree, 0) AS cap4,
                     IFNULL(warehouse_population.capacity_4degree, 0) - IFNULL(warehouse_population.requirments_4degree, 0) AS diff4,
                     District.location_name AS District,
                     Province.pk_id,
                     Province.location_name AS Province
                    FROM
                        warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
                    INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                    INNER JOIN locations AS Province ON District.province_id = Province.pk_id
                     " . $str_where . "
                    ) a
                   group by a.warehouse_type_name, a.district
                   ORDER BY
                   a.pk_id, a.district";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Storage Capacity By Area 20c
     *
     * @return type
     */
    public function getStorageCapacityByArea20c() {
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
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }

        $str_qry = "SELECT
                   a.Province, a.District, a.warehouse_type_name, req20, cap20, diff20,
                   sum(IF (diff20 / req20 > 0.3, 1, 0)) AS surplus30,
                   sum(IF (diff20 / req20 <= 0.3 AND diff20 / req20 > 0.1,1,0)) AS surplus1030,
                   sum(IF (diff20 / req20 <= 0.1 AND diff20 / req20 >= - 0.1,1, 0)) AS match10,
                   sum(IF (diff20 / req20 < - 0.1 AND diff20 / req20 >= - 0.3,1,0)) AS shortage1030,
                   sum(IF (diff20 / req20 < - 0.3, 1, 0)) AS shortage30
                   FROM
                    (
                    SELECT
                     warehouse_types.warehouse_type_name,
                     IFNULL(warehouse_population.requirments_20degree, 0) AS req20,
                     IFNULL(warehouse_population.capacity_20degree, 0) AS cap20,
                     IFNULL(warehouse_population.capacity_20degree, 0) - IFNULL(warehouse_population.requirments_20degree, 0) AS diff20,
                     District.location_name AS District,
                     Province.pk_id,
                     Province.location_name AS Province
                    FROM
                        warehouses
                        INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
                        INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
                    INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                    INNER JOIN locations AS Province ON District.province_id = Province.pk_id
                        " . $str_where . "
            ) a
                   group by a.warehouse_type_name, a.District
                   ORDER BY
                   a.pk_id, a.district
                    ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Facilities Suitable For Solar Equipment By Area Report
     *
     * @return type
     */
    public function facilitiesSuitableForSolarEquipmentByAreaReport() {
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
        $where[] = "warehouses.status = 1";
        if (count($where) > 0) {
            $str_where .= " AND " . implode(" AND ", $where);
        }
    }

}
