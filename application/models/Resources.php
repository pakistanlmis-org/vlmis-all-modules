<?php

/**
 * Model_Resources
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Resources
 * 
 * Inherits: Model_Base
 */
class Model_Resources extends Model_Base {

    /**
     * $_table
     * 
     * Public Variable
     * 
     * @var type 
     */
    protected $_table;

    /**
     * __construct
     * 
     * Constructor for Resources
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Resources');
    }

    /**
     * Get Resources
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getResources($order = null, $sort = null) {
        if (!empty($this->form_values['only_childs'])) {
            $only_childs = $this->form_values['only_childs'];
            unset($this->form_values['only_childs']);
        }

        if (!empty($this->form_values)) {
            return $this->_table->findBy($this->form_values);
        } else {
            $qry = $this->_em_read->createQueryBuilder()
                    ->select("r")
                    ->from("Resources", "r")
                    ->join("r.resourceType", "rt")
                    ->where("rt.pkId IN (1,3)");
            if (!empty($only_childs)) {
                $qry->andWhere("r.parentId <> 0");
            }

            if ($order == 'resource_name') {
                $qry->orderBy("r.resourceName", $sort);
            }
            if ($order == 'description') {
                $qry->orderBy("r.description", $sort);
            }
            if ($order == 'resource-type') {
                $qry->orderBy("rt.pkId", $sort);
            }
            if ($order == 'level') {
                $qry->orderBy("r.level", $sort);
            }
            return $qry->getQuery()->getResult();
        }
    }

    /**
     * Get All Resources
     * 
     * @return type
     */
    public function getAllResources() {

        $qry = $this->_em_read->createQueryBuilder()
                ->select("r")
                ->from("Resources", "r");
        if (!empty($this->form_values['resourceName'])) {
            $qry->where("r.resourceName= '" . $this->form_values['resourceName'] . "' ");
        }
        if (!empty($this->form_values['resourceType'])) {
            $qry->andWhere("r.resourceType=" . $this->form_values['resourceType']);
        }
        $qry->orderBy("r.resourceName", "ASC");
        $qry->addOrderBy("r.description", "ASC");


        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Resources
     * 
     * @return type
     */
    public function getAllMainResources() {

        $qry = $this->_em_read->createQueryBuilder()
                ->select("r")
                ->from("Resources", "r")
                ->where("r.parentId IS NULL OR r.parentId = 0")
                ->andWhere("r.resourceType <> 2");
        if (!empty($this->form_values['resourceName'])) {
            $qry->where("r.resourceName= '" . $this->form_values['resourceName'] . "' ");
        }
        if (!empty($this->form_values['resourceType'])) {
            $qry->andWhere("r.resourceType=" . $this->form_values['resourceType']);
        }
        $qry->orderBy("r.resourceName", "ASC");
        $qry->addOrderBy("r.description", "ASC");


        return $qry->getQuery()->getResult();
    }

}
