<?php

/**
 * VanDashletViewHelper
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @package van
 * @author     Muhammad Imran
 * @version    2.5.1
 */

/**
 *  Van Dashlet View Helper
 */
class Zend_View_Helper_VanDashletViewHelper extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * VanDashletViewHelper
     */
    public function VanDashletViewHelper() {
        return $this;
    }

    public function getWastageInDistrict($selected_district, $start_date, $end_date, $selected_product) {
        $product_filter = "";
        if ($selected_product != 0) {
            $product_filter = "AND item_pack_sizes.pk_id = $selected_product";
        }
        $str_qry = " 
                SELECT
                    hf_data_master.item_pack_size_id AS item_id,
                    item_pack_sizes.item_name AS Vaccine,
                    District.location_name,
                    Sum(hf_data_master.wastages) AS Wastage,
                    ROUND(IFNULL((sum(hf_data_master.wastages) / (sum(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS PercentWastage,
                    item_pack_sizes.wastage_rate_allowed AS AcceptableLevel,
                    DATE_FORMAT(hf_data_master.reporting_start_date,'%b-%y') AS Month

                FROM
                    locations AS District
                    INNER JOIN warehouses ON warehouses.district_id = District.pk_id
                    INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                    INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
                WHERE
                    warehouses.stakeholder_id = 1 AND
                    warehouses.`status` = 1 AND
                    DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' AND
                    hf_data_master.issue_balance IS NOT NULL AND
                    hf_data_master.issue_balance != 0 AND
                    District.district_id = '$selected_district' AND
                    stakeholder_activities.activity = 'routine' AND
                    item_pack_sizes.item_category_id = 1
                    $product_filter
                GROUP BY
                    item_pack_sizes.pk_id
                ORDER BY
                    item_pack_sizes.list_rank ASC

                ";

//                "SELECT
//                        item_pack_sizes.pk_id AS item_id,
//                        locations.location_name,
//                        item_pack_sizes.item_name AS Vaccine,
//                        consumption_summary.consumption,
//                        consumption_summary.open_wastage,
//                        consumption_summary.close_wastage,
//                        DATE_FORMAT(consumption_summary.reporting_start_date,'%b-%y') AS Month,
//                        consumption_summary.reporting_start_date,
//                        ROUND(((consumption_summary.open_wastage + consumption_summary.close_wastage)/consumption_summary.consumption)*100,2) AS PercentWastage,
//                        item_pack_sizes.wastage_rate_allowed AS AcceptableLevel
//                    FROM
//                        consumption_summary
//                        INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = consumption_summary.item_pack_size_id
//                        INNER JOIN locations ON consumption_summary.location_id = locations.pk_id
//                    WHERE
//                        consumption_summary.location_id = $selected_district 
//                        AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) >= '$start_date'
//			AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) <= '$end_date'
//                        $product_filter
//                    ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getUCsWastingVaccinesAbovePermissibleLevel($selected_district, $start_date, $end_date, $selected_product) {
        $product_filter = "";
        if ($selected_product != 0) {
            $product_filter = "AND item_pack_sizes.pk_id = $selected_product";
        }
        $str_qry = "SELECT
                        item_pack_sizes.pk_id AS item_id,
                        locations.location_name,
                        item_pack_sizes.item_name AS Vaccine,
                        consumption_summary.consumption,
                        consumption_summary.open_wastage,
                        consumption_summary.close_wastage,
                        DATE_FORMAT(consumption_summary.reporting_start_date,'%b-%y') AS Month,
                        consumption_summary.reporting_start_date,
                        ROUND(((consumption_summary.open_wastage + consumption_summary.close_wastage)/consumption_summary.consumption)*100,2) AS NomberOfUcs,
                        item_pack_sizes.wastage_rate_allowed AS AcceptableLevel
                    FROM
                        consumption_summary
                        INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = consumption_summary.item_pack_size_id
                        INNER JOIN locations ON consumption_summary.location_id = locations.pk_id
                        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
                    WHERE
                        consumption_summary.location_id = $selected_district 
                        AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) >= '$start_date'
			AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) <= '$end_date'
                        AND    stakeholder_activities.activity = 'routine' 
                        AND item_pack_sizes.item_category_id = 1
                        $product_filter
                    ";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNumberofUcsLateReported($selected_district, $month, $year, $selected_product) {
        $product_filter = "";
        if ($selected_product != 0) {
            $product_filter = "AND item_pack_sizes.pk_id = $selected_product";
        }
        $str_qry = "SELECT
                        A.districtId,
                        A.districtName,
                        A.totalWH,
                        B.Month,
                        IFNULL(B.reported, 0) AS reported,
                        ROUND(
                                (
                                        IFNULL(B.reported, 0) / A.totalWH
                                ) * 100
                        ) AS reportingPercentage,
                        IFNULL(C.in_time_reported, 0) AS in_time_reported,
                        IFNULL(
                                ROUND(
                                        (in_time_reported / reported) * 100
                                ),
                                0
                        ) AS timelinessPercentage,
                A.totalWH - in_time_reported AS late_reported
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
                                INNER JOIN warehouse_users ON warehouse_users.warehouse_id = warehouses.pk_id
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                WHERE
                                        stakeholders.geo_level_id = 6
                                AND warehouses.stakeholder_id = 1
                                AND warehouses. STATUS = 1
                                AND District.pk_id = $selected_district
                                GROUP BY
                                        District.pk_id
                                ORDER BY
                                        districtId ASC
                        ) A
                LEFT JOIN (
                        SELECT
                                District.pk_id AS districtId,
                                District.location_name AS districtName,
                                COUNT(DISTINCT UC.pk_id) AS reported,
                                DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%b-%y'
                                                    ) AS Month
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
                        AND District.pk_id = '63'
                        AND DATE_FORMAT(
                                hf_data_master.reporting_start_date,
                                '%Y-%m'
                        ) = '$year-$month'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        GROUP BY
                                District.pk_id
                        ORDER BY
                                districtId ASC
                ) B ON A.districtId = B.districtId
                LEFT JOIN (
                        SELECT
                                District.pk_id AS districtId,
                                District.location_name AS districtName,
                                COUNT(DISTINCT UC.pk_id) AS in_time_reported
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
                        AND District.pk_id = $selected_district
                        AND DATE_FORMAT(
                                hf_data_master.reporting_start_date,
                                '%Y-%m'
                        ) = '$year-$month'
                        AND DATE_FORMAT(
                                hf_data_master.created_date,
                                '%d'
                        ) <= '10'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        GROUP BY
                                District.pk_id
                        ORDER BY
                                districtId ASC
                ) C ON A.districtId = C.districtId
                    ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNumberOfChildrenVaccinated($selected_district, $start_date, $end_date, $selected_product) {
        $product_filter = "";
        if ($selected_product != 0) {
            $product_filter = "AND item_pack_sizes.pk_id = $selected_product";
        }
        $str_qry = "SELECT
                        item_pack_sizes.pk_id AS item_id,
                        locations.location_name,
                        item_pack_sizes.item_name AS Vaccine,
                        consumption_summary.consumption,
                        consumption_summary.open_wastage,
                        consumption_summary.close_wastage,
                        DATE_FORMAT(consumption_summary.reporting_start_date,'%b-%y') AS Month,
                        consumption_summary.reporting_start_date,
                        ROUND(((consumption_summary.open_wastage + consumption_summary.close_wastage)/consumption_summary.consumption)*100,2) AS NumberOfChildrenVaccinated,
                        item_pack_sizes.wastage_rate_allowed AS AcceptableLevel
                    FROM
                        consumption_summary
                        INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = consumption_summary.item_pack_size_id
                        INNER JOIN locations ON consumption_summary.location_id = locations.pk_id
                    WHERE
                        consumption_summary.location_id = $selected_district 
                        AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) >= '$start_date'
			AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) <= '$end_date'
                        $product_filter
                    ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getVaccinationAndTarget($selected_district, $month, $year, $selected_product) {
        $str_qry = "SELECT
                   A.district AS location_name,
                   A.item_id,
                   A.item_name,
                   A.MonthlyTarget,
                   B.Month,
                   IFNULL(B.consumption, 0) AS Vaccination,
                   ROUND(
                           (
                                   IFNULL(B.consumption, 0) / A.MonthlyTarget
                           ) * 100
                   ) AS CoveragePercentage
           FROM
                   (
                           SELECT
                                   B.pk_id AS item_id,
                                   B.item_name,
                                   A.district,
                                   ROUND(
                                           (
                                                   (
                                                           (
                                                                   (A.population / 100) * B.population_percent_increase_per_year
                                                           ) / 100 * B.child_surviving_percent_per_year
                                                   ) * B.doses_per_year
                                           ) / 12
                                   ) AS MonthlyTarget
                           FROM
                                   (
                                           SELECT DISTINCT
                                                   locations.location_name AS district,
                                                   (
                                                           SELECT
                                                                   IFNULL(
                                                                           location_populations.population,
                                                                           0
                                                                   )
                                                           FROM
                                                                   location_populations
                                                           WHERE
                                                                   location_populations.location_id = locations.pk_id
                                                           AND DATE_FORMAT(
                                                                   location_populations.estimation_date,
                                                                   '%Y'
                                                           ) = '$year'
                                                   ) AS population
                                           FROM
                                                   pilot_districts
                                           INNER JOIN locations ON pilot_districts.district_id = locations.district_id
                                           WHERE
                                                   locations.geo_level_id = 4
                                           AND locations.district_id = $selected_district
                                   ) A,
                                   (
                                           SELECT
                                                   item_pack_sizes.pk_id,
                                                   item_pack_sizes.item_name,
                                                   items.population_percent_increase_per_year,
                                                   items.child_surviving_percent_per_year,
                                                   items.doses_per_year
                                           FROM
                                                   item_activities
                                           INNER JOIN item_pack_sizes ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                                           INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
                                           WHERE
                                                   item_pack_sizes.item_category_id = 1
                                           AND item_activities.stakeholder_activity_id = 1
                                           ORDER BY
                                                   item_pack_sizes.list_rank
                                   ) B
                   ) A
           LEFT JOIN (
                   SELECT
                           sum(
                                   hf_data_master.issue_balance
                           ) AS consumption,
                           hf_data_master.item_pack_size_id AS item_id,
                           DATE_FORMAT(hf_data_master.reporting_start_date,'%b-%y') AS Month
                   FROM
                           warehouses
                   INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                   INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                   WHERE
                           MONTH (
                                   hf_data_master.reporting_start_date
                           ) = '$month'
                   AND YEAR (
                           hf_data_master.reporting_start_date
                   ) = '$year'
                   AND warehouses.pk_id = hf_data_master.warehouse_id
                   AND warehouses.district_id = '$selected_district'
                   AND stakeholders.geo_level_id = 6
                   GROUP BY
                           hf_data_master.item_pack_size_id
           ) B ON A.item_id = B.item_id";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getUcWiseReportingRate($selected_district, $month, $year, $selected_product) {
        $str_qry = "SELECT
                        A.districtId,
                        A.districtName,
                        A.totalWH,
                        B. Month,
                        IFNULL(B.reported, 0) AS reported,
                        ROUND(
                                (
                                        IFNULL(B.reported, 0) / A.totalWH
                                ) * 100
                        ) AS reportingPercentage	
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
                                INNER JOIN warehouse_users ON warehouse_users.warehouse_id = warehouses.pk_id
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                WHERE
                                        stakeholders.geo_level_id = 6
                                AND warehouses.stakeholder_id = 1
                                AND warehouses. STATUS = 1
                                AND District.pk_id = $selected_district

                                GROUP BY
                                        District.pk_id
                                ORDER BY
                                        districtId ASC
                        ) A
                LEFT JOIN (
                        SELECT
                                District.pk_id AS districtId,
                                District.location_name AS districtName,
                                COUNT(DISTINCT UC.pk_id) AS reported,
                                DATE_FORMAT(
			hf_data_master.reporting_start_date,
			'%b-%y'
		) AS Month
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
                        AND District.pk_id = $selected_district
                        AND DATE_FORMAT(
                                hf_data_master.reporting_start_date,
                                '%Y-%m'
                        ) = '$year-$month'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        GROUP BY
                                District.pk_id
                        ORDER BY
                                districtId ASC
                ) B ON A.districtId = B.districtId
                ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getHealthFacilityWiseReportingRate($selected_district, $month, $year, $selected_product) {
        $str_qry = "SELECT
                        A.districtId,
                        A.districtName,
                        A.totalWH,
                        B. Month,
                        IFNULL(B.reported, 0) AS reported,
                        ROUND(
                                (
                                        IFNULL(B.reported, 0) / A.totalWH
                                ) * 100
                        ) AS reportingPercentage	
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
                                INNER JOIN warehouse_users ON warehouse_users.warehouse_id = warehouses.pk_id
                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                WHERE
                                        stakeholders.geo_level_id = 6
                                AND warehouses.stakeholder_id = 1
                                AND warehouses. STATUS = 1
                                AND District.pk_id = $selected_district

                                GROUP BY
                                        District.pk_id
                                ORDER BY
                                        districtId ASC
                        ) A
                LEFT JOIN (
                        SELECT
                                District.pk_id AS districtId,
                                District.location_name AS districtName,
                                COUNT(DISTINCT UC.pk_id) AS reported,
                                DATE_FORMAT(
			hf_data_master.reporting_start_date,
			'%b-%y'
		) AS Month
                        FROM
                                locations AS District
                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                                stakeholders.geo_level_id = 7
                        AND warehouses.stakeholder_id = 1
                        AND warehouses. STATUS = 1
                        AND District.pk_id = $selected_district
                        AND DATE_FORMAT(
                                hf_data_master.reporting_start_date,
                                '%Y-%m'
                        ) = '$year-$month'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        GROUP BY
                                District.pk_id
                        ORDER BY
                                districtId ASC
                ) B ON A.districtId = B.districtId
                ";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getUcWiseMonthlyTargetsAndCoverage($selected_district, $month, $year, $selected_product) {

        if ($selected_product == 0) {
            $selected_product = 26;
        }
        $str_qry = "SELECT
                A.item_id,
                B.pk_id AS uc_id,
                B.district AS district,
                B.tehsil AS tehsil,
                B.ucs AS ucs,
                B.target AS target,
                IFNULL(A.total, 0) AS consumption,
                IFNULL(
                        ROUND(
                                (
                                        IFNULL(A.total, 0) / (B.target)
                                ) * 100
                        ),
                        0
                ) AS consumptionPercentage
        FROM
                (
                        (
                                SELECT
                                        A.pk_id,
                                        A.fixed_inside_uc_male,
                                        A.fixed_inside_uc_female,
                                        A.outreach_male,
                                        A.outreach_female,
                                        A.item_id,
                                        (
                                                IFNULL(A.total, 0) + IFNULL(B.male_doses, 0) + IFNULL(B.female_doses, 0)
                                        ) AS total,
                                        B.male_doses,
                                        B.female_doses
                                FROM
                                        (
                                                SELECT
                                                        locations.pk_id,
                                                        hf_data_master.item_pack_size_id AS item_id,
                                                        sum(
                                                                hf_data_detail.fixed_inside_uc_male + fixed_outside_uc_male
                                                        ) AS fixed_inside_uc_male,
                                                        sum(
                                                                hf_data_detail.fixed_inside_uc_female + fixed_outside_uc_female
                                                        ) AS fixed_inside_uc_female,
                                                        sum(
                                                                hf_data_detail.outreach_male + outreach_outside_male
                                                        ) AS outreach_male,
                                                        sum(
                                                                hf_data_detail.outreach_female + outreach_outside_female
                                                        ) AS outreach_female,
                                                        (
                                                                IFNULL(
                                                                        sum(
                                                                                hf_data_detail.fixed_inside_uc_male + fixed_outside_uc_male
                                                                        ),
                                                                        0
                                                                ) + IFNULL(
                                                                        sum(
                                                                                hf_data_detail.fixed_inside_uc_female + fixed_outside_uc_female
                                                                        ),
                                                                        0
                                                                ) + IFNULL(
                                                                        sum(
                                                                                hf_data_detail.outreach_male + outreach_outside_male
                                                                        ),
                                                                        0
                                                                ) + IFNULL(
                                                                        sum(
                                                                                hf_data_detail.outreach_female + outreach_outside_female
                                                                        ),
                                                                        0
                                                                )
                                                        ) AS total
                                                FROM
                                                        warehouses
                                                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
                                                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                                                WHERE
                                                        hf_data_master.item_pack_size_id = $selected_product
                                                AND DATE_FORMAT(
                                                        hf_data_master.reporting_start_date,
                                                        '%Y-%m'
                                                ) BETWEEN '$year-$month'
                                                AND '$year-$month'
                                                GROUP BY
                                                        locations.pk_id
                                        ) A
                                LEFT JOIN (
                                        SELECT
                                                Uc.pk_id,
                                                SUM(

                                                        IF (
                                                                log_book.gender = 'male' || log_book.gender IS NULL,
                                                                (log_book_item_doses.doses),
                                                                0
                                                        )
                                                ) AS male_doses,
                                                SUM(

                                                        IF (
                                                                log_book.gender = 'female',
                                                                (log_book_item_doses.doses),
                                                                0
                                                        )
                                                ) AS female_doses
                                        FROM
                                                log_book
                                        LEFT JOIN locations AS Uc ON Uc.pk_id = log_book.union_council_id
                                        INNER JOIN locations AS District ON log_book.district_id = District.pk_id
                                        INNER JOIN locations AS Tehsil ON Uc.parent_id = Tehsil.pk_id
                                        INNER JOIN warehouses ON log_book.warehouse_id = warehouses.pk_id
                                        INNER JOIN locations AS ref_from_uc ON ref_from_uc.pk_id = warehouses.location_id
                                        INNER JOIN locations AS ref_from_dist ON ref_from_dist.pk_id = warehouses.district_id
                                        INNER JOIN log_book_item_doses ON log_book_item_doses.log_book_id = log_book.pk_id
                                        WHERE
                                                log_book_item_doses.item_pack_size_id = $selected_product
                                        AND log_book.district_id = $selected_district
                                        AND DATE_FORMAT(
                                                log_book.vaccination_date,
                                                '%Y-%m'
                                        ) BETWEEN '$year-$month'
                                        AND '$year-$month'
                                        GROUP BY
                                                Uc.pk_id
                                ) B ON A.pk_id = B.pk_id
                        ) A
                        RIGHT JOIN (
                                SELECT
                                        A.pk_id,
                                        A.district,
                                        A.tehsil,
                                        A.ucs,
                                        IFNULL(B.target, 0) AS target
                                FROM
                                        (
                                                SELECT
                                                        ucs.pk_id AS pk_id,
                                                        locations.location_name AS district,
                                                        tehsils.location_name AS tehsil,
                                                        ucs.location_name AS ucs
                                                FROM
                                                        locations
                                                INNER JOIN locations AS tehsils ON locations.pk_id = tehsils.parent_id
                                                INNER JOIN locations AS ucs ON tehsils.pk_id = ucs.parent_id
                                                INNER JOIN warehouses ON ucs.pk_id = warehouses.location_id
                                                WHERE
                                                        locations.geo_level_id = 4
                                                AND locations.pk_id = $selected_district
                                                GROUP BY
                                                        ucs.pk_id
                                                ORDER BY
                                                        tehsil,
                                                        ucs
                                        ) A
                                LEFT JOIN (
                                        SELECT
                                                ROUND(
                                                        COALESCE (
                                                                ROUND(
                                                                        (
                                                                                (
                                                                                        (
                                                                                                (
                                                                                                        location_populations.population * 1
                                                                                                ) / 100 * 3.5
                                                                                        )
                                                                                ) * 1
                                                                        )
                                                                ) / 12,
                                                                NULL,
                                                                0
                                                        )
                                                ) AS target,
                                                ucs.pk_id AS pk_id
                                        FROM
                                                locations
                                        INNER JOIN locations AS tehsils ON locations.pk_id = tehsils.parent_id
                                        INNER JOIN locations AS ucs ON tehsils.pk_id = ucs.parent_id
                                        INNER JOIN warehouses ON ucs.pk_id = warehouses.location_id
                                        INNER JOIN location_populations ON ucs.pk_id = location_populations.location_id
                                        WHERE
                                                locations.geo_level_id = 4
                                        AND YEAR (
                                                location_populations.estimation_date
                                        ) = '$year'
                                        AND locations.pk_id = $selected_district
                                        GROUP BY
                                                ucs.pk_id
                                ) B ON A.pk_id = B.pk_id
                        ) B ON A.pk_id = B.pk_id
	)";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getUCsStockStatus($selected_tehsil, $start_date, $end_date, $selected_product) {

        $str_sql = "
                   SELECT
	A.location_id,
	B.pk_id AS item_id,
	B.item_name,
  A.reporting_start_date,
	IFNULL(A.stock_out, 0) stock_out,
	IFNULL(A.over_stocked, 0) over_stocked
FROM
	(
		SELECT
			locations.parent_id as location_id,
      consumption_summary.reporting_start_date,
			item_pack_sizes.pk_id AS item_id,
			item_pack_sizes.item_name,
			SUM(
				CASE
				WHEN consumption_summary.mos < 0.5 THEN
					1
				ELSE
					0
				END
			) AS stock_out,
			SUM(
				CASE
				WHEN consumption_summary.mos > 1.5 THEN
					1
				ELSE
					0
				END
			) AS over_stocked
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
		INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			warehouses.stakeholder_office_id = 6
		AND locations.parent_id = $selected_tehsil
		AND DATE_FORMAT(
			consumption_summary.reporting_start_date,
			'%Y-%m-%d'
		) BETWEEN '$start_date'
		AND '$end_date'
		GROUP BY
			item_pack_sizes.pk_id,locations.parent_id,consumption_summary.reporting_start_date
	) A
RIGHT JOIN (
	SELECT
		item_pack_sizes.pk_id,
		item_pack_sizes.item_name
	FROM
		item_pack_sizes
	INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
	INNER JOIN item_activities ON item_pack_sizes.pk_id = item_activities.item_pack_size_id
	WHERE
		item_pack_sizes.item_category_id = 1
	AND item_activities.stakeholder_activity_id = 1
) B ON A.item_id = B.pk_id
                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getNumberOfTimesTalukasStoreStockOutAndOverStock($selected_district, $tehsil_id, $start_date, $end_date) {

        $str_sql = "
                   SELECT B.pk_id,
B.location_name,
A.item_id,
A.item_name,
IFNULL(A.stock_out,0) as stock_out,
IFNULL(A.over_stocked,0) as over_stocked
from
(SELECT
	warehouses.location_id,
	item_pack_sizes.pk_id AS item_id,
	item_pack_sizes.item_name,
	SUM(
		CASE
		WHEN consumption_summary.mos < 0.5 THEN
			1
		ELSE
			0
		END
	) AS stock_out,
	SUM(
		CASE
		WHEN consumption_summary.mos > 1.5 THEN
			1
		ELSE
			0
		END
	) AS over_stocked
FROM
	warehouses
	INNER JOIN locations ON warehouses.location_id = locations.pk_id
	INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
	INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
	INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
	INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
WHERE
	warehouses.district_id = $selected_district
        AND warehouses.stakeholder_office_id = 5
        AND item_pack_sizes.item_category_id = 1
        AND stakeholder_activities.activity = 'routine'
        AND DATE_FORMAT( consumption_summary.reporting_start_date, '%Y-%m-%d' ) BETWEEN '$start_date' AND '$end_date'
GROUP BY
	item_pack_sizes.pk_id,
	warehouses.location_id) A
RIGHT JOIN (
SELECT locations.pk_id,locations.location_name
from locations
where locations.district_id = $selected_district
AND locations.pk_id = $tehsil_id
AND locations.geo_level_id =5
) B ON A.location_id =  B.pk_id

                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

}

?>