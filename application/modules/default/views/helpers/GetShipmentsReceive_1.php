<?php

/**
 * Zend_View_Helper_GetManufacturer
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helpe Get Manufacturer
 */
class Zend_View_Helper_GetManufacturer extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Manufacturer
     * @param type $batch_id
     */
    public function getManufacturer($batch_id) {


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
        AND stock_master.to_warehouse_id = $to_warehouse_id  ";


        $this->_em = Zend_Registry::get('doctrine');
        $row = $this->_em->getConnection()->prepare($querypro);

        $row->execute();
        $result = $row->fetchAll();
        return $result[0]['total_percent'];
    }

}
