<?php

/**
 * Zend_View_Helper_GetAvailabilityRateStr
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */





/**
 *  Zend View Helper Get Availability Rate Str
 */

class Zend_View_Helper_GetAvailabilityRateStr extends Zend_View_Helper_Abstract {

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
    function getAvailabilityRateStr($in_type, $in_month, $in_year, $in_item, $in_WF, $in_skt, $in_prov, $in_dist) {
        $conn = Zend_Registry::get("doctrine_read");
        return $conn->execute("SELECT REPgetAvailabilityRateStr('" . $in_type . "'," . $in_month . "," . $in_year . ",'" . $in_item . "','" . $in_WF . "'," . $in_skt . "," . $in_prov . "," . $in_dist . ") As Rate from DUAL");
    }

}

?>