
<?php

set_time_limit(0);

include 'config.php';





$date_sel = "2016-12";
$end_date = "2018-03";


while (strtotime($date_sel) <= strtotime($end_date)) {

    $date_sel = date("Y-m", strtotime("+1 month", strtotime($date_sel)));
    $startDate = date('Y-m-01', strtotime($date_sel));
    $endDate = date('Y-m-t', strtotime($date_sel));
    $year = date('Y', strtotime($date_sel));
    $wh_query = "SELECT
warehouses.pk_id,
warehouses.warehouse_name,
warehouses.province_id,
warehouses.district_id,
uc.location_name uc,
teh.location_name teh,
dis.location_name dis,
prov.location_name prov,
uc.pk_id uc_id,
teh.pk_id teh_id

FROM
warehouses
INNER JOIN locations AS uc ON warehouses.location_id = uc.pk_id
INNER JOIN locations AS teh ON uc.parent_id = teh.pk_id
INNER JOIN locations AS dis ON teh.district_id = dis.pk_id
INNER JOIN locations AS prov ON dis.province_id = prov.pk_id
WHERE
warehouses.province_id = 2 AND
warehouses.`status` = 1 AND
warehouses.stakeholder_office_id = 6

           
            ";

    $queryWh = $conn->query($wh_query);
    $num_rows_wh = mysqli_num_rows($queryWh);
    while ($row_wh = $queryWh->fetch_assoc()) {
        $warehouse_id = $row_wh['pk_id'];
        $warehouse_name = $row_wh['warehouse_name'];
        $province_id = $row_wh['province_id'];
        $district_id = $row_wh['district_id'];
        $tehsil_id = $row_wh['teh_id'];
        $uc_id = $row_wh['uc_id'];
        $province_name = $row_wh['prov'];
        $district_name = $row_wh['dis'];
        $tehsil_name = $row_wh['teh'];
        $uc_name = $row_wh['uc'];
        $qry1 = "SELECT
	item_pack_sizes.pk_id pkId,
	item_pack_sizes.description itemName,
        item_pack_sizes.wastage_rate_allowed
        FROM
                item_pack_sizes
        INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                item_pack_sizes.`status` = 1
        AND item_pack_sizes.item_category_id = 1
        AND item_activities.stakeholder_activity_id = 1
        ORDER BY
                item_pack_sizes.list_rank ASC";
        $query1 = $conn->query($qry1);


        while ($row1 = $query1->fetch_assoc()) {


            $product = $row1['pkId'];
            $product_name = $row1['itemName'];
            $wastage_rate_allowed = $row1['wastage_rate_allowed'];
            $qryA = "SELECT
            hf_data_master.reporting_start_date,
            hf_data_master.issue_balance,
            hf_data_master.wastages
            FROM
            hf_data_master
            WHERE
            DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m-%d') = '$startDate' AND
            hf_data_master.warehouse_id = '$warehouse_id'
            and item_pack_size_id = '$product'
            ";

            $queryA = $conn->query($qryA);
            $row_first = $queryA->fetch_assoc();
            if (count($row_first) > 0) {

                $consumption = $row_first['issue_balance'];

                $wastages = $row_first['wastages'];

                $wastages_percentage = Round((( $wastages / ($consumption + $wastages)) ) * 100, 2);
                if ($wastages_percentage >= $wastage_rate_allowed) {
                    $over_wastage = 1;
                } else {

                    $over_wastage = 0;
                }

                if ($wastages_percentage < $wastage_rate_allowed) {
                    $within = 1;
                } else {
                    $within = 0;
                }


                $qry_ins = "INSERT INTO wastages_sdps_summary (
	wastages_sdps_summary.province_id,
        wastages_sdps_summary.district_id,
        wastages_sdps_summary.warehouse_id,
        wastages_sdps_summary.warehouse_name,
        wastages_sdps_summary.consumption,
        wastages_sdps_summary.wastage,
        wastages_sdps_summary.reporting,
        wastages_sdps_summary.reporting_date,
        wastages_sdps_summary.item_pack_size_id,
        wastages_sdps_summary.item_name,
        wastages_sdps_summary.created_by,
        wastages_sdps_summary.created_date,
        wastages_sdps_summary.modified_by,
        wastages_sdps_summary.modified_date,
        wastages_sdps_summary.province_name,
        wastages_sdps_summary.district_name,
        wastages_sdps_summary.tehsil_name,
        wastages_sdps_summary.uc_name,
        wastages_sdps_summary.uc_id,
        wastages_sdps_summary.teh_id,
        wastages_sdps_summary.within_range,
        over_wastage)
        VALUES
        (  
           '$province_id',
           '$district_id',
           '$warehouse_id',
           '$warehouse_name',
           '$consumption',
           '$wastages',
           '1',
           '$startDate',
            '$product',     
           '$product_name',    
           '1',
           NOW(),
           '1',
           NOW(),
           '$province_name',
           '$district_name',
           '$tehsil_name',
           '$uc_name',
           '$uc_id',
           '$tehsil_id',
           '$within',
           '$over_wastage'
        )
        ";

                $conn->query($qry_ins);
            } else {
                $qry_ins = "INSERT INTO wastages_sdps_summary (
	wastages_sdps_summary.province_id,
        wastages_sdps_summary.district_id,
        wastages_sdps_summary.warehouse_id,
        wastages_sdps_summary.warehouse_name,
        wastages_sdps_summary.consumption,
        wastages_sdps_summary.wastage,
        wastages_sdps_summary.reporting,
        wastages_sdps_summary.reporting_date,
        wastages_sdps_summary.item_pack_size_id,
        wastages_sdps_summary.item_name,
        wastages_sdps_summary.created_by,
        wastages_sdps_summary.created_date,
        wastages_sdps_summary.modified_by,
        wastages_sdps_summary.modified_date,
        wastages_sdps_summary.province_name,
        wastages_sdps_summary.district_name,
        wastages_sdps_summary.tehsil_name,
        wastages_sdps_summary.uc_name,
        wastages_sdps_summary.uc_id,
        wastages_sdps_summary.teh_id)
        VALUES
        (  
           '$province_id',
           '$district_id',
           '$warehouse_id',
           '$warehouse_name',
           '0',
           '0',
           '0',
           '$startDate',
           '$product',    
           '$product_name',    
           '1',
           NOW(),
           '1',
           NOW(),
             '$province_name',
              '$district_name',
              '$tehsil_name',
              '$uc_name',
              '$uc_id',
              '$tehsil_id'
                   
        )
        ";


                $conn->query($qry_ins);
            }
        }
    }
}

// log update


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>