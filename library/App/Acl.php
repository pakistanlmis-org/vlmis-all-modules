<?php

class App_Acl extends Zend_Acl {

    private $_permissionsList = null;

    public function __construct() {
        $em = Zend_Registry::get("doctrine");
        // roles
        $rolesList = $em->getRepository('Roles')->findAll();

        if ($rolesList !== false) {
            foreach ($rolesList as $key => $value) {
                $role_name = $value->getRoleName();
                $role = new Zend_Acl_Role($role_name);

                switch ($role_name) {
                    case 'dataentry-level4-2':
                        $this->addRole($role, 'dataentry-level4');
                        break;
                    case 'dataentrynew-level6':
                        $this->addRole($role, 'dataentry-level6');
                        break;
                    
                    default :
                        $this->addRole($role);
                        break;
                }
            }
        }

        // resources
        $resourcesList = $em->getRepository('Resources')->findAll();

        if ($resourcesList !== false) {
            foreach ($resourcesList as $key => $value) {
                $resource = new Zend_Acl_Resource($value->getResourceName());
                $this->add($resource);
            }
        }

        // permissions
        $permissionsList = $em->getRepository('RoleResources')->findAll();

        if ($permissionsList !== false) {
            foreach ($permissionsList as $key => $value) {
                if ($value->getPermission() == Model_RoleResources::ALLOW) {
                    $this->allow($value->getRole()->getRoleName(), $value->getResource()->getResourceName());
                } else {
                    $this->deny($value->getRole()->getRoleName(), $value->getResource()->getResourceName());
                }
            }
        }

        $this->allow('admin');
        $this->allow('superadmin');

        $this->_permissionsList = $permissionsList;
    }

}
