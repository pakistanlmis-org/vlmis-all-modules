<?php

/**
 * Model_Van
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * 
 * @package Van (Visibility and Analytics Network Dashboards)
 * @author     Muhammad Imran 
 * @version    2.5.1
 */

/**
 *  Model for Van (Used for VAN: Visibility and Analytics Network Dashboards)
 */
class Model_Van extends Model_Base {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('HfDataMaster');
    }

    public function getLocationNameById($location_id) {
        $str_sql = "
                    SELECT
                        locations.location_name
                    FROM
                        locations
                    WHERE
                        locations.pk_id = $location_id

                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getAllMonths() {
        $str_sql = "
            SELECT
                period.period_name AS month_name,
                period.period_code AS month_code
            FROM
                period
            WHERE
                period.is_month = \"Yes\"
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getAllRoutineVaccines() {
        $str_sql = "
            SELECT
                item_pack_sizes.pk_id AS item_id,
                item_pack_sizes.item_name,
                item_pack_sizes.wastage_rate_allowed AS AcceptableLevel,
                stakeholder_activities.activity
            FROM
                item_pack_sizes
                INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
            WHERE
                item_pack_sizes.item_category_id = 1
                AND stakeholder_activities.activity = 'routine'
            ORDER BY
                item_pack_sizes.list_rank ASC
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getOneMonthCoverageForDistrict($selected_district, $month, $year) {
        $str_qry = "SELECT
                   A.district AS location_name,
                   A.item_id,
                   A.item_name,
                   A.MonthlyTarget,
                   B.Month,
                   IFNULL(B.consumption, 0) AS Vaccination,
                   ROUND((IFNULL(B.consumption, 0) / A.MonthlyTarget) * 100) AS CoveragePercentage
                   FROM
                   (
                           SELECT
                                   B.pk_id AS item_id,
                                   B.item_name,
                                   A.district,
                                   ROUND(((((A.population / 100) * B.population_percent_increase_per_year
                                             ) / 100 * B.child_surviving_percent_per_year) * B.doses_per_year) / 12) AS MonthlyTarget
                           FROM
                                   (
                                           SELECT DISTINCT
                                                   locations.location_name AS district,
                                                   (
                                                           SELECT
                                                                   IFNULL(location_populations.population,0)
                                                           FROM
                                                                   location_populations
                                                           WHERE
                                                                   location_populations.location_id = locations.pk_id
                                                           AND DATE_FORMAT(location_populations.estimation_date,'%Y') = '$year'
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
                           sum(hf_data_master.issue_balance) AS consumption,
                           hf_data_master.item_pack_size_id AS item_id,
                           DATE_FORMAT(hf_data_master.reporting_start_date,'%b-%y') AS Month
                   FROM
                           warehouses
                   INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                   INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                   WHERE
                           MONTH (hf_data_master.reporting_start_date) = '$month'
                   AND YEAR (hf_data_master.reporting_start_date) = '$year'
                   AND warehouses.pk_id = hf_data_master.warehouse_id
                   AND warehouses.district_id = '$selected_district'
                   
                   GROUP BY
                           hf_data_master.item_pack_size_id
           ) B ON A.item_id = B.item_id";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getOneMonthWastageForDistrict($selected_district, $month, $year) {
        $str_qry = "SELECT
                        hf_data_master.item_pack_size_id AS item_id,
                        item_pack_sizes.item_name AS Vaccine,
                        District.location_name,
                        Sum(hf_data_master.wastages) AS Wastage,
                        ROUND(IFNULL((sum(hf_data_master.wastages) / (sum(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS WastagePercentage,
                        item_pack_sizes.wastage_rate_allowed AS AcceptableLevel,
                        DATE_FORMAT(hf_data_master.reporting_start_date,'%b-%y') AS MONTH
                    FROM
                        locations AS District
                        INNER JOIN warehouses ON warehouses.district_id = District.pk_id
                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                        INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
                    WHERE
                        warehouses.stakeholder_id = 1
                        AND warehouses.`status` = 1
                        AND DATE_FORMAT(
					hf_data_master.reporting_start_date,
					'%Y-%m'
				) = '$year-$month'
                        AND hf_data_master.issue_balance IS NOT NULL
                        AND hf_data_master.issue_balance != 0
                        AND District.district_id = $selected_district
                        AND stakeholder_activities.activity = 'routine'
                        AND item_pack_sizes.item_category_id = 1
                    GROUP BY
                        item_pack_sizes.pk_id
                    ORDER BY
                        item_pack_sizes.list_rank ASC";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getOneMonthReportingRateForDistrict($selected_district, $month, $year) {
        $str_qry = "SELECT
                        item_pack_sizes.pk_id AS item_id,
                        item_pack_sizes.item_name,
                        consumption_summary.total_reporting_points,
                        consumption_summary.in_time_reporting,
                        consumption_summary.late_reporting,
                        consumption_summary.no_reporting,
                        ROUND(((consumption_summary.in_time_reporting + consumption_summary.late_reporting)/(consumption_summary.total_reporting_points))*100,2) AS ReportingRate
                    FROM
                        consumption_summary
                        INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
                    WHERE
                        consumption_summary.location_id = $selected_district
                        AND stakeholder_activities.activity = 'routine'
                        AND item_pack_sizes.item_category_id = 1
                        AND DATE_FORMAT(
                                consumption_summary.reporting_start_date,
                                '%Y-%m'
                        ) = '$year-$month'
                        ORDER BY
                                item_pack_sizes.list_rank ASC";

        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getPercentOfUcsStockedoutUdnerstockAdequetlyStockedOverstocked($selected_district, $current_item_id, $month, $year) {
$month = str_pad($month, 2, "0", STR_PAD_LEFT);
        $str_qry = "SELECT
        B.item_id,
	B.item_name,
        B.district_id,
	B.total_uc AS total_uc,
	ROUND((B.stock_out_uc / B.total_uc) * 100,2) AS stock_out_uc_percent,
	ROUND((B.under_stocked_uc / B.total_uc) * 100,2) AS under_stocked_uc_percent,
	ROUND((B.adequetly_stocked_uc / B.total_uc) * 100,2) AS adequetly_stocked_uc_percent,
	ROUND((B.over_stocked_uc / B.total_uc) * 100,2) AS over_stocked_uc_percent
    FROM
	(
		SELECT
			SUM(CASE WHEN A.MOS <  0.49 THEN 1 ELSE 0 END) AS stock_out_uc,
			SUM(CASE WHEN A.MOS >= 0.5  AND A.MOS <= 0.99 THEN 1 ELSE 0 END) AS under_stocked_uc,
			SUM(CASE WHEN A.MOS >= 1	  AND A.MOS <= 1.5  THEN 1 ELSE	0	END) AS adequetly_stocked_uc,
			SUM(CASE WHEN A.MOS >  1.5  THEN 1 ELSE 0 END) AS over_stocked_uc,
			SUM(1) AS total_uc,
                        A.item_id,
			A.item_name,
			A.district_id
		FROM
			(
				SELECT DISTINCT
					locations.pk_id,
					consumption_summary.mos AS MOS,
					warehouses.district_id AS district_id,
					item_pack_sizes.pk_id AS item_id,
					item_pack_sizes.item_name AS item_name
				FROM
					consumption_summary
				INNER JOIN locations ON locations.pk_id = consumption_summary.location_id
				INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
				INNER JOIN warehouses ON warehouses.location_id = locations.pk_id
				WHERE
					warehouses.district_id = $selected_district
				AND warehouses.stakeholder_office_id = 6
				AND DATE_FORMAT(
					consumption_summary.reporting_start_date,
					'%Y-%m'
				) = '$year-$month'
				AND item_pack_sizes.pk_id = $current_item_id
			) A
	) B";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getPercentOfUcsStockedout($selected_district, $month, $year) {

        $str_qry = "SELECT
	B.item_id,
	B.item_name,
	B.district_id,
	B.total_uc AS total_uc,
	ROUND((B.stock_out_uc / B.total_uc) * 100,2) AS stock_out_uc_percent
FROM
	(
		SELECT
			SUM(CASE WHEN A.MOS <  0.49 THEN 1 ELSE 0 END) AS stock_out_uc,
			SUM(1) AS total_uc,
			A.item_id,
			A.item_name,
			A.district_id
		FROM
			(
				SELECT DISTINCT
					locations.pk_id,
					consumption_summary.mos AS MOS,
					warehouses.district_id AS district_id,
					item_pack_sizes.pk_id AS item_id,
					item_pack_sizes.item_name AS item_name
FROM
consumption_summary
INNER JOIN locations ON locations.pk_id = consumption_summary.location_id
INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
INNER JOIN warehouses ON warehouses.location_id = locations.pk_id
INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
WHERE
warehouses.district_id = $selected_district AND
warehouses.stakeholder_office_id = 6 AND
DATE_FORMAT(
					consumption_summary.reporting_start_date,
					'%Y-%m'
				) = '$year-$month' AND
item_activities.stakeholder_activity_id = 1 AND
item_pack_sizes.item_category_id = 1
			) A
GROUP BY A.item_id
	) B
GROUP BY B.item_id";
        $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    public function getUCReportingRateByDistrict($selected_district, $month, $year) {

        $str_qry = "SELECT
	A.districtId,
	A.districtName,
	A.totalWH,
	IFNULL(B.reported, 0) AS reported,
	ROUND((IFNULL(B.reported, 0) / A.totalWH) * 100) AS reportingPercentage
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

    public function getAllDistricts() {
        $str_sql = "SELECT
            locations.pk_id as district_id,
            locations.location_name as district_name
            FROM
            locations
            WHERE
            locations.province_id = 2 AND 
            locations.geo_level_id = 4";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

}
