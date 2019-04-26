
<?php

set_time_limit(0);

include 'config.php';



$date_sel = "2016-12";
$end_date = "2018-03";

while (strtotime($date_sel) <= strtotime($end_date)) {

    $date_sel = date("Y-m", strtotime("+1 month", strtotime($date_sel)));
   
    
    
    $startDate = date('Y-m-01', strtotime($date_sel));
    $endDate = date('Y-m-t', strtotime($date_sel));
    $year = date('Y', strtotime($date_sel));
    $wh_query = "SELECT
            locations.pk_id,
            locations.location_name
            FROM
            locations
            WHERE
            locations.parent_id = 10 AND
            locations.pk_id <> 10
            ";

    $queryWh = $conn->query($wh_query);
    $num_rows_wh = mysqli_num_rows($queryWh);
    while ($row_wh = $queryWh->fetch_assoc()) {

        $province = $row_wh['pk_id'];
        $province_name = $row_wh['location_name'];
        $qry1 = "SELECT
	item_pack_sizes.pk_id pkId,
	item_pack_sizes.description itemName,
        item_pack_sizes.wastage_rate_allowed
        FROM
                item_pack_sizes
        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                item_pack_sizes.`status` = 1
        AND item_pack_sizes.item_category_id = 1
        AND item_activities.stakeholder_activity_id = 1
        ORDER BY
                item_pack_sizes.list_rank ASC";
        $query1 = $conn->query($qry1);


        while ($row1 = $query1->fetch_assoc()) {


            $product = $row1['pkId'];
            $product_name = $row1['itemName'];
            $wastage_rate_allowed = $row1['wastage_rate_allowed'];
            $qryA = "SELECT
            Sum(hf_data_master.wastages) AS wastage,
            SUM(hf_data_master.issue_balance) as consumption
            
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            WHERE
            warehouses.province_id = $province 
            AND Date_format(hf_data_master.reporting_start_date,'%Y-%m') = '$date_sel'
            AND  hf_data_master.item_pack_size_id = '$product'";

            $queryA = $conn->query($qryA);
            $row_first = $queryA->fetch_assoc();

            $qryB = "SELECT
	*
        FROM
	(
		SELECT
			locations.location_name,
			SUM(
				CASE
				WHEN COALESCE (A.wastagePer, NULL, 0) >= $wastage_rate_allowed THEN
					1
				ELSE
					0
				END
			) AS over,
			SUM(
				CASE
				WHEN COALESCE (A.wastagePer, NULL, 0) < $wastage_rate_allowed THEN
					1
				ELSE
					0
				END
			) AS withn_range,
			COALESCE (A.wastagePer, NULL, 0) AS wastagePer
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
					) AS wastagePer,
					warehouses.location_id
				FROM
					warehouses
				INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
				INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
				WHERE
					warehouses.stakeholder_id = 1
				AND Date_format(
					hf_data_master.reporting_start_date,
					'%Y-%m'
				) = '$date_sel'
				AND hf_data_master.issue_balance IS NOT NULL
				AND hf_data_master.item_pack_size_id = '$product'
				AND stakeholders.pk_id = 6
				AND warehouses. STATUS = 1
				GROUP BY
					warehouses.pk_id
			) A
		RIGHT JOIN locations ON locations.pk_id = A.location_id
		WHERE
			locations.geo_level_id = 6
		AND locations.province_id = '$province'
	) AS A
        ORDER BY
	wastagePer DESC";

            $queryB = $conn->query($qryB);
            $row_sec = $queryB->fetch_assoc();

            $sdps_within = $row_sec['withn_range'];
            $sdps_over = $row_sec['over'];
            $issue_balance = $row_first['consumption'];

            $wastages = $row_first['wastage'];

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
                $waste_not_per = '0';
            } else if ($wastageP <= $waste_not_per) {
                $waste_not_per = '0';
            } else {
                $wastage_rem = $wastageP_to - $wastage_rate_allowed;
                $toal_doses_rem = ($wastage_rem * $total) / 100;
                $waste_not_per = $toal_doses_rem;
            }



            $qry_ins = "INSERT INTO wastages_cost_summary (
	wastages_cost_summary.province_id,
        wastages_cost_summary.province_name,
        wastages_cost_summary.consumption,
        wastages_cost_summary.wastages,
        wastages_cost_summary.wastages_rage_allowed,
        wastages_cost_summary.permissible_wastage,
        wastages_cost_summary.over_wastage,
        wastages_cost_summary.sdps_within_range_wastage,
        wastages_cost_summary.sdps_over_wastage,
        wastages_cost_summary.item_pack_size_id,
        wastages_cost_summary.item_name,
        wastages_cost_summary.reporting_date,
        wastages_cost_summary.created_by,
        wastages_cost_summary.created_date,
        wastages_cost_summary.modified_by,
        wastages_cost_summary.modified_date)
        VALUES
        (  
           '$province',
           '$province_name',
           '$issue_balance',
           '$wastages',
           '$wastage_rate_allowed',
           '$was_per',
           '$waste_not_per',
           '$sdps_within',
           '$sdps_over',
           '$product',
           '$product_name',
           '$startDate',
           '1',
           NOW(),
           '1',
           NOW()
                   
        )
        ";

            $conn->query($qry_ins);
        }
    }
}
// log update


    echo date("d/m/Y h:i:s") . "<br>";
    echo "Executed Successfully!";
?>