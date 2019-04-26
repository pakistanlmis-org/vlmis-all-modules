<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';


$qry_select_dis = "SELECT
	fata_consumption.item_pack_size_id,
	fata_consumption.dose_no,
	fata_consumption.warehouse_id,
	fata_consumption.reporting_date,
	SUM(fata_consumption.issuance) issuance
FROM
	fata_consumption
GROUP BY
  reporting_date,
	item_pack_size_id,
	warehouse_id";
$row_select_dis = $conn->query($qry_select_dis);

while ($res_select_dis = $row_select_dis->fetch_assoc()) {

    $item_id = $res_select_dis['item_pack_size_id'];
    $warehouse_id = $res_select_dis['warehouse_id'];
    $issuance = $res_select_dis['issuance'];
    $reporting_date = $res_select_dis['reporting_date'];

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
                fata_consumption.item_pack_size_id,
                fata_consumption.dose_no,

                fata_consumption.warehouse_id,
                fata_consumption.reporting_date,
                fata_consumption.issuance
                FROM
                 fata_consumption
                WHERE
                fata_consumption.item_pack_size_id = '$item_id' AND
                fata_consumption.warehouse_id = '$warehouse_id' "
                . " AND Date_Format(fata_consumption.reporting_date,'%Y-%m-%d') = '$reporting_date'";
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