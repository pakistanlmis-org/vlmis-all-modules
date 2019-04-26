<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';

$token = sha1(md5("epivlmis#,0%$#communication" . date("Y-m-d")));




$url = "http://epimis.kphealth.pk/API/Communication/getUCsPopulations?token=$token";

$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL, $url);
// Execute
$result = curl_exec($ch);
// Closing
curl_close($ch);


$decoded = json_decode($result, true);


$i = 0;

foreach ($decoded['data'] as $key1 => $row0) {

   
        $UC_CODE = $row0['UC_CODE'];
        $UC_POPULATION = $row0['UC_POPULATION'];


        $str_qry1 = "UPDATE location_populations 

                INNER JOIN locations ON location_populations.location_id = locations.pk_id
                SET population = '$UC_POPULATION'
                WHERE
                locations.geo_level_id = 6 AND
                locations.province_id = 3 
                AND dhis_code = '$UC_CODE'";

        $conn->query($str_qry1);
    
    // echo $uc_code . '-' . $reporting_month_date . '-' . $transaction_id . '-' . $transaction_date_time . '-' . $item_id . '-' . $item_qty;
}

//mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Reporting data has been updated");



echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>