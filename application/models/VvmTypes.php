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
 *  Model for VVm Types
 */

class Model_VvmTypes extends Model_Base {

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
        $this->_table = $this->_em->getRepository('VvmTypes');
    }

    /**
     * Get All
     * 
     * @return type
     */
    public function getAll() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('vt.pkId as pk_id, vt.vvmTypeName as vvm_type_name')
                ->from("VvmTypes", "vt")
                ->orderBy("vt.pkId", "DESC");
        return $str_sql->getQuery()->getResult();
    }

    /**
     * Get All Vvm Types
     * 
     * @param type $order
     * @param type $sort
     * @return boolean
     */
    public function getAllVvmTypes($order = null, $sort = null) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("vt.pkId,vt.vvmTypeName,vt.status,u.userName")
                ->from('VvmTypes', 'vt')
                ->join('vt.createdBy', 'u');
        if (!empty($this->form_values['vvmTypeName'])) {
            $str_sql->where("vt.vvmTypeName = '" . $this->form_values['vvmTypeName'] . "'  ");
        }

        if ($this->form_values['status'] == '1') {
            $str_sql->where("vt.status = '1'  ");
        }
        if ($this->form_values['status'] == '2') {
            $str_sql->where("vt.status = '0'  ");
        }
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Check Vvm Types
     * 
     * @return type
     */
    public function checkVvmTypes() {
        $form_values = $this->form_values;
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select('vt.vvmTypeName')
                ->from("VvmTypes", "vt")
                ->where("vt.vvmTypeName= '" . $form_values['vvm_type_name'] . "' ");

        return $str_sql->getQuery()->getResult();
    }

}
