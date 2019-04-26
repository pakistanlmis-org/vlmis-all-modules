<?php

/**
 * Model_TransactionTypes
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Transaction Types
 * 
 * Inherits: Model_Base
 */

class Model_TransactionTypes extends Model_Base {

    /**
     * $_table
     * 
     * Protected Variable
     * 
     * Table
     * 
     * @var type 
     */
    protected $_table;

    //constant TRANSACTION_RECIEVE
    const TRANSACTION_RECIEVE = 1;
    
    //constant TRANSACTION_ISSUE
    const TRANSACTION_ISSUE = 2;
    
    //constant LOST RECOVERED
    const LOST_RECOVERED = 8;
    
    //constant PHYSICALLY_FOUND
    const PHYSICALLY_FOUND = 12;
    
    //constant PHYSICALLY_NOT_FOUND
    const PHYSICALLY_NOT_FOUND = 13;
    
    //constant OPENING_BALANCE
    const OPENING_BALANCE = 15;
    
    //constant CHANGE_PURPOSE_POSITIVE
    const CHANGE_PURPOSE_POSITIVE = 16;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('TransactionTypes');
    }

    /**
     * Get Adjusment Types
     * 
     * @return boolean
     */
    public function getAdjusmentTypes() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("tt.transactionTypeName,tt.pkId")
                ->from("TransactionTypes", "tt")
                ->where("tt.isAdjustment > 0 ")
                ->andWhere("tt.status = 1 ");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }
    
    /**
     * To show all types of adjustments in adjustment
     * search page.
     * 
     * @return boolean
     */
    public function getSearchAdjusmentTypes() {
        $str_sql = $this->_em->createQueryBuilder()
                ->select("tt.transactionTypeName,tt.pkId")
                ->from("TransactionTypes", "tt")
                ->where("tt.isAdjustment > 0 ")
                ->andWhere("tt.status IN (0,1) ")
                ->andWhere("tt.pkId NOT IN (16,17) ");
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Find By Id
     * 
     * @param type $id
     * @return boolean
     */
    public function findById($id = 0) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("t.nature")
                ->from("TransactionTypes", "t")
                ->where("t.pkId = $id")
                ->setMaxResults(1);

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Find All
     * 
     * @return boolean
     */
    public function findAll() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("t.pkId,t.transactionTypeName")
                ->from("TransactionTypes", "t")
                ->where("t.isAdjustment = 1");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All
     * 
     * @return boolean
     */
    public function getAll() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("t.pkId,t.transactionTypeName,t.nature")
                ->from("TransactionTypes", "t");

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get All Transaction Types
     * 
     * @param type $order
     * @param type $sort
     * @return boolean
     */
    public function getAllTransactionTypes($order = null, $sort = null) {


        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("t.pkId,t.transactionTypeName,t.nature,t.status,u.userName")
                ->from('TransactionTypes', 't')
                ->join('t.createdBy', 'u');
        if (!empty($this->form_values['transactionTypeName'])) {
            $str_sql->where("t.transactionTypeName = '" . $this->form_values['transactionTypeName'] . "'  ");
        }

        if ($this->form_values['nature'] == '+') {
            $str_sql->where("t.nature = '+'  ");
        }
        if ($this->form_values['nature'] == '-') {
            $str_sql->where("t.nature = '-'  ");
        }
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

}
