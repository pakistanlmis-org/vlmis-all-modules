
<?php

set_time_limit(0);

include 'config.php';



$date_sel = "2018-05";
$startDate = date('Y-m-01', strtotime($date_sel));
$endDate = date('Y-m-t', strtotime($date_sel));
$year = date('Y', strtotime($date_sel));
$wh_query = "SELECT DISTINCT
warehouses.pk_id,
	warehouses.warehouse_name,
	warehouses.pk_id AS warehouse_id,
	locations.location_name,
	locations.pk_id AS location_id,
	locations.geo_level_id
FROM
	warehouses
INNER JOIN locations ON warehouses.location_id = locations.pk_id
WHERE
	warehouses.stakeholder_office_id =  4
";

$queryWh = $conn->query($wh_query);
$num_rows_wh = mysqli_num_rows($queryWh);

while ($row_wh = $queryWh->fetch_assoc()) {

    $wh_id = $row_wh['warehouse_id'];
    $location_id = $row_wh['location_id'];
    $geo_level_id = $row_wh['geo_level_id'];

    if ($geo_level_id == 2 && $location_id == 10) {
        $l_id = 10;
        $l_name = 'National';
    } else if ($geo_level_id == 2 && $location_id != 10) {
        $l_id = $location_id;
        $l_name = 'Province';
    } else if ($geo_level_id == 4) {
        $l_id = $location_id;
        $l_name = 'District';
    } else if ($geo_level_id == 5) {
        $l_id = $location_id;
        $l_name = 'Tehsil';
    }



    if ($geo_level_id == 2 && $location_id == 10) {
        $qry1 = "SELECT
                SUM(case when locations.geo_level_id=2 && locations.pk_id <> 10  then 1 else 0 end) as province,
                SUM(case when locations.geo_level_id=4   then 1 else 0 end) as district,
                SUM(case when locations.geo_level_id=5   then 1 else 0 end) as tehsil,
                SUM(case when locations.geo_level_id=6   then 1 else 0 end) as uc,
        (SELECT
        SUM(case when warehouses.stakeholder_office_id=6 && warehouses.status=1   then 1 else 0 end) as epi_centers
        FROM
        warehouses) as epi_centers,
        (SELECT
	
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
	) AS target
        FROM
                location_populations
        INNER JOIN locations AS province ON location_populations.location_id = province.pk_id
        INNER JOIN locations AS national ON province.parent_id = national.pk_id
        WHERE
         YEAR (
                location_populations.estimation_date
        ) = '$year'
        AND province.geo_level_id = 2
        GROUP BY
                national.pk_id) as target
                FROM
                locations";
    } else if ($geo_level_id == 2 && $location_id != 10) {
        $qry1 = "SELECT
                locations.location_name as province,
                SUM(case when locations.geo_level_id=4   then 1 else 0 end) as district,
                SUM(case when locations.geo_level_id=5   then 1 else 0 end) as tehsil,
                SUM(case when locations.geo_level_id=6   then 1 else 0 end) as uc,
        (SELECT
        SUM(case when warehouses.stakeholder_office_id=6 && warehouses.status=1   then 1 else 0 end) as epi_centers
        FROM
        warehouses WHERE province_id= $l_id) as epi_centers,
        (SELECT
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
	) AS target
FROM
	location_populations
INNER JOIN locations AS province ON location_populations.location_id = province.pk_id

WHERE
	YEAR (
		location_populations.estimation_date
	) = '$year'
AND province.geo_level_id = 2
AND province.province_id = $l_id

GROUP BY
	province.province_id) as target
                FROM
                locations
where province_id = $l_id";
    } else if ($geo_level_id == 4) {
        $l_id = $location_id;
        $l_name = 'District';
        $qry1 = "SELECT
                province.location_name as province,
                locations.location_name as district,
                SUM(case when locations.geo_level_id=5   then 1 else 0 end) as tehsil,
                SUM(case when locations.geo_level_id=6   then 1 else 0 end) as uc,
        (SELECT
        SUM(case when warehouses.stakeholder_office_id=6 && warehouses.status=1   then 1 else 0 end) as epi_centers
        FROM
        warehouses WHERE district_id= $l_id) as epi_centers,
        (SELECT
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
	) AS target
FROM
	location_populations
INNER JOIN locations AS province ON location_populations.location_id = province.pk_id

WHERE
	YEAR (
		location_populations.estimation_date
	) = '$year'
AND province.geo_level_id = 4
AND province.district_id = $l_id

GROUP BY
	province.district_id) as target
                FROM
                locations
INNER JOIN locations as province ON province.pk_id = locations.province_id
where locations.district_id = $l_id";
    } else if ($geo_level_id == 5) {
        $l_id = $location_id;
        $l_name = 'Tehsil';
        $qry1 = "SELECT
	prov.location_name AS province,
	dis.location_name AS district,
	teh.location_name tehsil,
	SUM(
		CASE
		WHEN locations.geo_level_id = 6 THEN
			1
		ELSE
			0
		END
	) AS uc,
	(
		SELECT
			SUM(
				CASE
				WHEN warehouses.stakeholder_office_id = 6 && warehouses. STATUS = 1 THEN
					1
				ELSE
					0
				END
			) AS epi_centers
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		WHERE
			locations.parent_id = $l_id
	) AS epi_centers,
	(
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
									) / 100 * 3.5
								)
							) * 1
						)
					) / 12,
					NULL,
					0
				)
			) AS target
		FROM
			location_populations
		INNER JOIN locations AS province ON location_populations.location_id = province.pk_id
		WHERE
			YEAR (
				location_populations.estimation_date
			) = '$year'
		AND province.geo_level_id = 6
		AND province.parent_id = $l_id
		GROUP BY
			province.parent_id
	) AS target
FROM
locations
INNER JOIN locations AS teh ON locations.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id
INNER JOIN locations AS prov ON dis.province_id = prov.pk_id
WHERE
locations.parent_id = $l_id";
    }



    $query_total = $conn->query($qry1);
    $row_t = $query_total->fetch_assoc();

    $province_total = $row_t['province'];
    $district_total = $row_t['district'];
    $tehsil_total = $row_t['tehsil'];
    $uc_total = $row_t['uc'];
    $epi_centers = $row_t['epi_centers'];
    $target = $row_t['target'];

    if ($geo_level_id == 2 && $location_id == 10) {
        $qry_reporting_rate = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'";
        $query_rr = $conn->query($qry_reporting_rate);
        $row_rr = $query_rr->fetch_assoc();
        $reporting_rate = Round(($row_rr['total'] / $epi_centers) * 100, 2);

        $qry_in_time_reporting = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'
            AND 
            MONTH(hf_data_master.reporting_start_date) <= '10'";

        $query_in_rr = $conn->query($qry_in_time_reporting);
        $row_in_rr = $query_in_rr->fetch_assoc();
        $reporting_in_rate = Round(($row_in_rr['total'] / $row_rr['total']) * 100, 2);
    } else if ($geo_level_id == 2 && $location_id != 10) {
        $qry_reporting_rate = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'
                             AND warehouses.province_id = $l_id";

        $query_rr = $conn->query($qry_reporting_rate);
        $row_rr = $query_rr->fetch_assoc();
        $reporting_rate = Round(($row_rr['total'] / $epi_centers) * 100, 2);

        $qry_in_time_reporting = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'
            AND 
            MONTH(hf_data_master.reporting_start_date) <= '10'"
                . " AND warehouses.province_id = $l_id";

        $query_in_rr = $conn->query($qry_in_time_reporting);
        $row_in_rr = $query_in_rr->fetch_assoc();
        $reporting_in_rate = Round(($row_in_rr['total'] / $row_rr['total']) * 100, 2);
    } else if ($geo_level_id == 4) {
        $qry_reporting_rate = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel' "
                . " AND warehouses.district_id = $l_id";
        $query_rr = $conn->query($qry_reporting_rate);
        $row_rr = $query_rr->fetch_assoc();
        $reporting_rate = Round(($row_rr['total'] / $epi_centers) * 100, 2);

        $qry_in_time_reporting = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'
            AND 
            MONTH(hf_data_master.reporting_start_date) <= '10' "
                . " AND warehouses.district_id = $l_id ";

        $query_in_rr = $conn->query($qry_in_time_reporting);
        $row_in_rr = $query_in_rr->fetch_assoc();
        $reporting_in_rate = Round(($row_in_rr['total'] / $row_rr['total']) * 100, 2);
    } else if ($geo_level_id == 5) {
        $qry_reporting_rate = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            INNER JOIN locations ON locations.pk_id = warehouses.location_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel' "
                . " AND locations.parent_id = $l_id";
        $query_rr = $conn->query($qry_reporting_rate);
        $row_rr = $query_rr->fetch_assoc();
        $reporting_rate = Round(($row_rr['total'] / $epi_centers) * 100, 2);

        $qry_in_time_reporting = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            INNER JOIN locations ON locations.pk_id = warehouses.location_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'
            AND 
            MONTH(hf_data_master.reporting_start_date) <= '10' "
                . " AND locations.parent_id = $l_id";

        $query_in_rr = $conn->query($qry_in_time_reporting);
        $row_in_rr = $query_in_rr->fetch_assoc();
        $reporting_in_rate = Round(($row_in_rr['total'] / $row_rr['total']) * 100, 2);
    }

    $qry_item = "SELECT
    item_pack_sizes.item_name,
    item_pack_sizes.pk_id,
    item_pack_sizes.wastage_rate_allowed
    FROM
    item_pack_sizes
    WHERE
    item_pack_sizes.item_category_id = 1 AND
    item_pack_sizes.`status` = 1";
    $queryItem = $conn->query($qry_item);
    $num_rows_item = mysqli_num_rows($queryItem);
    while ($row_item = $queryItem->fetch_assoc()) {
        $item_id = $row_item['pk_id'];
        $item_name = $row_item['item_name'];
        $wastage_rate_allowed = $row_item['wastage_rate_allowed'];
        $str_qry = "SELECT
           SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') < '$startDate', stock_detail.quantity, 0))*item_pack_sizes.number_of_doses AS opening_balance,
           SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id = 1, stock_detail.quantity, 0))*item_pack_sizes.number_of_doses AS received_balance,
           SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id = 2, ABS(stock_detail.quantity), 0))*item_pack_sizes.number_of_doses AS issue_balance,
           SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id > 2 AND transaction_types.nature = '+', stock_detail.quantity, 0)) AS vials_used,
           ABS(SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id > 2 AND transaction_types.nature = '-', stock_detail.quantity, 0))) AS adjustments,
           SUM(stock_detail.quantity)*item_pack_sizes.number_of_doses AS closing_balance
           FROM
           stock_master
           INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
           INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
           INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
           INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
           INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
           INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
           INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
           WHERE
            DATE_FORMAT(
             stock_master.transaction_date,
             '%Y-%m-%d'
            ) <= '$endDate' AND
              DATE_FORMAT(
                stock_batch.expiry_date,
                '%Y-%m-%d'
        ) >= '$endDate'
           AND stock_batch_warehouses.warehouse_id = $wh_id
           AND stock_master.draft = 0
           AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id" .
                " ORDER BY item_pack_sizes.list_rank";



//$result_first = mysql_query($qry_first);
        $queryA = $conn->query($str_qry);
        $num_rows = mysqli_num_rows($queryA);
        while ($row_first = $queryA->fetch_assoc()) {

            $opening_balance = $row_first['opening_balance'];
            $received_balance = $row_first['received_balance'];
            $issue_balance = $row_first['issue_balance'];
            $vials_used = $row_first['vials_used'];
            $closing_balance = $row_first['closing_balance'];
            $adjustments = $row_first['adjustments'];
            if ($vials_used == 0) {
                $wastages = 0;
            } else {
                $wastages = $row_first['issue_balance'] - $row_first['vials_used'];
            }
            $total = $issue_balance + $wastages;

            // allowed wastages
            $wastageP = Round(($wastages / $total) * 100);
            $wastageP_to = ($wastages / $total) * 100;
            $toal_doses = ($wastageP_to * $total) / 100;
            if ($wastageP <= $wastage_rate_allowed) {
                $was_per = $toal_doses;
            } else if ($wastageP > $wastage_rate_allowed) {
                $wastage_rem = $wastage_rate_allowed;
                $toal_doses_rem = ($wastage_rem * $total) / 100;
                $was_per = $toal_doses_rem;
            }
            // not allowed wastages


            $waste_not_per = $wastage_rate_allowed;

            if ($wastageP == $waste_not_per) {
                $waste_not_per = '';
            } else if ($wastageP <= $waste_not_per) {
                $waste_not_per = '';
            } else {
                $wastage_rem = $wastageP_to - $wastage_rate_allowed;
                $toal_doses_rem = ($wastage_rem * $total) / 100;
                $waste_not_per = $toal_doses_rem;
            }

//            $qry_ins1 = "Select * FROM district_summary WHERE district_summary.location_id = 1 AND district_summary.item_pack_size_id = 6  AND district_summary.reporting_start_date = '2016-01'; ";
//
//            $query = $conn->query($qry_ins1);
//
//            $row = $query->fetch_assoc();
//
//            if (count($row) > 0) {
//                $pk_id = $row['pk_id'];
//                $qry_ins = "Update district_summary"
//                        . " SET location_id='$location_id',"
//                        . "item_pack_size_id='$item_pack_size_id',"
//                        . "consumption='" . $row_second['Vaccinated'] . "',"
//                        . " amc='" . $row_second['AMC'] . "',"
//                        . " soh='" . $soh . "',"
//                        . " reporting_start_date='" . $RMonth . "'"
//                        . " where pk_id = $pk_id;";
//
//                //    $conn->query($qry_ins);
//            } else {





            $qry_ins = "INSERT INTO district_summary (
	
	district_summary.location_id,
	district_summary.province,
	district_summary.districts,
	district_summary.tehsils,
	district_summary.ucs,
	district_summary.epi_centers,
        district_summary.item_name,
	district_summary.item_pack_size_id,
	district_summary.targets,
	district_summary.opening_balance,
	district_summary.received,
	district_summary.consumption,
	district_summary.amc,
	district_summary.soh,
        district_summary.wastage_rate_allowed,
	district_summary.wastages,
	district_summary.allowed_wastages,
	district_summary.over_wastages,
	district_summary.reporting_start_date,
	district_summary.adjustments,
	district_summary.reporting_rate,
	
	district_summary.created_by,
	district_summary.created_date,
	district_summary.modified_by,
	district_summary.modified_date)
        VALUES
        (  
           
           '$l_id',
           '$province_total',
           '$district_total',
           '$tehsil_total',
           '$uc_total',
           '$epi_centers',
           '$item_name',    
           '$item_id',
           '$target',
           '$opening_balance',
           '$received_balance',
           '$issue_balance',
           '$target',
           '$closing_balance',
           '$wastage_rate_allowed',    
           '$wastages',
           '$was_per',
           '$waste_not_per',
           '$startDate',
           '$adjustments',
           '$reporting_rate',
           
           '1',
           NOW(),
           '1',
           NOW()
                   
        )
        ";

            $conn->query($qry_ins);
        }
        // }
    }
}

// log update


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>