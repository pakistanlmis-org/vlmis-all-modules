<?php

/**
 * Model_PhysicalStockTakingDetail
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Campaigns
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Physical Stock Taking Detail
 */

class Model_PhysicalStockTakingDetail extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    const STOCKID = 3;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('PhysicalStockTakingDetail');
    }

}
