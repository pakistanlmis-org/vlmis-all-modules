<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';

$qry = "SELECT
punjab_cluster.pk_id,
punjab_cluster.cluster,
punjab_cluster.district_id,
punjab_cluster.tehsil_name,
punjab_cluster.tehsil_code,
replace(punjab_cluster.cluster_name , ' ','')uc_name,

punjab_cluster.uc_no,
punjab_cluster.uc_code,
punjab_cluster.hf_name,
punjab_cluster.hf_code,
punjab_cluster.hf_type,
punjab_cluster.population,
punjab_cluster.tehsil_id,
punjab_cluster.uc_id,
punjab_cluster.hf_id
FROM
punjab_cluster
where punjab_cluster.uc_name IS NOT NULL

GROUP BY punjab_cluster.district_id,punjab_cluster.cluster

";



$result = $conn->query($qry);


while ($row = $result->fetch_assoc()) {

 
    $user_name = $row['uc_name'];
     $uc_id = $row['uc_id'];
    $u_name = 'user'.$user_name;



    $password = randomString();
    
    $status = '1';
    $role_id = '55';
    $stakeholder_id = '1';
    $location_id = $uc_id;
 






    $str_qry1_i = "INSERT INTO users
                    (users.user_name,
                    users.`password`,
                   
                    users.login_id,
                
                    users.`status`,
                    users.role_id,
                    users.stakeholder_id,
                    users.location_id,
                  
                    users.created_by,
                    users.created_date,
                    users.modified_by,
                    users.modified_date)
                    VALUES ( '$u_name','202cb962ac59075b964b07152d234b70' ,'$u_name','$status','55','$stakeholder_id','1','1',NOW(),'1',NOW())";
 
    $conn->query($str_qry1_i);
    $last_id = $conn->insert_id;

   $district_id = $row['district_id'];
   $cluster = $row['cluster'];

    $qry_select2_s = "SELECT
	Distinct
	punjab_cluster.hf_id
FROM
	punjab_cluster
WHERE
	punjab_cluster.uc_name IS NOT NULL
AND district_id = '$district_id'
and cluster = '$cluster'";
   
    $row_select2_s = $conn->query($qry_select2_s);

    $res_select2_s = $row_select2_s->fetch_assoc();

   


    while ($res_select2_s = $row_select2_s->fetch_assoc()) {
        $warehouse_id = $res_select2_s['hf_id'];
        $is_default = 0;
        $str_qry1 = "INSERT INTO warehouse_users
                    (warehouse_users.user_id,
                    warehouse_users.warehouse_id,
                    warehouse_users.is_default,
                    warehouse_users.created_by,
                    warehouse_users.created_date,
                    warehouse_users.modified_by,
                    warehouse_users.modified_date)
                    VALUES ( '$last_id', '$warehouse_id', '$is_default','1',NOW(),'1',NOW())";
        $conn->query($str_qry1);
       
    }

    $i++;
}

//mail("ajmaleyetii@gmail.com", "Consumption Summary Updated ($date_time_from - $date_time_to)", "Reporting data has been updated");


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";


function randomString() {
    $length = 6;
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = "";    

    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, strlen($chars) - 1)];
    }

    return base64_encode($str);
}
?>