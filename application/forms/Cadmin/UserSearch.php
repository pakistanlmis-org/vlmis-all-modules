<?php

/**
 * Form_Cadmin_UserSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Cadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Cadmin User Search
 */
class Form_Cadmin_UserSearch extends Form_Base {

    /**
     * $_fields
     * 
     * Private Variable
     * 
     * @var type 
     */
    private $_fields = array(
        "login_id" => "Username",
        "role" => "User Role"
    );

    /**
     * $_list
     * 
     * Private VAriable
     * 
     * @var type 
     */
    private $_list = array(
        "role" => array()
    );

    /**
     * Initializes Form Fields
     */
    public function init() {

        $roles = new Model_Roles();
        $result = $roles->getRoleByCat(Model_Roles::COLDCHAIN);

        foreach ($result as $role) {
            $this->_list["role"][''] = 'Select';
            $this->_list["role"][$role->getPkId()] = $role->getRoleName();
        }

        foreach ($this->_fields as $col => $name) {
            if ($col == "login_id") {
                parent::createText($col);
            }

            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

}
