<?php

/**
 * Form_Iadmin_RoleResource
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Iadmin Role Resource
*/

class Form_Iadmin_RoleResource extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * Form Fields
     * @role: Role Name
     * @resource: Resource Name
     * @permission: Permission
     * 
     * @var type 
     */
    private $_fields = array(
        "role" => "Role Name",
        "resource" => "Resource Name",
        "permission" => "Permission"
    );
    
    /**
     * $_list
     * @var type 
     */
    private $_list = array(
        'role' => array(),
        'resource' => array(),
        'permission' => array(
            'ALLOW' => 'ALLOW',
            'DENY' => 'DENY'
        )
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $roles = new Model_Roles();
        $result = $roles->getRoles();

        if ($result) {
            foreach ($result as $row) {
                $this->_list["role"][$row->getPkId()] = $row->getDescription();
            }
        }

        $resources = new Model_Resources();
        $resources->form_values['resourceType'] = 1;
        $result2 = $resources->getAllResources();
        if ($result2) {
            foreach ($result2 as $row2) {
                $resource = $row2->getResourceName();
                $parentId = $row2->getParentId();
                $parent_resource = Zend_Registry::get("doctrine")->getRepository("Resources")->find($parentId);
                $second_name = (!empty($parent_resource)) ? ucfirst($parent_resource->getDescription()) . " - " : "";
                $this->_list["resource"][$row2->getPkId()] = $second_name . $row2->getDescription();
            }
        }

        foreach ($this->_fields as $col => $name) {
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
