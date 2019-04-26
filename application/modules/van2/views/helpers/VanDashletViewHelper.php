<?php

/**
 * VanDashletViewHelper
 *
 *
 *
 *     Logistics Management Information System for Vaccines
 * @package van
 * @author     Muhammad Imran
 * @version    2.5.1
 */

/**
 *  Van Dashlet View Helper
 */
class Zend_View_Helper_VanDashletViewHelper extends Zend_View_Helper_Abstract {

    protected $_em;
    protected $_em_read;

    public function __construct() {
        $this->_em = Zend_Registry::get('doctrine');
        $this->_em_read = Zend_Registry::get('doctrine_read');
    }

    /**
     * VanDashletViewHelper
     */
    public function VanDashletViewHelper() {
        return $this;
    }

}

?>