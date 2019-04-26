#!/usr/local/bin/php -q
<?php
set_time_limit(0);

include '/home/vlmispk/cron/config.php';
//include 'config.php';

echo date("d/m/Y H:i:s") . "<br>";
if (isset($_GET['input']) && !empty($_GET['input'])) {
    $input = 'AND hf_data_master.status = 0';
} else {
    $input = "";
}
$fh = fopen('/home/vlmispk/cron/update_reporting_date.txt', 'r');
//$fh = fopen('update_reporting_date.txt', 'r');
$line = fgets($fh);

$line_extract = explode('/', $line);
$date_f = $line_extract[0];
$date_t = $line_extract[1];

$between_date = 'Between ' . "'$date_f'" . ' AND ' . "'$date_t'";

fclose($fh);

$date_time_from = date("d/m/Y H:i:s");

$qry_first = "SELECT
	consumption_summary.pk_id,
	consumption_summary.location_id,
	DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') as reporting_start_date,
	locations.location_name,
	locations.geo_level_id
        FROM
                consumption_summary
        INNER JOIN locations ON consumption_summary.location_id = locations.pk_id
        where   
        DATE_FORMAT(
		consumption_summary.reporting_start_date,
		'%Y-%m'
	) $between_date ";

$queryA = $conn->query($qry_first);
$num_rows = mysqli_num_rows($queryA);
$i = 0;
// date change before completion and after query execution
$date_from = date('Y-m', strtotime("-6 month", strtotime($date_f)));
$date_to = date('Y-m', strtotime("-6 month", strtotime($date_t)));
$data_to_write = $date_from . '/' . $date_to;

$file_handle2 = fopen('/home/vlmispk/cron/update_reporting_date.txt', 'w');
//$file_handle2 = fopen('update_reporting_date.txt', 'w');
fwrite($file_handle2, $data_to_write);
fclose($file_handle2);
while ($row_first = $queryA->fetch_assoc()) {

    $pk_id = $row_first['pk_id'];
    $geo_level_id = $row_first['geo_level_id'];
    $reporting_start_date = $row_first['reporting_start_date'];
    $location_id = $row_first['location_id'];

    if ($geo_level_id == 4) {
        $where = "warehouses.district_id";
        $join = "";
    } else if ($geo_level_id == 5) {
        $join = "INNER JOIN locations ON warehouses.location_id = locations.pk_id";
        $where = "locations.parent_id";
    } else if ($geo_level_id == 6) {
        $where = "warehouses.location_id";
        $join = "";
    }

    $qry_second = "SELECT
	A.district_id,
	A.late_reporting AS late_reporting,
	IF (A.in_time_reporting > A.total_hfs, A.total_hfs,A.in_time_reporting) AS in_time_reporting,
	ABS(
		(
			A.total_hfs - (
				A.late_reporting + IF (A.in_time_reporting > A.total_hfs, A.total_hfs,A.in_time_reporting)
			)
		)
	) AS non_reporting,
	A.total_hfs
        FROM
                (SELECT
                                warehouses.location_id AS district_id,
			warehouses.pk_id AS wh_id,

	SUM(	IF (
			DATE_FORMAT(
				hf_data_master.created_date,
				'%d'
			) >= 10,
			1,
			0
		)) AS late_reporting,

	SUM(IF (
		DATE_FORMAT(
			hf_data_master.created_date,
			'%d'
		) <= 10,
		1,
		0
	)) AS in_time_reporting,
	(
		SELECT
			COUNT(
				DISTINCT warehouse_users.warehouse_id
			) AS abc
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
		WHERE
			 stakeholders.geo_level_id = 6
		AND warehouses.stakeholder_id = 1
		AND $where = '$location_id'
                AND warehouses.`status` = 1      
	) AS total_hfs
        FROM
                warehouses
        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
        INNER JOIN warehouse_users ON warehouses.pk_id = warehouse_users.warehouse_id
        $join
        WHERE
        $where = '$location_id'
        AND DATE_FORMAT(
                hf_data_master.reporting_start_date,
                '%Y-%m'
        ) = '$reporting_start_date'
        AND stakeholders.geo_level_id = 6
	AND warehouses.stakeholder_id = 1
	AND hf_data_master.item_pack_size_id = 7
	AND hf_data_master.issue_balance IS NOT NULL
	AND hf_data_master.issue_balance != 0
	AND warehouses. STATUS = 1
         $input

        GROUP BY
                warehouses.district_id 
        ) A
        GROUP BY
                A.district_id";

    $queryB = $conn->query($qry_second);

    $row_second = $queryB->fetch_assoc();
    $late_reporting = $row_second['late_reporting'];
    $in_time_reporting = $row_second['in_time_reporting'];
    $non_reporting = $row_second['non_reporting'];
    $total_hfs = $row_second['total_hfs'];

    $qry_ins = "UPDATE consumption_summary SET total_reporting_points = '$total_hfs', "
            . "in_time_reporting = '$in_time_reporting',"
            . " late_reporting = '$late_reporting',"
            . " no_reporting='$non_reporting'  where pk_id = $pk_id;";
    $conn->query($qry_ins);
    $i++;
}
// log update
$current = file_get_contents('/home/vlmispk/cron/log_file_reporting.txt');
//$current = file_get_contents('log_file_reporting.txt');
$date_time_to = date("d/m/Y H:i:s");

$data_to_write_log = $current . PHP_EOL . 'Total Records: ' . $num_rows . ' Updated Records: ' . $i . ' Time Period: ' . $date_f . ' to ' . $date_t . ' Completion Time: ' . $date_time_from . ' to ' . $date_time_to;
$file_handle1 = fopen('/home/vlmispk/cron/log_file_reporting.txt', 'w');
//$file_handle1 = fopen('log_file_reporting.txt', 'w');
fwrite($file_handle1, $data_to_write_log);
fclose($file_handle1);

mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Reporting data has been updated");


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>