<?php

/**
 * Zend_View_Helper_Pipeline
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Pipeline
 */
class Zend_View_Helper_Pipeline extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * pipeline
     * @return \Zend_View_Helper_Pipeline
     */
    public function pipeline() {
        return $this;
    }

    /**
     * Get SOH
     * Used to get stock on hand (SOH).
     * @param type $item_id
     * @param type $wh_id
     * @return type
     */
    public function getSOH($item_id, $wh_id) {

        // Prepare query.
        $str_sql = "SELECT
                            Sum(stock_detail.quantity) AS qty,
                            stakeholder_item_pack_sizes.item_pack_size_id,
                            DATE_FORMAT(
                                    MAX(
                                            stock_master.transaction_date
                                    ),
                                    '%m'
                            ) AS from_month,
                            DATE_FORMAT(
                                    MAX(
                                            stock_master.transaction_date
                                    ),
                                    '%Y'
                            ) AS from_year
                    FROM
                            stock_master
                    INNER JOIN stock_detail ON stock_detail.stock_master_id = stock_master.pk_id
                    INNER JOIN stock_batch_warehouses ON stock_detail.stock_batch_warehouse_id = stock_batch_warehouses.pk_id
                    INNER JOIN stock_batch ON stock_batch.pk_id = stock_batch_warehouses.stock_batch_id
                    INNER JOIN pack_info ON stock_batch.pack_info_id = pack_info.pk_id
                    INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                    INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                    WHERE
                            stock_batch_warehouses.warehouse_id = $wh_id
                    AND stakeholder_item_pack_sizes.item_pack_size_id = $item_id";
        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        
        if (!empty($data[0]['item_pack_size_id'])) {
            return $data;
        }

        return false;
    }

    /**
     * Get Shipments
     * Used to get shipments data.
     * @param type $item_id
     * @param type $wh_id
     * @param type $date
     * @return type
     */
    public function getShipments($item_id, $wh_id, $date) {
        // Prepare query.
        $str_sql = "SELECT
                            SUM(
                                    shipments.shipment_quantity
                            ) AS qty,
                            DATE_FORMAT(
                                    shipments.shipment_date,
                                    '%m/%Y'
                            ) AS ship_date
                    FROM
                            shipments
                    WHERE
                            shipments.warehouse_id = $wh_id
                    AND shipments.item_pack_size_id = $item_id
                    AND shipments.shipment_date >= '$date'
                    GROUP BY
                            DATE_FORMAT(
                                    shipments.shipment_date,
                                    '%m/%Y'
                            )";

        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        return $row->fetchAll();
    }

    /**
     * Get AMC
     * Used to get average monthly consumption.
     * @param type $item_id
     * @param type $wh_id
     * @param type $year
     * @return string
     */
    public function getAMC($item_id, $wh_id, $year) {

        // Prepare query.
        $str_sql = "SELECT
                            epi_amc.amc / 12 AS amc,
                            epi_amc.amc_year
                    FROM
                            epi_amc
                    WHERE
                            epi_amc.item_id = $item_id
                    AND epi_amc.warehouse_id = $wh_id
                    AND epi_amc.amc_year = '$year'
                    GROUP BY
                            epi_amc.amc_year";
        // Execute and get result.
        $row = $this->_em_read->getConnection()->prepare($str_sql);
        $row->execute();
        $data = $row->fetchAll();
        if (count($data) > 0) {
            return $data[0]['amc'];
        } else {
            return '0';
        }
    }

}

?>