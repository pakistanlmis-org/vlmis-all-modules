
<?php

set_time_limit(0);

include 'config.php';




$wh_query = "SELECT DISTINCT
DATE_FORMAT(sdps_summary.reporting_start_date,'%Y-%m') AS rep,
warehouses.province_id,
sdps_summary.epi_center_count,
sdps_summary.province
FROM
sdps_summary
INNER JOIN warehouses ON sdps_summary.location_id = warehouses.pk_id
where province_id <> 10
";

$queryWh = $conn->query($wh_query);
$num_rows_wh = mysqli_num_rows($queryWh);

while ($row_wh = $queryWh->fetch_assoc()) {

    $province_id = $row_wh['province_id'];
    $rep = $row_wh['rep'];
$epi_centers = $row_wh['epi_center_count'];
$province = $row_wh['province'];
    $qry_reporting_rate = "SELECT
            count(DISTINCT hf_data_master.warehouse_id) as total
            FROM
            hf_data_master
            INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$rep'
                             AND warehouses.province_id = $province_id";

    $query_rr = $conn->query($qry_reporting_rate);
    $row_rr = $query_rr->fetch_assoc();
    $reporting_rate = ($row_rr['total'] / $epi_centers);

   



    $rr_total = $row_rr['total'];
    $qry_ins = "UPDATE sdps_summary
        SET sdps_summary.reporting_epi_center_count = '$rr_total',
        sdps_summary.reporting_rate = '$reporting_rate' where sdps_summary.province = '$province' and DATE_FORMAT(sdps_summary.reporting_start_date,'%Y-%m') = '$rep'";

    $conn->query($qry_ins);
   
}
// }
// log update


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>