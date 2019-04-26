
<?php

set_time_limit(0);

include 'config.php';



$date_sel = "2018-01";
$startDate = date('Y-m-01', strtotime($date_sel));
$endDate = date('Y-m-t', strtotime($date_sel));
$year = date('Y', strtotime($date_sel));
$wh_query = "SELECT
 stakeholders.stakeholder_name,
  locations.location_name,
	warehouses.warehouse_name,
        warehouses.pk_id as warehouse_id,
	stock_batch.expiry_date as expiry_date,
	stock_batch.number,
	item_pack_sizes.item_name,
        item_pack_sizes.pk_id item_pack_size_id,
	Sum(placements.quantity) AS quantity,
	SUM(placements.quantity) * item_pack_sizes.number_of_doses AS doses,
	cold_chain.asset_id AS coldroom,
	placement_locations.pk_id AS coldroom_id,

        IF (
                item_pack_sizes.vvm_group_id = 1,
                IFNULL(vvm_stages.pk_id, 1),
                vvm_stages.vvm_stage_value
        ) AS vvm,
       warehouses.stakeholder_office_id
FROM
stock_batch_warehouses
INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
INNER JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
INNER JOIN locations ON warehouses.province_id = locations.pk_id
INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id 
INNER JOIN stakeholders ON stakeholders.pk_id = warehouses.stakeholder_office_id
WHERE
                item_pack_sizes.item_category_id <> 3
                AND item_activities.stakeholder_activity_id = 1
        AND (
              DATE_FORMAT(
                        stock_batch.expiry_date,
                        '%Y-%m-%d'
                ) < '2018-01-01'                                                                                                                                                           
        )
GROUP BY
	placements.vvm_stage,
	placements.stock_batch_warehouse_id,
	cold_chain.asset_id
HAVING
                quantity > 0
ORDER BY
        locations.location_name,
	stakeholder_office_id,
	warehouses.warehouse_name,
	item_pack_sizes.list_rank,
	stock_batch.expiry_date
";

$queryWh = $conn->query($wh_query);
$num_rows_wh = mysqli_num_rows($queryWh);
while ($row_wh = $queryWh->fetch_assoc()) {

    $stakeholder_name = $row_wh['stakeholder_name'];
    $location_name = $row_wh['location_name'];
    $warehouse_name = $row_wh['warehouse_name'];
    $warehouse_id = $row_wh['warehouse_id'];
    $expiry_date = $row_wh['expiry_date'];
    $number = $row_wh['number'];
    $item_name = $row_wh['item_name'];
    $item_pack_size_id = $row_wh['item_pack_size_id'];
    $quantity = $row_wh['quantity'];
    $doses = $row_wh['doses'];
    $coldroom = $row_wh['coldroom'];

  

    
    
        $qry_ins = "INSERT INTO expiry_summary (
	expiry_summary.location_level,
        expiry_summary.province,
        expiry_summary.warehouse,
        expiry_summary.warehouse_id,
        expiry_summary.item,
        expiry_summary.item_pack_size_id,
        expiry_summary.batch_number,
        expiry_summary.`status`,
        expiry_summary.expiry_date,
        expiry_summary.cold_room,
        expiry_summary.quantity_vials,
        expiry_summary.quantity_doses,
        expiry_summary.created_by,
        expiry_summary.created_date,
        expiry_summary.modified_by,
        expiry_summary.modified_date)
        VALUES
        (  
           '$stakeholder_name',
           '$location_name',
           '$warehouse_name',
           '$warehouse_id',
           '$item_name',
           '$item_pack_size_id',
           '$number',
           'Expired',
           '$expiry_date',
           '$coldroom',
           '$quantity',
           '$doses',
           '1',
           NOW(),
           '1',
           NOW()
                   
        )
        ";
       

        $conn->query($qry_ins);
    
}

// log update


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>