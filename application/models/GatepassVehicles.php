<?php

/**
 * Model_CcmMakes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Gatepass Vehicles
 */

class Model_GatepassVehicles extends Model_Base {

    /**
     *$_table
     * @var type 
     */
    private $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('GatepassVehicles');
    }

    /**
     * Get Vehicle Number By Type
     * 
     * @return type
     */
    public function getVehicleNumberByType() {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('gpv.pkId', 'gpv.number')
                ->from("GatepassVehicles", "gpv")
                ->where("gpv.vehicleType = " . $this->form_values['vehicle_type_id']);

        return $str_sql->getQuery()->getResult();
    }

}
