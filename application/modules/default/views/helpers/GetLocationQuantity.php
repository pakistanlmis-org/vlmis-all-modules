<?php

/**
 * Zend_View_Helper_GetLocationName
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage default
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Get Location Name
 */
class Zend_View_Helper_GetLocationQuantity extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Get Location Name
     * @param type $location_id
     * @param type $type
     * @return string
     */
    public function getLocationQuantity($location_id, $type,$stock_batch_warehouse_id) {

        if ($type == Model_Placements::LOCATIONTYPE_CCM) {


            $str_sql = "SELECT
        cold_chain.asset_id,
             SUM(placements.quantity) as quantity
    FROM
        placements
    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
    INNER JOIN cold_chain ON placement_locations.location_id = cold_chain.pk_id
    WHERE
        cold_chain.pk_id = $location_id
    AND placement_locations.location_type = 99"
                    . " AND placements.stock_batch_warehouse_id = $stock_batch_warehouse_id";
        } else {

            $str_sql = "SELECT
        non_ccm_locations.location_name as asset_id,
          SUM(placements.quantity) as quantity
    FROM
        placements
    INNER JOIN placement_locations ON placements.placement_location_id = placement_locations.pk_id
    INNER JOIN non_ccm_locations ON placement_locations.location_id = non_ccm_locations.pk_id
    WHERE
        placement_locations.location_type = 100
   AND non_ccm_locations.pk_id = $location_id "
                    . " AND placements.stock_batch_warehouse_id = $stock_batch_warehouse_id";
        }

        $row = $this->_em_read->getConnection()->prepare($str_sql);

        // Get result.
        $row->execute();
        $data = $row->fetchAll();
        if (count($data) > 0) {
            return $data[0]['quantity'];
        } else {
            return '';
        }
    }

}

?>