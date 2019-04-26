<?php

/**
 * Model_ItemPackSizes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for List Masters
 * 
 * Inherits: Model_Base
 */
class Model_ListMaster extends Model_Base {

    /**
     * $_table
     * 
     * Public Variable
     * 
     * @var type 
     */
    private $_table;

    const TEMPERATURE_RECORDING_SYSTEM = 1;
    const TYPE_OF_RECORDING_SYSTEM = 2;
    const REFRRIGERATOR_GAS_TYPE = 3;
    const HAS_WORKING_BACKUP_GENERATOR = 4;
    const FUEL_TYPE = 5;
    const USE_FOR = 6;
    const USER_ROLE_CATEGORIES = 8;
    const VACCINATION_STAFF = 9;
    const SOLAR_ENERGY = 10;
    const BARCODE_TYPE = 12;
    const EXPIRY_DATE_FORMAT = 13;
    const AREA = 14;
    const ROW = 15;
    const RACK = 16;
    const RACK_TYPE = 17;
    const PALLET = 18;
    const LEVEL = 19;
    const GRID_ELECTRICITY_AVAL = 20;
    const VEHICLE_TYPE = 21;
    const LOCATION_TTPE = 22;
    const VACCINE_SUPPLY_MODE = 24;
    const SERVICE_TYPES = 25;
    const TEMPERATURE_MONITOR = 28;
    const REFRIGERANT_GAS_TYPE = 3;
    const POWER_SOURCE = 29;
    const PACKAGING_LEVEL = 30;
    const TYPE_CASE = 38;
    const OUTCOME = 41;
    const LABRESULT = 42;
    const FINALCLASS = 43;
     const WEEK = 33;
     const AEFI = 35;
const PLACE_OF_BIRTH = 47;
const BIRTH_ATTENDEE=48;
    /**
     * __construct
     * 
     * Constructor for ListMaster
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('ListMaster');
    }

    /*
     * Get List Detail By Type.
     * 
     * Function Name :  getListDetailByType
     * Input         :  List Category Name (list_master_name of list_master table)
     * Output        :  It will return list detail entries according to input type/category
     */

    public function getListDetailByType() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('ld.pkId, ld.listValue')
                ->from('ListDetail', 'ld')
                ->innerJoin("ld.listMaster", "lm")
                ->where("lm.pkId = " . $this->form_values['pk_id']);
        if (isset($this->form_values['order'])) {
            $str_sql->orderBy('ld.listValue', $this->form_values['order']);
        } else {
            $str_sql->orderBy('ld.listValue', "ASC");
        }
         
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Master List
     * 
     * @return type
     */
    public function getMasterList() {
        return $this->_table->findAll();
    }

}
