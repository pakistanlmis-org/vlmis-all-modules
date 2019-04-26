<?php

/**
 * Model_CcmAssetTypes
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Rack Information
 */

class Model_RackInformation extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('RackInformation');
    }

    /**
     * Get Rack Information
     * 
     * @return boolean
     */
    public function getRackInformation() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ri.pkId,ri.rackType")
                ->from('RackInformation', 'ri');
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
