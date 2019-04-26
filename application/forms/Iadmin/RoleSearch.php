<?php

/**
 * Form_Iadmin_RoleSearch
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
*  Form for Iadmin Role Search
*/

class Form_Iadmin_RoleSearch extends Form_Base {

    /**
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "role_name" => "Role name",
        "description" => "Description"
    );
  
    /**
     * Initializes Form Fields
     */
    public function init() {
        
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "role_name":
                case "description":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
        }
    }

}
