<?php

/**
 * Zend_View_Helper_LoadLast3Months
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Load Last 3 Months
 */

class Zend_View_Helper_LoadLast3Months extends Zend_View_Helper_Abstract {

    /**
     * Load Last 3 Months
     * @return \Zend_View_Helper_LoadLast3Months
     */
    public function loadLast3Months() {
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

        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id, 'loc_id' => $loc_id);

        if (isset($update) && !empty($update)) {
            $result = $reports->last3monthsUpdate();
        } else {
            $result = $reports->last3months();
        }

        return $result;
    }

    /**
     * Load Last Reported Date
     * @param type $wh_id
     * @return string
     */
    public function loadLastReportedDate($wh_id) {
        $reports = new Model_Reports();
        $reports->form_values = array("wh_id" => $wh_id);

        $max_date = $reports->getLastCreatedDate();
        if (!empty($max_date)) {
            return date("d/m/Y", strtotime($max_date));
        } else {
            return '-';
        }
    }

}

?>