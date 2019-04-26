<?php

/**
 * Model_RoleResources
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Role Resources
 * 
 * Inherits: Model_Base
 */
class Model_RoleResources extends Model_Base {

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

    //constant ALLOW
    const ALLOW = 'ALLOW';
    //constant DENY
    const DENY = 'DENY';

    /**
     * __construct
     * 
     * Constructor for Role Resources
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('RoleResources');
    }

    /**
     * Get Role Resources By Type
     * 
     * Public Function
     * 
     * To get Role Resources By Type
     * 
     * @return type
     */
    public function getRoleResourcesByType() {
        // Get role id.
        $role_id = $this->form_values['role_id'];

        // Get resource id.
        $res_type = $this->form_values['type_id'];

        $qry = $this->_em_read->createQueryBuilder()
                ->select("rr")
                ->from("RoleResources", "rr")
                ->join("rr.resource", "r")
                ->join("rr.role", "role")
                ->where("r.resourceType = " . $res_type);
        if ($role_id == Model_Roles::SUPPLIER) {
            $qry->andWhere("role.pkId IN ($role_id,6)");
        } else {
            $qry->andWhere("role.pkId = " . $role_id);
        }
        $qry->orderBy("r.rank", "ASC");

        return $qry->getQuery()->getResult();
    }

    /**
     * Get Role Resources By Type By Parent
     * 
     * @return type
     */
    public function getRoleResourcesByTypeByParent() {
        $role_id = $this->_identity->getRoleId();
        $qry = $this->_em_read->createQueryBuilder()
                ->select("rr")
                ->from("RoleResources", "rr")
                ->join("rr.resource", "r")
                ->join("rr.role", "role")
                ->where("r.resourceType = " . $this->form_values['type_id'])
                ->andWhere("r.parentId = " . $this->form_values['parent_id'])
                ->andWhere("role.pkId = " . $this->form_values['role_id'])
                ->andWhere("rr.isDefault = 1");
        if ($this->form_values['province_id'] <> 1){
              $qry->andWhere("r.pkId <> 1102");
        }
        if ($role_id == 13) {
            $qry->andWhere("r.pkId <> 500");
            $qry->andWhere("r.pkId <> 646");
        }
        
        $qry->andWhere("r.pkId <> 595");
            $qry->andWhere("r.pkId <> 461");
            $qry->andWhere("r.pkId <> 475");    

        $qry->orderBy("r.rank", "ASC");
        return $qry->getQuery()->getResult();
    }

    /**
     * Get Role Resources
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getRoleResources($order = null, $sort = null) {
        $qry = $this->_em_read->createQueryBuilder()
                ->select("rr")
                ->from("RoleResources", "rr")
                ->join("rr.resource", "r")
                ->join("r.resourceType", "rt");

        if (!empty($this->form_values['role'])) {
            $qry->where("rr.role = " . $this->form_values['role']);
        }

        if (!empty($this->form_values['description'])) {
            $qry->andWhere("r.description = '" . $this->form_values['description'] . "'");
        }

        $qry->andWhere("r.resourceType = 1");

        if ($order == 'resource_name') {
            $qry->orderBy("r.resourceName", $sort);
        }
        if ($order == 'description') {
            $qry->orderBy("r.description", $sort);
        }
        if ($order == 'resource_type') {
            $qry->orderBy("rt.pkId", $sort);
        }
        if ($order == 'level') {
            $qry->orderBy("r.level", $sort);
        }

        return $qry->getQuery()->getResult();
    }

    /**
     * Get Role Resources By Type For Vlmis Dashboard
     * 
     * @return type
     */
    public function getRoleResourcesByTypeForVlmisDashboard() {
        $role_id = $this->form_values['role_id'];
        $res_type = $this->form_values['type_id'];

        $qry = $this->_em_read->createQueryBuilder()
                ->select("rr")
                ->from("RoleResources", "rr")
                ->join("rr.resource", "r")
                ->join("rr.role", "role")
                ->where("r.resourceType = " . $res_type)
                ->andWhere("r.pkId = 472");
        if ($role_id == Model_Roles::SUPPLIER) {
            $qry->andWhere("role.pkId IN ($role_id,6)");
        } else {
            $qry->andWhere("role.pkId = " . $role_id);
        }
        $qry->orderBy("r.rank", "ASC");

        return $qry->getQuery()->getResult();
    }

}
