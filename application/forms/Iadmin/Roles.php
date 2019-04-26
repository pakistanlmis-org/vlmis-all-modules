<?php

/**
 * Form_Iadmin_Roles
 *
 * 
 *
 *     Logistics Management Information System for Vaccines
 * @subpackage Iadmin
 * @author     Ajmal Hussain <ajmal@deliver-pk.org>
 * @version    2.5.1
 */

/**
 *  Form for Iadmin Roles
 */
class Form_Iadmin_Roles extends Form_Base {

    /**
     * Fields for Form_Iadmin_Roles
     * 
     * 
     * 
     * role_name
     * description
     * category_id
     * status
     * 
     * 
     * 
     * $_fields
     * @var type 
     */
    private $_fields = array(
        "role_name" => "UserName",
        "description" => "UserName",
        "category_id" => "Category",
        "status" => "Status"
    );

    /**
     * Combo boxes 
     * for Form_Iadmin_Roles
     * 
     * 
     * 
     * category_id
     * status
     * 
     * 
     * 
     * $_list
     * @var type 
     */
    private $_list = array(
        'category_id' => array(),
        'status' => array(
            '1' => 'Active',
            '0' => 'Deactive'
        )
    );

    /**
     * Initializes Form Fields
     * for Form_Iadmin_Roles
     */
    public function init() {

        $list_detail = new Model_ListDetail();
        $list_detail->form_values = array('master_id' => Model_ListMaster::USER_ROLE_CATEGORIES);
        $result = $list_detail->getListDetailByMasterId();
        $this->_list["category_id"][''] = "Select";
        foreach ($result as $rs) {
            $this->_list["category_id"][$rs['pkId']] = $rs['listValue'];
        }

        // Generate fields
        // for Form_Iadmin_Roles
        foreach ($this->_fields as $col => $name) {
            switch ($col) {
                case "role_name":
                case "description":
                    parent::createText($col);
                    break;
                default:
                    break;
            }

            //Generate combo boxes
            //for Form_Iadmin_Roles
            if (in_array($col, array_keys($this->_list))) {
                parent::createSelect($col, $this->_list[$col]);
            }
        }
    }

    /**
     * Add Hidden Fields
     * for Form_Iadmin_Roles
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }

}
