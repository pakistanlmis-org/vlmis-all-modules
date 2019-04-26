<?php

/**
 * Form_Iadmin_RoleComboSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Role Combo Search
 */
class Form_Iadmin_RoleComboSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @role: Role Name
     * @description: Description
     * @resource_name: Resource Name
     * @resource_type: Resource Type
     * 
     * @var type 
     */
    private $_fields = array(
        "role" => "Role name",
        "description" => "Description",
        "resource_name" => "Resource name",
        "resource_type" => "Resource type"
    );

    /**
     * $_list
     * 
     * Private Variable
     * 
     * List
     * 
     * @var type 
     */
    private $_list = array(
        'role' => array()
    );

    /**
     * Initializes Form Fields
     * 
     * Public Function
     */
    public function init() {

        $roles = new Model_Roles();
        $result = $roles->getRoles();
        $action = Zend_Registry::get("action");

        if ($action == 'role-resources') {
            $this->_list["role"][""] = 'Select';
        }
        if ($result) {
            foreach ($result as $row) {
                $this->_list["role"][$row->getPkId()] = $row->getDescription();
            }
        }
        $em = Zend_Registry::get("doctrine");

        $result = $em->getRepository("ResourceTypes")->findAll();
        $this->_list["resource_type"][''] = "Select";
        foreach ($result as $rs) {
            $this->_list["resource_type"][$rs->getPkId()] = $rs->getResourceType();
        }


        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "description":
                case "resource_name":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
