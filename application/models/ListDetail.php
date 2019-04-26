<?php

/**
 * Model_ListDetail
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for List Detail
 * 
 * Inherits: Model_Base
 */

class Model_ListDetail extends Model_Base {

    /**
     * $_table
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_table;

    //constant NON_CCM
    const NON_CCM = 100;
    
    //constant STOCK_PLACEMENT
    const STOCK_PLACEMENT = 114;
    
    //constant AGE_0_11
    const AGE_0_11 = 160;
    
    //constant AGE_12_13
    const AGE_12_23 = 161;
    
    //constant AGE_24
    const AGE_24 = 162;
    
    //constant WAREHOUSE_STATUS_FUNCTIONING
    const WAREHOUSE_STATUS_FUNCTIONING = 163;
    
    //constant WAREHOUSE_STATUS_NONFUNCTIONING
    const WAREHOUSE_STATUS_NONFUNCTIONING = 164;
    
    //constant WAREHOUSE_STATUS_REPORTING
    const WAREHOUSE_STATUS_REPORTING = 165;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('ListDetail');
    }

    /**
     * Get Fuel Types
     * 
     * @return boolean
     */
    public function getFuelTypes() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ld.listValue,ld.pkId")
                ->from('ListDetail', 'ld')
                ->join('ld.listMaster', 'lm')
                ->where('lm.pkId=' . $this->form_values['fuel_type_id']);
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get List Detail By Master Id
     * 
     * @return boolean
     */
    public function getListDetailByMasterId() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("ld.listValue,ld.pkId, lm.pkId as master_id")
                ->from('ListDetail', 'ld')
                ->join('ld.listMaster', 'lm')
                ->where('lm.pkId=' . $this->form_values['master_id']);
        $row = $str_sql->getQuery()->getResult();

        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get List Detail
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getListDetail($order = null, $sort = null) {
        if (!empty($this->form_values) && empty($order)) {
            return $this->_table->findBy($this->form_values);
        } else {
            $qry = $this->_em_read->createQueryBuilder()
                    ->select("ld")
                    ->from("ListDetail", "ld")
                    ->join("ld.listMaster", "lm")
                    ->join("ld.createdBy", "cb");

            if (!empty($this->form_values['listMaster'])) {
                $qry->where("lm.pkId = '" . $this->form_values['listMaster'] . "' ");
            }
            if (!empty($this->form_values['listValue'])) {
                $qry->where("ld.listValue = '" . $this->form_values['listValue'] . "' ");
            }

            if ($order == 'list_value') {
                $qry->orderBy("ld.listValue", $sort);
            } else if ($order == 'list_master') {
                $qry->orderBy("lm.pkId", $sort);
            } else if ($order == 'created_by') {
                $qry->orderBy("cb.pkId", $sort);
            } else if ($order == 'created_date') {
                $qry->orderBy("ld.createdDate", $sort);
            } else {
                $qry->orderBy("lm.pkId", 'ASC');
            }

            return $qry->getQuery()->getResult();
        }
    }

}
