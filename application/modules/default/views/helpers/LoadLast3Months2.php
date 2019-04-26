<?php

/**
 * Zend_View_Helper_LoadLast3Months2
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Load Last 3 Months 2
 */
class Zend_View_Helper_LoadLast3Months2 extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * LoadLast 3 Months 2
     * @return \Zend_View_Helper_LoadLast3Months2
     */
    public function loadLast3Months2() {
        return $this;
    }

    /**
     * Load Reported Months
     * @param type $wh_id
     * @param type $loc_id
     * @param type $update
     * @return type
     */
    public function loadReportedMonths($wh_id, $loc_id, $update = '') {

        // Init report instance.
        $reports = new Model_Reports();
        // Init array.
        $reports->form_values = array("wh_id" => $wh_id, 'loc_id' => $loc_id);

        // Validate.
        if (isset($update) && !empty($update)) {
            $result = $reports->last3monthsUpdate2();
        } else {
            $result = $reports->last3months2();
        }

        return $result;
    }

    /**
     * Load Last Reported Date
     * To get last report date
     * @param type $wh_id
     * @return string
     */
    public function loadLastReportedDate($wh_id) {
        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id);

        $max_date = $reports->getLastCreatedDate2();
        if (!empty($max_date)) {
            return date("d/m/Y", strtotime($max_date));
        } else {
            return '-';
        }
    }

    /**
     * Used to load log months.
     * @param type $wh_id
     * @param type $loc_id
     * @param type $update
     * @return type
     */
    public function loadLogMonths($wh_id, $loc_id, $update = '') {

        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id, 'loc_id' => $loc_id);

        if (isset($update) && !empty($update)) {
            $result = $reports->last3monthsLogUpdate2();
        } else {
            $result = $reports->lastLog3months2();
        }

        return $result;
    }

    /**
     * Used to load last log reported date.
     * @param type $wh_id
     * @return string
     */
    public function loadLastLogReportedDate($wh_id) {
        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id);

        $max_date = $reports->getLastLogCreatedDate2();
        if (!empty($max_date)) {
            return date("d/m/Y", strtotime($max_date));
        } else {
            return '-';
        }
    }

    /**
     * Used to get warehouses names assets
     * teshil status.
     * @param type $wh_id
     * @return string
     */
    public function getWarehouseNamesAssetTehsilStatus($wh_id) {

        // Prepare query.
        $querypro = "SELECT
                            w.pk_id,
                            w.warehouse_name,
                            MAX(csh.status_date) as status_date
                    FROM
                            warehouses w
                    LEFT JOIN cold_chain c ON c.warehouse_id = w.pk_id
                    LEFT JOIN ccm_status_history csh ON c.ccm_status_history_id = csh.pk_id
                    INNER JOIN warehouse_users wu ON w.pk_id = wu.warehouse_id
                    WHERE
                            wu.warehouse_id = $wh_id
                            and w.status = 1
                    GROUP BY w.warehouse_name";
        $this->_em_read = Zend_Registry::get('doctrine_read');
        $row = $this->_em_read->getConnection()->prepare($querypro);

        // execute and get result.
        $row->execute();
        $result = $row->fetchAll();
        $max_date = $result['0']['status_date'];
        // Check result.
        if (!empty($max_date)) {
            return date("d/m/Y", strtotime($max_date));
        } else {
            return '-';
        }
    }
      public function getWarehouseNamesAssetTehsilStatusColdChain($wh_id) {

        // Prepare query.
        $querypro = "SELECT
                            w.pk_id,
                            w.warehouse_name,
                            MAX(csh.status_date) as status_date
                    FROM
                            warehouses w
                    LEFT JOIN cold_chain c ON c.warehouse_id = w.pk_id
                    LEFT JOIN ccm_status_history csh ON c.ccm_status_history_id = csh.pk_id
                    INNER JOIN warehouse_users wu ON w.pk_id = wu.warehouse_id
                    WHERE
                            wu.warehouse_id = $wh_id
                            and w.status = 1
                    GROUP BY w.warehouse_name";
        $this->_em_read = Zend_Registry::get('doctrine_read');
        $row = $this->_em_read->getConnection()->prepare($querypro);

        // execute and get result.
        $row->execute();
        $result = $row->fetchAll();
        $max_date = $result['0']['status_date'];
        // Check result.
        if (!empty($max_date)) {
            return date("d/m/Y", strtotime($max_date));
        } else {
            return '-';
        }
    }
    
    
      /**
     * Load Reported Months
     * @param type $wh_id
     * @param type $loc_id
     * @param type $update
     * @return type
     */
    public function loadColdChainMonths($wh_id, $loc_id, $update = '') {

        // Init report instance.
        $reports = new Model_Reports();
        // Init array.
        $reports->form_values = array("wh_id" => $wh_id, 'loc_id' => $loc_id);

        // Validate.
        if (isset($update) && !empty($update)) {
            $result = $reports->last3monthsUpdateColdChain();
        } else {
            $result = $reports->last3monthsColdChain();
        
       
            
        }

        return $result;
    }

}

?>