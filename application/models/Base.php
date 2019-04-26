<?php

/**
 * Model_Base
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  This Model is the base for all Models
 */
class Model_Base {

    /**
     * $_identity
     * @var type $_identity
     */
    protected $_identity;

    /**
     * $_em for read/write connection
     * @var type $_em
     */
    protected $_em;

    /**
     * for read only connection
     * @var type 
     */
    protected $_em_read;

    /**
     * $_user_id
     * @var type $_user_id
     */
    protected $_user_id;

    /**
     * $form_values
     * @var type $form_values
     */
    public $form_values;

    CONST INACTIVE = 0;
    CONST ACTIVE = 1;
    CONST ROUITINEPRODUCTS = 1;
    CONST VACCINECATEGORY = 1;
    CONST CAMPAIGNPRODUCTS = 2;
    CONST NONVACCINE = 2;
    CONST INACTIVEVACCINE = 4;
    CONST MERGEDPRODUCTS = 5;
    CONST DILUENT = 3;
    // Placement locations
    CONST LOCATIONTYPE_CCM = 99;
    CONST LOCATIONTYPE_NONCCM = 100;
    CONST PLACEMENT_TRANSACTION_TYPE_P = 114;
    CONST PLACEMENT_TRANSACTION_TYPE_PICK = 115;
    CONST PLACEMENT_TRANSACTION_TYPE_T = 116;
    // Stock batch
    CONST FINISHED = "Finished";
    CONST STACKED = "Stacked";
    CONST RUNNING = "Running";
    CONST EXPIRED = "Expired";
    CONST TOTAL = "Total";
    CONST PRIORITY1 = "Priority1";
    CONST PRIORITY2 = "Priority2";
    CONST PRIORITY3 = "Priority3";
    CONST REVLOGISTICS = 19;
    CONST TRANSACTION_TYPE_STOCK_REQUISITION = 18;

    /**
     * Model_Base init
     */
    function __construct() {
        $this->_identity = App_Auth::getInstance();
        $this->_user_id = $this->_identity->getIdentity();
        $this->_em = Zend_Registry::get('doctrine'); // This is for read/write connection.
        $this->_em_read = Zend_Registry::get('doctrine_read'); // This is for read connection.
    }

}
