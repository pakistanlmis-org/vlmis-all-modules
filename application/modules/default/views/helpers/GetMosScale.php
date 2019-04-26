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
class Zend_View_Helper_GetMosScale extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

   

    public function getMosScale($item_id) {
        
         $stock_master = new Model_StockMaster();
        $result = $stock_master->getMosScale($item_id);

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
       
    }

}

?>