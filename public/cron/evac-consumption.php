<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';
$date = "2018-04-09";
$end_date = "2018-05-06";



while (strtotime($date) <= strtotime($end_date)) {
    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
    $url = "http://monitoring.punjab.gov.pk/evaccs/api/get_consumption_data?timestamp=$date";

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
    foreach ($decoded as $row0) {


        foreach ($row0 as $row) {

            $uc_code = $row['uc_code'];
            $reporting_month_date1 = $row['reporting_month_date'];
            $date_array = explode('-', $reporting_month_date1);


            $vaccines_data = $row['vaccines_data'];

            foreach ($vaccines_data as $row1) {

                $transaction_id = $row1['transaction_id'];
                $transaction_date_time = $row1['transaction_date_time'];
                $item_id = $row1['item_id'];
                $item_qty = $row1['item_qty'];

                $qry_select = "SELECT
            evac_consumption.pk_id,
            evac_consumption.transaction_id
            FROM
            evac_consumption
            WHERE
            evac_consumption.transaction_id = '$transaction_id'";
                $row_select = $conn->query($qry_select);

                $res_select = $row_select->fetch_assoc();

                if (empty($res_select)) {
                    $str_qry1 = "INSERT INTO evac_consumption
                    (evac_consumption.uc_code,
                    evac_consumption.reporting_month_date,
                    evac_consumption.transaction_id,
                    evac_consumption.transaction_date_time,
                    evac_consumption.item_id,
                    evac_consumption.item_qty,
                    evac_consumption.created_date)
                    VALUES ( '$uc_code', '$reporting_month_date1', '$transaction_id', '$transaction_date_time','$item_id','$item_qty',NOW())";
                    $conn->query($str_qry1);
                } else {
                    $id = $res_select['pk_id'];
                    $str_qry1 = "UPDATE evac_consumption SET evac_consumption.item_qty='$item_qty' Where evac_consumption.pk_id = '$id'";
                    $conn->query($str_qry1);
                }
            }
        }
        // echo $uc_code . '-' . $reporting_month_date . '-' . $transaction_id . '-' . $transaction_date_time . '-' . $item_id . '-' . $item_qty;

        $i++;
    }
}

//mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Reporting data has been updated");


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>