<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';


 $qry_select_dis = "SELECT
fata_consumption_s.pk_id,
fata_consumption_s.item_pack_size_id,
fata_consumption_s.dose_no,
fata_consumption_s.age_group_id,
fata_consumption_s.warehouse_id,
fata_consumption_s.reporting_date,


(fata_consumption_s.fixed_inside_uc_male + 
fata_consumption_s.fixed_inside_uc_female +
fata_consumption_s.fixed_outside_uc_male +
fata_consumption_s.fixed_outside_uc_female +
fata_consumption_s.referal_male +
fata_consumption_s.referal_female +
fata_consumption_s.outreach_male +
fata_consumption_s.outreach_female +
fata_consumption_s.pregnant_women + 
fata_consumption_s.non_pregnant_women) issuance

FROM
fata_consumption_s
where item_pack_size_id <> 12
GROUP BY item_pack_size_id,warehouse_id";
$row_select_dis = $conn->query($qry_select_dis);

while ($res_select_dis = $row_select_dis->fetch_assoc()) {

    $item_id = $res_select_dis['item_pack_size_id'];
    $warehouse_id = $res_select_dis['warehouse_id'];
    $issuance = $res_select_dis['issuance'];

    $qry_select = "SELECT
            hf_data_master.pk_id
            
            FROM
            hf_data_master
            WHERE
            hf_data_master.warehouse_id = '$warehouse_id'"
            . " AND hf_data_master.item_pack_size_id = '$item_id'"
            . "AND Date_Format(hf_data_master.reporting_start_date,'%Y-%m-%d') = '2017-12-01'";

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
                    VALUES ('0', '0','$issuance','0','2017-12-01','$item_id','$warehouse_id','1',NOW(),NOW(),'1')";
        $conn->query($str_qry1);
        $last_id = $conn->insert_id;
        $qry_detail = "SELECT
fata_consumption_s.item_pack_size_id,
fata_consumption_s.dose_no,
fata_consumption_s.warehouse_id,
fata_consumption_s.fixed_inside_uc_male,
fata_consumption_s.fixed_inside_uc_female,

fata_consumption_s.outreach_male,
fata_consumption_s.outreach_female,
fata_consumption_s.referal_female,
fata_consumption_s.referal_male,

fata_consumption_s.pregnant_women,
fata_consumption_s.non_pregnant_women,
fata_consumption_s.reporting_date,
fata_consumption_s.age_group_id
FROM
                fata_consumption_s
WHERE
                fata_consumption_s.item_pack_size_id = '$item_id' AND
                fata_consumption_s.warehouse_id = '$warehouse_id' ";
        $row_select_detail = $conn->query($qry_detail);
        $issue_balance = 0;
        while ($res_select_detail = $row_select_detail->fetch_assoc()) {
            $item_pack_size_id = $res_select_detail['item_pack_size_id'];
           
            $dose_no = $res_select_detail['dose_no'];
            $age_group = $res_select_detail['age_group_id'];
            $p_women = $res_select_detail['pregnant_women'];
            $n_p_women = $res_select_detail['non_pregnant_women'];

            $fixed_inside_uc_male = $res_select_detail['fixed_inside_uc_male'];
            $fixed_inside_uc_female = $res_select_detail['fixed_inside_uc_female'];
            $outreach_male = $res_select_detail['outreach_male'];
            $outreach_female = $res_select_detail['outreach_female'];
            $referal_female = $res_select_detail['referal_female'];
            $referal_male = $res_select_detail['referal_male'];
            if ($item_pack_size_id == 12) {
                $str_qry4 = "INSERT INTO hf_data_detail
                            (hf_data_detail.pregnant_women,
                              hf_data_detail.non_pregnant_women,
                           
                            
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_by,
                            hf_data_detail.created_date,
                            hf_data_detail.modified_by,
                            hf_data_detail.modified_date
                            
                            
                            )
                            VALUES ('$p_women','$n_p_women', '$dose_no','$last_id','1',NOW(),'1',NOW())";
                $conn->query($str_qry4);
            } else {

                $str_qry4 = "INSERT INTO hf_data_detail
                            (hf_data_detail.fixed_inside_uc_male,
                            hf_data_detail.fixed_inside_uc_female,
                            hf_data_detail.referal_male,
                            hf_data_detail.referal_female,
                            hf_data_detail.outreach_male,
                            hf_data_detail.outreach_female,
                           
                            hf_data_detail.age_group_id,
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_by,
                            hf_data_detail.created_date,
                            hf_data_detail.modified_by,
                            hf_data_detail.modified_date
                            
                            
                            )
                            VALUES ('$fixed_inside_uc_male','$fixed_inside_uc_female','$referal_male','$referal_female','$outreach_male','$outreach_female', '$age_group', '$dose_no','$last_id','1',NOW(),'1',NOW())";
                $conn->query($str_qry4);
            }
        }
    }
}





echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>