<?php

/**
 * Zend_View_Helper_ExplorerReport
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Explorer Report
 */
class Zend_View_Helper_ExplorerReport extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Explorer Report
     * @return \Zend_View_Helper_ExplorerReport
     */
    public function explorerReport() {
        return $this;
    }

    /**
     * ajaxExplorerReport
     * @param type $stkid
     * @param type $wh_id
     * @param type $yy
     * @param type $mm
     * @return boolean
     */
    public function ajaxExplorerReport($stkid, $wh_id, $yy, $mm) {


        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('DISTINCT ips.itemName as item_name, ips.pkId as pk_id, ips.numberOfDoses as description')
                ->from("ItemPackSizes", "ips")
                ->innerJoin("ips.itemCategory", "ic")
                ->where("ips.status = 1")
                ->andWhere("ic.pkId = 1")
                ->orderBy("ips.listRank", "ASC");

        $rows = $str_sql->getQuery()->getResult();
        if (!empty($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return false;
        }
    }

    /**
     * Get Monthly Receive Quantity Warehouse
     * @param type $mm
     * @param type $yy
     * @param type $item_id
     * @param type $wh_id
     * @return type
     */
    public function getMonthlyReceiveQuantityWarehouse($mm, $yy, $item_id, $wh_id) {

        $row = $this->_em_read->getConnection()->prepare("SELECT getMonthlyRcvQtyWH($mm,$yy,$item_id,$wh_id) as rcv from DUAL");
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get Warehouse Data By Item And WarehouseId
     * @param type $wh_id
     * @param type $item_id
     * @param type $date_sel
     * @return type
     */
    public function getWarehouseDataByItemAndWarehouseId($wh_id, $item_id, $date_sel) {
        $startDate = date('Y-m-01', strtotime($date_sel));
        $endDate = date('Y-m-t', strtotime($date_sel));

        $str_qry = "SELECT
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') < '$startDate', stock_detail.quantity, 0))*item_pack_sizes.number_of_doses AS opening_balance,
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id = 1, stock_detail.quantity, 0))*item_pack_sizes.number_of_doses AS received_balance,
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id = 2, ABS(stock_detail.quantity), 0))*item_pack_sizes.number_of_doses AS issue_balance,
            SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id > 2 AND transaction_types.nature = '+', stock_detail.quantity, 0)) AS vials_used,
            ABS(SUM(IF (DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') >= '$startDate' AND DATE_FORMAT(stock_master.transaction_date, '%Y-%m-%d') <= '$endDate' AND stock_master.transaction_type_id > 2 AND transaction_types.nature = '-', stock_detail.quantity, 0))) AS adjustments,
            SUM(stock_detail.quantity)*item_pack_sizes.number_of_doses AS closing_balance
            FROM
            stock_master
           INNER JOIN transaction_types ON stock_master.transaction_type_id = transaction_types.pk_id
           INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
           INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
           INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
           INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
           INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
           INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
           WHERE
           
            DATE_FORMAT(
             stock_master.transaction_date,
             '%Y-%m-%d'
            ) <= '$endDate' AND
              DATE_FORMAT(
                stock_batch.expiry_date,
                '%Y-%m-%d'
        ) >= '$endDate'  
           AND stock_batch_warehouses.warehouse_id = $wh_id
           AND stock_master.draft = 0    
           AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id" .
                " ORDER BY item_pack_sizes.list_rank";



        $row = $row = $this->_em_read->getConnection()->prepare($str_qry);
        $row->execute();
        $result = $row->fetchAll();

        return $result[0];
    }

}

?>