
<?php

set_time_limit(0);

include 'config.php';

echo date("d/m/Y h:i:s") . "<br>";
if (isset($_GET['input']) && !empty($_GET['input'])) {
    $input = 'AND hf_data_master.status = 0';
} else {
    $input = "";
}

$fh = fopen('update_date.txt', 'r');
$line = fgets($fh);

$line_extract = explode('/', $line);
$date_f = $line_extract[0];
$date_t = $line_extract[1];

$between_date = 'Between ' . "'$date_f'" . ' AND ' . "'$date_t'";

fclose($fh);


$qry_first = "(
	SELECT DISTINCT
		warehouses.district_id,
		DATE_FORMAT(
			hf_data_master.reporting_start_date,
			'%Y-%m-%d'
		) AS RMonth,
		hf_data_master.item_pack_size_id,
		warehouses.stakeholder_id,
		warehouses.stakeholder_office_id officelevel
	FROM
		hf_data_master
	INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
	WHERE
		warehouses.stakeholder_office_id IN (5, 6)
	AND warehouses.province_id = 2
	AND DATE_FORMAT(
		hf_data_master.reporting_start_date,
		'%Y-%m'
	) $between_date
         $input
	ORDER BY
		warehouses.district_id ASC,
		RMonth ASC,
		hf_data_master.item_pack_size_id ASC  LIMIT 1
)
UNION
	(
		SELECT DISTINCT
			warehouses.province_id AS district_id,
			DATE_FORMAT(
				hf_data_master.reporting_start_date,
				'%Y-%m-%d'
			) AS RMonth,
			hf_data_master.item_pack_size_id,
			warehouses.stakeholder_id,
			warehouses.stakeholder_office_id AS officelevel
		FROM
			hf_data_master
		INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
		WHERE
			warehouses.stakeholder_office_id IN (4)
		AND warehouses.province_id = 2
		AND DATE_FORMAT(
			hf_data_master.reporting_start_date,
			'%Y-%m'
		)  $between_date
                 $input
		ORDER BY
			warehouses.district_id ASC,
			RMonth ASC,
			hf_data_master.item_pack_size_id ASC LIMIT 1
	)";

//$result_first = mysql_query($qry_first);
$queryA = $conn->query($qry_first);
$num_rows = mysqli_num_rows($queryA);
while ($row_first = $queryA->fetch_assoc()) {

    $type = 'UD';

    $levl = $row_first['officelevel'];

    if ($levl == 4) {
        $type = 'D';
    }

    if ($levl == 5) {
        $type = 'TD';
    }

    $dist_id = $row_first['district_id'];
    $RMonth = $row_first['RMonth'];
    $item_pack_size_id = $row_first['item_pack_size_id'];
    $stakeholder_id = $row_first['stakeholder_id'];

    $qry_second = "CALL REPgetData('" . $type . "','" . $RMonth . "','" . $dist_id . "','" . $item_pack_size_id . "','" . $stakeholder_id . "')";
    $queryB = $conn->query($qry_second);
    mysqli_next_result($conn);
    $i = 1;
    while ($row_second = $queryB->fetch_assoc()) {
        $location_id = $row_second['uc_id'];
        $soh = $row_second['SOH'];
        $mos = $row_second['MOS'];

        if ($levl == 4) {
            $location_id = $row_second['district_id'];
            $soh = $row_second['district_store_SOH'];
            $mos = $row_second['district_store_MOS'];
        }

        if ($levl == 5) {
            $location_id = $row_second['tehsil_id'];
        }

        $qry_ins1 = "Select * FROM consumption_summary WHERE consumption_summary.location_id = $location_id AND consumption_summary.item_pack_size_id = $item_pack_size_id  AND consumption_summary.reporting_start_date = '" . $RMonth . "'; ";

        $query = $conn->query($qry_ins1);

        $row = $query->fetch_assoc();

        if (count($row) > 0) {
            $pk_id = $row['pk_id'];
            $qry_ins = "Update consumption_summary"
                    . " SET location_id='$location_id',"
                    . "item_pack_size_id='$item_pack_size_id',"
                    . "consumption='" . $row_second['Vaccinated'] . "',"
                    . " amc='" . $row_second['AMC'] . "',"
                    . " mos='" . $mos . "',"
                    . " soh='" . $soh . "',"
                    . " reporting_start_date='" . $RMonth . "'"
                    . " where pk_id = $pk_id;";

            $conn->query($qry_ins);
        } else {
            $qry_ins = "INSERT INTO consumption_summary (location_id, item_pack_size_id, consumption, amc, mos, soh, reporting_start_date) VALUES('" . $location_id . "','" . $item_pack_size_id . "','" . $row_second['Vaccinated'] . "','" . $row_second['AMC'] . "','" . $mos . "','" . $soh . "','" . $RMonth . "');";
            $conn->query($qry_ins);
        }
    }
    $i++;
}

// log update
$current = file_get_contents('log_file.txt');

$data_to_write_log = $current . PHP_EOL . 'Total Records: ' . $num_rows . ' Updated Records: ' . $i . ' Time Period: ' . $date_f . ' to ' . $date_t;
$file_handle1 = fopen('log_file.txt', 'w');
fwrite($file_handle1, $data_to_write_log);
fclose($file_handle1);

// date change
$date_from = date('Y-m', strtotime("-3 month", strtotime($date_f)));
$date_to = date('Y-m', strtotime("-3 month", strtotime($date_t)));
$data_to_write = $date_from . '/' . $date_to;


$file_handle2 = fopen('update_date.txt', 'w');
fwrite($file_handle2, $data_to_write);
fclose($file_handle2);

echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>