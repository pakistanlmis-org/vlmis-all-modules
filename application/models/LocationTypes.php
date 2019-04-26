<?php

/**
 * Model_VvmTypes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Location Types
 */

class Model_LocationTypes extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('LocationTypes');
    }

    /**
     * Get All Location Types
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllLocationTypes($order = null, $sort = null) {


        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("lt.pkId,lt.locationTypeName,lt.status,u.userName,gl.geoLevelName")
                ->from('LocationTypes', 'lt')
                ->join('lt.createdBy', 'u')
                ->join('lt.geoLevel', 'gl');
        if (!empty($this->form_values['locationTypeName'])) {
            $str_sql->where("lt.locationTypeName = '" . $this->form_values['locationTypeName'] . "'  ");
        }

        if ($this->form_values['status'] == '1') {
            $str_sql->ANDwhere("lt.status = '1'  ");
        }
        if ($this->form_values['status'] == '2') {
            $str_sql->ANDwhere("lt.status = '0'  ");
        }

        return $str_sql->getQuery()->getResult();
    }

}
