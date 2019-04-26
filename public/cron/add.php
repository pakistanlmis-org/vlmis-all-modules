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

$fh = fopen('/home/vlmispk/cron/update_vaccination_date.txt', 'r');
//$fh = fopen('update_vaccination_date.txt', 'r');
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
	DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m-%d') as reporting_start_date,
	locations.location_name,
	locations.geo_level_id
FROM
	consumption_summary
INNER JOIN locations ON consumption_summary.location_id = locations.pk_id 
Where  DATE_FORMAT(
		consumption_summary.reporting_start_date,
		'%Y-%m'
	) $between_date ";

//$result_first = mysql_query($qry_first);
$queryA = $conn->query($qry_first);
$num_rows = mysqli_num_rows($queryA);
$i = 0;
// date change before completion and after query execution
$date_from = date('Y-m', strtotime("-6 month", strtotime($date_f)));
$date_to = date('Y-m', strtotime("-6 month", strtotime($date_t)));
$data_to_write = $date_from . '/' . $date_to;

$file_handle2 = fopen('/home/vlmispk/cron/update_vaccination_date.txt', 'w');
//$file_handle2 = fopen('update_vaccination_date.txt', 'w');
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
	SUM(hf_data_detail.fixed_inside_uc_male+hf_data_detail.fixed_outside_uc_male+hf_data_detail.outreach_male+hf_data_detail.outreach_outside_male+hf_data_detail.outreach_outside_male) as vaccinated_male,
	SUM(hf_data_detail.fixed_inside_uc_female+hf_data_detail.fixed_outside_uc_female+hf_data_detail.outreach_female+hf_data_detail.outreach_outside_female) as vacinated_female,
        SUM(hf_data_detail.fixed_inside_uc_male+hf_data_detail.fixed_outside_uc_male+hf_data_detail.fixed_inside_uc_female+hf_data_detail.fixed_outside_uc_female) as vacinated_fixed,
        SUM(hf_data_detail.outreach_male+hf_data_detail.outreach_outside_male+hf_data_detail.outreach_female+hf_data_detail.outreach_outside_female) as vaccinated_outreach,
        SUM(hf_data_detail.fixed_inside_uc_male+hf_data_detail.fixed_inside_uc_female) as vaccinated_inside,
        SUM(hf_data_detail.fixed_outside_uc_male+hf_data_detail.fixed_outside_uc_female) as vaccinated_outside
      FROM
              hf_data_master
      INNER JOIN hf_data_detail ON hf_data_detail.hf_data_master_id = hf_data_master.pk_id
      INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
      $join
      WHERE

      $where = '$location_id'
      AND DATE_FORMAT(
              hf_data_master.reporting_start_date,
              '%Y-%m-%d'
      ) = '$reporting_start_date'
           $input
      ";
    $queryB = $conn->query($qry_second);

    $row_second = $queryB->fetch_assoc();

    $vaccinated_male = $row_second['vaccinated_male'];
    $vacinated_female = $row_second['vacinated_female'];
    $vacinated_fixed = $row_second['vacinated_fixed'];
    $vaccinated_outreach = $row_second['vaccinated_outreach'];
    $vaccinated_inside = $row_second['vaccinated_inside'];
    $vaccinated_outside = $row_second['vaccinated_outside'];

    $qry_ins = "UPDATE consumption_summary SET vaccinated_male = '$vaccinated_male', "
            . "vacinated_female = '$vacinated_female',"
            . " vacinated_fixed = '$vacinated_fixed',"
            . " vaccinated_outreach = '$vaccinated_outreach',"
            . " vaccinated_inside = '$vaccinated_inside',"
            . " vaccinated_outside = '$vaccinated_outside'"
            . " where pk_id = $pk_id;";

    $conn->query($qry_ins);
    $i++;
}
// log update
$current = file_get_contents('/home/vlmispk/cron/log_file_vaccination.txt');
//$current = file_get_contents('log_file_vaccination.txt');
$date_time_to = date("d/m/Y H:i:s");

$data_to_write_log = $current . PHP_EOL . 'Total Records: ' . $num_rows . ' Updated Records: ' . $i . ' Time Period: ' . $date_f . ' to ' . $date_t . ' Completion Time: ' . $date_time_from . ' to ' . $date_time_to;
$file_handle1 = fopen('/home/vlmispk/cron/log_file_vaccination.txt', 'w');
//$file_handle1 = fopen('log_file_vaccination.txt', 'w');
fwrite($file_handle1, $data_to_write_log);
fclose($file_handle1);
mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Vaccination data has been updated");

echo date("d/m/Y H:i:s") . "<br>";
echo "Executed Successfully!";
?>