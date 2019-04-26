<?php

/**
 * Model_FpDashboard
 *
 *
 *
 * Logistics Management Information System for Vaccines
 * @subpackage FP Dashboards
 * @author    ahmad saib
 * @version    2.5.1
 */

/**
 *  Model for FpDashboard
 */
class Model_EpiMis extends Model_Base {

    public function getIssuanceData($year, $month, $tr_id) {
        if (!empty($tr_id)) {
            $where = "AND stock_master.pk_id > $tr_id";
        }
        $rpt_date = $year . '-' . str_pad($month, 2, "0", STR_PAD_LEFT);
        $sql = "SELECT
                stock_master.pk_id AS transactionID,
                stock_master.transaction_number AS transactionNumber,
                stock_master.transaction_date AS transactionDateTime,
                stakeholder_activities.activity AS Purpose,
                stock_batch.unit_price unitPrice,
                item_units.item_unit_name as itemUnitName,
                from_warehouses.warehouse_name AS issuedBy,
                warehouses.warehouse_name AS issuedTo,
                stock_batch.number AS batchNumber,
                vvm_types.vvm_type_name AS vvmTypeName,
                stakeholders.stakeholder_name AS manufacturer,
                stock_batch.expiry_date AS expiryDate,
                stock_batch.production_date AS productionDate,
                stock_detail.vvm_stage AS vvmStage,
                stakeholder_item_pack_sizes.item_pack_size_id AS itemID,
                item_pack_sizes.item_name itemName,
                IF(item_pack_sizes.vvm_group_id=1,vvm_stages.pk_id,vvm_stages.vvm_stage_value) vvmStage,
                IFNULL(
                        ABS((stock_detail.quantity)),
                        0
                ) AS issuedQty
        FROM
                stock_master
            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN item_units ON stock_detail.item_unit_id = item_units.pk_id
            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
            INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
            LEFT JOIN vvm_types ON stock_batch.vvm_type_id = vvm_types.pk_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_activities ON stock_master.stakeholder_activity_id = stakeholder_activities.pk_id 
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN stakeholders ON stakeholder_item_pack_sizes.stakeholder_id = stakeholders.pk_id
            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
            INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
            INNER JOIN warehouses AS from_warehouses ON stock_master.from_warehouse_id = from_warehouses.pk_id
            INNER JOIN locations ON warehouses.location_id = locations.pk_id
            INNER JOIN vvm_stages ON stock_detail.vvm_stage = vvm_stages.pk_id
        WHERE
                stock_master.transaction_type_id = 2
        AND DATE_FORMAT(
                stock_master.transaction_date,
                '%Y-%m'
        ) = '$rpt_date'
        
        AND from_warehouses.pk_id = 159
        AND warehouses.pk_id = 160
        $where
    
                ORDER BY stock_master.pk_id
        ";

        $str_sql = $this->_em_read->getConnection()->prepare("$sql");

        $str_sql->execute();
        return $str_sql->fetchAll();
    }

    public function addConsumption($decoded) {
        foreach ($decoded as $row) {

            $uc_code = $row[0]['uc_code'];
            $reporting_month_date1 = $row[0]['reporting_month_date'];
            $date_array = explode('-', $reporting_month_date1);

            $reporting_month_date = $date_array[0] . '-' . $date_array[1] . '-01';
            $vaccines_data = $row[0]['vaccines_data'];

            foreach ($vaccines_data as $row1) {

                $transaction_id = $row1['transaction_id'];
                $transaction_date_time = $row1['transaction_date_time'];
                $item_id = $row1['item_id'];
                $item_qty = $row1['item_qty'];

                // for warehouse_id
                $str_qry_uc = "SELECT
                        warehouses.pk_id
                FROM
                        location_mapping
                INNER JOIN warehouses ON location_mapping.lmis_location_id = warehouses.location_id
                WHERE
                location_mapping.evac_location_id = $uc_code AND
                warehouses.stakeholder_id = 1
                LIMIT 1";

                $row_uc = $row = $this->_em_read->getConnection()->prepare($str_qry_uc);
                $row_uc->execute();
                $res_uc = $row_uc->fetchAll();
                $warehouse_id = $res_uc[0]['pk_id'];


                // for item item
                $str_qry_itm = "SELECT
                    item_doses.item_pack_size_id,
                    item_doses.dose_id
                    FROM
                    item_doses
                    WHERE
                    item_doses.evacc_item_id = $item_id";

                $row_itm = $row = $this->_em_read->getConnection()->prepare($str_qry_itm);
                $row_itm->execute();
                $res_itm = $row_itm->fetchAll();
                $item_pack_size_id = $res_itm[0]['item_pack_size_id'];
                $dose_id = $res_itm[0]['dose_id'];

                // for check if record already exit
                $str_qry_select = "SELECT
                    hf_data_master.pk_id
                    FROM
                    hf_data_master
                    WHERE
                    hf_data_master.reporting_start_date = '$reporting_month_date' AND
                    hf_data_master.item_pack_size_id = '$item_pack_size_id' AND
                    hf_data_master.warehouse_id = '$warehouse_id'";
                $row_select = $row = $this->_em_read->getConnection()->prepare($str_qry_select);
                $row_select->execute();
                $res_select = $row_select->fetchAll();
                if (empty($res_select)) {
                    $str_qry1 = "INSERT INTO hf_data_master (
                            
                            hf_data_master.issue_balance,
                            hf_data_master.reporting_start_date,
                            hf_data_master.item_pack_size_id,
                            hf_data_master.warehouse_id,
                            hf_data_master.created_date
                    )
                    VALUES ( '$item_qty', '$reporting_month_date', '$item_pack_size_id', $warehouse_id,NOW())";

                    $row1 = $this->_em_read->getConnection()->prepare($str_qry1);
                    $row1->execute();
                    $lastid = $this->_em_read->getConnection()->lastInsertId();
                } else {
                    $lastid = $res_select[0]['pk_id'];
                }

                $str_qry2 = "INSERT INTO hf_data_detail(
                            hf_data_detail.fixed_inside_uc_male,
                            hf_data_detail.vaccine_schedule_id,
                            hf_data_detail.hf_data_master_id,
                            hf_data_detail.created_date)
                    VALUES ( '$item_qty', '$dose_id', '$lastid', NOW())";

                $row2 = $row = $this->_em_read->getConnection()->prepare($str_qry2);
                $row2->execute();
                exit;
            }
            // echo $uc_code . '-' . $reporting_month_date . '-' . $transaction_id . '-' . $transaction_date_time . '-' . $item_id . '-' . $item_qty;
        }
    }

}
