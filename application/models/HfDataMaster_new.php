<?php

/**
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for HF Data Master
 */
class Model_HfDataMaster extends Model_Base {

    /**
     * $_table
     * @var type
     */
    protected $_table;

    /**
     * $wh_id
     * @var type
     */
    public $wh_id;

    /**
     * $detail_id
     * @var type
     */
    public $detail_id;

    /**
     * $report_month
     * @var type
     */
    public $report_month;

    /**
     * $report_year
     * @var type
     */
    public $report_year;

    /**
     * $temp
     * @var type
     */
    public $temp;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('HfDataMaster');
    }

    /**
     * Add Report
     * @param type $stockId
     * @param type $type
     * @param type $from
     */
    public function addReport($stockId, $type, $from = NULL) {
        $stock_master = new Model_StockMaster();
        $stock_master->form_values['pk_id'] = $stockId;
        $stock_master->form_values['from'] = $from;
        $result = $stock_master->getItemDetailFromStock();
        if ($result) {
            foreach ($result as $stockdata) {
                $tdate = $stockdata['transaction_date'];
                $product = $stockdata['item_id'];
                $month = App_Controller_Functions::dateFormat($tdate, "first day of this month", "m");
                $year = App_Controller_Functions::dateFormat($tdate, "first day of this month", "Y");
                $wh_id = $this->_identity->getWarehouseId();
                if ($month == date("m") && $year == date("Y")) {
                    $item = new Model_ItemPackSizes();
                    $items = $item->getAllItems();
                    foreach ($items as $one) {
                        $this->form_values['report_month'] = $month;
                        $this->form_values['report_year'] = $year;
                        $this->form_values['item_id'] = $one['pkId'];
                        $this->form_values['warehouse_id'] = $wh_id;
                        $this->form_values['created_by'] = $this->_user_id;
                        $this->adjustStockReport();
                    }
                } else {
                    $this->form_values['report_month'] = $month;
                    $this->form_values['report_year'] = $year;
                    $this->form_values['item_id'] = $product;
                    $this->form_values['warehouse_id'] = $wh_id;
                    $this->form_values['created_by'] = $this->_user_id;
                    $this->adjustStockReport();
                }
            }
        }
    }

    /**
     * Get Wastages
     * @return string
     */
    public function getWastages() {
        $form_values = $this->form_values;
        $level = $form_values['level'];
        switch ($level) {
            case 1:
                $sel_level = "N";
                break;
            case 2:
                $sel_level = "P";
                break;
            case 6:
                $sel_level = "D";
                break;
            default :
                break;
        }
        $year = $form_values['year'];
        $period = $form_values['period'];
        switch ($period) {
            case 13:
                $start_date = $year . '-01-01';
                $end_date = $year . '-03-01';
                break;
            case 14:
                $start_date = $year . '-04-01';
                $end_date = $year . '-06-01';
                break;
            case 15:
                $start_date = $year . '-07-01';
                $end_date = $year . '-09-01';
                break;
            case 16:
                $start_date = $year . '-10-01';
                $end_date = $year . '-12-01';
                break;
            case 17:
                $start_date = $year . '-01-01';
                $end_date = $year . '-06-01';
                break;
            case 18:
                $start_date = $year . '-07-01';
                $end_date = $year . '-12-01';
                break;
            case 19:
                $start_date = $year . '-01-01';
                $end_date = $year . '-12-01';
                break;
            default :
                break;
        }

        $item = $form_values['item'];
        $loc_id = $form_values['loc_id'];
        $stkid = 1;
        $str_sql = "SELECT REPgetWastage('$sel_level','$start_date','$end_date','$stkid', '$item', '$loc_id', 0) from DUAL";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        if ($this->form_values['level'] = 1) {
            $level = 'National Level';
        } else if ($this->form_values['level'] = 2) {
            $level = 'Provincial Level';
        } else if ($this->form_values['level'] = 3) {
            $level = 'District Level';
        }
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption= '$level - " . '"' . $item . '"' . "   Reported Vs Wastages Rate" . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Reported VS Wastages " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0' numberSuffix='%'>";
        foreach ($result as $row) {
            $province[$row['province_id']] = $row['provinceName'];
            $reported[$row['province_id']] = $row['RptPer'];
            $wastage_per[$row['province_id']] = $row['wastagePer'];
        }
        // Start Making Categories (Products)
        $xmlstore .= "<categories>";
        foreach ($province as $province_id => $province_name) {
            $xmlstore .= "<category label='$province_name' />";
        }
        $xmlstore .= "</categories>";
        // Stock Out Series
        $xmlstore .= "<dataset seriesName='Wastages'>";
        foreach ($wastage_per as $province_id => $value) {
            $xmlstore .= "<set value='" . number_format($value) . "' />";
        }
        $xmlstore .= "</dataset>";
        // Over Stock Series
        $xmlstore .= "<dataset seriesName='Reported UCs'>";
        foreach ($reported as $province_id => $value) {
            $xmlstore .= "<set value='" . number_format($value) . "' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Wastages By Districts
     * @param type $prov_id
     * @return string
     */
    public function getWastagesByDistricts($prov_id) {
        if (empty($prov_id)) {
            $prov_id = $this->_identity->getProvinceId();
        }
        $where = "";
        if (!empty($this->form_values['item'])) {
            $where = " AND hf_data_master.item_pack_size_id = " . $this->form_values['item'] . "";
        }
        $str_sql = "SELECT
                            B.district_id,
                            B.location_name AS districtName,
                            COALESCE (reported, NULL, 0) AS reported,
                            TotalWH,
                            COALESCE (
                                    ROUND(((reported / TotalWH) * 100), 1),NULL,0) AS RptPer,
                            COALESCE (wastagePer, NULL, 0) AS wastagePer
                    FROM
                            (
                                    SELECT
                                            UC.district_id,
                                            ROUND(
                                                    IFNULL((
                                                                    sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS wastagePer,
                                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                    FROM
                                            locations AS UC
                                    INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                    INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                    WHERE
                                            UC.geo_level_id = 6
                                    AND warehouses.stakeholder_id = 1
                                    AND warehouses.status = 1
                                    AND hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01'
                                    AND hf_data_master.issue_balance IS NOT NULL
                                    AND hf_data_master.issue_balance != 0
                                    $where
                                    GROUP BY
                                            UC.district_id
                                        UNION
                                        SELECT
                                            UC.district_id,
                                            ROUND(
                                                    IFNULL((
                                                                    sum(hf_data_master.wastages) / (SUM(hf_data_master.issue_balance) + sum(hf_data_master.wastages))) * 100,0),1) AS wastagePer,
                                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                    FROM
                                            locations AS UC
                                    INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                    INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                    WHERE
                                            UC.geo_level_id = 6
                                    AND warehouses.stakeholder_id = 1
                                    AND warehouses.status = 1
                                    AND hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01'
                                    AND hf_data_master.issue_balance IS NOT NULL
                                    AND hf_data_master.issue_balance != 0
                                    $where
                                    GROUP BY
                                            UC.district_id
                            ) AS A
                            RIGHT JOIN (
                            SELECT
                                    COUNT(DISTINCT UC.pk_id) AS TotalWH,
                                    District.location_name,
                                    warehouses.district_id
                            FROM
                                    locations AS UC
                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                            INNER JOIN locations AS District ON warehouses.district_id = District.pk_id
                            INNER JOIN pilot_districts ON warehouses.district_id = pilot_districts.district_id
                            WHERE
                                    UC.geo_level_id = 6
                            AND warehouses.stakeholder_id = 1
                            AND warehouses.status = 1
                            AND warehouses.province_id = '" . $prov_id . "'
                            GROUP BY
                                    warehouses.district_id
                    ) AS B ON A.district_id = B.district_id
                    GROUP BY
                            B.district_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        if ($this->form_values['level'] == 1) {
            $level = 'National Level';
        } else if ($this->form_values['level'] == 2) {
            $level = 'Provincial Level';
        } else if ($this->form_values['level'] == 6) {
            $level = 'District Level';
        }
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $prov_id;
        $province_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption= '$item  Reporting Rate Vs Wastage Camparison (" . $province_name . '-' . date('M Y', strtotime($this->form_values['date'])) . ")' exportFileName='Reported VS Wastages " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0' numberSuffix='%' theme='fint'>";
        foreach ($result as $row) {
            $districts[$row['district_id']] = $row['districtName'];
            $reported[$row['district_id']] = $row['RptPer'];
            $wastage_per[$row['district_id']] = $row['wastagePer'];
        }
        // Start Making Categories (Products)
        $xmlstore .= "<categories>";
        foreach ($districts as $district_id => $district_name) {
            $xmlstore .= "<category label='$district_name' />";
        }
        $xmlstore .= "</categories>";
        // Stock Out Series
        $xmlstore .= "<dataset seriesName='Wastages'>";
        foreach ($wastage_per as $district_id => $value) {
            $xmlstore .= "<set value='" . $value . "%' />";
        }
        $xmlstore .= "</dataset>";
        // Over Stock Series
        $xmlstore .= "<dataset seriesName='Reported UCs'>";
        foreach ($reported as $district_id => $value) {
            $xmlstore .= "<set value='" . $value . "%' />";
        }
        $xmlstore .= "</dataset>";
        $obj_product = new Model_ItemPackSizes();
        $prod_result = $obj_product->getProductById($this->form_values['item']);
        $xmlstore .= "<trendlines>
                <line startvalue='" . $prod_result->getWastageRateAllowed() . "' color='EE2000' displayvalue='Wastage Rate Allowed' valueonright='1' />
                </trendlines>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Reported Non Reported
     * @return string
     */
    public function reportedNonReported() {
        $date = $this->form_values['date'];
        $distId = $this->form_values['loc_id'];
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 7) {
            $distId = $this->_identity->getTehsilId();
            $where = "AND locations.parent_id = $distId";
        } else {
            $where = "AND warehouses.district_id = $distId";
        }
        $str_sql = "SELECT
        B.district_id,
        ROUND(
                (A.reportedUC / B.totalUC) * 100
        ,1) AS reportedPer,
        ROUND(
                (
                        (B.totalUC - A.reportedUC) / B.totalUC
                ) * 100
        ,1) AS nonReportedPer,
        A.item_pack_size_id
        FROM
        ( SELECT
                        COUNT(DISTINCT locations.pk_id) AS reportedUC,
                        hf_data_master.item_pack_size_id,
                        warehouses.district_id
                FROM
                        warehouses
                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                WHERE
                        warehouses.`status` = 1
                AND hf_data_master.issue_balance IS NOT NULL
                AND hf_data_master.issue_balance != 0
                AND DATE_FORMAT(
                        hf_data_master.reporting_start_date,
                        '%Y-%m'
                ) = '$date'
                AND warehouses.stakeholder_id = 1
                $where
                AND stakeholders.geo_level_id = 6
        ) A
    RIGHT JOIN ( SELECT
                Count(DISTINCT locations.pk_id) AS totalUC,
                warehouses.district_id
        FROM
                warehouses
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
        INNER JOIN warehouse_users ON warehouse_users.warehouse_id = warehouses.pk_id
        INNER JOIN locations ON warehouses.location_id = locations.pk_id
        WHERE
                warehouses.`status` = 1
        AND warehouses.stakeholder_id = 1
        $where
        AND stakeholders.geo_level_id = 6
) B ON A.district_id = B.district_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $reported = $result[0]['reportedPer'];
        $nonReported = $result[0]['nonReportedPer'];
        $districtId = $result[0]['district_id'];
        $itemPackId = $result[0]['item_pack_size_id'];
        $param = $districtId . '|' . $itemPackId . '|' . $date;
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Reporting Status' exportFileName='Reporting Status " . date('Y-m-d H:i:s') . "' numberSuffix='%' showValues='1' theme='fint'>";
        $xmlstore .= "<set label='Reported' color='#35AA47' value='$reported' link=\"JavaScript:showData('$param|1');\" />";
        $xmlstore .= "<set label='Non Reported' color='#FF5538' value='$nonReported' link=\"JavaScript:showData('$param|2');\" />";
        return $xmlstore .= "</chart>";
    }

    /**
     * Reported Non Reported By Province
     * @return string
     */
    public function reportedNonReportedByProvince() {
        $date = $this->form_values['date'];
        $prov_id = $this->form_values['province'];
        $item_id = $this->form_values['item'];
        $str_sql = "SELECT
                                B.province_id,
                                ROUND((A.reportedUC / B.totalUC) * 100) AS reportedPer,
                                ROUND(((B.totalUC - A.reportedUC) / B.totalUC) * 100) AS nonReportedPer,
                                A.item_pack_size_id
                        FROM
                                (
                                       SELECT
                                                COUNT(DISTINCT locations.pk_id) AS reportedUC,
                                                locations.province_id,
                                                hf_data_master.item_pack_size_id
                                        FROM
                                                locations
                                        INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                        WHERE
                                                locations.province_id = $prov_id
                                        AND locations.geo_level_id = 6
                                        AND warehouses.status = 1
                                        AND hf_data_master.item_pack_size_id = $item_id
                                        AND DATE_FORMAT(
                                                hf_data_master.reporting_start_date,
                                                '%Y-%m'
                                        ) = '$date'
                                ) A
                        RIGHT JOIN (
                                SELECT
                                        COUNT(locations.pk_id) AS totalUC,
                                        locations.province_id
                                FROM
                                        locations
                                WHERE
                                        locations.geo_level_id = 6
                                AND locations.province_id = $prov_id
                        ) B ON A.province_id = B.province_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $reported = $result[0]['reportedPer'];
        $nonReported = $result[0]['nonReportedPer'];
        $provinceId = $result[0]['province_id'];
        $itemPackId = $result[0]['item_pack_size_id'];
        $param = $provinceId . '|' . $itemPackId . '|' . $date;
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Reporting Status' exportFileName='Reporting Status " . date('Y-m-d H:i:s') . "' numberSuffix='%' showValues='1' theme='fint'>";
        $xmlstore .= "<set label='Reported' color='#35AA47' value='$reported' link=\"JavaScript:showDataProvince('$param|1');\" />";
        $xmlstore .= "<set label='Non Reported' color='#FF5538' value='$nonReported' link=\"JavaScript:showDataProvince('$param|2');\" />";
        return $xmlstore .= "</chart>";
    }

    /**
     * Stock Status By Item
     * @return string
     */
    public function stockStatusByItem() {
        $date = $this->form_values['date'];
        $wh_id = $this->form_values['wh_id'];
        $item_id = $this->form_values['item'];
        $str_sql = "SELECT
        A.Qty AS IssueQty,
        B.Qty AS ReceiveQty FROM ( SELECT
                            SUM(ABS(stock_detail.quantity)) AS Qty
                    FROM
                            stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    stock_master.transaction_type_id = " . Model_TransactionTypes::TRANSACTION_ISSUE . "
                    AND warehouses.status = 1
                    AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' AND
                    stock_master.from_warehouse_id = $wh_id
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
        ) A, (
                SELECT
                            SUM(ABS(stock_detail.quantity)) AS Qty
                    FROM
                     stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    stock_master.transaction_type_id = " . Model_TransactionTypes::TRANSACTION_RECIEVE . "
                    AND warehouses.status = 1
                    AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $date . "' AND
                    stock_master.to_warehouse_id = $wh_id
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id) B";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $issued = $result[0]['IssueQty'];
        $received = $result[0]['ReceiveQty'];
        $param = $wh_id . '|' . $item_id . '|' . $date;
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Stock Status' exportFileName='Stock Status " . date('Y-m-d H:i:s') . "' numberSuffix='Vials' showValues='1' theme='fint'>";
        $xmlstore .= "<set label='Received' value='$received' link=\"JavaScript:showData('$param|1');\" />";
        $xmlstore .= "<set label='Issued' value='$issued' link=\"JavaScript:showData('$param|2');\" />";
        return $xmlstore .= "</chart>";
    }

    /**
     * Vvm Stage Status
     * @return string
     */
    public function vvmStageStatus() {
        $date = $this->form_values['date'];
        $wh_id = $this->form_values['wh_id'];
        $item_id = $this->form_values['item'];
        $str_sql = "SELECT
        A.vvm_stage,
        COUNT(A.stock_batch_id) as cnt
        FROM
        ( SELECT DISTINCT
                        SUM(stock_detail.quantity) qty,
                        stock_detail.vvm_stage,
                        stock_detail.stock_batch_warehouse_id as stock_batch_id
                FROM
                 stock_detail
INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                WHERE
                        DATE_FORMAT(
                                stock_master.transaction_date,
                                '%Y-%m'
                        ) <= '$date'
                AND stock_batch_warehouses.warehouse_id = $wh_id
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                GROUP BY
                    stock_batch_warehouses.pk_id
                HAVING
                        qty > 0
                ) A
        GROUP BY
                A.vvm_stage";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Vvm Stage Status' exportFileName='Vvm Stage Status " . date('Y-m-d H:i:s') . "' numberSuffix=''  showPercentValues='1'  theme='fint'>";

        if (count($result) > 0) {
            foreach ($result as $row) {
                $vvm_stage = $row['vvm_stage'];
                $count = $row['cnt'];
                $param = $wh_id . '|' . $item_id . '|' . $date . '|' . $vvm_stage;
                $xmlstore .= "<set label='$vvm_stage' value='$count' link=\"JavaScript:showData15('$param');\" />";
            }
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Vvm Stage Status By Vvm Stage
     * @return type
     */
    public function vvmStageStatusByVvmStage() {
        $date = $this->form_values['date'];
        $wh_id = $this->form_values['wh_id'];
        $item_id = $this->form_values['item'];
        $vvm_stage = $this->form_values['type'];
        if (!empty($vvm_stage)) {
            $where = " AND placements.vvm_stage = $vvm_stage ";
        } else {
            $where = " AND placements.vvm_stage = 1";
        }
        $str_sql = "
                SELECT
                        Sum(placements.quantity) AS Qty,
                        placements.vvm_stage,
                        stock_batch.number
                FROM
                        stock_batch
                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        LEFT JOIN placements ON placements.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
                        INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
                        INNER JOIN vvm_stages ON placements.vvm_stage = vvm_stages.pk_id
                        INNER JOIN item_units ON item_pack_sizes.item_unit_id = item_units.pk_id
                WHERE
                        stock_batch_warehouses.warehouse_id = $wh_id
                        AND item_pack_sizes.item_category_id = 1
                        AND item_pack_sizes.pk_id = $item_id

                GROUP BY
                        placements.stock_batch_warehouse_id
                HAVING
                    Qty > 0
                    ORDER BY
                        item_pack_sizes.list_rank,
                        stock_batch.expiry_date
";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Reported Location
     * @return type
     */
    public function getReportedLocation() {
        $date = $this->form_values["date"];
        $dist_id = $this->form_values["loc_id"];
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 7) {
            $dist_id = $this->_identity->getTehsilId();
            $where = "locations.parent_id = $dist_id";
        } else {
            $where = "locations.district_id = $dist_id";
        }
        $str_sql = " SELECT DISTINCT
                                        locations.pk_id,
                                        locations.location_name
                                FROM
                                        locations
                                INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                WHERE
                                        $where
                                AND locations.geo_level_id = 6
                                AND warehouses.status = 1
                                AND warehouses.stakeholder_id = 1
                                AND hf_data_master.issue_balance IS NOT NULL
                                AND hf_data_master.issue_balance != 0
                                AND DATE_FORMAT(
                                        hf_data_master.reporting_start_date,
                                        '%Y-%m'
                                ) = '$date' AND
                                warehouses.stakeholder_id = 1";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Non Reported Location
     * @return type
     */
    public function getNonReportedLocation() {
        $date = $this->form_values["date"];
        $item_id = $this->form_values["item"];
        $dist_id = $this->form_values["loc_id"];
        $role_id = $this->_identity->getRoleId();
        if ($role_id == 7) {
            $dist_id = $this->_identity->getTehsilId();
            $where = "locations.parent_id = $dist_id";
        } else {
            $where = "locations.district_id = $dist_id";
        }
        $sub_sql = "Select locations.province_id from locations where $where";
        $row_sub = $this->_em_read->getConnection()->prepare($sub_sql);
        $row_sub->execute();
        $row_sub->fetchAll();
        $qry = " SELECT DISTINCT
                                        locations.pk_id
                                        FROM
                                        locations
                                        INNER JOIN warehouses ON locations.pk_id = warehouses.location_id
                                        INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                        WHERE
                                        $where
                                        AND locations.geo_level_id = 6
                                        AND warehouses.status = 1
                                        AND hf_data_master.item_pack_size_id = $item_id
                                        AND DATE_FORMAT(
                                        hf_data_master.reporting_start_date,
                                        '%Y-%m'
                                        ) = '$date' AND
                                        warehouses.stakeholder_id = 1";
        $str_sql = "SELECT DISTINCT
                                        B.pk_id,
                                        B.location_name
                                FROM ( $qry ) A
                                RIGHT JOIN (
                                        SELECT DISTINCT
                                                locations.pk_id,
                                                locations.location_name
                                        FROM
                                        locations
                                        INNER JOIN warehouses ON warehouses.location_id = locations.pk_id
                                        WHERE
                                        locations.geo_level_id = 6 AND
                                        $where AND
                                        warehouses.status = 1 AND
                                        warehouses.stakeholder_id = 1
                                ) B ON A.pk_id = B.pk_id
                                WHERE
                                        A.pk_id IS NULL";


        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Reported Location Province
     * @return type
     */
    public function getReportedLocationProvince() {
        $date = $this->form_values["date"];
        $item_id = $this->form_values["item"];
        $province = $this->form_values["province"];

        $str_sql = "SELECT
                                        B.districtId,
                                        B.districtName,
                                        ROUND(((COALESCE(A.reported,NULL,0) / B.totalWH) * 100)) AS perVal
                                FROM
                                        (SELECT
                                                    District.pk_id AS districtId,
                                                    COUNT(DISTINCT UC.pk_id) AS reported
                                            FROM
                                                    locations AS District
                                            INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                            INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                            INNER JOIN pilot_districts ON District.pk_id = pilot_districts.district_id
                                            WHERE
                                                    stakeholders.geo_level_id = 6
                                            AND hf_data_master.item_pack_size_id = $item_id
                                            AND warehouses. STATUS = 1
                                            AND District.province_id = $province
                                            AND DATE_FORMAT(
                                                    hf_data_master.reporting_start_date,
                                                    '%Y-%m'
                                            ) = '$date'
                                            AND hf_data_master.issue_balance IS NOT NULL
                                            AND warehouses.stakeholder_id = 1
                                            GROUP BY
                                                    District.pk_id
                                            ORDER BY
                                                    districtId ASC

                                        ) A
                                RIGHT JOIN (
                                        SELECT
                                                District.pk_id AS districtId,
                                                District.location_name AS districtName,
                                                COUNT(DISTINCT UC.pk_id) AS totalWH
                                        FROM
                                                locations AS District
                                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                        INNER JOIN pilot_districts ON District.pk_id = pilot_districts.district_id
                                        WHERE
                                                stakeholders.geo_level_id = 6
                                                AND warehouses.status = 1
                                        AND District.province_id = $province AND
                                        warehouses.stakeholder_id = 1
                                        GROUP BY
                                                District.pk_id
                                        ORDER BY
                                                districtId ASC
                                ) B ON A.districtId = B.districtId";


        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Non Reported Location Province
     * @return type
     */
    public function getNonReportedLocationProvince() {
        $date = $this->form_values["date"];
        $item_id = $this->form_values["item"];
        $province = $this->form_values["province"];
        $str_sql = "SELECT
                                        B.districtId,
                                        B.districtName,
                                                ROUND(
                (((B.totalWH - COALESCE(A.reported, NULL, 0)) / B.totalWH) * 100)
        ) AS perVal
                                FROM
                                        (
                                                SELECT
                                                        District.pk_id AS districtId,
                                                        COUNT(DISTINCT UC.pk_id) AS reported
                                                FROM
                                                        locations AS District
                                                INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                                INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                                                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                                INNER JOIN pilot_districts ON District.pk_id = pilot_districts.district_id
                                                WHERE
                                                        stakeholders.geo_level_id = 6
                                                AND warehouses.status = 1
                                                AND hf_data_master.item_pack_size_id = $item_id
                                                AND District.province_id = $province
                                                AND DATE_FORMAT(hf_data_master.reporting_start_date,'%Y-%m') = '$date'
                                                AND hf_data_master.issue_balance IS NOT NULL AND
                                                warehouses.stakeholder_id = 1
                                                GROUP BY
                                                        District.pk_id
                                                ORDER BY
                                                        districtId ASC
                                        ) A
                                RIGHT JOIN (
                                        SELECT
                                                District.pk_id AS districtId,
                                                District.location_name AS districtName,
                                                COUNT(DISTINCT UC.pk_id) AS totalWH
                                        FROM
                                                locations AS District
                                        INNER JOIN locations AS UC ON District.pk_id = UC.district_id
                                        INNER JOIN warehouses ON UC.pk_id = warehouses.location_id
                                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                                        INNER JOIN pilot_districts ON District.pk_id = pilot_districts.district_id
                                        WHERE
                                                stakeholders.geo_level_id = 6
                                        AND warehouses.status = 1
                                        AND District.province_id = $province AND
                                        warehouses.stakeholder_id = 1
                                        GROUP BY
                                                District.pk_id
                                        ORDER BY
                                                districtId ASC
                                ) B ON A.districtId = B.districtId";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Expiry Schedule
     * @return string
     */
    public function getExpirySchedule() {
        if (!empty($this->form_values['item'])) {
            $item_id = $this->form_values['item'];
        }
        if (!empty($this->form_values['loc_id'])) {
            $loc_id = $this->form_values['loc_id'];
        }
        $level = $this->form_values['level'];
        switch ($level) {
            case 1:
                $join = "";
                $where = "";
                break;
            case 2:
                $join = " INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id ";
                $where = " AND warehouses.province_id = $loc_id ";
                break;
            case 6:
                $join = " INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id ";
                $where = " AND warehouses.district_id = $loc_id ";
                break;
            default :
                break;
        }
        $str_sql = "SELECT
                                A.item_id,
                                A.itm_name,
                                ROUND(((A.Expire6Months / A.totalQty) * 100), 1) AS Expire6Months,
                                ROUND(((A.Expire12Months / A.totalQty) * 100), 1) AS Expire12Months,
                                ROUND(((A.Expire18Months / A.totalQty) * 100), 1) AS Expire18Months,
                                ROUND(((A.Expire18Greater / A.totalQty) * 100), 1) AS Expire18Greater
                        FROM (SELECT
                                stakeholder_item_pack_sizes.item_pack_size_id as item_id,
                                item_pack_sizes.item_name as itm_name,
                                SUM(stock_batch_warehouses.quantity) AS totalQty,
                                SUM(IF (stock_batch.expiry_date <= ADDDATE(CURDATE(), INTERVAL 6 MONTH), stock_batch_warehouses.quantity, 0)) AS Expire6Months,
                                SUM(IF (stock_batch.expiry_date > ADDDATE(CURDATE(), INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE(CURDATE(), INTERVAL 12 MONTH), stock_batch_warehouses.quantity, 0)) AS Expire12Months,
                                SUM(IF (stock_batch.expiry_date > ADDDATE(CURDATE(), INTERVAL 12 MONTH) AND stock_batch.expiry_date <= ADDDATE(CURDATE(), INTERVAL 18 MONTH), stock_batch_warehouses.quantity, 0)) AS Expire18Months,
                                SUM(IF (stock_batch.expiry_date > ADDDATE(CURDATE(), INTERVAL 18 MONTH), stock_batch_warehouses.quantity, 0)) AS Expire18Greater
                        FROM
                                stock_batch
                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        $join
                        WHERE
                        stakeholder_item_pack_sizes.item_pack_size_id IS NOT NULL
                        AND stock_batch_warehouses.quantity > 0
                        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                        $where
                        ) A";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $param = $loc_id . '|' . $item_id . '|' . $level;
        $xmlstore = "<chart theme='fint' numberSuffix='%' exportEnabled='1' exportAction='Download' caption='Stock expiry Status " . $result[0]['itm_name'] . "' exportFileName='Stock expiry Status " . $result[0]['itm_name'] . " - " . date('Y-m-d H:i:s') . "'>";
        $xmlstore .= "<set label='Expiry &lt;= 6 Months' value='" . $result[0]['Expire6Months'] . "' link=\"JavaScript:showData16('$param|1');\" />";
        $xmlstore .= "<set label='Expiry &lt;= 12 Months' value='" . $result[0]['Expire12Months'] . "' link=\"JavaScript:showData16('$param|2');\" />";
        $xmlstore .= "<set label='Expiry &lt;= 18 Months' value='" . $result[0]['Expire18Months'] . "' link=\"JavaScript:showData16('$param|3');\" />";
        $xmlstore .= "<set label='Expiry &gt; 18 Months' value='" . $result[0]['Expire18Greater'] . "' link=\"JavaScript:showData16('$param|4');\" />";
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Expiry Schedule By Type
     * @return type
     */
    public function getExpiryScheduleByType() {
        $form_values = $this->form_values;
        $type = $form_values['type'];
        $item_id = $form_values['item_id'];
        if (!empty($this->form_values['loc_id'])) {
            $loc_id = $this->form_values['loc_id'];
        }
        $level = $this->form_values['level'];
        switch ($level) {
            case 1:
                $join = "";
                $where2 = "";
                break;
            case 2:
                $join = " INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id ";
                $where2 = " AND warehouses.province_id = $loc_id ";
                break;
            case 6:
                $join = " INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id ";
                $where2 = " AND warehouses.district_id = $loc_id ";
                break;
            default :
                break;
        }
        if ($type == 1) {
            $where = ' AND stock_batch.expiry_date <= ADDDATE(CURDATE(), INTERVAL 6 MONTH)';
        } else if ($type == 2) {
            $where = ' AND stock_batch.expiry_date > ADDDATE(CURDATE(), INTERVAL 6 MONTH) AND stock_batch.expiry_date <= ADDDATE(CURDATE(), INTERVAL 12 MONTH)';
        } else if ($type == 3) {
            $where = ' AND stock_batch.expiry_date > ADDDATE(CURDATE(), INTERVAL 12 MONTH) AND stock_batch.expiry_date <= ADDDATE(CURDATE(), INTERVAL 18 MONTH)';
        } else if ($type == 4) {
            $where = ' AND stock_batch.expiry_date > ADDDATE(CURDATE(), INTERVAL 18 MONTH)';
        }
        $str_sql = "SELECT
                        item_pack_sizes.item_name,
                        stock_batch.number,
                        DATE_FORMAT(
                                stock_batch.expiry_date,
                                '%m/%d/%Y'
                        ) AS batch_expiry,
                        stock_batch_warehouses.`status`,
                        SUM(stock_batch_warehouses.quantity) AS quantity
                FROM
                        stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                $join
                WHERE
                        stakeholder_item_pack_sizes.item_pack_size_id IS NOT NULL
                AND stock_batch_warehouses.quantity > 0
                AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id
                $where
                $where2
                GROUP BY
                        stock_batch.number
                ORDER BY
                        stock_batch.expiry_date DESC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Stock Issue
     * @return string
     */
    public function stockIssue() {
        $where = "";
        if (!empty($this->form_values['item'])) {
            $where = " AND stock_batch.item_pack_size_id = " . $this->form_values['item'] . "";
        }
        $level = $this->form_values['level'];
        $loc_id = $this->form_values['loc_id'];
        $prov_id = $this->form_values['prov_id'];
        $warehouse = new Model_Locations();
        $warehouse->form_values = array(
            'level' => $level,
            'prov_id' => $prov_id,
            'loc_id' => $loc_id
        );
        $wh_id = $warehouse->getWarehouseByLevel();
        $str_sql = "SELECT
                            item_pack_sizes.item_name,
                            SUM(ABS(stock_detail.quantity)) AS Qty,
                            warehouses.warehouse_name,
                            stock_master.transaction_date
                    FROM
                            stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN item_pack_sizes ON stock_batch.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    stock_master.transaction_type_id = " . Model_TransactionTypes::TRANSACTION_ISSUE . "
                    AND warehouses.status = 1
                    AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $this->form_values['date'] . "' AND
                    stock_master.from_warehouse_id = $wh_id
                    $where
                    GROUP BY
                            stakeholder_item_pack_sizes.item_pack_size_id,
                            warehouses.pk_id
                    ORDER BY
                            Qty DESC
                    LIMIT 30";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        if ($this->form_values['level'] = 1) {
            $level = 'National Level';
        } else if ($this->form_values['level'] = 2) {
            $level = 'Provincial Level';
        } else if ($this->form_values['level'] = 3) {
            $level = 'District Level';
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $xmlstore = "<chart labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='National Level - Stock Issue Status(Vials)' subCaption = '$item  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Stock Issue " . date('Y-m-d H:i:s') . "' yAxisName='Vials' showValues='1' formatNumberScale='0'>";
        foreach ($result as $row) {
            $xmlstore .= "<set label='$row[warehouse_name]' value='$row[Qty]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Receive Warehouses
     * @return type
     */
    public function getReceiveWarehouses() {
        $wh_id = $this->form_values['wh_id'];
        $item = $this->form_values['item'];
        $str_sql = "SELECT
                            item_pack_sizes.item_name,
                            SUM(ABS(stock_detail.quantity)) AS Qty,
                            warehouses.warehouse_name,
                            stock_batch.number,
                            stock_master.transaction_date
                    FROM
                     stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    stock_master.transaction_type_id = " . Model_TransactionTypes::TRANSACTION_RECIEVE . "
                    AND warehouses.status = 1
                    AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $this->form_values['date'] . "' AND
                    stock_master.to_warehouse_id = $wh_id
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $item
                    GROUP BY
                            stakeholder_item_pack_sizes.item_pack_size_id,
                            warehouses.pk_id,
                            stock_batch_warehouses.pk_id
                    ORDER BY
                            Qty DESC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Issue Warehouses
     * @return type
     */
    public function getIssueWarehouses() {
        $wh_id = $this->form_values['wh_id'];
        $item = $this->form_values['item'];
        $str_sql = "SELECT
                            item_pack_sizes.item_name,
                            SUM(ABS(stock_detail.quantity)) AS Qty,
                            warehouses.warehouse_name,
                            stock_batch.number,
                            stock_master.transaction_date
                    FROM
                            stock_batch
                    INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
                    INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                    WHERE
                    stock_master.transaction_type_id = " . Model_TransactionTypes::TRANSACTION_ISSUE . "
                    AND warehouses.status = 1
                    AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m') = '" . $this->form_values['date'] . "' AND
                    stock_master.from_warehouse_id = $wh_id
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $item
                    GROUP BY
                            stakeholder_item_pack_sizes.item_pack_size_id,
                            warehouses.pk_id,
                            stock_batch_warehouses.pk_id
                    ORDER BY
                            Qty DESC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Different Missed Types
     * @return boolean|string
     */
    public function differentMissedTypes() {
        $loc_id = $this->form_values['loc_id'];
        $prov_id = $this->form_values['prov_id'];
        $camp_id = $this->form_values['camp'];
        $level = $this->form_values['level'];
        if (empty($camp_id)) {
            return false;
        }
        switch ($level) {
            case 1:
                $where = "";
                break;
            case 2:
                $where = "AND locations.province_id=" . $prov_id;
                break;
            case 6:
                $where = "AND locations.district_id=" . $loc_id;
                break;
            default :
                break;
        }
        $str_sql = "SELECT
                                A.pk_id,
                                A.location_name,
                                COALESCE(B.refusal, NULL, 0) AS refusal,
                                COALESCE(B.not_accessible, NULL, 0) AS not_accessible
                        FROM
                                (
                                        SELECT DISTINCT
                                                Province.location_name,
                                                Province.pk_id
                                        FROM
                                                campaign_districts
                                        INNER JOIN locations ON campaign_districts.district_id = locations.pk_id
                                        INNER JOIN locations AS Province ON locations.province_id = Province.pk_id
                                        WHERE
                                                campaign_districts.campaign_id = '" . $this->form_values['camp'] . "'
                                                    $where
                                ) A
                        LEFT JOIN (
                                SELECT
                                        SUM(campaign_data.record_not_accessible) AS not_accessible,
                                        SUM(campaign_data.record_refusal) AS refusal,
                                        warehouses.province_id
                                FROM
                                        campaign_data
                                INNER JOIN locations ON campaign_data.district_id = locations.pk_id
                                INNER JOIN warehouses ON campaign_data.warehouse_id = warehouses.pk_id
                                WHERE
                                campaign_data.campaign_id = '" . $this->form_values['camp'] . "'
                                 $where
                                GROUP BY
                                        warehouses.province_id
                        ) B ON A.pk_id = B.province_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $str_sql_sub = "SELECT
                    campaigns.campaign_name
                    FROM
                    campaigns
                    WHERE
                    campaigns.pk_id = '" . $this->form_values['camp'] . "' ";
        $row_sub = $this->_em_read->getConnection()->prepare($str_sql_sub);
        $row_sub->execute();
        $result_sub = $row_sub->fetchAll();
        $campaing_name = $result_sub[0]['campaign_name'];
        $xmlstore = "<chart labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='Campaign:$campaing_name' subCaption='Missed Children Types' exportFileName='Different Missed Types " . date('Y-m-d H:i:s') . "' yAxisName='Childs' showValues='1' formatNumberScale='0'>";
        foreach ($result as $row) {
            $provId[$row['pk_id']] = $row['location_name'];
            $refusal[$row['pk_id']] = $row['refusal'];
            $not_accessible[$row['pk_id']] = $row['not_accessible'];
        }
        $xmlstore .= "<categories>";
        foreach ($provId as $provId => $provName) {
            $xmlstore .= "<category label='$provName' />";
        }
        $xmlstore .= "</categories>";

        $xmlstore .= "<dataset seriesName='Refusal'>";
        foreach ($refusal as $provId => $value) {
            $xmlstore .= "<set value='$value' />";
        }
        $xmlstore .= "</dataset>";

        $xmlstore .= "<dataset seriesName='Not Accessible'>";
        foreach ($not_accessible as $provId => $value) {
            $xmlstore .= "<set value='$value' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Data Entry Status
     * @return boolean|string
     */
    public function dataEntryStatus() {
        $loc_id = $this->form_values['loc_id'];
        $prov_id = $this->form_values['prov_id'];
        $camp_id = $this->form_values['camp'];
        $level = $this->form_values['level'];

        if (empty($camp_id)) {
            return false;
        }
        switch ($level) {
            case 1:
                $where = "";
                break;
            case 2:
                $where = "AND warehouses.province_id=" . $prov_id;
                break;
            case 6:
                $where = "AND warehouses.district_id=" . $loc_id;
                break;
            default :
                break;
        }
        $str_sql = "SELECT
                    A.reportedWH,
                    (B.totalWH - A.reportedWH) AS remainingWH
            FROM
                    (
                            SELECT
                                    'wh',
                                    COUNT(DISTINCT campaign_data.warehouse_id) AS reportedWH
            FROM
            warehouses
            INNER JOIN campaign_data ON campaign_data.warehouse_id = warehouses.pk_id
            INNER JOIN campaign_districts ON warehouses.district_id = campaign_districts.district_id
            WHERE
                                    warehouses.stakeholder_id = 40
                                    AND warehouses.status = 1
                            AND campaign_data.campaign_id = " . $this->form_values['camp'] . "
                            $where
                    ) A
            JOIN (
                    SELECT
                            'wh',
                            COUNT(DISTINCT warehouses.pk_id) AS totalWH
            FROM
            warehouses
            INNER JOIN campaign_districts ON warehouses.district_id = campaign_districts.district_id
            WHERE
                            warehouses.stakeholder_id = 40
                            AND campaign_districts.campaign_id = " . $this->form_values['camp'] . "
                            AND warehouses.status = 1
                            $where
            ) B ON A.wh = B.wh";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        $reported_wh = $data[0]['reportedWH'];
        $remaining_wh = $data[0]['remainingWH'];
        $str_sql_sub = "SELECT
                    campaigns.campaign_name
                    FROM
                    campaigns
                    WHERE
                    campaigns.pk_id = '" . $this->form_values['camp'] . "' ";
        $row_sub = $this->_em_read->getConnection()->prepare($str_sql_sub);
        $row_sub->execute();
        $result_sub = $row_sub->fetchAll();
        $campaing_name = $result_sub[0]['campaign_name'];
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Campaign:$campaing_name' subcaption='Data Entry Status By UC' exportFileName='Data Entry Status " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' numberSuffix=' UC'  formatNumberScale='0' >";
        $xmlstore .= "<set label='Data Entered' value='$reported_wh' />";
        $xmlstore .= "<set label='Remaining/Not required' value='$remaining_wh' />";
        return $xmlstore .= "</chart>";
    }

    /**
     * Day Wise Coverage
     * @return boolean|string
     */
    public function dayWiseCoverage() {
        $loc_id = $this->form_values['loc_id'];
        $prov_id = $this->form_values['prov_id'];
        $camp_id = $this->form_values['camp'];
        $level = $this->form_values['level'];
        if (empty($camp_id)) {
            return false;
        }
        switch ($level) {
            case 1:
                $where = "";
                break;
            case 2:
                $where = "AND locations.province_id=" . $prov_id;
                break;
            case 6:
                $where = "AND locations.district_id=" . $loc_id;
                break;
            default :
                break;
        }
        $str_sql = "SELECT
                    campaign_data.campaign_day,
                    Sum(campaign_data.daily_target) AS target,
                    Sum(campaign_data.total_coverage) AS coverage
                    FROM
                    campaign_data
                    INNER JOIN locations ON campaign_data.district_id = locations.pk_id
                    WHERE
                    campaign_data.campaign_id = '" . $this->form_values['camp'] . "'
                        $where
                    GROUP BY
                            campaign_data.campaign_day,
                            campaign_data.campaign_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $str_sql_sub = "SELECT
                    campaigns.campaign_name
                    FROM
                    campaigns
                    WHERE
                    campaigns.pk_id = '" . $this->form_values['camp'] . "' ";
        $row_sub = $this->_em_read->getConnection()->prepare($str_sql_sub);
        $row_sub->execute();
        $result_sub = $row_sub->fetchAll();
        $campaing_name = $result_sub[0]['campaign_name'];
        $xmlstore = "<chart labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='Campaign:$campaing_name' subCaption='Day Wise Target Vs Coverage' exportFileName='Day Wise Coverage " . date('Y-m-d H:i:s') . "' yAxisName='Childs' showValues='1' formatNumberScale='0'>";
        $target = array();
        $coverage = array();
        foreach ($result as $row) {
            $target[] = $row['target'];
            $coverage[] = $row['coverage'];
        }
        $xmlstore .= "<categories>";
        foreach ($result as $row) {
            $day = $row['campaign_day'];
            $xmlstore .= "<category label='Day $day' />";
        }
        $xmlstore .= "</categories>";
        $xmlstore .= "<dataset seriesName='Target'>";
        foreach ($target as $value) {
            $xmlstore .= "<set value='$value' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesName='Coverage'>";
        foreach ($coverage as $value) {
            $xmlstore .= "<set value='$value' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Stock Receive
     * @return string
     */
    public function stockReceive() {
        $where = "";
        if (!empty($this->form_values['item'])) {
            $where = " AND stock_batch.item_pack_size_id = " . $this->form_values['item'] . "";
        }
        if (!empty($this->form_values['loc_id'])) {
            $where .= " AND warehouses.location_id = " . $this->form_values['loc_id'] . "";
        }
        $role_id = $this->_identity->getRoleId();
        switch ($role_id) {
            case 3:
            case 4:
            case 5:
                $level = " AND stakeholders.geo_level_id = 2 ";
                break;
            case 6:
            case 7:
            case 8:
                $level = " AND stakeholders.geo_level_id = 4 ";
                break;
            default :
                break;
        }
        $str_sql = "SELECT
                                Sum(ABS(stock_detail.quantity)) AS Qty,
                                warehouses.warehouse_name
                        FROM
                                stock_batch
                        INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                        INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                        INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                        INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
                        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                        WHERE
                        stock_master.transaction_type_id = " . Model_TransactionTypes::TRANSACTION_RECIEVE . "
                        AND warehouses.status = 1
                        AND stock_master.transaction_date BETWEEN '" . $this->form_values['date'] . "-01'
                        AND '" . $this->form_values['date'] . "-31'
                        $level
                        $where
                        GROUP BY
                                warehouses.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $xmlstore = "<chart labelDisplay='rotate' slantLabels='1' exportEnabled='1' exportAction='Download' caption='Stock Receive' exportFileName='Stock Receive " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='1' formatNumberScale='0'>";
        foreach ($result as $row) {
            $xmlstore .= "<set label='$row[warehouse_name]' value='$row[Qty]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Reporting Rate
     * @return string
     */
    public function reportingRate() {
        $str_sql = "SELECT
        A.location_id,
        A.location_name,
        CONCAT( ROUND( ( COALESCE (B.RptWH, NULL, 0) / A.TotalWH * 100 ) ), '%' ) RR
    FROM
        ( SELECT
                        Count(warehouses.pk_id) AS TotalWH,
                        warehouses.location_id,
                        locations.location_name,
                        warehouses.province_id
                FROM
                        warehouses
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                WHERE
                warehouses.province_id = " . $this->form_values['prov_id'] . "
                AND warehouses.status = 1
                AND warehouses.district_id = " . $this->form_values['dist_id'] . "
                GROUP BY
                        warehouses.location_id
        ) A
    LEFT JOIN ( SELECT
                warehouses.location_id,
                COUNT(
                        DISTINCT hf_data_master.warehouse_id
                ) AS RptWH,
                warehouses.province_id
        FROM
                warehouses
        INNER JOIN hf_data_master ON hf_data_master.warehouse_id = warehouses.pk_id
        WHERE
        warehouses.status = 1 AND
        hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01'
        AND warehouses.province_id = " . $this->form_values['prov_id'] . "
        AND warehouses.district_id = " . $this->form_values['dist_id'] . "
        GROUP BY
                warehouses.location_id
) B ON A.location_id = B.location_id
LIMIT 20";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $this->form_values['dist_id'];
        $district_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= 'Reporting of $item(Doses) in $district_name UCs(" . date('M Y', strtotime($this->form_values['date'])) . ")'   exportFileName='Reporting Rate " . date('Y-m-d H:i:s') . "' yAxisName='Reporting Rate' numberSuffix='%' showValues='1' formatNumberScale='0'>";
        foreach ($result as $row) {
            $xmlstore .= "<set label='$row[location_name]' value='$row[RR]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get SOH
     * @return string
     */
    public function getSOH() {
        $where = "";
        if (!empty($this->form_values['item'])) {
            $where = " AND hf_data_master.item_pack_size_id = " . $this->form_values['item'] . "";
        }
        $str_sql = "SELECT
                    SUM(hf_data_master.closing_balance) AS SOH,
                    warehouses.province_id,
                    locations.location_name
                    FROM
                    warehouses
                    INNER JOIN hf_data_master ON hf_data_master.warehouse_id = warehouses.pk_id
                    INNER JOIN locations ON locations.pk_id = warehouses.province_id
                    WHERE
                    hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01' $where
                    GROUP BY
                    hf_data_master.item_pack_size_id,
                    warehouses.province_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Province wise SOH Status(Doses)'
subCaption = '$item  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Provincial SOH " . date('Y-m-d H:i:s') . "' yAxisName='Stock' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[location_name]' value='$data[SOH]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get SOH By Districts
     * @param type $prov_id
     * @return string
     */
    public function getSOHByDistricts($prov_id) {
        if (empty($prov_id)) {
            $prov_id = $this->_identity->getProvinceId();
        }
        $str_sql = "SELECT
                    SUM(
                            hf_data_master.closing_balance
                    ) AS SOH,
                    warehouses.district_id,
                    locations.location_name
            FROM
                    warehouses
            INNER JOIN hf_data_master ON hf_data_master.warehouse_id = warehouses.pk_id
            INNER JOIN locations ON locations.pk_id = warehouses.district_id
            WHERE
            hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01' AND
            hf_data_master.item_pack_size_id = " . $this->form_values['item'] . " AND
            locations.geo_level_id = 4 AND
            AND warehouses.status = 1
            locations.province_id = $prov_id
            GROUP BY
                    hf_data_master.item_pack_size_id,
                    warehouses.district_id";
        $row = $this->_em_read > getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $prov_id;
        $province_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Stock On Hand(SOH) status of $item(Doses) in $province_name Stores and EPI Centers by the End of (" . date('M Y', strtotime($this->form_values['date'])) . ")'   exportFileName='Districts SOH " . date('Y-m-d H:i:s') . "' yAxisName='Stock' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[location_name]' value='$data[SOH]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Stock Status Routine
     * @return string
     */
    public function stockStatusRoutine() {
        $where = "";
        if (!empty($this->form_values['item'])) {
            $where = " AND item_pack_sizes.pk_id = " . $this->form_values['item'] . " ";
        }
        $str_sql = "SELECT
                    A.pk_id,
                    A.location_name,
                    COALESCE(A.CONS, NULL, 0) AS CONS,
                    COALESCE(A.SOH, NULL, 0) AS SOH,
                    ROUND(A.SOH / COALESCE(A.AvgCONS, NULL, 0), 1) AS MOS
                    FROM (SELECT
                            SUM(hf_data_master.issue_balance) AS CONS,
                            REPgetConsumptionAVG('N', 1, 2014, 3, 1, warehouses.province_id, 0) AS AvgCONS,
                            Sum(hf_data_master.closing_balance) AS SOH,
                            locations.pk_id,
                            locations.location_name
                            FROM
                            warehouses
                            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                            INNER JOIN hf_data_master ON hf_data_master.warehouse_id = warehouses.pk_id
                            INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
                            INNER JOIN locations ON warehouses.province_id = locations.pk_id
                            WHERE
                                    stakeholders.geo_level_id = 6
                                    AND warehouses.status = 1
                            AND hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01'
                            $where
                            GROUP BY
                                    warehouses.province_id) A";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Provincial SOH' exportFileName='Provincial SOH " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0'>";
        foreach ($result as $row) {
            $provName[$row['pk_id']] = $row['location_name'];
            $cons[$row['pk_id']] = $row['CONS'];
            $soh[$row['pk_id']] = $row['SOH'];
            $mos[$row['pk_id']] = $row['MOS'];
        }
// Start Making Categories (Products)
        $xmlstore .= "<categories>";
        foreach ($provName as $provId => $prov) {
            $xmlstore .= "<category label='$prov' />";
        }
        $xmlstore .= "</categories>";
        $xmlstore .= "<dataset seriesName='Consumption'>";
        foreach ($cons as $provId => $value) {
            $xmlstore .= "<set value='" . $value . "%' />";
        }
        $xmlstore .= "</dataset>";

        $xmlstore .= "<dataset seriesName='Stock on Hand'>";
        foreach ($soh as $provId => $value) {
            $xmlstore .= "<set value='" . $value . "' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesName='Months of Stock'>";
        foreach ($mos as $provId => $value) {
            $xmlstore .= "<set value='" . $value . "' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Wastages Rate
     * @return string
     */
    public function wastagesRate() {
        $obj_product = new Model_ItemPackSizes();
        $prod_result = $obj_product->getProductById($this->form_values['item']);
        $allowed = $prod_result->getWastageRateAllowed() / 100 * 80;
        $str_sql = "SELECT * FROM (SELECT
        locations.location_name,
        COALESCE (A.wastagePer, NULL, 0) AS wastagePer
        FROM
        ( SELECT ROUND( ( sum(hf_data_master.wastages) / ( sum( hf_data_master.issue_balance ) + sum(hf_data_master.wastages) ) ) * 100, 1 ) AS wastagePer,
                    warehouses.location_id
            FROM
                    warehouses
            INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
            INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
            WHERE
                    warehouses.stakeholder_id = 1
            AND hf_data_master.reporting_start_date = '" . $this->form_values[' date '] . "-01'
            AND hf_data_master.issue_balance IS NOT NULL
            AND hf_data_master.item_pack_size_id = " . $this->form_values['item'] . "
            AND stakeholders.pk_id = 6
            AND warehouses. STATUS = 1
            GROUP BY
                    warehouses.location_id
                    ) A
            RIGHT JOIN locations ON locations.pk_id = A.location_id
            WHERE
                    locations.geo_level_id = 6
            AND locations.province_id = " . $this->form_values['prov_id'] . "
            AND locations.district_id = " . $this->form_values['dist_id'] . ") AS A
            WHERE A.wastagePer > $allowed";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $this->form_values['dist_id'];
        $district_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='100' exportAction='Download' caption= 'Wastages Camparison of $item(Doses) in $district_name UCs(" . date('M Y', strtotime($this->form_values['date'])) . ")'   exportFileName='UCs wise Wastages Comparison " . date('Y-m-d H:i:s') . "' yAxisName='Reporting Rate' numberSuffix='%' showValues='1' formatNumberScale='0' theme='fint'>";
        foreach ($result as $row) {
            $xmlstore .= "<set label='$row[location_name]' value='$row[wastagePer]' />";
        }
        $xmlstore .= "<trendlines>
                <line startvalue='" . $prod_result->getWastageRateAllowed() . "' color='EE2000' displayvalue='Allowed' valueonright='1' />
                </trendlines>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Wastages Comparison
     * @return string
     */
    public function wastagesComparison() {
        $allowed = $this->form_values['allowed'];
        $option = $this->form_values['option'];
        $role_id = $this->_identity->getRoleId();
        $iftehsil = "AND locations.district_id = " . $this->form_values['dist_id'];
        if ($role_id == 7) {
            $this->form_values['dist_id'] = $this->_identity->getTehsilId();
            $iftehsil = "AND locations.parent_id = " . $this->form_values['dist_id'];
        }
        if ($option == 'N') {
            $where = " A.wastagePer > $allowed";
        } else {
            list($start, $end) = explode("-", $option);
            $where = " A.wastagePer >= $start AND A.wastagePer <= $end";
        }
        $str_sql = "SELECT * FROM (
        SELECT
        locations.location_name,
        COALESCE (A.wastagePer, NULL, 0) AS wastagePer
        FROM
        (SELECT
                        ROUND( IFNULL( ( sum(hf_data_master.wastages) / ( sum( hf_data_master.issue_balance ) + sum(hf_data_master.wastages) ) ) * 100, 0 ), 1 ) AS wastagePer,
                        warehouses.location_id
                FROM
                        warehouses
                INNER JOIN hf_data_master ON warehouses.pk_id = hf_data_master.warehouse_id
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                WHERE
                        warehouses.stakeholder_id = 1
                AND hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01'
                AND hf_data_master.issue_balance IS NOT NULL
                AND hf_data_master.item_pack_size_id = " . $this->form_values['item'] . "
                AND stakeholders.pk_id = 6
                AND warehouses.status = 1
                GROUP BY
                        warehouses.location_id
        ) A
            RIGHT JOIN locations ON locations.pk_id = A.location_id
            WHERE
                locations.geo_level_id = 6
            AND locations.province_id = " . $this->form_values['prov_id'] . "
            $iftehsil ) AS A
           WHERE $where ORDER BY wastagePer DESC";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $this->form_values['dist_id'];
        $district_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' labelDisplay='rotate' slantLabels='1' yAxisMaxValue='auto' exportAction='Download' caption= 'Wastage of $item(Doses) in $district_name UCs(" . date('M Y', strtotime($this->form_values['date'])) . ")'   exportFileName='UCs wise Wastages Comparison " . date('Y-m-d H:i:s') . "' yAxisName='Reporting Rate' numberSuffix='%' showValues='1' formatNumberScale='0' theme='fint'>";
        foreach ($result as $row) {
            $xmlstore .= "<set label='$row[location_name]' value='$row[wastagePer]' />";
        }
        $xmlstore .= "<trendlines>
                <line startvalue='" . $allowed . "' color='EE2000' displayvalue='" . $allowed . "% allowed' valueonright='1' />
                </trendlines>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Get MOS Districts
     * @return string
     */
    public function getMOSDistricts() {
        $where = "";
        if (!empty($this->form_values['item'])) {
            $where = " AND hf_data_master.item_pack_size_id = " . $this->form_values['item'] . "";
        }
        $str_sql = "SELECT
                        A.location_name AS provinceName,
                        ROUND(A.SOH / COALESCE(A.AvgCONS, NULL, 0), 1) AS MOS
                        FROM (SELECT
                REPgetConsumptionAVG('P', 1, 2014, 3, 0, warehouses.province_id, 0) AS AvgCONS,
                Sum(hf_data_master.closing_balance) AS SOH,
                warehouses.province_id,
                locations.location_name
                FROM
                warehouses
                INNER JOIN hf_data_master ON hf_data_master.warehouse_id = warehouses.pk_id
                INNER JOIN locations ON warehouses.province_id = locations.pk_id
                WHERE
                hf_data_master.reporting_start_date = '" . $this->form_values['date'] . "-01' AND warehouses.status = 1 $where
                GROUP BY
                warehouses.province_id) A";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Province wise MOS Status(Doses)'
     subCaption = '$item  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'  exportFileName='Provincial MOS " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[provinceName]' value='$data[MOS]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get MOS
     * @return string
     */
    public function getMOS() {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);
        $str_sql = "SELECT
                        A.location_name AS provinceName,
                        ROUND(A.SOH / COALESCE(A.AvgCONS, NULL, 0), 1) AS MOS
                        FROM (SELECT
                REPgetConsumptionAVG('P', " . $mm . ", " . $yy . ", " . $this->form_values['item'] . ", 1, locations.province_id, 0) AS AvgCONS,
                REPgetCB('P', " . $mm . ", " . $yy . ", " . $this->form_values['item'] . ", 1, locations.province_id, 0) AS SOH,
                locations.province_id,
                locations.location_name
                FROM
                locations
                WHERE locations.geo_level_id=2) A";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Province wise MOS Status(Months)'
      subCaption = '$item  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Provincial MOS " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0' theme='fint'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[provinceName]' value='$data[MOS]' />";
        }
        $xmlstore .= "<trendlines>
                <line startvalue='1' color='EE2000' displayvalue='Minimum' valueonright='1' />
                <line startvalue='3' color='EE2000' displayvalue='Maximum' valueonright='1' />
                </trendlines>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Get MOS By Districts
     * @param type $prov_id
     * @return string
     */
    public function getMOSByDistricts($prov_id) {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);

        if (empty($prov_id)) {
            $prov_id = $this->_identity->getProvinceId();
        }
        $start = $this->form_values['start'];
        $end = $this->form_values['end'];
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $mosucdashlet = "MOSP_$yy$mm$prov_id" . $this->form_values['item'];
        if (!$result = $cache->load($mosucdashlet)) {
            $em = Zend_Registry::get('doctrine');

            $str_sql_amc = "CALL REPgetAMCHF('P','" . $yy . "-" . $mm . "-01', $prov_id, '" . $this->form_values['item'] . "', 1);";
            $row_amc = $this->_em_read->getConnection()->prepare($str_sql_amc);
            $row_amc->execute();
            $result_amc = $row_amc->fetchAll();
            $this->_em_read->getConnection()->close();
            $str_sql_cb = "CALL REPgetCBHF('P','" . $yy . "-" . $mm . "-01', $prov_id, '" . $this->form_values['item'] . "', 1);";
            $row_cb = $this->_em_read->getConnection()->prepare($str_sql_cb);
            $row_cb->execute();
            $result_cb = $row_cb->fetchAll();
            $this->_em_read->getConnection()->close();
            $result = array();
            foreach ($result_amc as $row) {
                $result[$row['location_name']]['amc'] = $row['AMC'];
            }
            foreach ($result_cb as $row) {
                $result[$row['location_name']]['cb'] = $row['CB'];
            }
            $cache->save($result, $mosucdashlet);
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $prov_id;
        $this->view->location_name = $province_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Months of Stock(MOS) status of $item(Months) in $province_name Stores  and EPI centers by the End of " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Districts MOS " . date('Y-m-d H:i:s') . "' yAxisName='Months' showValues='1' formatNumberScale='0' theme='fint'>";
        foreach ($result as $loc_name => $data) {
            if ($data['amc'] == 0) {
                $mos = 0;
            } else {
                $mos = round($data['cb'] / $data['amc'], 2);
            }
            if ($mos >= $start && $mos <= $end) {
                $xmlstore .= "<set label='$loc_name' value='$mos' />";
            }
        }
        $xmlstore .= "<trendlines>
                <line startvalue='$start' color='EE2000' displayvalue='Minimum' valueonright='1' />
                <line startvalue='$end' color='EE2000' displayvalue='Maximum' valueonright='1' />
                </trendlines>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Get MOS By Uc
     * @param type $dist_id
     * @return string
     */
    public function getMOSByUc($dist_id) {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);
        if (empty($dist_id)) {
            $dist_id = $this->_identity->getDistrictId();
        }
        $start = $this->form_values['start'];
        $end = $this->form_values['end'];
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $mosucdashlet = "MOSD_$yy$mm$dist_id" . $this->form_values['item'];
        if (!$result = $cache->load($mosucdashlet)) {
            $str_sql_amc = "CALL REPgetAMCHF('D','" . $yy . "-" . $mm . "-01', $dist_id, '" . $this->form_values['item'] . "', 1);";

            $row_amc = $this->_em_read->getConnection()->prepare($str_sql_amc);
            $row_amc->execute();
            $result_amc = $row_amc->fetchAll();
            $this->_em_read->getConnection()->close();
            $str_sql_cb = "CALL REPgetCBHF('D','" . $yy . "-" . $mm . "-01', $dist_id, '" . $this->form_values['item'] . "', 1);";

            $row_cb = $this->_em_read->getConnection()->prepare($str_sql_cb);
            $row_cb->execute();
            $result_cb = $row_cb->fetchAll();
            $this->_em_read->getConnection()->close();
            $result = array();
            foreach ($result_amc as $row) {
                $result[$row['location_name']]['amc'] = $row['AMC'];
            }
            foreach ($result_cb as $row) {
                $result[$row['location_name']]['cb'] = $row['CB'];
            }
            $cache->save($result, $mosucdashlet);
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $dist_id;
        $province_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' color='black' caption='Months of Stock(MOS) status of $item(Months) in $province_name UCs by the End of " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'  exportFileName='Districts MOS " . date('Y-m-d H:i:s') . "' yAxisName='Months' showValues='1' formatNumberScale='0.0' theme='fint'>";
        $totalucs = 0;
        $currentucs = 0;
        foreach ($result as $loc_name => $data) {
            $totalucs++;
            if ($data['amc'] == 0) {
                $mos = 0;
            } else {
                $mos = round($data['cb'] / $data['amc'], 2);
            }
            if ($mos >= $start && $mos <= $end) {
                $currentucs++;
                $xmlstore .= "<set label='$loc_name' value='$mos' />";
            }
        }
        $xmlstore .= "<trendlines>
                <line startvalue='$start' color='EE2000' displayvalue='$start' valueonright='1' showontop='1' />
                <line startvalue='$end' color='EE2000' displayvalue='$end' valueonright='1' showontop='1' />
                </trendlines>";
        // return $xmlstore .="</chart>";
        $xmlstore .= "</chart>";

        return array(
            'xmlstore' => $xmlstore,
            'totalucs' => $totalucs,
            'currentucs' => $currentucs
        );
    }

    /**
     * Get AMC
     * @return string
     */
    public function getAMC() {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);
        $str_sql = "SELECT DISTINCT
                        REPgetConsumptionAVG ('P'," . $mm . "," . $yy . "," . $this->form_values['item'] . ",1,locations.province_id,0) AS AvgCONS,
                        locations.location_name as provinceName
                FROM
                         locations where locations.geo_level_id=2 LIMIT 30";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Province wise AMC Status(Doses)'
subCaption = '$item  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Provincial AMC " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[provinceName]' value='$data[AvgCONS]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get AMC By Districts
     * @param type $prov_id
     * @return string
     */
    public function getAMCByDistricts($prov_id) {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);

        if (empty($prov_id)) {
            $prov_id = $this->_identity->getProvinceId();
        }
        $str_sql = "SELECT DISTINCT
                        REPgetConsumptionAVG ('D'," . $mm . "," . $yy . "," . $this->form_values['item'] . ",1,locations.district_id,0) AS AvgCONS,
                        locations.location_name as districtName
                FROM
                         locations where locations.geo_level_id=4 AND locations.province_id = $prov_id LIMIT 30";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $prov_id;
        $province_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Average Month Consumption(AMC) status of $item(Doses) in $province_name Stores  and EPI centers by the End of " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'
        exportFileName='Districts AMC " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[districtName]' value='$data[AvgCONS]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get AMC By Uc
     * @param type $dist_id
     * @return string
     */
    public function getAMCByUc($dist_id) {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);

        if (empty($dist_id)) {
            $dist_id = $this->_identity->getDistrictId();
        }
        $str_sql = "SELECT DISTINCT
                        REPgetConsumptionAVG ('U'," . $mm . "," . $yy . "," . $this->form_values['item'] . ",1,locations.pk_id,0) AS AvgCONS,
                        locations.location_name as ucName
                FROM
                         locations where locations.geo_level_id=6 AND locations.district_id = $dist_id LIMIT 30";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $dist_id;
        $province_name = $this->view->location_name = $locations->getLocationName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Average Month Consumption(AMC) status of $item(Doses) in $province_name UCs by the End of " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'  exportFileName='Districts Doses " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[ucName]' value='$data[AvgCONS]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Illegal Wastages
     * @return string
     */
    public function illegalWastages() {
        $date = $this->form_values['date'] . "-01";
        $date_in = str_replace("-", "", $this->form_values['date']);
        $item = $this->form_values['item'];
        $allowed = $this->form_values['allowed'];
        $province = $this->form_values['province'];
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $mosucdashlet = "ILLWAS_$date_in$province$item";
        if (!$result = $cache->load($mosucdashlet)) {
            $str_sql_wastage = "CALL GetIllegalWastageofDistrictHF('$item','$date', $province, '" . $allowed . "', 1);";
            $row_wastage = $this->_em_read->getConnection()->prepare($str_sql_wastage);
            $row_wastage->execute();
            $result_wastage = $row_wastage->fetchAll();
            $this->_em_read->getConnection()->close();
            $result = array();
            foreach ($result_wastage as $row) {
                $result[$row['location_name']]['location_name'] = $row['location_name'];
                $result[$row['location_name']]['wastage_ucs'] = $row['wastage_uc'];
                $result[$row['location_name']]['total_ucs'] = $row['totalUcs'];
            }
            $cache->save($result, $mosucdashlet);
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item_name = $item_pack_sizes->getProductName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='District wise number of UCs having wastage > $allowed% '
        subCaption = '$item_name  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='District wise percentage of UCs having wastage > 50% " . date('Y-m-d H:i:s') . "' yAxisName='Number of UCs' showValues='1' formatNumberScale='0' theme='fint' numberSuffix=''>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[location_name]($data[total_ucs])' value='" . $data['wastage_ucs'] . "' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Consumption
     * @return string
     */
    public function getConsumption() {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);
        $str_sql = "SELECT DISTINCT
                        REPgetConsumption (" . $mm . "," . $yy . "," . $this->form_values['item'] . ",1,'P',locations.province_id) AS CONS,
                        locations.location_name as provinceName
                FROM
                         locations where locations.geo_level_id=2 LIMIT 30";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' bgColor='white' caption='Province wise Consumption Status(Doses)'
         subCaption = '$item  " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "' exportFileName='Provincial Consumption " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0' theme='fint'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[provinceName]' value='$data[CONS]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Consumption By Districts
     * @param type $prov_id
     * @return string
     */
    public function getConsumptionByDistricts($prov_id) {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);

        if (empty($prov_id)) {
            $prov_id = $this->_identity->getProvinceId();
        }
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $mosucdashlet = "CONSP_$yy$mm$prov_id" . $this->form_values['item'];
        if (!$result = $cache->load($mosucdashlet)) {
            $str_sql_con = "CALL REPgetConsumptionHF('P','" . $yy . "-" . $mm . "-01', $prov_id, '" . $this->form_values['item'] . "', 1);";
            $row_consumption = $this->_em_read->getConnection()->prepare($str_sql_con);
            $row_consumption->execute();
            $result_consumption = $row_consumption->fetchAll();
            $this->_em_read->getConnection()->close();
            $result = array();
            foreach ($result_consumption as $row) {
                $result[$row['location_name']]['districtName'] = $row['location_name'];
                $result[$row['location_name']]['CONS'] = $row['consumption'];
                $result[$row['location_name']]['target'] = $row['target'];
            }
            $cache->save($result, $mosucdashlet);
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $prov_id;
        $this->view->location_name = $province_name = $locations->getLocationName();
        $xmlstore = "<chart labelDisplay='rotate' exportEnabled='1' exportAction='Download' bgColor='white' caption='Vaccination status of $item(Doses) in $province_name Stores  and EPI centers  during " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'
        exportFileName='Districts Consumption " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='1' formatNumberScale='0' theme='fint'>";
        $xmlstore .= '<categories>';
        foreach ($result as $data) {
            $coverage = ROUND(($data['CONS'] / $data['target']) * 100, 1);
            $xmlstore .= '<category label="' . $data[districtName] . ' (' . $coverage . '%)" />';
        }
        $xmlstore .= '</categories>';
        $xmlstore .= "<dataset seriesname='Vaccination' color='0075C2'>";
        foreach ($result as $data) {
            $xmlstore .= "<set value='$data[CONS]' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesname='Target' parentyaxis='S' renderas='Line' color='f8bd19'>";
        foreach ($result as $data) {
            $xmlstore .= "<set value='$data[target]' />";
        }
        $xmlstore .= "</dataset>";
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Consumption By Uc
     * @param type $dist_id
     * @return string
     */
    public function getConsumptionByUc($dist_id) {
        $date = $this->form_values['date'];
        list($yy, $mm) = explode("-", $date);
        if (empty($dist_id)) {
            $dist_id = $this->_identity->getDistrictId();
        }
        $teh_id = $this->form_values['teh_id'];
        $cache = Zend_Registry::get('cacheManager')->getCache('file');
        $mosucdashlet = "CONSD_$yy$mm$dist_id$teh_id" . $this->form_values['item'];
        if (!$result = $cache->load($mosucdashlet)) {
            $str_sql_con = "CALL REPgetConsumptionHF('D','" . $yy . "-" . $mm . "-01', $teh_id, '" . $this->form_values['item'] . "', 1);";

            $row_consumption = $this->_em_read->getConnection()->prepare($str_sql_con);
            $row_consumption->execute();
            $result_consumption = $row_consumption->fetchAll();
            $this->_em_read->getConnection()->close();
            $result = array();
            foreach ($result_consumption as $row) {
                $result[$row['location_name']]['ucName'] = $row['location_name'];
                $result[$row['location_name']]['CONS'] = $row['consumption'];
                $result[$row['location_name']]['target'] = $row['target'];
                $result[$row['location_name']]['wastages'] = $row['wastages'];
                $result[$row['location_name']]['wastage_rate_allowed'] = $row['wastage_rate_allowed'];
            }
            $cache->save($result, $mosucdashlet);
        }
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $teh_id;
        $tehsil_name = $this->view->location_name = $locations->getLocationName();
        if ($this->form_values['wastages'] == 2) {
            $xmlstore = "<chart labelDisplay='rotate' exportEnabled='1' exportAction='Download' bgColor='white' caption='Vaccination status of $item in $tehsil_name Stores  and EPI centers  during " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'
            exportFileName='Districts Consumption " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= '<categories>';
            foreach ($result as $data) {
                $coverage = ROUND(($data['CONS'] / $data['target']) * 100, 1);
                $xmlstore .= '<category label="' . $data[ucName] . ' (' . $coverage . '%)" />';
            }
            $xmlstore .= '</categories>';




            $xmlstore .= "<dataset seriesname='Vaccination' color='0075C2'>";
            foreach ($result as $data) {
                $total = $data[CONS] + $data[wastages];
                $consumptionP = Round(($data[CONS] / $total) * 100);
                $xmlstore .= "<set value='$consumptionP' />";
            }
            $xmlstore .= "</dataset>";
            $xmlstore .= "<dataset seriesname='Permissible Wastages' color='#008000'>";
            foreach ($result as $data) {
                $total = $data[CONS] + $data[wastages];
                $wastageP = Round(($data[wastages] / $total) * 100);

                if ($wastageP <= $data[wastage_rate_allowed]) {
                    $was_per = $wastageP;
                } else {
                    $was_per = $data[wastage_rate_allowed];
                }

                $xmlstore .= "<set value='$was_per' />";
            }
            $xmlstore .= "</dataset>";
            $xmlstore .= "<dataset seriesname='Not Permissible Wastages' color='#FF0000'>";
            foreach ($result as $data) {
                $total = $data[CONS] + $data[wastages];
                $wastageP = Round(($data[wastages] / $total) * 100);
                $waste_not_per = $data[wastage_rate_allowed];
                if ($wastageP == $waste_not_per) {
                    $waste_not_per = '';
                } else if ($wastageP < $waste_not_per) {
                    $waste_not_per = '';
                } else {
                    $waste_not_per = $wastageP - $data[wastage_rate_allowed];
                }
                $xmlstore .= "<set value='$waste_not_per' />";
            }
            $xmlstore .= "</dataset>";
//            $xmlstore .= "<dataset seriesname='Target' parentyaxis='S' renderas='Line' color='f8bd19'>";
//            foreach ($result as $data) {
//                $total = $data[CONS] + $data[wastages];
//                $totalP = Round(($data[target] / $total) * 100);
//                $xmlstore .= "<set value='$totalP' />";
//            }
//            $xmlstore .= "</dataset>";
            return $xmlstore .= "</chart>";
        } else {
            $xmlstore = "<chart labelDisplay='rotate' exportEnabled='1' exportAction='Download' bgColor='white' caption='Vaccination status of $item(Doses) in $tehsil_name Stores  and EPI centers  during " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'
        exportFileName='Districts Consumption " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='1' formatNumberScale='0' theme='fint'>";
            $xmlstore .= '<categories>';
            foreach ($result as $data) {
                $coverage = ROUND(($data['CONS'] / $data['target']) * 100, 1);
                $xmlstore .= '<category label="' . $data[ucName] . ' (' . $coverage . '%)" />';
            }
            $xmlstore .= '</categories>';
            $xmlstore .= "<dataset seriesname='Vaccination' color='0075C2'>";
            foreach ($result as $data) {
                $xmlstore .= "<set value='$data[CONS]' />";
            }
            $xmlstore .= "</dataset>";
            $xmlstore .= "<dataset seriesname='Permissible Wastages' color='#008000'>";
            foreach ($result as $data) {
                $total = $data[CONS] + $data[wastages];
                $wastageP = Round(($data[wastages] / $total) * 100);
                $wastageP_to = ($data[wastages] / $total) * 100;
                $toal_doses = ($wastageP_to * $total) / 100;
                if ($wastageP <= $data[wastage_rate_allowed]) {
                    $was_per = $toal_doses;
                } else if ($wastageP > $data[wastage_rate_allowed]) {
                    $wastage_rem = $data[wastage_rate_allowed];
                    $toal_doses_rem = ($wastage_rem * $total) / 100;
                    $was_per = $toal_doses_rem;
                }
                $xmlstore .= "<set value='$was_per' />";
            }

            $xmlstore .= "</dataset>";
            $xmlstore .= "<dataset seriesname='Not Permissible Wastages' color='#FF0000'>";
            foreach ($result as $data) {
                $total = $data[CONS] + $data[wastages];
                $wastageP = Round(($data[wastages] / $total) * 100);
                $waste_not_per = $data[wastage_rate_allowed];
                $wastageP_to = ($data[wastages] / $total) * 100;
                if ($wastageP == $waste_not_per) {
                    $waste_not_per = '';
                } else if ($wastageP <= $waste_not_per) {
                    $waste_not_per = '';
                } else {
                    $wastage_rem = $wastageP_to - $data[wastage_rate_allowed];
                    $toal_doses_rem = ($wastage_rem * $total) / 100;
                    $waste_not_per = $toal_doses_rem;
                }
                $xmlstore .= "<set value='$waste_not_per' />";
            }
            $xmlstore .= "</dataset>";
            $xmlstore .= "<dataset seriesname='Target' parentyaxis='S' renderas='Line' color='f8bd19'>";
            foreach ($result as $data) {
                $xmlstore .= "<set value='$data[target]' />";
            }
            $xmlstore .= "</dataset>";
            return $xmlstore .= "</chart>";
        }
    }

    /**
     * Stock Position
     * @return string
     */
    public function stockPosition() {
        $str_sql = "SELECT
                A.item_name,
                COALESCE(A.CONS, NULL, 0) AS CONS,
                COALESCE(A.SOH, NULL, 0) AS SOH,
                ROUND(A.SOH / COALESCE(A.AvgCONS, NULL, 0), 1) AS MOS
                FROM (SELECT
                        item_pack_sizes.item_name,
                        Sum(hf_data_master.issue_balance) AS CONS,
                        REPgetConsumptionAVG('N', 1, 2014, hf_data_master.item_pack_size_id, 1, 0, 0) AS AvgCONS,
                        Sum(hf_data_master.closing_balance) AS SOH
                FROM
                warehouses
                INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
                INNER JOIN hf_data_master ON hf_data_master.warehouse_id = warehouses.pk_id
                INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
                WHERE
                stakeholders.geo_level_id = 6 AND
                AND warehouses.status = 1
                hf_data_master.reporting_start_date = '2014-01-01' AND
                warehouses.province_id = 1 AND
                item_pack_sizes.item_category_id = 1
                GROUP BY
                hf_data_master.item_pack_size_id) A";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Stock Position' exportFileName='Provincial MOS " . date('Y-m-d H:i:s') . "' yAxisName='Percentage' showValues='1' formatNumberScale='0'>";
        foreach ($result as $data) {
            $xmlstore .= "<set label='$data[item_name]' value='$data[MOS]' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Save Data
     * @return type
     */
    public function saveData() {
        $report_data = $this->getLastReport();

        if ($report_data) {
            $this->form_values['pk_id'] = $report_data['pk_id'];

            $this->form_values['received_balance'] = ($report_data['received_balance'] + $this->form_values['received_balance']);
            $this->form_values['closing_balance'] = ($this->form_values['opening_balance'] + $this->form_values['received_balance'] - $report_data['issue_balance'] + $report_data['adjustments']);

            return $this->update();
        } else {
            return $this->create();
        }
    }

    /**
     * Create
     * @return boolean
     */
    public function create() {
        $warehouse_data = new HfDataMaster();
        $warehouse_data->setOpeningBalance($this->form_values['opening_balance']);
        $warehouse_data->setReceivedBalance($this->form_values['received_balance']);
        $warehouse_data->setClosingBalance($this->form_values['closing_balance']);
        $warehouse_data->setReportingStartDate(new \DateTime($this->form_values['reporting_start_date']));
        $item = $this->_em->getRepository("ItemPackSizes")->find($this->form_values['item_id']);
        $warehouse_data->setItemPackSize($item);
        $warehouse = $this->_em->getRepository("Warehouses")->find($this->form_values['warehouse_id']);
        $warehouse_data->setWarehouse($warehouse);
        $warehouse_data->setCreatedDate(App_Tools_Time::now());
        $warehouse_data->setModifiedDate(App_Tools_Time::now());
        $warehouse_data->setIsCalculated(0);
        $user = $this->_em->getRepository('Users')->find($this->form_values['created_by']);
        $warehouse_data->setModifiedBy($user);
        $warehouse_data->setCreatedBy($user);
        $this->_em->persist($warehouse_data);
        $this->_em->flush();
        return true;
    }

    /**
     * Update
     * @return boolean
     */
    public function update() {
        $warehouse_data = $this->_em->getRepository("HfDataMaster")->find($this->form_values['pk_id']);
        $warehouse_data->setReceivedBalance($this->form_values['received_balance']);
        $warehouse_data->setClosingBalance($this->form_values['closing_balance']);
        $warehouse_data->setModifiedDate(new \DateTime(App_Controller_Functions::dateTimeToDbFormat(date("d/m/Y"))));
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $warehouse_data->setModifiedBy($user);
        $this->_em->persist($warehouse_data);
        $this->_em->flush();
        return true;
    }

    /**
     * Get Last Report
     * @return boolean
     */
    public function getLastReport() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wd')
                ->from("HfDataMaster", "wd")
                ->where("wd.itemPackSize = " . $this->form_values['item_id'])
                ->andWhere("DATE_FORMAT(wd.reportingStartDate,'%Y-%m-%d') = '" . $this->form_values['reporting_start_date'] . "'")
                ->andWhere("wd.warehouse = " . $this->form_values['warehouse_id']);
        $rs = $str_sql->getQuery()->getResult();
        if (!empty($rs) && count($rs) > 0) {
            return array(
                "pk_id" => $rs[0]->getPkId(),
                "received_balance" => $rs[0]->getReceivedBalance(),
                "issue_balance" => $rs[0]->getIssueBalance(),
                "adjustments" => $rs[0]->getAdjustments(),
                "closing_balance" => $rs[0]->getClosingBalance(),
                "opening_balance" => $rs[0]->getOpeningBalance()
            );
        } else {
            return FALSE;
        }
    }

    /**
     * Is Report Exists
     * @return boolean
     */
    public function isReportExists() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wd')
                ->from("HfDataMaster", "wd")
                ->andWhere("DATE_FORMAT(wd.reportingStartDate,'%Y-%m-%d') = '" . $this->form_values['reporting_start_date'] . "'")
                ->andWhere("wd.warehouse = " . $this->form_values['warehouse_id']);
        $rs = $str_sql->getQuery()->getResult();
        return !empty($rs) && count($rs) > 0;
    }

    /**
     * Closing Balance Of Month
     * @param type $month
     * @param type $year
     * @param type $wh_id
     * @param type $item_id
     * @return string
     */
    public function closingBalanceOfMonth($month, $year, $wh_id, $item_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('wd.closingBalance')
                ->from("HfDataMaster", "wd")
                ->where("wd.itemPackSize = " . $item_id)
                ->andWhere("DATE_FORMAT(wd.reportingStartDate,'%Y-%m') = '" . $year . "-" . $month . "'")
                ->andWhere("wd.warehouse = " . $wh_id);
        $rs = $str_sql->getQuery()->getResult();
        if (count($rs) > 0) {
            return $rs[0]['closingBalance'];
        } else {
            return '0';
        }
    }

    /**
     * Get Month Year By Warehouse Id
     * @return int
     */
    public function getMonthYearByWarehouseId() {
        $warehouse_id = $this->form_values['warehouse_id'];
        $level = $this->form_values['level'];
        if ($level == '6') {
            $querypro = "SELECT * from (
            SELECT DISTINCT
              MONTH (hf_data_master.reporting_start_date) as report_month,
              YEAR (hf_data_master.reporting_start_date) as report_year,
              locations.pk_id  as location_id,
              hf_data_master.reporting_start_date as reporting_start_date
            FROM
                    hf_data_master
            INNER JOIN warehouses  ON hf_data_master.warehouse_id = warehouses.pk_id
            INNER JOIN locations  ON warehouses.location_id = locations.pk_id
            WHERE
                    hf_data_master.warehouse_id = '$warehouse_id'
            AND warehouses.STATUS = 1) A
            ORDER BY reporting_start_date DESC";
        } else {
            $querypro = "SELECT
        *
        FROM
                ( SELECT DISTINCT
                                MONTH (
                                        stock_master.transaction_date
                                ) AS report_month,
                                YEAR (
                                                stock_master.transaction_date
                                ) AS report_year,
                                locations.pk_id AS location_id,
                                stock_master.transaction_date AS reporting_start_date
                        FROM
                                stock_master
                        INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
                        INNER JOIN locations ON warehouses.location_id = locations.pk_id
                        WHERE
                        stock_master.from_warehouse_id = '$warehouse_id'
                        AND warehouses.STATUS = 1
                ) A
        GROUP BY
        DATE_FORMAT(reporting_start_date,'%Y-%m')
        ORDER BY
        reporting_start_date DESC";
        }

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $rs = $row->fetchAll();
        if (!empty($rs) && count($rs) > 0) {
            return $rs;
        } else {
            return 0;
        }
    }

    /**
     * Get Month Year By Warehouse Id 2
     * @return int
     */
    public function getMonthYearByWarehouseId2() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT MONTH(wd.reportingStartDate) as report_month,'
                        . 'YEAR(wd.reportingStartDate) as report_year,'
                        . 'l.pkId as location_id')
                ->from("HfDataMaster", "wd")
                ->join("wd.warehouse", "w")
                ->join("w.location", "l")
                ->where("wd.warehouse = " . $this->form_values['warehouse_id'])
                ->andWhere("w.status=1")
                ->orderBy("wd.reportingStartDate", "DESC");

        $rs = $str_sql->getQuery()->getResult();
        if (!empty($rs) && count($rs) > 0) {
            return $rs;
        } else {
            return 0;
        }
    }

    /**
     * Get Month Year By Warehouse Id Log Book
     * @return int
     */
    public function getMonthYearByWarehouseIdLogBook() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT MONTH(wd.vaccinationDate) as report_month,'
                        . 'YEAR(wd.vaccinationDate) as report_year,'
                        . 'l.pkId as location_id')
                ->from("LogBook", "wd")
                ->join("wd.warehouse", "w")
                ->join("w.location", "l")
                ->where("wd.warehouse = " . $this->form_values['warehouse_id'])
                ->andWhere("w.status=1")
                ->orderBy("wd.vaccinationDate", "DESC");
        $rs = $str_sql->getQuery()->getResult();
        if (!empty($rs) && count($rs) > 0) {
            return $rs;
        } else {
            return 0;
        }
    }

    /**
     * Get Stock Report Params
     * @param type $detailId
     * @return boolean
     */
    public function getStockReportParams($detailId) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MONTH(s.transactionDate) as in_month,YEAR(s.transactionDate) as in_year,ips.pkId as itemPackSizeId,w.pkId as warehouseId,sd.pkId as detailId ,u.pkId as createdBy')
                ->from("StockDetail", "sd")
                ->join("sd.stockMaster", "s")
                ->join("s.createdBy", "u")
                ->join("sd.stockBatchWarehouse", "sbw")
                ->join("sbw.stockBatch", "sb")
                ->join("sb.packInfo", "pi")
                ->join("pi.stakeholderItemPackSize", "sip")
                ->join("sip.itemPackSize", "ips")
                ->join("sbw.warehouse", "w")
                ->where("sd.pkId = $detailId")
                ->andWhere("w.status=1");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Adjust Stock Report
     * @return boolean
     */
    public function adjustStockReport() {
        $row = $this->_em->getConnection()->prepare("SELECT
                REPUpdateData(" . $this->form_values['report_month'] . "," . $this->form_values['report_year'] . "," . $this->form_values['item_id'] . ",
                " . $this->form_values['warehouse_id'] . ", " . $this->form_values['created_by'] . ") from DUAL");
        $row->execute();
        return true;
    }

    /**
     * Monthly Consumtion Temp
     * @return type
     */
    public function monthlyConsumtionTemp() {
        $temp = $this->temp;
        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));
        $temp = explode("|", $temp);
        $wh_id = $temp[0];
        $rpt_date = $temp[1];
        $is_new_rpt = $temp[2];
        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];
        $dd = $tt[2];
        $newdate = new DateTime($rpt_date);
        $newdate->modify("-1 month");
        $check_date = $newdate->format("Y-m-d");
        if ($mm == '1') {
            $month = "Jan";
        } else if ($mm == '2') {
            $month = "Feb";
        } else if ($mm == '3') {
            $month = "Mar";
        } else if ($mm == '4') {
            $month = "Apr";
        } else if ($mm == '5') {
            $month = "May";
        } else if ($mm == '6') {
            $month = "Jun";
        } else if ($mm == '7') {
            $month = "Jul";
        } else if ($mm == '8') {
            $month = "Aug";
        } else if ($mm == '9') {
            $month = "Sep";
        } else if ($mm == '10') {
            $month = "Oct";
        } else if ($mm == '11') {
            $month = "Nov";
        } else if ($mm == '12') {
            $month = "Dec";
        }

        $warehouse = new Model_Warehouses();
        $warehouse->form_values['pk_id'] = $wh_id;
        $stakeholder_id = $warehouse->getStakeholderIdByWarehouseId();
        if ($is_new_rpt == 1) {
            $reports = new Model_Reports();
            $prev_month_date = $reports->getPreviousMonthReportDate($rpt_date);
        } else {
            $prev_month_date = $rpt_date;
        }
        return array(
            'wh_id' => $wh_id,
            'rpt_date' => $rpt_date,
            'is_new_rpt' => $is_new_rpt,
            'yy' => $yy,
            'mm' => $mm,
            'dd' => $dd,
            'prev_month_date' => $prev_month_date,
            'stakeholder_id' => $stakeholder_id,
            'month' => $month,
            'check_date' => $check_date
        );
    }

    /**
     * Add Monthly Consumption
     * @return boolean
     */
    public function addMonthlyConsumption22() {
        $data = $this->form_values;

        //$date = date("Y-m-d h:i:s");
        //$posted_array = $data['flitm_id'];
        //$opening_array = $data['opening'];
        //$received_array = $data['received'];
        //$dispensed_array = $data['dispensed'];
        //$vials_used_array = $data['vials_used'];
        //$unusable_vials_array = $data['unusable_vials'];
        //$closing_balance_array = $data['closing_balance'];
        //$nearest_expiry_array = $data['nearest_expiry'];
        //$doses_per_unit_array = $data['doses_per_unit'];

        foreach ($data['data'] as $product => $values) {
            $wh_id = $data['wh_id'];

            $opening = 0;
            $received = 0;
            //$consumption = 0;
            $closing = 0;

            foreach ($values as $row2->$values2) {
                $opening += $values2['opening'];
                $received += $values2['received'];
//                foreach ($values2['consumption'] as $row3=>$values3){
//                    $consumption += $values3['closing'];
//                }
                $closing += $values2['closing'];
            }

            //$vials_used = $vials_used_array[$key];
//                $doses = $doses_per_unit_array[$key];
//                $issue = $dispensed_array[$key];
//                $itemid = $posted_array[$key];
//                $wh_adj = $unusable_vials_array[$key] * $doses;
//                $wastages = (( $vials_used * $doses) - $issue ) + abs($wh_adj);
            if ($data['is_new_report'] == 0) {
                $str_qry_hf = "SELECT hf_data_master.pk_id
                       FROM
                     hf_data_master
                     where
                     hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                     and hf_data_master.item_pack_size_id =  GetProductId('" . $product . "')
                     and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                $this->_em = Zend_Registry::get('doctrine');
                $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                $row_hf->execute();
                $result_hf = $row_hf->fetchAll();
                $hf_data_master_id_edit = $result_hf[0]['pk_id'];
                if (!empty($hf_data_master_id_edit)) {
                    $wh_data = $this->_table->find($hf_data_master_id_edit);
                } else {
                    $wh_data = new HfDataMaster();
                }
            } else {
                $wh_data = new HfDataMaster();
            }
            $item = $this->_em->getRepository('ItemPackSizes')->findByItemName($product);
            $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
            $wh_data->setItemPackSize($item);
            $wh_data->setWarehouse($warehouse);
            $user = $this->_em->getRepository('Users')->find($this->_user_id);
            $wh_data->setCreatedBy($user);
            $wh_data->setOpeningBalance($opening);
            $wh_data->setReceivedBalance($received);
            $wh_data->setIssueBalance(0);
            $wh_data->setClosingBalance($closing);
            //$wh_data->setVialsUsed($vials_used_array[$key]);
            //$wh_data->setAdjustments($unusable_vials_array[$key] * $doses);
            $wh_data->setReportingStartDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['rpt_date'])));
            //if (!empty($nearest_expiry_array[$key])) {
            //  $wh_data->setNearestExpiry(new \DateTime(App_Controller_Functions::dateToDbFormat($nearest_expiry_array[$key])));
            //}
            //$wh_data->setWastages($wastages);
            //$wh_data->setStatus(0);
            $wh_data->setCreatedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($date)));
            $wh_data->setModifiedDate(new \DateTime(App_Controller_Functions::dateToDbFormat(date("Y-m-d"))));
            $wh_data->setModifiedBy($user);
            $wh_data->setStatus(0);
            $this->_em->persist($wh_data);
            $this->_em->flush();
            $hf_data_master_last_id = $wh_data->getPkId();
            if ($data['is_new_report'] == 0) {
                $str_qry_hf_detail = "SELECT hf_data_master.pk_id
                       FROM
                     hf_data_master
                     where
                     hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                     and hf_data_master.item_pack_size_id =  GetProductId('" . $product . "')
                     and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                $this->_em = Zend_Registry::get('doctrine');
                $row_hf_detail = $this->_em->getConnection()->prepare($str_qry_hf_detail);
                $row_hf_detail->execute();
                $result_hf_detail = $row_hf_detail->fetchAll();
                $hf_data_master_id_detail_edit = $result_hf_detail[0]['pk_id'];
                if (!empty($hf_data_master_id_detail_edit)) {
                    $row_detail = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id_detail_edit));
                    if (!empty($row_detail)) {
                        $detail_id = $row_detail[0]->getPkId();
                        $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                    } else {
                        $hf_data_detail = new HfDataDetail();
                    }
                } else {
                    $hf_data_detail = new HfDataDetail();
                }
            } else {

                $hf_data_detail = new HfDataDetail();
            }
            $hf_data_detail->setFixedInsideUcFemale($issue);
            $hf_data_master_i = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_last_id);
            $hf_data_detail->setHfDataMaster($hf_data_master_i);
            $hf_data_detail->setCreatedBy($user);
            $hf_data_detail->setModifiedBy($user);
            $this->_em->persist($hf_data_detail);
            $this->_em->flush();
            // Deleted data from Draft
            $str_qry_draft_detail = "DELETE hf_data_detail_draft.*
                        FROM
                       hf_data_master_draft,hf_data_detail_draft
                       where
                       hf_data_master_draft.pk_id = hf_data_detail_draft.hf_data_master_id
                        and hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
            $this->_em = Zend_Registry::get('doctrine');
            $row_draft_detail = $this->_em->getConnection()->prepare($str_qry_draft_detail);
            $row_draft_detail->execute();
            $str_qry_draft_master = "DELETE hf_data_master_draft.*
                        FROM
                       hf_data_master_draft
                       where
                       hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
            $this->_em = Zend_Registry::get('doctrine');
            $row_draft_master = $this->_em->getConnection()->prepare($str_qry_draft_master);
            $row_draft_master->execute();
            // end
            // If previous month is edited then it should carried to the last data entry month
            /* ----------------- Start ----------------- */
            $st_date = $data['rpt_date'] . '-01';
            $start_date = date('Y-m-d', strtotime("+1 month", strtotime($st_date)));
            $de_qry = "SELECT
                            hf_data_master.reporting_start_date
                    FROM
                            hf_data_master
                    WHERE
                            hf_data_master.warehouse_id = $wh_id
                    AND hf_data_master.item_pack_size_id = $itemid
                    ORDER BY
                            hf_data_master.reporting_start_date DESC
                    LIMIT 1";
            $this->_em = Zend_Registry::get('doctrine');
            $row_1 = $this->_em->getConnection()->prepare($de_qry);
            $row_1->execute();
            $result_de_qry = $row_1->fetchAll();
            if (!empty($result_de_qry['0']['reporting_start_date'])) {
                $endDate = date('Y-m-d', strtotime($result_de_qry['0']['reporting_start_date']));
                $endDate = date('Y-m-d', strtotime("+1 month", strtotime($endDate)));
                $begin = new DateTime($start_date);
                $end = new DateTime($endDate);
                $interval = DateInterval::createFromDateString('1 month');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $date) {
                    $date = $date->format("Y-m-d");
                    $this->_em = Zend_Registry::get('doctrine');
                    $row_12 = $this->_em->getConnection()->prepare("SELECT REPUpdateCarryForward('" . $date . "'," . $itemid . "," . $wh_id . ")  from DUAL");
                    $row_12->execute();
                }
            }
            /* ----------------- End ----------------- */
        }
        // Track history
        $ip = $_SERVER['REMOTE_ADDR'];
        $warehouse_history = new WarehousesUpdateHistory();
        $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
        $warehouse_history->setWarehouse($warehouse);
        $warehouse_history->setReportDate(App_Controller_Functions::dateToDbFormat($data['rpt_date']));
        $warehouse_history->setCreatedDate(App_Tools_Time::now());
        $warehouse_history->setModifiedDate(App_Tools_Time::now());
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $warehouse_history->setCreatedBy($user);
        $warehouse_history->setModifiedBy($user);
        $warehouse_history->setIpAddress($ip);
        $this->_em->persist($warehouse_history);
        $this->_em->flush();
        return true;
    }

    /**
     * Add Monthly Consumption Draft
     */
    public function addMonthlyConsumptionDraft() {
        $data = $this->form_values;
        $str_qry_detail = "DELETE hf_data_detail_draft.*
                        FROM
                       hf_data_master_draft,hf_data_detail_draft
                       where
                       hf_data_master_draft.pk_id = hf_data_detail_draft.hf_data_master_id
                        and hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
        $this->_em = Zend_Registry::get('doctrine');
        $row_detail = $row = $this->_em->getConnection()->prepare($str_qry_detail);
        $row_detail->execute();
        $str_qry_master = "DELETE hf_data_master_draft.*
                        FROM
                       hf_data_master_draft
                       where
                       hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
        $this->_em = Zend_Registry::get('doctrine');
        $row_master = $row = $this->_em->getConnection()->prepare($str_qry_master);
        $row_master->execute();
        $this->_em->flush();
        $posted_array = $data['flitm_id'];
        $opening_array = $data['opening_balance'];
        $received_array = $data['received'];
        $dispensed_array = $data['dispensed'];
        $vials_used_array = $data['vials_used'];
        $unusable_vials_array = $data['unusable_vials'];
        $closing_balance_array = $data['closing_balance'];
        $nearest_expiry_array = $data['nearest_expiry'];
        $doses_per_unit_array = $data['doses_per_unit'];
        foreach ($closing_balance_array as $key => $val) {
            if ($val != '' && $val >= 0) {
                $vials_used = $vials_used_array[$key];
                $doses = $doses_per_unit_array[$key];
                $issue = $dispensed_array[$key];
                $wh_adj = $unusable_vials_array[$key] * $doses;
                $wastages = (( $vials_used * $doses) - $issue ) + abs($wh_adj);
                $wh_data = new HfDataMasterDraft();
                $item = $this->_em->getRepository('ItemPackSizes')->find($posted_array[$key]);
                $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
                $wh_data->setItemPackSize($item);
                $wh_data->setWarehouse($warehouse);
                $user = $this->_em->getRepository('Users')->find($this->_user_id);
                $wh_data->setCreatedBy($user);
                $wh_data->setModifiedBy($user);
                $wh_data->setOpeningBalance($opening_array[$key]);
                $wh_data->setReceivedBalance($received_array[$key]);
                $wh_data->setIssueBalance($issue);
                $wh_data->setClosingBalance($val);
                $wh_data->setVialsUsed($vials_used_array[$key]);
                $wh_data->setAdjustments($unusable_vials_array[$key] * $doses);
                $wh_data->setReportingStartDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['rpt_date'])));
                if (!empty($nearest_expiry_array[$key])) {
                    $wh_data->setNearestExpiry(new \DateTime(App_Controller_Functions::dateToDbFormat($nearest_expiry_array[$key])));
                }
                $wh_data->setWastages($wastages);
                $wh_data->setCreatedDate(App_Tools_Time::now());
                $wh_data->setModifiedDate(App_Tools_Time::now());
                $this->_em->persist($wh_data);
                $this->_em->flush();
                $hf_data_master_id = $wh_data->getPkId();
                $hf_data_detail = new HfDataDetailDraft();
                $hf_data_detail->setFixedInsideUcFemale($issue);
                $hf_data_master_i = $this->_em->getRepository('HfDataMasterDraft')->find($hf_data_master_id);
                $hf_data_detail->setHfDataMaster($hf_data_master_i);
                $hf_data_detail->setCreatedBy($user);
                $hf_data_detail->setModifiedBy($user);
                $this->_em->persist($hf_data_detail);
                $this->_em->flush();
            }
        }
    }

    /**
     * Add Monthly Consumption 2 Draft
     */
    public function addMonthlyConsumption2Draft() {
        $data = $this->form_values;
        $date = date("Y-m-d h:i:s");
        $str_qry_detail = "DELETE hf_data_detail_draft.*
                        FROM
                       hf_data_master_draft,hf_data_detail_draft
                       where
                       hf_data_master_draft.pk_id = hf_data_detail_draft.hf_data_master_id
                        and hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
        $this->_em = Zend_Registry::get('doctrine');
        $row_detail = $row = $this->_em->getConnection()->prepare($str_qry_detail);
        $row_detail->execute();
        $str_qry_master = "DELETE hf_data_master_draft.*
                        FROM
                       hf_data_master_draft
                       where
                       hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
        $this->_em = Zend_Registry::get('doctrine');
        $row_master = $row = $this->_em->getConnection()->prepare($str_qry_master);
        $row_master->execute();
        $this->_em->flush();
        $posted_array = $data['flitm_id'];
        $vaccine_schedule_id = $data['vaccine_schedule_id'];
        $opening_array = $data['opening_balance'];
        $received_array = $data['received'];
        $dispensed_array = $data['dispensed'];
//        $pregenant_women = $data['pregenant_women'];
//        $non_pregenant_women = $data['non_pregenant_women'];
//
//        $pregenant_women_total = $data['pregenant_women_total'];
//        $non_pregenant_women_total = $data['non_pregenant_women_total'];

        $pregenant_women = $data['pregenant_women'];
        $outside_pregenant_women = $data['outside_pregenant_women'];
        $non_pregenant_women = $data['non_pregenant_women'];
        $outside_non_pregenant_women = $data['outside_non_pregenant_women'];
        $pregnant_women = $data['pregnant_women1'];
        $pregenant_women_total = $data['pregenant_women_total'];
        $outside_pregenant_women_total = $data['outside_pregenant_women_total'];
        $non_pregenant_women_total = $data['non_pregenant_women_total'];
        $outside_non_pregenant_women_total = $data['outside_non_pregenant_women_total'];


        $total_population = $data['total_population'];
        $pregnant_women = $data['pregnant_women1'];
        $cba = $data['cba'];
        $item_category = $data['item_category'];
        $unusable_vials_array = $data['unusable_vials'];
        $closing_balance_array = $data['closing_balance'];
        $strat_no = $data['start_no'];
        $no_of_doses = $data['no_of_doses'];
        $children_live_birth = $data['children_live_birth'];
        $surviving_children_0_11_M = $data['surviving_children_0_11_M'];
        $children_aged_12_23_M = $data['children_aged_12_23_M'];
        $two_year_above = $data['2_year_above'];
        $fixed_planned_sessions = $data['fixed_planned_sessions'];
        $fixed_actually_held_sessions = $data['fixed_actually_held_sessions'];
        $outreach_planned_sessions = $data['outreach_planned_sessions'];
        $outreach_actually_held_sessions = $data['outreach_actually_held_sessions'];
        foreach ($closing_balance_array as $key => $val) {
            if ($val != '' && $val >= 0) {
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] ) - $unusable_vials_array[$key];
                } else {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] );
                }
                $wh_id = $data['wh_id'];
                if ($item_category[$key] == 1) {
                    $nod = $no_of_doses[$key];
                    for ($i = $strat_no[$key]; $i <= $no_of_doses[$key]; $i++) {
                        if ($i == 0) {
                            $nod += 1;
                        }
                        $vacine_schedule = ($i == 1 && $i == $nod) ? '' : $i;
                        if (strlen($vacine_schedule) == 0) {
                            $vacine_schedule = 1;
                        }
                        $fix_inuc_m_11 = $data['fix_inuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_11 = $data['fix_inuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_11 = $data['fix_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_11 = $data['fix_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_11 = $data['outreach_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_11 = $data['outreach_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_11 = $data['outreach_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_11 = $data['outreach_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_m_23 = $data['fix_inuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_23 = $data['fix_inuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_23 = $data['fix_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_23 = $data['fix_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_23 = $data['outreach_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_23 = $data['outreach_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_23 = $data['outreach_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_23 = $data['outreach_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_m_24 = $data['fix_inuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_24 = $data['fix_inuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_24 = $data['fix_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_24 = $data['fix_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_24 = $data['outreach_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_24 = $data['outreach_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_24 = $data['outreach_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_24 = $data['outreach_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $issue += $fix_inuc_m_11 + $fix_inuc_f_11 + $fix_outuc_m_11 + $fix_outuc_f_11 + $outreach_m_11 + $outreach_f_11 + $outreach_outuc_m_11 + $outreach_outuc_f_11 + $fix_inuc_m_23 + $fix_inuc_f_23 + $fix_outuc_m_23 + $fix_outuc_f_23 + $outreach_m_23 + $outreach_f_23 + $outreach_outuc_m_23 + $outreach_outuc_f_23 + $fix_inuc_m_24 + $fix_inuc_f_24 + $fix_outuc_m_24 + $fix_outuc_f_24 + $outreach_m_24 + $outreach_f_24 + $outreach_outuc_m_24 + $outreach_outuc_f_24;
                    }
                } else if ($item_category[$key] == 2) {
                    $issue = $pregenant_women_total + $outside_pregenant_women_total + $non_pregenant_women_total + $outside_non_pregenant_women_total;
                } else if ($item_category[$key] == 3) {
                    $issue = $dispensed_array[$key];
                }
                $wh_adj = $unusable_vials_array[$key];
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $wastages = ($vials_used - $issue) + abs($wh_adj);
                } else {
                    $wastages = ($vials_used - $issue);
                }
                $hf_data_master = new HfDataMasterDraft();
                $item = $this->_em->getRepository('ItemPackSizes')->find($posted_array[$key]);
                $hf_data_master->setItemPackSize($item);
                if (!empty($data['wh_id'])) {
                    $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
                    $hf_data_master->setWarehouse($warehouse);
                }
                $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
                $hf_data_master->setCreatedBy($user_id);
                $hf_data_master->setModifiedBy($user_id);
                $hf_data_master->setOpeningBalance($opening_array[$key]);
                $hf_data_master->setReceivedBalance($received_array[$key]);
                $hf_data_master->setIssueBalance($issue);
                $hf_data_master->setClosingBalance($val);
                $hf_data_master->setVialsUsed($vials_used);
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $hf_data_master->setAdjustments($unusable_vials_array[$key]);
                }
                $hf_data_master->setReportingStartDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['rpt_date'])));
                $hf_data_master->setWastages($wastages);
                $hf_data_master->setCreatedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($date)));
                $hf_data_master->setModifiedDate(new \DateTime(App_Controller_Functions::dateToDbFormat(date("Y-m-d"))));
                $this->updateYearlyTargets($wh_id, App_Controller_Functions::dateToDbFormat($data['rpt_date']), $children_live_birth, $surviving_children_0_11_M, $children_aged_12_23_M, $pregnant_women, $cba, $two_year_above, $total_population);
                $this->updateHfSessions($wh_id, App_Controller_Functions::dateToDbFormat($data['rpt_date']), $fixed_planned_sessions, $fixed_actually_held_sessions, $outreach_planned_sessions, $outreach_actually_held_sessions);
                $this->_em->persist($hf_data_master);
                $this->_em->flush();
                $hf_data_master_id = $hf_data_master->getPkId();
                if ($item_category[$key] == 1) {
                    $nod = $no_of_doses[$key];
                    for ($i = $strat_no[$key]; $i <= $no_of_doses[$key]; $i++) {
                        if ($i == 0) {
                            $nod += 1;
                        }
                        $vacine_schedule = ($i == 1 && $i == $nod) ? '' : $i;
                        if (strlen($vacine_schedule) == 0) {
                            $vacine_schedule = 1;
                        }
                        $fix_inuc_m_11 = $data['fix_inuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_11 = $data['fix_inuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_11 = $data['fix_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_11 = $data['fix_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_11 = $data['outreach_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_11 = $data['outreach_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_11 = $data['outreach_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_11 = $data['outreach_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_m_23 = $data['fix_inuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_23 = $data['fix_inuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_23 = $data['fix_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_23 = $data['fix_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_23 = $data['outreach_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_23 = $data['outreach_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_23 = $data['outreach_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_23 = $data['outreach_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_m_24 = $data['fix_inuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_24 = $data['fix_inuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_24 = $data['fix_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_24 = $data['fix_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_24 = $data['outreach_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_24 = $data['outreach_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_24 = $data['outreach_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_24 = $data['outreach_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $hf_data_detail = new HfDataDetailDraft();
                        $ageGroup = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_0_11);
                        $hf_data_detail->setAgeGroup($ageGroup);
                        $hf_data_detail->setFixedInsideUcFemale($fix_inuc_f_11);
                        $hf_data_detail->setFixedInsideUcMale($fix_inuc_m_11);
                        $hf_data_detail->setFixedOutsideUcFemale($fix_outuc_f_11);
                        $hf_data_detail->setFixedOutsideUcMale($fix_outuc_m_11);
                        $hf_data_detail->setOutreachFemale($outreach_f_11);
                        $hf_data_detail->setOutreachMale($outreach_m_11);
                        $hf_data_detail->setOutreachOutsideFemale($outreach_outuc_f_11);
                        $hf_data_detail->setOutreachOutsideMale($outreach_outuc_m_11);
                        $hf_data_detail->setVaccineScheduleId($vacine_schedule);
                        $hf_data_master_i = $this->_em->getRepository('HfDataMasterDraft')->find($hf_data_master_id);
                        $hf_data_detail->setHfDataMaster($hf_data_master_i);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail->setModifiedBy($user);
                        $hf_data_detail->setCreatedBy($user);
                        $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail);
                        $this->_em->flush();
                        $hf_data_detail1 = new HfDataDetailDraft();
                        $ageGroup1 = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_12_23);
                        $hf_data_detail1->setAgeGroup($ageGroup1);
                        $hf_data_detail1->setFixedInsideUcFemale($fix_inuc_f_23);
                        $hf_data_detail1->setFixedInsideUcMale($fix_inuc_m_23);
                        $hf_data_detail1->setFixedOutsideUcFemale($fix_outuc_f_23);
                        $hf_data_detail1->setFixedOutsideUcMale($fix_outuc_m_23);
                        $hf_data_detail1->setOutreachFemale($outreach_f_23);
                        $hf_data_detail1->setOutreachMale($outreach_m_23);
                        $hf_data_detail1->setOutreachOutsideFemale($outreach_outuc_f_23);
                        $hf_data_detail1->setOutreachOutsideMale($outreach_outuc_m_23);
                        $hf_data_detail1->setVaccineScheduleId($vacine_schedule);
                        $hf_data_master_i1 = $this->_em->getRepository('HfDataMasterDraft')->find($hf_data_master_id);
                        $hf_data_detail1->setHfDataMaster($hf_data_master_i1);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail1->setModifiedBy($user);
                        $hf_data_detail1->setCreatedBy($user);
                        $hf_data_detail1->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail1->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail1);
                        $this->_em->flush();
                        $hf_data_detail2 = new HfDataDetailDraft();
                        $ageGroup2 = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_24);
                        $hf_data_detail2->setAgeGroup($ageGroup2);
                        $hf_data_detail2->setFixedInsideUcFemale($fix_inuc_f_24);
                        $hf_data_detail2->setFixedInsideUcMale($fix_inuc_m_24);
                        $hf_data_detail2->setFixedOutsideUcFemale($fix_outuc_f_24);
                        $hf_data_detail2->setFixedOutsideUcMale($fix_outuc_m_24);
                        $hf_data_detail2->setOutreachFemale($outreach_f_24);
                        $hf_data_detail2->setOutreachMale($outreach_m_24);
                        $hf_data_detail2->setOutreachOutsideFemale($outreach_outuc_f_24);
                        $hf_data_detail2->setOutreachOutsideMale($outreach_outuc_m_24);
                        $hf_data_detail2->setVaccineScheduleId($vacine_schedule);
                        $hf_data_master_i2 = $this->_em->getRepository('HfDataMasterDraft')->find($hf_data_master_id);
                        $hf_data_detail2->setHfDataMaster($hf_data_master_i2);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail2->setModifiedBy($user);
                        $hf_data_detail2->setCreatedBy($user);
                        $hf_data_detail2->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail2->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail2);
                        $this->_em->flush();
                    }
                }

                if ($item_category[$key] == 2) {
                    foreach ($pregenant_women as $key => $val) {
                        $hf_data_detail = new HfDataDetailDraft();
                        $hf_data_detail->setPregnantWomen($val);
                        $hf_data_detail->setNonPregnantWomen($non_pregenant_women[$key]);

                        $hf_data_detail->setOutsidePregnantWomen($outside_pregenant_women[$key]);
                        $hf_data_detail->setOutsideNonPregnantWomen($outside_non_pregenant_women[$key]);
                        $hf_data_detail->setVaccineScheduleId($vaccine_schedule_id[$key]);
                        $hf_data_master_i = $this->_em->getRepository('HfDataMasterDraft')->find($hf_data_master_id);
                        $hf_data_detail->setHfDataMaster($hf_data_master_i);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail->setModifiedBy($user);
                        $hf_data_detail->setCreatedBy($user);
                        $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail);
                        $this->_em->flush();
                    }
                }
            }
        }
    }

    /**
     * Add Monthly Consumption 2 Validation
     * @return type
     */
    public function addMonthlyConsumptionValidation() {
        $error_array = array();
        $data = $this->form_values;
        App_Controller_Functions::pr($data);
        //$posted_array = $data['flitm_id'];
        $opening_array = $data['opening_balance'];
        $received_array = $data['received'];
        //$dispensed_array = $data['dispensed'];
        //$pregenant_women_total = $data['pregenant_women_total'];
        //$outside_pregenant_women_total = $data['outside_pregenant_women_total'];
        //$non_pregenant_women_total = $data['non_pregenant_women_total'];
        //$outside_non_pregenant_women_total = $data['outside_non_pregenant_women_total'];
        //$item_category = $data['item_category'];
        //$unusable_vials_array = $data['unusable_vials'];
        $closing_balance_array = $data['closing_balance'];
        // vaccine schedule
        //$strat_no = $data['start_no'];
        //$no_of_doses = $data['no_of_doses'];
        foreach ($closing_balance_array as $key => $val) {
            if ($val != '' && $val >= 0) {
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] ) - $unusable_vials_array[$key];
                } else {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] );
                }
                if ($item_category[$key] == 1) {
                    $nod = $no_of_doses[$key];
                    $issue = 0;
                    for ($i = $strat_no[$key]; $i <= $no_of_doses[$key]; $i++) {
                        if ($i == 0) {
                            $nod += 1;
                        }
                        $vacine_schedule = ($i == 1 && $i == $nod) ? '' : $i;

                        if (strlen($vacine_schedule) == 0) {
                            $vacine_schedule = 1;
                        }
                        $fix_inuc_m_11 = $data['fix_inuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_11 = $data['fix_inuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_11 = $data['fix_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_11 = $data['fix_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_11 = $data['outreach_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_11 = $data['outreach_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_11 = $data['outreach_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_11 = $data['outreach_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_m_23 = $data['fix_inuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_23 = $data['fix_inuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_23 = $data['fix_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_23 = $data['fix_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_23 = $data['outreach_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_23 = $data['outreach_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_23 = $data['outreach_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_23 = $data['outreach_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_m_24 = $data['fix_inuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_24 = $data['fix_inuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_24 = $data['fix_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_24 = $data['fix_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_24 = $data['outreach_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_24 = $data['outreach_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_24 = $data['outreach_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_24 = $data['outreach_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $issue += $fix_inuc_m_11 + $fix_inuc_f_11 + $fix_outuc_m_11 + $fix_outuc_f_11 + $outreach_m_11 + $outreach_f_11 + $outreach_outuc_m_11 + $outreach_outuc_f_11 + $fix_inuc_m_23 + $fix_inuc_f_23 + $fix_outuc_m_23 + $fix_outuc_f_23 + $outreach_m_23 + $outreach_f_23 + $outreach_outuc_m_23 + $outreach_outuc_f_23 + $fix_inuc_m_24 + $fix_inuc_f_24 + $fix_outuc_m_24 + $fix_outuc_f_24 + $outreach_m_24 + $outreach_f_24 + $outreach_outuc_m_24 + $outreach_outuc_f_24;
                    }
                } else if ($item_category[$key] == 2) {
                    $issue = $pregenant_women_total + $outside_pregenant_women_total + $non_pregenant_women_total + $outside_non_pregenant_women_total;
                } else if ($item_category[$key] == 3) {
                    $issue = $dispensed_array[$key];
                }
                $itemid = $posted_array[$key];
                $wh_adj = $unusable_vials_array[$key];
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $wastages = ($vials_used - $issue) + abs($wh_adj);
                } else {
                    $wastages = ($vials_used - $issue);
                }
                if ($wastages < 0) {
                    $error_array[] = $itemid;
                }
            }
        }
        if (empty($error_array)) {
            $this->addMonthlyConsumption2($data);
        } else {
            return $error_array;
        }
    }

    /**
     * Add Monthly Consumption 2 Validation
     * @return type
     */
    public function addMonthlyConsumption2Validation() {
        $error_array = array();
        $data = $this->form_values;
        //App_Controller_Functions::pr($data);
        $posted_array = $data['flitm_id'];
        $opening_array = $data['opening_balance'];
        $received_array = $data['received'];
        $dispensed_array = $data['dispensed'];
        $pregenant_women_total = $data['pregenant_women_total'];
        $outside_pregenant_women_total = $data['outside_pregenant_women_total'];
        $non_pregenant_women_total = $data['non_pregenant_women_total'];
        $outside_non_pregenant_women_total = $data['outside_non_pregenant_women_total'];
        $item_category = $data['item_category'];
        $unusable_vials_array = $data['unusable_vials'];
        $closing_balance_array = $data['closing_balance'];
        // vaccine schedule
        $strat_no = $data['start_no'];
        $no_of_doses = $data['no_of_doses'];
        foreach ($closing_balance_array as $key => $val) {
            if ($val != '' && $val >= 0) {
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] ) - $unusable_vials_array[$key];
                } else {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] );
                }
                if ($item_category[$key] == 1) {
                    $nod = $no_of_doses[$key];
                    $issue = 0;
                    for ($i = $strat_no[$key]; $i <= $no_of_doses[$key]; $i++) {
                        if ($i == 0) {
                            $nod += 1;
                        }
                        $vacine_schedule = ($i == 1 && $i == $nod) ? '' : $i;

                        if (strlen($vacine_schedule) == 0) {
                            $vacine_schedule = 1;
                        }
                        $fix_inuc_m_11 = $data['fix_inuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_11 = $data['fix_inuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_11 = $data['fix_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_11 = $data['fix_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_11 = $data['outreach_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_11 = $data['outreach_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_11 = $data['outreach_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_11 = $data['outreach_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_11 = $data['mobile_inside_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_11 = $data['mobile_inside_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_11 = $data['lhw_inside_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_11 = $data['lhw_inside_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $fix_inuc_m_23 = $data['fix_inuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_23 = $data['fix_inuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_23 = $data['fix_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_23 = $data['fix_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_23 = $data['outreach_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_23 = $data['outreach_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_23 = $data['outreach_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_23 = $data['outreach_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_23 = $data['mobile_inside_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_23 = $data['mobile_inside_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_23 = $data['lhw_inside_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_23 = $data['lhw_inside_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $fix_inuc_m_24 = $data['fix_inuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_24 = $data['fix_inuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_24 = $data['fix_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_24 = $data['fix_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_24 = $data['outreach_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_24 = $data['outreach_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_24 = $data['outreach_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_24 = $data['outreach_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_24 = $data['mobile_inside_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_24 = $data['mobile_inside_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_24 = $data['lhw_inside_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_24 = $data['lhw_inside_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $issue += $fix_inuc_m_11 + $fix_inuc_f_11 + $fix_outuc_m_11 + $fix_outuc_f_11 + $outreach_m_11 + $outreach_f_11 + $outreach_outuc_m_11 + $outreach_outuc_f_11 + $mobile_inside_m_11 + $mobile_inside_f_11 + $lhw_inside_m_11 + $lhw_inside_f_11 + $fix_inuc_m_23 + $fix_inuc_f_23 + $fix_outuc_m_23 + $fix_outuc_f_23 + $outreach_m_23 + $outreach_f_23 + $outreach_outuc_m_23 + $outreach_outuc_f_23 + $mobile_inside_m_23 + $mobile_inside_f_23 + $lhw_inside_m_23 + $lhw_inside_f_23 + $fix_inuc_m_24 + $fix_inuc_f_24 + $fix_outuc_m_24 + $fix_outuc_f_24 + $outreach_m_24 + $outreach_f_24 + $outreach_outuc_m_24 + $outreach_outuc_f_24 + $mobile_inside_m_24 + $mobile_inside_f_24 + $lhw_inside_m_24 + $lhw_inside_f_24;
                    }
                } else if ($item_category[$key] == 2) {
                    $issue = $pregenant_women_total + $outside_pregenant_women_total + $non_pregenant_women_total + $outside_non_pregenant_women_total;
                } else if ($item_category[$key] == 3) {
                    $issue = $dispensed_array[$key];
                }
                $itemid = $posted_array[$key];
                $wh_adj = $unusable_vials_array[$key];
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $wastages = ($vials_used - $issue) + abs($wh_adj);
                } else {
                    $wastages = ($vials_used - $issue);
                }
                if ($wastages < 0) {
                    $error_array[] = $itemid;
                }
            }
        }
        if (empty($error_array)) {
            $this->addMonthlyConsumption2($data);
        } else {
            return $error_array;
        }
    }

    public function addMonthlyConsumption() {
        $data = $this->form_values;

        $children_live_birth = $data['children_live_birth'];
        $surviving_children_0_11_M = $data['surviving_children_0_11_M'];
        $children_aged_12_23_M = $data['children_aged_12_23_M'];
        $two_year_above = $data['2_year_above'];
        $total_population = $data['total_population'];
        $cba = $data['cba'];
        $pregnant_women = $data['pregnant_women1'];
        $fixed_planned_sessions = $data['fixed_planned_sessions'];
        $fixed_actually_held_sessions = $data['fixed_actually_held_sessions'];
        $outreach_planned_sessions = $data['outreach_planned_sessions'];
        $outreach_actually_held_sessions = $data['outreach_actually_held_sessions'];
        $wh_id = $data['wh_id'];

        foreach ($data['data'] as $product => $values) {

            $opening = 0;
            $received = 0;
            //$consumption = 0;
            $closing = 0;

            foreach ($values as $row2->$values2) {
                $opening += $values2['opening'];
                $received += $values2['received'];
//                foreach ($values2['consumption'] as $row3=>$values3){
//                    $consumption += $values3['closing'];
//                }
                $closing += $values2['closing'];
            }

            if ($data['is_new_report'] == 0) {
                $str_qry_hf = "SELECT hf_data_master.pk_id
                       FROM
                     hf_data_master
                     where
                     hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                     and hf_data_master.item_pack_size_id =  GetProductId('" . $product . "')
                     and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                $this->_em = Zend_Registry::get('doctrine');
                echo $str_qry_hf;
                exit;
                $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                $row_hf->execute();
                $result_hf = $row_hf->fetchAll();
                $hf_data_master_id = $result_hf[0]['pk_id'];
                if (!empty($hf_data_master_id)) {
                    $hf_data_master = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                } else {
                    $hf_data_master = new HfDataMaster();
                }
            } else {
                $hf_data_master = new HfDataMaster();
            }
            $item = $this->_em->getRepository('ItemPackSizes')->findByItemName($product);
            $hf_data_master->setItemPackSize($item);
            if (!empty($data['wh_id'])) {
                $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
                $hf_data_master->setWarehouse($warehouse);
            }
            $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
            $hf_data_master->setCreatedBy($user_id);
            $hf_data_master->setModifiedBy($user_id);
            $hf_data_master->setOpeningBalance($opening);
            $hf_data_master->setReceivedBalance($received);
            $hf_data_master->setIssueBalance(0);
            $hf_data_master->setClosingBalance($closing);
            //$hf_data_master->setVialsUsed($vials_used);
//            if ($item_category[$key] == 1 || $item_category[$key] == 2) {
//                $hf_data_master->setAdjustments($unusable_vials_array[$key]);
//            }
            $hf_data_master->setReportingStartDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['rpt_date'])));
            //$hf_data_master->setWastages($wastages);
            $hf_data_master->setCreatedDate(new \DateTime(App_Controller_Functions::dateToDbFormat(date("Y-m-d"))));
            $hf_data_master->setModifiedDate(new \DateTime(App_Controller_Functions::dateToDbFormat(date("Y-m-d"))));
            $hf_data_master->setStatus(0);
            $this->updateYearlyTargets($data['wh_id'], App_Controller_Functions::dateToDbFormat($data['rpt_date']), $children_live_birth, $surviving_children_0_11_M, $children_aged_12_23_M, $pregnant_women, $cba, $two_year_above, $total_population);
            $this->updateHfSessions($data['wh_id'], App_Controller_Functions::dateToDbFormat($data['rpt_date']), $fixed_planned_sessions, $fixed_actually_held_sessions, $outreach_planned_sessions, $outreach_actually_held_sessions);
            $this->_em->persist($hf_data_master);
            $this->_em->flush();
            $hf_data_master_id = $hf_data_master->getPkId();
            if ($item->getItemCategory() == 1) {

                $firstindexofvaccinator = array_keys($data['data'][$product]['Vaccinator (Fixed)']['consumption'])[0];
                $count = 1;
                foreach ($product['Vaccinator (Fixed)']['consumption'][$firstindexofvaccinator]['Male'] as $dose) {
                    if ($count == 1) {
                        $start_dose = $dose;
                    }
                    $no_of_doses[$dose] = $dose;
                    $count++;
                }

                $vaccinator_fixed_key = "Vaccinator (Fixed)";
                $vaccinator_outreach_key = "Vaccinator (Outreach)";
                $vaccinator_mobile_key = "Vaccinator (Mobile)";
                $vaccinator_lhw_key = "House Health (LHW)";

                $age_category1 = "0-11 Months";
                $age_category2 = "12-23 Months";
                $age_category3 = "2 Years and Above";


                for ($i = $start_dose; $i <= count($no_of_doses); $i++) {
                    $fix_inuc_m_11 = $data[$product][$vaccinator_fixed_key]['consumption'][$age_category1]['Male'][$i];
                    $fix_inuc_f_11 = $data[$product][$vaccinator_fixed_key]['consumption'][$age_category1]['Female'][$i];
                    $fix_outuc_m_11 = $data[$product][$vaccinator_mobile_key]['consumption'][$age_category1]['Male'][$i];
                    $fix_outuc_f_11 = $data[$product][$vaccinator_mobile_key]['consumption'][$age_category1]['Female'][$i];
                    $outreach_m_11 = $data[$product][$vaccinator_outreach_key]['consumption'][$age_category1]['Male'][$i];
                    $outreach_f_11 = $data[$product][$vaccinator_outreach_key]['consumption'][$age_category1]['Female'][$i];
                    $outreach_outuc_m_11 = $data[$product][$vaccinator_lhw_key]['consumption'][$age_category1]['Male'][$i];
                    $outreach_outuc_f_11 = $data[$product][$vaccinator_lhw_key]['consumption'][$age_category1]['Female'][$i];
                    $fix_inuc_m_23 = $data[$product][$vaccinator_fixed_key]['consumption'][$age_category2]['Male'][$i];
                    $fix_inuc_f_23 = $data[$product][$vaccinator_fixed_key]['consumption'][$age_category2]['Female'][$i];
                    $fix_outuc_m_23 = $data[$product][$vaccinator_mobile_key]['consumption'][$age_category2]['Male'][$i];
                    $fix_outuc_f_23 = $data[$product][$vaccinator_mobile_key]['consumption'][$age_category2]['Female'][$i];
                    $outreach_m_23 = $data[$product][$vaccinator_outreach_key]['consumption'][$age_category2]['Male'][$i];
                    $outreach_f_23 = $data[$product][$vaccinator_outreach_key]['consumption'][$age_category2]['Female'][$i];
                    $outreach_outuc_m_23 = $data[$product][$vaccinator_lhw_key]['consumption'][$age_category2]['Male'][$i];
                    $outreach_outuc_f_23 = $data[$product][$vaccinator_lhw_key]['consumption'][$age_category2]['Female'][$i];
                    $fix_inuc_m_24 = $data[$product][$vaccinator_fixed_key]['consumption'][$age_category3]['Male'][$i];
                    $fix_inuc_f_24 = $data[$product][$vaccinator_fixed_key]['consumption'][$age_category3]['Female'][$i];
                    $fix_outuc_m_24 = $data[$product][$vaccinator_mobile_key]['consumption'][$age_category3]['Male'][$i];
                    $fix_outuc_f_24 = $data[$product][$vaccinator_mobile_key]['consumption'][$age_category3]['Female'][$i];
                    $outreach_m_24 = $data[$product][$vaccinator_outreach_key]['consumption'][$age_category3]['Male'][$i];
                    $outreach_f_24 = $data[$product][$vaccinator_outreach_key]['consumption'][$age_category3]['Female'][$i];
                    $outreach_outuc_m_24 = $data[$product][$vaccinator_lhw_key]['consumption'][$age_category3]['Male'][$i];
                    $outreach_outuc_f_24 = $data[$product][$vaccinator_lhw_key]['consumption'][$age_category3]['Female'][$i];

                    if (!empty($hf_data_master_id)) {
                        $rows_edit = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id, 'ageGroup' => Model_ListDetail::AGE_0_11, 'vaccineScheduleId' => $i));
                        if (!empty($rows_edit)) {
                            $detail_id = $rows_edit[0]->getPkId();
                            $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                        } else {
                            $hf_data_detail = new HfDataDetail();
                        }
                    } else {
                        $hf_data_detail = new HfDataDetail();
                    }

                    $ageGroup = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_0_11);
                    $hf_data_detail->setAgeGroup($ageGroup);
                    $hf_data_detail->setFixedInsideUcFemale($fix_inuc_f_11);
                    $hf_data_detail->setFixedInsideUcMale($fix_inuc_m_11);
                    $hf_data_detail->setFixedOutsideUcFemale($fix_outuc_f_11);
                    $hf_data_detail->setFixedOutsideUcMale($fix_outuc_m_11);
                    $hf_data_detail->setOutreachFemale($outreach_f_11);
                    $hf_data_detail->setOutreachMale($outreach_m_11);
                    $hf_data_detail->setOutreachOutsideFemale($outreach_outuc_f_11);
                    $hf_data_detail->setOutreachOutsideMale($outreach_outuc_m_11);
                    $hf_data_detail->setVaccineScheduleId($vacine_schedule);
                    $hf_data_master_i = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                    $hf_data_detail->setHfDataMaster($hf_data_master_i);
                    $user = $this->_em->getRepository('Users')->find($this->_user_id);
                    $hf_data_detail->setModifiedBy($user);
                    $hf_data_detail->setCreatedBy($user);
                    $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                    $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($hf_data_detail);
                    $this->_em->flush();

                    if (!empty($hf_data_master_id)) {
                        $rows_edit = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id, 'ageGroup' => Model_ListDetail::AGE_12_23, 'vaccineScheduleId' => $i));
                        if (!empty($rows_edit)) {
                            $detail_id = $rows_edit[0]->getPkId();
                            $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                        } else {
                            $hf_data_detail = new HfDataDetail();
                        }
                    } else {
                        $hf_data_detail = new HfDataDetail();
                    }
                    
                    $ageGroup1 = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_12_23);
                    $hf_data_detail->setAgeGroup($ageGroup1);
                    $hf_data_detail->setFixedInsideUcFemale($fix_inuc_f_23);
                    $hf_data_detail->setFixedInsideUcMale($fix_inuc_m_23);
                    $hf_data_detail->setFixedOutsideUcFemale($fix_outuc_f_23);
                    $hf_data_detail->setFixedOutsideUcMale($fix_outuc_m_23);
                    $hf_data_detail->setOutreachFemale($outreach_f_23);
                    $hf_data_detail->setOutreachMale($outreach_m_23);
                    $hf_data_detail->setOutreachOutsideFemale($outreach_outuc_f_23);
                    $hf_data_detail->setOutreachOutsideMale($outreach_outuc_m_23);
                    $hf_data_detail->setVaccineScheduleId($vacine_schedule);
                    $hf_data_master_i1 = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                    $hf_data_detail->setHfDataMaster($hf_data_master_i1);
                    $user = $this->_em->getRepository('Users')->find($this->_user_id);
                    $hf_data_detail->setModifiedBy($user);
                    $hf_data_detail->setCreatedBy($user);
                    $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                    $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($hf_data_detail);
                    $this->_em->flush();

                    if (!empty($hf_data_master_id)) {
                        $rows_edit = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id, 'ageGroup' => Model_ListDetail::AGE_24, 'vaccineScheduleId' => $i));
                        if (!empty($rows_edit)) {
                            $detail_id = $rows_edit[0]->getPkId();
                            $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                        } else {
                            $hf_data_detail = new HfDataDetail();
                        }
                    } else {
                        $hf_data_detail = new HfDataDetail();
                    }
                    
                    $ageGroup2 = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_24);
                    $hf_data_detail->setAgeGroup($ageGroup2);
                    $hf_data_detail->setFixedInsideUcFemale($fix_inuc_f_24);
                    $hf_data_detail->setFixedInsideUcMale($fix_inuc_m_24);
                    $hf_data_detail->setFixedOutsideUcFemale($fix_outuc_f_24);
                    $hf_data_detail->setFixedOutsideUcMale($fix_outuc_m_24);
                    $hf_data_detail->setOutreachFemale($outreach_f_24);
                    $hf_data_detail->setOutreachMale($outreach_m_24);
                    $hf_data_detail->setOutreachOutsideFemale($outreach_outuc_f_24);
                    $hf_data_detail->setOutreachOutsideMale($outreach_outuc_m_24);
                    $hf_data_detail->setVaccineScheduleId($vacine_schedule);
                    $hf_data_master_i2 = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                    $hf_data_detail->setHfDataMaster($hf_data_master_i2);
                    $user = $this->_em->getRepository('Users')->find($this->_user_id);
                    $hf_data_detail->setModifiedBy($user);
                    $hf_data_detail->setCreatedBy($user);
                    $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                    $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($hf_data_detail);
                    $this->_em->flush();
                }
            }
            if ($item->getItemCategory() == 2) {
                if ($data['is_new_report'] == 0) {
                    $str_qry_hf = "SELECT hf_data_master.pk_id
                            FROM
                            hf_data_master
                            where
                            hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                            and hf_data_master.item_pack_size_id =  '" . $posted_array[$key] . "'
                            and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                    $this->_em = Zend_Registry::get('doctrine');
                    $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                    $row_hf->execute();
                    $result_hf_edit = $row_hf->fetchAll();
                    $hf_data_master_id_edit_pregnant = $result_hf_edit[0]['pk_id'];
                }
                foreach ($pregenant_women as $key => $val) {
                    if (!empty($hf_data_master_id_edit_pregnant)) {
                        $rows = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id_edit_pregnant, 'vaccineScheduleId' => $serial_id[$key]));
                        if (!empty($rows)) {
                            $detail_id = $rows[0]->getPkId();
                            $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                        } else {
                            $hf_data_detail = new HfDataDetail();
                        }
                    } else {
                        $hf_data_detail = new HfDataDetail();
                    }
                    $hf_data_detail->setPregnantWomen($val);
                    $hf_data_detail->setNonPregnantWomen($non_pregenant_women[$key]);

                    $hf_data_detail->setOutsidePregnantWomen($outside_pregenant_women[$key]);
                    $hf_data_detail->setOutsideNonPregnantWomen($outside_non_pregenant_women[$key]);
                    $hf_data_detail->setVaccineScheduleId($serial_id[$key]);
                    $hf_data_master_i = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                    $hf_data_detail->setHfDataMaster($hf_data_master_i);
                    $user = $this->_em->getRepository('Users')->find($this->_user_id);
                    $hf_data_detail->setModifiedBy($user);
                    $hf_data_detail->setCreatedBy($user);
                    $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                    $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($hf_data_detail);
                    $this->_em->flush();
                }
            }
            // Deleted data from Draft
            $str_qry_draft_detail = "DELETE hf_data_detail_draft.*
                        FROM
                       hf_data_master_draft,hf_data_detail_draft
                       where
                       hf_data_master_draft.pk_id = hf_data_detail_draft.hf_data_master_id
                       and hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                       and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
            $this->_em = Zend_Registry::get('doctrine');
            $row_draft_detail = $this->_em->getConnection()->prepare($str_qry_draft_detail);
            $row_draft_detail->execute();
            $str_qry_draft_master = "DELETE hf_data_master_draft.*
                        FROM
                       hf_data_master_draft
                       where
                       hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
            $this->_em = Zend_Registry::get('doctrine');
            $row_draft_master = $this->_em->getConnection()->prepare($str_qry_draft_master);
            $row_draft_master->execute();
            // end
            // If previous month is edited then it should carried to the last data entry month
            /* ----------------- Start ----------------- */
            $st_date = $data['rpt_date'] . '-01';
            $start_date = date('Y-m-d', strtotime("+1 month", strtotime($st_date)));
            $de_qry = "SELECT
                            hf_data_master.reporting_start_date
                    FROM
                            hf_data_master
                    WHERE
                            hf_data_master.warehouse_id = '" . $data['wh_id'] . "'
                    AND hf_data_master.item_pack_size_id = $itemid
                    ORDER BY
                            hf_data_master.reporting_start_date DESC
                    LIMIT 1";
            $this->_em = Zend_Registry::get('doctrine');
            $row_1 = $this->_em->getConnection()->prepare($de_qry);
            $row_1->execute();
            $result_de_qry = $row_1->fetchAll();
            if (!empty($result_de_qry['0']['reporting_start_date'])) {
                $endDate = date('Y-m-d', strtotime($result_de_qry['0']['reporting_start_date']));
                $endDate = date('Y-m-d', strtotime("+1 month", strtotime($endDate)));
                $begin = new DateTime($start_date);
                $end = new DateTime($endDate);
                $interval = DateInterval::createFromDateString('1 month');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $date) {
                    $date = $date->format("Y-m-d");
                    $this->_em = Zend_Registry::get('doctrine');
                    $row_12 = $this->_em->getConnection()->prepare("SELECT REPUpdateCarryForward('" . $date . "'," . $itemid . "," . $data['wh_id'] . ")  from DUAL");
                    $row_12->execute();
                }
            }
            /* ----------------- End ----------------- */
        }

        $ccm_status_history = new Model_CcmStatusHistory();
        $ccm_status_history->form_values = $data;
        $ccm_status_history->updateColdChainStatus();
        // Track history
        $ip = $_SERVER['REMOTE_ADDR'];
        $warehouse_history = new WarehousesUpdateHistory();
        $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
        $warehouse_history->setWarehouse($warehouse);
        $warehouse_history->setReportDate(App_Controller_Functions::dateToDbFormat($data['rpt_date']));
        $warehouse_history->setModifiedDate(new \DateTime(date("Y-m-d h:i:s")));
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $warehouse_history->setCreatedBy($user);
        $warehouse_history->setModifiedBy($user);
        $warehouse_history->setIpAddress($ip);
        $this->_em->persist($warehouse_history);
        $this->_em->flush();
        return true;
    }

    /**
     * Add Monthly Consumption 2
     * @return boolean
     */
    public function addMonthlyConsumption2() {
        $data = $this->form_values;
        $date = date("Y-m-d h:i:s");
        $posted_array = $data['flitm_id'];
        $serial_id = $data['serial_id'];
        $opening_array = $data['opening_balance'];
        $received_array = $data['received'];
        $dispensed_array = $data['dispensed'];
        $pregenant_women = $data['pregenant_women'];
        $outside_pregenant_women = $data['outside_pregenant_women'];
        $non_pregenant_women = $data['non_pregenant_women'];
        $outside_non_pregenant_women = $data['outside_non_pregenant_women'];
        $pregnant_women = $data['pregnant_women1'];
        $pregenant_women_total = $data['pregenant_women_total'];
        $outside_pregenant_women_total = $data['outside_pregenant_women_total'];
        $non_pregenant_women_total = $data['non_pregenant_women_total'];
        $outside_non_pregenant_women_total = $data['outside_non_pregenant_women_total'];
        $item_category = $data['item_category'];
        $unusable_vials_array = $data['unusable_vials'];
        $closing_balance_array = $data['closing_balance'];
        // vaccine schedule
        $strat_no = $data['start_no'];
        $no_of_doses = $data['no_of_doses'];
        $children_live_birth = $data['children_live_birth'];
        $surviving_children_0_11_M = $data['surviving_children_0_11_M'];
        $children_aged_12_23_M = $data['children_aged_12_23_M'];
        $two_year_above = $data['2_year_above'];
        $total_population = $data['total_population'];
        $cba = $data['cba'];
        $fixed_planned_sessions = $data['fixed_planned_sessions'];
        $fixed_actually_held_sessions = $data['fixed_actually_held_sessions'];
        $outreach_planned_sessions = $data['outreach_planned_sessions'];
        $outreach_actually_held_sessions = $data['outreach_actually_held_sessions'];
        foreach ($closing_balance_array as $key => $val) {
            if ($val != '' && $val >= 0) {
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] ) - $unusable_vials_array[$key];
                } else {
                    $vials_used = ($opening_array[$key] + $received_array[$key] - $closing_balance_array[$key] );
                }
                if ($item_category[$key] == 1) {
                    $nod = $no_of_doses[$key];
                    $issue = 0;
                    for ($i = $strat_no[$key]; $i <= $no_of_doses[$key]; $i++) {
                        if ($i == 0) {
                            $nod += 1;
                        }
                        $vacine_schedule = ($i == 1 && $i == $nod) ? '' : $i;
                        if (strlen($vacine_schedule) == 0) {
                            $vacine_schedule = 1;
                        }
                        $fix_inuc_m_11 = $data['fix_inuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_11 = $data['fix_inuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_11 = $data['fix_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_11 = $data['fix_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_11 = $data['outreach_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_11 = $data['outreach_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_11 = $data['outreach_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_11 = $data['outreach_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_11 = $data['mobile_inside_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_11 = $data['mobile_inside_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_11 = $data['lhw_inside_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_11 = $data['lhw_inside_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $fix_inuc_m_23 = $data['fix_inuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_23 = $data['fix_inuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_23 = $data['fix_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_23 = $data['fix_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_23 = $data['outreach_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_23 = $data['outreach_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_23 = $data['outreach_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_23 = $data['outreach_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_23 = $data['mobile_inside_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_23 = $data['mobile_inside_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_23 = $data['lhw_inside_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_23 = $data['lhw_inside_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $fix_inuc_m_24 = $data['fix_inuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_24 = $data['fix_inuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_24 = $data['fix_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_24 = $data['fix_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_24 = $data['outreach_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_24 = $data['outreach_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_24 = $data['outreach_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_24 = $data['outreach_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_24 = $data['mobile_inside_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_24 = $data['mobile_inside_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_24 = $data['lhw_inside_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_24 = $data['lhw_inside_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $issue += $fix_inuc_m_11 + $fix_inuc_f_11 + $fix_outuc_m_11 + $fix_outuc_f_11 + $outreach_m_11 + $outreach_f_11 + $outreach_outuc_m_11 + $outreach_outuc_f_11 + $mobile_inside_m_11 + $mobile_inside_f_11 + $lhw_inside_m_11 + $lhw_inside_f_11 + $fix_inuc_m_23 + $fix_inuc_f_23 + $fix_outuc_m_23 + $fix_outuc_f_23 + $outreach_m_23 + $outreach_f_23 + $outreach_outuc_m_23 + $outreach_outuc_f_23 + $mobile_inside_m_23 + $mobile_inside_f_23 + $lhw_inside_m_23 + $lhw_inside_f_23 + $fix_inuc_m_24 + $fix_inuc_f_24 + $fix_outuc_m_24 + $fix_outuc_f_24 + $outreach_m_24 + $outreach_f_24 + $outreach_outuc_m_24 + $outreach_outuc_f_24 + $mobile_inside_m_24 + $mobile_inside_f_24 + $lhw_inside_m_24 + $lhw_inside_f_24;
                    }
                } else if ($item_category[$key] == 2) {
                    $issue = $pregenant_women_total + $outside_pregenant_women_total + $non_pregenant_women_total + $outside_non_pregenant_women_total;
                } else if ($item_category[$key] == 3) {

                    $issue = $dispensed_array[$key];
                }
                $itemid = $posted_array[$key];
                $wh_adj = $unusable_vials_array[$key];
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $wastages = ($vials_used - $issue) + abs($wh_adj);
                } else {
                    $wastages = ($vials_used - $issue);
                }
                if ($data['is_new_report'] == 0) {
                    $str_qry_hf = "SELECT hf_data_master.pk_id
                       FROM
                     hf_data_master
                     where
                     hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                     and hf_data_master.item_pack_size_id =  '" . $posted_array[$key] . "'
                     and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                    $this->_em = Zend_Registry::get('doctrine');
                    $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                    $row_hf->execute();
                    $result_hf = $row_hf->fetchAll();
                    $hf_data_master_id = $result_hf[0]['pk_id'];
                    if (!empty($hf_data_master_id)) {
                        $hf_data_master = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                    } else {
                        $hf_data_master = new HfDataMaster();
                    }
                } else {
                    $hf_data_master = new HfDataMaster();
                }
                $item = $this->_em->getRepository('ItemPackSizes')->find($posted_array[$key]);
                $hf_data_master->setItemPackSize($item);
                if (!empty($data['wh_id'])) {
                    $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
                    $hf_data_master->setWarehouse($warehouse);
                }
                $user_id = $this->_em->getRepository('Users')->find($this->_user_id);
                $hf_data_master->setCreatedBy($user_id);
                $hf_data_master->setModifiedBy($user_id);
                $hf_data_master->setOpeningBalance($opening_array[$key]);
                $hf_data_master->setReceivedBalance($received_array[$key]);
                $hf_data_master->setIssueBalance($issue);
                $hf_data_master->setClosingBalance($val);
                $hf_data_master->setVialsUsed($vials_used);
                if ($item_category[$key] == 1 || $item_category[$key] == 2) {
                    $hf_data_master->setAdjustments($unusable_vials_array[$key]);
                }
                $hf_data_master->setReportingStartDate(new \DateTime(App_Controller_Functions::dateToDbFormat($data['rpt_date'])));
                $hf_data_master->setWastages($wastages);
                $hf_data_master->setCreatedDate(new \DateTime(App_Controller_Functions::dateToDbFormat($date)));
                $hf_data_master->setModifiedDate(new \DateTime(App_Controller_Functions::dateToDbFormat(date("Y-m-d"))));
                $hf_data_master->setStatus(0);
                $this->updateYearlyTargets($data['wh_id'], App_Controller_Functions::dateToDbFormat($data['rpt_date']), $children_live_birth, $surviving_children_0_11_M, $children_aged_12_23_M, $pregnant_women, $cba, $two_year_above, $total_population);
                $this->updateHfSessions($data['wh_id'], App_Controller_Functions::dateToDbFormat($data['rpt_date']), $fixed_planned_sessions, $fixed_actually_held_sessions, $outreach_planned_sessions, $outreach_actually_held_sessions);
                $this->_em->persist($hf_data_master);
                $this->_em->flush();
                $hf_data_master_id = $hf_data_master->getPkId();
                if ($item_category[$key] == 1) {
                    $nod = $no_of_doses[$key];
                    for ($i = $strat_no[$key]; $i <= $no_of_doses[$key]; $i++) {
                        if ($i == 0) {
                            $nod += 1;
                        }
                        $vacine_schedule = ($i == 1 && $i == $nod) ? '' : $i;
                        if (strlen($vacine_schedule) == 0) {
                            $vacine_schedule = 1;
                        }
                        $fix_inuc_m_11 = $data['fix_inuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_11 = $data['fix_inuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_11 = $data['fix_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_11 = $data['fix_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_11 = $data['outreach_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_11 = $data['outreach_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_11 = $data['outreach_outuc_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_11 = $data['outreach_outuc_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_11 = $data['mobile_inside_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_11 = $data['mobile_inside_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_11 = $data['lhw_inside_m_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_11 = $data['lhw_inside_f_11_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $fix_inuc_m_23 = $data['fix_inuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_23 = $data['fix_inuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_23 = $data['fix_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_23 = $data['fix_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_23 = $data['outreach_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_23 = $data['outreach_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_23 = $data['outreach_outuc_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_23 = $data['outreach_outuc_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_23 = $data['mobile_inside_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_23 = $data['mobile_inside_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_23 = $data['lhw_inside_m_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_23 = $data['lhw_inside_f_23_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        $fix_inuc_m_24 = $data['fix_inuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_inuc_f_24 = $data['fix_inuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_m_24 = $data['fix_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $fix_outuc_f_24 = $data['fix_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_m_24 = $data['outreach_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_f_24 = $data['outreach_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_m_24 = $data['outreach_outuc_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $outreach_outuc_f_24 = $data['outreach_outuc_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_m_24 = $data['mobile_inside_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $mobile_inside_f_24 = $data['mobile_inside_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_m_24 = $data['lhw_inside_m_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        $lhw_inside_f_24 = $data['lhw_inside_f_24_' . $posted_array[$key] . '_' . $vacine_schedule];
                        
                        if ($data['is_new_report'] == 0) {
                            $str_qry_hf = "SELECT hf_data_master.pk_id
                            FROM
                            hf_data_master
                            where
                            hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                            and hf_data_master.item_pack_size_id =  '" . $posted_array[$key] . "'
                            and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                            $this->_em = Zend_Registry::get('doctrine');
                            $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                            $row_hf->execute();
                            $result_hf_edit = $row_hf->fetchAll();
                            $hf_data_master_id_edit = $result_hf_edit[0]['pk_id'];
                            if (!empty($hf_data_master_id_edit)) {
                                $rows_edit = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id_edit, 'ageGroup' => Model_ListDetail::AGE_0_11, 'vaccineScheduleId' => $vacine_schedule));
                                if (!empty($rows_edit)) {
                                    $detail_id = $rows_edit[0]->getPkId();
                                    $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                                } else {
                                    $hf_data_detail = new HfDataDetail();
                                }
                            } else {
                                $hf_data_detail = new HfDataDetail();
                            }
                        } else {
                            $hf_data_detail = new HfDataDetail();
                        }
                        $ageGroup = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_0_11);
                        $hf_data_detail->setAgeGroup($ageGroup);
                        $hf_data_detail->setFixedInsideUcFemale($fix_inuc_f_11);
                        $hf_data_detail->setFixedInsideUcMale($fix_inuc_m_11);
                        $hf_data_detail->setFixedOutsideUcFemale($fix_outuc_f_11);
                        $hf_data_detail->setFixedOutsideUcMale($fix_outuc_m_11);
                        $hf_data_detail->setOutreachFemale($outreach_f_11);
                        $hf_data_detail->setOutreachMale($outreach_m_11);
                        $hf_data_detail->setOutreachOutsideFemale($outreach_outuc_f_11);
                        $hf_data_detail->setOutreachOutsideMale($outreach_outuc_m_11);
                        $hf_data_detail->setVaccineScheduleId($vacine_schedule);
                        $hf_data_detail->setMobileInsideMale($mobile_inside_m_11);
                        $hf_data_detail->setMobileInsideFemale($mobile_inside_f_11);
                        $hf_data_detail->setLhwInsideMale($lhw_inside_m_11);
                        $hf_data_detail->setLhwInsideFemale($lhw_inside_f_11);
                        $hf_data_master_i = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                        $hf_data_detail->setHfDataMaster($hf_data_master_i);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail->setModifiedBy($user);
                        $hf_data_detail->setCreatedBy($user);
                        $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail);
                        $this->_em->flush();
                        if ($data['is_new_report'] == 0) {
                            $str_qry_hf = "SELECT hf_data_master.pk_id
                            FROM
                            hf_data_master
                            where
                            hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                            and hf_data_master.item_pack_size_id =  '" . $posted_array[$key] . "'
                            and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                            $this->_em = Zend_Registry::get('doctrine');
                            $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                            $row_hf->execute();
                            $result_hf_edit = $row_hf->fetchAll();
                            $hf_data_master_id_edit = $result_hf_edit[0]['pk_id'];
                            if (!empty($hf_data_master_id_edit)) {
                                $rows = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id_edit, 'ageGroup' => Model_ListDetail::AGE_12_23, 'vaccineScheduleId' => $vacine_schedule));
                                if (!empty($rows)) {
                                    $detail_id = $rows[0]->getPkId();
                                    $hf_data_detail1 = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                                } else {
                                    $hf_data_detail1 = new HfDataDetail();
                                }
                            } else {
                                $hf_data_detail1 = new HfDataDetail();
                            }
                        } else {
                            $hf_data_detail1 = new HfDataDetail();
                        }
                        $ageGroup1 = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_12_23);
                        $hf_data_detail1->setAgeGroup($ageGroup1);
                        $hf_data_detail1->setFixedInsideUcFemale($fix_inuc_f_23);
                        $hf_data_detail1->setFixedInsideUcMale($fix_inuc_m_23);
                        $hf_data_detail1->setFixedOutsideUcFemale($fix_outuc_f_23);
                        $hf_data_detail1->setFixedOutsideUcMale($fix_outuc_m_23);
                        $hf_data_detail1->setOutreachFemale($outreach_f_23);
                        $hf_data_detail1->setOutreachMale($outreach_m_23);
                        $hf_data_detail1->setOutreachOutsideFemale($outreach_outuc_f_23);
                        $hf_data_detail1->setOutreachOutsideMale($outreach_outuc_m_23);
                        $hf_data_detail1->setMobileInsideMale($mobile_inside_m_23);
                        $hf_data_detail1->setMobileInsideFemale($mobile_inside_f_23);
                        $hf_data_detail1->setLhwInsideMale($lhw_inside_m_23);
                        $hf_data_detail1->setLhwInsideFemale($lhw_inside_f_23);
                        $hf_data_detail1->setVaccineScheduleId($vacine_schedule);
                        $hf_data_master_i1 = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                        $hf_data_detail1->setHfDataMaster($hf_data_master_i1);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail1->setModifiedBy($user);
                        $hf_data_detail1->setCreatedBy($user);
                        $hf_data_detail1->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail1->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail1);
                        $this->_em->flush();
                        if ($data['is_new_report'] == 0) {
                            $str_qry_hf = "SELECT hf_data_master.pk_id
                            FROM
                            hf_data_master
                            where
                            hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                            and hf_data_master.item_pack_size_id =  '" . $posted_array[$key] . "'
                            and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                            $this->_em = Zend_Registry::get('doctrine');
                            $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                            $row_hf->execute();
                            $result_hf_edit = $row_hf->fetchAll();
                            $hf_data_master_id_edit = $result_hf_edit[0]['pk_id'];
                            if (!empty($hf_data_master_id_edit)) {
                                $rows = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id_edit, 'ageGroup' => Model_ListDetail::AGE_24, 'vaccineScheduleId' => $vacine_schedule));
                                if (!empty($rows)) {
                                    $detail_id = $rows[0]->getPkId();
                                    $hf_data_detail2 = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                                } else {
                                    $hf_data_detail2 = new HfDataDetail();
                                }
                            } else {
                                $hf_data_detail2 = new HfDataDetail();
                            }
                        } else {
                            $hf_data_detail2 = new HfDataDetail();
                        }
                        $ageGroup2 = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::AGE_24);
                        $hf_data_detail2->setAgeGroup($ageGroup2);
                        $hf_data_detail2->setFixedInsideUcFemale($fix_inuc_f_24);
                        $hf_data_detail2->setFixedInsideUcMale($fix_inuc_m_24);
                        $hf_data_detail2->setFixedOutsideUcFemale($fix_outuc_f_24);
                        $hf_data_detail2->setFixedOutsideUcMale($fix_outuc_m_24);
                        $hf_data_detail2->setOutreachFemale($outreach_f_24);
                        $hf_data_detail2->setOutreachMale($outreach_m_24);
                        $hf_data_detail2->setOutreachOutsideFemale($outreach_outuc_f_24);
                        $hf_data_detail2->setOutreachOutsideMale($outreach_outuc_m_24);
                        $hf_data_detail2->setMobileInsideMale($mobile_inside_m_24);
                        $hf_data_detail2->setMobileInsideFemale($mobile_inside_f_24);
                        $hf_data_detail2->setLhwInsideMale($lhw_inside_m_24);
                        $hf_data_detail2->setLhwInsideFemale($lhw_inside_f_24);
                        $hf_data_detail2->setVaccineScheduleId($vacine_schedule);
                        $hf_data_master_i2 = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                        $hf_data_detail2->setHfDataMaster($hf_data_master_i2);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail2->setModifiedBy($user);
                        $hf_data_detail2->setCreatedBy($user);
                        $hf_data_detail2->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail2->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail2);
                        $this->_em->flush();
                    }
                }
                if ($item_category[$key] == 2) {
                    if ($data['is_new_report'] == 0) {
                        $str_qry_hf = "SELECT hf_data_master.pk_id
                            FROM
                            hf_data_master
                            where
                            hf_data_master.warehouse_id='" . $data['wh_id'] . "'
                            and hf_data_master.item_pack_size_id =  '" . $posted_array[$key] . "'
                            and DATE_FORMAT(hf_data_master.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "' ";
                        $this->_em = Zend_Registry::get('doctrine');
                        $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
                        $row_hf->execute();
                        $result_hf_edit = $row_hf->fetchAll();
                        $hf_data_master_id_edit_pregnant = $result_hf_edit[0]['pk_id'];
                    }
                    foreach ($pregenant_women as $key => $val) {
                        if (!empty($hf_data_master_id_edit_pregnant)) {
                            $rows = $this->_em->getRepository('HfDataDetail')->findBy(array('hfDataMaster' => $hf_data_master_id_edit_pregnant, 'vaccineScheduleId' => $serial_id[$key]));
                            if (!empty($rows)) {
                                $detail_id = $rows[0]->getPkId();
                                $hf_data_detail = $this->_em->getRepository('HfDataDetail')->find($detail_id);
                            } else {
                                $hf_data_detail = new HfDataDetail();
                            }
                        } else {
                            $hf_data_detail = new HfDataDetail();
                        }
                        $hf_data_detail->setPregnantWomen($val);
                        $hf_data_detail->setNonPregnantWomen($non_pregenant_women[$key]);

                        $hf_data_detail->setOutsidePregnantWomen($outside_pregenant_women[$key]);
                        $hf_data_detail->setOutsideNonPregnantWomen($outside_non_pregenant_women[$key]);
                        $hf_data_detail->setVaccineScheduleId($serial_id[$key]);
                        $hf_data_master_i = $this->_em->getRepository('HfDataMaster')->find($hf_data_master_id);
                        $hf_data_detail->setHfDataMaster($hf_data_master_i);
                        $user = $this->_em->getRepository('Users')->find($this->_user_id);
                        $hf_data_detail->setModifiedBy($user);
                        $hf_data_detail->setCreatedBy($user);
                        $hf_data_detail->setCreatedDate(App_Tools_Time::now());
                        $hf_data_detail->setModifiedDate(App_Tools_Time::now());
                        $this->_em->persist($hf_data_detail);
                        $this->_em->flush();
                    }
                }
                // Deleted data from Draft
                $str_qry_draft_detail = "DELETE hf_data_detail_draft.*
                        FROM
                       hf_data_master_draft,hf_data_detail_draft
                       where
                       hf_data_master_draft.pk_id = hf_data_detail_draft.hf_data_master_id
                       and hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                       and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
                $this->_em = Zend_Registry::get('doctrine');
                $row_draft_detail = $this->_em->getConnection()->prepare($str_qry_draft_detail);
                $row_draft_detail->execute();
                $str_qry_draft_master = "DELETE hf_data_master_draft.*
                        FROM
                       hf_data_master_draft
                       where
                       hf_data_master_draft.warehouse_id='" . $data['wh_id'] . "'
                      and DATE_FORMAT(hf_data_master_draft.reporting_start_date, '%Y-%m-%d') = '" . $data['rpt_date'] . "'";
                $this->_em = Zend_Registry::get('doctrine');
                $row_draft_master = $this->_em->getConnection()->prepare($str_qry_draft_master);
                $row_draft_master->execute();
                // end
                // If previous month is edited then it should carried to the last data entry month
                /* ----------------- Start ----------------- */
                $st_date = $data['rpt_date'] . '-01';
                $start_date = date('Y-m-d', strtotime("+1 month", strtotime($st_date)));
                $de_qry = "SELECT
                            hf_data_master.reporting_start_date
                    FROM
                            hf_data_master
                    WHERE
                            hf_data_master.warehouse_id = '" . $data['wh_id'] . "'
                    AND hf_data_master.item_pack_size_id = $itemid
                    ORDER BY
                            hf_data_master.reporting_start_date DESC
                    LIMIT 1";
                $this->_em = Zend_Registry::get('doctrine');
                $row_1 = $this->_em->getConnection()->prepare($de_qry);
                $row_1->execute();
                $result_de_qry = $row_1->fetchAll();
                if (!empty($result_de_qry['0']['reporting_start_date'])) {
                    $endDate = date('Y-m-d', strtotime($result_de_qry['0']['reporting_start_date']));
                    $endDate = date('Y-m-d', strtotime("+1 month", strtotime($endDate)));
                    $begin = new DateTime($start_date);
                    $end = new DateTime($endDate);
                    $interval = DateInterval::createFromDateString('1 month');
                    $period = new DatePeriod($begin, $interval, $end);
                    foreach ($period as $date) {
                        $date = $date->format("Y-m-d");
                        $this->_em = Zend_Registry::get('doctrine');
                        $row_12 = $this->_em->getConnection()->prepare("SELECT REPUpdateCarryForward('" . $date . "'," . $itemid . "," . $data['wh_id'] . ")  from DUAL");
                        $row_12->execute();
                    }
                }
                /* ----------------- End ----------------- */
            }
        }
        $ccm_status_history = new Model_CcmStatusHistory();
        $ccm_status_history->form_values = $data;
        $ccm_status_history->updateColdChainStatus();
        // Track history
        $ip = $_SERVER['REMOTE_ADDR'];
        $warehouse_history = new WarehousesUpdateHistory();
        $warehouse = $this->_em->getRepository('Warehouses')->find($data['wh_id']);
        $warehouse_history->setWarehouse($warehouse);
        $warehouse_history->setReportDate(App_Controller_Functions::dateToDbFormat($data['rpt_date']));
        $warehouse_history->setModifiedDate(new \DateTime(date("Y-m-d h:i:s")));
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $warehouse_history->setCreatedBy($user);
        $warehouse_history->setModifiedBy($user);
        $warehouse_history->setIpAddress($ip);
        $this->_em->persist($warehouse_history);
        $this->_em->flush();
        return true;
    }

    /**
     * Get Explorer Report
     * @return type
     */
    public function getExplorerReport() {
        $warehouse_data = new Model_HfDataMaster();
        $warehouses = new Model_Warehouses();
        $reports = new Model_Reports();
        $temp = $this->temp;
        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));
        $temp = explode("|", $temp);
        $wh_id = $temp[0];
        $loc_id = $temp[1];
        $report_date = $temp[2];
        $is_new_report = $temp[3];
        $tt = explode("-", $report_date);
        $yy = $tt[0];
        $mm = $tt[1];
        $dd = $tt[2];
        if ($mm == '1') {
            $month = "Jan";
        } else if ($mm == '2') {
            $month = "Feb";
        } else if ($mm == '3') {
            $month = "Mar";
        } else if ($mm == '4') {
            $month = "Apr";
        } else if ($mm == '5') {
            $month = "May";
        } else if ($mm == '6') {
            $month = "Jun";
        } else if ($mm == '7') {
            $month = "Jul";
        } else if ($mm == '8') {
            $month = "Aug";
        } else if ($mm == '9') {
            $month = "Sep";
        } else if ($mm == '10') {
            $month = "Oct";
        } else if ($mm == '11') {
            $month = "Nov";
        } else if ($mm == '12') {
            $month = "Dec";
        }
        $warehouses->form_values['pk_id'] = $wh_id;
        $stakeholder_id = $warehouses->getStakeholderIdByWarehouseId();
        if ($is_new_report == 1) {
            $prev_month_date = $reports->getPreviousMonthReportDate($report_date);
        } else {
            $prev_month_date = $report_date;
        }
        $warehouses->form_values['location_id'] = $loc_id;
        $warehouse_data->form_values['warehouse_id'] = $wh_id;
        $reports = $warehouse_data->getMonthYearByWarehouseId();
        $warehouse_level = $warehouses->getWarehouseLevelById();
        return array(
            'wh_id' => $wh_id,
            'loc_id' => $loc_id,
            'rpt_date' => $report_date,
            'is_new_rpt' => $is_new_report,
            'yy' => $yy,
            'mm' => $mm,
            'dd' => $dd,
            'prev_month_date' => $prev_month_date,
            'stakeholder_id' => $stakeholder_id,
            'month' => $month,
            'warehouse_level' => $warehouse_level
        );
    }

    /**
     * Get Max Year
     * @return type
     */
    public function getMaxYear() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MAX(YEAR(wd.reportingStartDate)) as report_year')
                ->from("HfDataMaster", "wd");
        $rows = $str_sql->getQuery()->getResult();
        $report_year = $rows['0']['report_year'];
        if (empty($report_year)) {

            return date("m");
        }
        return $report_year;
    }

    /**
     * Get Max Month
     * @param type $year
     * @return type
     */
    public function getMaxMonth($year) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('MAX(MONTH(wd.reportingStartDate)) as report_month')
                ->from("HfDataMaster", "wd")
                ->where("YEAR(wd.reportingStartDate)=$year")
                ->andWhere("wd.openingBalance <> 0")
                ->andWhere("wd.closingBalance <> 0");
        $rows = $str_sql->getQuery()->getResult();
        $report_month = $rows['0']['report_month'];
        if (empty($report_month)) {
            return date("m");
        }
        return $report_month;
    }

    /**
     * Get Stock Analysis District Wise Report
     * @return string
     */
    public function getStockAnalysisDistrictWiseReport() {
        $yy = $this->form_values['year'];
        $mm = $this->form_values['month'];
        $date = $yy . '-' . $mm;
        $province_id = $this->_identity->getProvinceId();
        $str_sql = "SELECT
                item_pack_sizes.item_name,
                locations.location_name,
                Sum(IF(DATE_FORMAT(stock_master.transaction_date,'%Y-%m') < '$date',
                    stock_detail.quantity,0)) AS OB,
                Sum(IF(DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date'
                                        AND stock_master.transaction_type_id = 1,
                                        stock_detail.quantity,
                                        0
                                )) AS Rcv,
                Sum(IF(DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date'
                                        AND stock_master.transaction_type_id = 2,
                                        ABS(stock_detail.quantity),
                                        0
                                )) AS Issue,
                Sum(IF(DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date'
                                        AND stock_master.transaction_type_id > 2
                                        AND transaction_types.nature = '+',
                                        stock_detail.quantity,
                                        0
                                )) AS AdjPos,
                ABS(
                                Sum(IF(DATE_FORMAT(stock_master.transaction_date,'%Y-%m') = '$date'
                                                AND stock_master.transaction_type_id > 2
                                                AND transaction_types.nature = '-',
                                                stock_detail.quantity,
                                                0
                                        )
                                )
                        ) AS AdjNeg,
                Sum(stock_detail.quantity) AS CB,
                REPgetConsumption($mm,$yy,item_pack_sizes.pk_id,1,'D',locations.pk_id) as consumption,
                REPgetWastage('D','$date-01','$date-31',1,item_pack_sizes.pk_id,locations.pk_id,1) as wastage_rate
                FROM
                stock_batch
                INNER JOIN stock_batch_warehouses ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                INNER JOIN stock_detail ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
                INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
                INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
                INNER JOIN locations ON warehouses.district_id = locations.pk_id
                WHERE
                DATE_FORMAT(
                                stock_master.transaction_date,
                                '%Y-%m'
                        ) <= '$date' AND
                (stock_master.from_warehouse_id = warehouses.pk_id OR
                stock_master.to_warehouse_id = warehouses.pk_id) AND
                locations.province_id = $province_id
                GROUP BY
                        locations.location_name,
                        item_pack_sizes.pk_id
                ORDER BY
                        locations.location_name, item_pack_sizes.list_rank ASC";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $result = $row->fetchAll();
        $xml_store = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xml_store .= "<rows>";
        if (!empty($result) && count($result) > 0) {
            foreach ($result as $row) {
                $xml_store .= "<row>";
                $xml_store .= "<cell>" . $row['location_name'] . "</cell>";
                $xml_store .= "<cell>" . $row['item_name'] . "</cell>";
                $xml_store .= "<cell>" . number_format($row['OB']) . "</cell>";
                $xml_store .= "<cell>" . number_format($row['Rcv']) . "</cell>";
                $xml_store .= "<cell>" . number_format($row['Issue']) . "</cell>";
                $xml_store .= "<cell>" . number_format($row['CB']) . "</cell>";
                $xml_store .= "<cell>" . number_format($row['consumption']) . "</cell>";
                $xml_store .= "<cell>" . $row['wastage_rate'] . "</cell>";
                $xml_store .= "</row>";
            }
        }
        return $xml_store .= "</rows>";
    }

    /**
     * Add Log
     * @return boolean
     */
    public function addLog() {
        $data = $this->form_values;
        $temp = $this->form_values['temp'];
        $temp = base64_decode(substr($temp, 1, strlen($temp) - 1));
        $temp = explode("|", $temp);
        $warehouse_id = $temp[0];
        $rpt_date = $temp[1];
        $is_new_rpt = $temp[2];
        $tt = explode("-", $rpt_date);
        $yy = $tt[0];
        $mm = $tt[1];
        $year_month = $yy . "-" . $mm;
        if ($is_new_rpt == 0) {
            $str_qry = "DELETE log_book.*,log_book_item_doses.*
                        FROM
                       log_book_item_doses,log_book
                       where
                       log_book.pk_id = log_book_item_doses.log_book_id
                        and log_book.warehouse_id='$warehouse_id'
                      and DATE_FORMAT(log_book.vaccination_date, '%Y-%m') = '$year_month'";

            $this->_em = Zend_Registry::get('doctrine');
            $row = $row = $this->_em->getConnection()->prepare($str_qry);
            $row->execute();
        }
        $name = $data['name'];
        $father_name = $data['father_name'];
        $age = $data['age'];
        $contact = $data['contact'];
        $address = $data['address'];
        $district = $data['district'];
        $uc = $data['uc'];
        $serial_number = $data['serial_number'];
        $vaccination_day = $data['day'];
        $reffers_to = $data['reffers_to'];
        $remarks = $data['remarks'];
        $user_id = $this->_user_id;
        $count = 0;
        foreach ($district as $key => $val) {
            if ($val != '' && $val >= 0 && $uc[$key] != '' && $uc[$key] >= 0) {
                $vaccination_date = $vaccination_day[$key] . "-" . $mm . "-" . $yy;
                $log_book = new LogBook();
                $log_book->setName($name[$key]);
                $log_book->setFatherName($father_name[$key]);
                $log_book->setAge($age[$key]);
                $log_book->setContact($contact[$key]);
                $log_book->setAddress($address[$key]);
                $districtId = $this->_em->getRepository('Locations')->find($district[$key]);
                $log_book->setDistrict($districtId);
                $ucId = $this->_em->getRepository('Locations')->find($uc[$key]);
                $log_book->setUnionCouncil($ucId);
                $log_book->setVaccinationDate(new \DateTime(App_Controller_Functions::dateToDbFormat($vaccination_date)));
                $log_book->setRefferTo($reffers_to[$key]);
                $log_book->setRemarks($remarks[$key]);
                $warehouse = $this->_em->getRepository('Warehouses')->find($warehouse_id);
                $log_book->setWarehouse($warehouse);
                $log_book->setCreatedDate(App_Tools_Time::now());
                $log_book->setModifiedDate(App_Tools_Time::now());
                $userId = $this->_em->getRepository('Users')->find($user_id);
                $log_book->setCreatedBy($userId);
                $log_book->setModifiedBy($userId);
                $this->_em->persist($log_book);
                $this->_em->flush();
                $log_book_id = $log_book->getPkId();
                $item_id = $data[$serial_number[$key] . "_" . 'item_id'];
                $dose_no = $data[$serial_number[$key] . "_" . 'dose_no'];
                foreach ($item_id as $key => $val) {
                    if ($dose_no[$key] == "") {
                        $dose_no[$key] = 0;
                    }
                    $log_book_item_doses = new LogBookItemDoses();
                    $logBook = $this->_em->getRepository('LogBook')->find($log_book_id);
                    $log_book_item_doses->setLogBook($logBook);
                    $itemPack = $this->_em->getRepository('ItemPackSizes')->find($val);
                    $log_book_item_doses->setItemPackSize($itemPack);
                    $log_book_item_doses->setDoses($dose_no[$key]);
                    $user = $this->_em->getRepository('Users')->find($this->_user_id);
                    $log_book_item_doses->setModifiedBy($user);
                    $log_book_item_doses->setCreatedBy($user);
                    $log_book_item_doses->setCreatedDate(App_Tools_Time::now());
                    $log_book_item_doses->setModifiedDate(App_Tools_Time::now());
                    $this->_em->persist($log_book_item_doses);
                    $this->_em->flush();
                }
                $count++;
            }
        }
        return $count > 0;
    }

    // for new logbook entry form
    /**
     * Get Log Book
     * @return type
     */
    public function getLogBook() {
        if (!empty($this->form_values['entry_type'])) {
            // My Entries
            if ($this->form_values['entry_type'] == "1") {
                $where[] = "log_book.created_by = '" . $this->_user_id . "'";
            }
            // Referrals
            if ($this->form_values['entry_type'] == "2") {
                $where[] = "log_book.created_by <> '" . $this->_user_id . "'";
            }
        }
        // Default is My Entries
        else {
            $where[] = "log_book.created_by = '" . $this->_user_id . "'";
        }
        if (!empty($this->form_values['district'])) {
            $where[] = "log_book.district_id = '" . $this->form_values['district'] . "'";
        } else {
            $where[] = "log_book.district_id = 0";
        }

        if (!empty($this->form_values['tehsil'])) {
            $where[] = "Uc.parent_id = '" . $this->form_values['tehsil'] . "'";
        }
        if (!empty($this->form_values['uc'])) {
            $where[] = "log_book.union_council_id = '" . $this->form_values['uc'] . "'";
        }
        if (!empty($this->form_values['vaccination_date_from']) && !empty($this->form_values['vaccination_date_to'])) {
            $where[] = "DATE_FORMAT(log_book.vaccination_date,'%Y-%m-%d') BETWEEN '" . App_Controller_Functions::dateToDbFormat($this->form_values['vaccination_date_from']) . "' AND  '" . App_Controller_Functions::dateToDbFormat($this->form_values['vaccination_date_to']) . "' ";
        } else {
            $date_from = date('Y-m' . '-01');
            $date_to = date('Y-m-d');
            $where[] = "DATE_FORMAT(log_book.vaccination_date,'%Y-%m-%d') BETWEEN '" . $date_from . "' AND '" . $date_to . "'";
        }
        if (is_array($where)) {
            $where_s = implode(" AND ", $where);
        }
        $str_qry = "SELECT
                        log_book.union_council_id,
                        Uc.location_name AS Uc,
                        log_book.pk_id,
                        log_book.`name`,
                        log_book.father_name,
                        log_book.gender,
                        log_book.age,
                        log_book.contact,
                        log_book.address,
                        log_book.district_id,
                        log_book.vaccination_date,
                        log_book.refer_to_warehouse_id,
                        log_book.remarks,
                        log_book.warehouse_id,
                        log_book.created_by,
                        log_book.created_date,
                        log_book.modified_date,
                        log_book.reporting_start_date,
                        District.location_name AS District,
                        Tehsil.location_name AS Tehsil,
                        warehouses.warehouse_name AS RefFromEPI,
                        ref_from_uc.location_name AS RefFromUc,
                        ref_from_dist.location_name AS RefFromDist
                    FROM
                        log_book
                        LEFT JOIN locations AS Uc ON Uc.pk_id = log_book.union_council_id
                        INNER JOIN locations AS District ON log_book.district_id = District.pk_id
                        INNER JOIN locations AS Tehsil ON Uc.parent_id = Tehsil.pk_id
                        INNER JOIN warehouses ON log_book.warehouse_id = warehouses.pk_id
                        INNER JOIN locations AS ref_from_uc ON ref_from_uc.pk_id = warehouses.location_id
                        INNER JOIN locations AS ref_from_dist ON ref_from_dist.pk_id = warehouses.district_id WHERE
                        "
                . "$where_s";
        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Monthly Consumtion 2 Targets
     * @param type $wh_id
     * @param type $prev_month_date
     * @return type
     */
    public function monthlyConsumtion2Targets($wh_id, $prev_month_date) {
        $pov = explode('-', $prev_month_date);
        $querypro = " SELECT w0_.pk_id AS pkId,
                        w0_.children_live_birth,
                        w0_.surviving_children_0_11,
                        w0_.children_aged_12_23,
                        w0_.pregnant_women
                        FROM
                        hf_data_master AS w0_
                        WHERE
                        w0_.warehouse_id = '$wh_id'
                        AND
                        DATE_FORMAT(w0_.reporting_start_date,'%Y') = '$pov[0]' LIMIT 1";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $result = $row->fetchAll();
        return $result[0];
    }

    /**
     * Update Yearly Targets
     * @param type $wh_id
     * @param type $report_date
     * @param type $children_live_birth
     * @param type $surviving_children_0_11_M
     * @param type $children_aged_12_23_M
     * @param type $pregnant_women
     * @param type $cba
     * @param type $above2Year
     */
    public function updateYearlyTargets($wh_id, $report_date, $children_live_birth, $surviving_children_0_11_M, $children_aged_12_23_M, $pregnant_women, $cba, $above2Year, $total_population) {
        $rep_date = explode('-', $report_date);
        $str_qry_hf = "SELECT warehouse_population.pk_id
                            FROM
                            warehouse_population
                            where
                            warehouse_population.warehouse_id = '" . $wh_id . "'
                            and DATE_FORMAT(warehouse_population.estimation_year, '%Y') = '" . $rep_date[0] . "' ";
        $this->_em = Zend_Registry::get('doctrine');
        $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
        $row_hf->execute();
        $warehouse_population = $row_hf->fetchAll();
        $warehouse_population_id = $warehouse_population[0]['pk_id'];
        if (!empty($warehouse_population_id)) {
            $warehouses_population = $this->_em->getRepository('WarehousePopulation')->find($warehouse_population_id);
        } else {
            $warehouses_population = new WarehousePopulation();
        }
        $warehouses_population->setLiveBirthsPerYear($children_live_birth);
        $warehouses_population->setSurvivingChildren011($surviving_children_0_11_M);
        $warehouses_population->setChildrenAged1223($children_aged_12_23_M);
        $warehouses_population->setPregnantWomenPerYear($pregnant_women);
        if ($this->_identity->getProvinceId() != 1) {
            $warehouses_population->setWomenOfChildBearingAge($cba);
        } else {
            $warehouses_population->setFacilityTotalPouplation($total_population);
        }
        $warehouses_population->setAbove2Year($above2Year);
        $warehouses_population->setEstimationYear(new \DateTime(App_Controller_Functions::dateToDbFormat($report_date)));
        $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
        $warehouses_population->setWarehouse($warehouse_id);
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $warehouses_population->setModifiedBy($user);
        $warehouses_population->setCreatedBy($user);
        $warehouses_population->setCreatedDate(App_Tools_Time::now());
        $warehouses_population->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($warehouses_population);
        $this->_em->flush();

        $ware_house = $wh_id;
        if (!empty($ware_house)) {
            $date_time = date("Y/m/d");
            $qry = "SELECT REPUpdateCapacity($ware_house,'$date_time') from DUAL";

            $row = $this->_em->getConnection()->prepare($qry);
            $row->execute();

            $qry1 = "SELECT REPUpdateRequirement($ware_house,'$date_time') from DUAL";
            $row1 = $this->_em->getConnection()->prepare($qry1);
            $row1->execute();
        }
    }

    /**
     * Update Hf Sessions
     * @param type $wh_id
     * @param type $report_date
     * @param type $fix_plan_sessions
     * @param type $fix_actually_held_sessions
     * @param type $outreach_plan_sessions
     * @param type $outreach_actually_held_sessions
     */
    public function updateHfSessions($wh_id, $report_date, $fix_plan_sessions, $fix_actually_held_sessions, $outreach_plan_sessions, $outreach_actually_held_sessions) {
        $str_qry_hf = "SELECT hf_sessions.pk_id
                            FROM
                            hf_sessions
                            where
                            hf_sessions.warehouse_id ='" . $wh_id . "'
                            and DATE_FORMAT(hf_sessions.reporting_start_date, '%Y-%m-%d') = '" . $report_date . "' ";
        $this->_em = Zend_Registry::get('doctrine');
        $row_hf = $this->_em->getConnection()->prepare($str_qry_hf);
        $row_hf->execute();
        $result_hf_edit = $row_hf->fetchAll();
        $hf_session_id = $result_hf_edit[0]['pk_id'];
        if (!empty($hf_session_id)) {
            $hf_sessions = $this->_em->getRepository('HfSessions')->find($hf_session_id);
        } else {
            $hf_sessions = new HfSessions();
        }
        $warehouse_status = $this->_em->getRepository('ListDetail')->find(Model_ListDetail::WAREHOUSE_STATUS_REPORTING);
        $hf_sessions->setWarehouseStatus($warehouse_status);
        $hf_sessions->setFixedPlannedSessions($fix_plan_sessions);
        $hf_sessions->setFixedActuallyHeldSessions($fix_actually_held_sessions);
        $hf_sessions->setOutreachPlannedSessions($outreach_plan_sessions);
        $hf_sessions->setOutreachActuallyHeldSessions($outreach_actually_held_sessions);
        $hf_sessions->setReportingStartDate(new \DateTime(App_Controller_Functions::dateToDbFormat($report_date)));
        $warehouse_id = $this->_em->getRepository('Warehouses')->find($wh_id);
        $hf_sessions->setWarehouse($warehouse_id);
        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $hf_sessions->setModifiedBy($user);
        $hf_sessions->setCreatedBy($user);
        $hf_sessions->setCreatedDate(App_Tools_Time::now());
        $hf_sessions->setModifiedDate(App_Tools_Time::now());
        $this->_em->persist($hf_sessions);
        $this->_em->flush();
    }

    /**
     * Get Province Id
     * @return type
     */
    public function getProvinceId() {
        $warehouse_id = $this->warehouse_id;
        $querypro = "SELECT
                warehouses.warehouse_name,
                warehouses.province_id
                FROM
                warehouses
                WHERE
                warehouses.pk_id = $warehouse_id";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $result = $row->fetchAll();
        return $result[0];
        //  return $result[0]['province_id'];
    }

    /**
     * Vaccine Coverage
     * @return type
     */
    public function vaccineCoverage() {
        $form_values = $this->form_values;
        $district = $form_values['district'];
        $querypro = "SELECT
        B.item_name,
        A.district,
        ROUND((( ( (A.population / 100) * B.population_percent_increase_per_year ) / 100 * B.child_surviving_percent_per_year ) * B.doses_per_year ) ) AS AnnualyTarget,
        ROUND( ( ( ( (A.population / 100) * B.population_percent_increase_per_year ) / 100 * B.child_surviving_percent_per_year ) * B.doses_per_year ) / 12 ) AS MonthlyTarget
      FROM
        (SELECT DISTINCT locations.location_name AS district,
                        ( SELECT IFNULL( location_populations.population, 0 )
                                FROM
                                        location_populations
                                WHERE
                                        location_populations.location_id = locations.pk_id
                                AND DATE_FORMAT(
                                        location_populations.estimation_date,
                                        '%Y'
                                ) = '2015'
                        ) AS population
                FROM
                        pilot_districts
                INNER JOIN locations ON pilot_districts.district_id = locations.district_id
                WHERE
                        locations.geo_level_id = 4
                AND locations.district_id = $district
        ) A,
        ( SELECT
                        item_pack_sizes.item_name,
                        items.population_percent_increase_per_year,
                        items.child_surviving_percent_per_year,
                        items.doses_per_year
                FROM
                        item_pack_sizes
                INNER JOIN items ON item_pack_sizes.item_id = items.pk_id
                where items.item_category_id = 1

        ) B";
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Generate Stock Availability Report
     * @return type
     */
    public function generateStockAvailabilityReport() {
        $reports = new Model_Reports();
        $rs_warehouse_data = array();
        $request_array = $this->temp;
        if (!empty($request_array['tp']) && !empty($request_array['go'])) {
            if (isset($request_array['item_id']) && !empty($request_array['item_id'])) {
                $sel_item = $request_array['item_id'];
                $q_str_pro = " AND stakeholder_item_pack_sizes.item_pack_size_id = " . $request_array['item_id'];
            }
            if (isset($request_array['stk_id']) && !empty($request_array['stk_id'])) {
                if ($request_array['stk_id'] != 'all') {
                    $sel_stk = $request_array['stk_id'];
                    $q_str_stk = " AND warehouses.stakeholder_id = " . $request_array['stk_id'];
                } else {
                    $q_str_stk = " ";
                    $sel_stk = $request_array['stk_id'];
                }
            }
            if (isset($request_array['prov_sel']) && !empty($request_array['prov_sel'])) {
                if ($request_array['prov_sel'] != 'all') {
                    $sel_prov = $request_array['prov_sel'];
                    $q_str_prov = " AND warehouses.province_id = " . $request_array['prov_sel'];
                } else {
                    $sel_prov = $request_array['prov_sel'];
                    $q_str_prov = "";
                }
            }
        } else if (!empty($request_array['go'])) {

            if (isset($request_array['prod_sel']) && !empty($request_array['prod_sel']) && $request_array['prod_sel'] != 'all') {
                $sel_item = $request_array['prod_sel'];
                $q_str_pro = " AND stakeholder_item_pack_sizes.item_pack_size_id = " . $request_array['prod_sel'];
            }
            if (isset($request_array['stk_sel']) && !empty($request_array['stk_sel']) && $request_array['stk_sel'] != 'all') {
                $sel_stk = $request_array['stk_sel'];
                $q_str_stk = " AND warehouses.stakeholder_id = " . $request_array['stk_sel'];
            } else {
                $q_str_stk = " ";
                $sel_stk = 1;
            }
            if (isset($request_array['prov_sel']) && !empty($request_array['prov_sel']) && $request_array['prov_sel'] != 'all') {
                $sel_prov = $request_array['prov_sel'];
                $q_str_prov = " AND warehouses.province_id = " . $request_array['prov_sel'];
            } else {
                $sel_prov = (!isset($request_array['prov_id']) ? 0 : $request_array['prov_id'] );
                $q_str_prov = "";
            }
        } else {
            $row_max_date = $reports->getMaxReportDate();
            $this->temp['month_sel'] = $row_max_date[0]['report_month'];
            $this->temp['year_sel'] = $row_max_date[0]['report_year'];

            $sel_stk = '1';
            $q_str_stk = " AND warehouses.stakeholder_id = 1";
            $sel_item = '1';
            $q_str_pro = " AND stakeholder_item_pack_sizes.item_pack_size_id = 1";
            $sel_prov = '1';
            $q_str_prov = " AND warehouses.province_id = 1";
        }
        $rs_warehouse_data['national'] = $this->getWarehouseDataByMonthAndYearForNational($q_str_pro, $q_str_stk);
        $rs_warehouse_data['provincial'] = $this->getWarehouseDataByMonthAndYearForProvince($q_str_pro, $q_str_stk, $q_str_prov);
        $rs_warehouse_data['district'] = $this->getWarehouseDataByMonthAndYearForDistrict($q_str_pro, $q_str_stk, $q_str_prov);
        return $rs_warehouse_data;
    }

    /**
     * Get Warehouse Data By Month And Year For National
     * @param type $q_str_pro
     * @param type $q_str_stk
     * @return boolean
     */
    public function getWarehouseDataByMonthAndYearForNational($q_str_pro = "", $q_str_stk = "") {
        $str_sql = "SELECT DISTINCT warehouses.pk_id, warehouses.warehouse_name, warehouses.stakeholder_id,
            warehouses.province_id, office.stakeholder_name
            FROM warehouses
            INNER JOIN stakeholders AS office ON office.pk_id = warehouses.stakeholder_office_id
            INNER JOIN stock_batch_warehouses ON warehouses.pk_id = stock_batch_warehouses.warehouse_id
            INNER JOIN stock_detail ON stock_batch_warehouses.pk_id = stock_detail.stock_batch_warehouse_id
            INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            WHERE office.geo_level_id = 1
            AND warehouses.status = 1
            AND MONTH(stock_master.transaction_date)= '" . $this->temp['month_sel'] . "'
            AND YEAR(stock_master.transaction_date)= '" . $this->temp['year_sel'] . "'
            $q_str_pro $q_str_stk";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $rs = $row->execute();
        if (!empty($rs) && count($rs) > 0) {
            return $row->fetchAll();
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse Data By Month And Year For Province
     * @param type $q_str_pro
     * @param type $q_str_stk
     * @param type $q_str_prov
     * @return boolean
     */
    public function getWarehouseDataByMonthAndYearForProvince($q_str_pro = "", $q_str_stk = "", $q_str_prov = "") {
        $str_sql = "SELECT DISTINCT warehouses.warehouse_name, warehouses.stakeholder_id,
            warehouses.province_id, office.stakeholder_name, province.location_name AS province
            FROM warehouses
            INNER JOIN stakeholders AS office ON office.pk_id = warehouses.stakeholder_office_id
            INNER JOIN stock_batch_warehouses ON warehouses.pk_id = stock_batch_warehouses.warehouse_id
            INNER JOIN stock_detail ON stock_batch_warehouses.pk_id = stock_detail.stock_batch_warehouse_id
            INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
            INNER JOIN locations AS province ON province.pk_id = warehouses.province_id
            WHERE office.geo_level_id = 2
            AND warehouses.status = 1
            AND MONTH(stock_master.transaction_date)= '" . $this->temp['month_sel'] . "'
            AND YEAR(stock_master.transaction_date)= '" . $this->temp['year_sel'] . "'
            $q_str_pro $q_str_stk $q_str_prov
            ";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $rs = $row->execute();
        if (!empty($rs) && count($rs) > 0) {
            return $row->fetchAll();
        } else {
            return false;
        }
    }

    /**
     * Get Warehouse Data By Month And Year For District
     * @param type $q_str_pro
     * @param type $q_str_stk
     * @param type $q_str_prov
     * @return boolean
     */
    public function getWarehouseDataByMonthAndYearForDistrict($q_str_pro = "", $q_str_stk = "", $q_str_prov = "") {
        $str_sql = "SELECT DISTINCT warehouses.warehouse_name, warehouses.stakeholder_id,
            warehouses.province_id, warehouses.district_id, office.stakeholder_name,
            province.location_name AS province, district.location_name AS district
            FROM warehouses
            INNER JOIN locations AS province ON warehouses.province_id = province.pk_id
            INNER JOIN locations AS district ON warehouses.district_id = district.pk_id
            INNER JOIN stakeholders AS office ON office.pk_id = warehouses.stakeholder_office_id
           WHERE office.geo_level_id = 4
            AND warehouses.status = 1

             $q_str_stk $q_str_prov
                AND warehouses.pk_id NOT IN (3682,3685,3686)
            ORDER BY province.location_name ASC, district.location_name ASC";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $rs = $row->execute();
        if (!empty($rs) && count($rs) > 0) {
            return $row->fetchAll();
        } else {
            return false;
        }
    }

    /**
     * Donor's Contrictuion
     * @return boolean|string
     */
    public function donorContribution() {
        $year_to = $this->form_values['to_date'];
        $str_sql = "SELECT
        Sum(stock_detail.quantity) AS qty,
        warehouses.warehouse_name,
        warehouses.pk_id
        FROM
                stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN warehouses ON stock_master.from_warehouse_id = warehouses.pk_id
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        WHERE
                stock_master.to_warehouse_id = 159
        AND stakeholders.stakeholder_type_id = 2
        AND YEAR (
                stock_master.transaction_date
        ) = $year_to
        AND item_pack_sizes.item_category_id = 1
        GROUP BY
                stakeholders.pk_id";

        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        $xmlstore = "<chart exportEnabled='1' exportAction='Download' caption='Donors Contribution - Year $year_to'  exportFileName='Donors Contribution - Year " . $year_to . "' yAxisName='Percentage' numberSuffix=''  formatNumberScale='0' showLegend='1' theme='fint'>";
        foreach ($data as $row) {
            if ($row['pk_id'] == 1) {
                $sliced = "issliced='1'";
            } else {
                $sliced = "";
            }
            $warehouse_name = $row['warehouse_name'];
            $qty = $row['qty'];
            $xmlstore .= "<set label='Stakeholder' value='$warehouse_name' />";
            $xmlstore .= "<set label='$warehouse_name' link=\"JavaScript:showSubGraphs('" . $row['pk_id'] . "');\" value='$qty' $sliced />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Provincially Vaccination
     * @return boolean|string
     */
    public function provinciallyVaccination() {
        $year_to = $this->form_values['to_date'];

        if (empty($this->form_values['wh_id'])) {
            $wh_id = 1;
        } else {
            $wh_id = $this->form_values['wh_id'];
        }
        $str_sql_name = "Select warehouses.warehouse_name from warehouses where warehouses.pk_id=$wh_id";
        $row_name = $this->_em_read->getConnection()->prepare($str_sql_name);
        $row_name->execute();
        $data_name = $row_name->fetchAll();
        $str_sql = "SELECT
        stock_master.to_warehouse_id,
        ABS(SUM(stock_detail.quantity)) AS qty,
        locations.location_name
        FROM
                stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN warehouses ON stock_master.to_warehouse_id = warehouses.pk_id
        INNER JOIN locations ON locations.pk_id = warehouses.location_id
         WHERE
        stock_master.from_warehouse_id = 159
        AND warehouses.stakeholder_office_id = 2
        AND YEAR (
                stock_master.transaction_date
        ) = $year_to
        AND stock_detail.stock_batch_warehouse_id IN (
        SELECT DISTINCT
                stock_detail.stock_batch_warehouse_id
        FROM
                stock_master
        INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
        WHERE
                stock_master.from_warehouse_id = $wh_id
        AND stock_master.to_warehouse_id = 159
        AND YEAR (
                stock_master.transaction_date
        ) = $year_to
        )
        GROUP BY
                warehouses.pk_id";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        $warehouse_name = $data_name[0]['warehouse_name'];
        $xmlstore = "<chart  exportEnabled='1' exportAction='Download' caption='$warehouse_name Provincially Vaccination - Year $year_to'  exportFileName='$warehouse_name Provincially Vaccinatin - Year $year_to' yAxisName='Percentage' numberSuffix=''  formatNumberScale='0' theme='fint' showLegend='1'>";
        foreach ($data as $row) {
            $location_name = $row['location_name'];
            $qty = $row['qty'];
            $xmlstore .= "<set label='Location' value='$location_name' />";
            $xmlstore .= "<set label='$location_name' value='$qty' />";
        }
        return $xmlstore .= "</chart>";
    }

    /**
     * Get Provincial Consumption
     * @return type
     */
    public function getProvincialConsumption() {
        $form_values = $this->form_values;

        $str_sql = "SELECT
	Sum(
		hf_data_master.issue_balance
	) AS issue_balance,
	DATE_FORMAT(
		hf_data_master.reporting_start_date,
		'%m/%Y'
	) as reporting_start_date
        FROM
                hf_data_master
        INNER JOIN warehouses ON warehouses.pk_id = hf_data_master.warehouse_id
        INNER JOIN stakeholders ON warehouses.stakeholder_office_id = stakeholders.pk_id
        WHERE
                hf_data_master.item_pack_size_id = " . $form_values['product_id'] . "
        AND DATE_FORMAT(
                hf_data_master.reporting_start_date,
                '%Y-%m'
        ) BETWEEN '" . $form_values['from_date'] . "'
        AND '" . $form_values['to_date'] . "'
        AND warehouses.province_id = " . $form_values['province_id'] . "
        AND stakeholders.geo_level_id = " . $form_values['level'] . "
        GROUP BY
	DATE_FORMAT(
		hf_data_master.reporting_start_date,
		'%Y-%m'
	)";
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * add Consumption Comments
     */
    public function addConsumptionComments() {
        $data = $this->form_values;
        $comments = $data['comments'];

        $wh_id = $data['wh_id'];
        $rpt_date = $data['rpt_date'];
        $consumption_comments = new ConsumptionComments();
        $consumption_comments->setComments($comments);

        $consumption_comments->setReportingStartDate(new \DateTime($rpt_date));

        $warehouse = $this->_em->getRepository("Warehouses")->find($wh_id);
        $consumption_comments->setWarehouse($warehouse);
        $consumption_comments->setCreatedDate(App_Tools_Time::now());
        $consumption_comments->setModifiedDate(App_Tools_Time::now());

        $user = $this->_em->getRepository('Users')->find($this->_user_id);
        $consumption_comments->setModifiedBy($user);
        $consumption_comments->setCreatedBy($user);
        $this->_em->persist($consumption_comments);
        $this->_em->flush();
    }

    public function getEvacConsumptionByUc() {
        //   print_r($this->form_values);
        $date = $this->form_values['date'];
        $item_id = $this->form_values['item'];
        $uc_id = $this->form_values['uc_id'];


        $qry = "SELECT
        Sum(hf_data_master.issue_balance) AS consumption,
        locations.location_name,
        location_mapping.evac_location_id,
        hf_data_master.item_pack_size_id,
        item_pack_sizes.item_name,
        item_doses.evacc_item_id
        FROM
        hf_data_master
        INNER JOIN warehouses ON hf_data_master.warehouse_id = warehouses.pk_id
        INNER JOIN locations ON warehouses.location_id = locations.pk_id
        INNER JOIN location_mapping ON locations.pk_id = location_mapping.lmis_location_id
        INNER JOIN item_pack_sizes ON hf_data_master.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN item_doses ON item_pack_sizes.pk_id = item_doses.item_pack_size_id
        WHERE
        hf_data_master.item_pack_size_id = '$item_id' AND
        locations.parent_id = '$uc_id' AND
        Date_format(hf_data_master.reporting_start_date,'%Y-%m') = '$date' AND
        location_mapping.`level` = 6
        GROUP BY locations.pk_id
        ORDER BY locations.location_name";





        $row = $this->_em_read->getConnection()->prepare($qry);
        $row->execute();
        $result = $row->fetchAll();
        $item_pack_sizes = new Model_ItemPackSizes();
        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
        $item_pack = $item_pack_sizes->getProductName();
        $locations = new Model_Locations();
        $locations->form_values['pk_id'] = $uc_id;
        $this->view->location_name = $tehsil_name = $locations->getLocationName();
        $xmlstore = "<chart labelDisplay='rotate' exportEnabled='1' exportAction='Download' bgColor='white' caption='eVaccs Consumption vs LMIS Consumption of $item_pack(Doses) in $tehsil_name during " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'
            exportFileName='Consumption " . date('Y-m-d H:i:s') . "' yAxisName='Doses' showValues='1' formatNumberScale='0' theme='fint'>";

        $xmlstore .= '<categories>';

        foreach ($result as $data) {
            $location_name = $data['location_name'];
            $xmlstore .= "<category label='$location_name' />";
        }
        $xmlstore .= '</categories>';
        $xmlstore .= "<dataset seriesname='eVaccs Consumption' color='0075C2'>";
        foreach ($result as $data) {
            $evac_item = $data['evacc_item_id'];
            $evac_location = $data['evac_location_id'];
            $qry_1 = "SELECT
                Sum(evac_consumption.item_qty) AS qty
                FROM
                evac_consumption
                WHERE
                Date_format(
                        evac_consumption.reporting_month_date,
                        '%Y-%m'
                ) = '$date' AND
                evac_consumption.uc_code = '$evac_location'  AND
                evac_consumption.item_id = '$evac_item' ";


            $row_1 = $this->_em_read->getConnection()->prepare($qry_1);
            $row_1->execute();
            $result_1 = $row_1->fetchAll();
            $qty = $result_1[0]['qty'];
            $xmlstore .= "<set value='$qty' />";
        }
        $xmlstore .= "</dataset>";
        $xmlstore .= "<dataset seriesname='vLMIS Consumption' color='#008000'>";
        foreach ($result as $data) {

            $xmlstore .= "<set value='$data[consumption]' />";
        }

        $xmlstore .= "</dataset>";

        return $xmlstore .= "</chart>";
    }

    /**
     * Get Consumption By Uc
     * @param type $dist_id
     * @return string
     */
//    public function getEvacConsumptionByUc() {
//        //   print_r($this->form_values);
//        $date = $this->form_values['date'];
//        $item_id = $this->form_values['item'];
//        $uc_id = $this->form_values['uc_id'];
//
//
//        $category_5 = "";
//        $dataset_5 = "";
//        $date_in = explode('-', $date);
//
//
//        if ($date_in['1'] == '04' || $date_in['1'] == '06' || $date_in['1'] == '09' || $date_in['1'] == '11') {
//            $month_array = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30');
//        } else if ($date_in['1'] == '02') {
//            $month_array = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29');
//        } else {
//            $month_array = array('01' => '1', '02' => '2', '03' => '3', '04' => '4', '05' => '5', '06' => '6', '07' => '7', '08' => '8', '09' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31');
//        }
//
//        foreach ($month_array as $key => $month) {
//
//            $category_5 .= "<category label='" . $month . "' stepskipped='false' labeltooltext='' />";
//        }
//        $qry1 = "SELECT
//                item_doses.item_pack_size_id,
//                item_doses.dose_id,
//                item_doses.evacc_item_id,
//                item_doses.evacc_item_name
//                FROM
//                item_doses
//                WHERE
//                item_doses.item_pack_size_id = $item_id";
//        $row1 = $this->_em_read->getConnection()->prepare($qry1);
//        $row1->execute();
//        $evac_item = $row1->fetchAll();
//        foreach ($evac_item as $row_item) {
//            $items = array();
//            if (!in_array($row_item['evacc_item_name'], $items)) {
//                $items[] = $row_item['evacc_item_name'];
//            }
//            foreach ($items as $item) {
//                $dataset_5 .= "<dataset seriesname='$item'>";
//            }
//            foreach ($month_array as $key => $month) {
//
//
//                $evac_item_i = $row_item['evacc_item_id'];
//                $qry = "SELECT
//        item_doses.evacc_item_name,
//        Date_format(evac_consumption.reporting_month_date,'%d') AS `Day_1`,
//        SUM(evac_consumption.item_qty) qty
//        FROM
//        evac_consumption
//        INNER JOIN item_doses ON evac_consumption.item_id = item_doses.evacc_item_id
//        INNER JOIN location_mapping ON evac_consumption.uc_code = location_mapping.evac_location_id
//        WHERE
//        item_doses.evacc_item_id = $evac_item_i AND
//        location_mapping.`level` = 6 AND
//        location_mapping.lmis_location_id = $uc_id  AND
//        Date_format(evac_consumption.reporting_month_date,'%Y-%m') = '$date'
//        AND Date_format(evac_consumption.reporting_month_date,'%d') = '$key'
//        GROUP BY Date_format(evac_consumption.reporting_month_date,'%d'),
//        item_doses.dose_id
//        ORDER BY evacc_item_name,`Day_1`";
//
//                $row = $this->_em_read->getConnection()->prepare($qry);
//                $row->execute();
//                $evac_consumption = $row->fetchAll();
//
//
//                if (empty($evac_consumption)) {
//                    $evac_consumption = array('0' => array('qty' => 0));
//                }
//                foreach ($evac_consumption as $value) {
//
//                    $qty = $value['qty'];
//
//
//
//                    $dataset_5 .= "<set value = '" . $qty . "' />";
//                }
//            }
//            $dataset_5 .= "</dataset>";
//        }
//        $item_pack_sizes = new Model_ItemPackSizes();
//        $item_pack_sizes->form_values['pk_id'] = $this->form_values['item'];
//        $item_pack = $item_pack_sizes->getProductName();
//        $locations = new Model_Locations();
//        $locations->form_values['pk_id'] = $uc_id;
//        $tehsil_name = $this->view->location_name = $locations->getLocationName();
//        $xmlstore5 = "
//           <chart caption='eVaccs Consumption of $item_pack(Doses) in $tehsil_name   during " . ("(" . date('M Y', strtotime($this->form_values['date'])) . ")" ) . "'  plotgradientcolor='' bgcolor='FFFFFF' showalternatehgridcolor='0' divlinecolor='CCCCCC' showvalues='0' showcanvasborder='0' canvasborderalpha='0' canvasbordercolor='CCCCCC' canvasborderthickness='1'  captionpadding='30' linethickness='3' yaxisvaluespadding='15' legendshadow='0' legendborderalpha='0' palettecolors='#f8bd19,#008ee4,#33bdda,#e44a00,#6baa01,#583e78' showborder='0'>
//
//                <categories>
//                    $category_5
//                </categories>
//                $dataset_5
//            </chart>  ";
//
//
//        return $xmlstore5;
//    }
}