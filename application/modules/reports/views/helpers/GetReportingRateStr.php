<?php

/**
 * Zend_View_Helper_GetReportingRateStr
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage reports
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */




/**
 *  Zend View Helper Get Reporting Rate Str
 */

class Zend_View_Helper_GetReportingRateStr extends Zend_View_Helper_Abstract {

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
        $conn = Zend_Registry::get("conn");
        return $conn->execute("SELECT REPgetReportingRateStr('" . $in_type . "'," . $in_month . "," . $in_year . ",'" . $in_item . "','" . $in_WF . "'," . $in_skt . "," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
    }

}

?>