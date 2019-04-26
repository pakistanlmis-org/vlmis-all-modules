<!--#!/usr/local/bin/php -q-->
<?php
set_time_limit(0);

//include '/home/vlmispk/cron/config.php';
include 'config.php';


$qry_select_dis = "SELECT
        import_ccem.pk_id,
	import_ccem.inventory_date,
	import_ccem.equipment_ccem_code,
	import_ccem.`level`,
	import_ccem.`primary`,
	import_ccem.subnational_1,
	import_ccem.subnational_2,
	import_ccem.lowest_distribution,
	import_ccem.hf_id,
	import_ccem.service_point,
	import_ccem.health_structure_name,
	import_ccem.pqs_code,
	import_ccem.equipment_type,
	import_ccem.manufacturer,
	import_ccem.model,
	import_ccem.net_positive_storage_volume,
	import_ccem.net_negative_storage_volume1,
	import_ccem.waterpack_freezing_capacity_24,
	import_ccem.waterpack_storage_capacity,
	import_ccem.serial_bar_code_number,
	import_ccem.energy_source_used,
	import_ccem.installation_year,
	import_ccem.working_status,
	import_ccem.waiting_repair,
	import_ccem.waiting_parts,
	import_ccem.electricity_availability,
	import_ccem.generator_status,
	import_ccem.yes,
	import_ccem.reason,
	import_ccem.remove_from_the_inventory,
	import_ccem.replacement_year,
	import_ccem.inventory_by,
	import_ccem.at_5,
	import_ccem.at_20,
	import_ccem.waterpack_freezing_capacity,
	import_ccem.waterpack_storage_capacity1,
	import_ccem.`2018`,
	import_ccem.`2019`,
	import_ccem.`2020`,
	import_ccem.`2021`,
	import_ccem.`2022`,
	import_ccem.Total,
	import_ccem.`status`
FROM
	import_ccem
WHERE
	import_ccem.hf_id <> ''
AND import_ccem.hf_id IS NOT NULL
AND import_ccem.status = 0
AND import_ccem.`level` = 'SP'

";

$row_select_dis = $conn->query($qry_select_dis);

while ($res_select_dis = $row_select_dis->fetch_assoc()) {
    $main_pk_id = $res_select_dis['pk_id'];
    $inventory_date = $res_select_dis['inventory_date'];
    $equipment_ccem_code = $res_select_dis['equipment_ccem_code'];
    $level = $res_select_dis['level'];
    $primary = $res_select_dis['primary'];
    $subnational_1 = $res_select_dis['subnational_1'];
    $subnational_2 = $res_select_dis['subnational_2'];
    $lowest_distribution = $res_select_dis['lowest_distribution'];
    $hf_id = $res_select_dis['hf_id'];
    $service_point = $res_select_dis['service_point'];
    $health_structure_name = $res_select_dis['health_structure_name'];
    $pqs_code = $res_select_dis['pqs_code'];
    $equipment_type = $res_select_dis['equipment_type'];
    $manufacturer = $res_select_dis['manufacturer'];
    $model = $res_select_dis['model'];
    $net_positive_storage_volume = $res_select_dis['net_positive_storage_volume'];
    $net_negative_storage_volume1 = $res_select_dis['net_negative_storage_volume1'];
    $waterpack_freezing_capacity_24 = $res_select_dis['waterpack_freezing_capacity_24'];
    $waterpack_storage_capacity = $res_select_dis['waterpack_storage_capacity'];
    $serial_bar_code_number = $res_select_dis['serial_bar_code_number'];
    $energy_source_used = $res_select_dis['energy_source_used'];
    $installation_year = $res_select_dis['installation_year'];
    $working_status = $res_select_dis['working_status'];
    $waiting_repair = $res_select_dis['waiting_repair'];
    $electricity_availability = $res_select_dis['electricity_availability'];
    $generator_status = $res_select_dis['generator_status'];
    $yes = $res_select_dis['yes'];
    $reason = $res_select_dis['reason'];
    $remove_from_the_inventory = $res_select_dis['remove_from_the_inventory'];
    $replacement_year = $res_select_dis['replacement_year'];
    $inventory_by = $res_select_dis['inventory_by'];
    $at_5 = $res_select_dis['at_5'];
    $at_20 = $res_select_dis['at_20'];
    $waterpack_freezing_capacity = $res_select_dis['waterpack_freezing_capacity'];
    $y_2018 = $res_select_dis['2018'];
    $y_2019 = $res_select_dis['2019'];
    $y_2020 = $res_select_dis['2020'];
    $y_2021 = $res_select_dis['2021'];
    $y_2022 = $res_select_dis['2022'];
    $total = $res_select_dis['Total'];
    $working_since = $res_select_dis['installation_year'];
    $warehouse_id = $res_select_dis['hf_id'];

    if ($equipment_type == 'Ice-lined refrigerator') {
        $ccm_asset_type_id = '20';
        $power_source_id = '135';
    } else if ($equipment_type == 'Domestic') {
        $ccm_asset_type_id = '8';
        $power_source_id = '135';
    } else if ($equipment_type == 'Absorption Freezer') {
        $ccm_asset_type_id = '8';
        $power_source_id = '135';
    } else if ($equipment_type == 'Ice-lined refrigeratorF') {
        $ccm_asset_type_id = '20';
        $power_source_id = '135';
    } else if ($equipment_type == 'Freezer') {
        $ccm_asset_type_id = '17';
        $power_source_id = '135';
    } else if ($equipment_type == 'Ice Pack freezer') {
        $ccm_asset_type_id = '17';
        $power_source_id = '135';
    } else if ($equipment_type == 'Ice-lined refrigerator/F') {
        $ccm_asset_type_id = '20';
        $power_source_id = '135';
    } else if ($equipment_type == 'Chest Freezer') {
        $ccm_asset_type_id = '8';
        $power_source_id = '135';
    } else if ($equipment_type == 'Solar battery refrigerator') {
        $ccm_asset_type_id = '21';
        $power_source_id = '139';
    } else if ($equipment_type == 'Solar battery refrigerator or Freezer') {
        $ccm_asset_type_id = '22';
        $power_source_id = '139';
    } else if ($equipment_type == 'Chest refrigerator/freezer') {
        $ccm_asset_type_id = '11';
        $power_source_id = '135';
    } else {
        $ccm_asset_type_id = '8';
        $power_source_id = '135';
    }

    //$issuance = $res_select_dis['issuance'];
    $qry_make = "SELECT
            ccm_makes.ccm_make_name,
            ccm_makes.pk_id
            FROM
            ccm_makes
            WHERE
            ccm_makes.ccm_make_name = '$manufacturer' ";
    $row_select_make = $conn->query($qry_make);


    $res_select_make = $row_select_make->fetch_assoc();

    if (empty($res_select_make)) {
        $str_qry1 = "INSERT INTO ccm_makes
                (ccm_makes.ccm_make_name,
                ccm_makes.`status`,
                ccm_makes.created_by,
                ccm_makes.created_date,
                ccm_makes.modified_by,
                ccm_makes.modified_date)
                VALUES ('$manufacturer', '1','1',NOW(),'1,NOW()')";
        $conn->query($str_qry1);
        $ccm_make_id = $conn->insert_id;
    } else {
        $ccm_make_id = $res_select_make['pk_id'];
    }



    $str_qry2 = "INSERT INTO ccm_models (
	ccm_models.ccm_model_name,
	ccm_models.ccm_make_id,
	ccm_models.ccm_asset_type_id,
	ccm_models.gas_type,
	ccm_models.gross_capacity_20,
	ccm_models.gross_capacity_4,
	ccm_models.net_capacity_20,
	ccm_models.net_capacity_4,
	ccm_models.cfc_free,
	ccm_models.no_of_phases,
	ccm_models.`status`,
	ccm_models.catalogue_id,
	ccm_models.power_source,
	ccm_models.created_by,
	ccm_models.created_date,
	ccm_models.modified_by,
	ccm_models.modified_date
        )
        VALUES
	(
		'$model',
		'$ccm_make_id',
		'1',
		'119',
		'0',
		'0',
		'$net_positive_storage_volume',
		'$net_negative_storage_volume1',
		'0',
		'0',
		'1',
		'UKNOWN',
		'$power_source_id',
		'1',
		NOW(),
		'1',
		NOW()
	)";

    $conn->query($str_qry2);
    $model_id = $conn->insert_id;

    $qry_auto_id = "SELECT
        MAX(cold_chain.auto_asset_id) as auto_id
        FROM
        cold_chain
        WHERE
        cold_chain.ccm_asset_type_id = '$ccm_asset_type_id'
        ";
    $row_select_auto = $conn->query($qry_auto_id);


    $res_select_auto = $row_select_auto->fetch_assoc();

    if (!empty($res_select_auto)) {
        $auto_ccm_id = $res_select_auto['auto_id'] + 1;
    } else {
        $auto_ccm_id = 1;
    }
    $installation_year1 = $installation_year."-01-01 00:00:00";

    $str_qry3 = "INSERT INTO cold_chain (
	
	cold_chain.auto_asset_id,
	cold_chain.serial_number,
	
	cold_chain.working_since,
	cold_chain.manufacture_year,
	cold_chain.`status`,
	cold_chain.ccm_asset_type_id,
	cold_chain.ccm_model_id,
	cold_chain.source_id,
	cold_chain.warehouse_id,
	cold_chain.created_by,
	cold_chain.created_date,
	cold_chain.modified_by,
	cold_chain.modified_date
        )
        VALUES
	(
		
		'$auto_ccm_id',
		'$serial_bar_code_number',
		
		'$installation_year1',
		'$installation_year',
		'1',
		'$ccm_asset_type_id',
		'$model_id',
		'1',
		'$warehouse_id',
		'1',
		NOW(),
		'1',
		NOW()
	)";
  
    $conn->query($str_qry3);
    $ccm_id = $conn->insert_id;

    if ($working_status == 'Working') {
        $utilization_id = '1';
    } else if ($working_status == 'Not Working') {
         $utilization_id = '3';
    } else if ($working_status == 'Service') {
         $utilization_id = '2';
    } else {
         $utilization_id = '19';
    }

    $str_qry4 = "INSERT INTO ccm_status_history
                (
                
                ccm_status_history.status_date,
                ccm_status_history.ccm_id,
                ccm_status_history.warehouse_id,
                ccm_status_history.ccm_status_list_id,
                ccm_status_history.ccm_asset_type_id,
                ccm_status_history.utilization_id,
                ccm_status_history.created_by,
                ccm_status_history.created_date,
                ccm_status_history.modified_by,
                ccm_status_history.modified_date
                )
               VALUES (NOW(),'$ccm_id','$warehouse_id','1','1','$utilization_id','1',NOW(),'1',NOW())";
  
    $conn->query($str_qry4);
     
    $last_ccm_id = $conn->insert_id;

    $str_qry1_12 = "UPDATE cold_chain SET ccm_status_history_id='$last_ccm_id' Where cold_chain.pk_id = '$ccm_id'";
    $conn->query($str_qry1_12);

    $str_qry1_123 = "UPDATE import_ccem SET status='1' Where import_ccem.pk_id = '$main_pk_id'";
    $conn->query($str_qry1_123);
}


echo date("d/m/Y h:i:s") . "<br>";
echo "Executed Successfully!";
?>