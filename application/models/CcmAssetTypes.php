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
 *  Model for CCM Asset Types
 * 
 * Inherits: Model_Base
 */
class Model_CcmAssetTypes extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    private $_table;

    //constant Refrigerator
    const REFRIGERATOR = 1;
    
    //constant VACCINECARRIER
    const VACCINECARRIER = 2;
    
    //constant COLDROOM
    const COLDROOM = 3;
    
    //constant ICEPACKS
    const ICEPACKS = 4;
    
    //constant VOLTAGEREGULATOR
    const VOLTAGEREGULATOR = 5;
    
    //constant GENERATOR
    const GENERATOR = 6;
    
    //constant TRANSPORT
    const TRANSPORT = 7;
    
    //constant SUBCOLDROOM
    const SUBCOLDROOM = 36;
    
    //constant SUBFREEZERROOM
    const SUBFREEZERROOM = 37;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('CcmAssetTypes');
    }

    /**
     * Get Asset Sub Types
     * @return boolean
     * 
     */
    public function getAssetSubTypes() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("a.pkId,a.assetTypeName")
                ->from('CcmAssetTypes', 'a');
        if ($this->form_values['parent_id'] == 'childs') {
            $str_sql->where('a.parent <> 0');
        } else if (!empty($this->form_values['parent_id'])) {
            $str_sql->where('a.parent = ' . $this->form_values['parent_id']);
        } else {
            $str_sql->where('a.parent =0');
        }
        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get Asset Types
     * @return boolean
     */
    public function getAssetTypes() {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("a.pkId,a.assetTypeName")
                ->from('CcmAssetTypes', 'a')
                ->where('a.pkId IN (1,3)')
                ->orderBy('a.pkId','DESC');

        $row = $str_sql->getQuery()->getResult();
        if (!empty($row) && count($row) > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get all asset sub types
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getAllAssetSubTypes($order = null, $sort = null) {
        $str_sql = $this->_em_read->createQueryBuilder()
                ->select("a.pkId,a.status,a.assetTypeName,u.userName,p.assetTypeName as assetType")
                ->from('CcmAssetTypes', 'a')
                ->join('a.createdBy', 'u')
                ->join('a.parent', 'p')
                ->where('p.pkId <> 0 ');

        if ($order == 'asset_sub_type') {
            $str_sql->orderBy("a.assetTypeName", $sort);
        }

        if ($order == 'created_by') {
            $str_sql->orderBy("a.createdBy", $sort);
        }

        $str_qry = "SELECT
        c0_.pk_id AS pkId,
        c0_. STATUS AS status,
        c0_.asset_type_name AS assetTypeName,
        u1_.user_name AS userName,
        c2_.asset_type_name AS assetType
        FROM
                ccm_asset_types c0_
        INNER JOIN users u1_ ON c0_.created_by = u1_.pk_id
        INNER JOIN ccm_asset_types c2_ ON c0_.parent_id = c2_.pk_id
        WHERE
                c2_.pk_id <> 0";
        if (!empty($this->form_values['assetSubType'])) {
            $str_qry .= ' AND c0_.asset_type_name LIKE "%' . $this->form_values['assetSubType'] . '%"';
        }

        $str_qry .= " ORDER BY
                c0_.asset_type_name ASC";
        $row = $this->_em_read->getConnection()->prepare($str_qry);

        $row->execute();
        return $row->fetchAll();
    }

}
