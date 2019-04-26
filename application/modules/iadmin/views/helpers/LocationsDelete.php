<?php

/**
 * Zend_View_Helper_LocationsDelete
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Zend View Helper Locations Delete
 */
class Zend_View_Helper_LocationsDelete extends Zend_View_Helper_Abstract {

    protected $_em_read;

    public function __construct() {
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * Locations Delete
     * @param type $location_id
     * @return type
     */
    public function locationsDelete($location_id) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("w.pkId")
                ->from('Warehouses', 'w')
                ->join('w.location', 'l')
                ->where("l.pkId =" . $location_id);


        return $str_sql->getQuery()->getResult();
    }

}

?>