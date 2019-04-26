<?php

/**
 * Zend_View_Helper_Reports
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */





/**
 *  Zend View Helper Reports
 */
class Zend_View_Helper_Reports extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * 
     * @return \Zend_View_Helper_Reportsreports
     */
    public function reports() {
        return $this;
    }

    /**
     * Report Data
     * @param type $in_col
     * @param type $in_rg
     * @param type $in_type
     * @param type $month
     * @param type $year
     * @param type $in_item
     * @param type $in_stk
     * @param type $in_prov
     * @param type $in_dist
     * @return type
     */
    public function reportData($in_col, $in_rg, $in_type, $month, $year, $in_item, $in_stk, $in_prov, $in_dist) {

        $row = $row = $this->_em_read->getConnection()->prepare("SELECT REPgetData('$in_col','$in_rg','$in_type'," . $month . "," . $year . "," . $in_item . ",'$in_stk','$in_prov','$in_dist') AS Value from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Reports Mos
     * @param type $v_mos
     * @param type $sel_item
     * @param type $id
     * @param type $sel_lvl
     * @return type
     */
    public function reportsMos($v_mos, $sel_item, $id, $sel_lvl) {

        $row = $this->_em_read->getConnection()->prepare("SELECT getMosColor('" . $v_mos . "'," . $sel_item . ",1," . $sel_lvl . ") as col from Dual");
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Reporting Rate Str
     * @param type $in_type
     * @param type $in_month
     * @param type $in_year
     * @param type $in_item
     * @param type $in_WF
     * @param type $in_skt
     * @param type $in_prov
     * @param type $in_dist
     * @return type
     */
    public function getReportingRateStr($in_type, $in_month, $in_year, $in_item, $in_WF, $in_skt, $in_prov, $in_dist) {

        $row = $this->_em_read->getConnection()->prepare("SELECT REPgetReportingRateStr('" . $in_type . "'," . $in_month . "," . $in_year . ",'" . $in_item . "','" . $in_WF . "'," . $in_skt . "," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Availability Rate Str
     * @param type $in_type
     * @param type $in_month
     * @param type $in_year
     * @param type $in_item
     * @param type $in_WF
     * @param type $in_skt
     * @param type $in_prov
     * @param type $in_dist
     * @return type
     */
    public function getAvailabilityRateStr($in_type, $in_month, $in_year, $in_item, $in_WF, $in_skt, $in_prov, $in_dist) {
        $row = $this->_em_read->getConnection()->prepare("SELECT REPgetAvailabilityRateStr('" . $in_type . "'," . $in_month . "," . $in_year . ",'" . $in_item . "','" . $in_WF . "'," . $in_skt . "," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

   
    /**
     * 
     * @param type $master_id
     * @param type $wh_id
     * @return type
     */
    public function getAdjustmentReceiveList($master_id, $wh_id) {

        $querypro = "SELECT
                    stock_master.transaction_date,
                    stock_master.transaction_number,
                    stock_master.transaction_reference,
                    item_pack_sizes.item_name,
                    stock_batch.number,
                    transaction_types.transaction_type_name,
                    ABS(COALESCE(stock_detail.quantity,null,0)) as quantity
                    FROM
                            stock_master
                    INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id                    
                    INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
                    WHERE
                            stock_master.parent_id = $master_id
                    AND stock_master.transaction_type_id > 2
                    AND stock_master.to_warehouse_id = $wh_id";

        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Pre Month Cb
     * @param type $wh_id
     * @param type $rptDate
     * @return type
     */
    public function getPreMonthCb($wh_id, $rptDate) {

        $querypro = "SELECT
                        hf_data_master.item_pack_size_id as item_id,
                        COALESCE(hf_data_master.closing_balance, NULL, 0) AS wh_cbl_a
                        FROM
                                hf_data_master
                        WHERE
                                hf_data_master.warehouse_id = $wh_id
                         AND hf_data_master.reporting_start_date = '$rptDate'";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Pre Month Cb 2
     * @param type $wh_id
     * @param type $rptDate
     * @return type
     */
    public function getPreMonthCb2($wh_id, $rptDate) {

        $querypro = "SELECT
                        hf_data_master.item_pack_size_id as item_id,
                        COALESCE(hf_data_master.closing_balance, NULL, 0) AS wh_cbl_a
                        FROM
                                hf_data_master
                        WHERE
                                hf_data_master.warehouse_id = $wh_id
                         AND hf_data_master.reporting_start_date = '$rptDate'";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * 
     * @param type $detail_id
     * @return string
     */
    public function getIssueNumberByReceiveNumber($detail_id) {

        $querypro = "SELECT DISTINCT
                    master2.transaction_number,
                    warehouses.warehouse_name
                    FROM
                    stock_detail
                    INNER JOIN stock_detail AS detail2 ON stock_detail.is_received = detail2.pk_id
                    INNER JOIN stock_master AS master2 ON detail2.stock_master_id = master2.pk_id
                    INNER JOIN warehouses ON master2.from_warehouse_id = warehouses.pk_id
                    WHERE
                    master2.transaction_type_id = 2 AND
                    stock_detail.pk_id = $detail_id";

        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        $rows = $row->fetchAll();

        if (!empty($rows) && count($rows) > 0) {
            return '<b>Issue No.: </b>' . $rows[0]['transaction_number'] . '</td><td class="right">
                    <b>Source: </b> ' . $rows[0]['warehouse_name'];
        } else {
            return '';
        }
    }

    /**
     * Get Summary List
     * @param type $master_id
     * @return type
     */
    public function getSummaryList($master_id) {

        $querypro = "SELECT
                            A.item_name,                            
                            (
                                    COALESCE (ABS(A.total_vials), NULL, 0) - COALESCE (ABS(B.total_vials), NULL, 0)
                            ) AS net_received,
                            COALESCE (A.number_of_doses, NULL, 0) AS doses_per_vial,
                            (
                                    COALESCE (ABS(A.total_vials), NULL, 0) - COALESCE (ABS(B.total_vials), NULL, 0)
                            ) * COALESCE (A.number_of_doses, NULL, 0) AS total_doses,
                            (A.unit_price * (
                                    COALESCE (ABS(A.total_vials), NULL, 0) - COALESCE (ABS(B.total_vials), NULL, 0)
                            ) * COALESCE (A.number_of_doses, NULL, 0)) as total_cost
                    FROM
                            (
                                    SELECT
                                            Sum(stock_detail.quantity) AS total_vials,
                                            item_pack_sizes.item_name,
                                            item_pack_sizes.number_of_doses,
                                            stakeholder_item_pack_sizes.item_pack_size_id,
                                            stock_batch.unit_price
                                    FROM
                                            stock_master
                                    INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                                    WHERE
                                            stock_master.pk_id = $master_id
                                    GROUP BY item_pack_sizes.pk_id
                            ) A
                    LEFT JOIN (
                            SELECT
                                    SUM(stock_detail.quantity) AS total_vials,
                                    stakeholder_item_pack_sizes.item_pack_size_id
                            FROM
                                    stock_master
                            INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                            WHERE
                                    stock_master.parent_id = $master_id
                            AND stock_master.transaction_type_id > 2
                            GROUP BY item_pack_sizes.pk_id
                    ) B ON B.item_pack_size_id = A.item_pack_size_id";


        $row = $this->_em_read->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Cancel Summary List
     * @param type $master_id
     * @return type
     */
    public function getCancelSummaryList($master_id) {

        $querypro = "SELECT
                            A.item_name,                            
                            (
                                    COALESCE (ABS(A.total_vials), NULL, 0) - COALESCE (ABS(B.total_vials), NULL, 0)
                            ) AS net_received,
                            COALESCE (A.number_of_doses, NULL, 0) AS doses_per_vial,
                            (
                                    COALESCE (ABS(A.total_vials), NULL, 0) - COALESCE (ABS(B.total_vials), NULL, 0)
                            ) * COALESCE (A.number_of_doses, NULL, 0) AS total_doses,
                            (A.unit_price * (
                                    COALESCE (ABS(A.total_vials), NULL, 0) - COALESCE (ABS(B.total_vials), NULL, 0)
                            ) * COALESCE (A.number_of_doses, NULL, 0)) as total_cost
                    FROM
                            (
                                    SELECT
                                            Sum(stock_detail.quantity) AS total_vials,
                                            item_pack_sizes.item_name,
                                            item_pack_sizes.number_of_doses,
                                            stakeholder_item_pack_sizes.item_pack_size_id,
                                            stock_batch.unit_price
                                    FROM
                                            stock_master_history as stock_master
                                    INNER JOIN stock_detail_history as stock_detail ON stock_detail.stock_master_id = stock_master.master_id
                                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                                    WHERE
                                            stock_master.master_id = $master_id and stock_master.action_type = 2 and stock_detail.action_type = 3
                                    GROUP BY
                                            stakeholder_item_pack_sizes.item_pack_size_id
                            ) A
                    LEFT JOIN (
                            SELECT
                                    SUM(stock_detail.quantity) AS total_vials,
                                    stakeholder_item_pack_sizes.item_pack_size_id
                            FROM
                                    stock_master_history as stock_master
                            INNER JOIN stock_detail_history as stock_detail ON stock_detail.stock_master_id = stock_master.master_id
                            INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                            INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                            INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                            INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                            INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                            WHERE
                                    stock_master.parent_id = $master_id
                            AND stock_master.transaction_type_id > 2 and stock_master.action_type = 2 and stock_detail.action_type = 3
                            GROUP BY
                                    stakeholder_item_pack_sizes.item_pack_size_id
                    ) B ON B.item_pack_size_id = A.item_pack_size_id";
        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Issuance Percent
     * @param type $warehouse_id
     * @param type $to_warehouse_id
     * @param type $item_pack_id
     * @param type $month
     * @param type $year
     * @return type
     */
    public function getIssuancePercent($warehouse_id, $to_warehouse_id, $item_pack_id, $month, $year) {


        $report_date1 = $year . "-" . $month;
        $report_date = date('Y-m', strtotime($report_date1));

        $querypro = "SELECT
        ABS(SUM(stock_detail.quantity)) AS total_percent

        FROM
                stock_detail
        INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
     
        WHERE
                stock_master.transaction_type_id = 2
         AND stock_master.draft = 0        
        AND DATE_FORMAT(
                stock_master.transaction_date,
                '%Y-%m'
        ) = '$report_date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_pack_id
        AND stock_master.from_warehouse_id = $warehouse_id
        AND stock_master.to_warehouse_id = $to_warehouse_id    
      ";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();
        return $result[0]['total_percent'];
    }

    /**
     * Get Issue Voucher List
     * @param type $warehouse_id
     * @param type $to_warehouse_id
     * @param type $month
     * @param type $year
     * @return type
     */
    public function getIssueVoucherList($warehouse_id, $to_warehouse_id, $month, $year) {
        $report_date1 = $year . "-" . $month;
        $report_date = date('Y-m', strtotime($report_date1));

        $querypro = "SELECT
        stock_master.pk_id,
        stock_master.transaction_number,
        stock_master.transaction_date
        FROM
        stock_master
        WHERE
        stock_master.draft = 0 and
        stock_master.transaction_type_id  = 2 and
        stock_master.from_warehouse_id = $warehouse_id
        AND stock_master.to_warehouse_id = $to_warehouse_id
        AND DATE_FORMAT(
        stock_master.transaction_date,
        '%Y-%m'
       ) = '$report_date'";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Issue Voucher List Temp
     * @param type $warehouse_id
     * @param type $to_warehouse_id
     * @param type $month
     * @param type $year
     * @return type
     */
    public function getIssueVoucherListTemp($warehouse_id, $to_warehouse_id, $month, $year) {
        $report_date1 = $year . "-" . $month;
        $report_date = date('Y-m', strtotime($report_date1));

        $querypro = "SELECT
        stock_master.pk_id,
        stock_master.transaction_number,
        stock_master.transaction_date
        FROM
        stock_master
        WHERE
        stock_master.draft = 1 and
        stock_master.from_warehouse_id = $warehouse_id
        AND stock_master.to_warehouse_id = $to_warehouse_id
        AND DATE_FORMAT(
        stock_master.transaction_date,
        '%Y-%m'
       ) = '$report_date'";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Issuance Total
     * @param type $warehouse_id
     * @param type $item_pack_id
     * @param type $month
     * @param type $year
     * @return type
     */
    public function getIssuanceTotal($warehouse_id, $item_pack_id, $month, $year) {


        $report_date1 = $year . "-" . $month;
        $report_date = date('Y-m', strtotime($report_date1));

        $querypro = "SELECT
        ABS(SUM(stock_detail.quantity)) AS total_percent

        FROM
                stock_detail
        INNER JOIN stock_master ON stock_detail.stock_master_id = stock_master.pk_id
        INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
        INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
        INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
        INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
        INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
        INNER JOIN warehouses ON stock_batch_warehouses.warehouse_id = warehouses.pk_id
     
        WHERE
                stock_master.transaction_type_id = 2
         AND stock_master.draft = 0        
        AND DATE_FORMAT(
                stock_master.transaction_date,
                '%Y-%m'
        ) = '$report_date'
        AND stakeholder_item_pack_sizes.item_pack_size_id = $item_pack_id
        AND stock_master.from_warehouse_id = $warehouse_id
          
      ";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();
        return $result[0]['total_percent'];
    }

}

?>