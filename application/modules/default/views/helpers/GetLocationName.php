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

class Zend_View_Helper_GetLocationName extends Zend_View_Helper_Abstract {
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
    public function getLocationName($location_id, $type) {

        if ($type == Model_Placements::LOCATIONTYPE_CCM) {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('loc.assetId as location_name')
                    ->from("ColdChain", "loc")
                    ->where("loc.pkId = $location_id");
        } else {
            $str_sql = $this->_em_read->createQueryBuilder()
                    ->select('loc.locationName as location_name')
                    ->from("NonCcmLocations", "loc")
                    ->where("loc.pkId = $location_id");
        }

        $row = $str_sql->getQuery()->getResult();
        if (count($row) > 0) {
            return $row[0]['location_name'];
        } else {
            return '';
        }
    }

}

?>