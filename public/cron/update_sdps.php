
<?php

set_time_limit(0);

include 'config.php';



$date_sel = "2018-02";
$startDate = date('Y-m-01', strtotime($date_sel));
$endDate = date('Y-m-t', strtotime($date_sel));
$year = date('Y', strtotime($date_sel));
$wh_query = "SELECT
'EPI Centers' level,
prov.location_name as prov,
dis.location_name as dis,
teh.location_name as teh,
uc.location_name as uc,
warehouses.warehouse_name,
warehouses.pk_id,
hf_data_master.item_pack_size_id,
hf_data_master.opening_balance,
hf_data_master.received_balance,
hf_data_master.issue_balance,
hf_data_master.closing_balance,
hf_data_master.wastages,
hf_data_master.reporting_start_date,
item_pack_sizes.wastage_rate_allowed,
ROUND(
		COALESCE (
			ROUND(
				(
					(
						(
							(
								SUM(
									warehouse_population.facility_total_pouplation
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
hf_data_master
INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id
INNER JOIN locations AS prov ON dis.province_id = prov.pk_id
INNER JOIN warehouse_population ON warehouse_population.warehouse_id = warehouses.pk_id
INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
WHERE
	DATE_FORMAT(
		hf_data_master.reporting_start_date,
		'%Y-%m'
	) = '$date_sel'

AND warehouses.`status` = 1
GROUP BY warehouses.warehouse_name,hf_data_master.item_pack_size_id,hf_data_master.reporting_start_date
";


$queryWh = $conn->query($wh_query);
$num_rows_wh = mysqli_num_rows($queryWh);
while ($row_wh = $queryWh->fetch_assoc()) {

    $l_name = $row_wh['level'];
    $prov = $row_wh['prov'];
    $dis = $row_wh['dis'];
    $teh = $row_wh['teh'];
    $uc = $row_wh['uc'];
    $warehouse_name = $row_wh['warehouse_name'];
    $location_id = $row_wh['pk_id'];
    $item_pack_size_id = $row_wh['item_pack_size_id'];
    $opening_balance = $row_wh['opening_balance'];
    $received_balance = $row_wh['received_balance'];
    $issue_balance = $row_wh['issue_balance'];

    $closing_balance = $row_wh['closing_balance'];
    $wastages = $row_wh['wastages'];

    $target = $row_wh['target'];
    $reporting_start_date = $row_wh['reporting_start_date'];
    $wastage_rate_allowed = $row_wh['wastage_rate_allowed'];
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

//    $qry_ins1 = "Select pk_id FROM sdps_summary WHERE sdps_summary.location_id = 1 AND sdps_summary.item_pack_size_id = 6  AND sdps_summary.reporting_start_date = '2016-01'; ";
//
//    $query = $conn->query($qry_ins1);
//
//    $row = $query->fetch_assoc();
//
//    if (count($row) > 0) {
//        $pk_id = $row['pk_id'];
//        $qry_ins = "Update sdps_summary"
//                . " SET location_id='$location_id',"
//                . "item_pack_size_id='$item_pack_size_id',"
//                . "consumption='" . $row_second['Vaccinated'] . "',"
//                . " amc='" . $row_second['AMC'] . "',"
//                . " soh='" . $soh . "',"
//                . " reporting_start_date='" . $RMonth . "'"
//                . " where pk_id = $pk_id;";
//
//        //    $conn->query($qry_ins);
//    } else {
        $qry_ins = "INSERT INTO sdps_summary (
	
	sdps_summary.location_id,
	sdps_summary.province,
	sdps_summary.districts,
	sdps_summary.tehsils,
	sdps_summary.ucs,
	sdps_summary.epi_centers,
	sdps_summary.item_pack_size_id,
	sdps_summary.targets,
	sdps_summary.opening_balance,
	sdps_summary.received,
	sdps_summary.consumption,
	sdps_summary.amc,
	sdps_summary.soh,
	sdps_summary.wastages,
	sdps_summary.allowed_wastages,
	sdps_summary.over_wastages,
	sdps_summary.reporting_start_date,
	
	
	sdps_summary.created_by,
	sdps_summary.created_date,
	sdps_summary.modified_by,
	sdps_summary.modified_date
       )
        VALUES
        (  
          
           '$location_id',
           '$prov',
           '$dis',
           '$teh',
           '$uc',
           '$warehouse_name',
           '$item_pack_size_id',
           '$target',
           '$opening_balance',
           '$received_balance',
           '$issue_balance',
           '$target',
           '$closing_balance',
           '$wastages',
           '$was_per',
           '$waste_not_per',
           '$startDate',
           
           '1',
           NOW(),
           '1',
           NOW()
                   
        )
        ";
       

        $conn->query($qry_ins);
  //  }
}

// log update


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>