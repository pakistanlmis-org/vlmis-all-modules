<?php

/**
 * Model_Roles
 * 
 * 
 * 
 *     Logistics Management Information System for Vaccines
 * @subpackage Inventory Management
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Model for Roles
 */
class Model_Roles extends Model_Base {

    /**
     * $_table
     * @var type 
     */
    protected $_table;

    const CAMPAIGN = 7;
    const INVENTORY = 29;
    const COLDCHAIN = 30;
    const SUPPLIER = 21;

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
        $this->_table = $this->_em->getRepository('Roles');
    }

    /**
     * Get Role By Cat
     * 
     * @param type $cat_id
     * @return type
     */
    public function getRoleByCat($cat_id) {
        return $this->_table->findBy(array("category" => $cat_id));
    }

    /**
     * Get Roles
     * 
     * @param type $order
     * @param type $sort
     * @return type
     */
    public function getRoles($order = null, $sort = null) {
        if (!empty($this->form_values)) {
            return $this->_table->findBy($this->form_values);
        } else {
            $qry = $this->_em_read->createQueryBuilder()
                    ->select("r")
                    ->from("Roles", "r")
                    ->join("r.category", "c");

            if ($order == 'role_name') {
                $qry->orderBy("r.roleName", $sort);
            }
            if ($order == 'description') {
                $qry->orderBy("r.description", $sort);
            }
            if ($order == 'created_date') {
                $qry->orderBy("r.createdDate", $sort);
            }
            if ($order == 'category') {
                $qry->orderBy("c.pkId", $sort);
            }

            return $qry->getQuery()->getResult();
        }
    }

    /**
     * Get All Roles
     * 
     * @return type
     */
    public function getAllRoles() {

        $qry = $this->_em_read->createQueryBuilder()
                ->select("r.pkId,r.roleName")
                ->from("Roles", "r");


        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Roles Resources
     *
     *  @return type
     */
    public function getAllRolesResources() {

        $qry = $this->_em_read->createQueryBuilder()
                ->select("r.pkId,r.roleName")
                ->from("Roles", "r");
        if (!empty($this->form_values['role'])) {
            $qry->where("r.pkId =" . $this->form_values['role']);
        }

        return $qry->getQuery()->getResult();
    }

    /**
     * Get All Roles Resources
     *
     *  @return type
     */
    public function getPolicyRoles() {

        //  $wh_id = $this->_identity->getWarehouseId();

        $form_values = $this->form_values;


        if ($form_values['office_id'] == 1) {
            $pk_id = 17;
        } else if ($form_values['office_id'] == 2) {
            $pk_id = 19;
        } else if ($form_values['office_id'] == 4) {
            $pk_id = 20;
        }
        $str_sql = "SELECT
        roles.pk_id,
        roles.role_name,
        roles.description,
        roles.role_level_id,
        roles.category_id,
        roles.`status`,
        roles.created_by,
        roles.created_date,
        roles.modified_by,
        roles.modified_date
        FROM
        roles

        WHERE
        roles.pk_id = $pk_id";

        $rs = $this->_em_read->getConnection()->prepare($str_sql);
        $rs->execute();
        return $result = $rs->fetchAll();
    }

}
