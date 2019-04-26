<?php

/**
 * Model_Geo
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Maps
 * @author    Hannan Mehmood <hannanmehmood@yahoo.com>
 * @version    2.5.1
 */

/**
 *  Model for Geo
 */
class Model_Geo extends Model_Base {

    /**
     * Get Province Mos
     * @param type $year
     * @param type $month
     * @param type $province
     * @param type $product
     * @param type $level
     * @return type
     */
    public function getProvinceMos($year, $month, $province, $product, $level) {

        $province = ($province == 'all') ? 0 : $province;
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';

        $str_sql = "CALL REPgetData ('D', '$reporting_date', $province, $product, 1)";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get District Mos
     * @param type $year
     * @param type $month
     * @param type $province
     * @param type $product
     * @param type $level
     * @return type
     */
    public function getDistrictMos($year, $month, $province, $product, $level) {

        $province = ($province == 'all') ? 0 : $province;
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        $str_sql = "CALL REPgetData ('D', '$reporting_date', $province, $product, 1)";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Tehsil Mos
     * @param type $year
     * @param type $month
     * @param type $province
     * @param type $district
     * @param type $product
     * @return type
     */
    public function getTehsilMos($year, $month, $province, $district, $product) {
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        if ($district == 'all') {
            $str_sql = "CALL REPgetData ('TP', '$reporting_date', $province, $product, 1)";
        } else {
            $str_sql = "CALL REPgetData ('TD', '$reporting_date', $district, $product, 1)";
        }
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Amc Map Data
     * @param type $year
     * @param type $month
     * @param type $product
     * @param type $province
     * @param type $type
     * @return type
     */
    public function getAmcMapData($year, $month, $product, $province, $type) {
        $province = ($province == 'all') ? 0 : $province;
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        $str_sql = "CALL REPgetData ('D', '$reporting_date', $province, $product, 1)";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Amc Tehsil Map Data
     * @param type $year
     * @param type $month
     * @param type $province
     * @param type $district
     * @param type $product
     * @param type $amctype
     * @return type
     */
    public function getAmcTehsilMapData($year, $month, $province, $district, $product, $amctype) {
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        if ($district == 'all' || empty($district)) {
            $str_sql = "CALL REPgetData ('TP', '$reporting_date', $province, $product, 1)";
        } else {
            $str_sql = "CALL REPgetData ('TD', '$reporting_date', $district, $product, 1)";
        }

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Reporting District
     * @param type $year
     * @param string $month
     * @param type $province
     * @return type
     */
    public function getReportingDistrict($year, $month, $province) {

        if ($month < 10) {
            $month = "0" . $month;
        }

        $str_date = $year . "-" . $month;

        if ($province == 'all') {
            $prov_filter = "";
        } else {
            $prov_filter = "AND District.province_id = $province";
        }

        $str_qry = "SELECT
                        A.districtId AS district_id,
                        A.districtName AS district_name,
                        A.totalWH AS total_warehouse,
                        IFNULL(B.reported, 0) AS reported,
                        ROUND((IFNULL(B.reported, 0) / A.totalWH) * 100
                        ) AS reporting_rate
                    FROM
                        (
                        SELECT
                            District.pk_id AS districtId,
                            District.location_name AS districtName,
                            COUNT(DISTINCT UC.pk_id) AS totalWH
                        FROM
                            locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                            stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses.status = 1
                        $prov_filter
                        GROUP BY
                            District.pk_id
                        ORDER BY
                            districtId ASC
                        ) A
                    LEFT JOIN (
                        SELECT
                            District.pk_id AS districtId,
                            District.location_name AS districtName,
                            COUNT(DISTINCT UC.pk_id) AS reported
                        FROM
                            locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                            stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses.status = 1
                        $prov_filter
                        AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m') = '" . $str_date . "'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        GROUP BY
                            District.pk_id
                        ORDER BY
                            districtId ASC
                    ) B ON A.districtId = B.districtId";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Reporting Tehsil
     * @param type $year
     * @param string $month
     * @param type $province
     * @param type $district
     * @return type
     */
    public function getReportingTehsil($year, $month, $province, $district) {

        if ($month < 10) {
            $month = "0" . $month;
        }

        $str_date = $year . "-" . $month;

        if ($district != "all") {
            $location = "AND District.district_id = " . $district;
        } else {
            $location = "AND District.province_id = " . $province;
        }

        $str_qry = "SELECT
                        A.districtId AS tehsil_id,
                        A.districtName AS tehsil_name,
                        A.totalWH AS total_warehouse,
                        IFNULL(B.reported, 0) AS reported,
                        ROUND((IFNULL(B.reported, 0) / A.totalWH) * 100) AS reporting_rate
                    FROM
                        (
                        SELECT
                            District.pk_id AS districtId,
                            District.location_name AS districtName,
                            COUNT(DISTINCT UC.pk_id) AS totalWH
                        FROM
                            locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.parent_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                            stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses.status = 1
                        $location
                        GROUP BY
                            District.pk_id
                        ORDER BY
                            districtId ASC
                        ) A
                    LEFT JOIN (
                        SELECT
                            District.pk_id AS districtId,
                            District.location_name AS districtName,
                            COUNT(DISTINCT UC.pk_id) AS reported
                        FROM
                            locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.parent_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                            stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses.status = 1
                        $location
                        AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m') = '" . $str_date . "'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        GROUP BY
                            District.pk_id
                        ORDER BY
                            districtId ASC
                    ) B ON A.districtId = B.districtId";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Wastages District
     * @param type $year
     * @param string $month
     * @param type $province
     * @param type $product
     * @return type
     */
    public function getWastagesDistrict($year, $month, $province, $product) {
        $query = "SELECT
                        item_pack_sizes.wastage_rate_allowed
                        FROM
                        item_pack_sizes
                        WHERE
                        item_pack_sizes.pk_id = " . $product;

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        while ($data = $row->fetch()) {
            $limit = $data['wastage_rate_allowed'];
        }

        if ($month < 10) {
            $month = "0" . $month;
        }
        $str_date = $year . "-" . $month;

        if ($province == 'all') {

            $query = "SELECT
                            E.districtId,
                            E.districtName,
                            E.TotalWH,
                            COALESCE (D.reported, NULL, 0) AS reported,
                            COALESCE (D.wastages, NULL, 0) AS wastages,
                            COALESCE(ROUND((COALESCE((D.wastages), NULL, 0) / (D.reported)) *  100, 1),null,0) AS wastages_rate
                    FROM
                            (
                                    SELECT
                                            B.districtId,
                                            B.districtName,
                                            B.reported,
                                            COALESCE (A.UCCOunt, NULL, 0) AS wastages
                                    FROM
                                            (
                                                 SELECT
                                                                COUNT(A.district_id) AS UCCOunt,
                                                                A.district_id AS districtId
                                                        FROM
                                                                (
                                                                        SELECT
                                                                                UC.district_id,
                                                                                UC.location_name AS districtName,
                                                                                ROUND(
                                                                                        IFNULL(
                                                                                                (
                                                                                                        sum(hf_data_master.wastages) / (
                                                                                                                sum(
                                                                                                                        hf_data_master.issue_balance
                                                                                                                ) + sum(hf_data_master.wastages)
                                                                                                        )
                                                                                                ) * 100,
                                                                                                0
                                                                                        ),
                                                                                        1
                                                                                ) AS wastages_rate
                                                                        FROM
                                                                                locations AS UC
                                                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                                        WHERE
                                                                                UC.geo_level_id = 6
                                                                        AND warehouses.stakeholder_id = 1
                                                                        AND warehouses. STATUS = 1
                                                                        AND DATE_FORMAT(
                                                                                hf_data_master.reporting_start_date,
                                                                                '%Y-%m'
                                                                        ) = '" . $str_date . "'
                                                                        AND hf_data_master.issue_balance IS NOT NULL
                                                                        AND hf_data_master.issue_balance != 0
                                                                        AND hf_data_master.item_pack_size_id = $product
                                                                        GROUP BY
                                                                                UC.pk_id
                                                                ) A
                                                        WHERE
                                                                A.wastages_rate > $limit
                                                        GROUP BY
                                                                A.district_id
                                            ) A
                                    RIGHT JOIN (
                                            SELECT
                                                    District.pk_id AS districtId,
                                                    District.location_name AS districtName,
                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                            FROM
                                                    locations AS District
                                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND warehouses.stakeholder_id = 1
                                            AND warehouses. STATUS = 1
                                            AND DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%Y-%m'
                                            ) = '" . $str_date . "'
                                            AND hf_data_master.issue_balance IS NOT NULL
                                            AND hf_data_master.issue_balance != 0
                                            GROUP BY
                                                    District.pk_id
                                            ORDER BY
                                                    districtId ASC
                                    ) B ON A.districtId = B.districtId
                            ) D
                    RIGHT JOIN (
                            SELECT
                                    District.pk_id AS districtId,
                                    District.location_name AS districtName,
                                    COUNT(DISTINCT UC.pk_id) AS totalWH
                            FROM
                                    locations AS District
                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 6
                            AND warehouses.stakeholder_id = 1
                            AND warehouses.status = 1
                            GROUP BY
                                    District.pk_id
                            ORDER BY
                                    districtId ASC
                    ) E ON E.districtId = D.districtId
                  ";
        } else {
            $query = "SELECT
                            E.districtId,
                            E.districtName,
                            E.TotalWH,
                            COALESCE (D.reported, NULL, 0) AS reported,
                            COALESCE (D.wastages, NULL, 0) AS wastages,
                            COALESCE(ROUND((COALESCE((D.wastages), NULL, 0) / (D.reported)) *  100, 1),null,0) AS wastages_rate
                    FROM
                            (
                                    SELECT
                                            B.districtId,
                                            B.districtName,
                                            B.reported,
                                            COALESCE (A.UCCOunt, NULL, 0) AS wastages
                                    FROM
                                            (
                                                 SELECT
                                                                COUNT(A.district_id) AS UCCOunt,
                                                                A.district_id AS districtId
                                                        FROM
                                                                (
                                                                        SELECT
                                                                                UC.district_id,
                                                                                UC.location_name AS districtName,
                                                                                ROUND(
                                                                                        IFNULL(
                                                                                                (
                                                                                                        sum(hf_data_master.wastages) / (
                                                                                                                sum(
                                                                                                                        hf_data_master.issue_balance
                                                                                                                ) + sum(hf_data_master.wastages)
                                                                                                        )
                                                                                                ) * 100,
                                                                                                0
                                                                                        ),
                                                                                        1
                                                                                ) AS wastages_rate
                                                                        FROM
                                                                                locations AS UC
                                                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                                        WHERE
                                                                                UC.geo_level_id = 6
                                                                        AND warehouses.stakeholder_id = 1
                                                                        AND warehouses. STATUS = 1
                                                                        AND DATE_FORMAT(
                                                                                hf_data_master.reporting_start_date,
                                                                                '%Y-%m'
                                                                        ) = '" . $str_date . "'
                                                                        AND hf_data_master.issue_balance IS NOT NULL
                                                                        AND hf_data_master.issue_balance != 0
                                                                        AND warehouses.province_id = $province
                                                                        AND hf_data_master.item_pack_size_id = $product
                                                                        GROUP BY
                                                                                UC.pk_id
                                                                ) A
                                                        WHERE
                                                                A.wastages_rate > $limit
                                                        GROUP BY
                                                                A.district_id
                                            ) A
                                    RIGHT JOIN (
                                            SELECT
                                                    District.pk_id AS districtId,
                                                    District.location_name AS districtName,
                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                            FROM
                                                    locations AS District
                                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND warehouses.stakeholder_id = 1
                                            AND warehouses. STATUS = 1
                                            AND District.province_id = $province
                                            AND DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%Y-%m'
                                            ) = '" . $str_date . "'
                                            AND hf_data_master.issue_balance IS NOT NULL
                                            AND hf_data_master.issue_balance != 0
                                            GROUP BY
                                                    District.pk_id
                                            ORDER BY
                                                    districtId ASC
                                    ) B ON A.districtId = B.districtId
                            ) D
                    RIGHT JOIN (
                            SELECT
                                    District.pk_id AS districtId,
                                    District.location_name AS districtName,
                                    COUNT(DISTINCT UC.pk_id) AS totalWH
                            FROM
                                    locations AS District
                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 6
                            AND warehouses.stakeholder_id = 1
                            AND warehouses.status = 1
                            AND District.province_id = $province
                            GROUP BY
                                    District.pk_id
                            ORDER BY
                                    districtId ASC
                    ) E ON E.districtId = D.districtId";
        }

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Wastages Tehsil
     * @param type $year
     * @param string $month
     * @param type $province
     * @param type $district
     * @param type $product
     * @return type
     */
    public function getWastagesTehsil($year, $month, $province, $district, $product) {

        $query = "SELECT
                        item_pack_sizes.wastage_rate_allowed
                        FROM
                        item_pack_sizes
                        WHERE
                        item_pack_sizes.pk_id = " . $product;

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        while ($data = $row->fetch()) {
            $limit = $data['wastage_rate_allowed'];
        }


        if ($month < 10) {
            $month = "0" . $month;
        }

        $str_date = $year . "-" . $month;

        if ($district != "all") {
            $location = "AND warehouses.district_id = " . $district;
        } else {
            $location = "AND warehouses.province_id = " . $province;
        }




        $query = "SELECT
                            E.districtId AS tehsil_id,
                            E.districtName AS tehsil_name,
                            E.TotalWH,
                            COALESCE (D.reported, NULL, 0) AS reported,
                            COALESCE (D.wastages, NULL, 0) AS wastages,
                            COALESCE(ROUND((COALESCE((D.wastages), NULL, 0) / (D.reported)) *  100, 1),null,0) AS wastages_rate
                    FROM
                            (
                                    SELECT
                                            B.districtId,
                                            B.districtName,
                                            B.reported,
                                            COALESCE (A.UCCOunt, NULL, 0) AS wastages
                                    FROM
                                            (
                                                 SELECT
                                                                COUNT(A.parent_id) AS UCCOunt,
                                                                A.parent_id AS districtId
                                                        FROM
                                                                (
                                                                        SELECT
                                                                                UC.district_id,
                                                                                UC.parent_id,
                                                                                UC.location_name AS districtName,
                                                                                ROUND(
                                                                                        IFNULL(
                                                                                                (
                                                                                                        sum(hf_data_master.wastages) / (
                                                                                                                sum(
                                                                                                                        hf_data_master.issue_balance
                                                                                                                ) + sum(hf_data_master.wastages)
                                                                                                        )
                                                                                                ) * 100,
                                                                                                0
                                                                                        ),
                                                                                        1
                                                                                ) AS wastages_rate
                                                                        FROM
                                                                                locations AS UC
                                                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                                        WHERE
                                                                                UC.geo_level_id = 6
                                                                        AND warehouses.stakeholder_id = 1
                                                                        AND warehouses. STATUS = 1
                                                                        AND DATE_FORMAT(
                                                                                hf_data_master.reporting_start_date,
                                                                                '%Y-%m'
                                                                        ) = '" . $str_date . "'
                                                                        AND hf_data_master.issue_balance IS NOT NULL
                                                                        AND hf_data_master.issue_balance != 0
                                                                        $location
                                                                        AND hf_data_master.item_pack_size_id = $product
                                                                        GROUP BY
                                                                                UC.pk_id
                                                                ) A
                                                        WHERE
                                                                A.wastages_rate > $limit
                                                        GROUP BY
                                                                A.parent_id
                                            ) A
                                    RIGHT JOIN (
                                            SELECT
                                                    District.pk_id AS districtId,
                                                    District.location_name AS districtName,
                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                            FROM
                                                    locations AS District
                                            INNER JOIN locations AS UC ON District.pk_id = UC.parent_id
                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND warehouses.stakeholder_id = 1
                                            AND warehouses. STATUS = 1
                                            $location
                                            AND DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%Y-%m'
                                            ) = '" . $str_date . "'
                                            AND hf_data_master.issue_balance IS NOT NULL
                                            AND hf_data_master.issue_balance != 0
                                            GROUP BY
                                                    District.pk_id
                                            ORDER BY
                                                    districtId ASC
                                    ) B ON A.districtId = B.districtId
                            ) D
                    RIGHT JOIN (
                            SELECT
                                                District.pk_id AS districtId,
                                                District.location_name AS districtName,
                                                COUNT(DISTINCT UC.pk_id) AS totalWH
                                        FROM
                                                locations AS District
                                        INNER JOIN locations AS UC ON District.pk_id = UC.parent_id
                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                        WHERE
                                                stakeholders.geo_level_id = 6
                                        AND warehouses.stakeholder_id = 1
                                        AND warehouses. STATUS = 1
                                       $location
                                        GROUP BY
                                                District.pk_id
                                        ORDER BY
                                                districtId ASC
                    ) E ON E.districtId = D.districtId";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Expiry District
     */
    public function getExpiryDistrict($province, $product) {

        if ($province == "all") {
            $provFilter = "";
        } else {
            $provFilter = "AND warehouses.province_id=" . $province;
        }

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        $year = $date->get(Zend_Date::YEAR);
        $month = $date->get(Zend_Date::MONTH);
        $str_date = $year . "-" . $month . "-" . $day;


        if ($year . "-" . $month >= '2015-05') {
            $filter = "";
        } else {
            $filter = "AND DATE_FORMAT(warehouses.starting_on,'%Y-%m') IS NULL";
        }

        $query = "SELECT
                        B.province_id,
                        B.district_id,
                        B.district_name,
                        COALESCE (A.quantity, NULL, 0) AS quantity,
                        COALESCE (A.ExpiringIn6Months, NULL, 0) AS ExpiringIn6Months,
                        COALESCE (
                                round(
                                        (
                                                A.ExpiringIn6Months / A.quantity * 100
                                        ),
                                        0
                                ),
                                NULL,
                                0
                        ) AS expiry_rate
                FROM
                        (
                                SELECT
                                        locations.pk_id AS district_id,
                                        locations.location_name AS district_name,
                                        stakeholder_item_pack_sizes.item_pack_size_id AS item_id,
                                        Sum(

                                                IF (
                                                        stock_batch.expiry_date >= '" . $str_date . "',
                                                        (stock_batch_warehouses.quantity),
                                                        0
                                                )
                                        ) AS quantity,
                                        Sum(

                                                IF (
                                                        stock_batch.expiry_date >= '" . $str_date . "' AND stock_batch.expiry_date <= ADDDATE(
                                                                '" . $str_date . "',
                                                                INTERVAL 6 MONTH
                                                        ),
                                                        (stock_batch_warehouses.quantity),
                                                        0
                                                )
                                        ) AS ExpiringIn6Months,
                                        Sum(

                                                IF (
                                                        (
                                                                stock_batch.expiry_date > ADDDATE(
                                                                        '" . $str_date . "',
                                                                        INTERVAL 6 MONTH
                                                                ) AND stock_batch.expiry_date <= ADDDATE(
                                                                        '" . $str_date . "',
                                                                        INTERVAL 13 MONTH
                                                                )
                                                        ),
                                                        (stock_batch_warehouses.quantity),
                                                        0
                                                )
                                        ) AS ExpiringIn12Months,
                                        Sum(

                                                IF (
                                                        stock_batch.expiry_date > ADDDATE(
                                                                '" . $str_date . "',
                                                                INTERVAL 13 MONTH
                                                        ),
                                                        (stock_batch_warehouses.quantity),
                                                        0
                                                )
                                        ) AS ExpiringIn13Months
                                FROM
                                        stock_batch
                                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                                INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                                INNER JOIN locations ON warehouses.district_id = locations.pk_id
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                WHERE
                                        stakeholders.geo_level_id = 4
                                AND warehouses.status = 1
                                AND stock_batch_warehouses.status <> 'Finished'
                                AND stakeholder_item_pack_sizes.item_pack_size_id = '" . $product . "'
                                GROUP BY
                                        locations.district_id
                        ) A
                RIGHT JOIN (
                        SELECT
                                District.pk_id AS district_id,
                                District.location_name AS district_name,
                                District.province_id
                        FROM
                                locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                                stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses.status = 1
                        $provFilter
                        $filter
                        GROUP BY
                                District.pk_id
                        ORDER BY
                                district_id ASC
                ) B ON A.district_id = B.district_id
                ORDER BY
                    B.province_id,
                    B.district_name";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Expiry Tehsil
     */
    public function getExpiryTehsil($province, $district, $product) {

        if ($district != "all") {
            $location = "AND District.district_id = " . $district;
        } else {
            $location = "AND District.province_id = " . $province;
        }

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        $year = $date->get(Zend_Date::YEAR);
        $month = $date->get(Zend_Date::MONTH);
        $str_date = $year . "-" . $month . "-" . $day;


        $query = "SELECT
                                B.tehsil_id,
                                B.tehsil_name,
                                COALESCE (A.quantity, NULL, 0) AS quantity,
                                COALESCE (A.ExpiringIn6Months, NULL, 0) AS ExpiringIn6Months,
                                COALESCE (
                                        round(
                                                (
                                                        A.ExpiringIn6Months / A.quantity * 100
                                                ),
                                                0
                                        ),
                                        NULL,
                                        0
                                ) AS expiry_rate
                        FROM
                                (
                                        SELECT DISTINCT
                                                teh.pk_id AS tehsil_id,
                                                teh.location_name AS tehsil_name,
                                                Sum(

                                                        IF (
                                                                stock_batch.expiry_date >= '" . $str_date . "',
                                                                (stock_batch_warehouses.quantity),
                                                                0
                                                        )
                                                ) AS quantity,
                                                Sum(

                                                        IF (
                                                                stock_batch.expiry_date >= '" . $str_date . "' && stock_batch.expiry_date <= ADDDATE(
                                                                        '" . $str_date . "',
                                                                        INTERVAL 6 MONTH
                                                                ),
                                                                (stock_batch_warehouses.quantity),
                                                                0
                                                        )
                                                ) AS ExpiringIn6Months,
                                                Sum(

                                                        IF (
                                                                (
                                                                        stock_batch.expiry_date > ADDDATE(
                                                                                '" . $str_date . "',
                                                                                INTERVAL 6 MONTH
                                                                        ) && stock_batch.expiry_date <= ADDDATE(
                                                                                '" . $str_date . "',
                                                                                INTERVAL 13 MONTH
                                                                        )
                                                                ),
                                                                (stock_batch_warehouses.quantity),
                                                                0
                                                        )
                                                ) AS ExpiringIn12Months,
                                                Sum(

                                                        IF (
                                                                stock_batch.expiry_date > ADDDATE(
                                                                        '" . $str_date . "',
                                                                        INTERVAL 13 MONTH
                                                                ),
                                                                (stock_batch_warehouses.quantity),
                                                                0
                                                        )
                                                ) AS ExpiringIn13Months
                                        FROM
                                                locations AS teh
                                        INNER JOIN locations AS uc ON uc.parent_id = teh.pk_id
                                        INNER JOIN warehouses ON uc.pk_id = warehouses.location_id
                                      
                                        INNER JOIN stock_batch_warehouses ON warehouses.pk_id = stock_batch_warehouses.warehouse_id
                                        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id                                        


                                        WHERE
                                                teh.geo_level_id = 5
                                        AND warehouses. STATUS = 1
                                        AND stock_batch_warehouses. STATUS <> 'Finished'
                                        AND stakeholder_item_pack_sizes.item_pack_size_id = '" . $product . "'
                                        GROUP BY
                                                teh.pk_id
                                ) A
                        RIGHT JOIN (
                                SELECT
                                        District.pk_id AS tehsil_id,
                                        District.location_name AS tehsil_name
                                FROM
                                        locations AS District
                                INNER JOIN locations AS UC ON District.pk_id = UC.parent_id
                                INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                WHERE
                                        stakeholders.geo_level_id = 6
                                AND warehouses.stakeholder_id = 1
                                AND warehouses. STATUS = 1
                                $location
                                GROUP BY
                                        District.pk_id
                                ORDER BY
                                        tehsil_id ASC
                        ) B ON A.tehsil_id = B.tehsil_id";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Vaccine Coverage
     */
    public function getVaccineCoverage($year, $month, $province, $product) {

        if ($province == "all") {
            $prov_filter = "";
        } else {
            $prov_filter = " AND warehouses.province_id = " . $province;
        }
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';

        $query = "SELECT
                    A.district_id,
                    A.district_name,
                    A.consumption,
                    A.target AS population,
                    ROUND((A.consumption / A.target) * 100, 2) AS vaccine_coverage
                FROM
                    (
                    SELECT
                        warehouses.district_id AS district_id,
                        locations.location_name AS district_name,
                        SUM(hf_data_master.issue_balance) AS consumption,
                        ROUND(
                                (
                                    (
                                        (
                                            (
                                                location_populations.population / 100
                                            ) * items.population_percent_increase_per_year
                                        ) / 100 * items.child_surviving_percent_per_year
                                    ) * items.doses_per_year
                                ) / 12
                        ) AS target
                    FROM
                         warehouses
                    INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN locations ON locations.pk_id = warehouses.district_id
                    INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                    INNER JOIN location_populations ON warehouses.district_id = location_populations.location_id
                    INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
                    WHERE
                        stakeholders.geo_level_id = 6
                    AND warehouses.stakeholder_id = 1
                    AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '$reporting_date'
                    AND hf_data_master.item_pack_size_id = $product
                    AND YEAR (location_populations.estimation_date) = YEAR ('$reporting_date')
                    $prov_filter
                    GROUP BY
                        warehouses.district_id,
                        Reporting_start_date
                    ORDER BY
                        warehouses.district_id,
                        Reporting_start_date DESC
                    ) A";
        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Vaccine Coverage Tehsil
     */
    public function getVaccineCoverageTehsil($year, $month, $province, $district, $product) {

        if ($district != "all") {
            $district_filter = " AND warehouses.district_id =" . $district;
        } else {
            $district_filter = "";
        }

        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        $query = "SELECT
                    A.tehsil_id,
                    A.tehsil_name,
                    SUM(A.consumption) AS consumption,
                    SUM(A.target) AS target,
                    SUM(A.population) AS population,
                    ROUND(SUM(A.consumption) / SUM(A.target) * 100, 2) AS vaccine_coverage
                FROM
                    (
                    SELECT
                        Tehsil.pk_id AS tehsil_id,
                        Tehsil.location_name AS tehsil_name,
                        warehouses.location_id,
                        locations.location_name,
                        SUM(hf_data_master.issue_balance) AS consumption,
                        location_populations.population,
                        ROUND(
                                (
                                    (
                                        (
                                            (
                                                (
                                                    location_populations.population / 100
                                                ) * items.population_percent_increase_per_year
                                            ) / 100 * items.child_surviving_percent_per_year
                                        ) * items.doses_per_year
                                    ) / 12
                                )
                        ) AS target
                    FROM
                        warehouses
                    INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN locations ON locations.pk_id = warehouses.location_id
                    INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                    INNER JOIN location_populations ON location_populations.location_id = locations.pk_id
                    INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
                    INNER JOIN locations AS Tehsil ON locations.parent_id = Tehsil.pk_id
                    WHERE
                        stakeholders.geo_level_id >= 6
                    AND warehouses.stakeholder_id = 1
                    AND DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '$reporting_date'
                    AND hf_data_master.item_pack_size_id = $product
                    $district_filter
                    AND YEAR (location_populations.estimation_date) = YEAR ('$reporting_date')
                    GROUP BY
                        locations.pk_id
                    ) A
                GROUP BY
                    A.tehsil_id";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Cold Chain Province
     */
    public function getColdChainProvince($type, $province = "") {
        $where = $where1 = '';
        if (!empty($province) && $province != 'all') {
            $where = "AND locations.province_id = $province";
            $where1 = "AND warehouses.province_id = $province";
        }
        if ($type == 1) {
            $col_name = "IF (
                (Difference / Required) > 0.3,
                1,
                0
        ) AS capacity "; // surplus30
        } else if ($type == 2) {
            $col_name = "IF (
                (Difference / Required) <= 0.3
                AND (Difference / Required) > 0.1,
                1,
                0
        ) AS capacity"; // surplus1030
        } else if ($type == 3) {
            $col_name = " IF (
                (Difference / Required) <= 0.1
                AND (Difference / Required) >= - 0.1,
                1,
                0
        ) AS capacity"; //match10
        } else if ($type == 4) {
            $col_name = "IF (
                (Difference / Required) < - 0.1
                AND (Difference / Required) >= - 0.3,
                1,
                0
        ) AS capacity"; //shortage1030
        } else if ($type == 5) {
            $col_name = "IF (
                (Difference / Required) < - 0.3,
                1,
                0
        ) AS capacity"; //shortage30
        }

        $query = "SELECT D.FacilityName,
D.Province,
D.district_name,
D.district_id,
D.FacilityType,
D.Required,
D.ActualCap,
D.Difference,
SUM(D.capacity) as capacity
 FROM (SELECT DISTINCT
	a.FacilityName,
	a.Province,
	a.District as district_name,
        a.DistrictID as district_id,
	a.FacilityType,
	Required,
	ActualCap,
	Difference,
        $col_name
        FROM
	(
		SELECT DISTINCT
			Province.pk_id,
			Province.location_name AS Province,
			locations.location_name AS District,
                        locations.pk_id AS DistrictID,
			warehouses.warehouse_name AS FacilityName,
			warehouse_types.warehouse_type_name AS FacilityType,
			IFNULL(
				warehouse_population.capacity_4degree,
				0
			) AS ActualCap,
			IFNULL(
				warehouse_population.requirments_4degree,
				0
			) AS Required,
			IFNULL(
				warehouse_population.capacity_4degree,
				0
			) - IFNULL(
				warehouse_population.requirments_4degree,
				0
			) AS Difference
		FROM
			warehouses
		INNER JOIN warehouse_types ON warehouses.warehouse_type_id = warehouse_types.pk_id
		INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
		INNER JOIN locations ON warehouses.district_id = locations.pk_id
		INNER JOIN locations AS Province ON warehouses.province_id = Province.pk_id
	
		 $where1
		
		AND warehouses. STATUS = 1
                AND YEAR(warehouse_population.estimation_year) = '0000'
               
                ) a
                
                ORDER BY
                a.pk_id,
                a.District,
                a.FacilityName )D GROUP BY district_id";

//        $query = "SELECT
//                            B.district_id,
//                            B.district_name,
//                            COALESCE (ROUND(A.capacity), NULL, 0) AS capacity
//                    FROM
//                            (
//                                    SELECT
//                                            warehouses.district_id AS district_id,
//                                            ROUND(
//                                                    SUM(ccm_models.net_capacity_4),
//                                                    2
//                                            ) AS capacity
//                                    FROM
//                                            cold_chain
//                                    INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
//                                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
//                                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
//                                    INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
//                                    WHERE
//                                            ccm_asset_types.parent_id = " . $type . "
//                                    $where1            
//                                    AND warehouses.status = 1
//                                    GROUP BY
//                                            warehouses.district_id
//                            ) A
//                    RIGHT JOIN (
//                            SELECT DISTINCT
//                                    warehouses.district_id,
//                                    locations.location_name AS district_name
//                            FROM
//                                    warehouses
//                            INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
//                            INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
//                            INNER JOIN locations ON warehouses.district_id = locations.pk_id
//                            WHERE
//                                    stakeholders.geo_level_id = 4
//                            $where        
//                            AND warehouses.status = 1
//                            AND DATE_FORMAT(
//                                    warehouses.starting_on,
//                                    '%Y-%m'
//                            ) IS NULL
//                            ORDER BY
//                                    district_id ASC
//                    ) B ON A.district_id = B.district_id";
//echo $query;
        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Cold Chain District
     */
    public function getColdChainDistrict($province, $type) {
        if ($province == "all") {
            $provFilter = "";
        } else {
            $provFilter = "AND warehouses.province_id =" . $province;
        }



        $query = "SELECT
                            B.district_id,
                            B.district_name,
                            COALESCE (ROUND(A.capacity), NULL, 0) AS capacity
                    FROM
                            (
                                    SELECT
                                            warehouses.district_id AS district_id,
                                            ROUND(
                                                    SUM(ccm_models.net_capacity_4),
                                                    2
                                            ) AS capacity
                                    FROM
                                            cold_chain
                                    INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                    INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                                    WHERE
                                            ccm_asset_types.parent_id = " . $type . "
                                    AND warehouses. STATUS = 1
                                    $provFilter
                                    GROUP BY
                                            warehouses.district_id
                            ) A
                    RIGHT JOIN (
                            SELECT DISTINCT
                                    warehouses.district_id,
                                    locations.location_name AS district_name
                            FROM
                                    warehouses
                            INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
                            INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                            INNER JOIN locations ON warehouses.district_id = locations.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 4
                            AND warehouses. STATUS = 1
                            $provFilter
                            AND DATE_FORMAT(
                                    warehouses.starting_on,
                                    '%Y-%m'
                            ) IS NULL
                            ORDER BY
                                    district_id ASC
                    ) B ON A.district_id = B.district_id";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Cold Chain Tehsil
     */
    public function getColdChainTehsil($province, $district, $type) {

        if ($district != "all") {
            $location = "AND warehouses.district_id = " . $district;
        } else {
            $location = "AND warehouses.province_id = " . $province;
        }

        $query = "SELECT
                            B.tehsil_id,
                            B.tehsil_name,
                            COALESCE(ROUND(A.capacity), NULL, 0) AS capacity
                    FROM
                            (
                                 SELECT
                                            teh.pk_id AS tehsil_id,
                                            teh.location_name AS tehsil_name,
                                            ROUND(
                                                    SUM(ccm_models.net_capacity_4),
                                                    2
                                            ) AS capacity
                                    FROM
                                            cold_chain
                                    INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                                    INNER JOIN locations ON locations.pk_id = warehouses.location_id
                                    INNER JOIN locations teh ON locations.parent_id = teh.pk_id
                                    INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                                    WHERE
                                            ccm_asset_types.parent_id = " . $type . "
                                    AND warehouses.STATUS = 1
                                    AND teh.geo_level_id = 5
                                    GROUP BY
                                            teh.pk_id
                            ) A
                    RIGHT JOIN (
                           SELECT
                                    District.pk_id AS tehsil_id,
                                    District.location_name AS tehsil_name
                            FROM
                                    locations AS District
                            INNER JOIN locations AS UC ON District.pk_id = UC.parent_id
                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 6
                            AND warehouses.stakeholder_id = 1
                            AND warehouses. STATUS = 1
                            $location
                            GROUP BY
                                    District.pk_id
                            ORDER BY
                                    tehsil_id ASC
                    ) B ON A.tehsil_id = B.tehsil_id";


        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Color Classes
     */
    public function getColorClasses($id) {
        $str_sql = "SELECT
                            geo_indicator_values.geo_indicator_id,
                            geo_indicator_values.start_value,
                            geo_indicator_values.end_value,
                            geo_indicator_values.`interval`,
                            geo_indicator_values.description,
                            geo_color.color_code
                            FROM
                            geo_indicators
                            INNER JOIN geo_indicator_values ON geo_indicators.pk_id = geo_indicator_values.geo_indicator_id
                            INNER JOIN geo_color ON geo_indicator_values.geo_color_id = geo_color.pk_id
                        WHERE geo_indicators.pk_id = " . $id;


        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Acceptable Wastages
     */
    public function getAcceptableWastages($product) {

        $query = "SELECT
                        item_pack_sizes.wastage_rate_allowed
                        FROM
                        item_pack_sizes
                        WHERE
                        item_pack_sizes.pk_id = " . $product;

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Month Range
     */
    public function getMonthRange($id) {

        $query = "SELECT
                        period.pk_id,
                        period.period_name,
                        period.period_code,
                        period.is_month,
                        period.begin_month,
                        period.end_month,
                        period.month_count
                        FROM
                        period
                        WHERE
                        period.pk_id =" . $id;

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Batch Map Data
     */
    public function getBatchMapData($batch) {

        $query = "SELECT DISTINCT
                            map_district_mapping.mapping_id AS district_id,
                            map_district_mapping.district_name,
                            stock_batch.number AS batch_number,
                            SUM(stock_batch_warehouses.quantity) AS quantity,
                            stakeholder_item_pack_sizes.item_pack_size_id,
                            stock_batch_warehouses.`status`,
                            DATE_FORMAT(
                                    stock_batch.expiry_date,
                                    '%Y-%m-%d'
                            ) AS expiry_date
                    FROM
                    stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                    INNER JOIN map_district_mapping ON warehouses.district_id = map_district_mapping.district_id
                    WHERE
                    stock_batch.number = '" . $batch . "'
                    AND warehouses. STATUS = 1
                    AND stock_batch_warehouses.`status` = 'Running'
                    GROUP BY
                            map_district_mapping.mapping_id
                    ORDER BY
                            map_district_mapping.mapping_id";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Reporting Rate Trend
     */
    public function getReportingRateTrend($year, $month, $district) {

        if ($month <= 9) {
            $month = "0" . $month;
        }
        $day = $year . "-" . $month;

        $first_month = date('Y-m', strtotime("-4 month", strtotime($day)));
        $second_month = date('Y-m', strtotime("-3 month", strtotime($day)));
        $third_month = date('Y-m', strtotime("-2 month", strtotime($day)));
        $fourth_month = date('Y-m', strtotime("-1 month", strtotime($day)));

        $query = "SELECT B.MONTH AS label,COALESCE(A.value,null,0) AS value FROM 
                (SELECT SUBSTRING('JAN FEB MAR APR MAY JUN JUL AUG SEP OCT NOV DEC ', (DATE_FORMAT(A.reporting_start_date,'%m') * 4) - 3, 3) AS label,ROUND(sum(coalesce(A.reported,null,0))/sum(coalesce(A.total_warehouse,null,0))*100) as value,DATE_FORMAT(A.reporting_start_date,'%Y-%m') AS MONTH FROM
                            (SELECT
                                    B.district_id,
                                    B.districtName as district_name,
                                                                                                                                                TotalWH as total_warehouse,
                                    reported as reported,
                                                                                                                                          A.reporting_start_date
                                   FROM
                                    (SELECT
                                      District.pk_id AS districtId,
                                      District.location_name AS districtName,
                                      COUNT(DISTINCT UC.pk_id) AS reported,
                                                                                                                                                        hf_data_master.reporting_start_date
                                     FROM
                                      locations AS District
                                     INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                     INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                     INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                     WHERE
                                      UC.geo_level_id = 6
                                      and warehouses.status = 1
                                    AND warehouses.stakeholder_id = 1
                                    AND DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') BETWEEN '" . $first_month . "' AND '" . $day . "'
                                    AND hf_data_master.issue_balance IS NOT NULL
                                    AND hf_data_master.issue_balance != 0
                                     GROUP BY
                                      District.pk_id,
                                      hf_data_master.reporting_start_date
                                    ) AS A
                                   RIGHT JOIN (
                                   SELECT   
                                                                                                                                                 warehouses.district_id,
                                     locations.location_name AS districtName,
                                                                                                                                                 COUNT(DISTINCT UC.pk_id) AS TotalWH
                                    FROM
                                     locations AS UC
                                    INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                    INNER JOIN locations ON warehouses.district_id = locations.pk_id
                                    WHERE
                                     UC.geo_level_id = 6
                                    AND warehouses.stakeholder_id = 1
                                    AND warehouses.status = 1
                                    GROUP BY
                                     warehouses.district_id
                                   ) AS B ON A.districtId = B.district_id) A
                                INNER JOIN map_district_mapping ON map_district_mapping.district_id = A.district_id
                                AND map_district_mapping.mapping_id =  '" . $district . "'
                                GROUP BY map_district_mapping.mapping_id,
                                A.reporting_start_date) A
                                RIGHT JOIN (
                                 SELECT '$first_month' AS MONTH UNION
                                 SELECT '$second_month' AS MONTH UNION  
                                 SELECT '$third_month' AS MONTH UNION
                                 SELECT '$fourth_month' AS MONTH UNION
                                 SELECT '$day' AS MONTH    
                                    ) B ON A.MONTH = B.MONTH";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Wastages Ucs List
     */
    public function getWastagesUcsList($year, $month, $district, $province, $tehsil, $product, $limit) {

        if ($month < 10) {
            $month = "0" . $month;
        }

        if ($tehsil != "") {
            $filter = "AND UC.parent_id =" . $tehsil;
        } else {
            $filter = "AND warehouses.district_id = " . $district;
        }

        $str_date = $year . "-" . $month;

        $query = "SELECT
                        A.district_id,
                        A.location_name,
                        A.wastages_rate
                FROM
                        (
                                SELECT
                                        ROUND(
                                                IFNULL(
                                                        (
                                                                sum(hf_data_master.wastages) / (
                                                                        sum(
                                                                                hf_data_master.issue_balance
                                                                        ) + sum(hf_data_master.wastages)
                                                                )
                                                        ) * 100,
                                                        0
                                                ),
                                                1
                                        ) AS wastages_rate,
                                        UC.pk_id,
                                        UC.location_name,
                                        UC.district_id
                                FROM
                                        locations AS UC
                                INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                WHERE
                                        UC.geo_level_id = 6
                                AND warehouses. STATUS = 1
                                AND hf_data_master.issue_balance IS NOT NULL
                                AND hf_data_master.issue_balance != 0
                                AND hf_data_master.item_pack_size_id = " . $product . "
                                AND warehouses.stakeholder_id = 1
                                $filter
                                AND DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '" . $str_date . "'
                                GROUP BY
                                        UC.pk_id
                        ) A
                WHERE
                        A.wastages_rate > " . $limit . "";



        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get District Stock Batch
     */
    public function getDistrictStockBatch($district, $product) {

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        $year = $date->get(Zend_Date::YEAR);
        $month = $date->get(Zend_Date::MONTH);
        $str_date = $year . "-" . $month . "-" . $day;

        $query = "SELECT 
                        locations.location_name as district_name,
                        stock_batch.number,
                        stock_batch_warehouses.quantity as quantity,
                        DATE_FORMAT(stock_batch.expiry_date, '%Y-%m-%d') AS expiry_date
                        FROM
                        stock_batch
                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                        stakeholders.geo_level_id = 4
                        AND stock_batch_warehouses.status <> 'Finished'
                        AND stock_batch.expiry_date >= '" . $str_date . "'
                        AND stock_batch.expiry_date <= ADDDATE('" . $str_date . "',INTERVAL 6 MONTH)
                        AND warehouses.district_id = " . $district . "
                        and warehouses.status = 1
                        and stakeholder_item_pack_sizes.item_pack_size_id = " . $product . "";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Tehsil Stock Batch
     */
    public function getTehsilStockBatch($tehsil, $product) {

        $date = new Zend_Date();
        $day = $date->get(Zend_Date::DAY);
        $year = $date->get(Zend_Date::YEAR);
        $month = $date->get(Zend_Date::MONTH);
        $str_date = $year . "-" . $month . "-" . $day;

        $query = "SELECT
                        teh.location_name AS tehsil_name,
                        stock_batch.number,
                        stock_batch_warehouses.quantity,
                  DATE_FORMAT(stock_batch.expiry_date, '%Y-%m-%d') AS expiry_date
                FROM
                        locations AS teh
                INNER JOIN locations AS uc ON uc.parent_id = teh.pk_id
                INNER JOIN warehouses ON uc.pk_id = warehouses.location_id
                INNER JOIN stock_batch_warehouses ON warehouses.pk_id = stock_batch_warehouses.warehouse_id
                INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id                

                WHERE
                        teh.geo_level_id = 5
                AND warehouses. STATUS = 1
                AND stock_batch_warehouses. STATUS <> 'Finished'
                AND stock_batch.expiry_date >= '" . $str_date . "'
                AND stock_batch.expiry_date <= ADDDATE('" . $str_date . "',INTERVAL 6 MONTH)
                AND teh.pk_id = " . $tehsil . "
                AND stakeholder_item_pack_sizes.item_pack_size_id = " . $product . "";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Cold Chain Asset District
     */
    public function getColdChainAssetDistrict($district, $type) {

        $query = "SELECT
                                locations.location_name AS district_name,
                                ccm_asset_types.asset_type_name,
                                ROUND(
                                        SUM(ccm_models.net_capacity_4),
                                        2
                                ) AS capacity
                        FROM
                                cold_chain
                        INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                        INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                        INNER JOIN locations ON locations.pk_id = warehouses.district_id
                        WHERE
                                ccm_asset_types.parent_id = " . $type . "
                        AND warehouses. STATUS = 1
                        AND warehouses.district_id = " . $district . "
                        GROUP BY
                                ccm_asset_types.asset_type_name
                        ORDER BY
                                district_name";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Cold Chain Asset Tehsil
     */
    public function getColdChainAssetTehsil($tehsil, $type) {

        $query = "SELECT
                            teh.location_name AS tehsil_name,
                            ccm_asset_types.asset_type_name,
                            ROUND(
                                    SUM(ccm_models.net_capacity_4)
                            ) AS capacity
                    FROM
                            cold_chain
                    INNER JOIN ccm_models ON cold_chain.ccm_model_id = ccm_models.pk_id
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN locations ON locations.pk_id = warehouses.location_id
                    INNER JOIN locations teh ON locations.parent_id = teh.pk_id
                    INNER JOIN ccm_asset_types ON cold_chain.ccm_asset_type_id = ccm_asset_types.pk_id
                    WHERE
                            ccm_asset_types.parent_id = " . $type . "
                    AND warehouses. STATUS = 1
                    AND teh.geo_level_id = 5
                    AND locations.parent_id = " . $tehsil . "
                    GROUP BY
                            ccm_asset_types.asset_type_name
                    ORDER BY
                            teh.location_name ASC";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Demographic Map Data
     */
    public function getDemographicMapData() {

        $date = new Zend_Date();
        $year = $date->get(Zend_Date::YEAR);

        $query = "SELECT
                    F.district_id,
                    F.district_name,
                    F.total_ucs,
                    F.total_users,
                    F.total_facility,
                    F.total_tehsils,
                    G.population
            FROM
                    (
                            SELECT
                                    map_district_mapping.mapping_id AS district_id,
                                    map_district_mapping.district_name,
                                    SUM(E.total_ucs) AS total_ucs,
                                    SUM(E.total_users) AS total_users,
                                    SUM(E.total_facility) AS total_facility,
                                    SUM(E.total_tehsils) AS total_tehsils
                            FROM
                                    (
                                            SELECT
                                                    C.district_id,
                                                    C.district_name,
                                                    C.total_ucs,
                                                    C.total_users,
                                                    C.total_facility,
                                                    COALESCE (D.total_tehsil, NULL, 0) AS total_tehsils
                                            FROM
                                                    (
                                                            SELECT
                                                                    A.district_id,
                                                                    A.district_name,
                                                                    A.total_ucs,
                                                                    A.total_users,
                                                                    B.total_facility
                                                            FROM
                                                                    (
                                                                            SELECT
                                                                                    Ucs.district_id,
                                                                                    Ucs.district_name,
                                                                                    Ucs.total_ucs,
                                                                                    users.total_users
                                                                            FROM
                                                                                    (
                                                                                            SELECT
                                                                                                    warehouses.district_id,
                                                                                                    locations.location_name AS district_name,
                                                                                                    COUNT(DISTINCT UC.pk_id) AS total_ucs
                                                                                            FROM
                                                                                                    locations AS UC
                                                                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                                                            INNER JOIN locations ON warehouses.district_id = locations.pk_id
                                                                                            WHERE
                                                                                                    UC.geo_level_id = 6
                                                                                            AND warehouses.stakeholder_id = 1
                                                                                            AND warehouses. STATUS = 1
                                                                                            GROUP BY
                                                                                                    warehouses.district_id
                                                                                    ) AS Ucs
                                                                            LEFT JOIN (
                                                                                    SELECT
                                                                                            locations.pk_id AS district_id,
                                                                                            count(
                                                                                                    DISTINCT warehouse_users.user_id
                                                                                            ) AS total_users
                                                                                    FROM
                                                                                            warehouses
                                                                                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                                                                    INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                                                                                    INNER JOIN locations ON warehouses.district_id = locations.pk_id
                                                                                    WHERE
                                                                                            stakeholders.geo_level_id = 6
                                                                                    AND warehouses. STATUS = 1
                                                                                    AND warehouses.stakeholder_id = 1
                                                                                    GROUP BY
                                                                                            locations.pk_id
                                                                            ) AS users ON Ucs.district_id = users.district_id
                                                                    ) AS A
                                                            LEFT JOIN (
                                                                    SELECT
                                                                            locations.pk_id AS district_id,
                                                                            count(
                                                                                    DISTINCT warehouse_users.warehouse_id
                                                                            ) AS total_facility
                                                                    FROM
                                                                            locations
                                                                    INNER JOIN warehouses ON locations.pk_id = warehouses.district_id
                                                                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                                                    INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                                                                    WHERE
                                                                            stakeholders.geo_level_id = 6
                                                                    AND warehouses.stakeholder_id = 1
                                                                    AND warehouses. STATUS = 1
                                                                    GROUP BY
                                                                            warehouses.district_id
                                                            ) AS B ON A.district_id = B.district_id
                                                    ) AS C
                                            LEFT JOIN (
                                                    SELECT
                                                            warehouses.district_id,
                                                            COUNT(UC.pk_id) AS total_tehsil
                                                    FROM
                                                            locations AS UC
                                                    INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                    INNER JOIN locations ON warehouses.district_id = locations.pk_id
                                                    WHERE
                                                            UC.geo_level_id = 5
                                                    AND warehouses.stakeholder_id = 1
                                                    AND warehouses. STATUS = 1
                                                    GROUP BY
                                                            warehouses.district_id
                                            ) AS D ON C.district_id = D.district_id
                                    ) E
                            INNER JOIN map_district_mapping ON map_district_mapping.district_id = E.district_id
                            GROUP BY
                                    map_district_mapping.mapping_id
                    ) AS F
            LEFT JOIN (
                    SELECT
                            pilot_districts.district_id,
                            location_populations.population
                    FROM
                            location_populations
                    INNER JOIN pilot_districts ON location_populations.location_id = pilot_districts.district_id
                    WHERE
                            DATE_FORMAT(
                                    location_populations.estimation_date,
                                    '%Y%'
                            ) = " . $year . "
            ) G ON F.district_id = G.district_id";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Demographic Detail
     */
    public function getDemographicDetail() {

        $date = new Zend_Date();
        $year = $date->get(Zend_Date::YEAR);

        $query = "SELECT
                    Count(DISTINCT locations.district_id) AS total_districts,
                    COUNT(DISTINCT locations.province_id) AS total_province,
                    SUM(location_populations.population) AS total_population
                    FROM
                    pilot_districts
                    INNER JOIN locations ON locations.pk_id = pilot_districts.district_id
                    INNER JOIN location_populations ON pilot_districts.district_id = location_populations.location_id
                    WHERE
                    DATE_FORMAT(location_populations.estimation_date,'%Y') = '" . $year . "'";


        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Demographic Assests
     */
    public function getDemographicAssests($id, $district) {

        if ($id == "th") {
            $query = "SELECT
                            DISTINCT locations.pk_id AS district_id,
                                  locations.location_name AS name
                          FROM
                                  locations 
                          INNER JOIN warehouses ON warehouses.district_id = locations.district_id
                          INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                          INNER JOIN map_district_mapping ON map_district_mapping.district_id = warehouses.district_id
                          WHERE
                                  locations.geo_level_id = 5
                          AND locations.district_id = '" . $district . "'";
        } else if ($id == "uc") {

            $query = "SELECT
                                DISTINCT UC.pk_id AS ucID,
                                UC.location_name AS name
                        FROM
                                locations AS UC
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN locations ON warehouses.district_id = locations.pk_id
                        WHERE
                                UC.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses. STATUS = 1
                        AND UC.district_id = '" . $district . "'";
        } else if ($id == "us") {

            $query = "SELECT DISTINCT
                            locations.pk_id AS districtId,
                            users.user_name AS name
                    FROM
                            warehouses
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                    INNER JOIN locations ON warehouses.district_id = locations.pk_id
                    INNER JOIN users ON warehouse_users.user_id = users.pk_id
                    WHERE
                            stakeholders.geo_level_id = 6
                    AND warehouses. STATUS = 1
                    AND warehouses.stakeholder_id = 1
                    AND locations.district_id = '" . $district . "'";
        } else if ($id == "fac") {

            $query = "SELECT DISTINCT
                                locations.pk_id AS districtId,
                                warehouses.warehouse_name AS name
                        FROM
                                locations
                        INNER JOIN warehouses ON locations.pk_id = warehouses.district_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                        WHERE
                                stakeholders.geo_level_id = 6
                        AND warehouses.stakeholder_id = 1
                        AND warehouses. STATUS = 1
                        AND locations.district_id= '" . $district . "'";
        }

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get District List
     */
    public function getDistrictList($province) {
        if ($province == "all") {
            $prov = "";
        } else {
            $prov = "AND l.province_id =" . $province;
        }

        $query = "SELECT
                        l.pk_id AS pkId,
                         l.location_name AS locationName
                        FROM
                                locations l
                        INNER JOIN pilot_districts ON pilot_districts.district_id = l.pk_id
                        WHERE
                                (
                                        l.geo_level_id = 4
                                        $prov
                                )
                        ORDER BY
                                locationName";

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        $data = $row->fetchAll(\PDO::FETCH_ASSOC);

        echo '<option value="all">All</option>';
        foreach ($data as $row) {
            echo "<option value=" . $row['pkId'] . ">" . $row['locationName'] . "</option>";
        }
    }

    /**
     * Get Uc Wise Mos
     */
    public function getUcWiseMos($year, $month, $product, $tehsilId) {
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        $str_sql = "CALL REPgetData ('UT', '$reporting_date', $tehsilId, $product, 1)";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Uc Wise Consumption
     */
    public function getUcWiseConsumption($year, $month, $product, $tehsilId, $type) {
        $reporting_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT) . '-01';
        $str_sql = "CALL REPgetData ('UT', '$reporting_date', $tehsilId, $product, 1)";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Non Reported Ucs By District
     */
    public function getNonReportedUcsByDistrict($month, $year, $province, $district) {

        if ($month < 10) {
            $month = "0" . $month;
        }

        if ($province == "all") {
            $where = "";
        } else {
            $where = "AND locations.district_id =" . $district;
        }

        $str_date = $year . "-" . $month;

        $qry = " SELECT DISTINCT
                                        locations.pk_id
                                        FROM
                                        locations
                                        INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                        WHERE
                                        locations.geo_level_id = 6
                                        $where
                                        AND warehouses.status = 1
                                        AND DATE_FORMAT(
                                        hf_data_master.reporting_start_date,
                                        '%Y-%m'
                                        ) = '$str_date' AND
                                        warehouses.stakeholder_id = 1";

        $str_sql = "SELECT DISTINCT
                                        B.pk_id,
                                        B.district_name,
                                        B.location_name
                                FROM
                                        (
                                        $qry
                                        ) A
                                RIGHT JOIN (
                                        SELECT DISTINCT
                                                locations.pk_id,
                                                dist.location_name AS district_name,
                                                locations.location_name
                                        FROM
                                        locations
                                        INNER JOIN warehouses ON warehouses.location_id = locations.pk_id
                                        INNER JOIN locations AS dist ON dist.pk_id = locations.district_id
                                        WHERE
                                        locations.geo_level_id = 6 
                                        $where AND
                                        warehouses.status = 1 AND
                                        warehouses.stakeholder_id = 1
                                ) B ON A.pk_id = B.pk_id
                                WHERE
                                        A.pk_id IS NULL";


        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Non Reported Ucs By Tehsil
     */
    public function getNonReportedUcsByTehsil($month, $year, $province, $tehsil) {

        if ($month < 10) {
            $month = "0" . $month;
        }

        $where = "AND locations.parent_id =" . $tehsil;

        $str_date = $year . "-" . $month;

        $qry = " SELECT DISTINCT
                                        locations.pk_id
                                        FROM
                                        locations
                                        INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                        WHERE
                                        locations.geo_level_id = 6
                                        $where
                                        AND warehouses.status = 1
                                        AND DATE_FORMAT(
                                        hf_data_master.reporting_start_date,
                                        '%Y-%m'
                                        ) = '$str_date' AND
                                        warehouses.stakeholder_id = 1";

        $str_sql = "SELECT DISTINCT
                                        B.pk_id,
                                        B.tehsil_name,
                                        B.location_name
                                FROM
                                        (
                                        $qry
                                        ) A
                                RIGHT JOIN (
                                        SELECT DISTINCT
                                                locations.pk_id,
                                                dist.location_name AS tehsil_name,
                                                locations.location_name
                                        FROM
                                        locations
                                        INNER JOIN warehouses ON warehouses.location_id = locations.pk_id
                                        INNER JOIN locations AS dist ON dist.pk_id = locations.parent_id
                                        WHERE
                                        locations.geo_level_id = 6 
                                        $where AND
                                        warehouses.status = 1 AND
                                        warehouses.stakeholder_id = 1
                                ) B ON A.pk_id = B.pk_id
                                WHERE
                                        A.pk_id IS NULL";


        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get Wastages District
     * @param type $year
     * @param string $month
     * @param type $province
     * @param type $product
     * @return type
     */
    public function getWastagesRateDistrict($year, $month, $province, $product) {
        $query = "SELECT
                        item_pack_sizes.wastage_rate_allowed
                        FROM
                        item_pack_sizes
                        WHERE
                        item_pack_sizes.pk_id = " . $product;

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        while ($data = $row->fetch()) {
            $limit = $data['wastage_rate_allowed'];
        }

        if ($month < 10) {
            $month = "0" . $month;
        }
        $str_date = $year . "-" . $month;

        if ($province == 'all') {

            $query = "SELECT
                            E.districtId,
                            E.districtName,
                            E.TotalWH,
                            COALESCE (D.reported, NULL, 0) AS reported,
                            COALESCE (D.wastages, NULL, 0) AS wastages,
                             COALESCE (D.wastages, NULL, 0) AS wastages_rate
                    FROM
                            (
                                    SELECT
                                            B.districtId,
                                            B.districtName,
                                            B.reported,
                                            COALESCE (A.UCCOunt, NULL, 0) AS wastages
                                    FROM
                                            (
                                                 SELECT
                                                                 A.wastages_rate AS UCCOunt,
                                                                A.district_id AS districtId
                                                        FROM
                                                                (
                                                                        SELECT
                                                                                UC.district_id,
                                                                                UC.location_name AS districtName,
                                                                                ROUND(
                                                                                        IFNULL(
                                                                                                (
                                                                                                        sum(hf_data_master.wastages) / (
                                                                                                                sum(
                                                                                                                        hf_data_master.issue_balance
                                                                                                                ) + sum(hf_data_master.wastages)
                                                                                                        )
                                                                                                ) * 100,
                                                                                                0
                                                                                        ),
                                                                                        1
                                                                                ) AS wastages_rate
                                                                        FROM
                                                                        			locations AS District
		                                                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                                        WHERE
                                                                                UC.geo_level_id = 6
                                                                        AND warehouses.stakeholder_id = 1
                                                                        AND warehouses. STATUS = 1
                                                                        AND DATE_FORMAT(
                                                                                hf_data_master.reporting_start_date,
                                                                                '%Y-%m'
                                                                        ) = '" . $str_date . "'
                                                                        AND hf_data_master.issue_balance IS NOT NULL
                                                                        AND hf_data_master.issue_balance != 0
                                                                        AND hf_data_master.item_pack_size_id = $product
                                                                        GROUP BY
                                                                                District.pk_id
                                                                ) A
                                                        
                                            ) A
                                    RIGHT JOIN (
                                            SELECT
                                                    District.pk_id AS districtId,
                                                    District.location_name AS districtName,
                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                            FROM
                                                    locations AS District
                                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND warehouses.stakeholder_id = 1
                                            AND warehouses. STATUS = 1
                                            AND DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%Y-%m'
                                            ) = '" . $str_date . "'
                                            AND hf_data_master.issue_balance IS NOT NULL
                                            AND hf_data_master.issue_balance != 0
                                            GROUP BY
                                                    District.pk_id
                                            ORDER BY
                                                    districtId ASC
                                    ) B ON A.districtId = B.districtId
                            ) D
                    RIGHT JOIN (
                            SELECT
                                    District.pk_id AS districtId,
                                    District.location_name AS districtName,
                                    COUNT(DISTINCT UC.pk_id) AS totalWH
                            FROM
                                    locations AS District
                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 6
                            AND warehouses.stakeholder_id = 1
                            AND warehouses.status = 1
                            GROUP BY
                                    District.pk_id
                            ORDER BY
                                    districtId ASC
                    ) E ON E.districtId = D.districtId
                  ";
        } else {
            $query = "SELECT
                            E.districtId,
                            E.districtName,
                            E.TotalWH,
                            COALESCE (D.reported, NULL, 0) AS reported,
                            COALESCE (D.wastages, NULL, 0) AS wastages,
                             COALESCE (D.wastages, NULL, 0) AS wastages_rate
                    FROM
                            (
                                    SELECT
                                            B.districtId,
                                            B.districtName,
                                            B.reported,
                                            COALESCE (A.UCCOunt, NULL, 0) AS wastages
                                    FROM
                                            (
                                                 SELECT
                                                               A.wastages_rate AS UCCOunt,
                                                                A.district_id AS districtId
                                                        FROM
                                                                (
                                                                        SELECT
                                                                                UC.district_id,
                                                                                UC.location_name AS districtName,
                                                                                ROUND(
                                                                                        IFNULL(
                                                                                                (
                                                                                                        sum(hf_data_master.wastages) / (
                                                                                                                sum(
                                                                                                                        hf_data_master.issue_balance
                                                                                                                ) + sum(hf_data_master.wastages)
                                                                                                        )
                                                                                                ) * 100,
                                                                                                0
                                                                                        ),
                                                                                        1
                                                                                ) AS wastages_rate
                                                                        FROM
                                                                        locations AS District
		                                                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                                        WHERE
                                                                                UC.geo_level_id = 6
                                                                        AND warehouses.stakeholder_id = 1
                                                                        AND warehouses. STATUS = 1
                                                                        AND DATE_FORMAT(
                                                                                hf_data_master.reporting_start_date,
                                                                                '%Y-%m'
                                                                        ) = '" . $str_date . "'
                                                                        AND hf_data_master.issue_balance IS NOT NULL
                                                                        AND hf_data_master.issue_balance != 0
                                                                        AND warehouses.province_id = $province
                                                                        AND hf_data_master.item_pack_size_id = $product
                                                                        GROUP BY
                                                                                District.pk_id
                                                                ) A
                                                        
                                            ) A
                                    RIGHT JOIN (
                                            SELECT
                                                    District.pk_id AS districtId,
                                                    District.location_name AS districtName,
                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                            FROM
                                                    locations AS District
                                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND warehouses.stakeholder_id = 1
                                            AND warehouses. STATUS = 1
                                            AND District.province_id = $province
                                            AND DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%Y-%m'
                                            ) = '" . $str_date . "'
                                            AND hf_data_master.issue_balance IS NOT NULL
                                            AND hf_data_master.issue_balance != 0
                                            GROUP BY
                                                    District.pk_id
                                            ORDER BY
                                                    districtId ASC
                                    ) B ON A.districtId = B.districtId
                            ) D
                    RIGHT JOIN (
                            SELECT
                                    District.pk_id AS districtId,
                                    District.location_name AS districtName,
                                    COUNT(DISTINCT UC.pk_id) AS totalWH
                            FROM
                                    locations AS District
                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 6
                            AND warehouses.stakeholder_id = 1
                            AND warehouses.status = 1
                            AND District.province_id = $province
                            GROUP BY
                                    District.pk_id
                            ORDER BY
                                    districtId ASC
                    ) E ON E.districtId = D.districtId";
        }

        $row = $this->_em_read->getConnection()->prepare($query);
        $row->execute();
        return $row->fetchAll(\PDO::FETCH_ASSOC);
    }

}
