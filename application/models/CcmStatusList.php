<?php

/**
 * Model_CcmStatusList
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Cold Chain
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for CCM Status List
 * 
 * Inherits: Model_Base
 */

class Model_CcmStatusList extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    //constanr WORKINGWELL
    const WORKINGWELL = 1;
    
    //constant NEEDSMAINTENANCE
    const NEEDSMAINTENANCE = 2;
    
    //constant NOTWORKING
    const NOTWORKING = 3;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CcmStatusList');
    }

    /**
     * Get Status Lists
     * 
     * @return boolean
     */
    public function getStatusLists() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("csl.pkId,csl.ccmStatusListName")
                ->from('CcmStatusList', 'csl')
                ->where("csl.type =" . Model_CcmStatusList::WORKINGWELL . "");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Reasons
     * 
     * @return boolean
     */
    public function getAllReasons() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("csl.pkId,csl.ccmStatusListName")
                ->from('CcmStatusList', 'csl')
                ->where("csl.type = " . Model_CcmStatusList::NEEDSMAINTENANCE . "");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Utilizations
     * 
     * @return boolean
     */
    public function getAllUtilizations() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("csl.pkId,csl.ccmStatusListName")
                ->from('CcmStatusList', 'csl')
                ->where("csl.type = " . Model_CcmStatusList::NOTWORKING . "");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Status Lists
     * 
     * @param type $order
     * @param type $sort
     * @return boolean
     */
    public function getAllStatusLists($order = null, $sort = null) {

        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("sl.pkId,sl.ccmStatusListName,sl.type,sl.status,u.userName")
                ->from('CcmStatusList', 'sl')
                ->join('sl.createdBy', 'u');
        if (!empty($this->form_values['ccmStatusListName'])) {
            $str_sql->where("sl.ccmStatusListName = '" . $this->form_values['ccmStatusListName'] . "'  ");
        }

        if ($this->form_values['status'] == '1') {
            $str_sql->where("sl.status = '1'  ");
        }
        if ($this->form_values['status'] == '2') {
            $str_sql->where("sl.status = '0'  ");
        }

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
