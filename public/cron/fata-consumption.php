<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';


$qry_select_dis = "SELECT
    fata_consumption.pk_id,
    fata_consumption.item_pack_size_id,
    fata_consumption.dose_no,
    fata_consumption.issuance,
    fata_consumption.warehouse_id,
    fata_consumption.reporting_date
    FROM
    fata_consumption 
    where item_pack_size_id  IN (12)
    and fata_consumption.warehouse_id NOT IN (
SELECT DISTINCT
	warehouses.pk_id
FROM
	hf_data_master
INNER JOIN hf_data_detail ON hf_data_detail.hf_data_master_id = hf_data_master.pk_id
INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
WHERE
	DATE_FORMAT(
		hf_data_master.reporting_start_date,
		'%Y-%m-%d'
	) = '2017-12-01'
AND Date_Format(
	hf_data_master.created_date,
	'%Y-%m-%d'
) <> '2018-01-26'
AND warehouses.province_id = 6
AND warehouses.stakeholder_office_id = 6
AND hf_data_master.issue_balance > 0
AND hf_data_master.created_by <> 1)
    GROUP BY item_pack_size_id,warehouse_id";
$row_select_dis = $conn->query($qry_select_dis);

while ($res_select_dis = $row_select_dis->fetch_assoc()) {

    $item_id = $res_select_dis['item_pack_size_id'];
    $warehouse_id = $res_select_dis['warehouse_id'];
    //$issuance = $res_select_dis['issuance'];



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
                    VALUES ('0', '0','0','0','2017-12-01','$item_id','$warehouse_id','1',NOW(),NOW(),'1')";
    $conn->query($str_qry1);
    $last_id = $conn->insert_id;
    $qry_detail = "SELECT
                fata_consumption.item_pack_size_id,
                fata_consumption.dose_no,
                fata_consumption.issuance,
                fata_consumption.warehouse_id
                FROM
                fata_consumption
                WHERE
                fata_consumption.item_pack_size_id = '$item_id' AND
                fata_consumption.warehouse_id = '$warehouse_id' ";
    $row_select_detail = $conn->query($qry_detail);
    $issue_balance = 0;
    while ($res_select_detail = $row_select_detail->fetch_assoc()) {
        $issuance = $res_select_detail['issuance'];
        $dose_no = $res_select_detail['dose_no'];
        $item_pack_size_id = $res_select_detail['item_pack_size_id'];
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
                            VALUES ('$issuance','$dose_no','$last_id','1',NOW(),'1',NOW())";
            $conn->query($str_qry4);
            $last_detail_id = $conn->insert_id;
            $qry_detail_1 = "SELECT
                fata_consumption.item_pack_size_id,
                fata_consumption.dose_no,
                fata_consumption.issuance,
                fata_consumption.warehouse_id
                FROM
                fata_consumption
                WHERE
                fata_consumption.item_pack_size_id = '11' AND
                fata_consumption.warehouse_id = '$warehouse_id' "
                    . "AND fata_consumption.dose_no = '$dose_no' ";
            $row_select_detail_1 = $conn->query($qry_detail_1);
            $res_select_detail_1 = $row_select_detail_1->fetch_assoc();
            $issuance_cba = $res_select_detail_1['issuance'];
            $str_qry1_12 = "UPDATE hf_data_detail SET hf_data_detail.non_pregnant_women='$issuance_cba' Where hf_data_detail.pk_id = '$last_detail_id'";
      $conn->query($str_qry1_12);
            }
        $issue_balance += $issuance + $issuance_cba;
    }
    $str_qry1_u = "UPDATE hf_data_master SET hf_data_master.issue_balance='$issue_balance' Where hf_data_master.pk_id = '$last_id'";

    $conn->query($str_qry1_u);
}





echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>