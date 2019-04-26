<?php

/**
 * Model_FpDashboard
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage FP Dashboards
 * @author    ahmad saib
 * @version    2.5.1
 */

/**
 *  Model for FpDashboard
 */
class Model_FpDashboard extends Model_Base {

    public function getComplianceInventoryManagment($indicator, $year, $month) {
        $rpt_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT);
        $sql = "SELECT
	A.district_id AS dist_id,
	A.location_name AS district,
	Round(
		(
			(
				B.district_receive + D.tehsil_receive + F.hf_received 
			) / (
				A.province_issue + C.district_issue  + J.total_hf
			)
		) * 100,
		2
	) AS compliance
    FROM
            (
                    SELECT
			B.location_name,
			B.pk_id AS district_id,
			IFNULL(A.province_issue, 0) AS province_issue
		FROM
			(
				SELECT
					COUNT(
					DISTINCT	stock_master.transaction_number
					) AS province_issue,
					stock_master.from_warehouse_id,
					to_warehouse.district_id,
					locations.location_name
				FROM
					stock_master
				INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
				INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
				INNER JOIN locations ON locations.district_id = to_warehouse.district_id
				WHERE
					stock_master.transaction_type_id = 2
				AND stock_master.from_warehouse_id = 163
				AND DATE_FORMAT(
					stock_master.transaction_date,
					'%Y-%m'
				) = '$rpt_date'
				GROUP BY
					to_warehouse.district_id
			) A
		RIGHT JOIN (
			SELECT
				locations.pk_id,
				locations.location_name
			FROM
				locations
			WHERE
				locations.geo_level_id = 4
			AND locations.province_id = 2
		) B ON A.district_id = B.pk_id
	) A
        JOIN (
                SELECT
		B.location_name,
		B.pk_id AS district_id,
		IFNULL(A.district_receive, 0) AS district_receive
	FROM
		(
			SELECT
				Count(
					stock_master.transaction_number
				) AS district_receive,
				stock_master.from_warehouse_id,
				to_warehouse.district_id
			FROM
				stock_master
			INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
			INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
			WHERE
				stock_master.transaction_type_id = 1
			AND stock_master.from_warehouse_id = 163
			AND DATE_FORMAT(
				stock_master.transaction_date,
				'%Y-%m'
			) = '$rpt_date'
			GROUP BY
				to_warehouse.district_id
		) A
	RIGHT JOIN (
		SELECT
			locations.pk_id,
			locations.location_name
		FROM
			locations
		WHERE
			locations.geo_level_id = 4
		AND locations.province_id = 2
	) B ON A.district_id = B.pk_id
        ) B ON A.district_id = B.district_id
        JOIN (
                SELECT
        B.location_name,
        B.pk_id as district_id,
        IFNULL(A.district_issue,0) as district_issue
        from
        (SELECT
                        count(
                                stock_master.transaction_number
                        ) AS district_issue,
                        from_warehouse.district_id
                FROM
                        stock_master
                INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
                INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
                WHERE
                        from_warehouse.stakeholder_office_id = 4
                AND DATE_FORMAT(
                        stock_master.transaction_date,
                        '%Y-%m'
                ) = '$rpt_date'
                AND stock_master.transaction_type_id = 2
                AND from_warehouse.province_id = 2
                GROUP BY
                        from_warehouse.district_id) A
        RIGHT JOIN (SELECT
        locations.pk_id,
        locations.location_name

        FROM
        locations
        WHERE
        locations.geo_level_id = 4 AND
        locations.province_id = 2
        ) B ON A.district_id = B.pk_id
        ) C ON B.district_id = C.district_id
        JOIN (
                SELECT
        B.location_name,
        B.pk_id as district_id,
        IFNULL(A.tehsil_receive,0) as tehsil_receive
        from
        (SELECT
                        COUNT(
                                stock_master.transaction_number
                        ) AS tehsil_receive,
                        stock_master.from_warehouse_id,
                        from_warehouse.district_id
                FROM
                        stock_master
                INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
                INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
                WHERE
                        stock_master.transaction_type_id = 1
                AND to_warehouse.stakeholder_office_id = 5
                AND from_warehouse.province_id = 2
                AND DATE_FORMAT(
                        stock_master.transaction_date,
                        '%Y-%m'
                ) = '$rpt_date'
                GROUP BY
                        from_warehouse.district_id) A
        RIGHT JOIN (SELECT
        locations.pk_id,
        locations.location_name

        FROM
        locations
        WHERE
        locations.geo_level_id = 4 AND
        locations.province_id = 2
        ) B ON A.district_id = B.pk_id
        ) D ON C.district_id = D.district_id
        JOIN (
                SELECT
        B.location_name,
        B.pk_id as district_id,
        IFNULL(A.tehsil_issue,0) as tehsil_issue
        from
        (SELECT
                        COUNT(
                                stock_master.transaction_number
                        ) AS tehsil_issue,
                        stock_master.from_warehouse_id,
                        from_warehouse.district_id
                FROM
                        stock_master
                INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
                INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
                WHERE
                        stock_master.transaction_type_id = 2
                AND to_warehouse.stakeholder_office_id = 6
                AND from_warehouse.province_id = 2
                AND DATE_FORMAT(
                        stock_master.transaction_date,
                        '%Y-%m'
                ) = '$rpt_date'
                GROUP BY
                        from_warehouse.district_id) A
        RIGHT JOIN (SELECT
        locations.pk_id,
        locations.location_name

        FROM
        locations
        WHERE
        locations.geo_level_id = 4 AND
        locations.province_id = 2
        ) B ON A.district_id = B.pk_id
        ) E ON D.district_id = E.district_id
        JOIN (
                SELECT
        B.location_name,
        B.pk_id as district_id,
        IFNULL(A.hf_received,0) as hf_received
        from
        (SELECT
                       count(DISTINCT hf_data_master.warehouse_id) AS hf_received,
                        warehouses.district_id
                FROM
                        hf_data_master
                INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
                WHERE
                        warehouses.province_id = 2
                AND warehouses.stakeholder_office_id = 6
                AND DATE_FORMAT(
                        hf_data_master.reporting_start_date,
                        '%Y-%m'
                ) = '$rpt_date'
                AND hf_data_master.received_balance > 0
                AND hf_data_master.issue_balance IS NOT NULL 
            AND hf_data_master.issue_balance != 0
             AND warehouses. STATUS = 1
                GROUP BY
                        warehouses.district_id) A
        RIGHT JOIN (SELECT
        locations.pk_id,
        locations.location_name

        FROM
        locations
        WHERE
        locations.geo_level_id = 4 AND
        locations.province_id = 2
        ) B ON A.district_id = B.pk_id
        ) F ON E.district_id = F.district_id"
                . " JOIN (
        SELECT
        dis.pk_id as district_id,
        Count(warehouse_users.pk_id) as total_hf
        FROM
        warehouses
        INNER JOIN locations ON warehouses.location_id = locations.pk_id
        INNER JOIN locations AS dis ON warehouses.district_id = dis.pk_id
        INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
        WHERE
        locations.geo_level_id = 6 AND
        locations.province_id = 2 AND
         warehouses.stakeholder_id = 1 AND
        dis.geo_level_id = 4
        AND warehouses.status = 1
        GROUP BY
        dis.pk_id
    ) J ON F.district_id = J.district_id";
        $str_sql = $this->_em_read->getConnection()->prepare("$sql");

        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    public function getCompianceConsumptionReports($indicator, $year, $month) {
        $rpt_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT);
        if ($rpt_date >= '2016-12') {

            $sql = "SELECT 
                  A.district_id AS dist_id,
                  A.location_name as district,
                    ROUND((
                            (B.total - (B.total - A.non_reporting)) / B.total
                    ) * 100) AS complaince
                from
                (SELECT
                district.location_name,
                  warehouses.district_id,
                        count(
                                DISTINCT hf_data_master.warehouse_id
                        ) AS non_reporting
                FROM
                        warehouses
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                INNER JOIN locations as district ON warehouses.district_id = district.pk_id
                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
                WHERE
                        hf_data_master.issue_balance IS NOT NULL
                AND warehouses. STATUS = 1
                AND hf_data_master.issue_balance != 0
                AND stakeholders.geo_level_id = 6
                AND warehouses.stakeholder_id = 1
               
                AND DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$rpt_date'
                AND warehouses.province_id = '2'
                GROUP BY warehouses.district_id ) A
                JOIN(
               SELECT
               warehouses.district_id,
                       COUNT(
                               DISTINCT warehouse_users.warehouse_id
                       ) AS total
               FROM
                       warehouses
               INNER JOIN locations ON warehouses.location_id = locations.pk_id
               INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
               INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
               WHERE
                       warehouses.location_id <> 0
               AND warehouses. STATUS = 1
               AND stakeholders.geo_level_id = 6
               AND warehouses.stakeholder_id = 1
               AND warehouses.province_id = '2'
               GROUP BY warehouses.district_id
               ) B ON A.district_id = B.district_id";

            $str_sql = $this->_em_read->getConnection()->prepare("$sql");

            $str_sql->execute();
            return $str_sql->fetchAll();
        } else {
            $sql = "SELECT DISTINCT
        locations.pk_id as dist_id,
        locations.location_name as district,
            IF(Round(
                     (
                             (
                                     consumption_summary.late_reporting + consumption_summary.in_time_reporting
                             ) / consumption_summary.total_reporting_points
                     ) * 100

             ) >= 100,100,Round(
                     (
                             (
                                     consumption_summary.late_reporting + consumption_summary.in_time_reporting
                             ) / consumption_summary.total_reporting_points
                     ) * 100))  AS complaince
                     FROM
             consumption_summary
        INNER JOIN locations ON consumption_summary.location_id = locations.pk_id
        WHERE
        locations.geo_level_id = 4 AND
        DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') = '$rpt_date' AND
      locations.province_id = 2";

            $str_sql = $this->_em_read->getConnection()->prepare("$sql");

            $str_sql->execute();
            return $str_sql->fetchAll();
        }
    }

    public function getChildrenReceivedMeasles1($indicator, $year, $month) {
        $rpt_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT);

        $sql = "SELECT
	B.district_id AS dist_id,
	B.district AS district,
	(
		IFNULL(A.fixed_inside_uc_male_1, 0) + IFNULL(
			A.fixed_inside_uc_female_1,
			0
                    ) + IFNULL(A.outreach_male_1, 0) + IFNULL(A.outreach_female_1, 0) 
            ) AS numerator,
            Round(((B.target * 92.3) / 100)) AS denominator,
            Round(
                    (
                            IFNULL(A.fixed_inside_uc_male_1, 0) + IFNULL(
                                    A.fixed_inside_uc_female_1,
                                    0
                            ) + IFNULL(A.outreach_male_1, 0) + IFNULL(A.outreach_female_1, 0) + IFNULL(A.male_doses, 0) + IFNULL(A.female_doses, 0)
                    ) / Round(((B.target * 92.3) / 100)) * 100,
                    2
            ) AS percentmeasle1
        FROM
            (
                    (
                            SELECT
                                    A.district_id,
                                    A.fixed_inside_uc_male_1,
				A.fixed_inside_uc_female_1,
				A.outreach_male_1,
				A.outreach_female_1,
				A.referal_male_1,
				A.referal_female_1,
				B.male_doses,
				B.female_doses
			FROM
				(
					SELECT
						locations.district_id,
						SUM(
							hf_data_detail.fixed_inside_uc_male + fixed_outside_uc_male
						) AS fixed_inside_uc_male_1,
						SUM(
							hf_data_detail.fixed_inside_uc_female + fixed_outside_uc_female
						) AS fixed_inside_uc_female_1,
						SUM(
							hf_data_detail.outreach_male + outreach_outside_male
						) AS outreach_male_1,
						SUM(
							hf_data_detail.outreach_female + outreach_outside_female
						) AS outreach_female_1,
						SUM(
							hf_data_detail.referal_male
						) AS referal_male_1,
						SUM(
							hf_data_detail.referal_female
						) AS referal_female_1
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
					INNER JOIN locations ON warehouses.location_id = locations.pk_id
					WHERE
						hf_data_master.item_pack_size_id = 9
					AND hf_data_detail.vaccine_schedule_id = '1'
					AND DATE_FORMAT(
						hf_data_master.reporting_start_date,
						'%Y-%m'
					) = '$rpt_date'
					GROUP BY
						locations.district_id
				) A
			LEFT JOIN (
				SELECT
					District.district_id,
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
					log_book_item_doses.item_pack_size_id = 9
				AND log_book.district_id = '30'
				AND log_book_item_doses.doses = '1'
				AND DATE_FORMAT(
					log_book.vaccination_date,
					'%Y-%m'
				) = '$rpt_date'
				GROUP BY
					Uc.district_id
			) B ON A.district_id = B.district_id
		) A
		RIGHT JOIN (
			SELECT
				A.pk_id,
				A.district_id,
				A.district,
				A.tehsil,
				A.ucs,
				IFNULL(B.target, 0) AS target,
				IFNULL(B.measles_target, 0) AS measles_target
			FROM
				(
					SELECT
						ucs.pk_id AS pk_id,
						locations.district_id,
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
					AND locations.province_id = '2'
					GROUP BY
						locations.district_id
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
                                                                            (
                                                                                    location_populations.population
                                                                            ) * 1
                                                                    ) / 100 * 3.5
                                                            )
                                                    ) * 1
                                            )
                                    ) / 12,
                                    NULL,
                                    0
                            )
                    ) AS measles_target,
            ROUND(
                            COALESCE (
                                    ROUND(
                                            (
                                                    (
                                                            (
                                                                    (
                                                                            SUM(
                                                                                    location_populations.population
                                                                            ) * 1
                                                                    ) / 100 * 3.5
                                                            )
                                                    ) * 1
                                            )
                                    ) / 12,
                                    NULL,
                                    0
                                                )
                                        ) AS target,
                                locations.location_name,
                                locations.district_id
                                FROM
                    location_populations
                    INNER JOIN locations ON location_populations.location_id = locations.pk_id
                    WHERE
                    locations.geo_level_id = 6 AND
                    locations.province_id = 2 AND
                    Year(location_populations.estimation_date) = '$year'
                    GROUP BY locations.district_id
                                            ) B ON A.district_id = B.district_id
                                    ) B ON A.district_id = B.district_id
                            )";


        $str_sql = $this->_em_read->getConnection()->prepare("$sql");

        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    public function getChildrenReceived($indicator, $year, $month) {
        if ($indicator == 'measles1') {
            $where = "hf_data_master.item_pack_size_id = 9";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 9";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'measles2') {
            $where = "hf_data_master.item_pack_size_id = 9";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '2'";
            $where_log = "log_book_item_doses.item_pack_size_id = 9";
            $where_log1 = "AND log_book_item_doses.doses = '2'";
        } else if ($indicator == 'tt1') {
            $where = "hf_data_master.item_pack_size_id = 12";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 12";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'tt2') {
            $where = "hf_data_master.item_pack_size_id = 12";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '2'";
            $where_log = "log_book_item_doses.item_pack_size_id = 12";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'ipv') {
            $where = "hf_data_master.item_pack_size_id = 40";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 40";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'bcg') {
            $where = "hf_data_master.item_pack_size_id = 6";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 6";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'opv0') {
            $where = "hf_data_master.item_pack_size_id = 43";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '0'";
            $where_log = "log_book_item_doses.item_pack_size_id = 43";
            $where_log1 = "AND log_book_item_doses.doses = '0'";
        } else if ($indicator == 'opv1') {
            $where = "hf_data_master.item_pack_size_id = 43";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 43";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'opv2') {
            $where = "hf_data_master.item_pack_size_id = 43";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '2'";
            $where_log = "log_book_item_doses.item_pack_size_id = 43";
            $where_log1 = "AND log_book_item_doses.doses = '2'";
        } else if ($indicator == 'opv3') {
            $where = "hf_data_master.item_pack_size_id = 43";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '3'";
            $where_log = "log_book_item_doses.item_pack_size_id = 43";
            $where_log1 = "AND log_book_item_doses.doses = '3'";
        } else if ($indicator == 'penta1') {
            $where = "hf_data_master.item_pack_size_id = 7";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 7";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'penta2') {
            $where = "hf_data_master.item_pack_size_id = 7";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '2'";
            $where_log = "log_book_item_doses.item_pack_size_id = 7";
            $where_log1 = "AND log_book_item_doses.doses = '2'";
        } else if ($indicator == 'penta3') {
            $where = "hf_data_master.item_pack_size_id = 7";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '3'";
            $where_log = "log_book_item_doses.item_pack_size_id = 7";
            $where_log1 = "AND log_book_item_doses.doses = '3'";
        } else if ($indicator == 'pneumo1') {
            $where = "hf_data_master.item_pack_size_id = 8";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '1'";
            $where_log = "log_book_item_doses.item_pack_size_id = 8";
            $where_log1 = "AND log_book_item_doses.doses = '1'";
        } else if ($indicator == 'pneumo2') {
            $where = "hf_data_master.item_pack_size_id = 8";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '2'";
            $where_log = "log_book_item_doses.item_pack_size_id = 8";
            $where_log1 = "AND log_book_item_doses.doses = '2'";
        } else if ($indicator == 'pneumo3') {
            $where = "hf_data_master.item_pack_size_id = 8";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '3'";
            $where_log = "log_book_item_doses.item_pack_size_id = 8";
            $where_log1 = "AND log_book_item_doses.doses = '3'";
        } else {
            $where = "hf_data_master.item_pack_size_id = 8";
            $where1 = "AND hf_data_detail.vaccine_schedule_id = '3'";
            $where_log = "log_book_item_doses.item_pack_size_id = 8";
            $where_log1 = "AND log_book_item_doses.doses = '3'";
        }


        $rpt_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT);

        if ($indicator == 'tt1' || $indicator == 'tt2') {
            $sql = "SELECT
	B.pk_id AS dist_id,
	
	
	
	IFNULL(A.pregnant_women, 0)  AS numerator,
        B.target AS denominator,
	IFNULL(
		ROUND(
			(
				(IFNULL(A.pregnant_women, 0)) / ((B.target))
			) * 100
		),
		0
	) AS indicatorvalue
FROM
	(
		(
			SELECT
				locations.district_id pk_id,
				SUM(
					hf_data_detail.pregnant_women
				) AS pregnant_women,
				SUM(
					hf_data_detail.non_pregnant_women
				) AS non_pregnant_women
			FROM
				warehouses
			INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
			INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
			INNER JOIN locations ON warehouses.location_id = locations.pk_id
			WHERE
				hf_data_master.item_pack_size_id = 12
			$where1
			AND DATE_FORMAT(
				hf_data_master.reporting_start_date,
				'%Y-%m'
			) =  '$rpt_date'
			GROUP BY
				locations.district_id
		) A
		RIGHT JOIN (
			SELECT
				A.pk_id,
				A.district,
				IFNULL(B.target, 0) AS target
			FROM
				(
					SELECT
						locations.district_id AS pk_id,
						locations.location_name AS district
					FROM
						locations
					INNER JOIN locations AS tehsils ON locations.pk_id = tehsils.parent_id
					INNER JOIN locations AS ucs ON tehsils.pk_id = ucs.parent_id
					INNER JOIN warehouses ON ucs.pk_id = warehouses.location_id
					WHERE
						locations.geo_level_id = 4
					AND locations.province_id = '2'
					GROUP BY
						locations.district_id
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
												SUM(
													location_populations.population
												) * 1
											) / 100 * 3.57
										)
									) * 1
								)
							) / 12,
							NULL,
							0
						)
					) AS target,
					district.pk_id
				FROM
					location_populations
				INNER JOIN locations ON location_populations.location_id = locations.pk_id
				INNER JOIN locations AS district ON locations.district_id = district.pk_id
				WHERE
					locations.geo_level_id = 6
				AND YEAR (
					location_populations.estimation_date
				) = '$year'
				AND locations.province_id = '2'
				GROUP BY
					locations.district_id
			) B ON A.pk_id = B.pk_id
		) B ON A.pk_id = B.pk_id
	) ";
    
        } else {
            $sql = "SELECT
	B.district_id AS dist_id,
	B.district AS district,
	(
		IFNULL(A.fixed_inside_uc_male_1, 0) + IFNULL(
			A.fixed_inside_uc_female_1,
			0
                    ) + IFNULL(A.outreach_male_1, 0) + IFNULL(A.outreach_female_1, 0) 
            ) AS numerator,
            Round(((B.target * 92.3) / 100)) AS denominator,
            Round(
                    (
                            IFNULL(A.fixed_inside_uc_male_1, 0) + IFNULL(
                                    A.fixed_inside_uc_female_1,
                                    0
                            ) + IFNULL(A.outreach_male_1, 0) + IFNULL(A.outreach_female_1, 0) + IFNULL(A.male_doses, 0) + IFNULL(A.female_doses, 0)
                    ) / Round(((B.target * 92.3) / 100)) * 100,
                    2
            ) AS indicatorvalue
        FROM
            (
                    (
                            SELECT
                                    A.district_id,
                                    A.fixed_inside_uc_male_1,
				A.fixed_inside_uc_female_1,
				A.outreach_male_1,
				A.outreach_female_1,
				A.referal_male_1,
				A.referal_female_1,
				B.male_doses,
				B.female_doses
			FROM
				(
					SELECT
						locations.district_id,
						SUM(
							hf_data_detail.fixed_inside_uc_male 
						) AS fixed_inside_uc_male_1,
						SUM(
							hf_data_detail.fixed_inside_uc_female 
						) AS fixed_inside_uc_female_1,
						SUM(
							hf_data_detail.outreach_male 
						) AS outreach_male_1,
						SUM(
							hf_data_detail.outreach_female 
						) AS outreach_female_1,
						SUM(
							hf_data_detail.referal_male
						) AS referal_male_1,
						SUM(
							hf_data_detail.referal_female
						) AS referal_female_1
					FROM
						warehouses
					INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
					INNER JOIN hf_data_detail ON hf_data_master.pk_id = hf_data_detail.hf_data_master_id
					INNER JOIN locations ON warehouses.location_id = locations.pk_id
					WHERE
						$where
					$where1
					AND DATE_FORMAT(
						hf_data_master.reporting_start_date,
						'%Y-%m'
					) = '$rpt_date'
					GROUP BY
						locations.district_id
				) A
			LEFT JOIN (
				SELECT
					District.district_id,
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
					$where_log
				AND log_book.district_id = '30'
				$where_log1
				AND DATE_FORMAT(
					log_book.vaccination_date,
					'%Y-%m'
				) = '$rpt_date'
				GROUP BY
					Uc.district_id
			) B ON A.district_id = B.district_id
		) A
		RIGHT JOIN (
			SELECT
				A.pk_id,
				A.district_id,
				A.district,
				A.tehsil,
				A.ucs,
				IFNULL(B.target, 0) AS target,
				IFNULL(B.measles_target, 0) AS measles_target
			FROM
				(
					SELECT
						ucs.pk_id AS pk_id,
						locations.district_id,
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
					AND locations.province_id = '2'
					GROUP BY
						locations.district_id
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
                                                                            (
                                                                                    location_populations.population
                                                                            ) * 1
                                                                    ) / 100 * 3.5
                                                            )
                                                    ) * 1
                                            )
                                    ) / 12,
                                    NULL,
                                    0
                            )
                    ) AS measles_target,
            ROUND(
                            COALESCE (
                                    ROUND(
                                            (
                                                    (
                                                            (
                                                                    (
                                                                            SUM(
                                                                                    location_populations.population
                                                                            ) * 1
                                                                    ) / 100 * 3.5
                                                            )
                                                    ) * 1
                                            )
                                    ) / 12,
                                    NULL,
                                    0
                                                )
                                        ) AS target,
                                locations.location_name,
                                locations.district_id
                                FROM
                    location_populations
                    INNER JOIN locations ON location_populations.location_id = locations.pk_id
                    WHERE
                    locations.geo_level_id = 6 AND
                    locations.province_id = 2 AND
                    Year(location_populations.estimation_date) = '$year'
                    GROUP BY locations.district_id
                                            ) B ON A.district_id = B.district_id
                                    ) B ON A.district_id = B.district_id
                            )";
        }



        $str_sql = $this->_em_read->getConnection()->prepare("$sql");

        $str_sql->execute();
        return $str_sql->fetchAll();
    }

}
