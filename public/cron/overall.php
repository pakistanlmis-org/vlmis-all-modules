<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';


$qry_select_dis = "SELECT DISTINCT
        locations.location_name,
        overall_consumption_dec.item_pack_size_id,
        overall_consumption_dec.dose_no,
        overall_consumption_dec.issuance,
        overall_consumption_dec.warehouse_id,
        overall_consumption_dec.reporting_date,
        overall_consumption_dec.district_id
        FROM
        locations
        INNER JOIN overall_consumption_dec ON overall_consumption_dec.district_id = locations.district_id
        WHERE
        locations.province_id = 6
        and overall_consumption_dec.district_id IN (115,139,166,34)";
$row_select_dis = $conn->query($qry_select_dis);

while ($res_select_dis = $row_select_dis->fetch_assoc()) {

    $item_id = $res_select_dis['item_pack_size_id'];
    $district_id = $res_select_dis['district_id'];
    $issuance = $res_select_dis['issuance'];
    $reporting_date = $res_select_dis['reporting_date'];

    $qry_ware = "SELECT
        warehouses.pk_id
        FROM
        warehouses
        WHERE
        warehouses.district_id = '$district_id' AND
        warehouses.stakeholder_office_id = 6 AND
        warehouses.`status` = 1
        LIMIT 1";
    $row_warehouse = $conn->query($qry_ware);

    $res_select_warehosue = $row_warehouse->fetch_assoc();
    $warehouse_id = $res_select_warehosue['pk_id'];

    $qry_select = "SELECT
            hf_data_master.pk_id
            FROM
            hf_data_master
            WHERE
            hf_data_master.warehouse_id = '$warehouse_id'"
            . " AND hf_data_master.item_pack_size_id = '$item_id'"
            . "AND Date_Format(hf_data_master.reporting_start_date,'%Y-%m-%d') = '$reporting_date'";

    $row_select = $conn->query($qry_select);

    $res_select = $row_select->fetch_assoc();

    if (empty($res_select)) {

        $str_qry1 = "INSERT INTO hf_data_master
                    (hf_data_master.opening_balance,
                    hf_data_master.received_balance,
                    hf_data_master.issue_balance,
                    hf_data_master.closing_balance,
                    hf_data_master.reporting_start_date,
                    hf_data_master.item_pack_size_id,
                    hf_data_master.warehouse_id,
                    hf_data_master.created_by,
                    hf_data_master.created_date,
                    hf_data_master.modified_date,
                    hf_data_master.modified_by
                    )
                    VALUES ('0', '0','$issuance','0','$reporting_date','$item_id','$warehouse_id','1',NOW(),NOW(),'1')";
        $conn->query($str_qry1);
        $last_id = $conn->insert_id;
        $qry_detail = "SELECT
                overall_consumption.item_pack_size_id,
                overall_consumption.dose_no,

                overall_consumption.warehouse_id,
                overall_consumption.reporting_date,
                overall_consumption.issuance
                FROM
                 overall_consumption
                WHERE
                overall_consumption.item_pack_size_id = '$item_id' AND
                overall_consumption.district_id = '$district_id' "
                . " AND Date_Format(overall_consumption.reporting_date,'%Y-%m-%d') = '$reporting_date'";
        $row_select_detail = $conn->query($qry_detail);
        $issue_balance = 0;
        while ($res_select_detail = $row_select_detail->fetch_assoc()) {
            $item_pack_size_id = $res_select_detail['item_pack_size_id'];

            $dose_no = $res_select_detail['dose_no'];

            $issuance = $res_select_detail['issuance'];

            if ($item_pack_size_id == 12) {
                $str_qry4 = "INSERT INTO hf_data_detail
                            (hf_data_detail.pregnant_women,
                            
                           
                            
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_by,
                            hf_data_detail.created_date,
                            hf_data_detail.modified_by,
                            hf_data_detail.modified_date
                            
                            
                            )
                            VALUES ('$issuance', '$dose_no','$last_id','1',NOW(),'1',NOW())";
                $conn->query($str_qry4);
            } else {

                $str_qry4 = "INSERT INTO hf_data_detail
                            (hf_data_detail.fixed_inside_uc_male,
                            hf_data_detail.age_group_id,
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_by,
                            hf_data_detail.created_date,
                            hf_data_detail.modified_by,
                            hf_data_detail.modified_date
                            
                            
                            )
                            VALUES ('$issuance', '160', '$dose_no','$last_id','1',NOW(),'1',NOW())";
                $conn->query($str_qry4);

                $str_qry5 = "INSERT INTO hf_data_detail
                            (hf_data_detail.fixed_inside_uc_male,
                           
                            hf_data_detail.age_group_id,
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_by,
                            hf_data_detail.created_date,
                            hf_data_detail.modified_by,
                            hf_data_detail.modified_date
                            
                            
                            )
                            VALUES ('0', '161', '$dose_no','$last_id','1',NOW(),'1',NOW())";
                $conn->query($str_qry5);
                $str_qry6 = "INSERT INTO hf_data_detail
                            (hf_data_detail.fixed_inside_uc_male,
                           
                            hf_data_detail.age_group_id,
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_by,
                            hf_data_detail.created_date,
                            hf_data_detail.modified_by,
                            hf_data_detail.modified_date
                            
                            
                            )
                            VALUES ('0', '162', '$dose_no','$last_id','1',NOW(),'1',NOW())";
                $conn->query($str_qry6);
            }
        }
    }
}



echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>