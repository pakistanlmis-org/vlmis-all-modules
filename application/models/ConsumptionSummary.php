<?php

/**
 * Model_ConsumptionSummary
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * 
 * @package Van (Visibility and Analytics Network Dashboards)
 * @author     Muhammad Imran 
 * @version    2.5.1
 */

/**
 *  Model for consumption_summary (Used for VAN: Visibility and Analytics Network Dashboards)
 */
class Model_ConsumptionSummary extends Model_Base {

    /**
     * __construct
     */
//    public function __construct() {
//        parent::__construct();
//        $this->_table = $this->_em->getRepository('consumption_summary');
//    }

    public function getVanDistricts($province) {
        $str_sql = "
            SELECT
                van_districts.district_id,
                district.location_name AS district_name
            FROM
                locations AS district
                INNER JOIN van_districts ON van_districts.district_id = district.pk_id
            WHERE
                district.province_id = $province
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getAllMonths() {
        $str_sql = "
            SELECT
                period.period_name AS month_name,
                period.period_code AS month_code
            FROM
                period
            WHERE
                period.is_month = \"Yes\"
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getAllVaccines() {
        $str_sql = "
            SELECT
                    item_pack_sizes.pk_id AS item_id,
                    item_pack_sizes.item_name
            FROM
                    item_pack_sizes
            WHERE
                    item_pack_sizes.item_category_id = 1
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getAllRoutineVaccines() {
        $str_sql = "
            SELECT
                item_pack_sizes.pk_id AS item_id,
                item_pack_sizes.item_name,
                item_pack_sizes.wastage_rate_allowed AS AcceptableLevel,
                stakeholder_activities.activity
            FROM
                item_pack_sizes
                INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
            WHERE
                item_pack_sizes.item_category_id = 1
                AND stakeholder_activities.activity = 'routine'
            ORDER BY
                item_pack_sizes.list_rank ASC
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getUcsOfDistrict($district) {
        $str_sql = "
            SELECT
                District.location_name AS district_name,
                Tehsil.location_name AS tehsil_name,
                Uc.location_name AS uc_name,
                Uc.pk_id AS uc_id
            FROM
                locations AS District
                INNER JOIN locations AS Tehsil ON Tehsil.district_id = District.pk_id
                INNER JOIN locations AS Uc ON Uc.parent_id = Tehsil.pk_id
            WHERE
                Uc.district_id = $district
                AND Uc.geo_level_id = 6
                AND Tehsil.geo_level_id = 5
            ORDER BY
                tehsil_name ASC,
                uc_name ASC
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getDistrictStoreColdChainEquipmentStatus($selected_district) {
        $str_sql = "
            SELECT
	warehouses.warehouse_name AS WarehouseName,
	AssetMainType.asset_type_name AS AssetType,
	Sum(
		ccm_status_history.working_quantity
	) AS Units,

IF(ccm_models.net_capacity_20 != NULL, IFNULL(ROUND(SUM(ccm_models.net_capacity_20),1),''), IFNULL(ROUND(SUM(ccm_models.net_capacity_4),1),'') ) as NetCapacity,

	warehouses.district_id,
	District.location_name AS DistrictName,
	warehouse_types.warehouse_type_name
FROM
	cold_chain
INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
INNER JOIN ccm_asset_types AS AssetMainType ON cold_chain.ccm_asset_type_id = AssetMainType.pk_id
INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
INNER JOIN ccm_asset_types ON ccm_asset_types.pk_id = ccm_models.ccm_asset_type_id
INNER JOIN locations AS District ON District.pk_id = warehouses.district_id
INNER JOIN warehouse_types ON warehouse_types.pk_id = warehouses.warehouse_type_id
WHERE
ccm_status_history.working_quantity > 0 AND
warehouses.district_id = $selected_district AND
warehouses.warehouse_type_id = 3 AND
warehouses.warehouse_name LIKE 'District%'
GROUP BY
	cold_chain.ccm_asset_type_id

            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    public function getTalukaAndUCsColdChainCapacity($selected_district) {
        $str_sql = "
            SELECT
	AssetMainType.asset_type_name AS AssetType,
	Sum(
		ccm_status_history.working_quantity
	) AS Units,

IF(ccm_models.net_capacity_20 != NULL, IFNULL(ROUND(SUM(ccm_models.net_capacity_20),1),''), IFNULL(ROUND(SUM(ccm_models.net_capacity_4),1),'') ) as NetCapacity,
	warehouses.district_id,
	District.location_name AS DistrictName
FROM
	cold_chain
INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
INNER JOIN ccm_asset_types AS AssetMainType ON cold_chain.ccm_asset_type_id = AssetMainType.pk_id
INNER JOIN ccm_status_history ON cold_chain.ccm_status_history_id = ccm_status_history.pk_id
INNER JOIN ccm_models ON ccm_models.pk_id = cold_chain.ccm_model_id
INNER JOIN ccm_asset_types ON ccm_asset_types.pk_id = ccm_models.ccm_asset_type_id
INNER JOIN locations AS District ON District.pk_id = warehouses.district_id
INNER JOIN warehouse_types ON warehouse_types.pk_id = warehouses.warehouse_type_id
WHERE
ccm_status_history.working_quantity > 0 AND
warehouses.district_id = $selected_district AND
warehouse_types.geo_level_id IN (5,6)
GROUP BY
	cold_chain.ccm_asset_type_id

            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    public function getWastedProducts($selected_district, $start_date, $end_date, $selected_product) {
        $product_filter = "";
        if ($selected_product != 0) {
            $product_filter = "AND item_pack_sizes.pk_id = $selected_product";
        }
        $str_sql = "
            SELECT
                item_pack_sizes.pk_id AS WastedItemId,
                item_pack_sizes.item_name AS WastedItemName,
                item_pack_sizes.wastage_rate_allowed AS AcceptableLevel
            FROM
                consumption_summary
                INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = consumption_summary.item_pack_size_id
                INNER JOIN locations ON consumption_summary.location_id = locations.pk_id
            WHERE
                consumption_summary.location_id = $selected_district
                AND (consumption_summary.open_wastage > 0 OR consumption_summary.close_wastage > 0 )
                AND DATE_FORMAT(    consumption_summary.reporting_start_date,   '%Y-%m') BETWEEN '$start_date'AND '$end_date'
                $product_filter
            GROUP BY
                item_pack_sizes.pk_id
            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    public function getNumberOfVaccinatorsInDistrict($selected_district) {
        $str_sql = "
            SELECT
                Tehsil.district_id,
                District.location_name AS district_name,
                Tehsil.pk_id AS tehsil_id,
                Tehsil.location_name AS tehsil_name,
                demographic_data.no_of_vaccinators
            FROM
                locations AS Tehsil
                INNER JOIN demographic_data ON demographic_data.location_id = Tehsil.pk_id
                INNER JOIN locations AS District ON District.pk_id = Tehsil.district_id
            WHERE
                Tehsil.district_id = $selected_district
                AND Tehsil.geo_level_id = 5
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPendingVoucher($selected_month, $selected_year, $selected_province) {
        $str_sql = "
            SELECT
	wh_id,
	wh_name,
	SUM(stockIssue) AS stockIssue,
	SUM(stockPend) AS pending
FROM
	(
		SELECT
			warehouses.pk_id AS wh_id,
			warehouses.warehouse_name AS wh_name,
			(IF (stock_master.to_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2 && stock_detail.is_received = 0,COUNT(DISTINCT stock_master.pk_id),0)
			) AS stockPend,
			SUM(IF (stock_master.from_warehouse_id = warehouses.pk_id && stock_master.transaction_type_id = 2,1,0)
			) AS stockIssue,
			stock_master.transaction_number
		FROM
			warehouses
		INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
		INNER JOIN stock_master ON warehouses.pk_id = stock_master.to_warehouse_id
		INNER JOIN stock_detail ON stock_master.pk_id = stock_detail.stock_master_id
		INNER JOIN pilot_districts ON pilot_districts.district_id = warehouses.district_id
		WHERE
			stakeholders.geo_level_id = 2
		AND warehouses. STATUS = 1
		AND stock_master.draft = 0
		AND warehouses.province_id = $selected_province
		AND DATE_FORMAT(
			stock_master.transaction_date,
			'%Y-%m'
		) = '$selected_year-$selected_month'
		GROUP BY
			warehouses.pk_id,
			stock_master.transaction_number
	) AS A
GROUP BY
	wh_id
ORDER BY
	wh_name
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPercentOfvaccineThreeMonthsShelfLife($selected_month, $selected_year, $district_wh_id) {
        //$time_period = App_Controller_Functions::dateToDbFormat($time_period1);

        $str_sql = "
            SELECT
	A.item_pack_size_id,
	A.item_name,
	A.totalQty * A.number_of_doses AS totalQuantity,
	A.Expire3Months * A.number_of_doses AS 3months,
	ROUND(((A.Expire3Months / A.totalQty) * 100),1) * A.number_of_doses AS Expire3Months
FROM
	(
		SELECT
			stakeholder_item_pack_sizes.item_pack_size_id,
			item_pack_sizes.item_name,
			SUM(stock_batch_warehouses.quantity) AS totalQty,
			item_pack_sizes.number_of_doses,
			SUM(IF ( stock_batch.expiry_date <= ADDDATE('$selected_year-$selected_month-01',INTERVAL 3 MONTH),stock_batch_warehouses.quantity,0)) AS Expire3Months

		FROM
			stock_batch_warehouses
		INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
		INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
		INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
		INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			stakeholder_item_pack_sizes.item_pack_size_id IS NOT NULL
		AND stock_batch_warehouses.quantity > 0
		AND stock_batch_warehouses.warehouse_id = $district_wh_id
GROUP BY stakeholder_item_pack_sizes.item_pack_size_id
	) A
                ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getDistrictWarehouse($selected_district) {

        $str_sql = "
            SELECT DISTINCT
 warehouses.warehouse_name,
 warehouses.pk_id
FROM
 warehouses
INNER JOIN stock_batch_warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
WHERE
 warehouses.district_id = $selected_district
AND warehouses.stakeholder_office_id = 4
                ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getProvincialWarehouse($selected_province) {
        $str_sql = "
                    SELECT
                        warehouses.pk_id AS warehouse_id,
                        warehouses.warehouse_name
                    FROM
                        warehouses
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                        stakeholders.geo_level_id = 2
                        AND stakeholders.pk_id = 2 
                        AND warehouses.province_id = $selected_province
                    ORDER BY
                        warehouses.warehouse_name
                ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getAllAdjustmentTypes() {
        $str_sql = "
                    SELECT
                        transaction_types.pk_id AS adjustment_type_id,
                        transaction_types.transaction_type_name AS adjustment_type_name
                    FROM
                        transaction_types 
                    WHERE
                        transaction_types.is_adjustment > 0
                        AND transaction_types. STATUS IN (0, 1)
                    ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getProportionOfAdjustments($selected_month, $selected_year, $district_wh_id) {
        $start_date = date('Y-m-d', strtotime("$selected_year-$selected_month"));
        $end_date = date('Y-m-t', strtotime("$selected_year-$selected_month"));

        $str_sql = "
                    SELECT
                    A.warehouse_id,
                    A.warehouse_name,
                    A.adjustment_type_id,
                    A.adjustment_type_name,
                    A.adjustment_type_count,
                    B.total_adjustments,
                    ROUND((A.adjustment_type_count/B.total_adjustments) * 100, 2) AS adjustment_percent
                    FROM
                    (SELECT
                            stock_batch_warehouses.warehouse_id,
                            warehouses.warehouse_name,
                            transaction_types.pk_id AS adjustment_type_id,
                            transaction_types.transaction_type_name AS adjustment_type_name,
                            Count(transaction_types.transaction_type_name) AS adjustment_type_count

                    FROM
                            stock_detail
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                    INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
                    INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                    WHERE
                            stock_master.from_warehouse_id = '$district_wh_id'
                    AND stock_master.to_warehouse_id = '$district_wh_id'
                    AND DATE_FORMAT(
                            stock_master.transaction_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$start_date'
                    AND '$end_date'
                    AND stock_master.transaction_type_id > 2
                    GROUP BY
                            stock_master.transaction_type_id
                    ORDER BY
                            stock_master.transaction_type_id ASC)A
                    LEFT JOIN
                    (SELECT
                            stock_batch_warehouses.warehouse_id,
                            warehouses.warehouse_name,
                            Count(stock_batch_warehouses.warehouse_id) AS total_adjustments

                    FROM
                            stock_detail
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                    INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
                    INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                    WHERE
                            stock_master.from_warehouse_id = '$district_wh_id'
                    AND stock_master.to_warehouse_id = '$district_wh_id'
                    AND DATE_FORMAT(
                            stock_master.transaction_date,
                            '%Y-%m-%d'
                    ) BETWEEN '$start_date'
                    AND '$end_date'
                    AND stock_master.transaction_type_id > 2
                    )B ON A.warehouse_id = B.warehouse_id
                    ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getVaccineNotUsableDueToChangedVVM($selected_month, $selected_year, $district_wh_id) {
        $start_date = date('Y-m-d', strtotime("$selected_year-$selected_month"));
        $end_date = date('Y-m-t', strtotime("$selected_year-$selected_month"));

        $str_sql = "
                    SELECT
                        vvm_transfer_history.from_vvm_stage_id,
                        vvm_transfer_history.to_vvm_stage_id,
                        ROUND(SUM(vvm_transfer_history.quantity)/SUM(stock_batch_warehouses.quantity) * 100,2) AS percentage_unusable,
                        item_pack_sizes.item_name,
                        warehouses.warehouse_name,
                        vvm_transfer_history.created_date
                    FROM
                        vvm_transfer_history
                        INNER JOIN stock_batch_warehouses ON vvm_transfer_history.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                    WHERE
                        warehouses.pk_id = $district_wh_id AND
                        vvm_transfer_history.from_vvm_stage_id = '1' AND
                        vvm_transfer_history.to_vvm_stage_id = '3' AND
                        DATE_FORMAT(vvm_transfer_history.created_date,'%Y-%m-%d') BETWEEN '$start_date' and '$end_date'
                        Group BY item_pack_sizes.pk_id
                    ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPercentageOfNonReconciledVouchers($selected_month, $selected_year, $provincial_wh_id, $district_wh_id) {
        $date = date('Y-m', strtotime("$selected_year-$selected_month"));

        $str_sql = "
                    SELECT 
                        A.from_warehouse_id,
                        A.to_warehouse_id,
                        A.from_warehouse,
                        A.to_warehouse,
                        A.total_issue,
                        B.total_receive,
                        Round(((B.total_receive - A.total_issue)/B.total_receive) * 100 ,2) as per_reconciled
                         from 

                        (SELECT

                        COUNT(stock_master.transaction_number) as total_issue,
                        stock_master.to_warehouse_id,
                        stock_master.from_warehouse_id,
                        from_warehouse.warehouse_name AS from_warehouse,
                        to_warehouse.warehouse_name AS to_warehouse
                        FROM
                        stock_master
                        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                        INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
                        INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
                        WHERE
                        stock_master.from_warehouse_id = $provincial_wh_id AND
                        stock_master.to_warehouse_id = $district_wh_id AND
                        DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date' AND
                        stock_master.transaction_type_id = 2
                        GROUP BY stock_master.transaction_number) A
                        JOIN
                        (SELECT
                        stock_master.from_warehouse_id,
                        COUNT(stock_master.transaction_number) as total_receive,
                        stock_master.to_warehouse_id,
                        from_warehouse.warehouse_name AS from_warehouse,
                        to_warehouse.warehouse_name AS to_warehouse
                        FROM
                        stock_master
                        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                        INNER JOIN warehouses AS from_warehouse ON stock_master.from_warehouse_id = from_warehouse.pk_id
                        INNER JOIN warehouses AS to_warehouse ON stock_master.to_warehouse_id = to_warehouse.pk_id
                        WHERE
                        stock_master.from_warehouse_id = $provincial_wh_id AND
                        stock_master.to_warehouse_id = $district_wh_id AND
                        DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date' AND
                        stock_master.transaction_type_id = 1
                        GROUP BY stock_master.transaction_number) B ON A.from_warehouse_id = B.from_warehouse_id

                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPercentageOfDistrictStoresHavingStockOutOfVaccine($selected_month, $selected_year, $selected_province) {
        $date = date('Y-m', strtotime("$selected_year-$selected_month"));

        $str_sql = "
            
SELECT
	A.province_id,
	A.pk_id AS item_pack_size_id,
	A.item_name,
	ROUND((A.total_district_stockout / A.total_districts) * 100,2) AS stockout_percent
FROM
	(
		SELECT
			count(warehouses.district_id) AS total_district_stockout,
			warehouses.province_id,
			consumption_summary.mos,
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name,
			(
				SELECT
					count(locations.pk_id)
				FROM
					locations
				WHERE
					locations.province_id = $selected_province
				AND locations.geo_level_id = 4
				GROUP BY
					province_id
			) AS total_districts
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
		INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
		INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
		INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
		WHERE
			warehouses.province_id = $selected_province
		AND warehouses.stakeholder_office_id = 4
		AND DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') = '$date'
		AND item_pack_sizes.item_category_id = 1
		AND stakeholder_activities.activity = 'routine'
		GROUP BY
			consumption_summary.item_pack_size_id,
			province_id
		HAVING
			mos < 0.5
	) A";

//                    "SELECT 
//                        A.province_id,
//                        A.pk_id as item_pack_size_id,
//                        A.item_name,
//                        ROUND((A.total_district_stockout/A.total_districts) * 100,2) as stockout_percent
//                    from
//                        (SELECT
//                            count(warehouses.district_id) as total_district_stockout,
//                            warehouses.province_id,
//                            consumption_summary.mos,
//                            item_pack_sizes.pk_id,
//                            item_pack_sizes.item_name,
//                            (SELECT
//                                count(locations.pk_id) 
//
//                                FROM
//                                locations
//                            WHERE
//                                locations.province_id = $selected_province AND
//                                locations.geo_level_id = 4
//                                Group BY province_id) as total_districts
//
//                    FROM
//                        warehouses
//                        INNER JOIN locations ON warehouses.location_id = locations.pk_id
//                        INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
//                        INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
//                    WHERE
//                        warehouses.province_id = $selected_province AND
//                        warehouses.stakeholder_office_id = 4 AND
//                        DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') = '$date'
//
//                    GROUP BY item_pack_size_id,province_id
//                    HAVING mos < 0.5 ) A
//
//                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPercentageOfUcStoresHavingStockOutOfVaccine($selected_month, $selected_year, $selected_province) {
        $date = date('Y-m', strtotime("$selected_year-$selected_month"));

        $str_sql = "
            

SELECT
	A.province_id,
	A.pk_id AS item_pack_size_id,
	A.item_name,
	ROUND((A.total_ucs_stockout / A.total_ucs) * 100,2) AS stockout_percent
FROM
	(
		SELECT
			count(warehouses.location_id) AS total_ucs_stockout,
			warehouses.province_id,
			consumption_summary.mos,
			item_pack_sizes.pk_id,
			item_pack_sizes.item_name,
			(
				SELECT
					count(locations.pk_id)
				FROM
					locations
				WHERE
					locations.province_id = $selected_province
				AND locations.geo_level_id = 6
				GROUP BY
					province_id
			) AS total_ucs
		FROM
			warehouses
		INNER JOIN locations ON warehouses.location_id = locations.pk_id
		INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
		INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
		INNER JOIN item_activities ON item_activities.item_pack_size_id = item_pack_sizes.pk_id
		INNER JOIN stakeholder_activities ON item_activities.stakeholder_activity_id = stakeholder_activities.pk_id
		WHERE
			warehouses.province_id = $selected_province
		AND warehouses.stakeholder_office_id = 6
		AND DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') = '$date'
		AND item_pack_sizes.item_category_id = 1
		AND stakeholder_activities.activity = 'routine'
		GROUP BY
			consumption_summary.item_pack_size_id,
			province_id
		HAVING
			mos < 0.5
	) A";
        

                    "SELECT 
A.province_id,
A.pk_id as item_pack_size_id,
A.item_name,
ROUND((A.total_ucs_stockout/A.total_ucs) * 100,2) as stockout_percent
from
(SELECT
count(warehouses.location_id) as total_ucs_stockout,
warehouses.province_id,
consumption_summary.mos,
item_pack_sizes.pk_id,
item_pack_sizes.item_name,
(SELECT
count(locations.pk_id) 

FROM
locations
WHERE
locations.province_id = $selected_province AND
locations.geo_level_id = 6
Group BY province_id) as total_ucs

FROM
warehouses
INNER JOIN locations ON warehouses.location_id = locations.pk_id
INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
WHERE
warehouses.province_id = $selected_province AND
warehouses.stakeholder_office_id = 6
AND
DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') = '$date'

GROUP BY item_pack_size_id,province_id
HAVING mos < .5 ) A

                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPercentageOfDistrictStoresUnderstockedAdequatelyStockedOverstockedOfVaccine($selected_month, $selected_year, $selected_province) {
        $date = date('Y-m', strtotime("$selected_year-$selected_month"));

        $str_sql = "
                   SELECT 
A.province_id,
A.pk_id as item_pack_size_id,
A.item_name,
ROUND((A.stock_out/A.total_districts) * 100,2) as stockout_percent,
ROUND((A.under_stocked/A.total_districts) * 100,2) as understocked_percent,
ROUND((A.adequetly_stocked/A.total_districts) * 100,2) as adequetlystocked_percent,
ROUND((A.over_stocked/A.total_districts) * 100,2) as overstocked_percent

from
(SELECT
warehouses.province_id,
item_pack_sizes.pk_id,
item_pack_sizes.item_name,
(SELECT
count(locations.pk_id) 

FROM
locations
WHERE
locations.province_id = $selected_province AND
locations.geo_level_id = 4
Group BY province_id) as total_districts,
SUM(case when consumption_summary.mos < 0.5 then 1 else 0 end) as stock_out,
SUM(case when consumption_summary.mos BETWEEN 0.5 and 0.99 then 1 else 0 end) as under_stocked,
SUM(case when consumption_summary.mos BETWEEN 1 and 1.5  then 1 else 0 end) as adequetly_stocked,
SUM(case when consumption_summary.mos > 1.5 then 1 else 0 end) as over_stocked 	
FROM
	consumption_summary
INNER JOIN warehouses ON consumption_summary.location_id = warehouses.location_id
INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
WHERE
	warehouses.province_id = $selected_province
AND warehouses.stakeholder_office_id = 4
AND DATE_FORMAT(consumption_summary.reporting_start_date,'%Y-%m') = '$date'
GROUP BY
	item_pack_sizes.pk_id,warehouses.province_id) A
                    ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getPercentageOfUCsUnderstockedAdequatelyStockedOverstockedOfVaccine($selected_month, $selected_year, $selected_province) {
        $date = date('Y-m', strtotime("$selected_year-$selected_month"));

        $str_sql = "
                   SELECT 
A.province_id,
A.pk_id as item_pack_size_id,
A.item_name,
ROUND((A.stock_out/A.total_ucs) * 100,2) as stockout_percent,
ROUND((A.under_stocked/A.total_ucs) * 100,2) as understocked_percent,
ROUND((A.adequetly_stocked/A.total_ucs) * 100,2) as adequetlystocked_percent,
ROUND((A.over_stocked/A.total_ucs) * 100,2) as overstocked_percent

from
(SELECT
warehouses.province_id,
item_pack_sizes.pk_id,
item_pack_sizes.item_name,
(SELECT
count(locations.pk_id) 

FROM
locations
WHERE
locations.province_id = $selected_province AND
locations.geo_level_id = 6
Group BY province_id) as total_ucs,
SUM(case when consumption_summary.mos < 0.5 then 1 else 0 end) as stock_out,
SUM(case when consumption_summary.mos BETWEEN 0.5 and 0.99 then 1 else 0 end) as under_stocked,
SUM(case when consumption_summary.mos BETWEEN 1 and 1.5  then 1 else 0 end) as adequetly_stocked,
SUM(case when consumption_summary.mos > 1.5 then 1 else 0 end) as over_stocked 

	

	FROM
warehouses
INNER JOIN locations ON warehouses.location_id = locations.pk_id
INNER JOIN consumption_summary ON consumption_summary.location_id = warehouses.location_id
INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
WHERE
	warehouses.province_id = $selected_province
AND warehouses.stakeholder_office_id = 6

AND DATE_FORMAT(
	consumption_summary.reporting_start_date,
	'%Y-%m'
) = '$date'
GROUP BY
	item_pack_sizes.pk_id,warehouses.province_id) A

                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getNumberOfTimeDistrictStoreStockOutAndOverStock($start_date, $end_date, $selected_district) {
        $str_sql = "
                   SELECT

	B.pk_id AS item_id,
	B.item_name,
	IFNULL(A.stock_out,0) stock_out,
	IFNULL(A.over_stocked,0) over_stocked
FROM
	(
		SELECT
			warehouses.district_id,
			item_pack_sizes.pk_id AS item_id,
			item_pack_sizes.item_name,
			SUM(
				CASE
				WHEN consumption_summary.mos < 0.5 THEN
					1
				ELSE
					0
				END
			) AS stock_out,
			SUM(
				CASE
				WHEN consumption_summary.mos > 1.5 THEN
					1
				ELSE
					0
				END
			) AS over_stocked
		FROM
			consumption_summary
		INNER JOIN warehouses ON consumption_summary.location_id = warehouses.location_id
		INNER JOIN item_pack_sizes ON consumption_summary.item_pack_size_id = item_pack_sizes.pk_id
		WHERE
			warehouses.district_id = $selected_district
		AND warehouses.stakeholder_office_id = 4
		AND DATE_FORMAT(
			consumption_summary.reporting_start_date,
			'%Y-%m-%d'
		) BETWEEN '$start_date'
		AND '$end_date'
		GROUP BY
			item_pack_sizes.pk_id,
			warehouses.district_id
	) A
RIGHT JOIN (
	SELECT
		item_pack_sizes.pk_id,
		item_pack_sizes.item_name
	FROM
		item_pack_sizes
	INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
	INNER JOIN item_activities ON item_pack_sizes.pk_id = item_activities.item_pack_size_id
	WHERE
		item_pack_sizes.item_category_id = 1
	AND item_activities.stakeholder_activity_id = 1
) B ON A.item_id = B.pk_id

                    ";

        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getTehsilesOfDistrict($selected_district) {
        $str_sql = "
                   SELECT 
                        locations.pk_id,
                        locations.location_name
                    from 
                        locations
                    where 
                        locations.district_id = $selected_district
                        and locations.geo_level_id =5
                    ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

    public function getLocationNameById($location_id) {
        $str_sql = "
                    SELECT
                        locations.location_name
                    FROM
                        locations
                    WHERE
                        locations.pk_id = $location_id
                    ";
        $rec = $this->_em_read->getConnection()->prepare($str_sql);
        $rec->execute();
        return $rec->fetchAll();
    }

}
