<?php

/**
 * Zend_View_Helper_MonthlyConsumtion
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Monthly Consumtion
 */
class Zend_View_Helper_EvacMonthlyConsumtion extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Monthly Consumtion
     * Used to get monthly consumption data
     * with respect to warehouse/store id, previous month date
     * and id.
     * @param type $wh_id
     * @param type $prev_month_date
     * @param type $pk_id
     * @return type
     */
    public function evacMonthlyConsumtion($wh_id, $prev_month_date, $pk_id) {


        $querypro = "SELECT
            warehouses.pk_id,
            item_doses.item_pack_size_id,
            SUM(evac_consumption.item_qty) issueBalance
            FROM
            evac_consumption
            INNER JOIN location_mapping ON evac_consumption.uc_code = location_mapping.evac_location_id
            INNER JOIN warehouses ON location_mapping.lmis_location_id = warehouses.location_id
            INNER JOIN item_doses ON evac_consumption.item_id = item_doses.evacc_item_id
            WHERE
            DATE_FORMAT(evac_consumption.reporting_month_date,'%Y-%m') = DATE_FORMAT('$prev_month_date','%Y-%m')
            AND warehouses.pk_id = '$wh_id'
            AND item_doses.item_pack_size_id = '$pk_id'";



        // Prepare query and get result.
        $row = $this->_em_read->getConnection()->prepare($querypro);
        $row->execute();
        $result = $row->fetchAll();

        // Return result.
        return $result[0];
    }

}

?>