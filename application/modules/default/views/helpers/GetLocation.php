<?php

/**
 * Zend_View_Helper_GetLocation
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helpe Get Location
 */
class Zend_View_Helper_GetLocation extends Zend_View_Helper_Abstract {
    protected $_em;
    protected $_em_read;
    
    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Location
     * @param type $detail_id
     * @param type $cat_id
     * @return string
     */
    public function getLocation($detail_id, $cat_id) {

        // Check category.
        if ($cat_id == 1) {
            $qry = "SELECT
        cold_chain.asset_id
    FROM
        placements
    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
    WHERE
        placements.stock_detail_id = $detail_id
    AND placement_locations.location_type = 99";
        } else {
            $qry = "SELECT
        non_ccm_locations.location_name as asset_id
    FROM
        placements
    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
    INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
    WHERE
        placement_locations.location_type = 100
AND placements.stock_detail_id = $detail_id";
        }

        $row = $this->_em_read->getConnection()->prepare($qry);

        // Get result.
        $row->execute();
        $data = $row->fetchAll();

        if (count($data) > 0) {
            return $data[0]['asset_id'];
        } else {
            return '';
        }
    }

}

?>