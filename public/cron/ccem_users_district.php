<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';

$qry = "SELECT

        locations.location_name,
        warehouses.pk_id warehouse_id

        FROM
        locations
        INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
        WHERE
        locations.province_id IN (5,6,7) AND
        locations.geo_level_id = 4";



$result = $conn->query($qry);


while ($row = $result->fetch_assoc()) {




    $user_name1 = $row['location_name'];

    $user_name = str_replace(' ', '', $user_name1);

    $u_name = 'ccem_' . strtolower($user_name);

    $warehouse_id = $row['warehouse_id'];


    $str_qry = "INSERT INTO users
                    (users.user_name,
                users.`password`,
               
                users.login_id,
                users.designation,
                users.email,
                users.`status`,
               
                users.role_id,
                users.stakeholder_id,
                users.location_id,
               
                users.created_by,
                users.created_date,
                users.modified_by,
                users.modified_date)
                    VALUES ( '$u_name', '202cb962ac59075b964b07152d234b70','$u_name','CCEM','','1','13','1','1','1',NOW(),'1',NOW())";

    $conn->query($str_qry);
    $last_id = $conn->insert_id;


    $str_qry1 = "INSERT INTO warehouse_users
                    (warehouse_users.user_id,
                    warehouse_users.warehouse_id,
                    warehouse_users.is_default,
                    warehouse_users.created_by,
                    warehouse_users.created_date,
                    warehouse_users.modified_by,
                    warehouse_users.modified_date)
                    VALUES ( '$last_id', '$warehouse_id', '1','1',NOW(),'1',NOW())";
    $conn->query($str_qry1);



    $i++;
}

//mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Reporting data has been updated");


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>