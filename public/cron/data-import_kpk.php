<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';

$qry = "SELECT

	users.pk_id,
	users.user_name,
	users.`password`,
	users.login_id,
	locations.location_name,
	users.role_id,
	locations.district_id
FROM
	users
INNER JOIN warehouse_users ON users.pk_id = warehouse_users.user_id
INNER JOIN warehouses ON warehouse_users.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.district_id = locations.pk_id
WHERE
	locations.geo_level_id = 4  
AND locations.province_id IN (4)
and users.role_id = 54";



$result = $conn->query($qry);


while ($row = $result->fetch_assoc()) {


    $user_id = $row['pk_id'];
    $user_name = $row['user_name'];
    $u_name = $user_name . '1';


    $district_id = $row['district_id'];

    $qry_select2_s = "SELECT
           DISTINCT
            warehouses.warehouse_name,
            warehouses.pk_id
            FROM
            warehouses
            WHERE
            warehouses.district_id = $district_id AND
            warehouses.stakeholder_office_id = 6 AND
            warehouses.stakeholder_id = 1 AND
            warehouses.`status` = 1";
    $row_select2_s = $conn->query($qry_select2_s);

    $res_select2_s = $row_select2_s->fetch_assoc();


    while ($res_select2_s = $row_select2_s->fetch_assoc()) {
        $warehouse_id = $res_select2_s['pk_id'];
        $str_qry1 = "INSERT INTO warehouse_users
                    (warehouse_users.user_id,
                    warehouse_users.warehouse_id,
                    warehouse_users.is_default,
                    warehouse_users.created_by,
                    warehouse_users.created_date,
                    warehouse_users.modified_by,
                    warehouse_users.modified_date)
                    VALUES ( '$user_id', '$warehouse_id', '0','1',NOW(),'1',NOW())";
        $conn->query($str_qry1);
        $last_id = $conn->insert_id;
    }

    $i++;
}

//mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Reporting data has been updated");


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>